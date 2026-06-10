<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use App\Models\Branch; // Pastikan model Branch sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLogin()
    {
        return view('login.login');
    }

    /**
     * Menampilkan halaman register
     */
    public function showRegister()
    {
        return view('register.register');
    }

    /**
     * Memproses pendaftaran user baru (Owner & Admin)
     */
    public function registerProcess(Request $request)
    {
        // 1. Validasi Input Form
        $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|max:100|unique:user,username', 
            'password'    => 'required|string|min:8|confirmed',
            'role'        => 'required|in:owner,admin',
            'store_name'  => 'required_if:role,owner|nullable|string|max:255',
            'branch_code' => 'required_if:role,admin|nullable|string', // Validasi token admin
        ], [
            'email.unique'           => 'Email ini sudah terdaftar.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'store_name.required_if' => 'Nama toko wajib diisi untuk pendaftaran Owner.',
            'branch_code.required_if'=> 'Kode cabang wajib diisi untuk pendaftaran Admin.',
        ]);

        // 2. Mapping nilai role dari form ke level_id tabel database
        // Asumsi: level_id 1 = Owner, level_id 2 = Admin
        $levelId = ($request->role === 'owner') ? 1 : 2;
        $branchId = null;

        // 3. Pengecekan Token Cabang Khusus untuk Admin
        if ($request->role === 'admin') {
            // Mencari data cabang berdasarkan branch_token yang diketik user
            $branch = Branch::where('branch_code', $request->branch_code)->first();

            // Jika token salah atau tidak ada di database
            if (!$branch) {
                return back()->withErrors(['branch_code' => 'Kode akses cabang tidak sah atau tidak ditemukan!'])->withInput();
            }

            // Jika token valid, simpan ID cabangnya. 
            // (Catatan: Ubah $branch->branch_id menjadi $branch->id jika primary key di tabel branches-mu adalah 'id')
            $branchId = $branch->branch_id; 
        }

        // 4. Gunakan Database Transaction demi keamanan data transaksional
        DB::beginTransaction();

        try {
            // Simpan ke tabel user
            $user = User::create([
                'level_id'  => $levelId,
                'username'  => $request->email, // Menyimpan input email ke kolom username
                'nama'      => $request->name,  // Menyimpan input name ke kolom nama
                'password'  => Hash::make($request->password),
                'branch_id' => $branchId,       // Menyimpan relasi ID Cabang (null jika owner)
            ]);

            // Jika mendaftar sebagai owner, buat data toko pendukung
            if ($request->role === 'owner') {
                Store::create([
                    'user_id'    => $user->user_id,
                    'store_name' => $request->store_name,
                ]);
            }

            DB::commit();

            // Otomatis login setelah berhasil mendaftar
            Auth::login($user);

            // Redireksi sesuai peran
            if ($user->level_id == 1) {
                return redirect()->route('owner.dashboard')->with('success', 'Selamat Datang di Salesight!');
            } else {
                // Admin langsung masuk ke halaman dashboard admin
                return redirect()->route('admin.dashboard')->with('success', 'Registrasi Admin berhasil!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal melakukan registrasi: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Memproses autentikasi / login user
     */
    public function loginProcess(Request $request)
    {
        // 1. Validasi input login
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Sesuaikan kredensial karena Laravel autentikasi default mencari kolom 'email', 
        // sedangkan database menggunakan kolom 'username'
        $authData = [
            'username' => $credentials['email'],
            'password' => $credentials['password']
        ];

        // 3. Proses Attempt Login
        if (Auth::attempt($authData)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Pengondisian hak akses redireksi setelah login sukses
            if ($user->level_id == 1) {
                return redirect()->intended('/owner/dashboard');
            }

            // Jika level_id adalah admin/data entry, arahkan ke rute admin nanti
            return redirect()->intended('/admin/dashboard');
        }

        // Jika otentikasi gagal, kembalikan dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses keluar sistem (Logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard'); // Ganti dengan tampilan dashboard Anda
    }
}
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Menangani proses login
    public function login(Request $request)
    {
        // Validasi data yang dimasukkan
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            // Jika berhasil, redirect ke halaman dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Jika login gagal, tampilkan pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }
}

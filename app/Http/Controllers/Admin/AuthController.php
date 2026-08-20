<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            session([
                'admin_logged_in' => true,
                'admin_id' => $admin->id,
                'admin_username' => $admin->username,
                'admin_name' => $admin->name ?? $admin->username,
                'admin_role' => $admin->role ?? 'admin',
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, ' . ($admin->name ?? $admin->username) . '!');
        }

        return back()->withInput($request->only('username'))->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_id', 'admin_username', 'admin_name', 'admin_role']);
        session()->flush();
        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
    }
}

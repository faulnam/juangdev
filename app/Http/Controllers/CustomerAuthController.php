<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    /**
     * Show Customer Login Page.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('customer.dashboard');
        }

        return view('pages.auth.login');
    }

    /**
     * Handle Customer Login Request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember', true);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil! Selamat datang kembali, ' . $user->name,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ],
                    'redirect' => session()->pull('url.intended', route('customer.dashboard')),
                ]);
            }

            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password yang Anda masukkan salah.',
            ], 422);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Show Customer Register Page.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('customer.dashboard');
        }

        return view('pages.auth.register');
    }

    /**
     * Handle Customer Register Request (Instant Without OTP).
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|min:9|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan login.',
            'phone.required' => 'Nomor WhatsApp / HP asli wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Sanitize phone number (Indonesia format)
        $phone = preg_replace('/[^0-9]/', '', $validated['phone']);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $phone,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        // Auto-login customer after registration
        Auth::login($user, true);
        $request->session()->regenerate();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil! Selamat datang di JuangDev.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
                'redirect' => session()->pull('url.intended', route('customer.dashboard')),
            ]);
        }

        return redirect()->route('customer.dashboard')
            ->with('success', 'Akun Anda berhasil dibuat. Selamat datang di JuangDev!');
    }

    /**
     * Handle Google Firebase Single Sign-On / Register.
     */
    public function firebaseGoogleAuth(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'uid' => 'required|string',
            'avatar' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $user = User::where('firebase_uid', $validated['uid'])
            ->orWhere('email', $validated['email'])
            ->first();

        if ($user) {
            // Update firebase_uid and avatar if not set
            $user->firebase_uid = $validated['uid'];
            if (!empty($validated['avatar']) && empty($user->avatar)) {
                $user->avatar = $validated['avatar'];
            }
            if (!empty($validated['phone']) && empty($user->phone)) {
                $user->phone = $validated['phone'];
            }
            $user->save();
        } else {
            // Create new customer account via Google
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'firebase_uid' => $validated['uid'],
                'avatar' => $validated['avatar'] ?? null,
                'password' => Hash::make(Str::random(32)),
                'role' => 'customer',
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Login Google berhasil! Selamat datang, ' . $user->name,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
            ],
            'redirect' => session()->pull('url.intended', route('customer.dashboard')),
        ]);
    }

    /**
     * Handle Customer Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Находим пользователя по логину
        $user = \App\Models\User::where('login', $request->login)->first();

        // Проверяем пользователя и пароль
        if ($user && Hash::check($request->password, $user->password_hash)) {
            
            // ПРОСТАЯ АУТЕНТИФИКАЦИЯ ЧЕРЕЗ СЕССИЮ
            session(['user_id' => $user->id]);
            session(['user_name' => $user->name]);
            session(['user_login' => $user->login]);
            
            // Проверяем запись в сессию
            \Log::info('Session auth', [
                'session_user_id' => session('user_id'),
                'session_id' => session()->getId()
            ]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'login' => 'Неверный логин или пароль.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        // Очищаем сессию
        session()->forget(['user_id', 'user_name', 'user_login']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
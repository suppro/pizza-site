<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:User,phone',
            'email' => 'nullable|email|max:100|unique:User,email',
            'login' => 'required|string|max:50|unique:User,login',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'nullable|string|max:200'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Создаем пользователя
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'login' => $request->login,
            'password_hash' => Hash::make($request->password),
            'address' => $request->address,
            'role_id' => 2 // Клиент
        ]);

        // Автоматически входим после регистрации
        session(['user_id' => $user->id]);
        session(['user_name' => $user->name]);
        session(['user_login' => $user->login]);

        return redirect()->route('dashboard')->with('success', 'Регистрация успешна! Добро пожаловать!');
    }
}
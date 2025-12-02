<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $query = User::query();

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Фильтр по роли
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,client',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно создан!');
    }

    public function edit($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $editUser = User::findOrFail($id);
        return view('admin.users.edit', compact('editUser'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $editUser = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,client',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $editUser->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно обновлен!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        // Нельзя удалить самого себя
        if ($user->id == $id) {
            return back()->withErrors(['error' => 'Нельзя удалить свой собственный аккаунт']);
        }

        $deleteUser = User::findOrFail($id);
        $deleteUser->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно удален!');
    }
}

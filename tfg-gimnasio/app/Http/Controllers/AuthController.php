<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin() {
        $users = User::all();
        return view('auth.login', compact('users'));
    }

    public function loginAs(User $user) {
        Auth::login($user);
        return redirect('/actividades')->with('success', 'Logueado como ' . $user->name);
    }

    public function logout() {
        Auth::logout();
        return redirect('/actividades');
    }
}
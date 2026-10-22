<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create()
    {
        return view('admin.auth.login');
    }

    public function store(LoginRequest $r)
    {
        if (! Auth::attempt($r->safe()->only(['email', 'password']), $r->boolean('remember'))) {
            return back()->withErrors(['email' => 'Kredensial tidak cocok.'])->onlyInput('email');
        } $r->session()->regenerate();
        if ($r->user()->role !== 'admin') {
            Auth::logout();
            abort(403);
        }

return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

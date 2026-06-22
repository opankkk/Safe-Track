<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            
            $role = Auth::user()->role;
            if ($role === 'hse') {
                return redirect()->intended(route('hse.dashboard'));
            } elseif ($role === 'manager') {
                return redirect()->intended(route('hse.manager.dashboard'));
            } elseif ($role === 'pic') {
                return redirect()->intended(route('pic.dashboard'));
            }

            return redirect()->intended(route('user.login')); // Fallback
        }

        $this->addError('email', 'Kredensial tidak valid. Silakan coba lagi.');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->extends('layouts.app')
            ->section('content');
    }
}

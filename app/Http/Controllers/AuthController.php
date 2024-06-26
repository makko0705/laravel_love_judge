<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'line_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'line_name' => $request->line_name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // ここでパスワードを設定
            ]);

            Log::info('User registered successfully: ', ['user' => $user]);

            return redirect('/')->with('success', 'ユーザー登録が完了しました');
        } catch (\Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'ユーザー登録に失敗しました');
        }
    }
}

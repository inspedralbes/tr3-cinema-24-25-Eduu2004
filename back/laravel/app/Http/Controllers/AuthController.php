<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'phone'     => 'nullable|string',
            'recaptcha_token' => 'required|string',  // Verifica el token de reCAPTCHA
        ]);

        // Verificar el token de reCAPTCHA con Google
        $recaptchaResponse = $request->recaptcha_token;
        $secretKey = '6LfFjwArAAAAAHydYaAM6oQ3Go64G71jvTHMfm7Z';

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $secretKey,
            'response' => $recaptchaResponse,
        ]);

        $responseBody = $response->json();

        if (!$responseBody['success']) {
            return response()->json(['message' => 'reCAPTCHA validation failed'], 400);
        }

        // Si la validación reCAPTCHA es exitosa, proceder con el registro
        $user = User::create([
            'name'      => $validated['name'],
            'lastname'  => $validated['lastname'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'phone'     => $validated['phone'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 201);
    }

    // Login de usuario
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'recaptcha_token' => 'required|string',  // Verifica el token de reCAPTCHA
        ]);

        // Verificar el token de reCAPTCHA con Google
        $recaptchaResponse = $request->recaptcha_token;
        $secretKey = '6LfFjwArAAAAAHydYaAM6oQ3Go64G71jvTHMfm7Z';

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $secretKey,
            'response' => $recaptchaResponse,
        ]);

        $responseBody = $response->json();

        if (!$responseBody['success']) {
            return response()->json(['message' => 'reCAPTCHA validation failed'], 400);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }
}

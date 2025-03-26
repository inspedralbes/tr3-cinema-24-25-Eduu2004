<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string',
                'recaptcha_token' => 'required'
            ]);

            // Validar reCAPTCHA
            $recaptchaResponse = $this->validateRecaptcha($validated['recaptcha_token'], $request->ip());
            if (!$recaptchaResponse['success']) {
                Log::warning('reCAPTCHA validation failed', [
                    'errors' => $recaptchaResponse['error-codes'] ?? [],
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'message' => 'Verificación de seguridad fallida',
                    'errors' => ['recaptcha' => 'Error en la verificación de seguridad']
                ], 422);
            }

            $user = User::create([
                'name' => $validated['name'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Registro exitoso',
                'data' => [
                    'user' => $user->makeHidden(['password']),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    // Login de usuario
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'recaptcha_token' => 'required'
            ]);

            // Validar reCAPTCHA
            $recaptchaResponse = $this->validateRecaptcha($request->recaptcha_token, $request->ip());
            if (!$recaptchaResponse['success']) {
                Log::warning('reCAPTCHA validation failed on login', [
                    'errors' => $recaptchaResponse['error-codes'] ?? [],
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'message' => 'Verificación de seguridad fallida',
                    'errors' => ['recaptcha' => 'Error en la verificación de seguridad']
                ], 422);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Credenciales inválidas'
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'data' => [
                    'user' => $user->makeHidden(['password']),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    private function validateRecaptcha($token, $remoteIp)
    {
        try {
            $client = new Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => config('services.recaptcha.secret'),
                    'response' => $token,
                    'remoteip' => $remoteIp
                ],
                'timeout' => 5 // Tiempo máximo de espera en segundos
            ]);

            $responseData = json_decode((string)$response->getBody(), true);

            // Validación adicional para desarrollo local
            if (app()->environment('local')) {
                $responseData['success'] = true;
                $responseData['hostname'] = 'localhost';
            }

            // Verificar hostname en producción
            if (app()->environment('production') && $responseData['hostname'] !== parse_url(config('app.url'), PHP_URL_HOST)) {
                $responseData['success'] = false;
                $responseData['error-codes'] = ['invalid-hostname'];
            }

            return $responseData;

        } catch (RequestException $e) {
            Log::error('Error al verificar reCAPTCHA: ' . $e->getMessage());
            return ['success' => false, 'error-codes' => ['connection-failed']];
        }
    }
}
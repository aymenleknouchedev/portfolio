<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FirebaseAuthController extends Controller
{
    public function callback(Request $request): JsonResponse
    {
        $request->validate(['id_token' => ['required', 'string']]);

        $apiKey = config('services.firebase.api_key');

        $response = Http::asJson()->post(
            "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}",
            ['idToken' => $request->input('id_token')]
        );

        if (!$response->successful() || empty($response->json('users.0'))) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        $fbUser = $response->json('users.0');
        $email = $fbUser['email'] ?? null;
        $name = $fbUser['displayName'] ?? ($email ? Str::before($email, '@') : 'User');
        $emailVerified = $fbUser['emailVerified'] ?? false;

        if (!$email) {
            return response()->json(['message' => 'No email on Google account.'], 422);
        }

        $user = User::firstOrNew(['email' => $email]);

        if (!$user->exists) {
            $user->name = $name;
            $user->password = Hash::make(Str::random(40));
            $user->role = 'client';
        }

        if ($emailVerified && !$user->email_verified_at) {
            $user->email_verified_at = now();
        }

        $user->save();

        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'redirect' => AuthenticatedSessionController::dashboardFor($user),
        ]);
    }
}

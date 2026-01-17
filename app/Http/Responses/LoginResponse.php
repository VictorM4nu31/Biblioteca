<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Determine where to redirect the user after login based on their role.
     */
    public function toResponse($request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $redirectTo = $this->redirectPathForUser($user);

        if ($request->wantsJson()) {
            return response()->json([
                'two_factor' => false,
                'redirect' => $redirectTo,
            ]);
        }

        return redirect()->intended($redirectTo);
    }

    /**
     * Get redirect path according to user roles.
     */
    protected function redirectPathForUser($user): string
    {
        // Ajusta los nombres de roles según tu configuración de Spatie
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('admin')) {
                return route('admin.dashboard');
            }

            // Puedes manejar otros roles específicos aquí si los tienes
            if ($user->hasRole('moderator')) {
                // Ejemplo: un dashboard distinto para moderadores
                return route('admin.dashboard');
            }
        }

        // Usuarios normales
        if (function_exists('route') && \Illuminate\Support\Facades\Route::has('user.dashboard')) {
            return route('user.dashboard');
        }

        // Fallback a la home pública
        return route('home');
    }
}


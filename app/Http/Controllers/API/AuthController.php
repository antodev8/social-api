<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\AuthLoginResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
     /**
     * Attempt login
     *
     * @param AuthLoginRequest $request
     * @return AuthLoginResource
     * @throws ValidationException
     */
    public function login(AuthLoginRequest $request): AuthLoginResource
    {
        $user = User::where('email',$request->email)->firstOrFail(); // Se non trova nessuna corrispondenza restituisce un errore automaticamente

        if(!Hash::check($request->password, $user->password)) {

            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);

            }


        return new AuthLoginResource($user);


    }
}

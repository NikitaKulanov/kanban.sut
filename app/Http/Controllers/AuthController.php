<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function login(): \Illuminate\Http\JsonResponse|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        if(! auth()->attempt($this->request->only('email', 'password'))){
            return response()->json(['message' => 'Wrong login or password!'], 422);
        }
        $this->request->session()->regenerate();

        return Auth::user();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($this->request->all(), [
            'email' => ['bail', 'required', 'string', 'email', 'max:255', 'unique:users'],
            'surname' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255', 'alpha'],
            'patronymic' => ['required', 'string', 'max:255', 'alpha'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['string', 'min:11'],
            'consent_to_privacy_policy' => ['accepted'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'Registration data was not successfully verified!',
                    'failed' => $validator->failed()
                ],
                422
            );
        }

        $userData = $validator->validated();

        $session = $this->request->session();

        $user = User::create([
            'surname' => $userData['surname'],
            'name' => $userData['name'],
            'patronymic' => $userData['patronymic'],
            'email' => $userData['email'],
            'password' => $userData['password'],
            'phone' => array_key_exists('phone', $userData) ? $userData['phone'] : null
        ]);

        Auth::guard('web')->login($user);
        $session->regenerate();

        return response()->json($user->refresh(), 201);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth()->guard('web')->logout();

        $this->request->session()->invalidate();

        $this->request->session()->regenerateToken();

        return response()->json(null, 204);
    }
}

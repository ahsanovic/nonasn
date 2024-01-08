<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ], [
            'username.required' => 'username harus diisi',
            'password.required' => 'password harus diisi'
        ]);

        $data = [
            'grant_type' => 'password',
            'client-id' => config('services.passport.client_id'),
            'client-secret' => config('services.passport.client_secret'),
            'username' => $request->username,
            'password' => $request->password
        ];

        // $request->request->add([
        //     'grant_type' => 'password',
        //     'client_id' => config('services.passport.client_id'),
        //     'client_secret' => config('services.passport.client_secret'),
        //     'username' => $request->username,
        //     'password' => $request->password,
        //   ]);

        // $httpResponse = app()->handle(Request::create('/oauth/token', 'post', $data));
        // $result = json_decode($httpResponse->getContent());
        // $tokenRequest = Request::create('/oauth/token', 'post');
        // $response = Route::dispatch($tokenRequest);

        $request = app('request')->create('/oauth/token', 'POST', $data);
        $response = app('router')->prepareResponse($request, app()->handle($request));

        // if ($httpResponse->getStatusCode() !== 200) {
        //     return response()->json([
        //         'message' => 'salah'
        //     ]);
        // }
        
        // return response()->json($result);
        return $response;
    }
}

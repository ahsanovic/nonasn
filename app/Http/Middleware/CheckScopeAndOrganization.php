<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;

class CheckScopeAndOrganization
{   
    public function handle(Request $request, Closure $next, $scope)
    {
         // Ambil token dari header Authorization
        $token = $request->bearerToken();

        if (!$token) {
            throw new AuthenticationException('unauthenticated.');
        }

        // Decode token untuk mendapatkan token ID
        try {
            $parser = new \Lcobucci\JWT\Parser();
            $tokenId = $parser->parse($token)->getClaim('jti');
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "code" => 400,
                "message" => "Invalid token format"
            ], 400);
        }

        // Ambil token model dari database berdasarkan token hashed
        $tokenModel = Token::find($tokenId);

        if (!$tokenModel) {
            return response()->json([
                "status" => "error",
                "code" => 403,
                "message" => "Token not found"
            ], 403);
        }

        // Cek apakah token telah dibatalkan atau kadaluarsa
        if ($tokenModel->revoked || $tokenModel->expires_at < now()) {
            return response()->json([
                "status" => "error",
                "code" => 403,
                "message" => "Token is revoked or expired"
            ], 403);
        }

        // Periksa scope
        if (!$tokenModel->can($scope)) {
            return response()->json([
                "status" => "error",
                "code" => 403,
                "message" => "unauthorized scope",
            ], 403);
        }

        // Ambil client berdasarkan token client_id
        $client = Client::find($tokenModel->client_id);

        // Log::info($client);

        if (!$client) {
            return response()->json([
                "status" => "error",
                "code" => 403,
                "message" => "client not found"
            ], 403);
        }

        // Masukkan organization_id ke dalam request untuk digunakan nanti di controller
        $request->attributes->set('organization_id', $client->skpd_id);

        return $next($request);
    }
}

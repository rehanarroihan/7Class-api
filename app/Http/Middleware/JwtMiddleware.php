<?php

namespace App\Http\Middleware;

use App\Models\Users;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Bearer not provided',
                'data' => null
            ], 401);
        }
        $token = explode(' ', $token);

        if (($token[0] !== 'Bearer') || !$token[1]) {
            return response()->json([
                'status' => false,
                'message' => 'Bearer not provided',
                'data' => null
            ], 401);
        }

        try {
            $credentials = JWT::decode($token[1], env('JWT_KEY'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Bearer expired'
            ], 400);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error with bearer',
                'data' => null
            ], 400);
        }

        $users = Users::where('email', $credentials->sub->id_google)->where('id', $credentials->sub->id)->first();

        if (!$users) {
            return response()->json([
                'status' => false,
                'message' => 'User with bearer not match',
                'data' => null
            ], 400);
        }

        return $next($request);
    }
}

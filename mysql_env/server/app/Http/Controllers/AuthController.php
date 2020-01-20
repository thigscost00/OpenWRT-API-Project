<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Router;

class AuthController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }*/

    public function register(Request $request) {
        $router = Router::create([
            'mac' => $request->mac
        ]);

        $token = auth('api')->login($router);

        return $this->respondWithToken($token);
    }

    public function config(Request $request) {
        $config['iface'] = "test";
        $config['ifname'] = "eth1";
        $config['ip'] = "192.168.2.7";
        $config['mask'] = "255.255.255.0";
        $config['proto'] = "static";

        return json_encode($config);
    }

    public function me() {
        return response()->json(auth('api')->user());
    }

    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }
}

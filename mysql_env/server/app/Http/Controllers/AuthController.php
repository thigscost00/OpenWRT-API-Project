<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Router;
use App\RoutConf;
use Storage;
use ZipArchive;

class AuthController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }*/

    public function register(Request $request) {
        $mac = $request->input('mac');

        if (Router::where('mac', $mac)->exists()) {
            $router = Router::where('mac', $mac)->first();
            RoutConf::where('router_id', $router->id)->delete();

            $rout_id = $router->id;
            $cf_ids = $request->input('cf');

            foreach ($cf_ids as $cid) {
                RoutConf::create([
                    'router_id' => $rout_id,
                    'configuration_id' => $cid
                ]);
            }
            //$token = auth()->tokenById($id);
            //return $this->respondWithToken($token);
        } else {
            $router = Router::create([
                'mac' => $mac
            ]);

            $rout_id = $router->id;
            $cf_ids = $request->input('cf');

            foreach ($cf_ids as $cid) {
                RoutConf::create([
                    'router_id' => $rout_id,
                    'configuration_id' => $cid
                ]);
            }

            //$token = auth('api')->login($router);
            //return $this->respondWithToken($token);
        }

        $allrout = Router::all();
        return view('showrout', ['routers' => $allrout]);
    }

    public function config(Request $request) {
        $router = auth()->user();

        if (!is_null($router)) {
            $configs = DB::table('rout_confs')
                        ->join('routers', 'router_id', '=', 'routers.id')
                        ->join('configurations', 'configuration_id', '=', 'configurations.id')
                        ->select('configurations.name')
                        ->where('router_id', $router->id)
                        ->get();

            $zip = new ZipArchive;
            $zip_name = public_path() . "/config.zip";

            if ($zip->open($zip_name, ZipArchive::CREATE) === true) {
                foreach ($configs as $cf) {
                    //$cf_file = Storage::disk('local')->get($cf->name);

                    $cf_path = storage_path('app') . "/$cf->name.json";
                    $zip->addFile($cf_path, "$cf->name");
                }

                if ($zip->close() === false) {
                    echo "Arquivo Zip não pode ser criado";
                }
            } else {
                var_dump($zip);
            }

            return response()->download($zip_name);
        } else {
            return "{}";
        }
    }

    public function refresh(Request $request) {
        $mac = $request->input('mac');

        if (Router::where('mac', $mac)->exists()) {
            $router = Router::where('mac', $mac)->first();
            $token = auth()->login($router);

            return $this->respondWithToken($token);
        } else {
            return "{}";
        }
        //$token = auth()->refresh(true, true);

        //return $this->respondWithToken($token);
    }

    public function me() {
        return response()->json(auth('api')->user());
    }

    public function list() {
        $allrout = Router::all();
        return view('showrout', ['routers' => $allrout]);
    }

    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }
}

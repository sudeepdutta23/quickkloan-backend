<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

use App\Models\Constants;

use App\Http\Utils\{ResponseHandler, Roles};

use Exception;

use Auth;

use Closure;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

use App\Exceptions\GlobalException as GlobalException;

class setHeader
{
    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    // Enumerate unwanted headers
    private $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
        'Platform'
    ];


    public function handle(Request $request, Closure $next)
    {

        try {
        
            $response = $next($request);    
            if(!empty($request->route()->getName()) && $request->route()->getName() != 'exportAllDocFiles' && $request->isExport == null)
            {
                $api_path = explode("/",$request->path());
                $cache = ($api_path[0] == 'api') ? 'no-cache' : 'public, max-age=31536000';
                
                $response->header('Cache-Control', $cache);
                $response->header('X-Frame-Options', 'DENY');
                $response->header('Server', 'DENY');
                $response->header('Platform', 'DENY');  
            }
            return $response;
        } catch (\Exception $e) {            
        
            throw new GlobalException;
        }

    }

    private function removeUnwantedHeaders($headerList)
    {
        foreach ($headerList as $header)
            header_remove($header);
    }
}

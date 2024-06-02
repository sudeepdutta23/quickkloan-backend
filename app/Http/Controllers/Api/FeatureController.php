<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GlobalException as GlobalException;
use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;
use App\Models\{
    Constants,
    FeatureToggle,    
};

use App\Http\Resources\FeatureResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Auth;
use Config;
use Illuminate\Support\Facades\Cache;
class FeatureController extends Controller
{

    private $const;
    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }


    
    public function getAllFeature(Request $request)
    {
        try {

            $getAllFeature = FeatureToggle::select('id','description','toggle_id','isActive')
            ->get();
           

            return (new ResponseHandler)->sendSuccessResponse(new FeatureResource($getAllFeature)) ?? [];

        } catch (Exception $e) {
            
            throw new GlobalException;

        }
    }

    public function updateCache(Request $request){

        $fetch_all_features = FeatureToggle::get();
        $updateCache = Cache::forever('features', $fetch_all_features);

        if($updateCache)

            return (new ResponseHandler)->sendSuccessResponse(['message'=>$this->const['CACHE_UPDATE']], 200);

    }

        

}

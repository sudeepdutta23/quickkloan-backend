<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

use App\Models\Constants;

use App\Http\Utils\{ResponseHandler, Roles};

use Exception;

use Auth;

use Closure;

use Config;

use App\Models\{    
    FeatureToggle
};

use Illuminate\Support\Facades\Cache;

class FeatureCheck
{
    private $const,$logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function handle(Request $request, Closure $next)
    {



            if($request->route()->getPrefix() == 'api/v1/blog'){
                    
                $blog_feature = Config::get('customConfig.Features')[0];
       
                $fetch_all_features = Cache::get('features');

                // dd($fetch_all_features);

                foreach($fetch_all_features as $features){  
                    
                    if($features->isActive == 1 && $features->id == $blog_feature['id'] && $features->description == $blog_feature['description']){
                        return $next($request);                    
                    }else
                    {
                        return (new ResponseHandler)->sendErrorResponse(['message'=>$this->const['ACCESS_DENIED']], 403);
                    }
                }
            }
            

            
    }
}

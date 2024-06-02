<?php

use App\Models\{
    Constants,
    LeadIndividualMapping
};
use App\Exceptions\GlobalException as GlobalException;

use App\Models\{
    BlogImage
};

function __construct()
{
    $const = (new Constants)->getConstants();
}

function randomNumber()
{
    $digits = '';
    $length = 4;
    $numbers = range(1, 9);
    shuffle($numbers);
    for ($i = 0; $i < $length; $i++) {
        $digits .= $numbers[$i];
    }

    return $digits;
}

function checkAppliedId($leadId,$individualId){

        $checkAppliedId = LeadIndividualMapping::where('individual_id', $individualId)
        ->where('lead_id', $leadId)
        ->where('is_active', 1)
        ->where('individual_applied_id','!=',null)
        ->first();

        return !empty($checkAppliedId);

    }

function checkAllCosignerConsentGiven($request){

    $checkAllCosignerConsentGiven = LeadIndividualMapping::join('individual','individual.id','=','lead_individual_mapping.individualId')
    ->where('leadId', $request->leadId)
    ->where('lead_individual_mapping.isActive', 1)
    ->where('lead_individual_mapping.consentGiven', 0)
    ->where('lead_individual_mapping.individualType', "Cosigner")
    ->select('individualId','consentGiven')->get();

    return $checkAllCosignerConsentGiven->count() == 0;

}


function resizeImage($request,$image=null){


    try{


            $laptop_dimension = Config::get('customConfig.laptop_dimension');
            $mobile_dimension = Config::get('customConfig.mobile_dimension');                       
        

            // for loan product
            if($request->route()->getName() == 'addLoanProduct')
            {
                
                $image_intervention = Image::make($request->file('icon'));
                $image = $request->file('icon');
                    
                $originalName = pathinfo(str_replace(' ', '', $image->getClientOriginalName()), PATHINFO_FILENAME);
                $ImageExtension = $image->getClientOriginalExtension();

                $productImage = $originalName.'.'.$ImageExtension;

                $assetlaptopImagePath = asset(env('PRODUCT')) . '/' . $productImage;
                $publiclaptopImagePath = public_path(env('PRODUCT') .'/'.$productImage);  
            
                $assetMobileImagePath = asset(env('PRODUCT')) . '/'.$originalName.'_'.$mobile_dimension['width'].'x'.$mobile_dimension['height'].'.'.$ImageExtension;                    
                $publicMobileImagePath = public_path(env('PRODUCT') .'/'.$originalName.'_'.$mobile_dimension['width'].'x'.$mobile_dimension['height'].'.'.$ImageExtension);  
                            
                $image_intervention->resize($laptop_dimension['width'],$laptop_dimension['height']);
                $image_intervention->save($publiclaptopImagePath);
                        
                $image_intervention->resize($mobile_dimension['width'],$mobile_dimension['height']);
                $image_intervention->save($publicMobileImagePath);

                return $assetlaptopImagePath;

            }
        
            // for loan carousel image
            if($request->route()->getName() == 'addCarouselImage')
            {        
                $image_intervention = Image::make($request->file('image'));
                $image = $request->file('image');
                
                $originalName = pathinfo(str_replace(' ', '', $image->getClientOriginalName()), PATHINFO_FILENAME);
                $ImageExtension = $image->getClientOriginalExtension();

                $productImage = $originalName.'.'.$ImageExtension;

                $assetlaptopImagePath = asset(env('CAROUSEL')) . '/' . $productImage;
                $publiclaptopImagePath = public_path(env('CAROUSEL') .'/'.$productImage);  
            
                $assetMobileImagePath = asset(env('CAROUSEL')) . '/'.$originalName.'_'.$mobile_dimension['width'].'x'.$mobile_dimension['height'].'.'.$ImageExtension;                    
                $publicMobileImagePath = public_path(env('CAROUSEL') .'/'.$originalName.'_'.$mobile_dimension['width'].'x'.$mobile_dimension['height'].'.'.$ImageExtension);  
            
                
                $image_intervention->resize($laptop_dimension['width'],$laptop_dimension['height']);
                $image_intervention->save($publiclaptopImagePath);

                        
                $image_intervention->resize($mobile_dimension['width'],$mobile_dimension['height']);
                $image_intervention->save($publicMobileImagePath);

                return $assetlaptopImagePath;


            }

            // // for blog image
            // if($request->route()->getName() == 'addBlog')
            // {       
            //         $blog_laptop_dimension = Config::get('customConfig.blog_laptop_dimension');
            //         $blog_mobile_dimension = Config::get('customConfig.blog_mobile_dimension');     
                                    
            //         $image_intervention = Image::make($image);

            //         $originalName = pathinfo(str_replace(' ', '', $image->getClientOriginalName()), PATHINFO_FILENAME);
            //         $ImageExtension = $image->getClientOriginalExtension();  
            //         $productImage = $originalName.'.'.$ImageExtension;

            //         $assetlaptopImagePath = asset(env('BLOG')).'/'.$productImage;

            //         $publiclaptopImagePath = public_path(env('BLOG')).'/'.$productImage; 
                
            //         $assetMobileImagePath = asset(env('BLOG')) . '/'.$originalName.'_'.$blog_mobile_dimension['width'].'x'.$blog_mobile_dimension['height'].'.'.$ImageExtension;        
                     
            //         $publicMobileImagePath = public_path(env('BLOG')) .'/'.$originalName.'_'.$blog_mobile_dimension['width'].'x'.$blog_mobile_dimension['height'].'.'.$ImageExtension;  

                 
            //         $image_intervention->resize($blog_laptop_dimension['width'],$blog_laptop_dimension['height']);
            //         $image_intervention->save($publiclaptopImagePath);
                            
            //         $image_intervention->resize($blog_mobile_dimension['width'],$blog_mobile_dimension['height']);
            //         $image_intervention->save($publicMobileImagePath);

            //         return $assetlaptopImagePath;                  

            // }

    }
    catch(\Exception $e){
        return response()->json(['error object'=>$e]);
    }
    

}

function fetchAppendImage($val){

    try{

        
        $laptop_dimension = Config::get('customConfig.laptop_dimension');
        $mobile_dimension = Config::get('customConfig.mobile_dimension'); 
        $pathinfo = pathinfo(!empty($val->icon) ? $val->icon : $val->image);
        $fullPath = $pathinfo['dirname'].'/'.$pathinfo['filename']; 
                       
        $suffix = '_'.$mobile_dimension['width'].'x'.$mobile_dimension['height'];  
        $new_file = $fullPath.$suffix.'.'.$pathinfo['extension'];

        if(!empty($val->icon)){
            $val->icon = $new_file;
        }
        else
        {
            $val->image = $new_file;
        }

    }
    catch (\Exception $e){
        
        throw new GlobalException;
    }

}

function fetchAppendBlogImage($val){

    try{

        
        $blog_laptop_dimension = Config::get('customConfig.blog_laptop_dimension');
        $blog_mobile_dimension = Config::get('customConfig.blog_mobile_dimension'); 
        
        foreach($val as $image){

            if(!empty($image)){

                $pathinfo = pathinfo($image->media);
                $fullPath = $pathinfo['dirname'].'/'.$pathinfo['filename'];
                $suffix = '_'.$blog_mobile_dimension['width'].'x'.$blog_mobile_dimension['height'];  
                $new_file = $fullPath.$suffix.'.'.$pathinfo['extension'];        
                $image->media = $new_file;   
                
            }

    

        }
       
    }
    catch (\Exception $e){

        throw new GlobalException;
    }

}

function resizeTestimonialImage($request){

    $testimonial_dimension = Config::get('customConfig.testimonial_dimension');

    $image_intervention = Image::make($request->file('customerImage'));

    $customerImageFile = $request->file('customerImage');
        
    $originalName = pathinfo(str_replace(' ', '', $customerImageFile->getClientOriginalName()), PATHINFO_FILENAME);
    $ImageExtension = $customerImageFile->getClientOriginalExtension();

    $productImage = $originalName.'.'.$ImageExtension;

    $assetlaptopImagePath = asset(env('TESTIMONIAL')) . '/' . $productImage;
    $publiclaptopImagePath = public_path(env('TESTIMONIAL') .'/'.$productImage);  
                
    $image_intervention->resize($testimonial_dimension['width'],$testimonial_dimension['width']);
    $image_intervention->save($publiclaptopImagePath);  

    return $assetlaptopImagePath;

}


function resizeBlogImage($request,$image=null){


    try{


        $blog_laptop_dimension = Config::get('customConfig.blog_laptop_dimension');
        $blog_mobile_dimension = Config::get('customConfig.blog_mobile_dimension');                             
               

            // for blog image
            if($request->route()->getName() == 'addBlog')
            {       
                    $blog_laptop_dimension = Config::get('customConfig.blog_laptop_dimension');
                    $blog_mobile_dimension = Config::get('customConfig.blog_mobile_dimension');     
                                    
                    $image_intervention = Image::make($request->file('media'));

                    $originalName = pathinfo(str_replace(' ', '', $image->getClientOriginalName()), PATHINFO_FILENAME);
                    $ImageExtension = $image->getClientOriginalExtension();  
                    $productImage = $originalName.'.'.$ImageExtension;

                    $assetlaptopImagePath = asset(env('BLOG')).'/'.$productImage;

                    $publiclaptopImagePath = public_path(env('BLOG')).'/'.$productImage; 
                
                    $assetMobileImagePath = asset(env('BLOG')) . '/'.$originalName.'_'.$blog_mobile_dimension['width'].'x'.$blog_mobile_dimension['height'].'.'.$ImageExtension;        
                     
                    $publicMobileImagePath = public_path(env('BLOG')) .'/'.$originalName.'_'.$blog_mobile_dimension['width'].'x'.$blog_mobile_dimension['height'].'.'.$ImageExtension;  

                 
                    $image_intervention->resize($blog_laptop_dimension['width'],$blog_laptop_dimension['height']);
                    $image_intervention->save($publiclaptopImagePath);
                            
                    $image_intervention->resize($blog_mobile_dimension['width'],$blog_mobile_dimension['height']);
                    $image_intervention->save($publicMobileImagePath);

                    return $assetlaptopImagePath;                  

            }

    }
    catch(\Exception $e){
        return response()->json(['error object'=>$e]);
    }
    

}



?>

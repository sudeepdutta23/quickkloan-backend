<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GlobalException as GlobalException;
use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;
use App\Models\{
    Constants,
    LoanProduct,
    CarouselImage
};

use App\Http\Resources\{LoanProductResource,CarouselImageResource};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Http\Requests\{
    AddLoanProductRequest,
    CarouselImageRequest
};

use Illuminate\Support\Facades\Auth;
use Config;
use Image;
class LoanProductController extends Controller
{

    private $const;
    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }


    public function addLoanProduct(AddLoanProductRequest $request)
    {

        try {

            // $user_role = Config::get('customConfig.Role.user');

            // if($request->user->role != $user_role)
            // {
                if(!empty($request->id))
                {

                    if($request->percentStart > $request->percentEnd){

                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['PERCENTAGE_START_ERROR']], 422);

                    }else if($request->percentEnd < $request->percentStart)
                    {
                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['PERCENTAGE_END_ERROR']], 422);
                    }

                    if($request->hasFile('icon'))
                    {
                        $assetlaptopImagePath = resizeImage($request);

                    }else
                    {
                        $check_icons = LoanProduct::where('id',$request->id)->select('icon')->first();
                        $assetlaptopImagePath = $check_icons->icon;
                    }

                    $updateLoanProduct = LoanProduct::where('id',$request->id)
                    ->update([
                        'desc'=>$request->desc,
                        'loanType'=>$request->loanType,
                        'icon'=>$assetlaptopImagePath,
                        'percentStart'=>$request->percentStart,
                        'percentEnd'=>$request->percentEnd,
                    ]);

                    if($updateLoanProduct){
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['LOAN_PRODUCT_UPDATE']]);
                    }


                }
                else
                {

                    if($request->percentStart > $request->percentEnd){

                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['PERCENTAGE_START_ERROR']], 422);

                    }else if($request->percentEnd < $request->percentStart)
                    {
                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['PERCENTAGE_END_ERROR']], 422);
                    }


                    if($request->hasFile('icon'))
                    {

                        $assetlaptopImagePath = resizeImage($request);

                        $addLoanProduct = LoanProduct::create([
                            'desc'=>$request->desc,
                            'loanType'=>$request->loanType,
                            'icon'=>$assetlaptopImagePath,
                            'percentStart'=>$request->percentStart,
                            'percentEnd'=>$request->percentEnd,
                        ]);

                        if($addLoanProduct){
                            return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['LOAN_PRODUCT_ADD']]);
                        }

                    }

                }
            // }
            // else
            // {
            //     return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['ROLE_PERMISSION_ERROR']], 400);

            // }

        } catch (Exception $e) {
            throw new GlobalException;
        }
    }

    public function fetchLoanProduct(Request $request)
    {
        try {

            $getAllProduct = LoanProduct::where('loan_products.isActive',1)
            ->join('master_loan_type','master_loan_type.id','=','loan_products.loanType')
            ->orderBy('master_loan_type.orderBy','Asc')   //newly added
            ->select('loan_products.id','loan_products.isActive','master_loan_type.name','loan_products.desc','loan_products.icon','percentStart','percentEnd','loan_products.loanType')
            ->get();

            foreach ($getAllProduct as $val) {
                $getAllProduct->desc = htmlspecialchars($val->desc);
                if($request->header('Device') == 2){
                    fetchAppendImage($val);
                }

            }


            return (new ResponseHandler)->sendSuccessResponse(new LoanProductResource($getAllProduct)) ?? [];

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function fetchLoanProductById(Request $request, $id)
    {
        try {


            $fetchLoanProductById = LoanProduct::where('loan_products.isActive',1)
            ->join('master_loan_type','master_loan_type.id','=','loan_products.loanType')
            ->select('loan_products.id','loan_products.isActive','master_loan_type.name','loan_products.desc','loan_products.icon','percentStart','percentEnd','loan_products.loanType')
            ->where('loan_products.id',$id)
            ->orderBy('master_loan_type.orderBy','Asc')
            ->first();

            if(!empty($fetchLoanProductById)){

                $fetchLoanProductById->desc = htmlspecialchars($fetchLoanProductById->desc);

                if($request->header('Device') == 2){

                    fetchAppendImage($fetchLoanProductById);
                }

            }


            return (new ResponseHandler)->sendSuccessResponse(new LoanProductResource($fetchLoanProductById)) ?? [];

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function deleteLoanProduct(Request $request, $id)
    {
        try {


            $user_role = Config::get('customConfig.Role.user');

            $mobile_dimension = Config::get('customConfig.mobile_dimension');

            // if($request->user->role != $user_role)
            // {

                $deleteLoanProduct = LoanProduct::where('id', $id)->first();

                if(!empty($deleteLoanProduct))
                {

                    if($deleteLoanProduct->isActive == '0')
                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                        if($deleteLoanProduct->update(['isActive' => 0]))
                        {

                            $pathinfo = pathinfo($deleteLoanProduct->icon);
                            $fullPath = $pathinfo['dirname'].'/'.$pathinfo['filename'];
                            $suffix = '_'.$mobile_dimension['width'].'x'.$mobile_dimension['height'];
                            $new_file = $fullPath.$suffix.'.'.$pathinfo['extension'];
                            $laptop_image_path = public_path(env('PRODUCT') .'/'.$pathinfo['basename']);
                            $mobile_image_path = public_path(env('PRODUCT') .'/'.explode("/",$new_file)[4]);

                            if (file_exists($laptop_image_path) && file_exists($mobile_image_path)) {
                                File::delete($laptop_image_path);
                                File::delete($mobile_image_path);
                            }

                        }

                    if ($deleteLoanProduct)
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['LOAN_PRODUCT_DELETE']]);

                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
                }else
                {
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
                }
            // }
            // else
            // {
            //     return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['ROLE_PERMISSION_ERROR']], 400);

            // }


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function fetchCarouselImage(Request $request)
    {
        try {

            $fetchCarouselImage = CarouselImage::where('carousel_image.isActive',1)
            ->join('master_loan_type', 'master_loan_type.id', '=', 'carousel_image.loanType')
            ->orderBy('master_loan_type.orderBy','Asc')   // newly added
            ->select('carousel_image.id','image','carousel_image.isActive','text','master_loan_type.name as loanType','carousel_image.loanType as loanTypeID')
            ->get();


            foreach ($fetchCarouselImage as $val) {
                if($request->header('Device') == 2){
                    fetchAppendImage($val);
                }
            }


            return (new ResponseHandler)->sendSuccessResponse(new CarouselImageResource($fetchCarouselImage)) ?? [];

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function addCarouselImage(CarouselImageRequest $request){

        try {



            // $user_role = Config::get('customConfig.Role.user');

            // if($request->user->role != $user_role)
            // {
                if(!empty($request->id))
                {


                    if($request->hasFile('image'))
                    {
                        // $image = $request->file('image');
                        // $carouselImage = str_replace(' ', '', $image->getClientOriginalName());
                        // $image->move(public_path(env('CAROUSEL') .'/'), $carouselImage);
                        // $carouselImagePath = asset(env('CAROUSEL')) . '/' . $carouselImage;

                        $carouselImagePath = resizeImage($request);

                    }else
                    {
                        $check_image = CarouselImage::where('id',$request->id)->select('image')->first();

                        $carouselImagePath = $check_image->image;
                    }

                    $updateLoanProduct = CarouselImage::where('id',$request->id)
                    ->update([
                        'loanType'=>$request->loanType,
                        'text'=>$request->text,
                        'image'=>$carouselImagePath,
                    ]);

                    if($updateLoanProduct){
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['CAROUSEL_UPDATE']]);
                    }


                }
                else
                {


                    if($request->hasFile('image'))
                    {
                        $carouselImagePath = resizeImage($request);

                        // $image = $request->file('image');
                        // $carouselImage = str_replace(' ', '', $image->getClientOriginalName());
                        // $image->move(public_path(env('CAROUSEL') .'/'), $carouselImage);
                        // $carouselImagePath = asset(env('CAROUSEL')) . '/' . $carouselImage;

                        $addCarouselProduct = CarouselImage::create([
                            'loanType'=>$request->loanType,
                            'text'=>$request->text,
                            'image'=>$carouselImagePath,
                        ]);

                        if($addCarouselProduct){
                            return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['CAROUSEL_ADD']]);
                        }

                    }

                }
            // }
            // else
            // {
            //     return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['ROLE_PERMISSION_ERROR']], 400);

            // }


        } catch (Exception $e) {

            throw new GlobalException;

        }

    }

    public function deleteCarouselImage(Request $request,$id){

        try {

            $mobile_dimension = Config::get('customConfig.mobile_dimension');


            // $user_role = Config::get('customConfig.Role.user');

            // if($request->user->role != $user_role)
            // {

                $deleteCarouselImage = CarouselImage::where('id', $id)->first();

                if(!empty($deleteCarouselImage))
                {
                    if($deleteCarouselImage->isActive == '0')
                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                    if($deleteCarouselImage->update(['isActive' => 0]))
                    {

                        $pathinfo = pathinfo($deleteCarouselImage->image);
                        $fullPath = $pathinfo['dirname'].'/'.$pathinfo['filename'];
                        $suffix = '_'.$mobile_dimension['width'].'x'.$mobile_dimension['height'];
                        $new_file = $fullPath.$suffix.'.'.$pathinfo['extension'];
                        $laptop_image_path = public_path(env('CAROUSEL') .'/'.$pathinfo['basename']);
                        $mobile_image_path = public_path(env('CAROUSEL') .'/'.explode("/",$new_file)[4]);

                        if (file_exists($laptop_image_path) && file_exists($mobile_image_path)) {
                            File::delete($laptop_image_path);
                            File::delete($mobile_image_path);
                        }

                    }

                    if ($deleteCarouselImage)
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['CAROUSEL_DELETE']]);

                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
                }else
                {
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
                }
            // }
            // else
            // {
            //     return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['ROLE_PERMISSION_ERROR']], 400);

            // }


        } catch (Exception $e) {

            throw new GlobalException;

        }
    }



}

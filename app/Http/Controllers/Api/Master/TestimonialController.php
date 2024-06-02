<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;
use App\Exceptions\GlobalException as GlobalException;
use App\Models\{Constants, MasterTestimonial};
use App\Http\Requests\{AddTestimonialRequest,EditTestimonialRequest};
use File;
use Exception;
use Carbon\Carbon;
use Image;

class TestimonialController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }



    public function fetchAllTestimonial(Request $request)
    {
        try {

            $fetchAllTestimonial = MasterTestimonial::where('isActive',1)
            // ->groupBy('customerName')
            ->select('id as value','customerName as name','customerImage','customerComment','customerCollegeName','isActive')
            ->orderBy('id','Desc')
            ->get();

            return (new ResponseHandler)->sendSuccessResponse($fetchAllTestimonial);

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function getAllTestimonial(Request $request)
    {
        try {

            $getAllTestimonial = MasterTestimonial::groupBy('customerName')->get();

            return (new ResponseHandler)->sendSuccessResponse($getAllTestimonial);

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function addTestimonial(AddTestimonialRequest $request)
    {
        try {

            if ($request->isAdmin) {


                if($request->hasFile('customerImage')){

                        $customerImagePath = resizeTestimonialImage($request);

                        $addTestimonial = MasterTestimonial::create([
                            'customerName'=>$request->customerName,
                            'customerComment'=>$request->customerComment,
                            'customerCollegeName'=>$request->customerCollegeName,
                            'customerImage'=>$customerImagePath,
                        ]);

                    if ($addTestimonial){
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['TESTIMONIAL_ADD']], 201);

                    }

                }
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {


            throw new GlobalException;

        }
    }

    public function editTestimonial(EditTestimonialRequest $request)
    {
        try {

            if($request->isAdmin) {


                $checkImageFile = MasterTestimonial::where('id', $request->id)
                ->where('isActive', 1)->select('customerImage')->first();

                if($request->hasFile('customerImage'))
                {

                    $customerImagePath = resizeTestimonialImage($request);

                    if (!empty($checkImageFile->customerImage)) {

                        $pathinfo = pathinfo($checkImageFile->customerImage);
                        $fullPath = $pathinfo['dirname'].'/'.$pathinfo['filename'];
                        $new_file = $fullPath.$pathinfo['extension'];
                        $image_path = public_path(env('TESTIMONIAL') .'/'.$pathinfo['basename']);

                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                    }


                    // if($request->hasFile('icon'))
                    // {

                    // }else
                    // {
                    //     $check_icons = LoanProduct::where('id',$request->id)->select('icon')->first();
                    //     $assetlaptopImagePath = $check_icons->icon;
                    // }




                    // $customerImageFile = $request->file('customerImage');
                    // $customerImage = str_replace(' ', '', $customerImageFile->getClientOriginalName());
                    // $customerImageFile->move(public_path(env('TESTIMONIAL') . '/' . trim($request->customerName)), $customerImage);
                    // $customerImagePath = asset(env('TESTIMONIAL') . '/' . trim($request->customerName)) . '/' . $customerImage;

                }
                else
                {
                    $customerImagePath = $checkImageFile->customerImage;
                }

                $editTestimonial = MasterTestimonial::where('id', $request->id)
                ->update([
                    'customerName'=>$request->customerName,
                    'customerComment'=>$request->customerComment,
                    'customerCollegeName'=>$request->customerCollegeName,
                    'customerImage'=>$customerImagePath,
                ]);

                if($editTestimonial){
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['TESTIMONIAL_UPDATE']], 200);
                }

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_UPDATE']], 400);

            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {


            throw new GlobalException;

        }
    }

    public function deleteTestimonial(Request $request, $id)
    {
        try {

            if ($request->isAdmin) {

                $deleteTestimonial = MasterTestimonial::where('id', $id)->first();

                if($deleteTestimonial->isActive == '0')
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                if ($deleteTestimonial->update(['isActive' => '0']))
                {
                    if (!empty($deleteTestimonial->customerImage))
                    {
                        $pathinfo = pathinfo($deleteTestimonial->customerImage);
                        $image_path = public_path(env('TESTIMONIAL') .'/'.$pathinfo['basename']);
                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                    }
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['TESTIMONIAL_DELETE']]);
                }

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);


        } catch (Exception $e) {


            throw new GlobalException;

        }
    }
}
?>

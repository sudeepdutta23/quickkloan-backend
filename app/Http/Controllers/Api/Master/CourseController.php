<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;

use App\Models\{Constants, MasterCourse};
use App\Http\Requests\{AddCourseRequest,EditCourseRequest};
use App\Exceptions\GlobalException as GlobalException;
use Exception;

class CourseController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }



    public function getAllCourse(Request $request)
    {
        try {
            $getAllCourse = MasterCourse::where('isActive', 1)->select('id as value', 'courseName as name')->get();

            return (new ResponseHandler)->sendSuccessResponse($getAllCourse);

        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }


    public function addCourse(AddCourseRequest $request)
    {
        try {
            if($request->isAdmin){
                $addCourse = MasterCourse::create(["courseName" => $request->courseName]);

                if($addCourse)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['COURSE_ADD']], 201);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);

        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }

    public function editCourse(EditCourseRequest $request,$courseId)
    {
        try {

            if($request->isAdmin){
                $editCourse = MasterCourse::where('id',$courseId)->update(["courseName" => $request->courseName]);

                if($editCourse)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['COURSE_UPDATE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_UPDATE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);
        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }


    public function deleteCourse(Request $request,$courseId)
    {
        try {
            if($request->isAdmin){
                $deleteCourse = MasterCourse::where('id',$courseId)->first();

                if($deleteCourse->isActive == '0')
                    return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                $deleteCourse->update([ 'isActive' => 0 ]);

                if($deleteCourse)
                    return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['COURSE_DELETE']]);

                return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);
            }
            return (new ResponseHandler)->sendErrorResponse(["message" => $this->const['UNAUTHENTICATE_USER']]);
        } catch (Exception $e) {
           
            throw new GlobalException; 

        }
    }
}
?>

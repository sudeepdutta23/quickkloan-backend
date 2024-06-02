<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Constants,
    IndividualComment
};
use Illuminate\Support\Str;
use App\Http\Requests\SaveForm;
use Carbon\Carbon;
use App\Http\Utils\ResponseHandler;
use App\Exceptions\GlobalException as GlobalException;
use Exception;
use Illuminate\Support\Arr;
class CommentController extends Controller
{
    private $const, $logConst;

    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }

    public function addComment(Request $request)
    {
        try {

            $addComment = IndividualComment::create([
                'leadId'=>$request->leadId,
                'comments'=>$request->comments,
                'commentedBy'=>auth('sanctum')->user()->id,
            ]);

            if($addComment)
                return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['COMMENT_ADDED']], 201);


        } catch (Exception $e) {

            throw new GlobalException;

        }

    }

    public function fetchAllComments(Request $request)
    {
        try {

            $desiredTimeZone = 'Asia/Kolkata';

            $fetchAllComments = DB::table('individual_comments')->
            join('users','users.id','=','individual_comments.commentedBy')
            ->where('leadId',$request->leadId)->where('individual_comments.isActive',1)
            ->select('comments as comment',
            'commentedOn',
            'users.firstName as commentedBy')
            ->orderBy('individual_comments.id','Desc')
            ->get();

           

            if($fetchAllComments)
                return (new ResponseHandler)->sendSuccessResponse($fetchAllComments);

        } catch (Exception $e) {

            throw new GlobalException;

        }

    }



}

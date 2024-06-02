<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GlobalException as GlobalException;
use App\Http\Controllers\Controller;

use App\Http\Utils\ResponseHandler;
use App\Models\{
    Constants,
    Blog,
    User,
    BlogImage
};
use Illuminate\Support\Facades\Log;
use App\Http\Resources\BlogResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Http\Requests\{
    AddBlogRequest,
    EditBlogRequest
};

use Illuminate\Support\Facades\Auth;
use Config;
use Image;
class BlogController extends Controller
{

    private $const;
    public function __construct()
    {
        $this->const = (new Constants)->getConstants();
    }


    public function addBlog(AddBlogRequest $request)
    {

        try {


            $user_role = Config::get('customConfig.Role.user');

            // if($request->user->role != $user_role)
            // {

                if(!empty($request->id))
                {
                    // for update records
                    // $check_values = Blog::where('id',$request->id)->select('id','hashTags','seoKeywords','isApproved')->first();

                    // if(!empty($check_values))
                    // {

                        // check if blog is approved or not

                            // if(empty($check_values->isApproved) || $check_values->isApproved == null || $check_values->isApproved == 0)
                            // {


                                $hashTags = str_replace(['[', ']', '"'], '', $request->hashTags);

                                $seoKeywords = str_replace(['[', ']', '"'], '', $request->seoKeywords);

                                $update = Blog::where('id',$request->id)->update([
                                    'title'=>$request->title,
                                    'shortDesc'=>$request->shortDesc,
                                    'longDesc'=>$request->longDesc,
                                    'hashTags'=>$hashTags,
                                    'seoKeywords'=>$seoKeywords,
                                    'blogCategory'=>$request->blogCategory,
                                    'timeToRead'=>$request->timeToRead,
                                ]);

                                if($update)
                                {
                                    // if($request->isVideo)
                                    // {

                                    //     foreach($request->media as $media)
                                    //     {

                                    //          BlogImage::updateOrCreate(
                                    //             ['blog_id' => $request->id,'media' => $media],
                                    //             ['media' => $media]
                                    //          );


                                    //         Blog::where('id',$request->id)->update([
                                    //             'isVideo'=>1
                                    //         ]);
                                    //     }

                                    //     $get_created_blog = Blog::with(['media' => function ($query) {
                                    //         $query->select('blog_id','media');
                                    //     }])
                                    //     ->where('blogs.id',$request->id)
                                    //     ->select('blogs.id','title','blogCategory','shortDesc','longDesc','timeToRead','hashTags','seoKeywords','isVideo')
                                    //     ->first();

                                    //     $get_created_blog->hashTags = explode(',',$get_created_blog->hashTags);
                                    //     $get_created_blog->seoKeywords = explode(',',$get_created_blog->seoKeywords);

                                    //     return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['BLOG_UPDATE'],'blog_data'=>$get_created_blog]);

                                    // }

                                    if($request->hasFile('media'))
                                    {

                                        foreach($request->file('media') as $image)
                                        {
                                            $blogImagePath = resizeImage($request,$image);

                                            BlogImage::create([
                                                'media' => $blogImagePath,
                                                'blog_id'=> $request->id,
                                            ]);
                                        }

                                        $get_created_blog = Blog::with(['media' => function ($query) {
                                            $query->select('blog_id','media');
                                        }])
                                        ->where('blogs.id',$request->id)
                                        ->select('blogs.id','title','blogCategory','shortDesc','longDesc','timeToRead','hashTags','seoKeywords','isVideo')
                                        ->first();

                                        $get_created_blog->hashTags = explode(',',$get_created_blog->hashTags);
                                        $get_created_blog->seoKeywords = explode(',',$get_created_blog->seoKeywords);

                                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['BLOG_UPDATE'],'blog_data'=>$get_created_blog]);

                                    }

                                }

                            // }
                            // else if($check_values->isApproved == 1)
                            // {

                            //     $hashTags = str_replace(['[', ']', '"'], '', $request->hashTags);

                            //     $seoKeywords = str_replace(['[', ']', '"'], '', $request->seoKeywords);

                            //     $newBlog = Blog::insertGetId([
                            //         'title'=>$request->title,
                            //         'shortDesc'=>$request->shortDesc,
                            //         'longDesc'=>$request->longDesc,
                            //         'blogCategory'=>$request->blogCategory,
                            //         'hashTags'=>$hashTags,
                            //         'seoKeywords'=>$seoKeywords,
                            //         'timeToRead'=>$request->timeToRead,
                            //         'createdBy'=>$request->user->id,
                            //         'parentId'=> $check_values->id
                            //     ]);

                            //     if($newBlog)
                            //     {

                            //         $updateStatus = Blog::where('id',$check_values->id)
                            //         ->update([
                            //             'isActive' => 0
                            //         ]);


                            //         if($request->isVideo == 1)
                            //         {
                            //             foreach($request->media as $media)
                            //             {

                            //                 BlogImage::updateOrCreate(
                            //                     ['blog_id' => $newBlog,'media' => $media],
                            //                     ['media' => $media]
                            //                  );

                            //                 Blog::where('id',$newBlog)->update([
                            //                     'isVideo'=>1
                            //                 ]);
                            //             }

                            //             $get_newcreated_blog = Blog::with(['media' => function ($query) {
                            //                 $query->select('blog_id','media');
                            //             }])
                            //             ->where('blogs.id',$newBlog)
                            //             ->select('blogs.id','title','blogCategory','shortDesc','longDesc','timeToRead','hashTags','seoKeywords','isVideo')
                            //             ->first();

                            //             $get_newcreated_blog->hashTags = explode(',',$get_newcreated_blog->hashTags);
                            //             $get_newcreated_blog->seoKeywords = explode(',',$get_newcreated_blog->seoKeywords);

                            //             return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['BLOG_UPDATE'],'blog_data'=>$get_newcreated_blog]);

                            //         }

                            //         if($request->hasFile('media'))
                            //         {
                            //             foreach((array)$request->file('media') as $image)
                            //             {
                            //                 // $blogImage = str_replace(' ', '', $image->getClientOriginalName());
                            //                 // $image->move(public_path(env('BLOG') .'/'.$request->user->id.'/'. str_replace(' ','',$request->title)), $blogImage);
                            //                 // $blogImagePath = asset(env('BLOG') .'/'.$request->user->id.'/'. str_replace(' ','',$request->title)) . '/' . $blogImage;

                            //                 $blogImagePath = resizeImage($request,$image);

                            //                 BlogImage::create([
                            //                     'media'=>$blogImagePath,
                            //                     'blog_id'=>$newBlog,
                            //                 ]);
                            //             }

                            //             $get_newcreated_blog = Blog::with(['media' => function ($query) {
                            //                 $query->select('blog_id','media');
                            //             }])
                            //             ->where('blogs.id',$newBlog)
                            //             ->select('blogs.id','title','blogCategory','shortDesc','longDesc','timeToRead','hashTags','seoKeywords','isVideo')
                            //             ->first();

                            //             $get_newcreated_blog->hashTags = explode(',',$get_newcreated_blog->hashTags);
                            //             $get_newcreated_blog->seoKeywords = explode(',',$get_newcreated_blog->seoKeywords);

                            //             return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['BLOG_UPDATE'],'blog_data'=>$get_newcreated_blog]);

                            //         }


                            //     }

                            // }

                    // }
                    // else
                    // {
                    //     return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['INVALID_BLOG_ID']]);

                    // }

                }
                else
                {
                    $hashTags = str_replace(['[', ']', '"'], '', $request->hashTags);

                    $seoKeywords = str_replace(['[', ']', '"'], '', $request->seoKeywords);

                    // new records
                    $addBlog = Blog::insertGetId([
                        'title'=>$request->title,
                        'shortDesc'=>$request->shortDesc,
                        'longDesc'=>$request->longDesc,
                        'hashTags'=>$hashTags,
                        'blogCategory'=>$request->blogCategory,
                        'seoKeywords'=>$seoKeywords,
                        'timeToRead'=>$request->timeToRead,
                        'createdBy'=>$request->user->id,
                    ]);

                    if($addBlog)
                    {

                            // if($request->isVideo == 1){


                            //     foreach($request->media as $media)
                            //     {

                            //         BlogImage::updateOrCreate(
                            //             ['blog_id' => $addBlog,'media' => $media],
                            //             ['media' => $media]
                            //         );

                            //         Blog::where('id',$addBlog)->update([
                            //             'isVideo'=>1
                            //         ]);
                            //     }


                            //     $get_created_blog = Blog::with(['media' => function ($query) {
                            //         $query->select('id','blog_id','media');
                            //     }])
                            //     ->where('blogs.id',$addBlog)
                            //     ->select('blogs.id','title','blogCategory','shortDesc','longDesc','timeToRead','hashTags','seoKeywords','isVideo')
                            //     ->first();


                            //     $get_created_blog->hashTags = explode(',',$get_created_blog->hashTags);
                            //     $get_created_blog->seoKeywords = explode(',',$get_created_blog->seoKeywords);

                            //     return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['BLOG_ADD'],'blog_data'=>$get_created_blog]);


                            // }

                            if($request->hasFile('media'))
                            {

                                foreach((array)$request->file('media') as $image){
                                    
                                   
                                    $blog_laptop_dimension = Config::get('customConfig.blog_laptop_dimension');
                                    $blog_mobile_dimension = Config::get('customConfig.blog_mobile_dimension');
                                    $image_intervention = Image::make($image);
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

                                    BlogImage::create([
                                        'media'=>$assetlaptopImagePath,
                                        'blog_id'=>$addBlog,
                                    ]);
                                }

                                $get_created_blog = Blog::with(['media' => function ($query) {
                                    $query->select('id','blog_id','media');
                                }])
                                ->where('blogs.id',$addBlog)
                                ->select('blogs.id','title','blogCategory','shortDesc','longDesc','timeToRead','hashTags','seoKeywords','isVideo')
                                ->first();

                                $get_created_blog->hashTags = explode(',',$get_created_blog->hashTags);
                                $get_created_blog->seoKeywords = explode(',',$get_created_blog->seoKeywords);

                                return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['BLOG_ADD'],'blog_data'=>$get_created_blog]);

                            }

                    }

                }
            // }
            // else
            // {
            //     return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['ROLE_PERMISSION_ERROR']], 400);

            // }


        } catch (\Exception $e) {            
            Log::info('Blog add error'.$e);
            throw new GlobalException;
        }
    }


    public function deleteBlog(Request $request, $id)
    {
        try {


            $blog_mobile_dimension = Config::get('customConfig.blog_mobile_dimension');


            // $super_admin_role = Config::get('customConfig.Role.super_admin');

            // if($request->user->role == $super_admin_role){

                $deleteBlog = Blog::where('id', $id)
                ->where('isActive',1)->first();

                if(!empty($deleteBlog))
                {

                    if($deleteBlog->isActive == '0')
                        return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_DELETE']], 400);

                    if($deleteBlog->update(['isActive' => '0']))
                    {

                        $deleteImage = BlogImage::where('blog_id',$id)
                                        ->update([
                                            'isActive' => 0
                                        ]);

                        if($deleteImage)
                        {
                            $blog_images = BlogImage::where('blog_id',$id)->pluck('media');

                            foreach($blog_images as $value)
                            {

                                $pathinfo = pathinfo($value);

                                $fullPath = $pathinfo['dirname'].'/'.$pathinfo['filename'];

                                $suffix = '_'.$blog_mobile_dimension['width'].'x'.$blog_mobile_dimension['height'];

                                $new_file = $fullPath.$suffix.'.'.$pathinfo['extension'];

                                $blog_laptop_image_path = public_path(env('BLOG') .'/'.$pathinfo['basename']);

                                $blog_mobile_image_path = public_path(env('BLOG') .'/'.explode("/",$new_file)[4]);

                                if (file_exists($blog_laptop_image_path) && file_exists($blog_mobile_image_path)) {
                                    File::delete($blog_laptop_image_path);
                                    File::delete($blog_mobile_image_path);
                                }
                            }
                        }
                    }

                    if ($deleteBlog){
                        return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['BLOG_DELETE']]);
                    }

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

    public function getAllBlog(Request $request)
    {
        try {


            $pageOffset = (isset($request->pageOffset)) ? (int) $request->pageOffset : 12;

            $orderBy = (isset($request->orderBy)) ? $request->orderBy : 'id';

            $sort = (isset($request->sort)) ? $request->sort : 'desc';

            $getAllBlog = Blog::with(['media' => function ($query) {
                $query->select('blog_images.id', 'blog_images.blog_id', 'blog_images.media')
                      ->where('blog_images.isActive','=',1);
            }])
            ->join('users','users.id','=','blogs.createdBy')
            ->select('blogs.id','title','blogCategory','shortDesc','longDesc','timeToRead','hashTags','seoKeywords','blogs.createdBy','isApproved',
            DB::raw("DATE_FORMAT(blogs.createdAt, '%d-%m-%Y %h:%i:%s') as postedAt"));

            // if admin logged in
            // $admin_role = Config::get('customConfig.Role.child_admin');

            // if(isset($request->user->id)){
            //     if($request->user->role == $admin_role){
            //         $getAllBlog = $getAllBlog->where('createdBy',$request->user->role);
            //     }
            // }

            if(!empty($request->searchValue))
            {
                $getAllBlog = $getAllBlog->where('title','LIKE', '%' .$request->searchValue. '%')
                ->orWhere('shortDesc','LIKE', '%' .$request->searchValue. '%')
                ->orWhere('longDesc','LIKE', '%' .$request->searchValue. '%');
            }

            $getAllBlog = $getAllBlog
            ->where('blogs.isActive','=',1)
            ->orderBy($orderBy,$sort)
            ->paginate($pageOffset);

            foreach ($getAllBlog as $val) {


                $val->shortDesc = htmlspecialchars($val->shortDesc);
                $val->longDesc = htmlspecialchars($val->longDesc);
                $val->hashTags = explode(",",$val->hashTags);
                $val->seoKeywords = explode(",",$val->seoKeywords);

                if($request->header('Device') == 2){
                    fetchAppendBlogImage($val->media);
                }
            }

            return (new ResponseHandler)->sendSuccessResponse(new BlogResource($getAllBlog));

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function getBlogById(Request $request,$id)
    {
        try {


            $getBlogById = Blog::with(['media' => function ($query) {
                $query->select('blog_images.id', 'blog_images.blog_id', 'blog_images.media')
                      ->where('blog_images.isActive','=',1);
            }])
            ->join('users','users.id','=','blogs.createdBy')
            ->select('blogs.id','title','blogCategory','shortDesc','longDesc','timeToRead','hashTags','seoKeywords','blogs.createdBy','isApproved',
            DB::raw("DATE_FORMAT(blogs.createdAt, '%d-%m-%Y %h:%i:%s') as postedAt"))
            ->where('blogs.isActive','=',1)
            ->where('blogs.id',$id)
            ->first();

            if(!empty($getBlogById->shortDesc) && !empty($getBlogById->longDesc)){
                $getBlogById->shortDesc = htmlspecialchars($getBlogById->shortDesc);
                $getBlogById->longDesc = htmlspecialchars($getBlogById->longDesc);
                $getBlogById->hashTags = explode(",",$getBlogById->hashTags);
                $getBlogById->seoKeywords = explode(",",$getBlogById->seoKeywords);
            }

            if($request->header('Device') == 2){
                fetchAppendBlogImage($getBlogById->media);
            }

            // latest blogs
            $latestBlog = Blog::with(['media' => function ($query) {
                $query->select('blog_images.id', 'blog_images.blog_id', 'blog_images.media')
                      ->where('blog_images.isActive','=',1);
            }])->where('blogs.isActive','=',1)
                ->join('users','users.id','=','blogs.createdBy')
                ->select('blogs.id','title','blogCategory','shortDesc','longDesc','timeToRead','hashTags','seoKeywords','blogs.createdBy','isApproved')
                ->orderBy('blogs.id','Desc')
                ->take(5)->get();

            foreach ($latestBlog as $val) {
                $val->shortDesc = htmlspecialchars($val->shortDesc);
                $val->longDesc = htmlspecialchars($val->longDesc);
                $val->hashTags = explode(",",$val->hashTags);
                $val->seoKeywords = explode(",",$val->seoKeywords);
            }


            $getBlogById['latestBlog'] = $latestBlog;

            return (new ResponseHandler)->sendSuccessResponse($getBlogById);

        } catch (Exception $e) {

            throw new GlobalException;

        }
    }

    public function editBlog(EditBlogRequest $request)
    {

        try {

            $checkImageFile = Blog::where('id', $request->id)
            ->where('isActive', 1)->select('image')->first();

            if($request->hasFile('image')){

                if (!empty($checkImageFile->customerImage)) {
                    if (File::exists(public_path(substr($checkImageFile->customerImage, 22)))) {
                        $deleteFile = public_path(substr($checkImageFile->customerImage, 22));
                        File::delete($deleteFile);
                    }
                }

                $blogImageFile = $request->file('image');
                $blogImage = str_replace(' ', '', $blogImageFile->getClientOriginalName());
                $blogImageFile->move(public_path(env('BLOG') . '/' . str_replace(' ','',$request->title)), $blogImage);
                $blogImagePath = asset(env('BLOG') . '/' . str_replace(' ','',$request->title)) . '/' . $blogImage;

            }


            $editBlog = Blog::where('id',$request->id)
            ->update([
                'title'=>$request->title,
                'shortDesc'=>$request->shortDesc,
                'longDesc'=>$request->longDesc,
                'createdBy'=>$request->createdBy,
                'image'=>$blogImagePath ?? $checkImageFile->image ?? env('DEFAULT_BLOG_IMAGE'),
            ]);

            if($editBlog)
                return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['BLOG_UPDATE']]);
            return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_UPDATE']], 400);



        } catch (Exception $e) {

            throw new GlobalException;
        }
    }

    public function deleteImage(Request $request,$id){

        $deleteImage = BlogImage::where('id',$id)
        ->update([
            'isActive' => 0
        ]);

        if($deleteImage){
            return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['BLOG_IMAGE_DELETE']]);
        }

    }

    public function approveBlog(Request $request,$id){

        $super_admin_role = Config::get('customConfig.Role.super_admin');

        if($request->user->role == $super_admin_role){

            $approveStatus = Blog::where('id',$id)
            ->where('blogs.isActive',1)->first();

            if($approveStatus->isApproved != 1){
                $approveStatus->update([
                    'isApproved' => 1
                ]);

                return (new ResponseHandler)->sendSuccessResponse(['message' => $this->const['BLOG_APPROVE']]);
            }

            return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['UNABLE_TO_APPROVE']]);
        }else
        {
            return (new ResponseHandler)->sendErrorResponse(['message' => $this->const['ROLE_PERMISSION_ERROR']], 400);

        }


    }



}

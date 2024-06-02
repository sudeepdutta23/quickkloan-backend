<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    LeadController,
    CosignerController,
    DocumentController,
    CommentController,
    BlogController,
    LoanProductController,
    AboutUsController,
    ContactUsController,
    FeatureController
};

use App\Http\Controllers\Api\Master\{
    CityController,
    CountryController,
    CourseController,
    EmployeeStatusController,
    LoanTypeController,
    StateController,
    LeadStatusController,
    TestimonialController,
    MasterRelationshipController,
    UserController
};



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {

    // Public Routes Start //
    Route::group(['prefix' => 'auth'], function () {
        Route::controller(AuthController::class)->group(function () {
            // Route::post("register", "register")->name('individual.register');
            Route::post("register", "register")->name('register');
            Route::post("login", "login")->name('login');
            // Route::post("otp", "verifyOtp")->name('individual.verifyOtp');
            Route::post("/otp", "verifyOtp")->name('verifyOtp');

        });
    });

    Route::group(['prefix' => 'master'], function () {
        Route::controller(TestimonialController::class)->group(function () {
            Route::get("fetchAllTestimonial", "fetchAllTestimonial")->name('fetchAllTestimonial');
        });
    });

    Route::controller(MasterRelationshipController::class)->group(function () {
        Route::get("/getAllRelationship", "getAllRelationship")->name('getAllRelationship');

    });



    // Public Routes for LoanProductController
    Route::group(['prefix' => 'product'], function () {
        Route::controller(LoanProductController::class)->group(function () {
            Route::get('/fetchLoanProduct', 'fetchLoanProduct')->name('fetchLoanProduct');
            Route::get('/fetchLoanProductById/{id}', 'fetchLoanProductById')->name('fetchLoanProductById');
            Route::get('/fetchCarouselImage', 'fetchCarouselImage')->name('fetchCarouselImage');
        });
    });

    // public Routes for AboutUsController
    Route::group(['prefix' => 'about'], function () {

        Route::controller(AboutUsController::class)->group(function(){
            // For About Us Route
            Route::get("/getAbout","getAbout")->name('getAbout');
        });
    });


    Route::group(['prefix' => 'contact'], function () {
        Route::controller(ContactUsController::class)->group(function(){
            // For Contact Us Route
            Route::post("/sendContactMail","sendContactMail")->name('sendContactMail');
            Route::get("/getContactDetails","getContactDetails")->name('getContactDetails');
        });

    });


    Route::group(['prefix' => 'feature'], function () {
        Route::controller(FeatureController::class)->group(function(){
            Route::get("/getAllFeature","getAllFeature");
            Route::get("/updateCache","updateCache");
        });

    });

    // Public Routes end //
    // Route::controller(LeadController::class)->group(function () {
    //     Route::get("/admin/downloadAllFile/{leadId}", "downloadAllFile");
    // });

    // Protected Routes start
    Route::group(['middleware' => 'userAuth'], function () {

        Route::group(['prefix' => 'master'], function () {

            Route::controller(LeadStatusController::class)->group(function () {
                Route::get("getAllLeadStatus", "getAllLeadStatus")->name('getAllLeadStatus');
            });

            Route::controller(CountryController::class)->group(function () {

                // country crud
                // Route::get("getAllCountry/{showOnAddress?}", "getAllCountry");
                Route::get("getAllCountry", "getAllCountry")->name('getAllCountry');
                Route::post("addCountry", "addCountry")->name('addCountry');
                Route::put("editCountry/{countryId}", "editCountry")->name('editCountry');
                Route::delete("deleteCountry/{countryId}", "deleteCountry")->name('deleteCountry');
                Route::get("getCourseCountry", "getCourseCountry")->name('getCourseCountry');

            });

            Route::controller(StateController::class)->group(function () {
                // state crud
                Route::get("getAllState", "getAllState")->name('getAllState');
                Route::post("addState", "addState")->name('addState');
                Route::put("editState/{stateId}", "editState")->name('editState');
                Route::delete("deleteState/{stateId}", "deleteState")->name('deleteState');

            });

            Route::controller(CityController::class)->group(function () {
                // city crud
                Route::get("getAllCity/{stateId}", "getAllCity")->name('getAllCity');
                Route::post("addCity", "addCity")->name('addCity');
                Route::put("editCity/{cityId}", "editCity")->name('editCity');
                Route::delete("deleteCity/{cityId}", "deleteCity")->name('deleteCity');

            });

            Route::controller(CourseController::class)->group(function () {
                // course crud
                Route::get("getAllCourse", "getAllCourse")->name('getAllCourse');
                Route::post("addCourse", "addCourse")->name('addCourse');
                Route::put("editCourse/{courseId}", "editCourse")->name('editCourse');
                Route::delete("deleteCourse/{courseId}", "deleteCourse")->name('deleteCourse');
            });

            Route::controller(LoanTypeController::class)->group(function () {
                // loanType crud
                Route::get("getLoanType", "getLoanType")->name('getLoanType');
                Route::post("addLoanType", "addLoanType")->name('addLoanType');
                Route::put("editLoanType/{loanTypeId}", "editLoanType")->name('editLoanType');
                Route::delete("deleteLoanType/{loanTypeId}", "deleteLoanType")->name('deleteLoanType');
            });

            Route::controller(EmployeeStatusController::class)->group(function () {
                // employeementstatus crud
                Route::get("getAllEmployeeStatus", "getAllEmployeeStatus")->name('getAllEmployeeStatus');
                Route::post("addEmployeeStatus", "addEmployeeStatus")->name('addEmployeeStatus');
                Route::put("editEmployeeStatus/{empStatusId}", "editEmployeeStatus")->name('editEmployeeStatus');
                Route::delete("deleteEmployeeStatus/{empStatusId}", "deleteEmployeeStatus")->name('deleteEmployeeStatus');

            });

            Route::controller(TestimonialController::class)->group(function () {
                Route::get("getAllTestimonial", "getAllTestimonial")->name('getAllTestimonial');
                Route::post("addTestimonial", "addTestimonial")->name('addTestimonial');
                Route::post("editTestimonial", "editTestimonial")->name('editTestimonial');
                Route::delete("deleteTestimonial/{id}", "deleteTestimonial")->name('deleteTestimonial');
            });

            Route::controller(DocumentController::class)->group(function () {
                Route::get("/getAllDocuments", "getAllDocuments")->name('getAllDocuments');
                Route::post("/addDocument", "addDocument")->name('addDocument');
                Route::put("/updateDocument/{docId}", "updateDocument")->name('updateDocument');
                Route::delete("/deleteDocument/{docId}", "deleteDocument")->name('deleteDocument');

            });

            Route::controller(MasterRelationshipController::class)->group(function () {
                Route::post("/addRelationship", "addRelationship")->name('addRelationship');
                Route::put("/editRelationship/{rid}", "editRelationship")->name('editRelationship');
                Route::delete("/deleteRelationship/{rid}", "deleteRelationship")->name('deleteRelationship');
            });



        });

        Route::controller(AuthController::class)->group(function () {
            Route::post("/auth/logout", "logout");
            // Route::post("/admin/register", "register")->name('admin.register');
            // Route::post("/admin/otp", "verifyOtp")->name('admin.verifyOtp');
            // Route::post("/auth/otp", "verifyOtp")->name('verifyOtp');
            Route::post("/auth/resendOTP", "resendOTP")->name('resendOTP');
        });

        Route::controller(LeadController::class)->group(function () {
            Route::post("/lead/applyLoan", "applyLoan")->name('applyLoan');
            Route::post("/lead/saveForm", "saveForm")->name('saveForm');
            Route::post("/lead/mapStateCity", "mapStateCity")->name('mapStateCity');
            Route::post("/lead/getOngoingCompletedIndividual", "getOngoingCompletedIndividual")->name('getOngoingCompletedIndividual');
            Route::get("/admin/getAllLeads", "getAllLeads")->name('getAllLeads');
            Route::get("/admin/getLeadRecord/{leadId}", "getLeadRecord")->name('getLeadRecord');
            Route::put("/admin/updateLeadStatus", "updateLeadStatus")->name('updateLeadStatus');
            Route::put("/admin/updateStep", "updateStep")->name('updateStep');
            Route::get("/admin/searchLead", "searchLead")->name('searchLead');
            Route::get("/admin/downloadAllFile/{leadId}", "downloadAllFile")->name('exportAllDocFiles');
        });

        Route::controller(CosignerController::class)->group(function () {
            Route::delete("/lead/deleteCosigner/{individualId}/{leadId}", "deleteCosigner")->name('deleteCosigner');
        });

        Route::controller(CommentController::class)->group(function(){
            // routes for comment
            Route::post("/admin/addComment", "addComment")->name('addComment');
            Route::post("/admin/fetchAllComments", "fetchAllComments")->name('fetchAllComments');
        });

        Route::group(['prefix' => 'user'], function () {
            Route::controller(UserController::class)->group(function () {
                Route::get('/getAllUser', 'getAllUser')->name('getAllUser')->name('getAllUser');
                Route::post('/addUser', 'addUser')->name('addUser')->name('addUser');
                Route::delete('/deleteUser/{id}', 'deleteUser')->name('deleteUser')->name('deleteUser');
            });
        });

        // Protected Routes for LoanProductController
        Route::group(['prefix' => 'product'], function () {
            Route::controller(LoanProductController::class)->group(function () {
                Route::post('/addLoanProduct', 'addLoanProduct')->name('addLoanProduct');
                Route::get('/deleteLoanProduct/{id}', 'deleteLoanProduct')->name('deleteLoanProduct');
                Route::post('/addCarouselImage', 'addCarouselImage')->name('addCarouselImage');
                Route::get('/deleteCarouselImage/{id}', 'deleteCarouselImage')->name('deleteCarouselImage');
            });
        });

        Route::group(['prefix' => 'about'], function () {

            // protected Routes for AboutUsController
            Route::controller(AboutUsController::class)->group(function(){
                // For About Us Route
                Route::post("/addAbout","addAbout")->name('addAbout');
                Route::get("/deleteAbout/{id}","deleteAbout")->name('deleteAbout');
            });

        });

    });
    // Protected Routes end


    // middleware for feature enable
    Route::group(['middleware' => 'featureCheck'], function () {

        Route::group(['prefix' => 'blog'], function () {

            Route::middleware(['userAuth'])->group(function () {
                    // protected routes for blog crud
                    // Routes for blogController
                    Route::controller(BlogController::class)->group(function () {
                        Route::post('/addBlog', 'addBlog')->name('addBlog');
                        Route::get('/deleteBlog/{id}', 'deleteBlog')->name('deleteBlog');
                        Route::delete('/deleteImage/{id}', 'deleteImage')->name('deleteImage');
                        Route::put('/approveBlog/{id}', 'approveBlog')->name('approveBlog');
                    });
            });

            Route::controller(BlogController::class)->group(function () {
                Route::get('/getAllBlog', 'getAllBlog')->name('getAllBlog');
                Route::get('/getBlogById/{id}', 'getBlogById')->name('getBlogById');
            });
        });



    });


});

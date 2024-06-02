<?php

namespace App\Models;

class Constants {

    public function getConstants() {
        return [
            "USER_ALREADY_EXIST" => "User Already Exists with this number/email",
            "OTP_NOT_MATCHED" => "OTP did not matched",
            "OTP_MATCHED" => "OTP Matched",
            "OTP_MAIL_SENT" => "OTP Mail Sent!",
            "UNAUTHORIZED_CREDENTIALS" => "Invalid Username or Password",
            "MAIL_USERNAME"=>env("MAIL_USERNAME"),
            "LOG_OUT" => "Successfully Logged Out!",
            "TOKEN_EXPIRED" => "Unauthenticated!",
            "STEP_NOT_FOUND" => "Step Not Foun!",
            "LEAD_CREATE" => "Lead Created!",
            "STEP_REQUIRED" => "Step is Required!",
            "NEXT_STEP_SAVED" => "Next Step Saved!",
            "STEP_SAVE" => "Saved Successfully!",
            "STEP_TWO" => "Step Two Saved!",
            "LEAD_USER" => "Lead User Not Found!",
            "ALREADY_UPDATED" => "Already Updated!",
            "COSIGNER_DEACTIVE" => "Cosigner Deleted!",
            "USER_DOESNOT_EXIST" => "User Doesn't Exist!",
            "FILE_MISSING" => "Some File Missing!",
            "FILE_UPLOADED" => "All Files Uploaded!",
            "EMAIL_TAKEN" => "This Email Already Taken!",
            "MOBILE_TAKEN" => "This Mobile Number Already Taken!",
            "EMAIL_MOBILE" => "Email Or Mobile Already Taken!",
            "INVALID_REQUEST" => "Request is Invalid!",
            "MAIL_NOT_FOUND" => "Mail Not Found!",
            "SUCCESSFULL" => "Successfully Comleted",
            "WRONG" => "Something Goes Wrong! Try Again Later!",
            "REGISTER_MAIL" => env("USER_REGISTER"),
            "LOGIN_MAIL" => env("USER_LOGIN"),
            "COSIGNER_REGISTER" => env("COSIGNER_REGISTER"),
            "LOGGED_IN" => "Logged In Successfully!",
            "COUNTRY_ADDED" => "Country Added!",
            "COUNTRY_UPDATE" => "Country Updated!",
            "COUNTRY_DELETE" => "Country Deleted!",
            "STATE_ADD"=>"State Added!",
            "STATE_UPDATE"=>"State Updated!",
            "STATE_DELETE"=>"State Deleted!",

            "CITY_ADD"=>"City Added!",
            "CITY_UPDATE"=>"City Updated!",
            "CITY_DELETE"=>"City Deleted!",


            "COURSE_ADD"=>"Course Added!",
            "COURSE_UPDATE"=>"Course Updated!",
            "COURSE_DELETE"=>"Course Deleted!",


            "LOANTYPE_ADD"=>"Loan Type Added!",
            "LOANTYPE_UPDATE"=>"Loan Type Updated!",
            "LOANTYPE_DELETE"=>"Loan Type Deleted!",


            "EMPSTATUS_ADD"=>"Employeement Status Added!",
            "EMPSTATUS_UPDATE"=>"Employeement Status Updated!",
            "EMPSTATUS_DELETE"=>"Employeement Status Deleted!",

            "INVALID_LEAD_ID" => "Invalid Lead ID!",
            "LEAD_STATUS_UPDATE"=>"Lead Status Updated!",

            "LEAD_STATUS_UPDATE_ERROR"=>"Can not Update Status on this stage!",

            "UNAUTHENTICATE_USER"=>"Unauthenticated User!",
            "UNABLE_TO_UPDATE"=>"Unable to Update Data!",
            "UNABLE_TO_DELETE"=>"Unable to Delete Data!",

            "UPDATE_STEP"=>"Step Updated!",

            "SUBMIT" => "Application Submitted Successfully! Please wait for the approval!",

            "DUPLICATE_NUMBER" => "Duplicate Number Found",
            "DUPLICATE_EMAIL" => "Duplicate Email Found",


            "TESTIMONIAL_ADD" => "Testimonial Added",
            "TESTIMONIAL_UPDATE" => "Testimonial Updated",
            "TESTIMONIAL_DELETE" => "Testimonial Deleted",

            "RELATIONSHIP_ADD" => "Relationship Added",
            "RELATIONSHIP_UPDATE" => "Relationship Updated",
            "RELATIONSHIP_DELETE" => "Relationship Deleted",


            "DOCUMENT_EMPTY_ERROR" => "No Documents Found",

            "MOBILE_EXISTS" => "This Number Exists with Another Records",

            "EMAIL_EXISTS" => "This Email Exists with Another Records",

            "INVALID_BORROWER" => "The Borrower is Invalid!",
            "CONSENT_ERROR" => "Please Provide ConsentGiven!",

            "CONSENT_VERIFY" => "Consent Verified Successfully!",

            "CONSENT_ALREADY_VERIFY" => "Consent Already Verified!",

            "DOCUMENT_ADD" => "Document Added!",

            "DOCUMENT_UPDATE" => "Document Updated!",


            "DOCUMENT_DELETE" => "Document Deleted!",

            "INVALID_INDIVIDUAL_TYPE" => "Invalid Individual Type!",

            "COMMENT_ADDED" => "Comment Added!",

            "UNABLE_TO_UPLOAD" => "You can not upload this document",

            "UPLOAD_ERROR" => "You can upload only one document",

            "NOT_AUTHORIZED" => "You are not authorized to upload document",

            "LEADID_ACCESS_ERROR" => "You can not access lead ID on this stage",

            "BLOG_ADD"=>"Blog Added!",

            "BLOG_UPDATE"=>"Blog Updated!",

            "BLOG_DELETE"=>"Blog Deleted!",

            "INVALID_BLOG_ID"=>"Invalid Input!",

            "ROLE_PERMISSION_ERROR"=>"You are not authorized to perform the action!",

            "BLOG_IMAGE_DELETE"=>"Blog Image Deleted!",

            "USER_ADD"=>"User Added Successfully!",

            "USER_DELETE"=>"User Deleted Successfully!",

            "USER_UPDATE" => "User Updated Successfully!",

            "BLOG_APPROVE" => "Blog Approved Successfully!",

            "UNABLE_TO_APPROVE"=>"Unable to approve!",

            "LOAN_PRODUCT_ADD"=>"Loan Product Added!",

            "LOAN_PRODUCT_UPDATE"=>"Loan Product Updated!",

            "LOAN_PRODUCT_DELETE"=>"Loan Product Deleted!",

            "ADD_ABOUT"=>"About Content Added Successfully!",

            "UPDATE_ABOUT"=>"About Content Updated Successfully!",

            "DELETE_ABOUT"=>"About Content Deleted Successfully!",

            "CONTACT_US_MAIL" => "Your Contact Mail Sent!",

            "PERCENTAGE_START_ERROR" => "Percentage Start should be less than  Percentage End!",

            "PERCENTAGE_END_ERROR" => "Percentage End should be greater than Percentage Start!",

            "CAROUSEL_ADD" => "Carousel Image Added Successfully!",

            "CAROUSEL_UPDATE" => "Carousel Details Updated Successfully!",

            "CAROUSEL_DELETE" => "Carousel Deleted Successfully!",

            "ACCESS_DENIED" => "Route not Found!",

            "CACHE_UPDATE" => "Cache Updated!"

        ];
    }
}

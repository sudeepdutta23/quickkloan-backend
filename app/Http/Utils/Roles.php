<?php

    namespace App\Http\Utils;

    use Config;

    use App\Models\RoleMaster;

    class Roles {

        public function UserRoles(){

            try{

                $UserRoles = RoleMaster::where('status',1)
                ->where('role','User')->pluck('id')->toArray();
                return $UserRoles;
            }
            catch(\Exception $e){
                return $e;
            }

            // return explode(",", Config::get('customConfig.Role.is_user'));


        }


        public function AdminRoles() {

            try{

                $AdminRoles = RoleMaster::where('status',1)
                ->where('role','!=','User')->pluck('id')->toArray();
                return $AdminRoles;
            }
            catch(\Exception $e){
                return $e;
            }


            // return explode(",", Config::get('customConfig.Role.is_admin'));
        }
    }
?>

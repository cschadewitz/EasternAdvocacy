<?php
    namespace Lasso\Subscribe\Models;

    use Model;

    class Subscribe extends Model
    {
        protected $table = 'lasso_subscribe_subscribers';
        protected $primaryKey = 'uuid';

        protected $fillable = [
            'name',
            'email',
            'zip',
            'type',
            'verificationDate'
        ];

        protected $guarded = [
            'uuid'
        ];

        public function scopeEmail($query, $email){
            return $query->where('email', '=', $email);
        }

        public function scopeName($query, $name){
            return $query->where('name', '=', $name);
        }

        public function scopeZip($query, $zip){
            return $query->where('zip', '=', $zip);
        }

        public function scopeType($query, $type){
            return $query->where('type', '=', $type);
        }

        public function  scopeUUID($query, $uuid){
            return $query->where('uuid', '=', $uuid);
        }

        public function scopeNotVerified($query){
            return $query->where('verificationDate', 'IS', 'NULL');
        }

        public function generateUUID(){
            return uniqid();
        }

        public function type2int($type){
            if(!empty($type)){
                if(strcmp($type, "student")==0){
                    return 1;
                }
                elseif(strcmp($type, "alumni")==0){
                    return 2;
                }
                elseif(strcmp($type, "friend")==0){
                    return 3;
                }
                return 4;
            }
            else{
                throw new \Exception(sprintf('ERROR: Empty type; Dropdown not selected.'));
            }
        }

        public function validEmail($email){
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return 1;
            }
            return 0;
        }

        public function uniqueEmail($email){
            $test = \lasso\subscribe\models\Subscribe::Email($email);
            if($test->count() > 0){
                return 0;
            }
            return 1;
        }

        public function uniqueName($name){
            $test = \lasso\subscribe\models\Subscribe::Name($name);
            if($test->count() > 0){
                return 0;
            }
            return 1;
        }

    }
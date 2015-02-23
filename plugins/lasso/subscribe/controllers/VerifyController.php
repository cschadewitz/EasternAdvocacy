<?php
    namespace Lasso\Subscribe\Controllers;

    class VerifyController extends \System\Classes\Controller{
        public function commit($uuid){
            $subscription = new \lasso\subscribe\models\Subscribe;
            if($subscription->UUID($uuid)->count() == 1){
                $verifier = \lasso\subscribe\models\Subscribe::find($uuid);
                $verifier->verificationDate = date('Y-m-d H:i:s');
                $verifier->save();
                return "Account Verified";
            }
            else{
                return "Invalid Authentication Token";
            }

        }
    }
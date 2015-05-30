<?php
    namespace Lasso\Subscribe\Controllers;

    class VerifyController extends \System\Classes\Controller
    {
        public function commit($uuid)
        {
            if(\Lasso\Subscribe\Models\Subscribe::UUID($uuid)->count() == 1){
                $verifier = \Lasso\Subscribe\Models\Subscribe::find($uuid);
                $verifier->verificationDate = date('Y-m-d H:i:s');
                $verifier->save();
                return redirect('/archive/1');
            }
            else{
                return redirect('/');
            }

        }
    }
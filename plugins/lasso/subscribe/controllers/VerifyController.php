<?php
    namespace Lasso\Subscribe\Controllers;

    use Lasso\Subscribe\Models\Subscribe;

    class VerifyController extends \System\Classes\Controller
    {
        public function commit($uuid)
        {
            $subscription = new Subscribe;
            if($subscription->UUID($uuid)->count() == 1){
                $verifier = Subscribe::find($uuid);
                $verifier->verificationDate = date('Y-m-d H:i:s');
                $verifier->save();
                return redirect('/archive/1');
            }
            else{
                return redirect('/');
            }

        }
    }
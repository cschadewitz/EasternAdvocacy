<?php
    namespace Lasso\Petitions\Components;

    use Illuminate\Support\Facades\DB;
    use Lasso\Petitions\Controllers\Signatures;

    class Post extends \Cms\Classes\ComponentBase
    {
        public function componentDetails()
        {
            return [
                'name' => 'Petition Posting',
                'description' => 'Displays the front end representation of the petition to the end users.',
            ];
        }

        public function defineProperties()
        {
            return [
                'petition' => [
                    'title' => 'Which petition',
                    'type' => 'dropdown',
                    'default' => 'Random Petition',
                    'placeholder' => 'Select Petition'
                ]
            ];
        }

        public function getPetitionOptions()
        {
            $petitions = \Lasso\Petitions\Models\Petitions::all();
            $result = [];

            foreach ($petitions as $code=>$data)
                $result[$data['pid']] = $data['title'];

            return $result;
        }

        public function getPetition()
        {
            $petition = \Lasso\Petitions\Models\Petitions::GetPetition($this->property('petition'))->first();
            $result = [];
            foreach($petition as $code=>$data)
            {
                $result[$code] = $data;
            }
            return $result;
        }

        public function signPetition()
        {
            $name = post('name');
            $email = post('email');
            $mailingAddress = post('mailingaddress');
            $city = post('city');
            $zip = post('zip');
            $pid = post('pid');

            if ( empty($name) )
                throw new \Exception(sprintf('Please enter your name.'));
            if ( empty($email) )
                throw new \Exception(sprintf('Please enter your email address.'));
            if(empty($mailingAddress))
                throw new \Exception(sprintf('Enter your registered mailing address'));
            if ( empty($zip) )
                throw new \Exception(sprintf('Please enter your zip code.'));
            if((new \lasso\petitions\models\Signatures)->SignatureValid($email, $pid) == 0)
            {
                \Flash::error('You have already signed this petition!');
            }
            else{
                $signature = new \lasso\petitions\models\Signatures;
                $signature->name = $name;
                $signature->email = $email;
                $signature->zip = $zip;
                $signature->address = $mailingAddress;
                $signature->city = $city;
                $signature->pid = $pid;
                $signature->save();
                $petition = \lasso\petitions\models\Petitions::pid($pid);
                $petition->increment('signatures');
              //  $petition->save();
            }
        }

        public function onRun()
        {
            $this->page['petitionInfo'] = $this->getPetition();
            $this->addCss('/plugins/lasso/petitions/assets/css/style.css');
        }
    }
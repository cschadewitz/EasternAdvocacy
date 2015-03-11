<?php
    namespace Lasso\Petitions\Components;

    use Cms\Classes\ComponentBase;
    use Lasso\Petitions\Controllers\Petitions;

    class ViewPost extends ComponentBase
    {
        public $petition;

        public function componentDetails()
        {
            return [
                'name' => 'Frontend Petition View',
                'description' => 'Provides a frontend view for petitions.'
            ];
        }

        public function defineProperties()
        {
            return [
                'slug' => [
                    'title' => 'SLUG',
                    'description' => 'SLUG of the petition being viewed.',
                    'default' => '{{ :slug }}',
                    'type' => 'string',
                ],
            ];
        }

        public function onRun()
        {
            $slug = $this->param('slug');
            $this->petition = \Lasso\Petitions\Models\Petitions::url($slug)->first();
            $this->page['petitionInfo'] = $this->petition;
            $this->addCss('/plugins/lasso/petitions/assets/css/style.css');
        }

        public function signPetition(){
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
    }
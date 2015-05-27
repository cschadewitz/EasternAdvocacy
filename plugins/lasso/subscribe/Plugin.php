<?php
    namespace Lasso\Subscribe;

    use System\Classes\PluginBase;
    use Illuminate\Support\Facades\DB;
    use Backend;

    class Plugin extends PluginBase
    {
        public function pluginDetails()
        {
            return [
                'name' => 'Subscribe Plugin',
                'description' => 'Provides user the ability to subscribe to the Eastern Advocacy mailing list.',
                'author' => 'Daniel Schultz',
                'icon' => 'icon-envelope'
            ];
        }

        public function registerComponents()
        {
            return [
                '\lasso\Subscribe\Components\Form' => 'SubForm'
            ];
        }

        public function registerMailTemplates()
        {
            return [
                'lasso.subscribe::mail.verify' => 'Verification email sent to new subscribers.',
            ];
        }

        public function registerSchedule($schedule)
        {
            // Check if old unverified entries need to be deleted
            $schedule->call(function(){
                $results = Db::select('select * from lasso_subscribe_subscribers where verificationDate IS NULL');
                $now = date('Y-m-d H:i:s');
                $timeLimit = 60 * 60 * 24;
                foreach($results as $val){
                    $checkIn = $val->created_at;
                    if(strtotime($now) - strtotime($checkIn) > $timeLimit){
                        Db::delete('delete from lasso_subscribe_subscribers where uuid = "'.$val->uuid.'"');
                    }
                }
            })->everyFiveMinutes();
        }

        public function registerNavigation()
        {
            return [
                'subscribe' => [
                    'label' => 'Subscribe',
                    'url' => Backend::url('lasso/subscribe/subscribe'),
                    'icon' => 'icon-check-square',
                    'permissions' => ['lasso.subscribe.*'],
                    'order' => 501,
                ]
            ];
        }
    }
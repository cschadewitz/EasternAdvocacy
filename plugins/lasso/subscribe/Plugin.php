<?php
    namespace Lasso\Subscribe;

    use System\Classes\PluginBase;
    use Illuminate\Support\Facades\DB;
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
                $results = Db::select('select * from subscribers where verificationDate IS NULL');
                $now = date('Y-m-d H:i:s');
                $timeLimit = 3 * 60;
                foreach($results as $val){
                    $checkIn = $val->created_at;
                    if(strtotime($now) - strtotime($checkIn) > $timeLimit){
                        Db::delete('delete from subscribers where uuid = "'.$val->uuid.'"');
                    }
                }
            })->everyFiveMinutes();
        }
    }
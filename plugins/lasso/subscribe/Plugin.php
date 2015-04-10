<?php
    namespace Lasso\Subscribe;

    use Event;
    use Backend;
    use BackendMenu;
    use RainLab\User\Controllers\Users as UserController;
    use RainLab\User\Models\User as UserModel;
    use Lasso\Subscribe\Models\UserSubscribe;
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

        public function registerMailTemplates(){
            return [
                'lasso.subscribe::mail.verify' => 'Verification email sent to new subscribers.',
            ];
        }



        public function boot(){
            UserModel::extend(function($model) {
                $model->hasOne['usersubscribe'] = ['Lasso\Subscribe\Models\UserSubscribe', 'table' => 'lasso_subscribe_user_subscribes', 'key' => 'user_id'];
            });
            UserController::extendFormFields(function($form, $model, $context) {

                if(!$model instanceof UserModel)
                        return;

                if(!$model->exists)
                        return;
                //dump($model);

                UserSubscribe::getModel($model);

                $form->addTabFields([
                    'usersubscribe[verificationDate]'  => [
                        'label' => 'Subscribed?',
                        'tab'   => 'Subscription',
                        'type'  => 'text',
                        'disabled' => 'true'
                        //'path'  => '~/plugins/lasso/subscribe/controllers/subscribers/_verificationDate.htm'
                    ]
                ]);
            });
            Event::listen('backend.menu.extendItems', function($manager){

                $manager->addSideMenuItems('RainLab.User', 'user', [
                    'subscribers'   => [
                        'label'         => 'Subscribers',
                        'url'           => Backend::url('lasso/subscribe/subscribers'),
                        'icon'          => 'icon-user',
                        'owner'         => 'RainLab.User',
                        'permissions'   => ['lasso.subscribers.*'],
                    ],
                    'users'         => [
                        'label'         => 'Users',
                    ]
                ]);
            });
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
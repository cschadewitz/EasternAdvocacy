<?php
    namespace Lasso\Subscribe;

    use Event;
    use Lasso\Subscribe\Models\UserExtension;
    use Backend;
    //use Lasso\Subscribe\Models\Subscribe;
    use System\Classes\PluginBase;
    use Illuminate\Support\Facades\DB;
    use RainLab\User\Models\User as UserModel;
    use RainLab\User\Controllers\Users as UserController;
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

        public $require = ['RainLab.User'];

        public function registerComponents()
        {
            return [
                '\Lasso\Subscribe\Components\Form' => 'SubForm',
                '\Lasso\Subscribe\Components\UserSubscribe' => 'user_extended'
            ];
        }

        public function registerPermissions()
        {
            return [
                'lasso.subscribe.access_subscribers'  => ['tab' => 'Users', 'label' => 'View Subscribers'],
                'lasso.subscribe.edit_subscribers' => ['tab' => 'Users', 'label' => 'Edit Subscribers']
            ];
        }

        public function registerReportWidget()
        {
            return [
                '\Lasso\Subscribe\ReportWidgets\Subscriptions' => [
                    'label' => 'Subscriptions',
                    'context' => 'dashboard',
                ],
            ];
        }


        public function registerMailTemplates()
        {
            return [
                'lasso.subscribe::mail.verify' => 'Verification email sent to new subscribers.',
            ];
        }

        public function boot(){
            $this->extendUserModel();
            $this->extendUserController();
            $this->extendUserMenu();
        }

        protected function extendUserModel()
        {
            UserModel::extend(function ($model) {
                $model->hasOne['extension'] = ['Lasso\Subscribe\Models\UserExtension', 'table' => 'lasso_subscribe_user_extensions', 'foreign_key' => 'user_id'];
                //$model->visible = ['id'];
                //UserExtension::getModel($model);
            });
        }
        protected function extendUserController()
        {
            UserController::extendListColumns(function($list,$model){
                if(!$model instanceof UserModel)
                    return;


                $list->addColumns([
                    'extension[verificationDate]'  => [
                        'label' => 'Subscribed?',
                        'type' => 'partial',
                        'path' => '$/lasso/subscribe/controllers/subscribe/_subscribed.htm',
                    ],
                    'extension[affiliation]' => [
                        'label' => 'University Affiliation',
                        'type' => 'text'
                    ]
                ]);
            });
            UserController::extendFormFields(function($form, $model, $context) {

                if(!$model instanceof UserModel)
                    return;

                if(!$model->exists)
                    return;
                //dump($model);

                UserExtension::getModel($model);
                $form->addTabFields([
                    'extension[verificationDate]'  => [
                        'label' => 'Subscribed',
                        'tab'   => 'Advocacy',
                        'type'  => 'Partial',
                        //'disabled' => 'true'
                        'path'  => '$/lasso/subscribe/controllers/subscribe/_subscribed.htm'
                    ],
                    'extension[affiliation]'  => [
                        'label' => 'University Affiliation',
                        'tab'   => 'Advocacy',
                        'type'  => 'text',
                        'disabled' => 'true'

                    ]
                ]);
            });
        }
        protected function extendUserMenu()
        {
            Event::listen('backend.menu.extendItems', function($manager){
                $manager->addSideMenuItems('RainLab.User', 'user', [
                    'subscribers'   => [
                        'label'         => 'Subscribers',
                        'url'           => Backend::url('lasso/subscribe/subscribe'),
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
                //Subscribe::where('verificationDate', null);
                $now = date('Y-m-d H:i:s');
                //DateTime::getTimestamp();
                $timeLimit = 3 * 60;
                foreach($results as $val){
                    $checkIn = $val->created_at;
                    if(strtotime($now) - strtotime($checkIn) > $timeLimit){
                        Db::delete('delete from subscribers where uuid = "'.$val->uuid.'"');
                        //Subscribers::where("uuid", $val->uuid)->delete();
                    }
                }
            })->everyFiveMinutes();
        }
    }
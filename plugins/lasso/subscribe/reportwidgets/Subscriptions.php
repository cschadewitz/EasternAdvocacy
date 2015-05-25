<?php
/**
 * Created by PhpStorm.
 * User: schad_000
 * Date: 2/26/2015
 * Time: 2:15 AM
 */

namespace Lasso\Subscribe\ReportWidgets;

use App;
use Backend\Classes\ReportWidgetBase;
use Cms\Classes\Page;
use Lasso\Subscribe\Models\Subscribe;
use Lasso\Subscribe\Models\UserExtension;
use RainLab\User\Models\User as UserModel;

class TopViewed extends ReportWidgetBase {

    public $posts;
    public $postPage;
    public $numberOfPosts;

    public $defaultAlias = 'subscriptions';

    public function widgetDetails()
    {
        return [
            'name'        => 'Subscribers',
            'description' => 'Shows the number of subscribers.'
        ];
    }

    public function defineProperties()
    {
        return [
            'includeUnsub' => [
                'title'    => 'Include unsubscribed users',
                'type'     => 'checkbox',
                'default'  => ''
            ]
        ];
    }

    public function render()
    {

        return $this->makePartial('reportWidget');
    }

    public function assignVars()
    {
        $this->vars['subs'] = Subscribe::where('verificationDate', '!=', null)->count();
        $this->vars['users'] = UserModel::where('isActivated', '!=', 0)->extension->where('verificationDate', '==', null)->count();
        $this->vars['userSubs'] = UserModel::where('isActivated', '!=', 0)->extension->where('verificationDate', '!=', null)->count();
        $this->vars['includeUnsub'] = $this->page['includeUnsub'] = $this->property('includeUnsub');
    }
}
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
use Lasso\Subscribe\Models\Subscribe;
use Lasso\Subscribe\Models\UserExtension;

class Subscriptions extends ReportWidgetBase {


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
                'title'    => 'Include un-subscribed users',
                'type'     => 'checkbox',
                'default'  => 'false'
            ]
        ];
    }

    public function render()
    {
        $this->assignVars();
        return $this->makePartial('reportWidget');
    }

    public function assignVars()
    {
        $this->vars['subs'] = $subs = Subscribe::verified()->count();
        $this->vars['userUnsubs'] = $userUnsubs = UserExtension::subscribers(true)->count();
        $this->vars['userSubs'] = $userSubs = UserExtension::subscribers()->count();
        $this->vars['subsTotal'] = $subs + $userSubs;
        $this->vars['usersTotal'] = $userUnsubs + $userSubs;
        $this->vars['includeUnsub'] = $this->page['includeUnsub'] = $this->property('includeUnsub');
    }
}
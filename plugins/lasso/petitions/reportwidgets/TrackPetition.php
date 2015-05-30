<?php
/**
 * Created by PhpStorm.
 * User: schad_000
 * Date: 2/26/2015
 * Time: 2:15 AM
 */

namespace Lasso\Petitions\ReportWidgets;

use App;
use Backend\Classes\ReportWidgetBase;
use Lasso\Petitions\Models\Petitions;
use Lasso\Petitions\Models\Signatures;

class Subscriptions extends ReportWidgetBase {


    public $defaultAlias = 'topPetitions';

    public function widgetDetails()
    {
        return [
            'name'        => 'Track Petition',
            'description' => 'Tracks a single petitions progress towards goal'
        ];
    }

    public function defineProperties()
    {

    }

    public function render()
    {
        $this->assignVars();
        return $this->makePartial('reportWidget');
    }

    public function assignVars()
    {

    }
}
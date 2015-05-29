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
            'name'        => 'Top Petitions',
            'description' => 'Shows the number of subscribers.'
        ];
    }

    public function defineProperties()
    {
        return [
            'numberOfPetitions' => [
                'title'             => 'Number of Top Petitions',
                'description'       => 'Number of top petitions to display.',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the petitions per page value',
                'default'           => '5',
            ],
            'percentage' => [
                'title'    => 'Use percent to goal as measurement',
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
        $this->vars['numberOfPetitions'] = $this->numberOfPetitions = $this->property('numberOfPetitions');
        $this->vars['percentage'] = $this->percentage = $this->property('percentage');
        if($this->percentage) {
            $this->vars['petitions'] = Petitions::sortByProgress()->take($this->numberOfPetitions)->get();
        }
        else {
            $this->vars['petitions'] = Petitions::sortBySigCount()->take($this->numberOfPetitions)->get();
        }

    }
}
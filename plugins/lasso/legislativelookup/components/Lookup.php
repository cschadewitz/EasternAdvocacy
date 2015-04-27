<?php namespace Lasso\LegislativeLookup\Components;

use Cms\Classes\ComponentBase;
use Lasso\LegislativeLookup\Models\Address;
use Lasso\LegislativeLookup\Models\Legislator;

class Lookup extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'Lookup Component',
            'description' => 'Legislator lookup component'
        ];
    }

    public function defineProperties() {
        return [
            'visibleOutput' => [
                'title'         => 'Visible Output',
                'description'   => 'Output results visibly, as opposed to returning JSON data',
                'default'       => 'visible',
                'type'          => 'dropdown',
                'options'       => ['visible'=>'Visible', 'json'=>'JSON']
            ]
        ];
    }

    /**
     * array of state Legislators
     * @var array
     */
    public $legislators;

    /**
     * parseAddress - main method of plugin for parsing the address
     * @return array|mixed
     */
    public function parseAddress() {
        /*Validator::create([
        //todo - do this later
        ]);*/

        $address = post('address');
        $city = post('city');
        $state = post('state');
        $zip = post('zip');
        $legislatorRecord = null;//instanciate here for scope purposes - we'll be using it soon

        $location = array($address, $city, $state, $zip);
        $coordinates = Address::parseNewAddress($location);

        if (!$coordinates->district) {
            $legislators = Legislator::getJSONLegislatorsFromAddress($coordinates);

            foreach ($legislators as $legislator) {
                $legislatorRecord = Legislator::UUID($legislator->id)->get();

                if (!$legislatorRecord) {
                    $legislatorRecord = Legislator::getLegislatorFromJSON($legislator);
                }
            }
        }
        unset($legislators);
        $legislators = array();
        if ($legislatorRecord) {
            $districtIds = Address::district($legislatorRecord->district);
            foreach ($districtIds->get() as $districtIndex) {
                $district = $districtIndex->representative_id;
                $legislator = Legislator::district($district)->first();
                array_push($legislators, $legislator);
            }
        }
    }

    /**
     * @param $legislators
     * @return mixed
     */
    public function displayResults($legislators) {
        if($this->properties['visibleOutput']=='visible') {
            $this->legislators = $legislators;
        } else {
            return $legislators;
        }
    }
}
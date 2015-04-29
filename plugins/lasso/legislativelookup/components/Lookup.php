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
        echo "Do something!";

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
        displayResults($legislators);
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

    /**
     * @param $address object with coordinates found
     * @return JSON from API to get
     */
    public function infoFromObject($address) {
        $street_address = array($address->address, $address->city, $address->state, $address->zip);
        return infoFromArray($street_address);
    }
    /**
     * @param $address array with street address
     * @return JSON from API to get
     */
    public function infoFromArray($address) {
        $street_address = $address[0] . $address[1] . $address[2] . $address[3];
        return infoFromString($street_address);
    }
    /**
     * @param $address string
     * @return JSON from API to get
     */
    public function infoFromString($address) {
        return Legislator::getJSONLegislatorsFromAddress($address);
    }
}
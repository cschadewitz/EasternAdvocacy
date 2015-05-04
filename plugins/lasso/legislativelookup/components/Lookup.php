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
    public function onParseAddress() {
        /*Validator::create([
        //todo - do this later
        ]);*/

        //STEP 1 - read address
        //STEP 2 - check cache for address
            //if not present, add address and query coords
            //else if present, retrieve coords
        //STEP 3 - check legislative cache for coords (district?)
            //if not present querey for legislators
            //else retrieve legislators
        //STEP 4 - pass legislators to display()

        //STEP 1
        $address = post('address');
        $city = post('city');
        $state = post('state');
        $zip = post('zip');
        $location = array($address, $city, $state, $zip);
        $coordinates = Address::parseNewAddress($location);//should handle both cached and non-cashed now
        $legislatorRecord = null;//instanciate here for scope purposes - we'll be using it soon

        //so we have our address added in successfully now, time to dig deeper,
        // if the district has been set, that tells us we've been here before and can
        // just query for our legislators, else we'll need to go to the api for them
        if (!isset($coordinates->district)) {
            $legislators = Legislator::getJSONLegislatorsFromAddress($coordinates->lat, $coordinates->long);

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
        //DEBUGIN
        $leg = Legislator::create([
            'uuid'=> "WA001",
            'state'=> "WA",
            'district'=> "9",
            'first_name'=> "Bob",
            'last_name'=> "Bobson",
            'party'=> "R",
            'email'=> "bob@senate.com",
            'photo_url'=> "http://imgur.com/photo.png",
            'office_phone'=> "509-888-5700",
            'url'=> "http://wastate.gov"
        ]);
        $leg->save();
        array_push($legislators, $leg);
        echo $legislators;
        echo $leg;
        $this->legislators=$legislators;//FOR TRUCKS SAKE THIS TOOK FOREVER, HAVE TO EXPLICITLY ASSIGN THE VALUE BACK TO THE GLOBAL VARIABLE
        //end debuggin
        return $legislators;
    }

    /**
     * @param $legislators
     * @return mixed
     */
    public function displayResults($legislators) {
        if($this->properties['visibleOutput']=='visible') {
            $this->legislators = $legislators;
        } else {
            return Legislator::create([
                'uuid'=> "WA001",
                'state'=> "WA",
                'district'=> "9",
                'first_name'=> "Bob",
                'last_name'=> "Bobson",
                'party'=> "R",
                'email'=> "bob@senate.com",
                'photo_url'=> "http://imgur.com/photo.png",
                'office_phone'=> "509-888-5700",
                'url'=> "http://wastate.gov"
            ]);
            //return $legislators;
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
     * @param $address string303
     * @return JSON from API to get
     */
    public function infoFromString($address) {
        return Legislator::getJSONLegislatorsFromAddress($address);
    }

    /**
     * Wrapper method
     * @param $address
     * @return mixed
     */
    public function info($address) {
        return infoFromString($address);
    }
}
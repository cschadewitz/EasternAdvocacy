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
        $legislatorRecord = null;//instanciate here for scope purposes
        if ($coordinates->districtExists()) {//if we don't have a district associated with our address, then we have no legislators yet
            $lat = $coordinates->getLat();
            $long = $coordinates->getLong();
            $legislators = Legislator::getJSONLegislatorsFromCoords($lat, $long);//pull from API
            //returning empty at this time!!!!!!
            print_r($legislators);
            var_dump($legislators);
            foreach ($legislators as $legislator) {
                $legislatorRecord = Legislator::UUID($legislator->id)->get();
                if (!(isset($legislatorRecord))) {
                    $legislatorRecord = Legislator::getLegislatorFromJSON($legislator);
                }
            }//endfor
            print_r($legislators);
            var_dump($legislators);
            $coordinates->district = json_decode(reset($legislators))->{'district'};//and assign the district value to cross refrence next time
        }//endif
        else {//else we already have the records and just have to grab them from the cache
            $legislatorRecord = Legislator::getLegislatorByDistrict($coordinates->district);
        }
        unset($legislators);
        $legislators = array();
        var_dump($legislatorRecord);//null still, so print_r doesn't catch it
        print_r($legislatorRecord);
        //so if were here and legislatorRecord is undefined, all this fails anyways
        //so lets define a new block to populate it

        if (!(is_null($legislatorRecord->district))) {
            $districtIds = Address::district($legislatorRecord->district);//well, whats here
            foreach ($districtIds->get() as $districtIndex) {
                $district = $districtIndex->representative_id;
                $legislator = Legislator::district($district)->first();
                array_push($legislators, $legislator);
            }
        }
        $this->legislators=$legislators;//FOR TRUCKS SAKE THIS TOOK FOREVER, HAVE TO EXPLICITLY ASSIGN THE VALUE BACK TO THE GLOBAL VARIABLE
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
        $coords = Address::getCoordsFromAPI($address);
        return Legislator::getJSONLegislatorsFromCoords($oords[0], $coords[1]);
    }

    /**
     * Wrapper method
     * @param $address
     * @return JSON with legislator information
     */
    public function info($address) {
        return infoFromString($address);
    }
}
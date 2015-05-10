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
    }//so depricated

    /**
     * array of state Legislators
     * @var array
     */
    public $legislators;

    public function onRun()
    {
        $this->addCss('/plugins/lasso/legislativelookup/assets/css/lookup.css');
    }

    /**
     * parseAddress - main method of plugin for parsing the address
     * @return array|mixed
     */
    public function onParseAddress() {
        /*Validator::create([
        //todo - do this later
        ]);*/

        //STEP 1
        $address = post('address');
        $city = post('city');
        $state = post('state');
        $zip = post('zip');
        $location = array($address, $city, $state, $zip);
        $coordinates = Address::parseNewAddress($location);
        $legislatorRecord = null;//instanciate here for scope purposes
        $legislators = null;//and this
        if ($coordinates->districtNotExists()) {//if we don't have a district associated with our address, then we have no legislators yet
            $legislators = Legislator::getJSONLegislatorsFromCoords(
                $coordinates->getLat(),
                $coordinates->getLong()
            );//pull from API
            foreach (json_decode($legislators) as $legislator) {
                $legislatorRecord = Legislator::UUID($legislator->{'id'})->first();//check the id of our json against the cache
                if (is_null($legislatorRecord)) {
                    $legislatorRecord = Legislator::getLegislatorFromJSON($legislator);//add it
                }
            }//endforeach
            $coordinates->district = $legislatorRecord->district;
            $coordinates->save();
        } else {//else we already have the records and just have to grab them from the cache
            $legislatorRecord = Legislator::getLegislatorByDistrict($coordinates->getDistrict());
        }
        $legislators = Legislator::district($coordinates->getDistrict())->get();
        $this->legislators=$legislators->toArray();
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
        if($address->districtExists())
            return(Legislator::getLegislatorByDistrict($address->district));
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
        $coords = Address::scopeAddress($address)->get();
        return Legislator::getJSONLegislatorsFromCoords($coords[0], $coords[1])->get()->toJson();
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
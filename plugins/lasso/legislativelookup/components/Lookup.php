<?php namespace Lasso\LegislativeLookup\Components;

use Cms\Classes\ComponentBase;
use Lasso\LegislativeLookup\Models\Address;
use Lasso\LegislativeLookup\Models\Legislator;
use Validator;
use ValidationException;

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

    /**
     * onRun - runs at load time, after page and layout have loaded, add our CSS for formatting
     */
    public function onRun()
    {
        $this->addCss('/plugins/lasso/legislativelookup/assets/css/lookup.css');
    }

    /**
     * parseAddress - main method of plugin for parsing the address, takes a
     * bit more since our models are generated here, and we need to tie the
     * districts to the addresses for caching the next time we search
     * @return array|mixed
     */
    public function onParseAddress() {
        $address = post('address');
        $city = post('city');
        $state = post('state');
        $zip = post('zip');
        $location = array($address, $city, $state, $zip);

        $rules = [];
        $rules['address'] = 'alpha_dash';
        $rules['city'] = 'alpha_dash';
        $rules['state'] = 'size:2';
        $rules['zip'] = 'numerical:5';

        $validation = Validator::make($location, $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

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
            $legislatorRecord = Legislator::getLegislatorByDistrict(
                $coordinates->getDistrict(),
                $coordinates->getState()
            );
        }
        $legislators = Legislator::district(
            $coordinates->getDistrict())
            ->state($coordinates->getState())
            ->get();
        $this->legislators=$legislators->toArray();
        return $legislators;
    }

    /**
     * @param $address object with coordinates found
     * @return JSON legislator records
     */
    public function infoFromObject($address) {
        if($address->districtExists())
            return(Legislator::getLegislatorByDistrict($address->district));
        $street_address = array($address->address, $address->city, $address->state, $address->zip);
        return infoFromArray($street_address);
    }
    /**
     * @param $address array with street address
     * @return JSON legislator records
     */
    public function infoFromArray($address) {
        $street_address = $address[0] . $address[1] . $address[2] . $address[3];
        return infoFromString($street_address);
    }
    /**
     * @param $address string containing address
     * @return JSON with legislator information, checking cache first
     */
    public function infoFromString($address) {
        $coords = Address::scopeAddress($address)->get();
        return Legislator::getJSONLegislatorsFromCoords($coords[0], $coords[1])->get()->toJson();
    }

    /**
     * Wrapper method, see infoFromString()
     * @param $address - string containing address
     * @return JSON with legislator information
     */
    public function info($address) {
        return infoFromString($address);
    }
}
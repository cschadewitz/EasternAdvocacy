<?php namespace Lasso\LegislativeLookup\Components;

use Cms\Classes\ComponentBase;
use Lasso\LegislativeLookup\Models\Address;
use Lasso\LegislativeLookup\Models\Legislator;
use Validator;
use ValidationException;
use Flash;

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

        $location = [
            'address' => post('address'),
            'city' => post('city'),
            'state' => post('state'),
            'zip' => post('zip')
        ];
        $rules = [//turns out alpha_dash doesn't allow for spaces, kinda need those for addresses (and some cities)
            'address' => 'regex:/^\w+(\s*\w*)*$/',
            'city' => 'regex:/^\w+(\s*\w*)*$/',
            'state' => 'alpha|size:2',
            'zip' => 'regex:/^\d{5}(-\d{4}){0,1}$/'
        ];
        $messages = [//turns out alpha_dash doesn't allow for spaces, kinda need those for addresses (and some cities)
            'address' => 'Address must not contain special characters, alpha-numeric and spaces only',
            'city' => 'City must not contain special characters, alpha-numeric and spaces only',
            'state' => 'State must be 2 letters',
            'zip' => 'Zipcode must be standard 5 or 9 digit zip code'
        ];

        $validation = Validator::make($location, $rules, $messages);
        if ($validation->fails()) {
            $this->page['message'] = $validation->errors()->toArray();
            return false;
            //throw new ValidationException($validation);
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
            $coordinates->state = $legislatorRecord->state;
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
        $street_address = array('address' => $address->address,
            'city' => $address->city,
            'state' => $address->state,
            'zip' => $address->zip);
        return infoFromArray($street_address);
    }
    /**
     * @param $address array with street address
     * @return JSON legislator records
     */
    public static function infoFromArray($address) {
        $street_address = $address['address'] . $address['city'] . $address['state'] . $address['zip'];
        return Lookup::infoFromString($street_address);
    }
    /**
     * @param $address string containing address
     * @return JSON with legislator information, checking cache first
     */
    public static function infoFromString($address) {
        $coords = Address::address($address)->first();
        $isnew = false;
        if(is_null($coords)) {
            $isnew = true;
            $coords = Address::parseNewAddress(array('address' => $address, 'city' => '', 'state' => '', 'zip' => ''));
        }
        $leg = Legislator::getLegislatorsFromAddress($coords);
        if($isnew) {
            $coords['state'] = $leg->first()['state'];
            $coords['district'] = $leg->first()['district'];
            $coords->save();
        }
        return $leg;
    }

    /**
     * Wrapper method, see infoFromString()
     * @param $address - string containing address
     * @return JSON with legislator information
     */
    public static function info($address) {
        return Lookup::infoFromString($address);
    }
}
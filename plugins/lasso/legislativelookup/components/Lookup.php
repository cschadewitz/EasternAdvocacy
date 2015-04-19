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
     * Street address, read as string
     * @var string
     */
    public $address;
    /**
     * City, read as string
     * @var string
     */
    public $city;
    /**
     * State, read as string
     * @var string
     */
    public $state;
    /**
     * zipcode, read as string
     * @var string
     */
    public $zip;
    /**
     * array of state Legislators
     * @var array
     */
    public $legislators;

    /**
     * parseAddress - main method of plugin for parsing the address
     * @return array|mixed
     */
    public function parseAddress()
    {
        mb_regex_encoding('UTF-8');
        $address = post('address');
        if(mb_ereg('^[a-zA-Z0-9]', $address))
        $city = post('city');
        if(mb_ereg('^[a-zA-Z0-9]', $city))
        $state = post('state');
        if(mb_ereg('^[a-zA-Z]{2}', $state))
        $zip = post('zip');
        if(mb_ereg('^[0-9]{5}?[-][0-9]{4}', $zip))

        $location = array($address, $city, $state, $zip);
        $coordinates = Address::getCoordinatesFromAddress($location);

        if (!$coordinates) {
            //?????? todo $coordinates = Address::getCoordinatesFromAddress($address);
            $legislators = Lookup::getLegislatorsFromAPICoordinatess($coordinates->{'lat'}, $coordinates->{'lng'});

            foreach ($legislators as $legislator) {
                $legislatorRecord = (Legislator::whereraw('$uuid = ? ',
                    array($legislator->id))->first());
                if (!$legislatorRecord) {
                    $legislatorRecord = Legislator::create([
                        'uuid'=> $legislator->id,
                        'state'=> $legislator->state,
                        'district'=> $legislator->district,
                        'first_name'=> $legislator->first_name,
                        'last_name'=> $legislator->last_name,
                        'party'=> $legislator->party,
                        'email'=> $legislator->email,
                        'photo_url'=> $legislator->photo_url,
                        'office_phone'=> $legislator->office_phone,
                        'url'=> $legislator->url
                    ]);
                    $legislatorRecord->save();
                }
                $legislatorRecord = new Legislator();
                $legislatorRecord->zip = $zip;
                $legislatorRecord->openid = $legislatorRecord->openID;
                $legislatorRecord->save();
            }
        }
        unset($legislators);
        $legislators = array();
        if ($legislatorRecord) {
            $districtIds = Address::where('district', '=', $legislatorRecord->district);
            foreach ($districtIds->get() as $districtIndex) {
                $district = $districtIndex->representative_id;
                $legislator = Legislator::where('district', '=', $district)->first();
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
    /**
     * @param $address - Array of address components
     * @return mixed - JSON containing the lat/long coordinates - might normalize here
     */
        public function coordinatesFromAddress($address) {
            $json = file_get_contents(sprintf(
                "https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&key=%s",
                $address[3],
                Settings::get('google_id')
                //$this->property('userGoogleID')
            ));
            $json = json_decode($json)->{'results'};
            $json = json_decode($json)->{'geometry'};
            $json = json_decode($json)->{'location'};
            return json_decode($json);
        }
    /**
     * getLegislatorsFromAPICoordinates - gets the (state) legislators from the
     * api by geocaching
     * @param $lat - latitude we are checking against
     * @param $long - longitude we are checking against
     * @return mixed - json data that includes the legislators we are looking for
     */
    public function getLegislatorsFromAPICoordinates($lat, $long) {
        $json = file_get_contents(htmlspecialchars_decode(sprintf(
            "https://openstates.org/api/v1//legislators/geo/?lat=%s&long=%s&apikey=%s",
            round($lat, 1),
            round($long, 1),
            Settings::get('sunlight_id')
            //$this->property('userSunlightID')
        )));
        return json_decode($json);
    }

    /**
     * getLegislatorsFromAPIDistrict - gets the state legislators from the api based on district number
     * @param district - District of legislators
     * @return mixed - list of state legislators from given district
     */
    public function getLegislatorsFromAPIDistrict($district) {
        $json = file_get_contents(htmlspecialchars_decode(sprintf(
            "https://openstates.org/api/v1//legislators/?state=wa&active=true&district=%s&apikey=%s",
            ltrim($district),
            $this->property('userSunlightID')
        )));
        return json_decode($json);
    }
}
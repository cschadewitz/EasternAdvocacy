<?php namespace Lasso\LegLookup\Components;

use Cms\Classes\ComponentBase;

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
            'userSunlightID' => [
                'title'         => 'Sunlight API Key',
                'description'   => 'API key to access Sunlight OpenState API',
                'default'       => 'edf3725acca94c9a897416cd3517f08e',
                'type'          => 'string'
            ],
            'userGoogleID' => [
                'title'         => 'Google Geocode API Key',
                'description'   => 'API key to access Google Geocode API',
                'default'       => 'AIzaSyAYMLT-JMVwC69aPCFDPUNmc9G8PbtF_Wo',
                'type'          => 'string'
            ],
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
    public $streetAddr;
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
    public $zipCode;
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
        $streetAddr = post('streetAddr');
        $city = post('city');
        $state = post('state');
        $zipCode = post('zipCode');

        $address = array($streetAddr, $city, $state, $zipCode);
        $coords = coordsFromAddress($address);

        if (!$zipRecord) {
            //$representatives = Zip::getFedReps($zipCode);

            $coords = Zip::getCoordsFromZip($zipCode);
            $representatives = Zip::getRepsFromAPICoords($coords->{'lat'}, $coords->{'lng'});

            foreach ($representatives as $rep) {
                $repRecord = (Rep::whereraw('firstName = ? AND lastName = ? ',
                    array($rep->first_name, $rep->last_name))->first());
                if (!$repRecord) {
                    $repRecord = Rep::create(['firstName' => $rep->first_name,
                        'lastName' => $rep->last_name,
                        //'title' => $rep->title,
                        'politicalParty' => $rep->party,
                        //'emailAddress' => $rep->oc_email,
                        //'phoneNumber' => $rep->phone,
                        //'physicalAddress' => $rep->office . ", " . $rep->state,
                        //'expireDate' => $rep->term_end
                    ]);
                    $repRecord->save();
                }
                $zipRecord = new ZipRecord();
                $zipRecord->zip = $zipCode;
                $zipRecord->representative_id = $repRecord->id;
                $zipRecord->save();
            }
        }
        unset($representatives);
        $representatives = array();
        if ($zipRecord) {
            $repsIds = ZipRecord::where('zip', '=', $zipRecord->zip);
            foreach ($repsIds->get() as $zipIndex) {
                $repIndex = $zipIndex->representative_id;
                $rep = Rep::where('id', '=', $repIndex)->first();
                array_push($representatives, $rep);
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
        public function coordsFromAddress($address) {
            $json = file_get_contents(sprintf(
                "https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&key=%s",
                $address[3],
                $this->property('userGoogleID')
            ));
            $json = json_decode($json)->{'results'};
            $json = json_decode($json)->{'geometry'};
            $json = json_decode($json)->{'location'};
            return json_decode($json);
        }
    /**
     * getFedReps - gets list of Federal legislators based on zip code - DEPRICATED
     * @param $zipCode - zip code we are checking against
     * @return mixed - json collection of the legislators returned by the api (0 or more)
     */
    public function getFedReps($zipCode) {
        $json = file_get_contents(sprintf(
            "https://congress.api.sunlightfoundation.com/legislators/locate?zip=%s&apikey=%s",
            $zipCode,
            $this->property('userSunlightID')
        ));
        return json_decode($json)->{'results'};
    }

    /**
     * getCoordsFromZip - gets the geo lookup information based on address fields for further use
     * @param $zipCode - the zip code we are checking agains
     * @return mixed - json data that includes the longitude and latitude that correlate to the zip code
     */
/*    public function getCoordsFromZip($zipCode) {
        $json = file_get_contents(sprintf(
            "https://www.zipcodeapi.com/rest/%s/info.json/%s/degrees",
            $this->property('userZipcodeID'),
            $zipCode
        ));
        return json_decode($json);
    }*/

    /**
     * getRepsFromAPICoords - gets the (state) legislators from the api by geolocationi
     * @param $lat - latitude we are checking against
     * @param $long - longitude we are checking against
     * @return mixed - json data that includes the legislators we are looking for
     */
    public function getRepsFromAPICoords($lat, $long) {
        $json = file_get_contents(htmlspecialchars_decode(sprintf(
            "https://openstates.org/api/v1//legislators/geo/?lat=%s&long=%s&apikey=%s",
            round($lat, 1),
            round($long, 1),
            $this->property('userSunlightID')
        )));
        return json_decode($json);
    }

    /**
     * getRepsFromAPIDist - gets the state legislators from the api based on district number
     * @param $dist - District of legislators
     * @return mixed - list of state legislators from given district
     */
    public function getRepsFromAPIDist($dist) {
        $json = file_get_contents(htmlspecialchars_decode(sprintf(
            "https://openstates.org/api/v1//legislators/?state=wa&active=true&district=%s&apikey=%s",
            ltrim($dist),
            $this->property('userSunlightID')
        )));
        return json_decode($json);
    }
}
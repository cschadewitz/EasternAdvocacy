<?php namespace Lasso\LegislativeLookup\Models;

use Model;
use Exception;

/**
 * Address Model
 */
class Address extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_legislativelookup_addresses';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['address','city','state','zip','lat','long','district'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * @param $street_address - Array of address components
     * @return Address - new address object with lat/long coordinates looked up
     */
    public static function parseNewAddress($street_address) {
        if(Address::checkRecord($street_address)) {
            return Address::parseAddress($street_address);
        }
        $street = "";
        $street = $street . (isset($street_address[0])?$street_address[0]:"");
        $street = $street . (isset($street_address[1])?$street_address[1]:"");
        $street = $street . (isset($street_address[2])?$street_address[2]:"");
        $street = $street . (isset($street_address[3])?$street_address[3]:"");

        $results = Address::getCoordsFromAPI($street);
        $lat=round($results[0], 2);//normalized here, too many digits and the sunlight api fails
        $long=round($results[1], 2);

        $newAddress = Address::create([
            'address' => $street_address[0],
            'city' => $street_address[1],
            'state' => $street_address[2],
            'zip' => $street_address[3],
            'lat' => $lat,
            'long' => $long,
            'district' => null
        ]);
        $newAddress->save();
        return $newAddress;
    }

    /**
     * @param $street_address - array with the components of the inputed street address
     * @return mixed - instance of Address object at the specified address
     */
    public static function parseAddress($street_address) {
        return Address::address($street_address[0])
            ->city($street_address[1])
            ->state($street_address[2])
            ->zip($street_address[3])
            ->first();
    }

    /**
     * @param $street - String of street address
     * @return array|string - Geolocated coordinates from API
     */
    public static function getCoordsFromAPI($street)
    {
        try {
            $json = file_get_contents(sprintf(
            //"https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&key=%s",
            //original API string, now stored in settings for ease of update, keeping here for now so it has a backup location
                Settings::get('google_api'),
                urlencode($street),
                Settings::get('google_id')
            ));
            $json = json_decode($json);
            if (!($json->{'status'} == 'OK')) {
                throw new Exception($json->{'status'});
            }
            $json = $json->{'results'};
            $json = (is_array($json) ? reset($json) : $json);//if is an array, return the first element, else just hand back

            $lat = $json->{'geometry'}->{'location'}->{'lat'};
            $long = $json->{'geometry'}->{'location'}->{'lng'};

            $results = array($lat, $long);
            return $results;
        } catch (Exception $e) {
            echo 'Exception: ', $e->getMessage(), "\n";
            return $e->getMessage();
        }
    }

    /**
     * @param $street_address - array of street address compnents
     * @return bool - whether or not the address is in our records
     */
    public static function checkRecord($street_address) {//top down for more specificity
        $exists=Address::address($street_address[0])->first();
        if($exists!=null) {
            $exists=Address::address($street_address[0])->city($street_address[1])->first();
            if($exists!=null) {
                $exists=Address::address($street_address[0])->city($street_address[1])->state($street_address[2])->first();
                if($exists!=null) {
                    $exists=Address::address($street_address[0])->city($street_address[1])->state($street_address[2])->zip($street_address[3])->first();
                    if($exists!=null) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }//The cost of 4 nested if's is less than potentially going out to the API so we'll do it

    /**
     * @return bool - if the district has not been set yet
     */
    public function districtNotExists() {
        return is_null($this->district);
    }

    /*
     * getters and scope functions for use
     */
    public function getLat(){
        return $this->lat;
    }
    public function getLong(){
        return $this->long;
    }
    public function getDistrict() {
        return $this->district;
    }
    public function getState() {
        return $this->state;
    }
    public function scopeAddress($query, $address) {
        return $query->where('address', '=', $address);
    }
    public function scopeCity($query, $city) {
        return $query->where('city', '=', $city);
    }
    public function scopeState($query, $state){
        return $query->where('state', '=', $state);
    }
    public function scopeZip($query, $zip){
        return $query->where('zip', '=', $zip);
    }
    public function scopeDistrict($query, $district){
        return $query->where('district', '=', $district);
    }
}
<?php namespace Lasso\LegislativeLookup\Models;

use Model;

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
        $street = "address=";
        $street = $street . ($street_address[0]==null?$street_address[0]:"");
        $street = $street . ($street_address[1]==null?$street_address[1]:"");
        $street = $street . ($street_address[2]==null?$street_address[2]:"WA");//default to washington since that's where we are
        $street = $street . ($street_address[3]==null?$street_address[3]:"");

        $results = Address::getCoordsFromAPI($street);
        $lat=round($results[0], 1);//normalized here
        $long=round($results[1], 1);

        $newAddress = Address::create([
            'address' => $street_address[0],
            'city' => $street_address[1],
            'state' => $street_address[2],
            'zip' => $street_address[3],
            'lat' => $lat,
            'long' => $long
        ]);
        $newAddress->save();
        return $newAddress;
    }
    public static function parseAddress($street_address) {
        return Address::address($street_address[0])->city($street_address[1])->state($street_address[2])->zip($street_address[3])->get();
    }
    public static function getCoordsFromAPI($street) {
        $json = file_get_contents(sprintf(
            "https://maps.googleapis.com/maps/api/geocode/json?%s&sensor=false&key=%s",
            $street,
            Settings::get('google_id')
        ));
        $json = json_decode($json);
        $xlat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $xlong = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        $results = array($xlat, $xlong);
        return $results;
    }
    public static function checkRecord($street_address) {
        if(Address::address($street_address[0])->city($street_address[1])->state($street_address[2])->zip($street_address[3])->get()!=null)
            return false;
        else
            return true;
    }
    public function getLat(){
        return round($this->lat, 1);//normalize here
    }
    public function getLong(){
        return round($this->long, 1);
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
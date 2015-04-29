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
        $address = $street_address[0];
        $city = $street_address[1];
        $state = $street_address[2];
        $zip = $street_address[3];

        $street = "address=";
        $street = $street . ($address==null?$address:"");
        $street = $street . ($city==null?$city:"");
        $street = $street . ($state==null?$state:"WA");//default to washington since that's where we are
        $street = $street . ($zip==null?$zip:"");

        $json = Address->getCoordsFromAPI($street);

        $lat=round(json_decode($json)->{'lat'}, 1);//normalized here
        $long=round(json_decode($json)->{'lng'}, 1);
        $newAddress = Address::create([
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'lat' => $lat,
            'long' => $long
        ]);
        $newAddress->save();

        return $newAddress;
    }

    public static function getCoordsFromAPI($street) {
        $json = file_get_contents(sprintf(
            "https://maps.googleapis.com/maps/api/geocode/json?%s&sensor=false&key=%s",
            $street,
            Settings::get('google_id')
        ));
        $json = json_decode($json)->{'results'};
        $json = json_decode($json)->{'geometry'};
        $json = json_decode($json)->{'location'};
        return (json_decode($json));
    }

    public function getLat(){
        return round($this->lat, 1);//normalize here
    }
    public function getLong(){
        return round($this->long, 1);
    }
    public function scopeDistrict($query, $district){
        return $query->where('district', '=', $district);
    }
    public function scopeState($query, $state){
        return $query->where('state', '=', $state);
    }
    public function scopeZip($query, $zip){
        return $query->where('zip', '=', $zip);
    }
}
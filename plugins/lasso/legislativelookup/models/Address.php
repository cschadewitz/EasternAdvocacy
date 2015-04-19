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
    protected $fillable = [];

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
     * @param $address - Array of address components
     * @return mixed - JSON containing the lat/long coordinates - might normalize here
     */
    public function coordinatesFromAddress($street_address) {
        $address = $street_address[0];
        $city = $street_address[1];
        $state = $street_address[2];
        $zip = $street_address[3];

        $street = ($address==null?("address=" . $address):"");
        $street = $street . ($city==null?("city=" . $city):"");
        $street = $street . ($state==null?("state=" . $state):"");
        $street = $street . ($zip==null?("zip=" . $zip):"");
        $json = file_get_contents(sprintf(
            "https://maps.googleapis.com/maps/api/geocode/json?%s&sensor=false&key=%s",
            $street,
            Settings::get('google_id')
        //$this->property('userGoogleID')
        ));
        $json = json_decode($json)->{'results'};
        $json = json_decode($json)->{'geometry'};
        $json = json_decode($json)->{'location'};
        return json_decode($json);
    }

}
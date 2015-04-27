<?php namespace Lasso\LegislativeLookup\Models;

use Model;

/**
 * Legislator Model
 */
class Legislator extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'lasso_legislativelookup_legislators';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['uuid','state','district','first_name','last_name','party','email','photo_url','office_phone','url'];

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
     * @param $json - JSON returned from API to parse through
     * @return Legislator - new Legislator object instance
     */
    public static function getLegislatorFromJSON($json)
    {
        $legislatorRecord = Legislator::create([
            'uuid'=> $json->id,
            'state'=> $json->state,
            'district'=> $json->district,
            'first_name'=> $json->first_name,
            'last_name'=> $json->last_name,
            'party'=> $json->party,
            'email'=> $json->email,
            'photo_url'=> $json->photo_url,
            'office_phone'=> $json->office_phone,
            'url'=> $json->url
        ]);
        $legislatorRecord->save();
        return $legislatorRecord;
    }

    /**
     * getLegislatorsFromDistrict - gets the (state) legislators from the
     * api by district
     * @param $district - district we are checkign
     * @return mixed - json data that includes the legislators we are looking for
     */
    public static function getLegislatorsFromDistrict($district) {
        $json = file_get_contents(htmlspecialchars_decode(sprintf(
            "https://openstates.org/api/v1/legislators/?district=%s&apikey=%s",
            $district,
            Settings::get('sunlight_id')
        )));
        return json_decode($json);
    }

    /**
     * @param $theAddress - Address object to check API for
     * @return mixed - JSON of legislator data for address provided
     */
    public static function getJSONLegislatorsFromAddress($theAddress) {
        $json = file_get_contents(htmlspecialchars_decode(sprintf(
            "https://openstates.org/api/v1//legislators/geo/?lat=%s&long=%s&apikey=%s",
            $theAddress->getLat(),
            $theAddress->getLong(),
            Settings::get('sunlight_id')
        )));
        return json_decode($json);
    }

    public static function getLegislatorByUUID($UUID){
        return Legislator::whereraw('$uuid = ? ', $UUID);
    }
    public static function getLegislatorByDistrict($district){
        return Legislator::whereraw('$district = ? ', $district);
    }
    public function getUUID(){
        return $this->uuid;
    }
    public function scopeDistrict($query, $district){
        return $query->where('district', '=', $district);
    }
    public function scopeUUID($query, $uuid){
        return $query->where('uuid', '=', $uuid);
    }
}
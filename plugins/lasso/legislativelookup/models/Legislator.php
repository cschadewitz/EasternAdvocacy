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
            'party'=> isset($json->party)?$json->party:null,
            'email'=> isset($json->email)?$json->email:null,
            'photo_url'=> empty($json->photo_url)?"/plugins/rainlab/user/assets/images/no-user-avatar.png":$json->photo_url,
            'office_phone'=> isset($json->office_phone)?$json->office_phone:null,
            'url'=> isset($json->url)?$json->url:null
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
    public static function getLegislatorsFromDistrict($district, $state)
    {
        try {
            $json = file_get_contents(htmlspecialchars_decode(sprintf(
            //"https://openstates.org/api/v1/legislators/?district=%s&state=%s&apikey=%s", //saving original here just in case
                Settings::get('sunlight_district_api'),
                $district,
                $state,
                Settings::get('sunlight_id')
            )));
            return json_decode($json);
        } catch (Exception $e) {
            echo 'Exception: ', $e->getMessage(), "\n";
            return $e->getMessage();
        }
    }

    /**
     * @param $lat - Latitiude to check
     * @param $long - Longitude to check
     * @return mixed - JSON of legislator data for address provided
     */
    public static function getJSONLegislatorsFromCoords($lat, $long) {
        try {
            $json = file_get_contents(htmlspecialchars_decode(sprintf(
            //"http://openstates.org/api/v1/legislators/geo/?lat=%s&long=%s&apikey=%s", //saving original here just in case
                Settings::get('sunlight_geo_api'),
                $lat,
                $long,
                Settings::get('sunlight_id')
            )));
            return $json;
        }
        catch (Exception $e) {
            echo 'Exception: ', $e->getMessage(), "\n";
            return $e->getMessage();
        }
    }

    /*
     * get and scope methods for use, instance and statics
     */
    public static function getLegislatorByUUID($UUID){
        return Legislator::where('uuid', '=', $uuid)->first();
    }
    public static function getLegislatorByDistrict($district){
        return Legislator::where('district', '=', $district)->first();
    }
    public function getUUID(){
        return $this->uuid;
    }
    public function scopeDistrict($query, $district){
        return $query->where('district', '=', $district);
    }
    public function scopeState($query, $state) {
        return $query->where('state', '=', $state);
    }
    public function scopeUUID($query, $uuid){
        return $query->where('uuid', '=', $uuid);
    }
    public function scopeJSON($query) {
        return json_encode($query->get());
    }
}
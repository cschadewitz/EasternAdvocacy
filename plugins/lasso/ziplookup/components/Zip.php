<?php namespace Lasso\ZipLookup\Components;

use Cms\Classes\ComponentBase;
use Lasso\ZipLookup\Models\ZipRecord;
use Lasso\ZipLookup\Models\Rep;

class Zip extends ComponentBase
{
    /**
     * zipcode, read as string
     * @var string
     */
    public $zipCode;
    /**
     * array of representatives
     * @var array
     */
    public $representatives;


    public function componentDetails()
    {
        return [
            'name'        => 'Zip',
            'description' => 'Zip Lookup Plugin'
        ];
    }
    public function defineProperties()
    {
	    return [
            'userID' => [
            'title'         => 'User ID',
            'description'   => 'User ID to access legislator API',
            'default'       => 'edf3725acca94c9a897416cd3517f08e',
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
    public function onRun()
    {
        //doesn't run under ajax events
    }
    public function init()
    {
        //does run under ajax events
    }
    public function onSearchZip()
    {
        $zipCode=post('zipCode');

        //$CurrentZipRecord = ZipRecord::where('zip', '=', intval($zipCode))->first();
        //$representatives[] = Rep::where('id', '=', $CurrentZipRecord);

        //if($this->properties['visibleOutput']=='visible')
        //  $this->page['#results']=$zipCode;
        //else
        //  return $representatives;

        $representatives = Zip::info($zipCode);
        $this->page{'#results'}=$representatives;
        //$this['representatives']=$representatives;
        return $representatives;
        //return Zip::info($zipCode);
    }

    public function onAddZip()
    {
        $zipRecord = new ZipRecord();
        $zipRecord ->zip = post('zip');
        $zipRecord ->representative_id = post('repID');
        $zipRecord ->save();
    }

    public function info($zippy)
    {
        echo $zippy;
        $json = file_get_contents(sprintf(
            "https://congress.api.sunlightfoundation.com/legislators/locate?zip=%s&apikey=%s",
            $zippy,
            $this->property('userID')
        ));
        return json_decode($json)->{'results'};
    }
}
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
        //$this->zipCode = $this->zipCode;

        /*$this->representatives = [
            'patty murray',
            'maria cantwell',
            'john boner'
        ];*/
        //doesn't run under ajax events
    }
    public function init()
    {
        //does run under ajax events
    }
    public function onSearchZip()
    {
        $zipCode=post('zipCode');

        echo json_encode(Zip::info(), JSON_PRETTY_PRINT);

        //echo $zipCode;
        $CurrentZipRecord = ZipRecord::where('zip', '=', intval($zipCode))->first();
        //echo $CurrentZipRecord;
        $id = $CurrentZipRecord;

        $representatives[] = Rep::where('id', '=', $id);
        //sforeach($representatives as $rep)
            //echo $rep->get();
            //echo $rep->__toString();

        //if($this->properties['visibleOutput']=='visible')
        //    $this->page['#results']=$zipCode;
        //else
        //    return $representatives;
    }

    public function onAddZip()
    {
        $zipRecord = new ZipRecord();
        $zipRecord ->zip = post('zip');
        $zipRecord ->representative_id = post('repID');
        $zipRecord ->save();
    }

    public function info()
    {
        $json = file_get_contents(sprintf(
            "https://congress.api.sunlightfoundation.com/legislators/locate?zip=%s&apikey=%s",
            post('zipCode'),
            $this->property('userID')
        ));
        return json_decode($json);
    }
}
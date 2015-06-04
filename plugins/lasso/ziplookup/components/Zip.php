<?php namespace Lasso\ZipLookup\Components;

use Cms\Classes\ComponentBase;
use Lasso\ZipLookup\Models\ZipRecord;
use Lasso\ZipLookup\Models\Rep;

class Zip extends ComponentBase {
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

    public function componentDetails() {
        return [
            'name'        => 'Zip',
            'description' => 'Zip Lookup Plugin'
        ];
    }
    public function defineProperties() {
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
    public function onSearchZip() {
        $zipCode = post('zipCode');
        $zipRecord = ZipRecord::where('zip', '=', intval($zipCode))->first();

        if (!$zipRecord) {
            $representatives = Zip::info($zipCode);
            foreach($representatives as $rep) {
                $repRecord = (Rep::whereraw('firstName = ? AND lastName = ? ',
                    array($rep->first_name, $rep->last_name))->first());
                if (!$repRecord) {
                    $repRecord = Rep::create(['firstName' => $rep->first_name,
                        'lastName' => $rep->last_name,
                        'title' => $rep->title,
                        'politicalParty' => $rep->party,
                        'emailAddress' => $rep->oc_email,
                        'phoneNumber' => $rep->phone,
                        'physicalAddress' => $rep->office . ", " . $rep->state,
                        'expireDate' => $rep->term_end]);
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
        if($zipRecord) {
            $repsIds = ZipRecord::where('zip', '=', $zipRecord->zip);
            foreach ($repsIds->get() as $zipIndex) {
                $repIndex = $zipIndex->representative_id;
                $rep = Rep::where('id', '=', $repIndex)->first();
                array_push($representatives, $rep);
            }
        }
        if($this->properties['visibleOutput']=='visible') {
            $this->representatives = $representatives;
        } else {
            return $representatives;
        }
    }
    public function info($zipCode) {
        $json = file_get_contents(sprintf(
            "https://congress.api.sunlightfoundation.com/legislators/locate?zip=%s&apikey=%s",
            $zipCode,
            $this->property('userID')
        ));
        return json_decode($json)->{'results'};
    }
}
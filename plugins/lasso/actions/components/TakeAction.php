<?php namespace Lasso\Actions\Components;

use Cms\Classes\ComponentBase;
use Lasso\Actions\Models\Action;
use Lasso\LegislativeLookup\Components\Lookup;
use Auth;

class TakeAction extends ComponentBase {

	public function componentDetails() {
		return [
			'name' => 'TakeAction Component',
			'description' => 'No description provided yet...',
		];
	}

	public function defineProperties() {
		return [
			'actionId' => [
				'title' => 'ID',
				'description' => 'ID of the email being viewed by the user',
				'default' => '{{ :actionId }}',
				'type' => 'string',
			]];
	}

	public function onRun() {
		$this->injectAssets();

		$this->assignVars();
	}


	public function injectAssets()
	{
		$this->addCss('/plugins/lasso/actions/assets/css/frontend.css');
		$this->addJs('/plugins/lasso/actions/assets/js/frontend.js');
	}

	public function assignVars()
	{		
		$action = Action::with('template')->find($this->property('actionId'));
		$this->page['access_status'] = $this->checkAccessStatus($action);
		$this->page['action'] = $action;
		if(Auth::check() && !empty(Auth::check()->zipcode))
			$this->page['reps'] = $this->lookupReps(Auth::check()->zipcode);
		else
			$this->page['reps'] = null;
	}

	private function checkAccessStatus($action)
	{
		if(!is_null($action))
		{
			if($action->require_user)
			{
				if(Auth::check())
					return 'ok';
				else
					return 'no';
			}
			else
			{
				if(Auth::check())
					return 'ok';
				else
					return 'sub';
			}
		}
		else
			return 'invalid';
	}

	public function getRepsHtml($address)
	{
		$html = "";

		$reps = $this->lookupReps($address);

        if(count($reps)==0)
            $html .= 'No representatives found at this zip code.';
        else
            foreach($reps as $rep)
            {
                $html .= '<div class="col-xs-4">';
                $html .= '<div class="thumbnail">';
                $html .= '<img src="http://static.openstates.org/photos/small/'.$rep->id.'.jpg" alt="">';
                $html .= '<div class="caption">';
                $html .= '<h5>'.$rep->full_name.'</h5>';
                $html .= '</div></div></div>';
            }

		return $html;
	}

	public function lookupReps($address) {
		$geo = $this->getGeoCode($address);

		$reps = $this->getGeoReps($geo);

		return $reps;
	}

	public function getGeoCode($address) {
		$geokey = 'AIzaSyB-uT5MX5748RHDpXJ5YgTWD3gWoSC_KbA';

		$json = file_get_contents(sprintf(
			"https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s",
			urlencode($address),
			$geokey
		));

		return json_decode($json, true);
	}

	public function getGeoReps($geo) {
		$openhousekey = 'f78ae693f1c343aab4d0e5898eca594d';

		$lat = $geo['results'][0]['geometry']['location']['lat']; // 46.486931;
		$long = $geo['results'][0]['geometry']['location']['lng']; // -107.575956;

		$json = file_get_contents(sprintf(
			"http://openstates.org/api/v1//legislators/geo/?lat=%s&long=%s&apikey=%s",
			$lat,
			$long,
			$openhousekey
		));

		return json_decode($json);
	}
}
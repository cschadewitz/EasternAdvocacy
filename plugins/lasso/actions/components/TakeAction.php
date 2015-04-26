<?php namespace Lasso\Actions\Components;

use Cms\Classes\ComponentBase;
use Lasso\Actions\Models\Action;
use Lasso\ZipLookup\Components\Zip;

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
		$zip = new Zip;
		$this->addCss('/plugins/lasso/actions/assets/css/frontend.css');
		$this->addJs('/plugins/lasso/actions/assets/js/frontend.js');
		$action = Action::with('template')->find($this->property('actionId'));
		$this->page['action'] = $action;
		$this->page['reps'] = $this->lookupReps('eastern washington university'); //$zip->info('99004');
		//$this->page['debug'] = $this->lookupReps('99004');
	}

	public function lookupReps($address) {
		$geo = $this->getGeoCode($address);

		$reps = $this->getGeoReps($geo);

		return $reps;
	}

	public function getGeoCode($address) {
		$geokey = 'AIzaSyA9spIfrAzZZy84WCQQ_WxQozHHNiupVbE';

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
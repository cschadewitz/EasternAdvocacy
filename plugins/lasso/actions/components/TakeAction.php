<?php namespace Lasso\Actions\Components;

use Cms\Classes\ComponentBase;
use Lasso\Actions\Models\Action;
use Lasso\Actions\Models\ActionTaken;
use Auth;
use Mail;
use Request;
use Validator;
use ValidationException;

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

    public function onSubmitAction() {
        $validation = $this->validateInput();

        if ($validation->fails()) {
            $this->feedbackVars((new ValidationException($validation))->getMessage(), true);
        }
        else
        {
            $count = ActionTaken::where('action_id', '=', $this->property('actionId'))
                    ->where('email', Request::input('email'))->count();
            if($count > 0)
            {
                $this->feedbackVars("You have already taken this action.", true);
            }
            else
            {
                $actionTaken = $this->saveActionTaken();

                $this->emailReps($actionTaken);

                $this->feedbackVars("You have succesfully contacted your representatives.");
            }
        }

    }

    private function validateInput()
    {
        $rules = [];
        $rules['name'] = 'required';
        $rules['email'] = 'email';
        $rules['zipcode'] = 'regex:^\d{5}([\-]?\d{4})?^';

        return Validator::make(Request::all(), $rules);
    }

    private function saveActionTaken()
    {
        $actionTaken = new ActionTaken;
        $actionTaken->action_id = $this->property('actionId');
        $actionTaken->name = Request::input('name');
        $actionTaken->email = Request::input('email');
        $actionTaken->zipcode = Request::input('zipcode');
        $actionTaken->ip_address = Request::getClientIp();
        $actionTaken->save();

        return $actionTaken;
    }

    private function emailReps($actionTaken)
    {
        $reps = $this->lookupReps($actionTaken->zipcode);

        $action = Action::with('template')->find($actionTaken->action_id);

        $params = ['rep' => $reps, 'sender' => $actionTaken];
        foreach ($reps as $rep) {
            Mail::send($action->template->code, $params, function ($message) use ($rep){
                $message->to('samir@ouahhabi.com', 'Samir Ouahhabi');
            });
        }
    }

    private function feedbackVars($message, $error=false)
    {
        $this->page['has_error'] = $error;
        $this->page['feedback_header'] = $error? "Error:" : "Thank you!";
        $this->page['message'] = $message;
    }

	private function injectAssets()
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
        if(!$this->checkZip($address))
            return null;

		$geo = $this->getGeoCode($address);

		$reps = $this->getGeoReps($geo);

		return $reps;
	}

    public function checkZip($address)
    {
        $json = file_get_contents(
            "https://api.bring.com/shippingguide/api/postalCode.json?clientUrl=ewuadvocates.org&country=us&pnr=".$address
        );

        $json = json_decode($json);

        return $json->valid;
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
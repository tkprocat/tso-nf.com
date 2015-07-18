<?php namespace LootTracker\Http\Requests;

use Illuminate\Support\Facades\Auth;

class GuildApplicationRequest extends Request
{
    protected $rules = array(
        'message' => 'required',
    );

    public function rules()
    {
        return $this->rules;
    }

    public function authorize()
    {
        //Fail if the use are not logged in.
        if (!Auth::check()) {
            return false;
        }

        return true;
    }
}

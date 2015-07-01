<?php namespace LootTracker\Http\Requests;

use Illuminate\Support\Facades\Auth;

class UpdatePriceRequest extends Request
{
    protected $rules = array(
        'min_price' => 'required',
        'avg_price' => 'required',
        'max_price' => 'required'
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

        //Check if the user is an admin.
        if (!Auth::user()->hasRole('admin')) {
            return false;
        }

        return true;
    }
}

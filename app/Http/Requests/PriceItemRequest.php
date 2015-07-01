<?php namespace LootTracker\Http\Requests;

use Illuminate\Support\Facades\Auth;

class PriceItemRequest extends Request
{
    protected $rules = array(
        'name' => 'required|string'
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

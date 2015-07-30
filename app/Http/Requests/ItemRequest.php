<?php namespace LootTracker\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ItemRequest extends Request
{
    protected $rules = array(
        'name' => 'required|string',
        'category' => 'required|string'
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

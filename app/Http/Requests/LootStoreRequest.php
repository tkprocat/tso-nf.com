<?php namespace LootTracker\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class LootStoreRequest extends Request
{
    protected $rules = array(
        'adventure_id' => 'required|exists:adventure,id',
    );

    protected $messages = array(
    );



    public function authorize()
    {
        if (!Auth::check())
            return false;

        return true;
    }
}
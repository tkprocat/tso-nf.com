<?php namespace LootTracker\Http\Requests;

use Illuminate\Support\Facades\Auth;

/**
 * Class LootStoreRequest
 * @package LootTracker\Http\Requests
 */
class LootStoreRequest extends Request
{

    /**
     * @var array
     */
    protected $rules = array(
        'adventure_id' => 'required|exists:adventure,id',
    );

    /**
     * @var array
     */
    protected $messages = array(
    );

    /**
     * @return bool
     */
    public function authorize()
    {
        if (!Auth::check()) {
            return false;
        }

        return true;
    }
}

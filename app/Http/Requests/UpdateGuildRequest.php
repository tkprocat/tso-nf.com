<?php namespace LootTracker\Http\Requests;

use Illuminate\Support\Facades\Auth;

class UpdateGuildRequest extends Request
{
    protected $rules = array(
        'name' => 'required',
        'tag' => 'required',
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

        //Fail if the user does not have permission to create/edit blog posts.
        if (!Auth::user()->can('admin-guild') && !Auth::user()->hasRole('admin')) {
            return false;
        }

        return true;
    }
}

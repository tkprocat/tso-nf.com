<?php namespace LootTracker\Http\Requests;

use Illuminate\Support\Facades\Auth;

class BlogCommentRequest extends Request
{
    protected $rules = array(
        'content' => 'required',
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
        if (!Auth::user()->can('post-blog-comment') && !Auth::user()->can('admin-blog') ) {
            return false;
        }

        return true;
    }
}

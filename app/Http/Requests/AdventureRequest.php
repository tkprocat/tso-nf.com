<?php namespace LootTracker\Http\Requests;

use Illuminate\Support\Facades\Auth;

class AdventureRequest extends Request
{
    protected $rules = array(
        'name' => 'required|string',
    );

    public function rules()
    {
        $data = $this->request->all();

        //Removes any "empty" items.
        $itemCount = count($data["items"]);
        if ($itemCount > 0) {
            for ($a = 1; $a <= $itemCount; $a++) {
                if (($data["items"][$a]["slot"] == "") && ($data["items"][$a]["type"] == "") && ($data["items"][$a]["amount"] == "")) {
                    unset($data["items"][$a]);
                }
            }
        }

        //Add dynamic rules.
        for ($i = 1; $i < count($data["items"]); $i++) {
            //Only check if one of the fields are filled out
            if (($data["items"][$i]['slot'] != '') || ($data["items"][$i]['type'] != '') || ($data["items"][$i]['amount'] != '')) {
                $this->rules = array_add($this->rules, "items.$i.slot", 'required|integer');
                //$this->rules = array_add($this->rules, "items.$i.type", 'required|alpha_spaces');
                $this->rules = array_add($this->rules, "items.$i.amount", 'required|integer');
            }
        }
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

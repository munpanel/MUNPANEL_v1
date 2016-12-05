<?php

namespace App\Http\Controllers;

use App\User;
use App\Delegate;
use App\Volunteer;
use App\Observer;
use App\School;
use App\Committee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function regSaveDel(Request $request)
    {
        //$user = Auth::user();
        $user = User::find($request->id);
        if (is_null($request->id))
            $user = Auth::user();
        else if (Auth::user()->type != 'ot' && Auth::user()->type != 'school')
            return "error";
        else if (Auth::user()->type == 'school' && Auth::user()->school->id != $user->specific()->school->id)
            return "error";
        $user->type = 'delegate';
        $user->save();
        $del = $user->delegate;
        if (is_null($del))
        {
            $del = new Delegate;
            $del->user_id = $user->id;
            $del->email = $user->email;
        }
        $del->school_id = $request->school;
        $del->status = 'reg';
        $del->gender = $request->gender;
        $del->sfz = $request->sfz;
        $del->grade = $request->grade;
        $del->qq = $request->qq;
        $del->wechat = $request->wechat;
        $del->partnername = $request->partnername;
        $del->parenttel = $request->parenttel;
        $del->tel = $request->tel;
        $del->committee_id = $request->committee;
        $del->accomodate = $request->accomodate;
        $del->roommatename = $request->roommatename;
        $del->save();
        Volunteer::destroy($user->id);
        Observer::destroy($user->id);
    }

    public function regSaveVol(Request $request)
    {
        //$user = Auth::user();
        $user = User::find($request->id);
        if (is_null($request->id))
            $user = Auth::user();
        else if (Auth::user()->type != 'ot' && Auth::user()->type != 'school')
            return "error";
        else if (Auth::user()->type == 'school' && Auth::user()->school->id != $user->specific()->school->id)
            return "error";
        $user->type = 'volunteer';
        $user->save();
        $vol = $user->volunteer;
        if (is_null($vol))
        {
            $vol = new Volunteer;
            $vol->user_id = $user->id;
            $vol->email = $user->email;
        }
        $vol->school_id = $request->school;
        $vol->status = 'reg';
        $vol->gender = $request->gender;
        $vol->sfz = $request->sfz;
        $vol->grade = $request->grade;
        $vol->qq = $request->qq;
        $vol->wechat = $request->wechat;
        $vol->parenttel = $request->parenttel;
        $vol->tel = $request->tel;
        $vol->accomodate = $request->accomodate;
        $vol->roommatename = $request->roommatename;
        $vol->save();
        Delegate::destroy($user->id);
        Observer::destroy($user->id);
    }

    public function regSaveObs(Request $request)
    {
        $user = Auth::user();
        $user->type = 'observer';
        $user->save();
        $obs = $user->observer;
        if (is_null($obs))
        {
            $obs = new Observer;
            $obs->user_id = $user->id;
            $obs->email = $user->email;
        }
        $obs->school_id = $request->school;
        $obs->status = 'reg';
        $obs->gender = $request->gender;
        $obs->sfz = $request->sfz;
        $obs->grade = $request->grade;
        $obs->qq = $request->qq;
        $obs->wechat = $request->wechat;
        $obs->parenttel = $request->parenttel;
        $obs->tel = $request->tel;
        $obs->accomodate = $request->accomodate;
        $obs->roommatename = $request->roommatename;
        $obs->save();
        Delegate::destroy($user->id);
        Volunteer::destroy($user->id);
    } 

    public function schoolVerify($id)
    {
        $school = Auth::user()->school;
        $specific =  User::find($id)->specific();
        if ($specific->school->id != $school->id)
            return "error";
        $specific->status = 'sVerified';
        $specific->save();
    }

    public function schoolUnverify($id)
    {
        $school = Auth::user()->school;
        $specific =  User::find($id)->specific();
        if ($specific->school->id != $school->id)
            return "error";
        $specific->status = 'reg';
        $specific->save();
    }

    public function setStatus($id, $status)
    {
        if (Auth::user()->type != 'ot')
            return "Error";
        $specific =  User::find($id)->specific();
        $specific->status = $status;
        $specific->save();
    }


    public function regSchool()
    {
if (($handle = fopen("/var/www/munpanel/test.csv", "r")) !== FALSE) {
    $resp = "";
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
            $resp = $resp. $data[$c] . "<br />\n";
        }
        $user = new User;
        $user->name = $data[0];
        $user->password = Hash::make($data[1]);
        $user->email = $data[0]. '@schools.bjmun.org';
        $user->type = 'school';
        $user->save();
        $school = School::where('name', $data[0])->first();
        $school->user_id =$user->id;
        $school->save();
        $resp = $resp. response()->json($user) . "<br />\n";
        $resp = $resp. response()->json($school) . "<br />\n";
    }
    fclose($handle);
    return $resp;
}
    }

    public function doChangePwd(Request $request)
    {
        $user = Auth::user();
        if (Hash::check($request->oldPassword, $user->password))
        {
            $user->password = Hash::make($request->newPassword);
            $user->save();
            return redirect(secure_url('/home'));
        }
        else
            return view('error', ['msg' => 'Wrong password!']);
    }

    public function updateUser(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return 'Error';
        $user = User::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        if ($name == 'password')
            $value = Hash::make($value);
        if ($name == 'type')
        {
            Delegate::destroy($user->id);
            Volunteer::destroy($user->id);
            Observer::destroy($user->id);
        }
        $user->$name = $value;
        $user->save();
    }

    public function updateSchool(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return 'Error';
        $school = School::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $school->$name = $value;
        $school->save();
    }

    public function updateCommittee(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return 'Error';
        $committee = Committee::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $committee->$name = $value;
        $committee->save();
    }

    public function deleteUser(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return 'Error';
        User::destroy($id);
    }

    public function deleteCommittee(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return 'Error';
        Committee::destroy($id);
    }

    public function deleteSchool(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return 'Error';
        School::destroy($id);
    }

    public function test()
    {
        return "gou";
    }
}

<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\plant;
use App\area;
use App\location;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class mechanicsController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		// Session::set('leaderid', NULL);
	}

	public function index()
	{
		//
		// dd('test');
		$leaderid = Session::get('leaderid');
		if (isset($leaderid)) {
			
			return Redirect::to('afterlogin');
		}
		return view('Mechanics.login');

	}

	public function logincheck(Request $request) {

		$this->validate($request, ['pin'=>'required|min:4|max:5']);
		$forminput = $request->all(); 

		$pin = $forminput['pin'];
		// dd($pin);

		// $inteosmech = DB::connection('sqlsrv2')->select(DB::raw("SELECT Cod,Name FROM BdkCLZG.dbo.WEA_PersData WHERE Func = 2 AND FlgAct = 1 AND PinCode = '".$pin."'"));
		$inteosmech = DB::connection('sqlsrv2')->select(DB::raw("SELECT	Cod, Name,
		(SELECT e.[Subdepartment] FROM [172.27.161.221\GPD].[Gordon_LIVE].[dbo].[GORDON\$Employee] as e where e.[No_] COLLATE Latin1_General_CI_AS = BadgeNum ) as plant
		FROM BdkCLZG.dbo.WEA_PersData WHERE Func = 2 AND FlgAct = 1 AND PinCode = '".$pin."'"));
		// dd($inteosmech);

		if (empty($inteosmech)) {
			$msg = 'Mechanic with this PIN is not active';
		    return view('Mechanics.login',compact('msg'));
		
		} else {
			foreach ($inteosmech as $row) {
				$mechanicid = $row->Cod;
    			$mechanic = $row->Name;

    			if ($row->plant == 'Mechanics') {
    				$mechanic_plant = 'Subotica';
    			} else if  ($row->plant == 'Mechanics KIKINDA') {
    				$mechanic_plant = 'Kikinda';
    			} else if  ($row->plant == 'Mechanics SENTA') {
    				$mechanic_plant = 'Senta';
    			} else {
    				$mechanic_plant = 'missing';
    			}

    			// dd($mechanic_plant);
    			Session::set('mechanicid', $mechanicid);
    			Session::set('mechanic', $mechanic);
    			Session::set('mechanic_plant', $mechanic_plant);
    		}
    	}

    	return Redirect::to('afterlogin');
	}

	public function afterlogin() {

		$mechanicid = Session::get('mechanicid');
    	$mechanic = Session::get('mechanic');
		// dd($mechanic);
    	
		return view('Mechanics.functions', compact('mechanic'));
	}

}

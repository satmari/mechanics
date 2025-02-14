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
use App\machines;
use App\temp_move_machine;
use App\temp_transfer_machine;
use App\temp_give_machine;
use App\temp_return_machine;
use App\temp_adjust_machine;
use App\temp_fix_machine;
use App\temp_writeoff_machine;
use App\temp_sell_machine;
use App\comment;
use App\transfer_machine_log;
use App\borrow_machine_log;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;


class workstudyController extends Controller {

	public function index()
	{
		//
		// dd('WS Controller');
		$leaderid = Session::get('leaderid');
		if (isset($leaderid)) {
			
			return Redirect::to('afterlogin');
		} else {
			$leaderid = Session::get('leaderid');
			if (isset($leaderid)) {
				
				return Redirect::to('afterlogin');
			} else {
				// return view('Mechanics.login');		
			}
		}
		return view('workstudy.login');
	}

	public function logincheck_ws(Request $request) {

		$this->validate($request, ['pin'=>'required|min:4|max:5']);
		$forminput = $request->all(); 

		$pin = $forminput['pin'];
		// dd($pin);

		
		$inteosws = DB::connection('sqlsrv2')->select(DB::raw("SELECT	Cod, Name,
		(SELECT e.[Subdepartment] FROM [172.27.161.221\GPD].[Gordon_LIVE].[dbo].[GORDON\$Employee] as e where e.[No_] COLLATE Latin1_General_CI_AS = BadgeNum ) as plant
		FROM BdkCLZG.dbo.WEA_PersData WHERE Func = 23 AND FlgAct = 1 AND PinCode = '".$pin."'"));
		// dd($inteosws);

		if (empty($inteosws)) {
			$msg = 'Workstudy with this PIN is not active';
		    return view('Workstudy.login',compact('msg'));
		
		} else {
			foreach ($inteosws as $row) {
				$workstudyid = $row->Cod;
    			$workstudy = $row->Name;

    			if ($row->plant == 'Workstudy') {
    				$ws_plant = 'Subotica';
    			} else if  ($row->plant == 'Workstudy KIKINDA') {
    				$ws_plant = 'Kikinda';
    			} else if  ($row->plant == 'Workstudy SENTA') {
    				$ws_plant = 'Senta';
    			} else {
    				$ws_plant = 'missing';
    			}

    			Session::set('workstudyid', $workstudyid);
    			Session::set('workstudy', $workstudy);
    			Session::set('ws_plant', $ws_plant);
    		}
    	}

    	return Redirect::to('afterlogin_ws');
	}

	public function afterlogin_ws() {

		$workstudyid = Session::get('workstudyid');
    	$workstudy = Session::get('workstudy');
		// dd($workstudy);
    	
		return view('workstudy.functions', compact('workstudy'));
	}

	public function add_comment_ws() {

		$machines = DB::connection('sqlsrv')->select(DB::raw("SELECT os, brand, type, code FROM machines order by id"));

		return view('workstudy.add_comment_ws', compact('machines'));
	}

	public function add_comment_ws_scan(Request $request) {
		//
		$input = $request->all(); 
		// $session = Session::getId();
		// dd($input);

		if (!empty($input['machine1'])) {

			$test = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM machines
				WHERE os = '".strtoupper($input['machine1'])."' "));
			// dd($test);

			if (empty($test)) {
				$machines = DB::connection('sqlsrv')->select(DB::raw("SELECT os, brand, type, code FROM machines order by id"));
				$msge = 'Machine doesnt exist';
				return view('workstudy.add_comment_ws', compact('machines','msge'));
			}

			$machine = strtoupper($input['machine1']);

		} elseif (!empty($input['machine3'])) {

			$machine = $input['machine3'];	
		} else {

			$machines = DB::connection('sqlsrv')->select(DB::raw("SELECT os, brand, type, code FROM machines order by id"));
			$msge = 'Please scan or choose machine';
			return view('workstudy.add_comment_ws', compact('machines','msge'));

		}
		// dd($machine);

		$data2 = DB::table('machines')
        ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
        ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
        ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
        ->where('machines.os', '=', $machine)
        ->get();
        // dd($data2);

        if (isset($data2)) {
        	
        	$brand = $data2[0]->brand;
        	$type = $data2[0]->type;
        	$code = $data2[0]->code;
        	$location = $data2[0]->location;
        	$area = $data2[0]->area;
        	$plant = $data2[0]->plant;
        	$machine_status = $data2[0]->machine_status;
        	$comment_ws = $data2[0]->comment_ws;

        }

		return view('workstudy.add_comment_ws_post', compact('machine','brand','type','code','location','area','plant','machine_status','comment_ws'));
	}

	public function add_comment_ws_post(Request $request) {
		//
		// $this->validate($request, ['comment_ws' => 'required']);
		$input = $request->all(); 
		// dd($input);
		$machine = $input['machine'];

		if ($input['comment_ws'] == '') {

			
			
			$data2 = DB::table('machines')
	        ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
	        ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
	        ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
	        ->where('machines.os', '=', $machine)
	        ->get();
	        // dd($data2);

	        if (isset($data2)) {
	        	
	        	$brand = $data2[0]->brand;
	        	$type = $data2[0]->type;
	        	$code = $data2[0]->code;
	        	$location = $data2[0]->location;
	        	$area = $data2[0]->area;
	        	$plant = $data2[0]->plant;
	        	$machine_status = $data2[0]->machine_status;
	        	$comment_ws = $data2[0]->comment_ws;

	        }

	        $msge = 'Please add machine infromation';
			return view('workstudy.add_comment_ws_post', compact('machine','brand','type','code','location','area','plant','machine_status','comment_ws','msge'));
		}

		// dd($input);
		$comment_ws = $input['comment_ws'];
		// dd($comment_ws);

		$find_id = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM machines where os = '".$machine."' order by id"));
		// dd($find_id);

		$id = $find_id[0]->id;

        $machinedb = machines::findOrFail($id);
        $machinedb->comment_ws = $comment_ws;
        $machinedb->save();

        $machines = DB::connection('sqlsrv')->select(DB::raw("SELECT os, brand, type, code FROM machines order by id"));
        $msgs = 'Information successfuly saved';
		return view('workstudy.add_comment_ws', compact('machines','msgs'));


	}

}

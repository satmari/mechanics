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
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class mechanicsController extends Controller {

	// public function __construct()
	// {
	// 	$this->middleware('auth');
	// 	// Session::set('leaderid', NULL);
	// }

	public function index()
	{
		//
		// dd('test');
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
		// return view('Mechanics.login');
		return Redirect::to('afterlogin');

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

// MOVE MACHINE in the same PLANT
	public function move_machine_in_plant() {

		// dd('cao');
		return view('Mechanics.move_machine_in_plant');
	}

	public function move_machine_in_plant_loc(Request $request) {
		//
		// $this->validate($request, ['location_new' => 'required']);
		$input = $request->all(); 
		// dd($input);

		if (!isset($input['location_new'])) {
			return view('Mechanics.move_machine_in_plant');
		}

		$location_new = strtoupper($input['location_new']);
		// dd($location_new);

		try {

			$find_location = DB::connection('sqlsrv')->select(DB::raw("SELECT l.id, l.location, a.area, p.plant
			FROM locations as l
			JOIN areas as a ON a.id = l.area_id
			JOIN plants as p ON p.id = a.plant_id
			WHERE l.location = '".$location_new."' "));

		} catch(\Illuminate\Database\QueryException $ex){ 
			
			try {
			$find_location = DB::table('locations')
		            ->join('areas', 'areas.id', '=', 'locations.area_id')
		            ->join('plants', 'plants.id', '=', 'areas.plant_id')
		            ->where('locations.location', '=', $location_new)
		            ->select('locations.id', 'locations.location', 'areas.area', 'plants.plant')
		            ->get();

		    } catch(\Illuminate\Database\QueryException $ex){ 
		    	return view('Mechanics.move_machine_in_plant');
		    }

		}
		// dd($find_location);

		if (!isset($find_location[0]->id)) {
			// dd("Location does not exist");
			$msge = 'Location does not exist';
			return view('Mechanics.move_machine_in_plant', compact('msge'));
		} else {

			$new_location_id = $find_location[0]->id;
			$new_location = $find_location[0]->location;
			$new_area = $find_location[0]->area;
			$new_plant = $find_location[0]->plant;

			$session = Session::getId();
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));
			$msg = "Please scan OS (machine)";

			return view('Mechanics.move_machine_in_plant_scan',compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session' ,'msg'));
		}
	}

	public function move_machine_in_plant_scan(Request $request) {
		//
		// $this->validate($request, ['machine_temp' => 'required']);
		$input = $request->all();
		// dd($input);

		$session = Session::getId();
		$new_location_id = $input['new_location_id'];
		$session = $input['session'];
		$new_location = strtoupper($input['new_location']);
		$new_area = strtoupper($input['new_area']);
		$new_plant = strtoupper($input['new_plant']);
		
		// $machine_temp = $input['machine_temp'];
		// dd($location);
		
		if (isset($input['machine_temp'])) {
			$machine_temp = strtoupper($input['machine_temp']);

			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8) {
				// dd($machine_temp);

				// if (machines::where('os', '=', $machine_temp)->exists()) {
				// 	// dd('exist');
				// } else {
				// 	// dd('not exist');	
				// 	$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));
				// 	$msge = 'OS (machine) does not exist in table';
				// 	return view('Mechanics.move_machine_in_plant_scan', compact('data','location_id', 'location', 'area', 'plant', 'session', 'msge'));
				// }

				try {
					$exist = DB::connection('sqlsrv')->select(DB::raw("SELECT m.id, m.os, m.location, p.plant
					FROM machines as m
					LEFT JOIN locations as l ON l.id = m.location_id
					LEFT JOIN areas as a ON a.id = l.area_id
					LEFT JOIN plants as p ON p.id = a.plant_id
					WHERE os = '".$machine_temp."' "));

				// 	// dd($exist);
				} catch(\Illuminate\Database\QueryException $ex){ 

					try {
						$exist = DB::table('machines')
			            ->join('locations', 'locations.id', '=', 'machines.location_id')
			            ->join('areas', 'areas.id', '=', 'locations.area_id')
			            ->join('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('machines.os', '=', $machine_temp)
			            ->select('machines.id', 'machines.os', 'machines.location', 'plants.plant')
			            ->get();
			            // dd($exist);
			        } catch(\Illuminate\Database\QueryException $ex){ 

			        	$exist = DB::table('machines')
			            ->join('locations', 'locations.id', '=', 'machines.location_id')
			            ->join('areas', 'areas.id', '=', 'locations.area_id')
			            ->join('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('machines.os', '=', $machine_temp)
			            ->select('machines.id', 'machines.os', 'machines.location', 'plants.plant')
			            ->get();
			            // dd($exist);
			        }
				}
				
				if (isset($exist[0]->id)) {

					if (($new_plant == $exist[0]->plant) /*OR ($exist[0]->plant == NULL)*/)  {

						$data_temp = DB::connection('sqlsrv')->select(DB::raw("SELECT os, ses FROM temp_move_machines WHERE os = '".$exist[0]->os."' AND ses = '".$session."' "));
						if (isset($data_temp[0]->os)) {
							// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));
							$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
							$msge = 'Machine already scaned';
							return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session', 'msge'));							
						}

					} else {

						// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));
						$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
						if ($exist[0]->plant == NULL) {
							$exist[0]->plant = 'NOT DEFINED';
						}
						$msge = 'Destination location is in '.$new_plant.' and machine is in '.$exist[0]->plant;
						return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session', 'msge'));						
					}
				} else {
					// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));
					$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
					$msge = 'OS (machine) does not exist in table';
					return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session', 'msge'));
				}
			} else {

				// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));
				$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
				$msge = 'OS barcode must have 7 or 8 characters';
				return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session', 'msge'));
			}
		} else {
			dd('Error, zovi IT.');
		}

		// dd('change');
		// SAVE TO TEMP TABLE

		$table = temp_move_machine::firstOrNew(['os' => $machine_temp]);
		$table->os_id =  $exist[0]->id;
		$table->location =  $exist[0]->location;
		$table->os =  $machine_temp;
		$table->ses =  Session::getId();
		$table->new_location_id =  $new_location_id;
		$table->new_location =  $new_location;
		$table->new_area =  $new_area;
		$table->new_plant =  $new_plant;
		$table->save();		

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));
		$msg = 'OS added';
		return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session', 'msg'));

	}

	public function move_machine_in_plant_remove($id, $ses) {
		
		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$ses."' order by id desc"));
		$new_location = $ses_data[0]->new_location;
		$new_location_id = $ses_data[0]->new_location_id;
		$new_area = $ses_data[0]->new_area;
		$new_plant = $ses_data[0]->new_plant;
		$session = $ses;

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_move_machines WHERE ses = '".$ses."' AND id = '".$id."' "));
		
		// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$ses."' order by id desc"));
		$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
		$msg = 'OS removed';
		return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session', 'msg'));
	}

	public function move_machine_in_plant_confirm($session) {
		//
		$session;

		try {
			$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));	
		} catch(\Illuminate\Database\QueryException $ex){ 
			$ses_data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
		}
		// dd($ses_data);
	
		if (!isset($ses_data[0]->id)) {

			// $session = Session::getId();
			// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));
			$msge = 'List is empty, first choose destination location?';
			// return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session', 'msge'));
			return view('Mechanics.move_machine_in_plant', compact('msge'));
		}

		$new_location = $ses_data[0]->new_location;
		$new_location_id = $ses_data[0]->new_location_id;
		$new_area = $ses_data[0]->new_area;
		$new_plant = $ses_data[0]->new_plant;
		// dd($new_location);

		for ($i=0; $i < count($ses_data); $i++) { 
			
			$os = $ses_data[$i]->os;
			// dd($os);

			try {
				$table = machines::where(['os' => $os])->update(['location' => $new_location, 'location_id' => $new_location_id]);
			} catch(\Illuminate\Database\QueryException $ex){ 
				dd('problem to save');
			}
		}
		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_move_machines WHERE ses = '".$session."' "));

		// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));
		// $msg = 'Successfully saved';
		// return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session', 'msg'));
		// return Redirect::to('/move_machine_in_plant');

		$msgs = 'Successfully saved';
		return view('Mechanics.move_machine_in_plant', compact('msgs'));
	}

// CHANGE MACHINE STATUS

	public function transfer_machine()	{
		# 
		return view('Mechanics.transfer_machine');
	}

	public function transfer_machine_from(Request $request) {
		//
		$this->validate($request, ['plant_from' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$plant_from = strtoupper($input['plant_from']);

		return view('Mechanics.transfer_machine_to', compact('plant_from'));
	}

	public function transfer_machine_to(Request $request) {
		//
		$this->validate($request, ['plant_from' => 'required','plant_to' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$plant_from = strtoupper($input['plant_from']);
		$plant_to = strtoupper($input['plant_to']);
		$session = Session::getId();

		$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
		$msg = "Please scan OS (machine)";

		return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','session','msg'));
	}

	public function transfer_machine_scan(Request $request) {
		//
		// $this->validate($request, ['plant_from' => 'required','plant_to' => 'required']);
		$input = $request->all(); 
		$session = Session::getId();
		
		$machine_temp = strtoupper($input['machine_temp']);
		// dd($machine_temp);

		if (isset($input['plant_from']) AND isset($input['plant_to'])) {
			$plant_from = strtoupper($input['plant_from']);
			$plant_to = strtoupper($input['plant_to']);

			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8) {
				// dd($machine_temp);

				try {
					$exist = DB::connection('sqlsrv')->select(DB::raw("SELECT m.id, m.os, m.location, p.plant
					FROM machines as m
					LEFT JOIN locations as l ON l.id = m.location_id
					LEFT JOIN areas as a ON a.id = l.area_id
					LEFT JOIN plants as p ON p.id = a.plant_id
					WHERE os = '".$machine_temp."' "));
				 	// dd($exist);

				} catch(\Illuminate\Database\QueryException $ex){ 

					$exist = DB::table('machines')
		            ->join('locations', 'locations.id', '=', 'machines.location_id')
		            ->join('areas', 'areas.id', '=', 'locations.area_id')
		            ->join('plants', 'plants.id', '=', 'areas.plant_id')
		            ->where('machines.os', '=', $machine_temp)
		            ->select('machines.id', 'machines.os', 'machines.location', 'plants.plant')
		            ->get();
		            // dd($exist);
				}
				
				if (isset($exist[0]->id)) {

					if (($plant_from == $exist[0]->plant) /*OR ($exist[0]->plant == NULL)*/)  {

						$data_temp = DB::connection('sqlsrv')->select(DB::raw("SELECT os, ses FROM temp_transfer_machines WHERE os = '".$exist[0]->os."' AND ses = '".$session."' "));
						if (isset($data_temp[0]->os)) {
							// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_transfer_machines WHERE ses = '".$session."' order by id desc"));
							$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
							$msge = 'Machine already scaned';
							return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','session','msge'));
						}
					} else {

						// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_transfer_machines WHERE ses = '".$session."' order by id desc"));
						$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
						if ($exist[0]->plant == NULL) {
							$exist[0]->plant = 'NOT DEFINED';
						}
						$msge = 'Machine is in '.$exist[0]->plant.' but inserted plant from is '.$plant_from. ' ! ';
						return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','session','msge'));
					}
				} else {
					// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_transfer_machines WHERE ses = '".$session."' order by id desc"));
					$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
					$msge = 'OS (machine) does not exist in table';
					return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','session','msge'));
				}
			} else {
				// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_transfer_machines WHERE ses = '".$session."' order by id desc"));
				$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
				$msge = 'OS barcode must have 7 or 8 characters';
				return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','session','msge'));
			}
		} else {
			dd('Error, zovi IT.');
		}

		$table = temp_transfer_machine::firstOrNew(['os' => strtoupper($machine_temp)]);
		$table->os_id =  $exist[0]->id;
		$table->os =  strtoupper($machine_temp);
		$table->plant_from =  strtoupper($plant_from);
		$table->plant_to =  strtoupper($plant_to);
		$table->ses =  Session::getId();
		$table->save();

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_transfer_machines WHERE ses = '".$session."' order by id desc"));
		$msg = 'OS added';
		return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','session','msg'));
	}

	public function transfer_machine_remove($id, $session) {

		$ses = $session;
		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_transfer_machines WHERE ses = '".$ses."' order by id desc"));
		$plant_from = strtoupper($ses_data[0]->plant_from);
		$plant_to = strtoupper($ses_data[0]->plant_to);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_transfer_machines WHERE ses = '".$ses."' AND id = '".$id."' "));
		
		// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$ses."' order by id desc"));
		$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
		$msg = 'OS removed';
		return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','session','msg'));
	}

	public function transfer_machine_confirm($session) {
		//
		$session;

		try {
			$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_transfer_machines WHERE ses = '".$session."' order by id desc"));	
		} catch(\Illuminate\Database\QueryException $ex){ 
			$ses_data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
		}
		// dd($ses_data);
	
		if (!isset($ses_data[0]->id)) {

			$msge = 'List is empty, first choose transfer From (plant)?';
			return view('Mechanics.transfer_machine', compact('msge'));
		}

		$plant_from = strtoupper($ses_data[0]->plant_from);
		$plant_to = strtoupper($ses_data[0]->plant_to);
		$plant = DB::table('plants')->where(['plant' => $plant_to])->get();

		if ($plant[0]->plant == 'SUBOTICA') {

			$plant_new = $plant[0]->plant;
			$plant_id = $plant[0]->id;
			$new_location = 'RECEIVING_SU';
			$new_location_id = 118;

		} elseif (($plant[0]->plant == 'KIKINDA')) {

			$plant_new = $plant[0]->plant;
			$plant_id = $plant[0]->id;
			$new_location = 'RECEIVING_KI';
			$new_location_id = 119;

		} elseif (($plant[0]->plant == 'SENTA')) {

			$plant_new = $plant[0]->plant;
			$plant_id = $plant[0]->id;
			$new_location = 'RECEIVING_SE';
			$new_location_id = 120;

		} else {
			dd('Plant not recognized');
		}
		// dd($plant_new);

		for ($i=0; $i < count($ses_data); $i++) {
			
			$os = $ses_data[$i]->os;
			// dd($os);
			try {

				$table = machines::where(['os' => $os])->update(['location' => $new_location, 'location_id' => $new_location_id]);
				DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_transfer_machines WHERE ses = '".$session."' "));

			} catch(\Illuminate\Database\QueryException $ex){
				dd('problem to save');
			}
		}
		
		// dd('stop');
		// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$session."' order by id desc"));
		// $msg = 'Successfully saved';
		// return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session', 'msg'));
		// return Redirect::to('/move_machine_in_plant');

		$msgs = 'Successfully saved';
		return view('Mechanics.transfer_machine', compact('msgs'));
	}


// TRANSFER MACHINES AMONG PLANTS

}
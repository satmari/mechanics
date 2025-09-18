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

class mechanicsController extends Controller {

	public function index() {
		
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
		return view('Mechanics.login');
		// return Redirect::to('afterlogin');class_table
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
		$locations = DB::table('locations')
		            ->join('areas', 'areas.id', '=', 'locations.area_id')
		            ->join('plants', 'plants.id', '=', 'areas.plant_id')
		            ->where('locations.active', '=', '1')
		            ->where('areas.area', '=', 'STOCK_S')
		            ->orWhere('areas.area', '=', 'STOCK_K')
		            ->orWhere('areas.area', '=', 'STOCK_Z')
		            
		            ->select('locations.id', 'locations.location', 'areas.area', 'plants.plant')
		            ->orderBy('plants.plant', 'desc')
		            ->get();

		return view('Mechanics.move_machine_in_plant', compact('locations'));
	}

	public function move_machine_in_plant_loc(Request $request) {
		//
		// $this->validate($request, ['location_new' => 'required']);
		$input = $request->all(); 
		// dd($input);


		$locations = DB::table('locations')
		            ->join('areas', 'areas.id', '=', 'locations.area_id')
		            ->join('plants', 'plants.id', '=', 'areas.plant_id')
		            ->where('locations.active', '=', '1')
		            ->where('areas.area', '=', 'STOCK_S')
		            ->orWhere('areas.area', '=', 'STOCK_K')
		            ->orWhere('areas.area', '=', 'STOCK_Z')
		            ->select('locations.id', 'locations.location', 'areas.area', 'plants.plant')
		            ->orderBy('plants.plant', 'desc')
		            ->get();

		if (!empty($input['location_new1'])) {
			$location_new = $input['location_new1'];
		} elseif (!empty($input['location_new2'])) {
			$location_new = $input['location_new2'];
		} elseif (!empty($input['location_new3'])) {
			$location_new = $input['location_new3'];
		} else {
			$msge = 'Please scan or select location';
			return view('Mechanics.move_machine_in_plant', compact('locations', 'msge'));
		}
		
		$location_new = strtoupper($location_new);
		// dd($location_new);

		try {

			$find_location = DB::connection('sqlsrv')->select(DB::raw("SELECT l.id, l.location, a.area, p.plant
			FROM locations as l
			JOIN areas as a ON a.id = l.area_id
			JOIN plants as p ON p.id = a.plant_id
			WHERE l.location = '".$location_new."' and l.active = 1 "));

		} catch(\Illuminate\Database\QueryException $ex){ 
			
			try {
			$find_location = DB::table('locations')
		            ->join('areas', 'areas.id', '=', 'locations.area_id')
		            ->join('plants', 'plants.id', '=', 'areas.plant_id')
		            ->where('locations.location', '=', $location_new)
		            ->where('locations.active', '=', '1')
		            ->select('locations.id', 'locations.location', 'areas.area', 'plants.plant')
		            ->get();

		    } catch(\Illuminate\Database\QueryException $ex){ 
		    	$msge = 'Location does not exist';
		    	return view('Mechanics.move_machine_in_plant', compact('msge','locations'));
		    }

		}
		// dd($find_location);

		if (!isset($find_location[0]->id)) {
			// dd("Location does not exist");
			$msge = 'Location does not exist';
			return view('Mechanics.move_machine_in_plant', compact('msge','locations'));
		} else {

			$new_location_id = $find_location[0]->id;
			$new_location = $find_location[0]->location;
			$new_area = $find_location[0]->area;
			$new_plant = $find_location[0]->plant;

			$session = Session::getId();
			$msg = "Please scan machine";

			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $new_plant)
			            ->where('machines.location', '!=', $new_location)
			            ->where('machines.machine_status', '!=', 'TO_REPAIR')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
			// dd($machines);

            $data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
			return view('Mechanics.move_machine_in_plant_scan',compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session' ,'msg','machines'));
		}
	}

	public function move_machine_in_plant_scan(Request $request) {
		//
		// $this->validate($request, ['machine_temp' => 'required']);
		$input = $request->all();
		// dd($input);

		$session = Session::getId();
		$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
		$new_location_id = $input['new_location_id'];
		$session = $input['session'];
		$new_location = strtoupper($input['new_location']);
		$new_area = strtoupper($input['new_area']);
		$new_plant = strtoupper($input['new_plant']);
		
		if (!empty($input['machine_temp1'])) {
			$machine_temp = $input['machine_temp1'];
		} elseif (!empty($input['machine_temp2'])) {
			$machine_temp = $input['machine_temp2'];
		} else {
			$machine_temp = NULL;
		}

		// $machine_temp = $input['machine_temp'];
		// dd($location);

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $new_plant)
			            ->where('machines.location', '!=', $new_location)
			            ->where('machines.machine_status', '!=', 'TO_REPAIR')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		// dd($machines);
		
		if (isset($machine_temp)) {

			$machine_temp = strtoupper($machine_temp);

			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8 OR strlen($machine_temp) == 9) {
				// dd($machine_temp);

				$exist = DB::table('machines')
	            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
	            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
	            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
	            ->where('machines.os', '=', $machine_temp)
	            ->where('machines.machine_status', '!=', 'TO_REPAIR')
	            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'machines.machine_status' ,'plants.plant')
	            ->get();
	            // dd($exist);
			    
				if (isset($exist[0]->id)) {

					if (($new_plant == $exist[0]->plant) /*OR ($exist[0]->plant == NULL)*/)  {

						$data_temp = DB::connection('sqlsrv')->select(DB::raw("SELECT os, ses FROM temp_move_machines WHERE os = '".$exist[0]->os."' AND ses = '".$session."' "));
						if (isset($data_temp[0]->os)) {
							
							$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
							$msge = 'Machine already scaned';
							return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session','machines','msge'));
						} else {

							if ($exist[0]->machine_status == 'IN_LINE') {
							
								$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
								$msge = 'Machine have status IN_LINE and it is on the line '.$exist[0]->location.' . Please logout machine first. ';
								return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session','machines','msge'));
							}

						}
					} else {

						$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
						if ($exist[0]->plant == NULL) {
							$exist[0]->plant = 'NOT DEFINED';
						}
						$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
						$msge = 'Destination location is in '.$new_plant.' plant, but machine is in '.$exist[0]->plant.' plant' ;
						return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session','machines','msge'));						
					}
				} else {
					
					$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
					$msge = 'Machine does not exist in table or habe status ON_REPAIR ';
					return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session','machines','msge'));
				}
			} else {

				$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
				$msge = 'Machine barcode must have 7 ,8 or 9 characters';

				return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session','machines','msge'));
			}
		
		} else {
			// dd('Error, zovi IT.');
			$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
			$msge = 'Please add machine or scan machine barcode';

			return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session','machines','msge'));
		
		}
		// dd('change');

		// CHECK IF MACHINE HAS ACTIVE DOWNITME
		$check_dt_in_inteos = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
			d.[MachNum]
		  	,l.[ModNam]
			,o.[Name] as LineLeader
			,mc.[Name] as Mechanic
		  FROM [BdkCLZG].[dbo].[CNF_ModStatDecl] as d
		  LEFT JOIN [BdkCLZG].[dbo].[CNF_Modules] as l ON l.Module = d.Module
		  LEFT JOIN [BdkCLZG].[dbo].[WEA_PersData] as o ON o.Cod = d.leaderkey
		  LEFT JOIN [BdkCLZG].[dbo].[WEA_PersData] as mc ON mc.Cod = d.MecKey
		  WHERE MachCod != '-1' AND d.MachNum = '".$machine_temp."'
		  
		  UNION 
		  
          SELECT 
			d.[MachNum]
		  	,l.[ModNam]
			,o.[Name] as LineLeader
			,mc.[Name]  as Mechanic
		  FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModStatDecl] as d
		  LEFT JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_Modules] as l ON l.Module = d.Module
		  LEFT JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[WEA_PersData] as o ON o.Cod = d.leaderkey
		  LEFT JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[WEA_PersData] as mc ON mc.Cod = d.MecKey
		  WHERE MachCod != '-1' AND d.MachNum = '".$machine_temp."'
		  "));

		if (isset($check_dt_in_inteos[0])) {
			// printf('This machine has some active downtime');
			// dd($check_dt_in_inteos[0]);

			$MachNum = $check_dt_in_inteos[0]->MachNum;
			$ModNam = $check_dt_in_inteos[0]->ModNam;
			$LineLeader = $check_dt_in_inteos[0]->LineLeader;
			$Mechanic = $check_dt_in_inteos[0]->Mechanic;

			if ($Mechanic != '') {
				$msge = 'This machine '.$MachNum.' has ACTIVE downtime on line '.$ModNam.' by LineLeader: '.$LineLeader .' and Mechanic: '.$Mechanic.' ';
			} else {
				$msge = 'This machine '.$MachNum.' has ACTIVE downtime on line '.$ModNam.' by LineLeader: '.$LineLeader .'.  ';	
			}
			// dd($msge);
			$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
			
			return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location', 'new_area', 'new_plant', 'session','machines','msge'));
		}

		// SAVE TO TEMP TABLE
		$table = temp_move_machine::firstOrNew(['os' => $machine_temp]);
		$table->os_id =  $exist[0]->id;
		$table->location =  $exist[0]->location;
		$table->os =  $machine_temp;
		$table->brand =  $exist[0]->brand;
		$table->type =  $exist[0]->type;
		$table->code =  $exist[0]->code;
		$table->ses =  Session::getId();
		$table->new_location_id =  $new_location_id;
		$table->new_location =  $new_location;
		$table->new_area =  $new_area;
		$table->new_plant =  $new_plant;
		$table->save();		

		$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_temp.' added to the list';
		return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id','new_location','new_area','new_plant','session','machines','msg'));
	}

	public function move_machine_in_plant_remove($id, $ses) {
		
		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_move_machines WHERE ses = '".$ses."' order by id desc"));
		$new_location = $ses_data[0]->new_location;
		$new_location_id = $ses_data[0]->new_location_id;
		$new_area = $ses_data[0]->new_area;
		$new_plant = $ses_data[0]->new_plant;
		$session = $ses;

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $new_plant)
			            ->where('machines.location', '!=', $new_location)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

		$machine_to_remove = DB::table('temp_move_machines')
			            ->where('temp_move_machines.id', '=', $id)
			            ->select('temp_move_machines.id', 'temp_move_machines.os')
			            ->get();
		// dd($machine_to_remove[0]->os);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_move_machines WHERE ses = '".$ses."' AND id = '".$id."' "));
		
		$data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_to_remove[0]->os.' removed from the list';
		return view('Mechanics.move_machine_in_plant_scan', compact('data','new_location_id', 'new_location','new_area','new_plant','session','machines','msg'));
	}

	public function move_machine_in_plant_confirm($session) {
		//
		$session;
		$ses_data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);
	
		if (!isset($ses_data[0]->id)) {

			$msge = 'List was empty, first choose destination location?';
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

			if (substr($new_area,0,3) == 'LIN' ) {
				$machine_status = 'IN_LINE';
			} elseif (substr($new_area,0,3) == 'STO' ) {
				$machine_status = 'STOCK';
			}

			// try {
				if ($new_location == 'REPAIRING_SU') {
					
					$table = machines::where(['os' => $os])->update(['location' => $new_location, 'location_id' => $new_location_id, 'machine_status' => 'TO_REPAIR']);

				} elseif ($new_location == 'REPAIRING_KI') {

					$table = machines::where(['os' => $os])->update(['location' => $new_location, 'location_id' => $new_location_id, 'machine_status' => 'TO_REPAIR']);

				} elseif ($new_location == 'REPAIRING_SE') {

					$table = machines::where(['os' => $os])->update(['location' => $new_location, 'location_id' => $new_location_id, 'machine_status' => 'TO_REPAIR']);

				} else {

					$table = machines::where(['os' => $os])->update(['location' => $new_location, 'location_id' => $new_location_id, 'machine_status' => $machine_status]);
				}

			// } catch(\Illuminate\Database\QueryException $ex){ 
			// 	dd('problem to save');
			// }
				
				DB::connection('sqlsrv2')->update(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_ModMach]
  				SET [BdkCLZG].[dbo].[CNF_ModMach].[MaStat] = 1 , [BdkCLZG].[dbo].[CNF_ModMach].[MdCod] = NULL
  				FROM [BdkCLZG].[dbo].[CNF_ModMach] as mm
  				JOIN [BdkCLZG].[dbo].[CNF_MachPool] as mp ON mm.[MdCod] = mp.[Cod]
  				WHERE mp.[MachNum] = '".$os."' "));

				DB::connection('sqlsrv2')->update(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach]
  				SET mm.[MaStat] = 1 , mm.[MdCod] = NULL
  				FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] as mm
  				JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as mp ON mp.[Cod] = mm.[MdCod]
  				WHERE mp.[MachNum] = '".$os."' "));
		}

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_move_machines WHERE ses = '".$session."' "));

		
		$locations = DB::table('locations')
		            ->join('areas', 'areas.id', '=', 'locations.area_id')
		            ->join('plants', 'plants.id', '=', 'areas.plant_id')
		            ->where('locations.active', '=', '1')
		            ->select('locations.id', 'locations.location', 'areas.area', 'plants.plant')
		            ->orderBy('plants.plant', 'desc')
		            ->get();

		$msgs = 'Successfully saved';
		return view('Mechanics.move_machine_in_plant', compact('msgs','locations'));
	}

	public function move_machine_in_plant_cancel($session) {
		//
		$session;
		$ses_data = DB::table('temp_move_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_move_machines WHERE ses = '".$session."' "));

		$locations = DB::table('locations')
		            ->join('areas', 'areas.id', '=', 'locations.area_id')
		            ->join('plants', 'plants.id', '=', 'areas.plant_id')
		            ->where('locations.active', '=', '1')
		            ->where('areas.area', '=', 'STOCK_S')
		            ->orWhere('areas.area', '=', 'STOCK_K')
		            ->orWhere('areas.area', '=', 'STOCK_Z')
		            ->select('locations.id', 'locations.location', 'areas.area', 'plants.plant')
		            ->orderBy('plants.plant', 'desc')
		            ->get();

		$msgs = 'Successfully canceled';
		return view('Mechanics.move_machine_in_plant', compact('msgs','locations'));	
	}

// TRANSFER MACHINE

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

		if ($plant_from != 'SUBOTICA' AND $plant_from != 'KIKINDA' AND $plant_from != 'SENTA') {
			$msge = 'Souce plant not valid, please choose SUBOTICA,KIKINDA or SENTA.';
			return view('Mechanics.transfer_machine', compact('msge'));
		}

		return view('Mechanics.transfer_machine_to', compact('plant_from'));
	}

	public function transfer_machine_to(Request $request) {
		//
		// $this->validate($request, ['plant_from' => 'required','plant_to' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$plant_from = strtoupper($input['plant_from']);
		$plant_to = strtoupper($input['plant_to']);

		if (empty($input['plant_to'])) {
			$msge = 'Please choose destination plant';
			return view('Mechanics.transfer_machine_to', compact('plant_from','msge'));
		}

		if ($plant_from == $plant_to) {
			$msge = 'Destination plant should be different from source plant';
			return view('Mechanics.transfer_machine_to', compact('plant_from','msge'));
		}

		if ($plant_to != 'SUBOTICA' AND $plant_to != 'KIKINDA' AND $plant_to != 'SENTA') {
			$msge = 'Destination plant not valid.';
			return view('Mechanics.transfer_machine_to', compact('plant_from','msge'));
		}

		$session = Session::getId();

		$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
		$msg = "Please scan Machine";

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $plant_from)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

		return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','session','machines','msg'));
	}

	public function transfer_machine_scan(Request $request) {
		//
		$input = $request->all();
		// dd($input);
		$session = Session::getId();
		$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
		// dd($data);

		if ($input['transfer_doc'] != "") {
			$transfer_doc = $input['transfer_doc'];	
		} else {
			// dd('problem');
			$plant_from = strtoupper($input['plant_from']);
			$plant_to = strtoupper($input['plant_to']);
			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $plant_from)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

			$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
			$msge = 'Please insert first document number (otpremnica)';
			return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','transfer_doc','session','machines','msge'));
		}

		if (!empty($input['machine_temp1'])) {
			$machine_temp = $input['machine_temp1'];

		} elseif (!empty($input['machine_temp2'])) {
			$machine_temp = $input['machine_temp2'];

		} else {
			// dd('problem');
			$plant_from = strtoupper($input['plant_from']);
			$plant_to = strtoupper($input['plant_to']);
			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $plant_from)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

			$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
			$msge = 'Please scan or select machine';
			return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','transfer_doc','session','machines','msge'));
		}

		$machine_temp = strtoupper($machine_temp);
		// dd($machine_temp);

		if (isset($input['plant_from']) AND isset($input['plant_to'])) {
			$plant_from = strtoupper($input['plant_from']);
			$plant_to = strtoupper($input['plant_to']);

			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $plant_from)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8 OR strlen($machine_temp) == 9) {
				// dd($machine_temp);

				$exist = DB::table('machines')
	            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
	            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
	            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
	            ->where('machines.os', '=', $machine_temp)
	            ->select('machines.id', 'machines.os', 'machines.location','machines.brand','machines.type','machines.code','machines.machine_status','plants.plant')
	            ->get();
	            // dd($exist);
				
				
				if (isset($exist[0]->id)) {

					if (($plant_from == $exist[0]->plant) /*OR ($exist[0]->plant == NULL)*/)  {

						$data_temp = DB::connection('sqlsrv')->select(DB::raw("SELECT os, ses FROM temp_transfer_machines WHERE os = '".$exist[0]->os."' AND ses = '".$session."' "));
						if (isset($data_temp[0]->os)) {
							//							
							$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
							$msge = 'Machine already scaned';
							return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','transfer_doc','session','machines','msge'));
						} else {

							if ($exist[0]->machine_status == 'IN_LINE') {
							
								$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
								$msge = 'Machine have status IN_LINE and it is on the line '.$exist[0]->location.' . Please logout machine first. ';
								return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','transfer_doc','session','machines','msge'));
							}
						}
					} else {

						$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
						if ($exist[0]->plant == NULL) {
							$exist[0]->plant = 'NOT DEFINED';
						}
						$msge = 'Machine is in '.$exist[0]->plant.' plant but source plant (from) is '.$plant_from. ' .';
						return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','transfer_doc','session','machines','msge'));
					}
				} else {
					
					$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
					$msge = 'Machine does not exist in table';
					return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','transfer_doc','session','machines','msge'));
				}
			} else {
				
				$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
				$msge = 'Machine barcode must have 7, 8 or 9 characters';
				return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','transfer_doc','session','machines','msge'));
			}
		} else {
			dd('Error, zovi IT.');
		}

		$table = temp_transfer_machine::firstOrNew(['os' => strtoupper($machine_temp)]);
		$table->os_id =  $exist[0]->id;
		$table->os =  strtoupper($machine_temp);
		$table->brand =  $exist[0]->brand;
		$table->type =  $exist[0]->type;
		$table->code =  $exist[0]->code;
		$table->plant_from =  strtoupper($plant_from);
		$table->plant_to =  strtoupper($plant_to);
		$table->ses =  Session::getId();
		$table->transfer_doc =  $transfer_doc;
		$table->save();

		$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_temp.' added to the list';
		return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','transfer_doc','session','machines','msg'));
	}

	public function transfer_machine_remove($id, $session) {

		$ses = $session;
		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_transfer_machines WHERE ses = '".$ses."' order by id desc"));
		$plant_from = strtoupper($ses_data[0]->plant_from);
		$plant_to = strtoupper($ses_data[0]->plant_to);
		$transfer_doc = $ses_data[0]->transfer_doc;

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $plant_from)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

	    $machine_to_remove = DB::table('temp_transfer_machines')
			            ->where('temp_transfer_machines.id', '=', $id)
			            ->select('temp_transfer_machines.id', 'temp_transfer_machines.os')
			            ->get();
		// dd($machine_to_remove);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_transfer_machines WHERE ses = '".$ses."' AND id = '".$id."' "));

		$data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_to_remove[0]->os.' removed from the list';
		return view('Mechanics.transfer_machine_scan', compact('data','plant_from','plant_to','transfer_doc','session','machines','msg'));
	}

	public function transfer_machine_confirm($session) {
		//
		$session;
		$ses_data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);
	
		if (!isset($ses_data[0]->id)) {

			$msge = 'List was empty, first choose transfer From (plant)?';
			return view('Mechanics.transfer_machine', compact('msge'));
		}

		$plant_from = strtoupper($ses_data[0]->plant_from);
		$plant_to = strtoupper($ses_data[0]->plant_to);
		$plant = DB::table('plants')->where(['plant' => $plant_to])->get();
		$transfer_doc = $ses_data[0]->transfer_doc;

		if ($plant[0]->plant == 'SUBOTICA') {

			$plant_new = $plant[0]->plant;
			$plant_id = $plant[0]->id;
			$new_location = 'RECEIVING_SU';
			$machine_status = 'STOCK';
			$new_location_id = DB::table('locations')->where('location', '=', 'RECEIVING_SU')->get();
			$new_location_id = $new_location_id[0]->id;
			$inteos_status = 'SU';
			// dd($new_location_id);

		} elseif ($plant[0]->plant == 'KIKINDA') {

			$plant_new = $plant[0]->plant;
			$plant_id = $plant[0]->id;
			$new_location = 'RECEIVING_KI';
			$machine_status = 'STOCK';
			$new_location_id = DB::table('locations')->where('location', '=', 'RECEIVING_KI')->get();
			$new_location_id = $new_location_id[0]->id;
			$inteos_status = 'KI';
			// dd($new_location_id);

		} elseif ($plant[0]->plant == 'SENTA') {

			$plant_new = $plant[0]->plant;
			$plant_id = $plant[0]->id;
			$new_location = 'RECEIVING_SE';
			$machine_status = 'STOCK';
			$new_location_id = DB::table('locations')->where('location', '=', 'RECEIVING_SE')->get();
			$new_location_id = $new_location_id[0]->id;
			$inteos_status = 'SU';
			// dd($new_location_id);

		} else {
			dd('Plant not recognized');
		}
		// dd($plant_new);

		for ($i=0; $i < count($ses_data); $i++) {

			$os_id = $ses_data[$i]->os_id;
			$os = $ses_data[$i]->os;
			$brand = $ses_data[$i]->brand;
			$type = $ses_data[$i]->type;
			$code = $ses_data[$i]->code;
			// dd($os);
			// try {
				$table = machines::where(['os' => $os])->update(['location' => $new_location, 'location_id' => $new_location_id, 'machine_status' => $machine_status, 'inteos_status' => $inteos_status]);
				
				/* write to log */

				$table = new transfer_machine_log;
				$table->os_id = $os_id;
				$table->os = $os;
				$table->brand = $brand;
				$table->type = $type;
				$table->code = $code;
				$table->function = 'TRANSFER';
				$table->destination = $new_location;
				$table->doc = $transfer_doc;
				$table->plant_from = $plant_from;
				$table->plant_to = $plant_to;
				$table->save();

				/* turn on and off in inteos */
				if (($plant_from == 'SUBOTICA') OR ($plant_from == 'SENTA')) {
					if (($plant_to == 'SUBOTICA') OR ($plant_to == 'SENTA')) {

						
					} else {

						// $update_su_inteos = DB::connection('sqlsrv2')->delete(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_MachPool]  SET NotAct = '1'  WHERE MachNum = '". $os."' "));
						$update_su_inteos = DB::connection('sqlsrv2')->delete(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool]
	  						SET from_plant.[Remark] = to_plant.[Remark], from_plant.[NotAct] = NULL
						  	FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as from_plant
						  	JOIN [BdkCLZG].[dbo].[CNF_MachPool] as to_plant ON from_plant.[MachNum] = to_plant.[MachNum]
						  	WHERE from_plant.[MachNum] = '". $os."' "));

						//$update_ki_inteos = DB::connection('sqlsrv2')->delete(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool]  SET NotAct = NULL  WHERE MachNum = '". $os."' "));
						$update_ki_inteos = DB::connection('sqlsrv2')->delete(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_MachPool] 
						  	SET [Remark] = '', NotAct = '1'
						  	WHERE [MachNum] = '". $os."' "));
					}
					
				} else {
					// $update_su_inteos = DB::connection('sqlsrv2')->delete(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_MachPool]  SET NotAct = NULL  WHERE MachNum = '". $os."' "));
					$update_su_inteos = DB::connection('sqlsrv2')->delete(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_MachPool]
						SET [BdkCLZG].[dbo].[CNF_MachPool].[Remark] = to_plant.[Remark], [BdkCLZG].[dbo].[CNF_MachPool].[NotAct] = NULL
						FROM [BdkCLZG].[dbo].[CNF_MachPool] as from_plant
						JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as to_plant ON from_plant.[MachNum] = to_plant.[MachNum]
						WHERE from_plant.[MachNum] = '". $os."' "));

					// $update_ki_inteos = DB::connection('sqlsrv2')->delete(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool]  SET NotAct = '1'  WHERE MachNum = '". $os."' "));
					$update_ki_inteos = DB::connection('sqlsrv2')->delete(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool]
						SET [Remark] = '', NotAct = '1'
						WHERE [MachNum] = '". $os."' "));
				}
				
				
			// } catch(\Illuminate\Database\QueryException $ex){
			// 	dd('problem to save');
			// }
		}
		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_transfer_machines WHERE ses = '".$session."' "));
		
		$msgs = 'Successfully saved';
		return view('Mechanics.transfer_machine', compact('msgs'));
	}

	public function transfer_machine_cancel($session) {
		//
		$session;
		$ses_data = DB::table('temp_transfer_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_transfer_machines WHERE ses = '".$session."' "));

		$locations = DB::table('locations')
		            ->join('areas', 'areas.id', '=', 'locations.area_id')
		            ->join('plants', 'plants.id', '=', 'areas.plant_id')
		            ->where('locations.active', '=', '1')
		            ->select('locations.id', 'locations.location', 'areas.area', 'plants.plant')
		            ->orderBy('plants.plant', 'desc')
		            ->get();

		$msgs = 'Successfully canceled';
		return view('Mechanics.transfer_machine', compact('msgs','locations'));	
	}

// BORROW MACHINE (GIVE & RETURN)

	public function borrow_machine() {
		// dd('cao');

		$borrow = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'BORROWED' "));
		$borrow = $borrow[0]->c;
		return view('Mechanics.borrow_machine', compact('borrow'));	
	}

// GIVE MACHINE

	public function give_machine() {
		//
		$session = Session::getId();

		$external_locations = DB::connection('sqlsrv')->select(DB::raw("SELECT l.location,
			   (SELECT COUNT(id) FROM [mechanics].[dbo].[machines] WHERE l.location = location) as qty,
			   l.id,
			   a.area,
			   p.plant
		  FROM [mechanics].[dbo].[locations] as l
		  JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
		  JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
		  LEFT JOIN [mechanics].[dbo].[machines] as m ON m.location = l.location

		WHERE p.plant = 'EXTERNAL' and l.active = 1
		GROUP BY l.location, l.id, a.area, p.plant"));
		// dd($external_locations);


		return view('Mechanics.give_machine', compact('session','external_locations'));
	}

	public function give_machine_to(Request $request) {
		//
		$this->validate($request, ['give_machine_to' => 'required']);
		$input = $request->all();
		// dd($input);

		$give_machine_to = strtoupper($input['give_machine_to']);
		$session = $input['session'];

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('locations.location', '!=', $give_machine_to)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		
		return view('Mechanics.give_machine_scan', compact('data','give_machine_to','session','machines','msg'));
	}

	public function give_machine_scan(Request $request) {
		//
		$input = $request->all(); 
		// dd($input);
		
		$give_machine_to = strtoupper($input['give_machine_to']);
		$session = $input['session'];
		if ($input['give_doc'] != "") {
			$give_doc = $input['give_doc'];	
		} else {
			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('locations.location', '!=', $give_machine_to)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

			$data = DB::table('temp_give_machines')->where('ses', '=', $session)->get();
			$msge = 'Please insert first document number (otpremnica)';
			return view('Mechanics.give_machine_scan', compact('data','give_machine_to','give_doc','session','machines','msge'));
		}
		
		// dd($machine_temp);
		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('locations.location', '!=', $give_machine_to)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

		$data = DB::table('temp_give_machines')->where('ses', '=', $session)->get();

		if (!empty($input['machine_temp1'])) {
			$machine_temp = strtoupper($input['machine_temp1']);
		} elseif (!empty($input['machine_temp2'])) {
			$machine_temp = strtoupper($input['machine_temp2']);
		} else {
			$msge = 'Please scan or select machine';
			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('locations.location', '!=', $give_machine_to)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
			$data = DB::table('temp_give_machines')->where('ses', '=', $session)->get();      
			return view('Mechanics.give_machine_scan', compact('data','give_machine_to','give_doc','session','machines','msge'));
		}

		if (isset($machine_temp)) {
			
			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8 OR strlen($machine_temp) == 9) {
				// dd($machine_temp);

				$exist = DB::table('machines')
	            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
	            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
	            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
	            ->where('machines.os', '=', $machine_temp)
	            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
	            ->get();
	            // dd($exist);
				
				if (isset($exist[0]->id)) {

					$data_temp = DB::connection('sqlsrv')->select(DB::raw("SELECT os, ses FROM temp_give_machines WHERE os = '".$exist[0]->os."' AND ses = '".$session."' "));
					if (isset($data_temp[0]->os)) {
						
						$data = DB::table('temp_give_machines')->where('ses', '=', $session)->get();
						$msge = 'Machine already scaned';
						return view('Mechanics.give_machine_scan', compact('data','give_machine_to','give_doc','session','machines','msge'));
					}
				} else {
					
					$data = DB::table('temp_give_machines')->where('ses', '=', $session)->get();
					$msge = 'Machine does not exist in table';
					return view('Mechanics.give_machine_scan', compact('data','give_machine_to','give_doc','session','machines','msge'));
				}
			} else {
				
				$data = DB::table('temp_give_machines')->where('ses', '=', $session)->get();
				$msge = 'Machine barcode must have 7 ,8 or 9 characters';
				return view('Mechanics.give_machine_scan', compact('data','give_machine_to','give_doc','session','machines','msge'));
			}
		} else {
			dd('Error, zovi IT.');
		}
		// dd($machine_temp);

		$table = temp_give_machine::firstOrNew(['os' => strtoupper($machine_temp)]);
		$table->os_id =  $exist[0]->id;
		$table->os =  strtoupper($machine_temp);
		$table->brand =  $exist[0]->brand;
		$table->type =  $exist[0]->type;
		$table->code =  $exist[0]->code;
		$table->give_machine_to =  strtoupper($give_machine_to);
		$table->give_doc =  $give_doc;
		$table->ses =  Session::getId();
		$table->save();

		$data = DB::table('temp_give_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_temp.' added to the list';
		return view('Mechanics.give_machine_scan', compact('data','give_machine_to','give_doc','session','machines','msg'));
	}

	public function give_machine_remove($id, $session) {

		$ses = $session;
		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_give_machines WHERE ses = '".$ses."' order by id desc"));
		$give_machine_to = strtoupper($ses_data[0]->give_machine_to);
		$give_doc = $ses_data[0]->give_doc;
		
		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('locations.location', '!=', $give_machine_to)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		$machine_to_remove = DB::table('temp_give_machines')
			            ->where('temp_give_machines.id', '=', $id)
			            ->select('temp_give_machines.id', 'temp_give_machines.os')
			            ->get();
	    // dd($machine_to_remove);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_give_machines WHERE ses = '".$ses."' AND id = '".$id."' "));
		
		$data = DB::table('temp_give_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_to_remove[0]->os.' removed from the list';
		return view('Mechanics.give_machine_scan', compact('data','give_machine_to','give_doc','session','machines','msg'));
	}

	public function give_machine_confirm($session) {
		//
		$session;
		$ses_data = DB::table('temp_give_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);
	
		if (!isset($ses_data[0]->id)) {

			$msge = 'List was empty, first choose give to (plant)?';
			return view('Mechanics.give_machine', compact('msge','session'));
		}

		$give_machine_to = strtoupper($ses_data[0]->give_machine_to);
		$give_doc = $ses_data[0]->give_doc;
		$new_location = $give_machine_to;
		// $new_location_id = NULL;
		$new_location_id = DB::table('locations')
			            ->where('locations.location', '=', $give_machine_to)
			            ->where('locations.active', '=', '1')
			            ->select('locations.id')
			            ->get();
	    // dd($new_location_id[0]->id);
	    $new_location_id = $new_location_id[0]->id;

		for ($i=0; $i < count($ses_data); $i++) {
			
			$os_id = $ses_data[$i]->os_id;
			$os = $ses_data[$i]->os;
			$brand = $ses_data[$i]->brand;
			$type = $ses_data[$i]->type;
			$code = $ses_data[$i]->code;
			
			// try {
				$table = machines::where(['os' => $os])->update(['location' => $new_location, 'location_id' => $new_location_id, 'machine_status' => 'BORROWED', 'give_doc'=> $give_doc]);
				
				/* write to log */
				$table = new borrow_machine_log;
				$table->os_id = $os_id;
				$table->os = $os;
				$table->brand = $brand;
				$table->type = $type;
				$table->code = $code;
				$table->function = 'GIVE';
				$table->destination = $new_location;
				$table->doc = $give_doc;
				$table->save();

				/* turn on and off in inteos */
				/* no need */
			// } catch(\Illuminate\Database\QueryException $ex){
			// 	dd('problem to save');
			// }
			
		}
		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_give_machines WHERE ses = '".$session."' "));

		$msgs = 'Successfully saved';
		$external_locations = DB::connection('sqlsrv')->select(DB::raw("SELECT l.location,
			   (SELECT COUNT(id) FROM [mechanics].[dbo].[machines] WHERE l.location = location) as qty,
			   l.id,
			   a.area,
			   p.plant
		  FROM [mechanics].[dbo].[locations] as l
		  JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
		  JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
		  LEFT JOIN [mechanics].[dbo].[machines] as m ON m.location = l.location

		WHERE p.plant = 'EXTERNAL' and l.active = 1
		GROUP BY l.location, l.id, a.area, p.plant"));
		return view('Mechanics.give_machine', compact('msgs','session','external_locations'));
	}

	public function give_machine_cancel($session) {
		//
		$session;
		$ses_data = DB::table('temp_give_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_give_machines WHERE ses = '".$session."' "));

		$external_locations = DB::connection('sqlsrv')->select(DB::raw("SELECT l.location,
			   (SELECT COUNT(id) FROM [mechanics].[dbo].[machines] WHERE l.location = location) as qty,
			   l.id,
			   a.area,
			   p.plant
		  FROM [mechanics].[dbo].[locations] as l
		  JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
		  JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
		  LEFT JOIN [mechanics].[dbo].[machines] as m ON m.location = l.location

		WHERE p.plant = 'EXTERNAL' and l.active = 1
		GROUP BY l.location, l.id, a.area, p.plant"));

		$msgs = 'Successfully canceled';
		return view('Mechanics.give_machine', compact('msgs','session','external_locations'));
	}

// RETURN

	public function return_machine() {
		//
		$session = Session::getId();
		
		$external_locations = DB::connection('sqlsrv')->select(DB::raw("SELECT l.location,
			   (SELECT COUNT(id) FROM [mechanics].[dbo].[machines] WHERE l.location = location) as qty,
			   l.id,
			   a.area,
			   p.plant
		  FROM [mechanics].[dbo].[locations] as l
		  JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
		  JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
		  LEFT JOIN [mechanics].[dbo].[machines] as m ON m.location = l.location

		WHERE p.plant = 'EXTERNAL' and l.active = 1
		GROUP BY l.location, l.id, a.area, p.plant"));

		return view('Mechanics.return_machine', compact('session','external_locations'));
	}

	public function return_machine_to(Request $request) {
		//
		$this->validate($request, ['return_machine_to' => 'required']);
		$input = $request->all();
		// dd($input);

		$return_machine_to = strtoupper($input['return_machine_to']);
		$session = $input['session'];

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('locations.location', '=', $return_machine_to)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		
		return view('Mechanics.return_machine_scan', compact('data','return_machine_to','session','machines','msg'));
	}

	public function return_machine_scan(Request $request) {
		//
		$input = $request->all(); 
		// dd($input);
		
		$return_machine_to = strtoupper($input['return_machine_to']);
		$session = $input['session'];
		if ($input['return_doc'] != "") {
			$return_doc = $input['return_doc'];	
		} else {
			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('locations.location', '=', $return_machine_to)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
			$data = DB::table('temp_return_machines')->where('ses', '=', $session)->get();
			$msge = 'Please insert first document number (otpremnica)';
			return view('Mechanics.return_machine_scan', compact('data','return_machine_to','return_doc','session','machines','msge'));

		}

		// dd($machine_temp);

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('locations.location', '=', $return_machine_to)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
			            
		$data = DB::table('temp_return_machines')->where('ses', '=', $session)->get();

		if (!empty($input['machine_temp1'])) {
			$machine_temp = strtoupper($input['machine_temp1']);

		} elseif (!empty($input['machine_temp2'])) {
			$machine_temp = strtoupper($input['machine_temp2']);
		} else {
			$msge = 'Please scan or select machine';
			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('locations.location', '=', $return_machine_to)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
			$data = DB::table('temp_return_machines')->where('ses', '=', $session)->get();      
			return view('Mechanics.return_machine_scan', compact('data','return_machine_to','return_doc','session','machines','msge'));
		}

		if (isset($machine_temp)) {
			$machine_temp = strtoupper($machine_temp);
			
			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8 OR strlen($machine_temp) == 9) {
				// dd($machine_temp);

				$exist = DB::table('machines')
	            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
	            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
	            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
	            ->where('machines.os', '=', $machine_temp)
	            ->where('machines.location', '=' , $return_machine_to)
	            ->select('machines.id', 'machines.os', 'machines.location','machines.brand', 'machines.type', 'machines.code', 'plants.plant')
	            ->get();
				
				if (isset($exist[0]->id)) {

						$data_temp = DB::connection('sqlsrv')->select(DB::raw("SELECT os, ses FROM temp_return_machines WHERE os = '".$exist[0]->os."' AND ses = '".$session."' "));
						if (isset($data_temp[0]->os)) {
							
							$data = DB::table('temp_return_machines')->where('ses', '=', $session)->get();
							$msge = 'Machine already scaned';
							return view('Mechanics.return_machine_scan', compact('data','return_machine_to','return_doc','session','machines','msge'));
						}

				} else {
					
					$data = DB::table('temp_return_machines')->where('ses', '=', $session)->get();
					$msge = 'Machine '.$machine_temp.' does not exist on location '.$return_machine_to.' ';
					return view('Mechanics.return_machine_scan', compact('data','return_machine_to','return_doc','session','machines','msge'));
				}
			} else {
				
				$data = DB::table('temp_return_machines')->where('ses', '=', $session)->get();
				$msge = 'Machine barcode must have 7 ,8 or 9 characters';
				return view('Mechanics.return_machine_scan', compact('data','return_machine_to','return_doc','session','machines','msge'));
			}
		} else {
			dd('Error, zovi IT.');
		}
		// dd($machine_temp);

		$table = temp_return_machine::firstOrNew(['os' => strtoupper($machine_temp)]);
		$table->os_id =  $exist[0]->id;
		$table->os =  strtoupper($machine_temp);
		$table->brand =  $exist[0]->brand;
		$table->type =  $exist[0]->type;
		$table->code =  $exist[0]->code;
		$table->return_machine_to =  strtoupper($return_machine_to);
		$table->return_doc =  $return_doc;
		$table->ses =  Session::getId();
		$table->save();

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_return_machines WHERE ses = '".$session."' order by id desc"));
		$msg = 'Machine added to the list';
		return view('Mechanics.return_machine_scan', compact('data','return_machine_to','return_doc','session','machines','msg'));
	}

	public function return_machine_remove($id, $session) {

		$ses = $session;
		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_return_machines WHERE ses = '".$ses."' order by id desc"));
		$return_machine_to = strtoupper($ses_data[0]->return_machine_to);
		$return_doc = $ses_data[0]->return_doc;
		
		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('locations.location', '=', $return_machine_to)
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		$machine_to_remove = DB::table('temp_return_machines')
			            ->where('temp_return_machines.id', '=', $id)
			            ->select('temp_return_machines.id', 'temp_return_machines.os')
			            ->get();

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_return_machines WHERE ses = '".$ses."' AND id = '".$id."' "));
		
		// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_return_machines WHERE ses = '".$ses."' order by id desc"));
		$data = DB::table('temp_return_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_to_remove[0]->os.' removed from the list';
		return view('Mechanics.return_machine_scan', compact('data','return_machine_to','return_doc','session','machines','msg'));
	}

	public function return_machine_confirm($session) {
		//
		$session;
		$ses_data = DB::table('temp_return_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);
	
		if (!isset($ses_data[0]->id)) {

			$msge = 'List was empty, first choose return from (plant)?';
			return view('Mechanics.return_machine', compact('msge', 'session'));
		}

		$return_machine_to = strtoupper($ses_data[0]->return_machine_to);
		$return_doc = $ses_data[0]->return_doc;
		// $new_location = $return_machine_to;
		// $new_location_id = NULL;
		$new_location = 'RECEIVING_SU';
		$new_location_id = DB::table('locations')
			            ->where('locations.location', '=', $new_location)
			            ->where('locations.active', '=', '1')
			            ->select('locations.id')
			            ->get();
		// dd($new_location_id);
		$new_location_id = $new_location_id[0]->id;
		
		for ($i=0; $i < count($ses_data); $i++) {
			
			$os_id = $ses_data[$i]->os_id;
			$os = $ses_data[$i]->os;
			$brand = $ses_data[$i]->brand;
			$type = $ses_data[$i]->type;
			$code = $ses_data[$i]->code;
			// $source = $ses_data[$i]->return_machine_to;
			
			// try {

				$table = machines::where(['os' => $os])->update(['location' => $new_location, 'location_id' => $new_location_id, 'machine_status' => 'STOCK', 'give_doc'=> '' ]);
				
				/* write to log */

				$table = new borrow_machine_log;
				$table->os_id = $os_id;
				$table->os = $os;
				$table->brand = $brand;
				$table->type = $type;
				$table->code = $code;
				$table->function = 'RETURN';
				$table->destination = $new_location;
				// $table->source = $source;
				$table->doc = $return_doc;
				$table->save();

				/* turn on and off in inteos */

			// } catch(\Illuminate\Database\QueryException $ex){
			// 	dd('problem to save');
			// }

		}
		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_return_machines WHERE ses = '".$session."' "));
		
		$msgs = 'Successfully saved and returned to location RECEIVING_SU';
		$external_locations = DB::connection('sqlsrv')->select(DB::raw("SELECT l.location,
			   (SELECT COUNT(id) FROM [mechanics].[dbo].[machines] WHERE l.location = location) as qty,
			   l.id,
			   a.area,
			   p.plant
		  FROM [mechanics].[dbo].[locations] as l
		  JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
		  JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
		  LEFT JOIN [mechanics].[dbo].[machines] as m ON m.location = l.location

		WHERE p.plant = 'EXTERNAL' and l.active = 1
		GROUP BY l.location, l.id, a.area, p.plant"));
		return view('Mechanics.return_machine', compact('msgs','session','external_locations'));
	}

	public function return_machine_cancel($session) {
		//
		$session;
		$ses_data = DB::table('temp_return_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_return_machines WHERE ses = '".$session."' "));

		$external_locations = DB::connection('sqlsrv')->select(DB::raw("SELECT l.location,
			   (SELECT COUNT(id) FROM [mechanics].[dbo].[machines] WHERE l.location = location) as qty,
			   l.id,
			   a.area,
			   p.plant
		  FROM [mechanics].[dbo].[locations] as l
		  JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
		  JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
		  LEFT JOIN [mechanics].[dbo].[machines] as m ON m.location = l.location

		WHERE p.plant = 'EXTERNAL' and l.active = 1
		GROUP BY l.location, l.id, a.area, p.plant"));

		$msgs = 'Successfully canceled';
		return view('Mechanics.return_machine', compact('msgs','session','external_locations'));
	}

// REPAIR MACHINE (ADJUST & FIX)

	public function repair_machine() {
		// dd('cao');
		
		$on_repair = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'TO_REPAIR' "));
		$on_repair = $on_repair[0]->c;
		// dd($on_repair);

		return view('Mechanics.repair_machine', compact('on_repair'));	
	}

// ADJUST MACHINE

	public function adjust_machine() {
		//
		$session = Session::getId();
		return view('Mechanics.adjust_machine', compact('session'));
	}

	public function adjust_machine_to(Request $request) {
		//
		$this->validate($request, ['adjust_machine_to' => 'required']);
		$input = $request->all();
		// dd($input);

		$adjust_machine_to = strtoupper($input['adjust_machine_to']);
		$session = $input['session'];
		
		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            // ->where('plants.plant', '=', $adjust_machine_to)
			            ->where('machines.machine_status', '!=', 'TO_REPAIR')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

		return view('Mechanics.adjust_machine_scan', compact('data','adjust_machine_to','session','machines','msg'));
	}

	public function adjust_machine_scan(Request $request) {
		//
		$input = $request->all(); 
		// $session = Session::getId();
		
		$adjust_machine_to = strtoupper($input['adjust_machine_to']);
		$session = $input['session'];
		// dd($input);

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $adjust_machine_to)
			            ->where('machines.machine_status', '!=', 'TO_REPAIR')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

		$data = DB::table('temp_adjust_machines')->where('ses', '=', $session)->get();

		if (!empty($input['machine_temp1'])) {
			$machine_temp = strtoupper($input['machine_temp1']);

		} elseif (!empty($input['machine_temp2'])) {
			$machine_temp = strtoupper($input['machine_temp2']);
		} else {
			$msge = 'Please scan or select machine';
			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $adjust_machine_to)
			            ->where('machines.machine_status', '!=', 'TO_REPAIR')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
			$data = DB::table('temp_adjust_machines')->where('ses', '=', $session)->get();      
			return view('Mechanics.adjust_machine_scan', compact('data','adjust_machine_to','session','machines','msge'));
		}

		if (isset($machine_temp)) {
			
			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8 OR strlen($machine_temp) == 9) {
				// dd($machine_temp);

				$exist = DB::table('machines')
	            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
	            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
	            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
	            ->where('machines.os', '=', $machine_temp)
	            ->where('machines.machine_status', '!=', 'TO_REPAIR')
	            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
	            ->get();
		        // dd($exist);
				
				if (isset($exist[0]->id)) {

					if ($adjust_machine_to == $exist[0]->plant)  {

						$data_temp = DB::connection('sqlsrv')->select(DB::raw("SELECT os, ses FROM temp_adjust_machines WHERE os = '".$exist[0]->os."' AND ses = '".$session."' "));
						if (isset($data_temp[0]->os)) {
						
							$data = DB::table('temp_adjust_machines')->where('ses', '=', $session)->get();
							$msge = 'Machine already scaned';
							return view('Mechanics.adjust_machine_scan', compact('data','adjust_machine_to','session','machines','msge'));
						}
					} else {
						
						$data = DB::table('temp_adjust_machines')->where('ses', '=', $session)->get();
						if ($exist[0]->plant == NULL) {
							$exist[0]->plant = 'NOT DEFINED';
						}
						$msge = 'Machine is in '.$exist[0]->plant.' plant but choosen plant to repair is '.$adjust_machine_to. ' ! ';
						return view('Mechanics.adjust_machine_scan', compact('data','adjust_machine_to','session','machines','msge'));
					}
				} else {
					
					$data = DB::table('temp_adjust_machines')->where('ses', '=', $session)->get();
					$msge = 'Machine does not exist in table or machine have status TO_REPAIR';
					return view('Mechanics.adjust_machine_scan', compact('data','adjust_machine_to','session','machines','msge'));
				}
			} else {
				
				$data = DB::table('temp_adjust_machines')->where('ses', '=', $session)->get();
				$msge = 'Machine barcode must have 7 ,8 or 9 characters';
				return view('Mechanics.adjust_machine_scan', compact('data','adjust_machine_to','session','machines','msge'));
			}
		} else {
			dd('Error, zovi IT.');
		}
		// dd($machine_temp);

		$table = temp_adjust_machine::firstOrNew(['os' => strtoupper($machine_temp)]);
		$table->os_id =  $exist[0]->id;
		$table->os =  strtoupper($machine_temp);
		$table->brand = $exist[0]->brand;
		$table->type =  $exist[0]->type;
		$table->code =  $exist[0]->code;
		$table->adjust_machine_to =  strtoupper($adjust_machine_to);
		$table->ses =  Session::getId();
		$table->save();

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_adjust_machines WHERE ses = '".$session."' order by id desc"));
		$msg = 'Machine added '.$machine_temp.' to the list';
		return view('Mechanics.adjust_machine_scan', compact('data','adjust_machine_to','session','machines','msg'));
	}

	public function adjust_machine_remove($id, $session) {

		$ses = $session;
		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_adjust_machines WHERE ses = '".$ses."' order by id desc"));
		$adjust_machine_to = strtoupper($ses_data[0]->adjust_machine_to);
		
		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $adjust_machine_to)
			            ->where('machines.machine_status', '!=', 'TO_REPAIR')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		$machine_to_remove = DB::table('temp_adjust_machines')
			            ->where('temp_adjust_machines.id', '=', $id)
			            ->select('temp_adjust_machines.id', 'temp_adjust_machines.os')
			            ->get();

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_adjust_machines WHERE ses = '".$ses."' AND id = '".$id."' "));
		
		// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_adjust_machines WHERE ses = '".$ses."' order by id desc"));
		$data = DB::table('temp_adjust_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_to_remove[0]->os.' removed from the list';
		return view('Mechanics.adjust_machine_scan', compact('data','adjust_machine_to','session','machines','msg'));
	}

	public function adjust_machine_confirm($session) {
		//
		$session;
		$ses_data = DB::table('temp_adjust_machines')->where('ses', '=', $session)->get();

		if (!isset($ses_data[0]->id)) {

			$msge = 'List was empty, first choose adjust to (plant)?';
			return view('Mechanics.adjust_machine', compact('msge','session'));
		}

		$adjust_machine_to = strtoupper($ses_data[0]->adjust_machine_to);
		// $new_location = $adjust_machine_to;
		// $new_location_id = NULL;
		
		$exist_in_plant = DB::table('machines')
		            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
		            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
		            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
		            ->where('machines.os', '=', $ses_data[0]->os)
		            ->select('machines.id', 'machines.os', 'machines.location', 'plants.plant')
		            ->get();
		
		if ($adjust_machine_to == 'SUBOTICA')  {

			$new_location = 'REPAIRING_SU';
			$new_location_id = DB::table('locations')
			            ->where('locations.location', '=', $new_location)
			            ->where('locations.active', '=', '1')
			            ->select('locations.id')
			            ->get();
	    	// dd($new_location_id[0]->id);
	    	$new_location_id = $new_location_id[0]->id;

		} elseif ($adjust_machine_to == 'KIKINDA') {

			$new_location = 'REPAIRING_KI';
			$new_location_id = DB::table('locations')
			            ->where('locations.location', '=', $new_location)
			            ->where('locations.active', '=', '1')
			            ->select('locations.id')
			            ->get();
	    	// dd($new_location_id[0]->id);
	    	$new_location_id = $new_location_id[0]->id;

		} elseif ($adjust_machine_to == 'SENTA') {

			$new_location = 'REPAIRING_SE';
			$new_location_id = DB::table('locations')
			            ->where('locations.location', '=', $new_location)
			            ->where('locations.active', '=', '1')
			            ->select('locations.id')
			            ->get();
	    	// dd($new_location_id[0]->id);
	    	$new_location_id = $new_location_id[0]->id;

		} else {
			dd('call IT');
		}

		for ($i=0; $i < count($ses_data); $i++) {
			
			$os = $ses_data[$i]->os;
			// dd($os);
			// try {

				// $table = machines::where(['os' => $os])->update(['location' => $new_location, 'location_id' => $new_location_id, 'machine_status' => 'TO_REPAIR']);
				$table = machines::where(['os' => $os])->update(['machine_status' => 'TO_REPAIR']);
				

			// } catch(\Illuminate\Database\QueryException $ex){
			// 	dd('problem to save');
			// }
		}
		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_adjust_machines WHERE ses = '".$session."' "));
		
		$msgs = 'Successfully saved';
		return view('Mechanics.adjust_machine', compact('msgs','session'));
	}

	public function adjust_machine_cancel($session) {
		//
		$session;
		$ses_data = DB::table('temp_adjust_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_adjust_machines WHERE ses = '".$session."' "));

		$msgs = 'Successfully canceled';
		return view('Mechanics.adjust_machine', compact('msgs','session'));
	}	

// FIX MACHINE

	public function fix_machine() {
		//
		$session = Session::getId();
		return view('Mechanics.fix_machine', compact('session'));
	}

	public function fix_machine_to(Request $request) {
		//
		$this->validate($request, ['fix_machine_to' => 'required']);
		$input = $request->all();
		// dd($input);

		$fix_machine_to = strtoupper($input['fix_machine_to']);
		$session = $input['session'];

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            // ->where('locations.location', '=', $fix_machine_to)
			            ->where('machines.machine_status', '=', 'TO_REPAIR')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		
		return view('Mechanics.fix_machine_scan', compact('data','fix_machine_to','session','machines','msg'));
	}

	public function fix_machine_scan(Request $request) {
		//
		$input = $request->all(); 
		// $session = Session::getId();
		
		$fix_machine_to = strtoupper($input['fix_machine_to']);
		$session = $input['session'];
		// dd($machine_temp);

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            // ->where('locations.location', '=', $fix_machine_to)
			            ->where('machines.machine_status', '=', 'TO_REPAIR')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		$data = DB::table('temp_fix_machines')->where('ses', '=', $session)->get();

		if (!empty($input['machine_temp1'])) {
			$machine_temp = strtoupper($input['machine_temp1']);

		} elseif (!empty($input['machine_temp2'])) {
			$machine_temp = strtoupper($input['machine_temp2']);
		} else {
			$msge = 'Please scan or select machine';
			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            // ->where('locations.location', '=', $fix_machine_to)
			            ->where('machines.machine_status', '=', 'TO_REPAIR')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
			$data = DB::table('temp_fix_machines')->where('ses', '=', $session)->get();      
			return view('Mechanics.fix_machine_scan', compact('data','fix_machine_to','session','machines','msge'));
		}

		if (isset($machine_temp)) {
			
			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8 OR strlen($machine_temp) == 9) {
				// dd($machine_temp);

				$exist = DB::table('machines')
	            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
	            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
	            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
	            ->where('machines.os', '=', $machine_temp)
	            // ->where('machines.location', '=' , $fix_machine_to)
	            ->where('machines.machine_status', '=', 'TO_REPAIR')
	            ->select('machines.id', 'machines.os', 'machines.location','machines.brand', 'machines.type', 'machines.code', 'plants.plant')
	            ->get();
	            // dd($exist);
				
				if (isset($exist[0]->id)) {

					// if ($fix_machine_to == $exist[0]->location) {
						$data_temp = DB::connection('sqlsrv')->select(DB::raw("SELECT os, ses FROM temp_fix_machines WHERE os = '".$exist[0]->os."' AND ses = '".$session."' "));
						if (isset($data_temp[0]->os)) {
							
							$data = DB::table('temp_fix_machines')->where('ses', '=', $session)->get();
							$msge = 'Machine already scaned';
							return view('Mechanics.fix_machine_scan', compact('data','fix_machine_to','session','machines','msge'));
						}
					// } else {
					
					// 	$data = DB::table('temp_fix_machines')->where('ses', '=', $session)->get();
					// 	$msge = 'Machine '.$machine_temp.' does not exist on location '.$fix_machine_to.' ';
					// 	return view('Mechanics.fix_machine_scan', compact('data','fix_machine_to','session','machines','msge'));
					// }

				} else {
				
					$data = DB::table('temp_fix_machines')->where('ses', '=', $session)->get();
					$msge = 'Machine '.$machine_temp.' does not exist on location '.$fix_machine_to.' ';
					return view('Mechanics.fix_machine_scan', compact('data','fix_machine_to','session','machines','msge'));
				}
			} else {
				
				$data = DB::table('temp_fix_machines')->where('ses', '=', $session)->get();
				$msge = 'Machine barcode must have 7 ,8 or 9 characters';
				return view('Mechanics.fix_machine_scan', compact('data','fix_machine_to','session','machines','msge'));
			}
		} else {
			dd('Error, zovi IT.');
		}
		// dd($machine_temp);

		$table = temp_fix_machine::firstOrNew(['os' => strtoupper($machine_temp)]);
		$table->os_id =  $exist[0]->id;
		$table->os =  strtoupper($machine_temp);
		$table->brand = $exist[0]->brand;
		$table->type =  $exist[0]->type;
		$table->code =  $exist[0]->code;
		$table->fix_machine_to =  strtoupper($fix_machine_to);
		$table->ses =  Session::getId();
		$table->save();

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_fix_machines WHERE ses = '".$session."' order by id desc"));
		$msg = 'Machine '.$machine_temp.' added to the list';
		return view('Mechanics.fix_machine_scan', compact('data','fix_machine_to','session','machines','msg'));
	}

	public function fix_machine_remove($id, $session) {

		$ses = $session;
		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_fix_machines WHERE ses = '".$ses."' order by id desc"));
		$fix_machine_to = strtoupper($ses_data[0]->fix_machine_to);
		
		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            // ->where('locations.location', '=', $fix_machine_to)
			            ->where('machines.machine_status', '=', 'TO_REPAIR')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		$machine_to_remove = DB::table('temp_fix_machines')
			            ->where('temp_fix_machines.id', '=', $id)
			            ->select('temp_fix_machines.id', 'temp_fix_machines.os')
			            ->get();

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_fix_machines WHERE ses = '".$ses."' AND id = '".$id."' "));
		
		// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_fix_machines WHERE ses = '".$ses."' order by id desc"));
		$data = DB::table('temp_fix_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_to_remove[0]->os.' removed from the list';
		return view('Mechanics.fix_machine_scan', compact('data','fix_machine_to','session','machines','msg'));
	}

	public function fix_machine_confirm($session) {
		//
		$session;
		$ses_data = DB::table('temp_fix_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);

		if (!isset($ses_data[0]->id)) {

			$msge = 'List was empty, first choose fix from (plant)?';
			return view('Mechanics.fix_machine', compact('msge', 'session'));
		}

		$fix_machine_to = strtoupper($ses_data[0]->fix_machine_to);
		// dd($fix_machine_to);

		if ($fix_machine_to == 'REPAIRING_SU') {

			$new_locations = DB::table('locations')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', 'SUBOTICA')
			            ->where('areas.area', '=', 'STOCK_S')
			            ->where('locations.active', '=', '1')
			            ->get();
		    $plant = 'SUBOTICA';
		    $area = 'STOCK_S';

		} elseif (($fix_machine_to == 'REPAIRING_KI')) {

			$new_locations = DB::table('locations')
						->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', 'KIKINDA')
			            ->where('areas.area', '=', 'STOCK_K')
			            ->where('locations.active', '=', '1')
			            ->get();
  			$plant = 'KIKINDA';
  			$area = 'STOCK_K';
		    
		} elseif (($fix_machine_to == 'REPAIRING_SE')) {

			$new_locations = DB::table('locations')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', 'SENTA')
			            ->where('areas.area', '=', 'STOCK_Z')
			            ->where('locations.active', '=', '1')
			            ->get();
			$plant = 'SENTA';
			$area = 'STOCK_Z';
		    
		} else {
			dd('Plant not recognized');
		}
		// dd($new_locations);
		return view('Mechanics.fix_machine_destination', compact('session','new_locations','plant','area'));
	}

	public function fix_machine_destination_post(Request $request) {
		//
		$input = $request->all(); 
		// dd($input);
		
		$session = $input['session'];
		$plant = $input['plant'];
		$area = $input['area'];

		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_fix_machines WHERE ses = '".$session."' order by id desc"));

		if (!empty($input['location1'])) {
			$location = strtoupper($input['location1']);

		} elseif (!empty($input['location2'])) {
			$location = strtoupper($input['location2']);
		} else {
			
			$new_locations = DB::table('locations')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->where('plants.plant', '=', $plant)
			            ->where('areas.area', '=', $area)
			            ->where('locations.active', '=', '1')
			            ->get();
			$msge = 'Please scan or select location';
			return view('Mechanics.fix_machine_destination', compact('session','new_locations','plant','area','msge'));
		}

		$new_location_id = DB::table('locations')
			            ->where('location', '=', $location)
			            ->where('locations.active', '=', '1')
			            ->get();
		// dd($new_location_id[0]->id);

		$new_location_id = $new_location_id[0]->id;

		for ($i=0; $i < count($ses_data); $i++) {
			
			$os = $ses_data[$i]->os;
			// dd($os);
			// try {

				$table = machines::where(['os' => $os])->update(['location' => $location, 'location_id' => $new_location_id, 'machine_status' => 'STOCK']);
				

			// } catch(\Illuminate\Database\QueryException $ex){
			// 	dd('problem to save');
			// }
		}
		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_fix_machines WHERE ses = '".$session."' "));
	
		$msgs = 'Successfully saved';
		return view('Mechanics.fix_machine', compact('msgs','session'));
	}

	public function fix_machine_cancel($session) {
		//
		$session;
		$ses_data = DB::table('temp_fix_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_fix_machines WHERE ses = '".$session."' "));

		$msgs = 'Successfully canceled';
		return view('Mechanics.fix_machine', compact('msgs','session'));
	}	

// DISABLE MACHINE (WRITEOFF & SELL)

	public function disable_machine() {

		$writeoff = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'WRITE_OFF' "));
		$writeoff = $writeoff[0]->c;
		$sold = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'SOLD' "));
		$sold = $sold[0]->c;

		$session = Session::getId();

		return view('Mechanics.disable_machine', compact('writeoff','sold','session'));	
	}

	public function writeoff_machine_scan(Request $request) {
		//
		// dd('Cao');

		$input = $request->all(); 
		// dd($input);
		
		if (isset($input['reason'])) {
			$reason = $input['reason'];
		} else {
			$reason = '';
		}
		
		// if (isset($input['session'])) {
		// 	$session = $input['session'];	
		// } else {
		// 	$session = NULL;
		// }
		$session = Session::getId();
		// dd($session);
		
		// dd($machine_temp);
		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		$data = DB::table('temp_writeoff_machines')->where('ses', '=', $session)->get();
		// dd($data);

		if (!empty($input['machine_temp1'])) {
			$machine_temp = strtoupper($input['machine_temp1']);

		} elseif (!empty($input['machine_temp2'])) {
			$machine_temp = strtoupper($input['machine_temp2']);
		} else {
			$msge = 'Please scan or select machine';
			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
			$data = DB::table('temp_writeoff_machines')->where('ses', '=', $session)->get();      
			return view('Mechanics.writeoff_machine_scan', compact('data','reason','session','machines','msge'));
		}
		// dd($machine_temp);

		if (isset($machine_temp)) {
			
			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8 OR strlen($machine_temp) == 9) {
				// dd($machine_temp);

				$exist = DB::table('machines')
	            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
	            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
	            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
	            ->where('machines.os', '=', $machine_temp)
	            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
	            ->get();
	            // dd($exist);
				
				if (isset($exist[0]->id)) {

						$data_temp = DB::connection('sqlsrv')->select(DB::raw("SELECT os, ses FROM temp_writeoff_machines WHERE os = '".$exist[0]->os."' AND ses = '".$session."' "));
						if (isset($data_temp[0]->os)) {
							
							$data = DB::table('temp_writeoff_machines')->where('ses', '=', $session)->get();
							$msge = 'Machine already scaned';
							return view('Mechanics.writeoff_machine_scan', compact('data','reason','session','machines','msge'));
						}
				} else {
					
					$data = DB::table('temp_writeoff_machines')->where('ses', '=', $session)->get();
					$msge = 'Mmachine does not exist in table';
					return view('Mechanics.writeoff_machine_scan', compact('data','reason','session','machines','msge'));
				}
			} else {
				
				$data = DB::table('temp_writeoff_machines')->where('ses', '=', $session)->get();
				$msge = 'Machine barcode must have 7 ,8 or 9 characters';
				return view('Mechanics.writeoff_machine_scan', compact('data','reason','session','machines','msge'));
			}
		} else {
			dd('Error, zovi IT.');
		}
		// dd($machine_temp);

		$table = temp_writeoff_machine::firstOrNew(['os' => strtoupper($machine_temp)]);
		$table->os_id =  $exist[0]->id;
		$table->os =  strtoupper($machine_temp);
		$table->brand =  $exist[0]->brand;
		$table->type =  $exist[0]->type;
		$table->code =  $exist[0]->code;
		$table->reason =  $reason;
		$table->ses =  Session::getId();
		$table->save();

		$data = DB::table('temp_writeoff_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_temp.' added to the list';
		return view('Mechanics.writeoff_machine_scan', compact('data','reason','session','machines','msg'));
	}

	public function writeoff_machine_remove($id, $session) {

		$ses = $session;
		// dd($ses);
		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_writeoff_machines WHERE ses = '".$ses."' order by id desc"));
		// dd($ses_data);
		$reason = $ses_data[0]->reason;

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

	    $machine_to_remove = DB::table('temp_writeoff_machines')
			            ->where('temp_writeoff_machines.id', '=', $id)
			            ->select('temp_writeoff_machines.id', 'temp_writeoff_machines.os')
			            ->get();
		// dd($machine_to_remove);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_writeoff_machines WHERE ses = '".$ses."' AND id = '".$id."' "));

		$data = DB::table('temp_writeoff_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_to_remove[0]->os.' removed from the list';
		return view('Mechanics.writeoff_machine_scan', compact('data','reason','session','machines','msg'));
	}

	public function writeoff_machine_confirm($session) {
		//
		$session;
		$ses_data = DB::table('temp_writeoff_machines')->where('ses', '=', $session)->get();

		if (!isset($ses_data[0]->id)) {

			$msge = 'List was empty, first choose writeoff to (plant)?';
			return view('Mechanics.writeoff_machine', compact('msge','session'));
		}

		$reason = $ses_data[0]->reason;
		
		for ($i=0; $i < count($ses_data); $i++) {
			
			$os = $ses_data[$i]->os;
			
			// try {

				$table = machines::where(['os' => $os])->update(['machine_status' => 'WRITE_OFF', 'location' => 'WRITE_OFF', 'location_id' => NULL, 'write_off_reason' => $reason]);
				
			// } catch(\Illuminate\Database\QueryException $ex){
			// 	dd('problem to save');
			// }
			DB::connection('sqlsrv2')->update(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_MachPool] SET [NotAct] = 1 WHERE [MachNum] = '".$os."' "));	
			DB::connection('sqlsrv2')->update(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] SET [NotAct] = 1 WHERE [MachNum] = '".$os."' "));	

		}
		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_writeoff_machines WHERE ses = '".$session."' "));


		
		$msgs = 'Successfully saved';
		// return view('Mechanics.writeoff_machine', compact('msgs','session'));
		$writeoff = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'WRITE_OFF' "));
		$writeoff = $writeoff[0]->c;
		$sold = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'SOLD' "));
		$sold = $sold[0]->c;

		$session = Session::getId();

		return view('Mechanics.disable_machine', compact('writeoff','sold','session','msgs'));
	}

	public function writeoff_machine_cancel($session) {
		//
		$session;
		$ses_data = DB::table('temp_writeoff_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_writeoff_machines WHERE ses = '".$session."' "));

		$msgs = 'Successfully canceled';
		// return view('Mechanics.writeoff_machine', compact('msgs','session'));

		$writeoff = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'WRITE_OFF' "));
		$writeoff = $writeoff[0]->c;
		$sold = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'SOLD' "));
		$sold = $sold[0]->c;

		$session = Session::getId();

		return view('Mechanics.disable_machine', compact('writeoff','sold','session','msgs'));
	}	

	public function sell_machine() {

		// dd('cao');
		return view('Mechanics.sell_machine');
	}

	public function sell_machine_to(Request $request) {
		//
		// dd('Cao');

		$input = $request->all(); 
		// dd($input);
		
		if ($input['buyer'] == '' OR $input['buyer'] == NULL) {
			$msge = 'Unesite kupca';
			return view('Mechanics.sell_machine', compact('msge'));	
		
		} else {
			$buyer = $input['buyer'];
		}
		// dd($buyer);

		$session = Session::getId();

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

		return view('Mechanics.sell_machine_scan', compact('buyer','session','machines'));
	}

	public function sell_machine_scan(Request $request) {
		//
		// dd('Cao');

		$input = $request->all(); 
		// dd($input);
		
		if (isset($input['buyer'])) {
			$buyer = $input['buyer'];
		} else {
			$buyer = '';
		}
		
		$session = Session::getId();
		// dd($session);
		
		// dd($machine_temp);
		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
		$data = DB::table('temp_sell_machines')->where('ses', '=', $session)->get();
		// dd($data);

		if (!empty($input['machine_temp1'])) {
			$machine_temp = strtoupper($input['machine_temp1']);

		} elseif (!empty($input['machine_temp2'])) {
			$machine_temp = strtoupper($input['machine_temp2']);
		} else {
			$msge = 'Please scan or select machine';
			$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();
			$data = DB::table('temp_sell_machines')->where('ses', '=', $session)->get();      
			return view('Mechanics.sell_machine_scan', compact('data','buyer','session','machines','msge'));
		}
		// dd($machine_temp);

		if (isset($machine_temp)) {
			
			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8 OR strlen($machine_temp) == 9) {
				// dd($machine_temp);

				$exist = DB::table('machines')
	            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
	            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
	            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
	            ->where('machines.os', '=', $machine_temp)
	            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
	            ->get();
	            // dd($exist);
				
				if (isset($exist[0]->id)) {

						$data_temp = DB::connection('sqlsrv')->select(DB::raw("SELECT os, ses FROM temp_sell_machines WHERE os = '".$exist[0]->os."' AND ses = '".$session."' "));
						if (isset($data_temp[0]->os)) {
							
							$data = DB::table('temp_sell_machines')->where('ses', '=', $session)->get();
							$msge = 'Machine already scaned';
							return view('Mechanics.sell_machine_scan', compact('data','buyer','session','machines','msge'));
						}
				} else {
					
					$data = DB::table('temp_sell_machines')->where('ses', '=', $session)->get();
					$msge = 'Mmachine does not exist in table';
					return view('Mechanics.sell_machine_scan', compact('data','buyer','session','machines','msge'));
				}
			} else {
				
				$data = DB::table('temp_sell_machines')->where('ses', '=', $session)->get();
				$msge = 'Machine barcode must have 7 ,8 or 9 characters';
				return view('Mechanics.sell_machine_scan', compact('data','buyer','session','machines','msge'));
			}
		} else {
			dd('Error, zovi IT.');
		}
		// dd($machine_temp);

		$table = temp_sell_machine::firstOrNew(['os' => strtoupper($machine_temp)]);
		$table->os_id =  $exist[0]->id;
		$table->os =  strtoupper($machine_temp);
		$table->brand =  $exist[0]->brand;
		$table->type =  $exist[0]->type;
		$table->code =  $exist[0]->code;
		$table->buyer =  $buyer;
		$table->ses =  Session::getId();
		$table->save();

		$data = DB::table('temp_sell_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_temp.' added to the list';
		return view('Mechanics.sell_machine_scan', compact('data','buyer','session','machines','msg'));
	}

	public function sell_machine_remove($id, $session) {

		$ses = $session;
		// dd($ses);
		$ses_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_sell_machines WHERE ses = '".$ses."' order by id desc"));
		// dd($ses_data);
		$buyer = $ses_data[0]->buyer;

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

	    $machine_to_remove = DB::table('temp_sell_machines')
			            ->where('temp_sell_machines.id', '=', $id)
			            ->select('temp_sell_machines.id', 'temp_sell_machines.os')
			            ->get();
		// dd($machine_to_remove);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_sell_machines WHERE ses = '".$ses."' AND id = '".$id."' "));

		$data = DB::table('temp_sell_machines')->where('ses', '=', $session)->get();
		$msg = 'Machine '.$machine_to_remove[0]->os.' removed from the list';
		return view('Mechanics.sell_machine_scan', compact('data','buyer','session','machines','msg'));
	}

	public function sell_machine_confirm($session) {
		//
		$session;
		$ses_data = DB::table('temp_sell_machines')->where('ses', '=', $session)->get();

		if (!isset($ses_data[0]->id)) {

			$msge = 'List was empty, first insert buyer?';
			return view('Mechanics.sell_machine', compact('msge','session'));
		}

		$buyer = $ses_data[0]->buyer;
		
		for ($i=0; $i < count($ses_data); $i++) {
			
			$os = $ses_data[$i]->os;
			// dd($os);
			// try {

				$table = machines::where(['os' => $os])->update(['machine_status' => 'SOLD', 'location' => 'SOLD', 'location_id' => NULL, 'buyer' => $buyer]);
				
			// } catch(\Illuminate\Database\QueryException $ex){
			// 	dd('problem to save');
			// }
			DB::connection('sqlsrv2')->update(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_MachPool] SET [NotAct] = 1 WHERE [MachNum] = '".$os."' "));	
			DB::connection('sqlsrv2')->update(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] SET [NotAct] = 1 WHERE [MachNum] = '".$os."' "));	
		}
		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_sell_machines WHERE ses = '".$session."' "));
		
		$msgs = 'Successfully saved';
		// return view('Mechanics.sell_machine', compact('msgs','session'));
		$writeoff = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'WRITE_OFF' "));
		$writeoff = $writeoff[0]->c;
		$sold = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'SOLD' "));
		$sold = $sold[0]->c;

		$session = Session::getId();

		return view('Mechanics.disable_machine', compact('writeoff','sold','session','msgs'));
	}

	public function sell_machine_cancel($session) {
		//
		$session;
		$ses_data = DB::table('temp_sell_machines')->where('ses', '=', $session)->get();
		// dd($ses_data);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM temp_sell_machines WHERE ses = '".$session."' "));

		$msgs = 'Successfully canceled';
		// return view('Mechanics.sell_machine', compact('msgs','session'));

		$writeoff = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'WRITE_OFF' "));
		$writeoff = $writeoff[0]->c;
		$sold = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(id) as c FROM machines WHERE machine_status = 'SOLD' "));
		$sold = $sold[0]->c;

		$session = Session::getId();

		return view('Mechanics.disable_machine', compact('writeoff','sold','session','msgs'));
	}

// SEARCH

	public function search_machine() {

		return view('Search.search_machine');
	}

	public function search_by_barcode(){

		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

		return view('Search.search_by_barcode', compact('machines'));	
	}

	public function search_by_barcode_scan(Request $request) {
		//
		$input = $request->all(); 
		// $session = Session::getId();
		// dd($input);
		$machines = DB::table('machines')
			            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
			            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
			            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
			            ->select('machines.id', 'machines.os', 'machines.location', 'machines.brand', 'machines.type', 'machines.code', 'plants.plant')
			            ->get();

		if (!empty($input['machine_temp1'])) {
			$machine_temp = strtoupper($input['machine_temp1']);

		} elseif (!empty($input['machine_temp2'])) {
			$machine_temp = strtoupper($input['machine_temp2']);			

		} else {
			
			$msge = 'Please scan or choose machine';
			return view('Search.search_by_barcode', compact('machines','msge'));	
		}

		if (isset($machine_temp)) {
			
			if (strlen($machine_temp) == 7 OR strlen($machine_temp) == 8 OR strlen($machine_temp) == 9) {
				// dd($machine_temp);

				$exist = DB::table('machines')
	            ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
	            ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
	            ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
	            ->where('machines.os', $machine_temp)
	            ->get();
	            // dd($exist);
				
				if (isset($exist[0]->os)) {

					//

				} else {
					
					$msge = 'Machine does not exist in table';
					return view('Search.search_by_barcode', compact('machines','msge'));
				}
			} else {
				
				$msge = 'Machine barcode must have 7 ,8 or 9 characters';
				return view('Search.search_by_barcode', compact('machines','msge'));
			}
		} else {
			dd('Error, zovi IT.');
		}
		// dd($machine_temp);
		$data = $exist;
		// dd($data);

		$comments = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM comments WHERE os = '".$machine_temp."' order by id desc"));


		return view('Search.search_by_barcode', compact('data','machines','comments'));
	}

	public function search_by_location(){

		// $locations = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		// 			   l.[id]
		// 		      ,l.[location]
		// 		      ,a.[area]
		// 		      ,p.[plant]
		// 		  FROM [mechanics].[dbo].[locations] as l
		// 		  JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
		// 		  JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
		// 		  ORDER BY plant desc "));

		$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT *
			FROM (
				SELECT 
					   l.[id]
				      ,l.[location]
				      ,a.[area]
				      ,p.[plant]
				      ,(SELECT COUNT(id) FROM machines WHERE location = l.location) as counttt
				  FROM [mechanics].[dbo].[locations] as l
				  LEFT JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
				  LEFT JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
				  ) as m
				  GROUP by m.id, m.location, m.area, m.plant, m.counttt
				  HAVING counttt > 0
				  ORDER BY m.plant desc ,m.id asc"));


		// dd($locations);
		return view('Search.search_by_location', compact('locations'));	
	}

	public function search_by_location_scan(Request $request) {
		//
		$input = $request->all(); 
		// $session = Session::getId();
		// dd($input);

		if ($input['loc1'] != '') {

			$test = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					   l.[id]
				      ,l.[location]
				      ,a.[area]
				      ,p.[plant]
				  FROM [mechanics].[dbo].[locations] as l
				  LEFT JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
				  LEFT JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
				  WHERE location = '".$input['loc1']."'
				  ORDER BY plant desc "));
			// dd($test);

			if (empty($test)) {
				// $locations = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				// 	   l.[id]
				//       ,l.[location]
				//       ,a.[area]
				//       ,p.[plant]
				//   FROM [mechanics].[dbo].[locations] as l
				//   JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
				//   JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
				// 	  ORDER BY plant desc "));
				$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT *
			FROM (
				SELECT 
					   l.[id]
				      ,l.[location]
				      ,a.[area]
				      ,p.[plant]
				      ,(SELECT COUNT(id) FROM machines WHERE location = l.location) as counttt
				  FROM [mechanics].[dbo].[locations] as l
				  LEFT JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
				  LEFT JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
				  ) as m
				  GROUP by m.id, m.location, m.area, m.plant, m.counttt
				  HAVING counttt > 0
				  ORDER BY m.plant desc ,m.id asc"));
				$msge = 'Location doesnt exist';
				return view('Search.search_by_location', compact('locations', 'msge'));	
			}

			$location = $input['loc1'];
		// } elseif ($input['loc2'] != '') {
		// 	$location = $input['loc2'];
		} elseif ($input['loc3'] != '') {
			$location = $input['loc3'];
		} else {
			
			$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT *
			FROM (
				SELECT 
					   l.[id]
				      ,l.[location]
				      ,a.[area]
				      ,p.[plant]
				      ,(SELECT COUNT(id) FROM machines WHERE location = l.location) as counttt
				  FROM [mechanics].[dbo].[locations] as l
				  LEFT JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
				  LEFT JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
				  ) as m
				  GROUP by m.id, m.location, m.area, m.plant, m.counttt
				  HAVING counttt > 0
				  ORDER BY m.plant desc ,m.id asc"));
			$msge = 'Location missing';
			return view('Search.search_by_location', compact('locations', 'msge'));	
		}

		// dd($location);

		$data = DB::table('machines')
        ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
        ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
        ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
        ->where('machines.location', '=', $location)
        ->get();
        // dd($exist);
	

		if (empty($data)) {
			
			$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT *
			FROM (
				SELECT 
					   l.[id]
				      ,l.[location]
				      ,a.[area]
				      ,p.[plant]
				      ,(SELECT COUNT(id) FROM machines WHERE location = l.location) as counttt
				  FROM [mechanics].[dbo].[locations] as l
				  LEFT JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
				  LEFT JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
				  ) as m
				  GROUP by m.id, m.location, m.area, m.plant, m.counttt
				  HAVING counttt > 0
				  ORDER BY m.plant desc ,m.id asc"));
			$msge = 'No machines on location '.$location;

			return view('Search.search_by_location', compact('locations', 'msge'));	
		}

		$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT *
			FROM (
				SELECT 
					   l.[id]
				      ,l.[location]
				      ,a.[area]
				      ,p.[plant]
				      ,(SELECT COUNT(id) FROM machines WHERE location = l.location) as counttt
				  FROM [mechanics].[dbo].[locations] as l
				  LEFT JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
				  LEFT JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
				  ) as m
				  GROUP by m.id, m.location, m.area, m.plant, m.counttt
				  HAVING counttt > 0
				  ORDER BY m.plant desc ,m.id asc"));

		return view('Search.search_by_location', compact('data','locations'));
	}

// ADVANCED SEARCH
	public function advanced_search() {

		$statuses = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT machine_status FROM machines order by machine_status asc"));
		$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT location FROM machines order by location asc"));
		
		$plants = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT plant FROM plants order by plant asc"));
		$areas = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT area  FROM areas order by area asc"));
		// dd($statuses);
		return view('Search.advanced_search', compact('statuses','locations','plants','areas'));	
	}

	public function advanced_search_post(Request $request) {
		//
		$input = $request->all(); 
		// $session = Session::getId();
		// dd($input);
		$os = $input['os'];
		$brand = $input['brand'];
		$code = $input['code'];
		$type = $input['type'];
		$location = $input['location'];
		$machine_status = $input['machine_status'];
		$gauge = $input['gauge'];
		$gadget = $input['gadget'];
		$el_dev_small_brand = $input['el_dev_small_brand'];
		$el_dev_big_brand = $input['el_dev_big_brand'];
		$puller = $input['puller'];
		$rollers = $input['rollers'];
		$plant = $input['plant'];
		$area = $input['area'];

		$query = '';
		
		if ($os != '') {
			$query = "os like '%".$os."%' ";
		}
		if ($brand != '') {
			if ($query == '') {
				$query = "brand like '%".$brand."%' ";
			} else {
				$query = $query ." and brand like '%".$brand."%' ";
			}
		}
		if ($code != '') {
			if ($query == '') {
				$query = "code like '%".$code."%' ";
			} else {
				$query = $query ." and code like '%".$code."%' ";
			}
		}
		if ($type != '') {
			if ($query == '') {
				$query = "type like '%".$type."%' ";
			} else {
				$query = $query ." and type like '%".$type."%' ";
			}
		}
		if ($plant != '') {
			if ($query == '') {
				$query = "plant like '%".$plant."%' ";
			} else {
				$query = $query ." and plant like '%".$plant."%' ";
			}
		}
		if ($area != '') {
			if ($query == '') {
				$query = "area like '%".$area."%' ";
			} else {
				$query = $query ." and area like '%".$area."%' ";
			}
		}
		if ($location != '') {
			if ($query == '') {
				$query = "m.location like '%".$location."%' ";
			} else {
				$query = $query ." and m.location like '%".$location."%' ";
			}
		}
		if ($machine_status != '') {
			if ($query == '') {
				$query = "machine_status like '%".$machine_status."%' ";
			} else {
				$query = $query ." and machine_status like '%".$machine_status."%' ";
			}
		}
		if ($gauge != '') {
			if ($query == '') {
				$query = "gauge like '%".$gauge."%' ";
			} else {
				$query = $query ." and gauge like '%".$gauge."%' ";
			}
		}
		if ($gadget != '') {
			if ($query == '') {
				$query = "gadget like '%".$gadget."%' ";
			} else {
				$query = $query ." and gadget like '%".$gadget."%' ";
			}
		}
		if ($el_dev_small_brand != '') {
			if ($query == '') {
				$query = "el_dev_small_brand like '%".$el_dev_small_brand."%' ";
			} else {
				$query = $query ." and el_dev_small_brand like '%".$el_dev_small_brand."%' ";
			}
		}
		if ($el_dev_big_brand != '') {
			if ($query == '') {
				$query = "el_dev_big_brand like '%".$el_dev_big_brand."%' ";
			} else {
				$query = $query ." and el_dev_big_brand like '%".$el_dev_big_brand."%' ";
			}
		}
		if ($puller != '') {
			if ($query == '') {
				$query = "puller like '%".$puller."%' ";
			} else {
				$query = $query ." and puller like '%".$puller."%' ";
			}
		}
		if ($rollers != '') {
			if ($query == '') {
				$query = "rollers like '%".$rollers."%' ";
			} else {
				$query = $query ." and rollers like '%".$rollers."%' ";
			}
		}

		if ($query == '') {
			$query = "os like '%' ";
		}

		$search = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				,(SELECT convert(varchar, c.[created_at], 105)+' ' +c.[comment] + ' \n '
					FROM [mechanics].[dbo].[comments] as c
					WHERE c.os =  m.os
					FOR XML PATH('') ) as comment
				,p.plant
				,a.area

			  	FROM [mechanics].[dbo].[machines] as m
			  	LEFT JOIN [mechanics].[dbo].locations as l ON l.id = m.location_id
			  	LEFT JOIN [mechanics].[dbo].areas as a ON a.id = l.area_id
			  	LEFT JOIN [mechanics].[dbo].plants as p ON p.id = a.plant_id
			WHERE
			".$query."
			ORDER BY os asc
			"));

		// dd($search);
		$data = $search;
		return view('Search.advanced_search_table', compact('data','query'));	
	}

// ADD COMMENT

	public function add_comment() {


		$machines = DB::connection('sqlsrv')->select(DB::raw("SELECT os, brand, type, code FROM machines order by id"));

		return view('Mechanics.add_comment', compact('machines'));
	}

	public function add_comment_scan(Request $request) {
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
				return view('Mechanics.add_comment', compact('machines','msge'));
			}

			$machine = strtoupper($input['machine1']);

		// } elseif (!empty($input['machine2'])) {

		// 	$machine = $input['machine2'];

		} elseif (!empty($input['machine3'])) {

			$machine = $input['machine3'];	
		} else {

			$machines = DB::connection('sqlsrv')->select(DB::raw("SELECT os, brand, type, code FROM machines order by id"));
				$msge = 'Please scan or choose machine';
				return view('Mechanics.add_comment', compact('machines','msge'));

		}

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
        	$remark_su = $data2[0]->remark_su;
        	$remark_ki = $data2[0]->remark_ki;
        }

		$comments = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM comments WHERE os = '".$machine."' order by id desc"));
		return view('Mechanics.add_comment_post', compact('machine','brand','type','code','location','area','plant','machine_status','remark_su','remark_ki','comments'));
	}

	public function delete_comment_post($id) {
		// dd($id);

		return view('Mechanics.delete_comment_post_confirm', compact('id'));
	}

	public function delete_comment_post_confirm($id) {
		// dd($id);

		DB::connection('sqlsrv')->delete(DB::raw("DELETE FROM [comments] WHERE id = '".$id."' "));
		
		return Redirect::to('add_comment');
	}

	public function add_comment_post(Request $request) {
		//
		$input = $request->all(); 
		// $session = Session::getId();
		// dd($input);

		$machine = $input['machine'];
		$user = $mechanic = Session::get('mechanic');

		$data2 = DB::table('machines')
        ->leftjoin('locations', 'locations.id', '=', 'machines.location_id')
        ->leftjoin('areas', 'areas.id', '=', 'locations.area_id')
        ->leftjoin('plants', 'plants.id', '=', 'areas.plant_id')
        ->where('machines.os', '=', $machine)
        ->get();

        if (isset($data2)) {
        	
        	$brand = $data2[0]->brand;
        	$type = $data2[0]->type;
        	$code = $data2[0]->code;
        	$location = $data2[0]->location;
        	$area = $data2[0]->area;
        	$plant = $data2[0]->plant;
        	$machine_status = $data2[0]->machine_status;
        	$remark_su = $data2[0]->remark_su;
        	$remark_ki = $data2[0]->remark_ki;
        }


		if (isset($input['comment']) AND (!empty($input['comment']))) {
			
			$os_id_check = DB::table('machines')
            	->where('machines.os', '=', $machine)
            	->get();
            $os_id = $os_id_check[0]->id;

			$table = new comment;
			$table->os = $machine;
			$table->os_id = $os_id;
			$table->comment = $input['comment'];
			$table->user = $user;
			$table->save();

		} else {

			$comments = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM comments WHERE os = '".$machine."' order by id desc"));
			$msge = 'Please add comment';
			return view('Mechanics.add_comment_post', compact('machine','brand','type','code','location','area','plant','machine_status','remark_su','remark_ki','comments','msge'));

		}

		$comments = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM comments WHERE os = '".$machine."' order by id desc"));
		$msgs = 'Successfully saved';
		return view('Mechanics.add_comment_post', compact('machine','brand','type','code','location','area','plant','machine_status','remark_su','remark_ki','comments','msgs'));
	}

// MACHINE EDIT (test)
	public function machine_edit($machine_id) {

		// dd($machine_id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM machines WHERE id = '".$machine_id."' "));
		// dd($machine[0]->code);
		$machineCode = $data[0]->code;
		// var_dump($machineCode);

		$gadget_validations = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM gadget_validations "));
		$el_dev_validations = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM el_dev_validations "));
		$gauge_validations =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM gauge_validations WHERE type = '".$machineCode."' "));
		// dd($gauge_validations);

		return view('Mechanics.machine_edit', compact('data','gadget_validations','el_dev_validations','gauge_validations'));
	}

	public function machine_edit_post(Request  $request) {
		//
		$input = $request->all(); 
		// $session = Session::getId();
		dd($input);
	}

// ADD GADGET
	public function add_info() {

		$machines = DB::connection('sqlsrv')->select(DB::raw("SELECT os, brand, type, code FROM machines order by id"));
		return view('Mechanics.add_info', compact('machines'));
	}

	public function add_info_scan(Request $request) {
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
				return view('Mechanics.add_info', compact('machines','msge'));
			}

			$machine = strtoupper($input['machine1']);

		} elseif (!empty($input['machine3'])) {

			$machine = $input['machine3'];	
		} else {

			$machines = DB::connection('sqlsrv')->select(DB::raw("SELECT os, brand, type, code FROM machines order by id"));
			$msge = 'Please scan or choose machine';
			return view('Mechanics.add_info', compact('machines','msge'));
		}

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
        	$remark_su = $data2[0]->remark_su;
        	$remark_ki = $data2[0]->remark_ki;
        	$gauge = (float)$data2[0]->gauge;
        	$gadget = $data2[0]->gadget;
        	$el_dev_small_brand = $data2[0]->el_dev_small_brand;
        	$el_dev_small_quantity = (int)$data2[0]->el_dev_small_quantity;
        	$el_dev_big_brand = $data2[0]->el_dev_big_brand;
        	$el_dev_big_quantity = (int)$data2[0]->el_dev_big_quantity;
        	$puller = $data2[0]->puller;
        	$rollers = $data2[0]->rollers;

        } else {
        	dd('Error');
        }

		$gauge_validation =   DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM gauge_validations WHERE type = '".$data2[0]->code."' order by id desc"));
		$gadget_validation = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM gadget_validations order by id desc"));
		$el_dev_validation = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM el_dev_validations order by id desc"));
		
		return view('Mechanics.add_info_post', compact('machine','brand','type','code',
			'location','area','plant','machine_status','remark_su','remark_ki',
			'gauge_validation','gadget_validation','el_dev_validation','gauge','gadget',
			'el_dev_small_brand','el_dev_small_quantity','el_dev_big_brand','el_dev_big_quantity',
			'puller','rollers'));
	}

	public function add_info_post(Request $request) {
		//
		$input = $request->all(); 
		// $session = Session::getId();
		// dd($input);

		$machine = $input['machine'];

		if (isset($input['gauge'])) {
			if ($input['gauge'] != '') {
				$gauge = round((float)$input['gauge'],1);
			} else {
				$gauge = 0;
			}
		} else {
			$gauge = 0;
		}
		// dd($gauge);
		
		if (isset($input['gadget'])) {
			if ($input['gadget'] != '') {
				$gadget = $input['gadget'];
			} else {
				$gadget = NULL;
			}
		} else {
			$gadget = NULL;
		}
		// dd($gadget);

		if (isset($input['el_dev_small_brand'])) {
			
			if ($input['el_dev_small_brand'] != '') {
				$el_dev_small_brand = $input['el_dev_small_brand'];	

				if (isset($input['el_dev_small_quantity'])) {

					if ((int)$input['el_dev_small_quantity'] != 0) {
						$el_dev_small_quantity = (int)$input['el_dev_small_quantity'];	
					} else {
						dd('error, missing el_dev_small_quantity');	
					}
					
				} else {
					dd('error, missing el_dev_small_quantity');
				}
			} else {
				$el_dev_small_brand = NULL;
				$el_dev_small_quantity = 0;
			}
		} else {
			$el_dev_small_brand = NULL;
			$el_dev_small_quantity = 0;
		}
		// dd($el_dev_small_brand);
		// dd($el_dev_small_quantity);

		if (isset($input['el_dev_big_brand'])) {

			if ($input['el_dev_big_brand'] != '') {
				$el_dev_big_brand = $input['el_dev_big_brand'];

				if (isset($input['el_dev_big_quantity'])) {

					if ((int)$input['el_dev_big_quantity']) {
						$el_dev_big_quantity = (int)$input['el_dev_big_quantity'];	
					} else {
						dd('error, missing el_dev_big_quantity');	
					}
					
				} else {
					dd('error, missing el_dev_big_quantity');
				}
			} else {
				$el_dev_big_brand = NULL;
				$el_dev_big_quantity = 0;
			}
		} else {
			$el_dev_big_brand = NULL;
			$el_dev_big_quantity = 0;
		}
		// dd($el_dev_big_brand);
		// dd($el_dev_big_quantity);

		if (isset($input['puller']))
			$puller = 1;
		else {
			$puller = 0;
		}
		// dd($puller);

		if (isset($input['rollers']))
			$rollers = 1;
		else {
			$rollers = 0;
		}
		// dd($rollers);

		$db = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM machines WHERE os = '".$machine."' "));
		$id = $db[0]->id;

		$table = machines::findOrFail($id);
		$table->gauge = $gauge;
		$table->gadget = $gadget;
		$table->el_dev_small_brand = $el_dev_small_brand;
		$table->el_dev_small_quantity = $el_dev_small_quantity;
		$table->el_dev_big_brand = $el_dev_big_brand;
		$table->el_dev_big_quantity = $el_dev_big_quantity;
		$table->puller = $puller;
		$table->rollers = $rollers;
		$table->save();

		// return Redirect::to('add_info');
		$msgs = 'Succesfuly saved';
		$machines = DB::connection('sqlsrv')->select(DB::raw("SELECT os, brand, type, code FROM machines order by id"));
		return view('Mechanics.add_info', compact('machines','msgs'));
	}

// CLASS
	public function class_table() {

		$data = DB::connection('sqlsrv2')->select(DB::raw("SELECT *
			,(SELECT COUNT([MachNum]) 
				FROM [CNF_MachPool] as p 
				JOIN [172.27.161.200].[mechanics].[dbo].[machines] as m ON p.MachNum = m.os
				WHERE 
					MaTyCod = IntKey
					AND m.machine_status NOT IN ('SOLD', 'WRITE_OFF')
			 ) as count_machine
			,(SELECT image FROM [172.27.161.200].[mechanics].[dbo].[class_tables] WHERE  IntKey = [CNF_MaTypes].IntKey) as image
  			FROM [BdkCLZG].[dbo].[CNF_MaTypes]
  			WHERE IntKey > 199"));
		// dd($data);
		return view('Mechanics.class_table', compact('data'));
	}

	public function add_class() {

		return view('Mechanics.add_class');
	}

	public function add_class_post(Request $request) {

		// $this->validate($request, ['pin'=>'required|min:4|max:5']);
		$forminput = $request->all(); 

		$brand = trim($forminput['brand']);
		$code = trim($forminput['code']);
		$class = trim($forminput['class']);

		$exist = DB::connection('sqlsrv2')->select(DB::raw("SELECT *
  			FROM [BdkCLZG].[dbo].[CNF_MaTypes]
  			WHERE Brand = '".$brand."' AND MaCod = '".$code."' AND MaTyp = '".$class."' "));

		if (isset($exist[0]->id)) {
			// exist
			$id = $exist[0]->id;

			dd('Combination of Brand, Code and Class already exist.');


		} else { 
			// add in both inteos tables
			$su_last_id = DB::connection('sqlsrv2')->select(DB::raw("SELECT TOP 1 [IntKey]
				FROM [BdkCLZG].[dbo].[CNF_MaTypes]
				ORDER BY [IntKey] desc"));

			$id = (int)$su_last_id[0]->IntKey + 1;

			$su = DB::connection('sqlsrv2')->update(DB::raw("INSERT INTO [BdkCLZG].[dbo].[CNF_MaTypes]
				([IntKey],[Brand],[MaTyp],[MaCod])
				VALUES
				('".$id."', '".$brand."', '".$class."','".$code."')  				
  				"));

			$ki = DB::connection('sqlsrv2')->update(DB::raw("INSERT INTO [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MaTypes]
				([IntKey],[Brand],[MaTyp],[MaCod])
				VALUES
				('".$id."', '".$brand."', '".$class."','".$code."')  				
  				"));



		}
		return Redirect::to('class_table');
	}

	public function edit_class($id) {
		
		// dd($id);
		$data = DB::connection('sqlsrv2')->select(DB::raw("SELECT *
  			FROM [BdkCLZG].[dbo].[CNF_MaTypes] 
  			WHERE IntKey = '".$id."' "));

		$brand = $data[0]->Brand;
		$code = $data[0]->MaCod;
		$class = $data[0]->MaTyp;



		return view('Mechanics.add_class', compact('id','brand','code','class'));
	}

	public function edit_class_post(Request $request) {

		// $this->validate($request, ['pin'=>'required|min:4|max:5']);
		$forminput = $request->all(); 

		$brand = trim($forminput['brand']);
		$code = trim($forminput['code']);
		$class = trim($forminput['class']);
		$id = $forminput['id'];

		$exist = DB::connection('sqlsrv2')->select(DB::raw("SELECT *
  			FROM [BdkCLZG].[dbo].[CNF_MaTypes]
  			WHERE Brand = '".$brand."' AND MaCod = '".$code."' AND MaTyp = '".$class."' "));

		if (isset($exist[0]->id)) {
			// exist
			$id = $exist[0]->id;
			dd('Combination of Brand, Code and Class already exist.');

		} else { 
			// add in both inteos tables
			
			$su = DB::connection('sqlsrv2')->update(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_MaTypes]
				SET [Brand] = '".$brand."', [MaTyp] = '".$class."', [MaCod] = '".$code."'
				WHERE [IntKey] = '".(int)$id."'
  				"));

			$ki = DB::connection('sqlsrv2')->update(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MaTypes]
				SET [Brand] = '".$brand."', [MaTyp] = '".$class."', [MaCod] = '".$code."'
				WHERE [IntKey] = '".(int)$id."'
  				"));

			$class_table = DB::connection('sqlsrv')->update(DB::raw("UPDATE class_tables
				SET brand = '".$brand."', class = '".$class."', code = '".$code."'
				WHERE [IntKey] = '".(int)$id."'
  				"));

		}

		return Redirect::to('class_table');
	}

	public function upload_image(Request $request){
		// dd($id);

		$forminput = $request->all(); 
		// dd($forminput);

		$id = trim($forminput['IntKey']);
		$brand = trim($forminput['brand']);
		$code = trim($forminput['code']);
		$class = trim($forminput['class']);

		return view('Mechanics.upload_image', compact('id','brand','code','class'));	
	}



}
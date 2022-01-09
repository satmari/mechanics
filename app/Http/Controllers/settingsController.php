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

class settingsController extends Controller {


	public function index()
	{
		//
	}

	public function plant()
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [mechanics].[dbo].plants"));
		return view('Plant.plant',compact('data'));
	}

	public function add_plant()
	{
		//
		return view('Plant.add_plant');
	}

	public function add_plant_post(Request $request)
	{
		//
		$this->validate($request, ['plant' => 'required']);
		$input = $request->all(); 

		$plant = strtoupper($input['plant']);
		// dd($plant);

		// try {
			$table = new plant;
			$table->plant = $plant;
			$table->save();
		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save, try again");
		// }		

		return Redirect::to('/plant');
	}

	public function edit_plant($id)
	{
		//
		$data = plant::findOrFail($id);
		return view('Plant.edit_plant', compact('data'));
	}

	public function edit_plant_post($id, Request $request)
	{
		$this->validate($request, ['plant' => 'required']);
		$input = $request->all(); 

		$plant = strtoupper($input['plant']);
		// dd($plant);		

		// try {
			$table = plant::findOrFail($id);
			$table->plant = $plant;
			$table->save();
		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save, try again");
		// }

		return Redirect::to('/plant');
	}

	public function remove_plant($id)
	{
		try {
			$table = plant::findOrFail($id);
			// $table->delete();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to delete in table";
		}
		return Redirect::to('/plant');
	}

	public function area()
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT a.id, a.area, p.id as plant_id, p.plant
		  FROM [mechanics].[dbo].areas as a
		  JOIN [mechanics].[dbo].plants as p ON p.id = a.plant_id
		  ORDER BY plant_id desc"));

		return view('Area.area',compact('data'));
	}

	public function add_area()
	{
		//
		$plants = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM plants"));
		return view('Area.add_area', compact('plants'));
	}

	public function add_area_post(Request $request)
	{
		//
		$this->validate($request, ['area' => 'required', 'plant_id' => 'required']);
		$input = $request->all();
		// dd($input);

		$area = strtoupper($input['area']);
		$plant_id = $input['plant_id'];
		
		// try {
			$table = new area;
			$table->area = $area;
			$table->plant_id = $plant_id;
			$table->save();
		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save, try again");
		// }		

		return Redirect::to('/area');
	}

	public function edit_area($id)
	{
		//
		$data = area::findOrFail($id);
		$plants = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM plants"));

		return view('Area.edit_area', compact('data', 'plants'));
	}

	public function edit_area_post($id, Request $request)
	{
		$this->validate($request, ['area' => 'required', 'plant_id' => 'required']);
		$input = $request->all(); 

		$area = strtoupper($input['area']);
		$plant_id = $input['plant_id'];
		
		// try {
			$table = area::findOrFail($id);
			$table->area = $area;
			$table->plant_id = $plant_id;
			$table->save();
		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save, try again");
		// }

		return Redirect::to('/area');
	}

	public function remove_area($id)
	{
		try {
			$table = area::findOrFail($id);
			// $table->delete();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to delete in table";
		}
		
		return Redirect::to('/area');
	}

	public function location()
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT l.id, l.location, a.area, p.plant
		  FROM [mechanics].[dbo].[locations] as l
		  LEFT JOIN [mechanics].[dbo].[areas] as a ON a.id = l.area_id
		  LEFT JOIN [mechanics].[dbo].[plants] as p ON p.id = a.plant_id
		  Order by a.plant_id asc, a.id asc, l.id asc
		"));

		return view('Location.location',compact('data'));
	}

	public function add_location()
	{
		//
		$areas = DB::connection('sqlsrv')->select(DB::raw("SELECT a.id, a.area, p.plant 
			FROM [mechanics].[dbo].areas as a
			JOIN [mechanics].[dbo].plants as p ON p.id = a.plant_id
			Order by a.plant_id asc
		"));
		// dd($areas);

		return view('Location.add_location', compact('areas'));
	}

	public function add_location_post(Request $request) {
		//
		$this->validate($request, ['location' => 'required', 'area_id' => 'required']);
		$input = $request->all(); 

		$location = strtoupper($input['location']);
		$area_id = $input['area_id'];
		
		// try {
			$table = new location;
			$table->location = $location;
			$table->area_id = $area_id;
			$table->save();
		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save, try again");
		// }		

		return Redirect::to('/location');
	}

	public function edit_location($id) {
		//
		$data = location::findOrFail($id);
		$areas = DB::connection('sqlsrv')->select(DB::raw("SELECT a.id, a.area, p.plant 
			FROM [mechanics].[dbo].areas as a
			JOIN [mechanics].[dbo].plants as p ON p.id = a.plant_id
			Order by a.plant_id asc
		"));

		return view('Location.edit_location', compact('data','areas'));
	}

	public function edit_location_post($id, Request $request) {
		$this->validate($request, ['location' => 'required', 'area_id' => 'required']);
		$input = $request->all(); 

		$location = strtoupper($input['location']);
		$area_id = $input['area_id'];
		
		// try {
			$table = location::findOrFail($id);
			$table->location = $location;
			$table->area_id = $area_id;
			$table->save();
		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save, try again");
		// }

		return Redirect::to('/location');
	}

	public function remove_location($id)
	{
		try {
			$table = location::findOrFail($id);
			// $table->delete();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to delete in table";
		}
		
		return Redirect::to('/location');
	}
	
}

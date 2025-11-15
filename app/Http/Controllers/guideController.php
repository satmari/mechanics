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
use App\guides;
use App\locations_g;
use App\supplier;
use App\guide_type;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;
use Carbon\Carbon;

class guideController extends Controller {

	public function guide_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT g.*,
			  gt.guide_type,
			  s.supplier,
			  l.location,

			  (SELECT SUM(stock.qty) as qty_g FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Subotica') as qty_su,
			  (SELECT SUM(stock.qty) as qty_k FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Kikinda') as qty_ki,
			  (SELECT SUM(stock.qty) as qty_z FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Senta') as qty_se
			  

			  FROM [guides] as g
			  LEFT JOIN guide_types as gt ON gt.id = g.guide_type_id
			  LEFT JOIN locations_gs as l ON l.id = g.location_g_id
			  LEFT JOIN suppliers as s ON s.id = g.suplier_id
			  ORDER BY g.guide_code asc

		"));

		return view('guide.table',compact('data'));
	}


    public function guide_add()
    {
        $guide_types = DB::table('guide_types')->lists('guide_type', 'id');
        $suppliers   = ['' => '-- Select Supplier --'] + DB::table('suppliers')->lists('supplier', 'id');
        $locations   = ['' => '-- Select Location --'] + DB::table('locations_gs')->lists('location', 'id');

        return view('guide.add', compact('guide_types', 'suppliers', 'locations'));
    }

    public function guide_add_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guide_code' => 'required|unique:guides,guide_code',
        ]);

        if ($validator->fails()) {
        	dd($validator->errors()->all());
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Insert and get ID
        $id = DB::table('guides')->insertGetId([
            'guide_code'        => $request->guide_code,
            'guide_description' => $request->guide_description,
            'guide_type_id'     => $request->guide_type_id,
            'machine_class'     => $request->machine_class,
            'fold'              => $request->fold,
            'entry_mm'          => $request->entry_mm,
            'exit_mm'           => $request->exit_mm,
            'tickness_mm'       => $request->tickness_mm,
            'elastic_mm'        => $request->elastic_mm,
            'style'             => $request->style,
            'operation'         => $request->operation,
            'notes'             => $request->notes,
            'location_g_id'     => $request->location_g_id,
            'suplier_id'        => $request->suplier_id,
            'calz_code'         => $request->calz_code,
            'status'            => $request->status,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        // Handle file uploads
        $folder = public_path('storage/GuidesFiles');
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $pictureName = null;
        $videoName = null;

        if ($request->hasFile('picture')) {
            $ext = $request->file('picture')->getClientOriginalExtension();
            $pictureName = $id . '.' . $ext;
            $request->file('picture')->move($folder, $pictureName);
        }

        if ($request->hasFile('video')) {
            $ext = $request->file('video')->getClientOriginalExtension();
            $videoName = $id . '.' . $ext;
            $request->file('video')->move($folder, $videoName);
        }

        DB::table('guides')->where('id', $id)->update([
            'picture' => $pictureName,
            'video'   => $videoName,
        ]);

        return Redirect::to('/guides')->with('success', 'New guide added successfully!');
    }

    public function guide_edit($id)
    {
        $data = DB::table('guides')->where('id', $id)->first();
        $guide_types = DB::table('guide_types')->lists('guide_type', 'id');
        $suppliers   = ['' => '-- Select Supplier --'] + DB::table('suppliers')->lists('supplier', 'id');
        $locations   = ['' => '-- Select Location --'] + DB::table('locations_gs')->lists('location', 'id');

        return view('guide.edit', compact('data', 'guide_types', 'suppliers', 'locations'));
    }

    public function guide_update_post(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'guide_code' => 'required|unique:guides,guide_code,' . $id,
        ]);

        if ($validator->fails()) {
        	dd($validator->errors()->all());
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $folder = public_path('storage/GuidesFiles');
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $guide = DB::table('guides')->where('id', $id)->first();
        $pictureName = $guide->picture;
        $videoName   = $guide->video;

        if ($request->hasFile('picture')) {
            $ext = $request->file('picture')->getClientOriginalExtension();
            $pictureName = $id . '.' . $ext;
            $request->file('picture')->move($folder, $pictureName);
        }

        if ($request->hasFile('video')) {
            $ext = $request->file('video')->getClientOriginalExtension();
            $videoName = $id . '.' . $ext;
            $request->file('video')->move($folder, $videoName);
        }

        DB::table('guides')->where('id', $id)->update([
            'guide_code'        => $request->guide_code,
            'guide_description' => $request->guide_description,
            'guide_type_id'     => $request->guide_type_id,
            'suplier_id'        => $request->suplier_id,
            'machine_class'     => $request->machine_class,
            'location_g_id'     => $request->location_g_id,
            'calz_code'         => $request->calz_code,
            'fold'              => $request->fold,
            'style'             => $request->style,
            'operation'         => $request->operation,
            'entry_mm'          => $request->entry_mm,
            'exit_mm'           => $request->exit_mm,
            'tickness_mm'       => $request->tickness_mm,
            'elastic_mm'        => $request->elastic_mm,
            'notes'             => $request->notes,
            'picture'           => $pictureName,
            'video'             => $videoName,
            'status'            => $request->status,
            'updated_at'        => Carbon::now(),
        ]);

        return Redirect::to('/guides')->with('success', 'Guide updated successfully!');
    }

    public function guide_history($id)
	{
	    // Get guide info
	    $guide = DB::table('guides')->where('id', $id)->first();

	    // Get stock history
	    $stocks = DB::table('guides_stocks')
	        ->where('guide_id', $id)
	        ->orderBy('created_at', 'desc')
	        ->get();

	    return view('guide.history', compact('guide', 'stocks'));
	}

// Transfer

	public function guide_transfer()
	{
	    $guides = ['' => '-- Select Guide --'] + DB::table('guides as g')
	        ->select('g.id', 'g.guide_code')
	        ->whereRaw("
	            (
	                (SELECT SUM(stock.qty) FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Subotica') > 0 OR
	                (SELECT SUM(stock.qty) FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Kikinda') > 0 OR
	                (SELECT SUM(stock.qty) FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Senta') > 0
	            )
	        ")
	        ->lists('guide_code', 'id');

	    return view('guide.transfer_1', compact('guides'));
	}

	public function guide_transfer_1(Request $request)
	{
	    $validator = Validator::make($request->all(), [
	        'guide_id' => 'required',
	    ]);

	    $guides = ['' => '-- Select Guide --'] + DB::table('guides as g')
	        ->select('g.id', 'g.guide_code')
	        ->whereRaw("
	            (
	                (SELECT SUM(stock.qty) FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Subotica') > 0 OR
	                (SELECT SUM(stock.qty) FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Kikinda') > 0 OR
	                (SELECT SUM(stock.qty) FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Senta') > 0
	            )
	        ")
	        ->lists('guide_code', 'id');

	    if ($validator->fails()) {
	        $msge = implode(', ', $validator->errors()->all());
	        return view('guide.transfer_1', compact('guides','msge'));
	    }

	    $guide_id = $request->input('guide_id');

	    // Load guide details and related stocks where qty > 0
	    $stocks = DB::table('guides_stocks')
	        ->select('plant', DB::raw('SUM(qty) as qty'))
	        ->where('guide_id', $guide_id)
	        ->groupBy('plant')
	        ->havingRaw('SUM(qty) > 0')
	        ->orderBy('plant')
	        ->get();

	    $guide = DB::table('guides')->where('id', $guide_id)->first();

	    if (!$guide) {
	        $msge = 'Guide not found';
	        return view('guide.transfer_1', compact('guides','msge'));
	    }

	    return view('guide.transfer_post', compact('guide', 'stocks'));
	}

	public function guides_transfer_post(Request $request)
	{
	    $guide_id = $request->input('guide_id');
	    $guide = DB::table('guides')->where('id', $guide_id)->first();
	    $source_plant = $request->input('source_plant');
	    $qty = (int) $request->input('transfer_qty');
	    $target_plant = $request->input('target_plant');

	    $stocks = DB::table('guides_stocks')
	        ->select('plant', DB::raw('SUM(qty) as qty'))
	        ->where('guide_id', $guide_id)
	        ->groupBy('plant')
	        ->havingRaw('SUM(qty) > 0')
	        ->orderBy('plant')
	        ->get();

	    // Validate input
	    $validator = Validator::make($request->all(), [
	        'guide_id' => 'required|exists:guides,id',
	        'source_plant' => 'required',
	        'transfer_qty' => 'required|integer|min:1',
	        'target_plant' => 'required|different:source_plant',
	    ]);

	    if ($validator->fails()) {
	        $msge = implode(', ', $validator->errors()->all());
	        return view('guide.transfer_post', compact('guide','stocks','msge'));
	    }

	    // Check available qty in source
	    $source_plant_stock_check = DB::table('guides_stocks')
	        ->select('plant', DB::raw('SUM(qty) as total_qty'))
	        ->where('guide_id', $guide_id)
	        ->where('plant', $source_plant)
	        ->groupBy('plant')
	        ->first();

	    $total_qty = $source_plant_stock_check ? $source_plant_stock_check->total_qty : 0;

	    if ($total_qty < $qty) {
	        $msge = 'Insufficient stock in source plant.';
	        return view('guide.transfer_post', compact('guide','stocks','msge'));
	    }

	    // Perform transfer
	    try {
	        DB::beginTransaction();

	        $comment = "Transfer from $source_plant to $target_plant";

	        DB::table('guides_stocks')->insert([
	            'guide_id' => $guide_id,
	            'plant' => $source_plant,
	            'qty' => -$qty,
	            'comment' => $comment,
	            'type' => 'transfer',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now(),
	        ]);

	        DB::table('guides_stocks')->insert([
	            'guide_id' => $guide_id,
	            'plant' => $target_plant,
	            'qty' => $qty,
	            'comment' => $comment,
	            'type' => 'transfer',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now(),
	        ]);

	        DB::commit();

	        $guides = ['' => '-- Select Guide --'] + DB::table('guides as g')
		        ->select('g.id', 'g.guide_code')
		        ->whereRaw("
		            (
		                (SELECT SUM(stock.qty) FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Subotica') > 0 OR
		                (SELECT SUM(stock.qty) FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Kikinda') > 0 OR
		                (SELECT SUM(stock.qty) FROM guides_stocks as stock WHERE stock.guide_id = g.id AND stock.plant = 'Senta') > 0
		            )
		        ")
		        ->lists('guide_code', 'id');
	        $msgs = 'Transfer completed successfully.';
	        return view('guide.transfer_1', compact('guides','msgs'));

	    } catch (\Exception $e) {
	        DB::rollBack();
	        $msge = 'Transfer failed: ' . $e->getMessage();
	        return view('guide.transfer_post', compact('guide','stocks','msge'));
	    }
	}


// Stock add or remove

	public function guide_add_remove()
	{
	    $guides = ['' => '-- Select Guide --'] + DB::table('guides as g')
	        ->select('g.id', 'g.guide_code')
	        ->where('g.status', 'ACTIVE')
	        ->orderBy('g.guide_code', 'asc')
	        ->lists('g.guide_code', 'g.id');

	    return view('guide.add_remove_1', compact('guides'));
	}

	public function guide_add_remove_1(Request $request)
	{
	    $validator = Validator::make($request->all(), [
	        'guide_id' => 'required',
	    ]);

	    $guides = ['' => '-- Select Guide --'] + DB::table('guides as g')
	        ->select('g.id', 'g.guide_code')
	        ->where('g.status', 'ACTIVE')
	        ->orderBy('g.guide_code', 'asc')
	        ->lists('g.guide_code', 'g.id');

	    if ($validator->fails()) {
	        $msge = implode(', ', $validator->errors()->all());
	        return view('guide.add_remove_1', compact('guides','msge'));
	    }

	    $guide_id = $request->input('guide_id');
	    $guide = DB::table('guides')->where('id', $guide_id)->first();

	    if (!$guide) {
	        $msge = 'Guide not found';

	        return view('guide.add_remove_1', compact('guides','msge'));
	    }

	    return view('guide.add_remove_post', compact('guide'));
	}

	public function guide_add_remove_post(Request $request)
	{
	    $guide_id = $request->input('guide_id');
	    $guide = DB::table('guides')->where('id', $guide_id)->first();
	    $target_plant = $request->input('target_plant');
	    $qty = (int) $request->input('qty');
	    $comment = $request->input('comment');

	    // Validate input
	    $validator = Validator::make($request->all(), [
	        'guide_id' => 'required|exists:guides,id',
	        'target_plant' => 'required',
	        'qty' => 'required|integer'
	    ]);

	    if ($validator->fails()) {
	        $msge = implode(', ', $validator->errors()->all());
	        return view('guide.add_remove_post', compact('guide','msge'));
	    }

	    // Check current stock for this plant
	    $source_plant_stock_check = DB::table('guides_stocks')
	        ->select('plant', DB::raw('SUM(qty) as total_qty'))
	        ->where('guide_id', $guide_id)
	        ->where('plant', $target_plant)
	        ->groupBy('plant')
	        ->first();

	    $total_qty = $source_plant_stock_check ? $source_plant_stock_check->total_qty : 0;

	    // Prevent negative stock
	    if (($total_qty + $qty) < 0) {
	        $msge = 'Insufficient stock in source plant to reduce';
	        return view('guide.add_remove_post', compact('guide','msge'));
	    }

	    // Insert adjustment
	    try {
	        DB::table('guides_stocks')->insert([
	            'guide_id' => $guide_id,
	            'plant' => $target_plant,
	            'qty' => $qty,
	            'comment' => $comment,
	            'type' => 'add_or_reduce',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now(),
	        ]);

	        $msgs = 'Stock adjustments completed successfully.';
	        $guides = ['' => '-- Select Guide --'] + DB::table('guides as g')
		        ->select('g.id', 'g.guide_code')
		        ->where('g.status', 'ACTIVE')
		        ->orderBy('g.guide_code', 'asc')
		        ->lists('g.guide_code', 'g.id');

	        return view('guide.add_remove_1', compact('guides', 'msgs'));

	    } catch (\Exception $e) {
	        $msge = 'Stock adjustment failed: ' . $e->getMessage();
	        return view('guide.add_remove_post', compact('guide', 'msge'));
	    }
	}

// Guides Location

	public function guide_location_table() 
	{

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [locations_gs]"));

		return view('guide.guide_location_table',compact('data'));
	}

	public function guide_location_table_add() 
	{

		return view('guide.guide_location_table_add');
	}

	public function guide_location_table_post (Request $request) 
	{

		// Get form inputs
	    $forminput = $request->all();

	    $location = trim($forminput['location']);

	    $table = new locations_g;
		$table->location = $location;
		$table->save();


		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [locations_gs]"));
		$msgs = 'Guide location Succesfuly created';
		return view('guide.guide_location_table',compact('data','msgs'));
	}

	public function guide_location_edit($id) 
	{
		
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
  			FROM [locations_gs]
  			WHERE [id] = '".$id."' "));

		
		$location = $data[0]->location;
		
		return view('guide.guide_location_edit', compact('id','location'));
	}

	public function guide_location_edit_post(Request $request)	
	{

	    // Optional validation
	    $this->validate($request, [
	        'id' => 'required|integer',
	        'location' => 'string|max:1000'
	    ]);

	    // Get form inputs
	    $forminput = $request->all();

	    $id = $forminput['id'];
	    $location = trim($forminput['location']);

	    	    // Update the record using raw SQL
	    DB::connection('sqlsrv')->update(DB::raw("
	        UPDATE [locations_gs]
	        SET location = :location
	        WHERE id = :id
	    "), [
	        'location' => $location,
	        'id' => $id
	    ]);

	    $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [locations_gs]"));
		$msgs = 'Guide location Succesfuly updated';
		return view('guide.guide_location_table',compact('data','msgs'));
	}

// Guide Type

	public function guide_type_table() 
	{

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [guide_types]"));

		return view('guide.guide_type_table',compact('data'));
	}

	public function guide_type_table_add() 
	{

		return view('guide.guide_type_table_add');
	}

	public function guide_type_table_post(Request $request) 
	{

		// Get form inputs
	    $forminput = $request->all();

	    $guide_type = trim($forminput['guide_type']);
	    $description = trim($forminput['description']);

	    $table = new guide_type;
		$table->guide_type = $guide_type;
		$table->description = $description;
		$table->save();


		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [guide_types]"));
		$msgs = 'Guide type Succesfuly created';
		return view('guide.guide_type_table',compact('data','msgs'));
	}

	public function guide_type_edit($id)
	{
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
  			FROM [guide_types]
  			WHERE [id] = '".$id."' "));

		
		$guide_type = $data[0]->guide_type;
		$description = $data[0]->description;
		
		return view('guide.guide_type_edit', compact('id','guide_type','description'));
	}

	public function guide_type_edit_post(Request $request)	
	{

	    // Optional validation
	    $this->validate($request, [
	        'id' => 'required|integer',
	        'guide_type' => 'string|max:1000'
	        
	    ]);

	    // Get form inputs
	    $forminput = $request->all();

	    $id = $forminput['id'];
	    $guide_type = trim($forminput['guide_type']);
	    $description = trim($forminput['description']);

	    	    // Update the record using raw SQL
	    DB::connection('sqlsrv')->update(DB::raw("
	        UPDATE [guide_types]
	        SET guide_type = :guide_type,
	        	description = :description
	        WHERE id = :id
	    "), [
	        'guide_type' => $guide_type,
	        'description' => $description,
	        'id' => $id
	    ]);

	    $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [guide_types]"));
		$msgs = 'Guide type Succesfuly updated';
		return view('guide.guide_type_table',compact('data','msgs'));
	}

// Supplier

	public function supplier_table() 
	{

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [suppliers]"));

		return view('guide.supplier_table',compact('data'));
	}

	public function supplier_table_add() 
	{

		return view('guide.supplier_add');
	}

	public function supplier_table_post (Request $request) 
	{

		// Get form inputs
	    $forminput = $request->all();

	    $supplier = trim($forminput['supplier']);
	    $location = trim($forminput['location']);
	    $contact = trim($forminput['contact']);

	    $table = new supplier;
		$table->supplier = $supplier;
		$table->location = $location;
		$table->contact = $contact;
		$table->save();


		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [suppliers]"));
		$msgs = 'Supplier Succesfuly created';
		return view('guide.supplier_table',compact('data','msgs'));
	}

	public function supplier_edit($id)
	{
		
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
  			FROM [suppliers]
  			WHERE [id] = '".$id."' "));

		$supplier = $data[0]->supplier;
		$location = $data[0]->location;
		$contact = $data[0]->contact;
		

		return view('guide.supplier_edit', compact('id','supplier','location','contact'));
	}

	public function supplier_edit_post(Request $request)	
	{

	    // Optional validation
	    $this->validate($request, [
	        'id' => 'required|integer',
	        'supplier' => 'required|string|max:255',
	        'location' => 'string|max:1000',
	        'contact' => 'string|max:1000'
	    ]);

	    // Get form inputs
	    $forminput = $request->all();

	    $id = $forminput['id'];
	    $supplier = trim($forminput['supplier']);
	    $location = trim($forminput['location']);
	    $contact = trim($forminput['contact']);

	    // Update the record using raw SQL
	    DB::connection('sqlsrv')->update(DB::raw("
	        UPDATE [suppliers]
	        SET supplier = :supplier,
	            location = :location,
	            contact = :contact
	        WHERE id = :id
	    "), [
	        'supplier' => $supplier,
	        'location' => $location,
	        'contact' => $contact,
	        'id' => $id
	    ]);

	    return Redirect::to('supplier_table');
	}



	
}

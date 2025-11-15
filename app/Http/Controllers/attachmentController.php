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
use App\attachments;
use App\locations_a;
use App\supplier;
use App\attachment_type;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;
use Carbon\Carbon;

class attachmentController extends Controller {

	public function attachment_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT g.*,
			  gt.attachment_type,
			  s.supplier,
			  l.location,

			  (SELECT SUM(stock.qty) as qty_g FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Subotica') as qty_su,
			  (SELECT SUM(stock.qty) as qty_k FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Kikinda') as qty_ki,
			  (SELECT SUM(stock.qty) as qty_z FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Senta') as qty_se
			  

			  FROM [attachments] as g
			  LEFT JOIN attachment_types as gt ON gt.id = g.attachment_type_id
			  LEFT JOIN locations_as as l ON l.id = g.location_a_id
			  LEFT JOIN suppliers as s ON s.id = g.suplier_id
			  ORDER BY g.attachment_code asc

		"));

		return view('attachment.table',compact('data'));
	}


    public function attachment_add()
    {
        $attachment_types = ['' => '-- Select Attachment --'] + DB::table('attachment_types')->lists('attachment_type', 'id');
        $suppliers   = ['' => '-- Select Supplier --'] + DB::table('suppliers')->lists('supplier', 'id');
        $locations   = ['' => '-- Select Location --'] + DB::table('locations_as')->lists('location', 'id');

        return view('attachment.add', compact('attachment_types', 'suppliers', 'locations'));
    }

    public function attachment_add_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attachment_code' => 'required|unique:attachments,attachment_code',
        ]);

        if ($validator->fails()) {
        	dd($validator->errors()->all());
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Insert and get ID
        $id = DB::table('attachments')->insertGetId([
            'attachment_code'        => $request->attachment_code,
            'attachment_description' => $request->attachment_description,
            'attachment_type_id'     => $request->attachment_type_id,
            'machine_class'     => $request->machine_class,
            
            'style'             => $request->style,
            'operation'         => $request->operation,
            'notes'             => $request->notes,
            'location_a_id'     => $request->location_a_id,
            'suplier_id'        => $request->suplier_id,
            'calz_code'         => $request->calz_code,
            'status'            => $request->status,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        // Handle file uploads
        $folder = public_path('storage/attachmentsFiles');
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

        DB::table('attachments')->where('id', $id)->update([
            'picture' => $pictureName,
            'video'   => $videoName,
        ]);

        return Redirect::to('/attachments')->with('success', 'New attachment added successfully!');
    }

    public function attachment_edit($id)
    {
        $data = DB::table('attachments')->where('id', $id)->first();
        $attachment_types = ['' => '-- Select Type --'] +DB::table('attachment_types')->lists('attachment_type', 'id');
        $suppliers   = ['' => '-- Select Supplier --'] + DB::table('suppliers')->lists('supplier', 'id');
        $locations   = ['' => '-- Select Location --'] + DB::table('locations_as')->lists('location', 'id');

        return view('attachment.edit', compact('data', 'attachment_types', 'suppliers', 'locations'));
    }

    public function attachment_update_post(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'attachment_code' => 'required|unique:attachments,attachment_code,' . $id,
        ]);

        if ($validator->fails()) {
        	dd($validator->errors()->all());
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $folder = public_path('storage/attachmentsFiles');
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $attachment = DB::table('attachments')->where('id', $id)->first();
        $pictureName = $attachment->picture;
        $videoName   = $attachment->video;

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

        DB::table('attachments')->where('id', $id)->update([
            'attachment_code'        => $request->attachment_code,
            'attachment_description' => $request->attachment_description,
            'attachment_type_id'     => $request->attachment_type_id,
            'suplier_id'        => $request->suplier_id,
            'machine_class'     => $request->machine_class,
            'location_a_id'     => $request->location_a_id,
            
            'style'             => $request->style,
            'operation'         => $request->operation,
            'notes'             => $request->notes,
            'calz_code'         => $request->calz_code,
            'picture'           => $pictureName,
            'video'             => $videoName,
            'status'            => $request->status,
            'updated_at'        => Carbon::now(),
        ]);

        return Redirect::to('/attachments')->with('success', 'attachment updated successfully!');
    }

    public function attachment_history($id)
	{
	    // Get attachment info
	    $attachment = DB::table('attachments')->where('id', $id)->first();

	    // Get stock history
	    $stocks = DB::table('attachments_stocks')
	        ->where('attachment_id', $id)
	        ->orderBy('created_at', 'desc')
	        ->get();

	    return view('attachment.history', compact('attachment', 'stocks'));
	}

// Transfer

	public function attachment_transfer()
	{
	    $attachments = ['' => '-- Select attachment --'] + DB::table('attachments as g')
	        ->select('g.id', 'g.attachment_code')
	        ->whereRaw("
	            (
	                (SELECT SUM(stock.qty) FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Subotica') > 0 OR
	                (SELECT SUM(stock.qty) FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Kikinda') > 0 OR
	                (SELECT SUM(stock.qty) FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Senta') > 0
	            )
	        ")
	        ->lists('attachment_code', 'id');

	    return view('attachment.transfer_1', compact('attachments'));
	}

	public function attachment_transfer_1(Request $request)
	{
	    $validator = Validator::make($request->all(), [
	        'attachment_id' => 'required',
	    ]);

	    $attachments = ['' => '-- Select attachment --'] + DB::table('attachments as g')
	        ->select('g.id', 'g.attachment_code')
	        ->whereRaw("
	            (
	                (SELECT SUM(stock.qty) FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Subotica') > 0 OR
	                (SELECT SUM(stock.qty) FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Kikinda') > 0 OR
	                (SELECT SUM(stock.qty) FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Senta') > 0
	            )
	        ")
	        ->lists('attachment_code', 'id');

	    if ($validator->fails()) {
	        $msge = implode(', ', $validator->errors()->all());
	        return view('attachment.transfer_1', compact('attachments','msge'));
	    }

	    $attachment_id = $request->input('attachment_id');

	    // Load attachment details and related stocks where qty > 0
	    $stocks = DB::table('attachments_stocks')
	        ->select('plant', DB::raw('SUM(qty) as qty'))
	        ->where('attachment_id', $attachment_id)
	        ->groupBy('plant')
	        ->havingRaw('SUM(qty) > 0')
	        ->orderBy('plant')
	        ->get();

	    $attachment = DB::table('attachments')->where('id', $attachment_id)->first();

	    if (!$attachment) {
	        $msge = 'attachment not found';
	        return view('attachment.transfer_1', compact('attachments','msge'));
	    }

	    return view('attachment.transfer_post', compact('attachment', 'stocks'));
	}

	public function attachments_transfer_post(Request $request)
	{
	    $attachment_id = $request->input('attachment_id');
	    $attachment = DB::table('attachments')->where('id', $attachment_id)->first();
	    $source_plant = $request->input('source_plant');
	    $qty = (int) $request->input('transfer_qty');
	    $target_plant = $request->input('target_plant');

	    $stocks = DB::table('attachments_stocks')
	        ->select('plant', DB::raw('SUM(qty) as qty'))
	        ->where('attachment_id', $attachment_id)
	        ->groupBy('plant')
	        ->havingRaw('SUM(qty) > 0')
	        ->orderBy('plant')
	        ->get();

	    // Validate input
	    $validator = Validator::make($request->all(), [
	        'attachment_id' => 'required|exists:attachments,id',
	        'source_plant' => 'required',
	        'transfer_qty' => 'required|integer|min:1',
	        'target_plant' => 'required|different:source_plant',
	    ]);

	    if ($validator->fails()) {
	        $msge = implode(', ', $validator->errors()->all());
	        return view('attachment.transfer_post', compact('attachment','stocks','msge'));
	    }

	    // Check available qty in source
	    $source_plant_stock_check = DB::table('attachments_stocks')
	        ->select('plant', DB::raw('SUM(qty) as total_qty'))
	        ->where('attachment_id', $attachment_id)
	        ->where('plant', $source_plant)
	        ->groupBy('plant')
	        ->first();

	    $total_qty = $source_plant_stock_check ? $source_plant_stock_check->total_qty : 0;

	    if ($total_qty < $qty) {
	        $msge = 'Insufficient stock in source plant.';
	        return view('attachment.transfer_post', compact('attachment','stocks','msge'));
	    }

	    // Perform transfer
	    try {
	        DB::beginTransaction();

	        $comment = "Transfer from $source_plant to $target_plant";

	        DB::table('attachments_stocks')->insert([
	            'attachment_id' => $attachment_id,
	            'plant' => $source_plant,
	            'qty' => -$qty,
	            'comment' => $comment,
	            'type' => 'transfer',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now(),
	        ]);

	        DB::table('attachments_stocks')->insert([
	            'attachment_id' => $attachment_id,
	            'plant' => $target_plant,
	            'qty' => $qty,
	            'comment' => $comment,
	            'type' => 'transfer',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now(),
	        ]);

	        DB::commit();

	        $attachments = ['' => '-- Select attachment --'] + DB::table('attachments as g')
		        ->select('g.id', 'g.attachment_code')
		        ->whereRaw("
		            (
		                (SELECT SUM(stock.qty) FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Subotica') > 0 OR
		                (SELECT SUM(stock.qty) FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Kikinda') > 0 OR
		                (SELECT SUM(stock.qty) FROM attachments_stocks as stock WHERE stock.attachment_id = g.id AND stock.plant = 'Senta') > 0
		            )
		        ")
		        ->lists('attachment_code', 'id');
	        $msgs = 'Transfer completed successfully.';
	        return view('attachment.transfer_1', compact('attachments','msgs'));

	    } catch (\Exception $e) {
	        DB::rollBack();
	        $msge = 'Transfer failed: ' . $e->getMessage();
	        return view('attachment.transfer_post', compact('attachment','stocks','msge'));
	    }
	}


// Stock add or remove

	public function attachment_add_remove()
	{
	    $attachments = ['' => '-- Select attachment --'] + DB::table('attachments as g')
	        ->select('g.id', 'g.attachment_code')
	        ->where('g.status', 'ACTIVE')
	        ->orderBy('g.attachment_code', 'asc')
	        ->lists('g.attachment_code', 'g.id');

	    return view('attachment.add_remove_1', compact('attachments'));
	}

	public function attachment_add_remove_1(Request $request)
	{
	    $validator = Validator::make($request->all(), [
	        'attachment_id' => 'required',
	    ]);

	    $attachments = ['' => '-- Select attachment --'] + DB::table('attachments as g')
	        ->select('g.id', 'g.attachment_code')
	        ->where('g.status', 'ACTIVE')
	        ->orderBy('g.attachment_code', 'asc')
	        ->lists('g.attachment_code', 'g.id');

	    if ($validator->fails()) {
	        $msge = implode(', ', $validator->errors()->all());
	        return view('attachment.add_remove_1', compact('attachments','msge'));
	    }

	    $attachment_id = $request->input('attachment_id');
	    $attachment = DB::table('attachments')->where('id', $attachment_id)->first();

	    if (!$attachment) {
	        $msge = 'attachment not found';

	        return view('attachment.add_remove_1', compact('attachments','msge'));
	    }

	    return view('attachment.add_remove_post', compact('attachment'));
	}

	public function attachment_add_remove_post(Request $request)
	{
	    $attachment_id = $request->input('attachment_id');
	    $attachment = DB::table('attachments')->where('id', $attachment_id)->first();
	    $target_plant = $request->input('target_plant');
	    $qty = (int) $request->input('qty');
	    $comment = $request->input('comment');

	    // Validate input
	    $validator = Validator::make($request->all(), [
	        'attachment_id' => 'required|exists:attachments,id',
	        'target_plant' => 'required',
	        'qty' => 'required|integer'
	    ]);

	    if ($validator->fails()) {
	        $msge = implode(', ', $validator->errors()->all());
	        return view('attachment.add_remove_post', compact('attachment','msge'));
	    }

	    // Check current stock for this plant
	    $source_plant_stock_check = DB::table('attachments_stocks')
	        ->select('plant', DB::raw('SUM(qty) as total_qty'))
	        ->where('attachment_id', $attachment_id)
	        ->where('plant', $target_plant)
	        ->groupBy('plant')
	        ->first();

	    $total_qty = $source_plant_stock_check ? $source_plant_stock_check->total_qty : 0;

	    // Prevent negative stock
	    if (($total_qty + $qty) < 0) {
	        $msge = 'Insufficient stock in source plant to reduce';
	        return view('attachment.add_remove_post', compact('attachment','msge'));
	    }

	    // Insert adjustment
	    try {
	        DB::table('attachments_stocks')->insert([
	            'attachment_id' => $attachment_id,
	            'plant' => $target_plant,
	            'qty' => $qty,
	            'comment' => $comment,
	            'type' => 'add_or_reduce',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now(),
	        ]);

	        $msgs = 'Stock adjustments completed successfully.';
	        $attachments = ['' => '-- Select attachment --'] + DB::table('attachments as g')
		        ->select('g.id', 'g.attachment_code')
		        ->where('g.status', 'ACTIVE')
		        ->orderBy('g.attachment_code', 'asc')
		        ->lists('g.attachment_code', 'g.id');

	        return view('attachment.add_remove_1', compact('attachments', 'msgs'));

	    } catch (\Exception $e) {
	        $msge = 'Stock adjustment failed: ' . $e->getMessage();
	        return view('attachment.add_remove_post', compact('attachment', 'msge'));
	    }
	}

// attachments Location

	public function attachment_location_table() 
	{

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [locations_as]"));

		return view('attachment.attachment_location_table',compact('data'));
	}

	public function attachment_location_table_add() 
	{

		return view('attachment.attachment_location_table_add');
	}

	public function attachment_location_table_post (Request $request) 
	{

		// Get form inputs
	    $forminput = $request->all();

	    $location = trim($forminput['location']);

	    $table = new locations_a;
		$table->location = $location;
		$table->save();


		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [locations_as]"));
		$msgs = 'attachment location Succesfuly created';
		return view('attachment.attachment_location_table',compact('data','msgs'));
	}

	public function attachment_location_edit($id) 
	{
		
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
  			FROM [locations_as]
  			WHERE [id] = '".$id."' "));

		
		$location = $data[0]->location;
		
		return view('attachment.attachment_location_edit', compact('id','location'));
	}

	public function attachment_location_edit_post(Request $request)	
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
	        UPDATE [locations_as]
	        SET location = :location
	        WHERE id = :id
	    "), [
	        'location' => $location,
	        'id' => $id
	    ]);

	    $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [locations_as]"));
		$msgs = 'attachment location Succesfuly updated';
		return view('attachment.attachment_location_table',compact('data','msgs'));
	}

// attachment Type

	public function attachment_type_table() 
	{

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [attachment_types]"));

		return view('attachment.attachment_type_table',compact('data'));
	}

	public function attachment_type_table_add() 
	{

		return view('attachment.attachment_type_table_add');
	}

	public function attachment_type_table_post(Request $request) 
	{

		// Get form inputs
	    $forminput = $request->all();

	    $attachment_type = trim($forminput['attachment_type']);
	    $description = trim($forminput['description']);

	    $table = new attachment_type;
		$table->attachment_type = $attachment_type;
		$table->description = $description;
		$table->save();


		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [attachment_types]"));
		$msgs = 'attachment type Succesfuly created';
		return view('attachment.attachment_type_table',compact('data','msgs'));
	}

	public function attachment_type_edit($id)
	{
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
  			FROM [attachment_types]
  			WHERE [id] = '".$id."' "));

		
		$attachment_type = $data[0]->attachment_type;
		$description = $data[0]->description;
		
		return view('attachment.attachment_type_edit', compact('id','attachment_type','description'));
	}

	public function attachment_type_edit_post(Request $request)	
	{

	    // Optional validation
	    $this->validate($request, [
	        'id' => 'required|integer',
	        'attachment_type' => 'string|max:1000'
	        
	    ]);

	    // Get form inputs
	    $forminput = $request->all();

	    $id = $forminput['id'];
	    $attachment_type = trim($forminput['attachment_type']);
	    $description = trim($forminput['description']);

	    	    // Update the record using raw SQL
	    DB::connection('sqlsrv')->update(DB::raw("
	        UPDATE [attachment_types]
	        SET attachment_type = :attachment_type,
	        	description = :description
	        WHERE id = :id
	    "), [
	        'attachment_type' => $attachment_type,
	        'description' => $description,
	        'id' => $id
	    ]);

	    $data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [attachment_types]"));
		$msgs = 'attachment type Succesfuly updated';
		return view('attachment.attachment_type_table',compact('data','msgs'));
	}





	
}

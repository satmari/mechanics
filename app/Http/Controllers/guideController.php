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

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class guideController extends Controller {

	public function guide_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT g.*,
			  gt.guide_type,
			  s.supplier
			  FROM [mechanics].[dbo].[guides] as g
			  LEFT JOIN [mechanics].[dbo].[guide_types] as gt ON gt.id = g.guide_type_id
			  LEFT JOIN [mechanics].[dbo].suppliers as s ON s.id = g.suplier_id
		"));

		return view('guide.table',compact('data'));
	}

	public function guide_edit($id) {

	    $data = DB::table('guides')->where('id', $id)->first();

	    // Fetch dropdown options and convert to array
		$guide_types = DB::table('guide_types')->lists('guide_type', 'id');
    	$suppliers   = DB::table('suppliers')->lists('supplier', 'id');

	    return view('guide.edit', compact('data','guide_types','suppliers'));
	}

	public function guide_update_post(Request $request, $id) {
	
	    DB::table('guides')->where('id', $id)->update([
	        'guide_code'        => $request->guide_code,
	        'guide_description' => $request->guide_description,
	        'guide_type_id'     => $request->guide_type_id,
	        'suplier_id'        => $request->suplier_id,
	        'machine_class'     => $request->machine_class,
	        'location'          => $request->location,
	        'calz_code'         => $request->calz_code,
	        'fold'              => $request->fold,
	        'style'             => $request->style,
	        'operation'         => $request->operation,
	        'entry_mm'          => $request->entry_mm,
	        'exit_mm'           => $request->exit_mm,
	        'tickness_mm'       => $request->tickness_mm,
	        'elastic_mm'        => $request->elastic_mm,
	        'note'              => $request->note,
	        'picture'           => $request->picture,
	        'video'             => $request->video,
	        'qty_su'            => $request->qty_su,
	        'qty_ki'            => $request->qty_ki,
	        'qty_se'            => $request->qty_se,
	        'qty_valy'          => $request->qty_valy,
	        'status'            => $request->status,
	        'updated_at'        => now()
	    ]);

	    return redirect()->back()->with('success','Guide updated successfully!');
	}

	public function guide_type_edit($id) {
		
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
  			FROM [guide_types]
  			WHERE [id] = '".$id."' "));

		$guide_type = $data[0]->guide_type;
		$description = $data[0]->description;
		

		return view('guide.guide_type_edit', compact('id','guide_type','description'));
	}

	public function guide_type_edit_post(Request $request)	{

	    // Optional validation
	    $this->validate($request, [
	        'id' => 'required|integer',
	        'guide_type' => 'required|string|max:255',
	        'description' => 'string|max:1000'
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

    return Redirect::to('guide_type_table');
}


	public function supplier_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
		  FROM [suppliers]
		"));

		return view('guide.supplier_table',compact('data'));
	}

	public function supplier_edit($id) {
		
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
  			FROM [suppliers]
  			WHERE [id] = '".$id."' "));

		$supplier = $data[0]->supplier;
		$location = $data[0]->location;
		$contact = $data[0]->contact;
		

		return view('guide.supplier_edit', compact('id','supplier','location','contact'));
	}

	public function supplier_edit_post(Request $request)	{

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

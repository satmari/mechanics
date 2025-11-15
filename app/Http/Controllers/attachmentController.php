<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class attachmentController extends Controller {


	public function index()
	{
		//
	}

	public function attachment_type_edit($id) {
		
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
  			FROM [attachment_types]
  			WHERE [id] = '".$id."' "));

		$attachment_type = $data[0]->attachment_type;
		$description = $data[0]->description;
		

		return view('attachmente.attachment_type_edit', compact('id','attachment_type','description'));
	}

	public function attachment_type_edit_post(Request $request)	{

	    // Optional validation
	    $this->validate($request, [
	        'id' => 'required|integer',
	        'attachment_type' => 'required|string|max:255',
	        'description' => 'string|max:1000'
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

    return Redirect::to('attachment_type_table');
}


}

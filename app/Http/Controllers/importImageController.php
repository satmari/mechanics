<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Maatwebsite\Excel\Facades\Excel;

// use Request; // for import
use Illuminate\Http\Request; // for image

use App\class_table;

use DB;
// use Carbon;

class importImageController extends Controller {

	public function upload_class_image(Request $request){


	  $input = $request->all();
      // dd($input);
      $IntKey = $input['id'];
      $brand = $input['brand'];
      $code = $input['code'];
  	  $class = $input['class'];
	  
	  // dd($IntKey);

      if ($request->hasFile('file')) {
        $image = $request->file('file');
        
        $old_name = $image->getClientOriginalName();
        $filename = pathinfo($old_name, PATHINFO_FILENAME);
        // dd($filename);
        // $old = str_replace($old_namel)

        // $name = $filename.'_'.time().'.'.$image->getClientOriginalExtension();
        $name = $filename.'_'.date("Y-m-d_his").'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/storage/ClassImages/');
        $image->move($destinationPath, $name);
        // $this->save();
        // return back()->with('success','Image Upload successfully');

    		
		$table = class_table::firstOrNew(['IntKey' => $IntKey]);
        $table->IntKey = $IntKey;
        $table->brand = $brand;
        $table->code = $code;
		$table->class = $class;
        
		$table->image = $name;
		$table->save();


        // dd('Upload successfully, file is:'.$name.' and style_id: '.$id);
        return redirect('class_table');

      }

    }



	
}

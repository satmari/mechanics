<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Maatwebsite\Excel\Facades\Excel;

use Request; // for import
// use Illuminate\Http\Request; // for image

use App\Ecommerce;

use DB;
// use Carbon;

class importController extends Controller {

	public function index()
	{
		//
		return view('import.index');
	}
	
	public function postUpdateRemark(Request $request) {
		// dd('CAO');
	    $getSheetName = Excel::load(Request::file('file1'))->getSheetNames();
	    // dd($getSheetName);

	    foreach($getSheetName as $sheetName)
	    {
	        //if ($sheetName === 'Product-General-Table')  {
	    	//selectSheetsByIndex(0)
	           	// DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	            // DB::table('users')->truncate();
				// DB::statement('SET FOREIGN_KEY_CHECKS=1;');

	            //Excel::selectSheets($sheetName)->load($request->file('file'), function ($reader)
	            //Excel::selectSheets($sheetName)->load(Input::file('file'), function ($reader)
	            //Excel::filter('chunk')->selectSheetsByIndex(0)->load(Request::file('file'))->chunk(50, function ($reader)
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file1'))->chunk(500, function ($reader)
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);

	                foreach($readerarray as $row)
	                {
	                	// dd(strval($row['os']));
	                	$os = strval($row['os']);
	                	$remark = strval($row['remark']);

	                	$machine_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM machines WHERE os = '".$os."' "));
	                	// dd($machine_data);

	                	if ($machine_data[0]->inteos_status == 'SU') {

		     //            	DB::connection('sqlsrv2')->update(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_MachPool]
				  	// 			SET [Remark] = '".$remark."'
				  	// 			WHERE [MachNum] = '".$os."' "));

							// DB::connection('sqlsrv2')->update(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool]
				  	// 			SET [Remark] = ''
				  	// 			WHERE [MachNum] = '".$os."' "));

	                	} elseif ($machine_data[0]->inteos_status == 'KI') {


		     //            	DB::connection('sqlsrv2')->update(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_MachPool]
				  	// 			SET [Remark] = ''
				  	// 			WHERE [MachNum] = '".$os."' "));

							// DB::connection('sqlsrv2')->update(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool]
				  	// 			SET [Remark] = '".$remark."'
				  	// 			WHERE [MachNum] = '".$os."' "));

	                	} else {
	                		// dd('no inteos_status');

	      //           		DB::connection('sqlsrv2')->update(DB::raw("UPDATE [BdkCLZG].[dbo].[CNF_MachPool]
				  	// 			SET [Remark] = '".$remark."'
				  	// 			WHERE [MachNum] = '".$os."' "));

							// DB::connection('sqlsrv2')->update(DB::raw("UPDATE [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool]
				  	// 			SET [Remark] = ''
				  	// 			WHERE [MachNum] = '".$os."' "));

	                	}

	                }
	            });
	    }
		return redirect('/');
	}

}

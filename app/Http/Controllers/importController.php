<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Maatwebsite\Excel\Facades\Excel;

use Request; // for import
// use Illuminate\Http\Request; // for image

use App\Ecommerce;
use App\machines;

use DB;
// use Carbon;

class importController extends Controller {

	public function index() {
		//

		$pr_no = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 os FROM [mechanics].[dbo].[machines]
			WHERE os like 'PR%' 
			ORDER BY os desc"));
		// dd($pr_no);

		if (isset($pr_no[0]->os)) {
			$pr_no = $pr_no[0]->os;
		}

		return view('import.index', compact('pr_no'));
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
	
	public function postUpdateInfo(Request $request) {
		// dd('CAO');
	    $getSheetName = Excel::load(Request::file('file2'))->getSheetNames();
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
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file2'))->chunk(5000, function ($reader)
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);

	                foreach($readerarray as $row)
	                {
	                	// dd($row);
	                	$os = strval($row['os']);

	                	$gauge = $row['gauge'];
	                	if (is_null($gauge)) {
	                		$gauge = 0;
	                	} else {
	                		$gauge = round((float)$row['gauge'],1);
	                	}

	                	$gadget = $row['gadget'];
	                	if (is_null($gadget)) {
	                		$gadget = NULL;
	                	}

	                	$el_dev_small_brand = $row['tension_device_small_brand'];
	                	if (is_null($el_dev_small_brand)) {
	                		$el_dev_small_brand = NULL;
	                		$el_dev_small_quantity = 0;
	                	} else {
	                		$el_dev_small_quantity = (int)$row['tension_device_small_quantity'];
	                	}

	                	$el_dev_big_brand = $row['tension_device_big_brand'];
	                	if (is_null($el_dev_big_brand)) {
	                		$el_dev_big_brand = NULL;
	                		$el_dev_big_quantity = 0;
	                	} else {
	                		$el_dev_big_quantity = (int)$row['tension_device_big_quantity'];
	                	}
	                	
	                	$puller = $row['puller'];
	                	if (is_null($row['puller'])) {
	                		$puller = 0;
	                	} else {
	                		$puller = 1;
	                	}

	                	$rollers = $row['rollers'];
	                	if (is_null($row['rollers'])) {
	                		$rollers = 0;
	                	} else {
	                		$rollers = 1;
	                	}
	                	


	                	$machine_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM machines WHERE os = '".$os."' "));
	                	// dd($machine_data);
	                	$id = $machine_data[0]->id;


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
	                	

	                }
	            });
	    }
		return redirect('/');
	}

	public function postImportMachines(Request $request) {
		// dd('CAO');
	    $getSheetName = Excel::load(Request::file('file3'))->getSheetNames();
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
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file3'))->chunk(5000, function ($reader)
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);

	                foreach($readerarray as $row)
	                {
	                	// dd($row);

	                	$os = trim($row['os']);
	                	$IntKey = trim((int)$row['class']);

	                	if (strlen($os) != 8) {
	                		dd('Error: os ('.$os.') must be 8 characters');
	                	}

	                	$data = DB::connection('sqlsrv2')->select(DB::raw("SELECT *
								FROM [BdkCLZG].[dbo].[CNF_MaTypes]
  								WHERE IntKey =  '".$IntKey."' "));
	                	// dd($data);

	                	if (isset($data[0]->IntKey)) {

	                		$IntKey = $data[0]->IntKey;
	                		$brand = $data[0]->Brand;
	                		$class = $data[0]->MaTyp;
	                		$code = $data[0]->MaCod;

	                	} else {

	                		dd('Error: can not find this class ('.$IntKey.') in Inteos, please create class first.');
	                	}

	                	$check_if_exist = DB::connection('sqlsrv2')->select(DB::raw("SELECT *
								FROM [BdkCLZG].[dbo].[CNF_MachPool]
  								WHERE MachNum =  '".$os."' "));
	                	// dd($check_if_exist);

	                	if (isset($check_if_exist[0]->Cod)) {
	                		dd('Error: machine with this os ('.$os.') already exist in table');
	                	}

	                	$da = date("Y-m-d H:i:s");

	                	$su_last_id = DB::connection('sqlsrv2')->select(DB::raw("SELECT TOP 1 [Cod]
							FROM [BdkCLZG].[dbo].[CNF_MachPool]
							ORDER BY [Cod] desc"));
	                	// dd($su_last_id);

						$id = (int)$su_last_id[0]->Cod + 1;

						$su = DB::connection('sqlsrv2')->update(DB::raw("INSERT INTO [BdkCLZG].[dbo].[CNF_MachPool]
							([Cod],[MachNum],[MaTyCod],[CreateDT],[InRepair],[Remark],[NotAct])
							VALUES
							('".$id."', '".$os."', '".$IntKey."','".$da."', NULL, NULL, NULL)
			  				"));

						$ki = DB::connection('sqlsrv2')->update(DB::raw("INSERT INTO [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool]
							([Cod],[MachNum],[MaTyCod],[CreateDT],[InRepair],[Remark],[NotAct])
							VALUES
							('".$id."', '".$os."', '".$IntKey."','".$da."', NULL, NULL, '1')
			  				"));

	                	

	                }
	            });
	    }
		return redirect('update_from_inteos');
	}

	

}

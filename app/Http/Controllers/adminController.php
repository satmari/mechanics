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

class adminController extends Controller {

	public function index()
	{
		//
	}

	public function machines_in_inteos() {
		// dd('test');

		$data = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
			mp.MachNum
			--,(SELECT MachNum FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) as MAchNum_KIK
			,mp.Cod as Cod_SU
			--,(SELECT [Cod] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) as Cod_KIK
			,mt.Brand
			--,(SELECT mt_kik.Brand FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as mp_kik JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MaTypes] as mt_kik ON mt_kik.IntKey = mp_kik.MaTyCod WHERE mp_kik.Cod = mp.Cod) as MaBrand_KIK
			,mt.MaCod
			--,(SELECT mt_kik.MaCod FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as mp_kik JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MaTypes] as mt_kik ON mt_kik.IntKey = mp_kik.MaTyCod WHERE mp_kik.Cod = mp.Cod) as MaCod_KIK
			,mt.MaTyp
			--,(SELECT mt_kik.MaTyp FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as mp_kik JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MaTypes] as mt_kik ON mt_kik.IntKey = mp_kik.MaTyCod WHERE mp_kik.Cod = mp.Cod) as MaTyp_KIK
			--,mt.IntKey as mt_IntKey
			--,(SELECT mt_kik.IntKey FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as mp_kik JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MaTypes] as mt_kik ON mt_kik.IntKey = mp_kik.MaTyCod WHERE mp_kik.Cod = mp.Cod) as mt_IntKey_KIK
			--,mp.Remark as remark_su
			--,(SELECT Remark FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) as remark_KIK

		--,mp.NotAct
		,(CASE WHEN mp.NotAct = 1 THEN 'OFF' ELSE 'ON' END) as Subotica_main_status
		--,(SELECT NotAct FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE MachNum = mp.MachNum) as KikNotAct
		,(CASE WHEN (SELECT NotAct FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE MachNum = mp.MachNum) = 1 THEN 'OFF' ELSE 'ON' END) as Kikinda_main_status
		--,(SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) as Subotica_status1

		,(CASE
			WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) is null THEN --'test'
			(
			CASE
				WHEN (SELECT [InRepair] FROM [BdkCLZG].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) IS NOT NULL THEN 'In Repair'
				WHEN (SELECT [InRepair] FROM [BdkCLZG].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) IS NULL THEN 'Available'
			END
			)
				WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 0 THEN 'Spare'
				WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 1 THEN 'Needed'
				WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 4 THEN 'Ready for next change'
				WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 5 THEN 'On stock'
				WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 6 THEN 'To be repaired'
			END) as Subotica_status

		,(CASE
			WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) is null THEN --'test'
			(
			CASE
				WHEN (SELECT [InRepair] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) IS NOT NULL THEN 'In Repair'
				WHEN (SELECT [InRepair] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) IS NULL THEN 'Available'
			END
		)

		WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 0 THEN 'Spare'
		WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 1 THEN 'Needed'
		WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 4 THEN 'Ready for next change'
		WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 5 THEN 'On stock'
		WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 6 THEN 'To be repaired'

		END) as Kikinda_status

		,(SELECT m.[ModNam] FROM [BdkCLZG].[dbo].[CNF_ModMach] as mm
		RIGHT JOIN [BdkCLZG].[dbo].[CNF_Modules] as m ON m.[Module] = mm.Module
		WHERE mm.MdCod = mp.Cod) as Subotica_line

		,(SELECT m.[ModNam] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] as mm
		RIGHT JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_Modules] as m ON m.[Module] = mm.Module
		WHERE mm.MdCod = mp.Cod) as Kikinda_line

		--,(SELECT Pos FROM [BdkCLZG].[dbo].[CNF_ModMach] as mm WHERE mm.MdCod = mp.Cod) as Subotica_pos
		--,(SELECT Pos FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] as mm WHERE mm.MdCod = mp.Cod) as Kikinda_pos

		FROM [BdkCLZG].[dbo].[CNF_MachPool] as mp
		LEFT JOIN [BdkCLZG].[dbo].[CNF_MaTypes] as mt ON mp.MaTyCod = mt.IntKey
		WHERE MachNum != ''
		ORDER BY MachNum ASC"));
		// dd($data);

		return view('Admin.machines_in_inteos',compact('data'));
	}

	public function update_from_inteos() {

		$data = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
			mp.MachNum
			--,(SELECT MachNum FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) as MAchNum_KIK
			,mp.Cod as Cod_SU
			--,(SELECT [Cod] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) as Cod_KIK
			,mt.Brand
			--,(SELECT mt_kik.Brand FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as mp_kik JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MaTypes] as mt_kik ON mt_kik.IntKey = mp_kik.MaTyCod WHERE mp_kik.Cod = mp.Cod) as MaBrand_KIK
			,mt.MaCod
			--,(SELECT mt_kik.MaCod FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as mp_kik JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MaTypes] as mt_kik ON mt_kik.IntKey = mp_kik.MaTyCod WHERE mp_kik.Cod = mp.Cod) as MaCod_KIK
			,mt.MaTyp
			--,(SELECT mt_kik.MaTyp FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as mp_kik JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MaTypes] as mt_kik ON mt_kik.IntKey = mp_kik.MaTyCod WHERE mp_kik.Cod = mp.Cod) as MaTyp_KIK
			--,mt.IntKey as mt_IntKey
			--,(SELECT mt_kik.IntKey FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] as mp_kik JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MaTypes] as mt_kik ON mt_kik.IntKey = mp_kik.MaTyCod WHERE mp_kik.Cod = mp.Cod) as mt_IntKey_KIK
			,mp.Remark as remark_su
			,(SELECT Remark FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) as remark_ki

			--,mp.NotAct
			,(CASE WHEN mp.NotAct = 1 THEN 'OFF' ELSE 'ON' END) as Subotica_main_status
			--,(SELECT NotAct FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE MachNum = mp.MachNum) as KikNotAct
			,(CASE WHEN (SELECT NotAct FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE MachNum = mp.MachNum) = 1 THEN 'OFF' ELSE 'ON' END) as Kikinda_main_status
			--,(SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) as Subotica_status1

			,(CASE
				WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) is null THEN --'test'
				(
				CASE
					WHEN (SELECT [InRepair] FROM [BdkCLZG].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) IS NOT NULL THEN 'In Repair'
					WHEN (SELECT [InRepair] FROM [BdkCLZG].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) IS NULL THEN 'Available'
				END
				)
					WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 0 THEN 'Spare'
					WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 1 THEN 'Needed'
					WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 4 THEN 'Ready for next change'
					WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 5 THEN 'On stock'
					WHEN (SELECT [MaStat] FROM [BdkCLZG].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 6 THEN 'To be repaired'
				END) as Subotica_status

			,(CASE
				WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) is null THEN --'test'
				(
				CASE
					WHEN (SELECT [InRepair] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) IS NOT NULL THEN 'In Repair'
					WHEN (SELECT [InRepair] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_MachPool] WHERE [Cod] = mp.Cod) IS NULL THEN 'Available'
				END
			)

			WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 0 THEN 'Spare'
			WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 1 THEN 'Needed'
			WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 4 THEN 'Ready for next change'
			WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 5 THEN 'On stock'
			WHEN (SELECT [MaStat] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] WHERE MdCod = mp.Cod) = 6 THEN 'To be repaired'

			END) as Kikinda_status

			,(SELECT m.[ModNam] FROM [BdkCLZG].[dbo].[CNF_ModMach] as mm
			RIGHT JOIN [BdkCLZG].[dbo].[CNF_Modules] as m ON m.[Module] = mm.Module
			WHERE mm.MdCod = mp.Cod) as Subotica_line

			,(SELECT m.[ModNam] FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] as mm
			RIGHT JOIN [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_Modules] as m ON m.[Module] = mm.Module
			WHERE mm.MdCod = mp.Cod) as Kikinda_line

			--,(SELECT Pos FROM [BdkCLZG].[dbo].[CNF_ModMach] as mm WHERE mm.MdCod = mp.Cod) as Subotica_pos
			--,(SELECT Pos FROM [172.27.161.221\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_ModMach] as mm WHERE mm.MdCod = mp.Cod) as Kikinda_pos

			FROM [BdkCLZG].[dbo].[CNF_MachPool] as mp
			LEFT JOIN [BdkCLZG].[dbo].[CNF_MaTypes] as mt ON mp.MaTyCod = mt.IntKey
			WHERE MachNum != ''
			ORDER BY MachNum ASC"));
		// dd($data);
		

		for ($i=0; $i < count($data); $i++) { 
			
			$os = $data[$i]->MachNum;
			$brand = $data[$i]->Brand;
			$code = $data[$i]->MaCod;
			$type = $data[$i]->MaTyp;
			$remark_su = $data[$i]->remark_su;
			$remark_ki = $data[$i]->remark_ki;

			$inteos_status_su = $data[$i]->Subotica_main_status;
			$inteos_status_ki = $data[$i]->Kikinda_main_status;
			
			if ($inteos_status_su == 'ON') {
				$inteos_status = 'SU';
				$inteos_line = $data[$i]->Subotica_line;
				$inteos_machine_status = $data[$i]->Subotica_status;

			} elseif ($inteos_status_ki == 'ON') {
				$inteos_status = 'KI';
				$inteos_line = $data[$i]->Kikinda_line;
				$inteos_machine_status = $data[$i]->Kikinda_status;
			} else {
				$inteos_status = 'ERROR';
				$inteos_line;
				$inteos_machine_status;
			}
			// dd($inteos_line);

			$find_location = DB::connection('sqlsrv')->select(DB::raw("SELECT [id],[location] FROM [locations] WHERE location = '".$inteos_line."' "));
			// dd($find_location);

			if (isset($find_location[0])) {
				$location = $find_location[0]->location;
				$location_id = $find_location[0]->id;
			} else {
				$location = NULL;
				$location_id = NULL;
			}
			// dd($location);

			if ($inteos_machine_status == 'Needed') {
				$machine_status = 'Needed';
			}
			dd($machine_status);
			
					

			
		}



	}
}

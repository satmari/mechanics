<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class temp_fix_machine extends Model {

	//
	protected $table = 'temp_fix_machines';
	protected $fillable = ['id', 'os_id','os'];

}

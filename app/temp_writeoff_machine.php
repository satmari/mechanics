<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class temp_writeoff_machine extends Model {

	//
	protected $table = 'temp_writeoff_machines';
	protected $fillable = ['id', 'os_id','os'];

}

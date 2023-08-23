<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class temp_sell_machine extends Model {

	//
	protected $table = 'temp_sell_machines';
	protected $fillable = ['id', 'os_id','os'];
}

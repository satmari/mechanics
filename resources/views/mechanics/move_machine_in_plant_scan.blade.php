@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading" >Scan machine 
						@if (isset($new_location))
							<b><i>on {{ $new_location }}</i></b>
						@endif
					</div>
						
						@if (isset($msg))
							<div class="panel-heading" >	
							<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:green"><b>{{ $msg }}</b></span></i></small>
							</div>
						@endif
						@if (isset($msge))
							<div class="panel-heading" >
							<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msge }}</b></span></i></small>
							<audio autoplay="true" style="display:none;">
					        	<!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
					       	</audio>
					       	</div>
						@endif
						@if (isset($msgs))
							<div class="panel-heading" >
							<audio autoplay="true" style="display:none;">
					        	<!-- <source src="{{ asset('/css/1.wav') }}" type="audio/wav"> -->
					       	</audio>
					       	</div>
						@endif
						@if (isset($msgbin))
							<div class="panel-heading" >
							<audio autoplay="true" style="display:none;">
					        	<!-- <source src="{{ asset('/css/3.wav') }}" type="audio/wav"> -->
					       	</audio>
					       </div>
						@endif
				
				
				{!! Form::open(['url' => 'move_machine_in_plant_scan']) !!}

					@if (isset($new_location_id))
						{!! Form::hidden('new_location_id', $new_location_id) !!}
						{!! Form::hidden('new_location', $new_location) !!}
						{!! Form::hidden('new_area', $new_area) !!}
						{!! Form::hidden('new_plant', $new_plant) !!}
						{!! Form::hidden('session', $session) !!}
					@endif

				<div class="panel-body">
					{!! Form::text('machine_temp1', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
					<br>
				</div>
				<div class="panel-body">
					<!-- <p>Scan machine:</p> -->
					<p>Select machine manualy from the list: </p>
					<p><i>Filters included. Plant = {{$new_plant}} , location != {{ $new_location }} </i></p>
                    <select name="machine_temp2" id='select2' class="select form-con rol sele ct-form chos en">
                        <option value="" selected></option>
                        
                        @foreach ($machines as $m)
                        <option value="{{ $m->os }}">
                            {{ $m->os }} \ {{ $m->brand }} \ {{ $m->type }} \ {{ $m->code}}
                        </option>
                        @endforeach
                    </select>
					<hr>
				</div>

				{!! Form::submit('Confirm machine', ['class' => 'btn btn-info btn-lg center-block']) !!}
				@include('errors.list')
				{!! Form::close() !!}
				<br>

				@if(isset($data) AND (!empty($data)))
				<hr>
				<input type="hidden" id="_token" value="<?php echo csrf_token(); ?>">
				<table class="table table-striped table-bordered" >
					<tbody>
						@foreach ($data as $d)
						<tr>
							<td><b>{{ $d->os }}</b> \ {{$d->brand}} \ {{$d->type}} \ {{$d->code}} <i><small>(from {{ $d->location}})</small></i>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="{{ url('move_machine_in_plant_remove/'.$d->id.'/'.$d->ses) }}"><big><strong><span style="color:red">X</span></strong></big></a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>

				@endif

				<!-- <br>	 -->
				<hr>		
				<div>
				@if (!empty($data))
				<a href="{{ url('move_machine_in_plant_confirm/'.$session) }}" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-ok">&nbsp;</span>Confirm list</a>&nbsp;&nbsp;&nbsp;
				@else
				<a href="{{ url('move_machine_in_plant_confirm/'.$session) }}" class="btn btn-lg btn-success disabled"><span class="glyphicon glyphicon-ok">&nbsp;</span>Confirm list</a>&nbsp;&nbsp;&nbsp;
				@endif
				<a href="{{ url('move_machine_in_plant_cancel/'.$session) }}" class="btn btn-lg btn-danger"><span class="glyphicon glyphicon-remove">&nbsp;</span>Cancel and return</a>
				</div>
				<br>
				
				
			</div>
		</div>
	</div>
</div>
@endsection
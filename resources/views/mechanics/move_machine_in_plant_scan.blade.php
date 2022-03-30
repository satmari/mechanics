@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading" >Scan OS 
						@if (isset($new_location))
							<b><i>on {{ $new_location }}</i></b>
						@endif
						
						@if (isset($msg))
							<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:green"><b>{{ $msg }}</b></span></i></small>
						@endif
						@if (isset($msge))
							<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msge }}</b></span></i></small>
							<audio autoplay="true" style="display:none;">
					        	<!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
					       	</audio>
						@endif
						@if (isset($msgs))
							<audio autoplay="true" style="display:none;">
					        	<!-- <source src="{{ asset('/css/1.wav') }}" type="audio/wav"> -->
					       	</audio>
						@endif
						@if (isset($msgbin))
							<audio autoplay="true" style="display:none;">
					        	<!-- <source src="{{ asset('/css/3.wav') }}" type="audio/wav"> -->
					       	</audio>
						@endif
				</div>
				
				{!! Form::open(['url' => 'move_machine_in_plant_scan']) !!}

					@if (isset($new_location_id))
						{!! Form::hidden('new_location_id', $new_location_id) !!}
						{!! Form::hidden('new_location', $new_location) !!}
						{!! Form::hidden('new_area', $new_area) !!}
						{!! Form::hidden('new_plant', $new_plant) !!}
					@endif

				<div class="panel-body">
					{!! Form::text('machine_temp', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
					<br>
					{!! Form::submit('Confirm machine', ['class' => 'btn btn-success center-block']) !!}
					@include('errors.list')
				</div>
				{!! Form::close() !!}

				@if(isset($data))
				<input type="hidden" id="_token" value="<?php echo csrf_token(); ?>">
				<table class="table table-striped table-bordered" >
					<tbody>
						@foreach ($data as $d)
						<tr>
							<td>{{ $d->os }} <i><small>(from {{ $d->location}})</small></i>
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
				@if (isset($new_location_id))
					<a href="{{ url('move_machine_in_plant_confirm/'.$session) }}" class="btn btn-danger">Confirm list</a>
				@else
					<a href="{{ url('move_machine_in_plant_confirm/'.$session) }}" class="btn btn-danger" disabled>Confirm list</a>				
				@endif
				</div>
				<br>
				
			</div>
		</div>
	</div>
</div>
@endsection
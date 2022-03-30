@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading" >Scan OS - 
						@if (isset($plant_from))
							<b><i>FROM {{ $plant_from }}</i></b>
						@endif
						@if (isset($plant_to))
							<b><i>TO {{ $plant_to }}</i></b>
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
				
				
				{!! Form::open(['url' => 'transfer_machine_scan']) !!}

					@if ((isset($plant_from)) AND (isset($plant_to)))
						{!! Form::hidden('plant_from', $plant_from, ['class' => 'form-control']) !!}
						{!! Form::hidden('plant_to', $plant_to, ['class' => 'form-control']) !!}
						
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
							<td>{{ $d->os }} 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="{{ url('transfer_machine_remove/'.$d->id.'/'.$d->ses) }}"><big><strong><span style="color:red">X</span></strong></big></a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				@endif
				
				<!-- <br>	 -->
				<hr>		
				<div>
				@if (isset($plant_to))
					<a href="{{ url('transfer_machine_confirm/'.$session) }}" class="btn btn-danger">Confirm list</a>
				@else
					<a href="{{ url('transfer_machine_confirm/'.$session) }}" class="btn btn-danger" disabled>Confirm list</a>				
				@endif
				</div>
				<br>
				
			</div>
		</div>
	</div>
</div>
@endsection
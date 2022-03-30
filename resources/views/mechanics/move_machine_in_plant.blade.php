@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Move machine in same plant:</b>
				@if (isset($msge))
					<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msge }}</b></span></i></small>
					<audio autoplay="true" style="display:none;">
			        	<!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
			       	</audio>
				@endif
				@if (isset($msgs))
					<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:green"><b>{{ $msgs }}</b></span></i></small>
					<audio autoplay="true" style="display:none;">
			        	<!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
			       	</audio>
				@endif
					</div>
				<!-- <br> -->
			    <!-- <hr> -->
					{!! Form::open(['method'=>'POST', 'url'=>'move_machine_in_plant_loc']) !!}
					
				<div class="panel-body">
	                <p>Destination location:</p>
	            	{!! Form::text('location_new', '' , ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<br>
				{!! Form::submit('Next', ['class' => 'btn  btn-success center-block']) !!}
	            <br>

	            @include('errors.list')
	            {!! Form::close() !!}

	            @if (isset($success))
				<div class="alert alert-success" role="alert">
				  {{ $success }}
				</div>
				@endif
				@if (isset($danger))
				<div class="alert alert-danger" role="alert">
				  {{ $danger }}
				</div>
				@endif

	            <hr>
	            <a href="{{ url('afterlogin/') }}" class="btn btn-default center-bl ock">Back</a>
	            <br>
	            <br>
			</div>	
			</div>
		</div>
	</div>
</div>

@endsection
@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Transfer machine between plants (TO):</b></div>
				@if (isset($msge))
					<div class="panel-heading">
						<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msge }}</b></span></i></small>
						<audio autoplay="true" style="display:none;">
				        	<!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
				       	</audio>
				    </div>
				@endif
				@if (isset($msgs))
					<div class="panel-heading">
						<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:green"><b>{{ $msgs }}</b></span></i></small>
						<audio autoplay="true" style="display:none;">
				        	<!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
				       	</audio>
				    </div>
				@endif
					
				<br>
				{!! Form::open(['method'=>'POST', 'url'=>'transfer_machine_to']) !!}
				{!! Form::hidden('plant_from', $plant_from, ['class' => 'form-control']) !!}
	            {!! Form::hidden('plant_to', 'SUBOTICA', ['class' => 'form-control']) !!}
				{!! Form::submit('SUBOTICA', ['class' => 'btn  btn-success center-block']) !!}
		        @include('errors.list')
	            {!! Form::close() !!}
	            <br>
	            {!! Form::open(['method'=>'POST', 'url'=>'transfer_machine_to']) !!}
	            {!! Form::hidden('plant_from', $plant_from, ['class' => 'form-control']) !!}
	            {!! Form::hidden('plant_to', 'KIKINDA', ['class' => 'form-control']) !!}
				{!! Form::submit('KIKINDA', ['class' => 'btn  btn-warning center-block']) !!}
		        @include('errors.list')
	            {!! Form::close() !!}
	            <br>
	            {!! Form::open(['method'=>'POST', 'url'=>'transfer_machine_to']) !!}
	            {!! Form::hidden('plant_from', $plant_from, ['class' => 'form-control']) !!}
	            {!! Form::hidden('plant_to', 'SENTA', ['class' => 'form-control']) !!}
				{!! Form::submit('SENTA', ['class' => 'btn  btn-danger center-block']) !!}
		        @include('errors.list')
	            {!! Form::close() !!}
	            <br>
			    <hr>

				{!! Form::open(['method'=>'POST', 'url'=>'transfer_machine_to']) !!}
					{!! Form::hidden('plant_from', $plant_from, ['class' => 'form-control']) !!}
					<div class="panel-body">
		                <p><b>To</b> plant:</p>
		            	{!! Form::text('plant_to', '' , ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
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
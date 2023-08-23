@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading" >To Repair Machine in
				
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

				<br><br>
				{!! Form::open(['method'=>'POST', 'url'=>'adjust_machine_to']) !!}
				{!! Form::hidden('adjust_machine_to', 'SUBOTICA', ['class' => 'form-control']) !!}
				{!! Form::hidden('session', $session, ['class' => 'form-control']) !!}
				{!! Form::submit('SUBOTICA', ['class' => 'btn btn-success btn-lg center-block']) !!}
		        @include('errors.list')
	            {!! Form::close() !!}
	            <br><br>
	            {!! Form::open(['method'=>'POST', 'url'=>'adjust_machine_to']) !!}
	            {!! Form::hidden('adjust_machine_to', 'KIKINDA', ['class' => 'form-control']) !!}
	            {!! Form::hidden('session', $session, ['class' => 'form-control']) !!}
				{!! Form::submit('&nbsp;&nbsp;KIKINDA&nbsp;&nbsp;', ['class' => 'btn btn-warning btn-lg center-block']) !!}
		        @include('errors.list')
	            {!! Form::close() !!}
	           	<br><br>
	           	{!! Form::open(['method'=>'POST', 'url'=>'adjust_machine_to']) !!}
	            {!! Form::hidden('adjust_machine_to', 'SENTA', ['class' => 'form-control']) !!}
	            {!! Form::hidden('session', $session, ['class' => 'form-control']) !!}
				{!! Form::submit('&nbsp;&nbsp;&nbsp;SENTA&nbsp;&nbsp;&nbsp;', ['class' => 'btn btn-danger btn-lg center-block']) !!}
		        @include('errors.list')
	            {!! Form::close() !!}
	           	<br>
	           	<!-- <br>	 -->
				<!-- <hr> -->
				<hr>
	            <a href="{{ url('repair_machine') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
	            <br>
	            <br>

			</div>
		</div>
	</div>
</div>
@endsection
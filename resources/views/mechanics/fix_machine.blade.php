@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading" >Return repaired machine from?
				
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
				{!! Form::open(['method'=>'POST', 'url'=>'fix_machine_to']) !!}
				{!! Form::hidden('fix_machine_to', 'REPAIRING_SU', ['class' => 'form-control']) !!}
				{!! Form::hidden('session', $session, ['class' => 'form-control']) !!}
				{!! Form::submit('REPAIRING_SU', ['class' => 'btn btn-lg btn-success center-block']) !!}
		        @include('errors.list')
	            {!! Form::close() !!}
	            <br><br>
	            {!! Form::open(['method'=>'POST', 'url'=>'fix_machine_to']) !!}
	            {!! Form::hidden('fix_machine_to', 'REPAIRING_KI', ['class' => 'form-control']) !!}
	            {!! Form::hidden('session', $session, ['class' => 'form-control']) !!}
				{!! Form::submit('REPAIRING_KI', ['class' => 'btn btn-lg btn-warning center-block']) !!}
		        @include('errors.list')
	            {!! Form::close() !!}
	           	<br><br>
	           	{!! Form::open(['method'=>'POST', 'url'=>'fix_machine_to']) !!}
	            {!! Form::hidden('fix_machine_to', 'REPAIRING_SE', ['class' => 'form-control']) !!}
	            {!! Form::hidden('session', $session, ['class' => 'form-control']) !!}
				{!! Form::submit('REPAIRING_SE', ['class' => 'btn btn-lg btn-danger center-block']) !!}
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
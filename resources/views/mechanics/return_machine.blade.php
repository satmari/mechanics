@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading" >Return machine from
				
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

				@if (isset($external_locations))
					@foreach ($external_locations as $d)
					
						<br><br>
						{!! Form::open(['method'=>'POST', 'url'=>'return_machine_to']) !!}
						{!! Form::hidden('return_machine_to', $d->location, ['class' => 'losbut form-control']) !!}
						{!! Form::hidden('session', $session, ['class' => 'form-control']) !!}
						{!! Form::submit(str_pad($d->location .'   ('.$d->qty.')',15,' ',STR_PAD_BOTH), ['class' => 'btn btn-lg btn-success center-block']) !!}
				        @include('errors.list')
			            {!! Form::close() !!}

					@endforeach
				@endif
				
				<hr>
	            <a href="{{ url('borrow_machine') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
	            <br>
	            <br>
				
			</div>
		</div>
	</div>
</div>
@endsection
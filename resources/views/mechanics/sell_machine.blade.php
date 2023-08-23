@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading" >Sell machine to?
				
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

				<div class="panel-body">
				<br>
				{!! Form::open(['method'=>'POST', 'url'=>'sell_machine_to']) !!}
				
				<p>Insert buyer (unesite kupca) or document number (i broj otpremnice)</p>
					{!! Form::text('buyer', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
				<br>
				{!! Form::submit('Next', ['class' => 'btn btn-lg btn-success center-block']) !!}

		        @include('errors.list')
	            {!! Form::close() !!}
	            
				<hr>
	            <a href="{{ url('disable_machine') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
	            <br>
	            <br>
	            </div>
				
			</div>
		</div>
	</div>
</div>
@endsection
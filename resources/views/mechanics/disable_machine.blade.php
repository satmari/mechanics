@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Disable machine:</div>
				<div class="panel-heading">Curent number of <b>write-off</b> machines: {{ $writeoff }}</div>
				<div class="panel-heading">Curent number of <b>sold</b> machines: {{ $sold }}</div>
				
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

				@if (Auth::guest())
					<p>Please login first.</p>
				@else

					@if (Auth::user()->name == 'mechanics' OR Auth::user()->name == 'magacin' OR Auth::user()->name == 'admin')
					<div class="panel-body">
						<div class="">
							<a href="{{url('/writeoff_machine_scan')}}" class="btn btn-suc cess btn-lg center-block" style="background-color: #f96240;color: white;">
								<br><span class="glyphicon glyphicon-trash" aria-hidden="true">&nbsp;</span>WRITE OFF MACHINE <br><i>(otpis masine)</i><br><br></a>
						</div>
					</div>
					
					<div class="panel-body">
						<div class="">
							<a href="{{url('/sell_machine')}}" class="btn btn-pri mary btn-lg center-block" style="background-color: #ff2828;color: white">
								<br><span class="glyphicon glyphicon-eur" aria-hidden="true">&nbsp;</span>SELL MACHINE <br><i>(prodata masina)</i><br><br></a>
						</div>
					</div>
					<br>
					@endif
				@endif
				<hr>
	            <a href="{{ url('afterlogin/') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
	            <br>
	            <br>

			</div>
		</div>
	</div>
</div>
@endsection

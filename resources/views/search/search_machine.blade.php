@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading" >Search machine 
				
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

	           	<br>
	           	<div class="panel-body">
					<div class="">
						<a href="{{url('/search_by_barcode')}}" class="btn btn-suc cess btn-lg center-block" style="background-color: #e3e3e3"><span class="glyphicon glyphicon-search" aria-hidden="true">&nbsp;</span>SEARCH BY MACHINE BARCODE<br><i>(info about machine)</i><br></a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/search_by_location')}}" class="btn btn-suc cess btn-lg center-block" style="background-color: #e3e3e3"><span class="glyphicon glyphicon-search" aria-hidden="true">&nbsp;</span>SEARCH BY LOCATION<br><i>(info about machine)</i><br></a>
					</div>
				</div>	
	           	
				<!-- <hr> -->
				<hr>
	            <a href="{{ url('afterlogin/') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
	            <br>
	            <br>
				
			</div>
		</div>
	</div>
</div>
@endsection
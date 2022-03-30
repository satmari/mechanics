@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Mechanic: {{ $mechanic }}</div>
				

				@if(isset($msg))
				{{ $msg }}
				@endif

				@if (Auth::guest())
					<p>Please login first.</p>
				@else

					@if (Auth::user()->name == 'mechanics' OR Auth::user()->name == 'admin')
					<div class="panel-body">
						<div class="">
							<a href="{{url('/move_machine_in_plant')}}" class="btn btn-success btn-lg center-block"><br>MOVE MACHINE <br><i>in the same plant</i><br><br></a>
						</div>
					</div>
					<br>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/transfer_machine')}}" class="btn btn-primary btn-lg center-block"><br>TRANSFER MACHINE <br> <i>between plants</i><br><br></a>
						</div>
					</div>
					<br>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/transfer_machines_among_plants')}}" class="btn btn-warning btn-lg center-block"><br>TRANSFER MACHINES <br><i>among plants</i><br><br></a>
						</div>
					</div>
					@endif
				@endif
			</div>
		</div>
	</div>
</div>
@endsection

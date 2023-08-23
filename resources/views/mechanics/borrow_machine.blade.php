@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Borrow machine:</div>
				<div class="panel-heading">Curent number of borrowed machines: {{ $borrow }}</div>
				
				@if(isset($msg))
				{{ $msg }}
				@endif

				@if (Auth::guest())
					<p>Please login first.</p>
				@else

					@if (Auth::user()->name == 'mechanics' OR Auth::user()->name == 'magacin' OR Auth::user()->name == 'admin')
					<div class="panel-body">
						<div class="">
							<a href="{{url('/give_machine')}}" class="btn btn-suc cess btn-lg center-block" style="background-color: #f9da40"><br><span class="glyphicon glyphicon-log-out" aria-hidden="true">&nbsp;</span>GIVE MACHINE <br><i>(pozajmi masinu)</i><br><br></a>
						</div>
					</div>
					
					<div class="panel-body">
						<div class="">
							<a href="{{url('/return_machine')}}" class="btn btn-pri mary btn-lg center-block" style="background-color: #cdae88"><br><span class="glyphicon glyphicon-log-in" aria-hidden="true">&nbsp;</span>RETURN MACHINE <br><i>(vrati pozajmljenu masinu)</i><br><br></a>
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

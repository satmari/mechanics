@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Repair machine:</div>
				<div class="panel-heading">Curent number of machines on repairing: {{ $on_repair }}</div>
				
				@if(isset($msg))
				{{ $msg }}
				@endif

				@if (Auth::guest())
					<p>Please login first.</p>
				@else

					@if (Auth::user()->name == 'mechanics' OR Auth::user()->name == 'admin')
					<div class="panel-body">
						<div class="">
							<a href="{{url('/adjust_machine')}}" class="btn btn-succe ss btn-lg center-block" style="background-color: #a3f2ff"><br><span class="glyphicon glyphicon-remove-sign" aria-hidden="true">&nbsp;</span>TO REPAIR MACHINE <br><i>(masina za popravku)</i><br><br></a>
						</div>
					</div>
					
					<div class="panel-body">
						<div class="">
							<a href="{{url('/fix_machine')}}" class="btn btn-prim ary btn-lg center-block" style="background-color: #a5c6cb"><br><span class="glyphicon glyphicon-ok-sign" aria-hidden="true">&nbsp;</span>REPAIRED MACHINE <br><i>(vrati popravljenu masinu)</i><br><br></a>
						</div>
					</div>
					
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

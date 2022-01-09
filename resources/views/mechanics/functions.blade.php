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
							<a href="{{url('/#')}}" class="btn btn-success btn-lg center-block"><br>Function 1<br><br></a>
						</div>
					</div>
					<br>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/#')}}" class="btn btn-primary btn-lg center-block"><br>Function 2<br><br></a>
						</div>
					</div>
					<br>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/#')}}" class="btn btn-warning btn-lg center-block"><br>Function 3<br><br></a>
						</div>
					</div>
					@endif
				@endif
			</div>
		</div>
	</div>
</div>
@endsection

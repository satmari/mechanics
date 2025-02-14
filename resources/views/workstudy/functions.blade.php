@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Workstudy: {{ $workstudy }}</div>

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
					<!-- </div> -->

				@if(isset($msg))
				{{ $msg }}
				@endif

				@if (Auth::guest())
					<p>Please login first.</p>
				@else

					@if (Auth::user()->name == 'workstudy' OR Auth::user()->name == 'admin')
					<br>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/add_comment_ws')}}" class="btn btn-li nk btn-lg center-block" style="background-color: #9eedc7"><span class="glyphicon glyphicon-pencil" aria-hidden="true">&nbsp;</span>ADD MACHINE<br> INFORMATION</a>
						</div>
					</div>
					
					
					
					@endif
				@endif
			</div>
		</div>
	</div>
</div>
@endsection

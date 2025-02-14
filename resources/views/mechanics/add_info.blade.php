@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading" >Add machine information:
						
				</div>

						@if (isset($msg))
							<div class="panel-heading" >
								<small><i>&nbsp;&nbsp;&nbsp; Msg: <span style="color:green"><b>{{ $msg }}</b></span></i></small>
							</div>
						@endif
						@if (isset($msge))
							<div class="panel-heading" >
								<small><i>&nbsp;&nbsp;&nbsp; Msg: <span style="color:red"><b>{{ $msge }}</b></span></i></small>
								<audio autoplay="true" style="display:none;">
						        	<!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
						       	</audio>
					       	</div>
						@endif
						@if (isset($msgs))
							<div class="panel-heading" >
								<small><i>&nbsp;&nbsp;&nbsp; <span style="color:green"><b>{{ $msgs }}</b></span></i></small>
					       	</div>
						@endif
						@if (isset($msgbin))
							<div class="panel-heading" >
								<audio autoplay="true" style="display:none;">
						        	<!-- <source src="{{ asset('/css/3.wav') }}" type="audio/wav"> -->
						       	</audio>
					       	</div>
						@endif

				{!! Form::open(['url' => 'add_info_scan']) !!}

					<div class="panel-body">
						<p>Scan machine:</p>
						{!! Form::text('machine1', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
						<br>
						
						@include('errors.list')
					</div>
					
					<div class="panel-body">
						<!-- <p>Scan machine:</p> -->
						<p>Select machine manualy from the list (search): </p>
                        <select name="machine3" id='select2' class="select form-con rol sele ct-form cho sen">
                            <option value="" selected></option>
                            
                            @foreach ($machines as $m)
                            <option value="{{ $m->os }}">
                                {{ $m->os }} / {{ $m->brand }} / {{ $m->type }} / {{ $m->code }}
                            </option>
                            @endforeach
                        </select>
						<br>
						<hr>
						<br>
						{!! Form::submit('Search', ['class' => 'btn btn-success btn-lg center-block']) !!}
						@include('errors.list')
					</div>
					
				{!! Form::close() !!}
				
				
				<!-- <br> -->
				<hr>
	            <a href="{{ url('afterlogin/') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
	            <br>
	            <br>
				
			</div>
		</div>
	</div>
</div>
@endsection
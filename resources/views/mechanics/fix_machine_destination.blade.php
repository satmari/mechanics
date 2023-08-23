@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading" >Scan destination, location where to put machine afer repairing
						
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
								<audio autoplay="true" style="display:none;">
						        	<!-- <source src="{{ asset('/css/1.wav') }}" type="audio/wav"> -->
						       	</audio>
					       	</div>
						@endif
						@if (isset($msgbin))
							<div class="panel-heading" >
								<audio autoplay="true" style="display:none;">
						        	<!-- <source src="{{ asset('/css/3.wav') }}" type="audio/wav"> -->
						       	</audio>
					       	</div>
						@endif
				
				{!! Form::open(['url' => 'fix_machine_destination_post']) !!}

					{!! Form::hidden('session', $session) !!}
					{!! Form::hidden('plant', $plant) !!}
					{!! Form::hidden('area', $area) !!}

				<div class="panel-body">
					<p>Scan location:</p>
					{!! Form::text('location1', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
					<br>

					<!-- <p>Scan machine:</p> -->
					<p>Select location manualy from the list: </p>
					<p><i>Filters included. Locations from plant = {{$plant}} </i></p>
                    <select name="location2" id='select2' class="select form-con rol sele ct-form chos en">
                        <option value="" selected></option>
                        
                        @foreach ($new_locations as $m)
                        <option value="{{ $m->location }}">
                            {{ $m->location}}
                        </option>
                        @endforeach
                    </select>
					<hr>

					{!! Form::submit('Confirm destination', ['class' => 'btn btn-info btn-lg center-block']) !!}
					@include('errors.list')
				</div>
				{!! Form::close() !!}

				
				<br>
				
				
		</div>
	</div>
</div>
@endsection
@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Move machine in same plant:</b>
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
				<!-- <br> -->
			    <!-- <hr> -->
					{!! Form::open(['method'=>'POST', 'url'=>'move_machine_in_plant_loc']) !!}
					
				<div class="panel-body">
	                <p>Destination location:</p>
	            	{!! Form::text('location_new1', '' , ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<br>
				
	            <!-- <br> -->
<!-- 
	            <div class="panel-body">
					<p>Select destination manualy from the list : </p>
                    <select name="location_new2" class="select form-control select-form">
                        <option value="" selected></option>
                        
                        @foreach ($locations as $m)
                        <option value="{{ $m->location }}">
                            <span >{{ $m->plant }} &nbsp;&nbsp; {{ $m->area }} &nbsp;&nbsp;&nbsp; {{ $m->location }} </span>
                        </option>
                        @endforeach
                    </select>
					<br>
					<br>
					
					@include('errors.list')
				</div> -->
				
				<div class="panel-body">
					<!-- <p>Scan machine:</p> -->
					<p>Select destination manualy from the list (search): </p>
                    <select name="location_new3" id='select2' class="select form-con rol sele ct-form cho sen" style="min-width:350px">
                        <option value="" selected></option>
                        
                        @foreach ($locations as $m)
                        <option value="{{ $m->location }}">
                            {{ $m->plant }} &nbsp;&nbsp; {{ $m->area }} &nbsp;&nbsp;&nbsp; {{ $m->location }} 
                        </option>
                        @endforeach
                    </select>
					<br>
				
				</div>
				
				{!! Form::submit('Next', ['class' => 'btn  btn-success btn-lg center-block']) !!}
	            @include('errors.list')
	            {!! Form::close() !!}

	            @if (isset($success))
				<div class="alert alert-success" role="alert">
				  {{ $success }}
				</div>
				@endif
				@if (isset($danger))
				<div class="alert alert-danger" role="alert">
				  {{ $danger }}
				</div>
				@endif

	            <hr>
	            <a href="{{ url('afterlogin/') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
	            <br>
	            <br>
			</div>	
			</div>
		</div>
	</div>
</div>

@endsection
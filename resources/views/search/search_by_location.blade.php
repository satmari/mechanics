@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading" >Search by location barcode:
						
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
				{!! Form::open(['url' => 'search_by_location_scan']) !!}

					<div class="panel-body">
						<p>Scan location:</p>
						{!! Form::text('loc1', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
						<br>
						
						@include('errors.list')
					</div>
					
				<!-- {!! Form::close() !!} -->
				<!-- <hr> -->
				<!-- {!! Form::open(['url' => 'search_by_location_scan2']) !!} -->

					<!-- <div class="panel-body">
						
						<p>Select location manualy from the list : </p>
                        <select name="loc2" class="select form-control select-form">
                            <option value="" selected></option>
                            
                            @foreach ($locations as $m)
                            <option value="{{ $m->location }}">
                                <span >{{ $m->plant }} &nbsp;&nbsp; {{ $m->area }} &nbsp;&nbsp;&nbsp; {{ $m->location }} &nbsp;&nbsp; ({{ $m->counttt }})</span>
                            </option>
                            @endforeach
                        </select>
						<br>
						<br>
						
						@include('errors.list')
					</div> -->
					
				<!-- {!! Form::close() !!} -->
				<!-- <hr> -->
			
				<!-- {!! Form::open(['url' => 'search_by_location_scan3']) !!} -->

					<div class="panel-body">
						<!-- <p>Scan machine:</p> -->
						<p>Select location manualy from the list (search): </p>
                        <select name="loc3" id='select2' class="select form-con rol sele ct-form chose n">
                            <option value="" selected></option>
                            
                            @foreach ($locations as $m)
                            <option value="{{ $m->location }}">
                                {{ $m->plant }} &nbsp;&nbsp; {{ $m->area }} &nbsp;&nbsp;&nbsp; {{ $m->location }} &nbsp;&nbsp; ({{ $m->counttt }})
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
				
				@if(isset($data))
				<hr>
				<input type="hidden" id="_token" value="<?php echo csrf_token(); ?>">
				<table class="table table-striped table-bordered" >
					<thead>
						<th>Machine:</th>
						<th>Brand:</th>
						<th>Code:</th>
						<th>Type:</th>
						<th>Status:</th>
						<th>Location:</th>
						<th>Plant:</th>
					</thead>
					<tbody>
						@foreach ($data as $d)
						<tr>
							<td><b>{{ $d->os }}</b></td>
							<td>{{ $d->brand }}</td>
							<td>{{ $d->code }}</td>
							<td>{{ $d->type }}</td>
							<td>{{ $d->machine_status }}</td>
							<td>{{ $d->location }}</td>
							<td>{{ $d->plant }}</td>
						</tr>
						
						@endforeach
					</tbody>
				</table>
				@endif
				
				<!-- <br> -->
				<hr>
	            <a href="{{ url('search_machine/') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
	            <br>
	            <br>
				
			</div>
		</div>
	</div>
</div>
@endsection
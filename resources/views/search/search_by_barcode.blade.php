@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading" >Search by machine barcode:
						
				</div>

						@if (isset($msg))
							<div class="panel-heading" >
								<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:green"><b>{{ $msg }}</b></span></i></small>
							</div>
						@endif
						@if (isset($msge))
							<div class="panel-heading" >
								<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msge }}</b></span></i></small>
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
				
				{!! Form::open(['url' => 'search_by_barcode_scan']) !!}

					<div class="panel-body">
						<!-- <p>Scan machine:</p> -->
						{!! Form::text('machine_temp1', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
						<br>

						<!-- <p>Scan machine:</p> -->
						<p>Select machine manualy from the list: </p>
						<select name="machine_temp2" id='select2' class="select form-con rol sele ct-form cho sen s-exa mple-basic-single">
	                        <option value="" selected></option>
	                        
	                        @foreach ($machines as $m)
	                        <option value="{{ $m->os }}">
	                            {{ $m->os }} \ {{ $m->brand }} \ {{ $m->type }} \ {{ $m->code}}
	                        </option>
	                        @endforeach
	                    </select>
						<hr>

						{!! Form::submit('Search', ['class' => 'btn btn-success btn-lg center-block']) !!}
						@include('errors.list')
					</div>

				{!! Form::close() !!}

				@if(isset($data))
				<hr>
				<input type="hidden" id="_token" value="<?php echo csrf_token(); ?>">
				<table class="table table-striped table-bordered" >
					<tbody>
						@foreach ($data as $d)
						<tr>
							<td>Machine:</td>
							<td><b>{{ $d->os }}</b></td>
						</tr>
						<tr>
							<td>Brand:</td>
							<td><b>{{ $d->brand }}</b></td>
						</tr>
						<tr>
							<td>Code:</td>
							<td><b>{{ $d->code }}</b></td>
						</tr>
						<tr>
							<td>Type:</td>
							<td><b>{{ $d->type }}</b></td>
						</tr>
						<tr>
							<td>Status:</td>
							<td><b>{{ $d->machine_status }}</b></td>
						</tr>
						<tr>
							<td>Location:</td>
							<td><b>{{ $d->location }}</b></td>
						</tr>
						<tr>
							<td>Plant:</td>
							<td><b>{{ $d->plant }}</b></td>
						</tr>
						<tr>
							<td>Remark Subotica:</td>
							<td><b>{{ $d->remark_su }}</b></td>
						</tr>
						<tr>
							<td>Remark Kikinda:</td>
							<td><b>{{ $d->remark_ki }}</b></td>
						</tr>
						
						@endforeach
					</tbody>
				</table>
				@endif

				<hr>
				<table class="table table-striped table-bordered" >
					<thead>
						<th>Date</th>
						<th>User</th>
						<th>Comment</th>
					</thead>
					@if (isset($comments) AND (!empty($comments)))
					<tbody>
						@foreach ($comments as $d)
						<tr>
							<td>{{ substr($d->updated_at,0,16) }} </td>
							<td>{{ $d->user }} </td>
							<td>{{ $d->comment }} </td>
						</tr>
						@endforeach
					</tbody>
					@else
					No comments for this machine
					@endif	
				</table>
				
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
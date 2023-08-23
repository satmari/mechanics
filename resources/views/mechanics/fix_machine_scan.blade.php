@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading" >Scan machine - 
						@if (isset($fix_machine_to))
							<b><i>REPAIR MACHINE IN
							@if ($fix_machine_to == 'REPAIRING_SU') 
							SUBOTICA
							@elseif ($fix_machine_to == 'REPAIRING_KI')
							KIKINDA
							@elseif ($fix_machine_to == 'REPAIRING_SE')
							SENTA
							@endif
							
							</i></b>
						@endif
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
				
				{!! Form::open(['url' => 'fix_machine_scan']) !!}

					{!! Form::hidden('fix_machine_to', $fix_machine_to) !!}
					{!! Form::hidden('session', $session) !!}
					
				<div class="panel-body">
					<p>Scan machine:</p>
					{!! Form::text('machine_temp1', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
					<br>

					<!-- <p>Scan machine:</p> -->
					<p>Select machine manualy from the list: </p>
					<p><i>Filters included. Status = 'ON_REPAIR'</i></p>
                    <select name="machine_temp2" id='select2' class="select form-con rol sele ct-form chos en">
                        <option value="" selected></option>
                        
                        @foreach ($machines as $m)
                        <option value="{{ $m->os }}">
                            {{ $m->os }} \ {{ $m->brand }} \ {{ $m->type }} \ {{ $m->code}}
                        </option>
                        @endforeach
                    </select>
					<hr>

					{!! Form::submit('Confirm machine', ['class' => 'btn btn-info btn-lg center-block']) !!}
					@include('errors.list')
				</div>
				{!! Form::close() !!}

				@if(isset($data) AND (!empty($data)))
				<hr>
				<input type="hidden" id="_token" value="<?php echo csrf_token(); ?>">
				<table class="table table-striped table-bordered" >
					<tbody>
						@foreach ($data as $d)
						<tr>
							<td><b>{{ $d->os }} / {{ $d->brand }} / {{ $d->type }} / {{ $d->code }}</b>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="{{ url('fix_machine_remove/'.$d->id.'/'.$d->ses) }}"><big><strong><span style="color:red">X</span></strong></big></a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>

				<!-- <br> -->
				
				@endif

				<!-- <br>	 -->
				<hr>		
				<div>
				@if (!empty($data))
				<a href="{{ url('fix_machine_confirm/'.$session) }}" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-ok">&nbsp;</span>Confirm list</a>&nbsp;&nbsp;&nbsp;
				@else
				<a href="{{ url('fix_machine_confirm/'.$session) }}" class="btn btn-lg btn-success disabled"><span class="glyphicon glyphicon-ok">&nbsp;</span>Confirm list</a>&nbsp;&nbsp;&nbsp;
				@endif
				<a href="{{ url('fix_machine_cancel/'.$session) }}" class="btn btn-lg btn-danger"><span class="glyphicon glyphicon-remove">&nbsp;</span>Cancel and return</a>
				</div>
				<br>
				
				
		</div>
	</div>
</div>
@endsection
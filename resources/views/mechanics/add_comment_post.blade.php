@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading" >Add comment to machine 
					@if (isset($machine))
						<b>{{ $machine }}</b>:
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
				       	</div>
					@endif
					@if (isset($msgs))
						<div class="panel-heading" >
							<small><i>&nbsp;&nbsp;&nbsp; Msg: <span style="color:green"><b>{{ $msgs }}</b></span></i></small>
				       	</div>
					@endif
					@if (isset($msgbin))
						<div class="panel-heading" >
							<small><i>&nbsp;&nbsp;&nbsp; Msg: <span style="color:red"><b>{{ $msgbin }}</b></span></i></small>
				       	</div>
					@endif

				@if (isset($machine))

					{!! Form::open(['url' => 'add_comment_post']) !!}

							{!! Form::hidden('machine', $machine) !!}

						<div class="panel-body">
							<p>Add comment:</p>
							{!! Form::textarea('comment', null, ['class' => 'form-control','autofocus' => 'autofocus','placeholder' => 'Write comment here...',  'size' => '30x3']) !!}
							<br>

							{!! Form::submit('Save comment', ['class' => 'btn btn-danger btn-lg center-block']) !!}
							
							
							@include('errors.list')
						</div>
						
					{!! Form::close() !!}
				
				
	            @endif

	            <div class="panel-heading" >Info about machine: <br>Location:<b> {{ $location }} </b> , Area: <b>{{ $area }}</b> , Plant: <b>{{ $plant }}</b> , Status: <b>{{ $machine_status }} </b></a></div>
				<div class="panel-heading" >Remark Inteos Subotica: <br><b>{{ $remark_su }}</b> <br> Remark Inteos Kikinda: <br><b>{{ $remark_ki }}</b> </a></div>
				<div class="panel-heading" >Machine: <b>{{ $machine }}</b> , Brand: <b>{{ $brand }}</b> , Type: <b>{{ $type }}</b> , Code: <b>{{ $code }}</b></a></div>

		            
		            <!-- <hr> -->
														
							<input type="hidden" id="_token" value="<?php echo csrf_token(); ?>">
							<table class="table table-striped table-bordered" >
								<thead>
									<th>Date</th>
									<th>User</th>
									<th>Comment</th>
									<th>Delete</th>
								</thead>
								@if (isset($comments) AND (!empty($comments)))
								<tbody>
									@foreach ($comments as $d)
									<tr>
										<td>{{ substr($d->updated_at,0,16) }} </td>
										<td>{{ $d->user }} </td>
										<td>{{ $d->comment }} </td>
										<td><a href="{{ url('/delete_comment_post/'.$d->id) }}" class="">Delete</a></td>
									</tr>
									@endforeach
								</tbody>
								@else
								No comments for this machine
								@endif	
							</table>
						


					<hr>
					<a href="{{ url('/add_comment') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
					<br>
					<br>
				
			</div>
		</div>
	</div>
</div>
@endsection
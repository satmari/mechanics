@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading" >Add machine information

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

					<div class="panel-heading" >Info about machine: <br>Location:<b> {{ $location }} </b> , Area: <b>{{ $area }}</b> , Plant: <b>{{ $plant }}</b> , Status: <b>{{ $machine_status }} </b></a></div>
					<div class="panel-heading" >Machine: <b>{{ $machine }}</b> , Brand: <b>{{ $brand }}</b> , Type: <b>{{ $type }}</b> , Code: <b>{{ $code }}</b></a></div>

					{!! Form::open(['url' => 'add_comment_ws_post']) !!}
							{!! Form::hidden('machine', $machine) !!}

						<div class="panel-body">
							<p>Add workstudy info for this machine:</p>

							{!! Form::textarea('comment_ws', $comment_ws, ['class' => 'form-control','autofocus' => 'autofocus','placeholder' => 'Write comment here...',  'size' => '30x3']) !!}
							<br>
							{!! Form::submit('Save', ['class' => 'btn btn-danger btn-lg center-block']) !!}
							
							@include('errors.list')
						</div>
						
					{!! Form::close() !!}
	            @endif
			</div>
		</div>
	</div>
</div>
@endsection
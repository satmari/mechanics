@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading" >Add/Edit class:
						
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

				@if (!isset($brand))

					{!! Form::open(['url' => 'add_class_post']) !!}

						<div class="panel-body">
							<p>Brand:</p>
							{!! Form::text('brand', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
							<br>
						</div>

						<div class="panel-body">
							<p>Code:</p>
							{!! Form::text('code', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
							<br>
						</div>

						<div class="panel-body">
							<p>Class:</p>
							{!! Form::text('class', null, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
							<br>
						</div>

						{!! Form::submit('Save', ['class' => 'btn btn-danger center-block']) !!}
						@include('errors.list')

					{!! Form::close() !!}

				@else

					{!! Form::open(['url' => 'edit_class_post']) !!}

					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					<div class="panel-body">
						<p>Brand:</p>
						{!! Form::text('brand', $brand, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
						<br>
					</div>

					<div class="panel-body">
						<p>Code:</p>
						{!! Form::text('code', $code, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
						<br>
					</div>

					<div class="panel-body">
						<p>Class:</p>
						{!! Form::text('class', $class, ['class' => 'form-control','autofocus' => 'autofocus']) !!}
						<br>
					</div>

					{!! Form::submit('Save', ['class' => 'btn btn-danger center-block']) !!}
					@include('errors.list')

				{!! Form::close() !!}

				@endif
			
				<!-- <br> -->
				<hr>
	            <a href="{{ url('/afterlogin') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
	            <br>
	            <br>
				
			</div>
		</div>
	</div>
</div>
@endsection
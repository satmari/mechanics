@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center col-md-3 col-md-offset-4">
			<div class="panel panel-default">
				
				
						<div class="panel-heading">Upload class image</div>
						<p></p>

						{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importImageController@upload_class_image']]) !!}
							<div class="panel-body">
								{!! Form::file('file', ['class' => 'center-block']) !!}

								{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
								{!! Form::hidden('brand', $brand, ['class' => 'form-control']) !!}
								{!! Form::hidden('code', $code, ['class' => 'form-control']) !!}
								{!! Form::hidden('class', $class, ['class' => 'form-control']) !!}
							</div>
							<div class="panel-body">
								{!! Form::submit('Upload', ['class' => 'btn btn-warning center-block']) !!}
							</div>
							@include('errors.list')
						{!! Form::close() !!}
				
			</div>
		</div>
	</div>
</div>

@endsection
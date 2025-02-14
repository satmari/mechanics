@extends('app')

@section('content')
<div class="container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Edit machine information:</div>
				<br>
					{!! Form::model($data , ['method' => 'POST', 'url' => 'machine_edit_post']) !!}

					{!! Form::hidden('id', $data[0]->id, ['class' => 'form-control']) !!}
					
					<div class="panel-body">
					<p>OS: {{ $data[0]->os }}</p>
						
					<div class="panel-body">
						{!! Form::submit('Save', ['class' => 'btn btn-success center-block']) !!}
					</div>
					<br>

					@include('errors.list')
					{!! Form::close() !!}
					
					
				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/padprint_conf')}}" class="btn btn-default">Back</a>
					</div>
				</div>
					
			</div>
		</div>
	</div>
</div>

@endsection
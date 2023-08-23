@extends('app')

@section('content')
<div class="container container-table">
	<div class="row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Import controller</div>
				<h3 style="color:red;"></h3>
				<p style="color:red;"></p>

				<div class="panel panel-default">
					<div class="panel-heading">Import remark in Inteos</div>
					
					{!! Form::open(['files'=>'True', 'method'=>'POST', 'action'=>['importController@postUpdateRemark'] ]) !!}
						<div class="panel-body">
							{!! Form::file('file1', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						
					{!! Form::close() !!}
				</div>
							

			</div>
		</div>
		
	</div>
</div>


@endsection
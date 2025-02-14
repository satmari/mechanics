@extends('app')

@section('content')
<div class="container container-table">
	<div class="row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Import controller</div>
				<h3 style="color:red;"></h3>
				<p style="color:red;"></p>

				
				<!-- <div class="panel panel-default">
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

				<div class="panel panel-default">
					<div class="panel-heading">Import machine information</div>
					
					{!! Form::open(['files'=>'True', 'method'=>'POST', 'action'=>['importController@postUpdateInfo'] ]) !!}
						<div class="panel-body">
							{!! Form::file('file2', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						
					{!! Form::close() !!}
				</div> -->
				

				<div class="panel panel-default">
					<div class="panel-heading">Import machines</div>
					<br>
					<p>Excel file should contain two columns with headers  os and class.</p>
					<p>os => OS000001 OR 10000001...</p>
					<p>class => id of class table, like 201</p>
					<hr>
					Please check if class is already present in Class table, if not please create class first.
					<hr>
					<p>For Temporary machines please use os code <b>FROM {{ $pr_no }}</b></p>
					<hr>
					
					{!! Form::open(['files'=>'True', 'method'=>'POST', 'action'=>['importController@postImportMachines'] ]) !!}
						<div class="panel-body">
							{!! Form::file('file3', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import machines', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						
					{!! Form::close() !!}
				</div>
							

			</div>
		</div>
		
	</div>
</div>


@endsection
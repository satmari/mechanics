@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Class table</div>

				@if((Auth::check() && Auth::user()->name == "admin") OR ( Auth::check() && Auth::user()->name == "mechanics"))
					<a href="{{ url('add_class') }}" class="btn btn-info btn-xs ">Add new Class</a>
				@endif

                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>
                <table class="table table-striped table-bordered" id="sort" 
                data-show-export="true"
                data-export-types="['excel']"
                >
                <!--
                data-show-export="true"
                data-export-types="['excel']"
                data-search="true"
                data-show-refresh="true"
                data-show-toggle="true"
                data-query-params="queryParams" 
                data-pagination="true"
                data-height="300"
                data-show-columns="true" 
                data-export-options='{
                         "fileName": "preparation_app", 
                         "worksheetName": "test1",         
                         "jspdf": {                  
                           "autotable": {
                             "styles": { "rowHeight": 20, "fontSize": 10 },
                             "headerStyles": { "fillColor": 255, "textColor": 0 },
                             "alternateRowStyles": { "fillColor": [60, 69, 79], "textColor": 255 }
                           }
                         }
                       }'
                -->
				    <thead>
				        <tr>
				           <th>Id</th>
				           
				           <th>Brand</th>
				           <th>Code</th>
				           <th>Class</th>
				           <th>Number fo Machines</th>
				           <th></th>
				           <th>Image name</th>
				           <th></th>
				           <th></th>
				           
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    @foreach ($data as $d)
				        <tr>
				        	<td>{{ $d->IntKey }}</td>
				        	<td>{{ $d->Brand }}</td>
				        	<td>{{ $d->MaCod }}</td>
				        	<td>{{ $d->MaTyp }}</td>
				        	<td>{{ $d->count_machine }}</td>
				        	<td> <a href="{{ url('/public/storage/ClassImages/'.$d->image ) }}" target="_blank" onClick="javascript:window.open('{{ url('/public/storage/ClassImages/'.$d->image ) }}','Windows','width=650,height=350,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no');return false" ) >show image</a> </td>
							<td>{{ $d->image }}</td>
							<td>
				        	@if(Auth::check())
								{!! Form::open(['url' => 'upload_image']) !!}

									{!! Form::hidden('IntKey', $d->IntKey) !!}
									{!! Form::hidden('brand', $d->Brand) !!}
									{!! Form::hidden('code', $d->MaCod) !!}
									{!! Form::hidden('class', $d->MaTyp) !!}
									{!! Form::submit('Upload image', ['class' => 'btn btn-info btn-xs center-block']) !!}
									
								{!! Form::close() !!}

				        	@endif
				        	</td>
							<td><a href="{{ url('edit_class/'.$d->IntKey) }}" class="btn btn-danger btn-xs">Edit Class</a></td>
							
							
						</tr>
				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection

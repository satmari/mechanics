@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Edit location</b><br>
					<a href="{{ url('remove_location/'.$data->id) }}" style="color:red">Set as inactive</a>
				</div>
				
				
					{!! Form::open(['url' => 'edit_location_post/'.$data->id]) !!}
					
					<div class="panel-body">
						<p>Location name:<span style="color:red;">*</span></p>
	               		{!! Form::input('string', 'location', $data->location, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
	                <p>Area: <span style="color:red;">*</span></p>
	                    <select name="area_id" class="chosen" style="width:200px !important">
	                    	
		                    @foreach ($areas as $line)
		                    	@if ($line->id == $data->area_id)
		                    	<option value="{{ $line->id }}" selected>{{ $line->plant }} - {{ $line->area }}</option>
		                    	@else

		                        <option value="{{ $line->id }}">{{ $line->plant }} - {{ $line->area }}</option>

		                        @endif
		                    @endforeach
	                    </select>
	                </div>
					
					<div class="panel-body">
						{!! Form::submit('Confirm', ['class' => 'btn btn-success btn-lg center-block']) !!}
					</div>

					@include('errors.list')

					{!! Form::close() !!}

				
				<br>
				
			</div>
		</div>
	</div>
</div>
@endsection

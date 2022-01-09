@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Edit Area</div>
				
				
					{!! Form::open(['url' => 'edit_area_post/'.$data->id]) !!}
					
					<div class="panel-body">
						<p>Area:<span style="color:red;">*</span></p>
	               		{!! Form::input('string', 'area', $data->area, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
	                <p>Plant: <span style="color:red;">*</span></p>
	                    <select name="plant_id" class="chosen" style="width:200px !important">
	                    	
		                    @foreach ($plants as $line)
		                    	@if ($line->id == $data->plant_id)
		                    	<option value="{{ $line->id }}" selected>{{ $line->plant }}</option>
		                    	@else

		                        <option value="{{ $line->id }}">{{ $line->plant }}</option>

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

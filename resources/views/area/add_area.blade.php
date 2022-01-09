@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Add new Area</b></div>
				
				{!! Form::open(['url' => 'add_area_post']) !!}
				
				<div class="panel-body">
				<p>Area name: </p>
					{!! Form::text('area', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>

				<div class="panel-body">
                <p>Plant: <span style="color:red;">*</span></p>
                    <select name="plant_id" class="chosen" style="width:200px !important">
                        <option value="" selected disabled>Choose plant</option>
	                    @foreach ($plants as $line)
	                        <option value="{{ $line->id }}">
	                            {{ $line->plant }}
	                        </option>
	                    @endforeach
                    </select>
                </div>

				
				<div class="panel-body">
					{!! Form::submit('Confirm', ['class' => 'btn btn-success btn-lg center-block']) !!}
				</div>

				@include('errors.list')

				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
</div>
@endsection
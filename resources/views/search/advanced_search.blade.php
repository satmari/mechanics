@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Advanced search <br>
					<i>Please refresh page before each search, because sometimes filter stay active if you press back button </i></b></div>
				
				{!! Form::open(['url' => 'advanced_search_post']) !!}
				
				<div class="panel-body">
				<p>OS: </p>
					{!! Form::text('os', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>

				<div class="panel-body">
				<p>Brand: </p>
					{!! Form::text('brand', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>

				<div class="panel-body">
				<p>Machine code: </p>
					{!! Form::text('code', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>

				<div class="panel-body">
				<p>Machine type: </p>
					{!! Form::text('type', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<div class="panel-body">
                <p>Plant: </p>
                    <select name="plant" class="select" id='select3' style="width:300px">
                        <option value="" selected ></option>
	                    @foreach ($plants as $line)
	                        <option value="{{ $line->plant }}">
	                            {{$line->plant}}
	                        </option>
	                    @endforeach
                    </select>
                </div>

				<div class="panel-body">
                <p>Area: </p>
                    <select name="area" class="select" id='select4' style="width:300px">
                        <option value="" selected ></option>
	                    @foreach ($areas as $line)
	                        <option value="{{ $line->area }}">
	                            {{$line->area}}
	                        </option>
	                    @endforeach
                    </select>
                </div>
				<div class="panel-body">
                <p>Location: </p>
                    <select name="location" class="select" id='select5' style="width:300px">
                        <option value="" selected ></option>
	                    @foreach ($locations as $line)
	                        <option value="{{ $line->location }}">
	                            {{$line->location}}
	                        </option>
	                    @endforeach
                    </select>
                </div>

				<div class="panel-body">
                <p>Status: </p>
                    <select name="machine_status" class="select" id='select6' style="width:300px">
                        <option value="" selected ></option>
	                    @foreach ($statuses as $line)
	                        <option value="{{ $line->machine_status }}">
	                            {{$line->machine_status}}
	                        </option>
	                    @endforeach
                    </select>
                </div>
                
                <div class="panel-body">
				<p>Gauge: </p>
					{!! Form::text('gauge', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<div class="panel-body">
				<p>Gadget: </p>
					{!! Form::text('gadget', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<div class="panel-body">
				<p>Ten. dev. small brand: </p>
					{!! Form::text('el_dev_small_brand', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<div class="panel-body">
				<p>Ten. dev. big brand: </p>
					{!! Form::text('el_dev_big_brand', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>

				<div class="panel-body">
                <p>Puller: </p>
                    <select name="puller" class="form-control">
                        <option value="" selected ></option>
                        <option value="1" >Yes</option>
                        <option value="0" >No</option>
	                </select>
                </div>

				<div class="panel-body">
                <p>Rollers: </p>
                    <select name="rollers" class="form-control">
                        <option value="" selected ></option>
                        <option value="1" >Yes</option>
                        <option value="0" >No</option>
	                </select>
                </div>

				<hr>

				<div class="panel-body">
					{!! Form::submit('Search', ['class' => 'btn btn-success btn-lg center-block']) !!}
				</div>

				@include('errors.list')

				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
</div>
@endsection
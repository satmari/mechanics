@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading" >Add machine information: 
					@if (isset($machine))
						<b>{{ $machine }}</b>:
					@endif
						
				</div>
				
				<div class="panel-heading" >Info about machine: <br>Location:<b> {{ $location }} </b> , Area: <b>{{ $area }}</b> , Plant: <b>{{ $plant }}</b> , Status: <b>{{ $machine_status }} </b></a></div>
				<div class="panel-heading" >Brand: <b>{{ $brand }}</b> , Type: <b>{{ $type }}</b> , Code: <b>{{ $code }}</b></a></div>
				<!-- <div class="panel-heading" >Remark Inteos Subotica: <br><b>{{ $remark_su }}</b> <br> Remark Inteos Kikinda: <br><b>{{ $remark_ki }}</b> </a></div> -->
				

						
					@if (isset($msg))
						<div class="panel-heading" >
							<small><i>&nbsp;&nbsp;&nbsp; Msg: <span style="color:green"><b>{{ $msg }}</b></span></i></small>
						</div>
					@endif
					@if (isset($msge))
						<div class="panel-heading" >
							<small><i>&nbsp;&nbsp;&nbsp; Msg: <span style="color:red"><b>{{ $msge }}</b></span></i></small>
				       	</div>
					@endif
					@if (isset($msgs))
						<div class="panel-heading" >
							<small><i>&nbsp;&nbsp;&nbsp; Msg: <span style="color:green"><b>{{ $msgs }}</b></span></i></small>
				       	</div>
					@endif
					@if (isset($msgbin))
						<div class="panel-heading" >
							<small><i>&nbsp;&nbsp;&nbsp; Msg: <span style="color:red"><b>{{ $msgbin }}</b></span></i></small>
				       	</div>
					@endif

				@if (isset($machine))

					{!! Form::open(['url' => 'add_info_post']) !!}
						{!! Form::hidden('machine', $machine) !!}

						@if (isset($gauge_validation[0]->gauge))
							<div class="panel-body"><big>Gauge:</big>
								<select name="gauge" class="form-control" >
			                        <option value=""></option>
			                        @foreach ($gauge_validation as $line)
				                    	 @if ($gauge == $line->gauge)
				                    		<option value="{{ round($line->gauge,1) }}" selected >{{ round($line->gauge,1) }}</option> 
				                    	 @else 
				                        	<option value="{{ round($line->gauge,1) }}">{{ round($line->gauge,1) }}</option>
				                        @endif
				                    @endforeach
			                    </select>
							</div>
						@else
							<div class="panel-body">Gauge:
								<select name="gauge" class="form-control" disabled >
			                        <option value="" selected disabled></option>
			                    </select>
							</div>
						@endif
						<hr>

						<div class="panel-body"><big>Gadget:</big>
							<select name="gadget" class="form-control" >
		                        <option value=""></option>
		                        @foreach ($gadget_validation as $line)
			                    	 @if ($gadget == $line->gadget)
			                    		<option value="{{  $line->gadget }}" selected >{{ $line->gadget }}</option> 
			                    	 @else 
			                        	<option value="{{ $line->gadget }}">{{$line->gadget}}</option>
			                        @endif
			                    @endforeach
			                </select>
						</div>
						<hr>

						<div class="panel-body"><big>Tension Device Small:</big>
							<table class='t able'>
								<tr>
									<td>Brand</td>
									<td>Quantity</td>
								</tr>
								<tr>
									<td>
										<select name="el_dev_small_brand" class="form-control" >
					                        <option value="" ></option>
					                        					                        	
						                    @foreach ($el_dev_validation as $line)
						                    	@if ($el_dev_small_brand == $line->el_dev)
						                    		<option value="{{  $line->el_dev }}" selected >{{ $line->el_dev }}</option> 
						                    	@else
						                        	<option value="{{ $line->el_dev }}">{{$line->el_dev}}</option>
						                        @endif
							            	@endforeach
						                </select>
									</td>
									<td>
										<select name="el_dev_small_quantity" class="form-control" >
											
											<option value="" ></option>
											<option value="1" @if ($el_dev_small_quantity == 1) selected @endif >1</option>
											<option value="2" @if ($el_dev_small_quantity == 2) selected @endif >2</option>
											<option value="3" @if ($el_dev_small_quantity == 3) selected @endif >3</option>
										</select>
									</td>
								</tr>

							</table>
						</div>
						<hr>

						<div class="panel-body"><big>Tension Device Big:</big>
							<table class='t able'>
								<tr>
									<td>Brand</td>
									<td>Quantity</td>
								</tr>
								<tr>
									<td>
										<select name="el_dev_big_brand" class="form-control" >
					                          <option value="" ></option>
					                        					                        	
						                    @foreach ($el_dev_validation as $line)
						                    	@if ($el_dev_big_brand == $line->el_dev)
						                    		<option value="{{  $line->el_dev }}" selected >{{ $line->el_dev }}</option> 
						                    	@else
						                        	<option value="{{ $line->el_dev }}">{{$line->el_dev}}</option>
						                        @endif
							            	@endforeach
						                </select>
									</td>
									<td>
										<select name="el_dev_big_quantity" class="form-control" >
											
											<option value="" ></option>
											<option value="1" @if ($el_dev_big_quantity == 1) selected @endif >1</option>
											<option value="2" @if ($el_dev_big_quantity == 2) selected @endif >2</option>
											<option value="3" @if ($el_dev_big_quantity == 3) selected @endif >3</option>
										</select>
									</td>
								</tr>

							</table>
						</div>
						<hr>

						<div class="panel-body">
							<table class='t able'>
								<tr>
									<td>Puller</td>
									<td>Rollers</td>
								</tr>
								<tr>
									<td>
										<div class="form-check form-switch">
											<br>
											@if ($puller == 0)
												<input style="transform:scale(2.5)" name='puller' class="form-check-input" type="checkbox" id="puller">
											@else
												<input style="transform:scale(2.5)" name='puller' class="form-check-input" type="checkbox" id="puller" checked>
											@endif
										</diV>
									</td>
									<td>
										<div class="form-check form-switch">
											<br>
											@if ($rollers == 0)
												<input style="transform:scale(2.5)" name='rollers' class="form-check-input" type="checkbox" id="rollers">
											@else
												<input style="transform:scale(2.5)" name='rollers' class="form-check-input" type="checkbox" id="rollers" checked>
											@endif
										</diV>
									</td>
								</tr>

							</table>
						</div>

					<hr>
					<br>
					<br>

					{!! Form::submit('Save', ['class' => 'btn btn-danger btn-lg center-block']) !!}
					@include('errors.list')
					{!! Form::close() !!}
				
	            @endif

	

				<hr>
				<a href="{{ url('afterlogin/') }}" class="btn btn-default btn-lg center-bl ock">Back</a>
				<br>
				<br>
				
			</div>
		</div>
	</div>
</div>
@endsection
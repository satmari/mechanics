@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Mechanic: {{ $mechanic }}</div>

				@if (isset($msge))
					<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:red"><b>{{ $msge }}</b></span></i></small>
					<audio autoplay="true" style="display:none;">
			        	<!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
			       	</audio>
				@endif
				@if (isset($msgs))
					<small><i>&nbsp &nbsp &nbsp Msg: <span style="color:green"><b>{{ $msgs }}</b></span></i></small>
					<audio autoplay="true" style="display:none;">
			        	<!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
			       	</audio>
				@endif
					<!-- </div> -->

				@if(isset($msg))
				{{ $msg }}
				@endif

				@if (Auth::guest())
					<p>Please login first.</p>
				@else

					@if (Auth::user()->name == 'mechanics' OR Auth::user()->name == 'admin')
					<br>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/search_machine')}}" class="btn btn-suc cess btn-lg center-block" style="background-color: #e3e3e3">
								<span class="glyphicon glyphicon-search" aria-hidden="true">&nbsp;</span>SEARCH MACHINE<br><i>(info about machine)</i><br></a>
						</div>
					</div>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/move_machine_in_plant')}}" class="btn btn-success btn-lg center-block">
								<span class="glyphicon glyphicon-import" aria-hidden="true">&nbsp;</span>MOVE MACHINE <br><i>(move in the same plant)</i><br></a>
						</div>
					</div>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/transfer_machine')}}" class="btn btn-primary btn-lg center-block">
								<span class="glyphicon glyphicon-sort" aria-hidden="true">&nbsp;</span>TRANSFER MACHINE <br> <i>(move between plants)</i><br></a>
						</div>
					</div>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/borrow_machine')}}" class="btn btn-warning btn-lg center-block">
								<span class="glyphicon glyphicon-new-window" aria-hidden="true">&nbsp;</span>BORROW MACHINE <br><i>(Lazarevac, Fiorano, Comprom...)</i><br></a>
						</div>
					</div>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/repair_machine')}}" class="btn btn-info btn-lg center-block">
								<span class="glyphicon glyphicon-wrench" aria-hidden="true">&nbsp;</span>REPAIR MACHINE <br><i>(repair and return machine)</i><br></a>
						</div>
					</div>
					
					<div class="panel-body">
						<div class="">
							<a href="{{url('/disable_machine')}}" class="btn btn-danger btn-lg center-block">
							<span class="glyphicon glyphicon-trash" aria-hidden="true">&nbsp;</span>DISABLE MACHINE <br><i>(write-off or sell machine)</i><br></a>
						</div>
					</div>
					<!--
					<div class="panel-body">
						<div class="">
							<a href="{{url('/#')}}" class="btn btn-li nk btn-lg center-block" style="background-color: #edd7d6">
							<span class="glyphicon glyphicon-tags" aria-hidden="true">&nbsp;</span>MANAGE FLAG/ATTACHMENT <br><i></i><br></a>
						</div>
					</div> -->
					<div class="panel-body">
						<div class="">
							<a href="{{url('/add_comment')}}" class="btn btn-li nk btn-lg center-block" style="background-color: #d6ede2"><span class="glyphicon glyphicon-pencil" aria-hidden="true">&nbsp;</span>ADD COMMENT TO THE <br>MACHINE<i></i><br></a>
						</div>
					</div>
					
					@endif
					
					
					@if (Auth::user()->name == 'magacin')

					<div class="panel-body">
						<div class="">
							<a href="{{url('/transfer_machine')}}" class="btn btn-primary btn-lg center-block">
								<span class="glyphicon glyphicon-sort" aria-hidden="true">&nbsp;</span>TRANSFER MACHINE <br> <i>(move between plants)</i><br></a>
						</div>
					</div>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/borrow_machine')}}" class="btn btn-warning btn-lg center-block">
								<span class="glyphicon glyphicon-new-window" aria-hidden="true">&nbsp;</span>BORROW MACHINE <br><i>(Lazarevac, Fiorano, Comprom...)</i><br></a>
						</div>
					</div>


					@endif

				@endif
			</div>
		</div>
	</div>
</div>
@endsection

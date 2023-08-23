@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Delete comment:</div>
				
					<br>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/delete_comment_post_confirm/'.$id )}}" class="btn btn-danger ">Confirm</a>
						</div>
					</div>
					
					<div class="panel-body">
						<div class="">
							<a href="{{url('/add_comment' )}}" class="btn btn-success ">Cancel</a>
						</div>
					</div>
					
	            
	            <br>
	            <br>

			</div>
		</div>
	</div>
</div>
@endsection

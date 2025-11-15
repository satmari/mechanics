@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit Guide Location:
                </div>

                @if(isset($msge))
                    <div class="alert alert-danger">{{ $msge }}</div>
                @endif
                @if(isset($msgs))
                    <div class="alert alert-success">{{ $msgs }}</div>
                @endif

                {!! Form::open(['url' => 'guide_location_edit_post']) !!}

                    <div class="panel-body">
                        {!! Form::hidden('id', $id) !!}

                        <p>Location:</p>
                        {!! Form::text('location', $location, ['class' => 'form-control']) !!}
                        <br>

                        {!! Form::submit('Save', ['class' => 'btn btn-danger btn-lg center-block']) !!}
                    </div>

                {!! Form::close() !!}

              <!--   <hr>
                <a href="{{ url('guide_location_table') }}" class="btn btn-default btn-lg center-block">Back</a>
                <br><br> -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = form.querySelector('input[type="submit"]');

    submitButton.addEventListener('click', function(event) {
        const password = prompt("Enter password to confirm update:");
        if (password !== "1234") {
            event.preventDefault();
            alert("Incorrect password. Update canceled.");
        }
    });
});
</script>

@endsection

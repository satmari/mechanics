@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add or remove Guide: choose guide
                </div>

                @if(isset($msge))
                    <div class="alert alert-danger">{{ $msge }}</div>
                @endif
                @if(isset($msgs))
                    <div class="alert alert-success">{{ $msgs }}</div>
                @endif

                {!! Form::open([
                    'url' => url('guides_add_remove_1'),
                    'method' => 'POST',
                    'class' => 'form-horizontal'
                    
                ]) !!}
                
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="panel-body">

                        {{-- Guide Code --}}
                        <div class="form-group">
                            {!! Form::label('guide_id', 'Guide', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('guide_id', $guides, isset($data->guide_id) ? $data->guide_id : null, ['class'=>'form-control', 'placeholder'=>'Select Guide']) !!}
                            </div>
                        </div>

                 
                        {{-- Submit --}}
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-10">
                                {!! Form::submit('Next', ['class'=>'btn btn-success']) !!}
                                <a href="{{ url('guides') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>

                    </div>

                {!! Form::close() !!}

                <!-- <hr>
                <a href="{{ url('guides') }}" class="btn btn-default btn-lg center-block">Back</a>
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

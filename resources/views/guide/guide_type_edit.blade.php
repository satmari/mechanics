@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit Guide Type Information:
                </div>

                @if(isset($msge))
                    <div class="alert alert-danger">{{ $msge }}</div>
                @endif
                @if(isset($msgs))
                    <div class="alert alert-success">{{ $msgs }}</div>
                @endif

                {!! Form::open(['url' => 'guide_type_edit_post']) !!}

                    <div class="panel-body">
                        {!! Form::hidden('id', $id) !!}

                        <p>Guide Type:</p>
                        {!! Form::text('guide_type', $guide_type, ['class' => 'form-control', 'required' => true]) !!}
                        <br>

                        <p>Description:</p>
                        {!! Form::textarea('description', $description, ['class' => 'form-control', 'rows' => 4]) !!}
                        <br>

                        {!! Form::submit('Update', ['class' => 'btn btn-primary btn-lg center-block']) !!}
                    </div>

                {!! Form::close() !!}

               <!--  <hr>
                <a href="{{ url('guide_type_table') }}" class="btn btn-default btn-lg center-block">Back</a>
                <br><br> -->
            </div>
        </div>
    </div>
</div>
@endsection

@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add or reduce Guide stock: {{ $guide->guide_code }}
                </div>

                @if(isset($msge))
                    <div class="alert alert-danger">{{ $msge }}</div>
                @endif
                @if(isset($msgs))
                    <div class="alert alert-success">{{ $msgs }}</div>
                @endif
                
                {!! Form::open([
                    'url' => url('guides_add_remove_post'),
                    'method' => 'POST',
                    'class' => 'form-horizontal'
                    
                ]) !!}
                
                {!! Form::hidden('guide_id', $guide->id) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="panel-body">

                    {{-- Destination plant --}}
                    <div class="form-group">
                        {!! Form::label('target_plant', 'Destination', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('target_plant', [
                                'Subotica' => 'Subotica',
                                'Kikinda' => 'Kikinda',
                                'Senta' => 'Senta'
                            ], old('target_plant', 'Subotica'), ['class'=>'form-control']) !!}
                        </div>
                    </div>

                    {{-- Comment --}}
                    <div class="form-group">
                        {!! Form::label('comment', 'Comment', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('comment', old('comment'), ['class' => 'form-control', 'placeholder' => 'Enter comment...']) !!}
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div class="form-group">
                        {!! Form::label('qty', 'Quantity', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::number('qty', old('qty'), [
                                'class' => 'form-control',
                                'placeholder' => 'Enter POSITIVE OR NEGATIVE quantity',
                                'required' => true
                            ]) !!}
                        </div>
                    </div>

                    

                    {{-- Submit --}}
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            {!! Form::submit('Save', ['class'=>'btn btn-success']) !!}
                            <a href="{{ url('guides_transfer_2') }}" class="btn btn-default">Back</a>
                        </div>
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>


@endsection

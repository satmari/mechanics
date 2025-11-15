@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Transfer attachment: {{ $attachment->attachment_code }}
                </div>

                @if(isset($msge))
                    <div class="alert alert-danger">{{ $msge }}</div>
                @endif
                @if(isset($msgs))
                    <div class="alert alert-success">{{ $msgs }}</div>
                @endif

                {!! Form::open([
                    'url' => url('attachments_transfer_post'),
                    'method' => 'POST',
                    'class' => 'form-horizontal'
                    
                ]) !!}
                {!! Form::hidden('attachment_id', $attachment->id) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="panel-body">

                    {{-- Stock list as radio --}}
                    <div class="form-group">
                        {!! Form::label('source_plant', 'From Stock Location', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10 text-left">
                            <ul class="list-unstyled" style="padding-left: 0;">
                                @forelse ($stocks as $s)
                                    <li style="margin-bottom: 6px;">
                                        <label style="display: flex; align-items: center; gap: 10px;">
                                            {!! Form::radio(
                                                'source_plant', 
                                                $s->plant, 
                                                $s->plant === 'Subotica' ? true : false,
                                                ['style' => 'transform: scale(1.2);']
                                            ) !!}
                                            <span style="min-width: 120px; display: inline-block;">
                                                {{ $s->plant }}
                                            </span>
                                            <span>Available: {{ $s->qty }}</span>
                                        </label>
                                    </li>
                                @empty
                                    <li><em>No available stocks found.</em></li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div class="form-group">
                        {!! Form::label('transfer_qty', 'Quantity', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::number('transfer_qty', old('transfer_qty'), [
                                'min' => 1,
                                'class' => 'form-control',
                                'placeholder' => 'Enter quantity to transfer',
                                'required' => true
                            ]) !!}
                        </div>
                    </div>

                    {{-- Destination plant --}}
                    <div class="form-group">
                        {!! Form::label('target_plant', 'Transfer To', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('target_plant', [
                                'Subotica' => 'Subotica',
                                'Kikinda' => 'Kikinda',
                                'Senta' => 'Senta'
                            ], old('target_plant', 'Subotica'), ['class'=>'form-control']) !!}
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            {!! Form::submit('Transfer', ['class'=>'btn btn-success']) !!}
                            <a href="{{ url('attachments_transfer_2') }}" class="btn btn-default">Back</a>
                        </div>
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>


@endsection

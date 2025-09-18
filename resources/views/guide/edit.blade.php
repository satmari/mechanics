@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit Guide Information:
                </div>

                @if (session('success'))
                    <div class="panel-heading">
                        <small>
                            <i>&nbsp;&nbsp;&nbsp; 
                                <span style="color:green"><b>{{ session('success') }}</b></span>
                            </i>
                        </small>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="panel-body">
                        <ul class="alert alert-danger list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li><i class="glyphicon glyphicon-exclamation-sign"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {!! Form::model($data, [
                    'url' => url('guide_update_post/' . $data->id),
                    'method' => 'PUT',
                    'class' => 'form-horizontal'
                ]) !!}

                    <div class="panel-body">

                        {{-- Guide Code --}}
                        <div class="form-group">
                            {!! Form::label('guide_code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('guide_code', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Guide Description --}}
                        <div class="form-group">
                            {!! Form::label('guide_description', 'Description', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('guide_description', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Guide Type Dropdown --}}
                        <div class="form-group">
                            {!! Form::label('guide_type_id', 'Guide Type', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('guide_type_id', $guide_types, $data->guide_type_id, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Supplier Dropdown --}}
                        <div class="form-group">
                            {!! Form::label('suplier_id', 'Supplier', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('suplier_id', $suppliers, $data->suplier_id, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="form-group">
                            {!! Form::label('location', 'Location', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('location', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Machine Class --}}
                        <div class="form-group">
                            {!! Form::label('machine_class', 'Machine Class', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('machine_class', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Calz Code --}}
                        <div class="form-group">
                            {!! Form::label('calz_code', 'Calz Code', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('calz_code', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Style --}}
                        <div class="form-group">
                            {!! Form::label('style', 'Style', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('style', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Fold --}}
                        <div class="form-group">
                            {!! Form::label('fold', 'Fold', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('fold', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Operation --}}
                        <div class="form-group">
                            {!! Form::label('operation', 'Operation', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('operation', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Numeric fields --}}
                        <div class="form-group">
                            {!! Form::label('entry_mm', 'Entry (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::number('entry_mm', null, ['class'=>'form-control', 'step'=>'0.01']) !!}</div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('exit_mm', 'Exit (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::number('exit_mm', null, ['class'=>'form-control', 'step'=>'0.01']) !!}</div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('tickness_mm', 'Thickness (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::number('tickness_mm', null, ['class'=>'form-control', 'step'=>'0.01']) !!}</div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('elastic_mm', 'Elastic (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::number('elastic_mm', null, ['class'=>'form-control', 'step'=>'0.01']) !!}</div>
                        </div>

                        {{-- Picture & Video --}}
                        <div class="form-group">
                            {!! Form::label('picture', 'Picture', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('picture', null, ['class'=>'form-control']) !!}</div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('video', 'Video', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('video', null, ['class'=>'form-control']) !!}</div>
                        </div>

                       
                        {{-- Note --}}
                        <div class="form-group">
                            {!! Form::label('note', 'Note', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::textarea('note', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            {!! Form::label('status', 'Status', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('status', ['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'], null, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                         <br>
                        {{-- Quantities --}}
                        <div class="form-group">
                            <p>Stock Qty </p>
                            {!! Form::label('qty_su', 'Su Qty', ['class'=>'col-sm-1 control-label']) !!}
                            <div class="col-sm-2">{!! Form::number('qty_su', null, ['class'=>'form-control']) !!}</div>

                            {!! Form::label('qty_ki', 'Ki Qty', ['class'=>'col-sm-1 control-label']) !!}
                            <div class="col-sm-2">{!! Form::number('qty_ki', null, ['class'=>'form-control']) !!}</div>

                            {!! Form::label('qty_se', 'Se Qty', ['class'=>'col-sm-1 control-label']) !!}
                            <div class="col-sm-2">{!! Form::number('qty_se', null, ['class'=>'form-control']) !!}</div>

                            {!! Form::label('qty_valy', 'Va Qty', ['class'=>'col-sm-1 control-label']) !!}
                            <div class="col-sm-2">{!! Form::number('qty_valy', null, ['class'=>'form-control']) !!}</div>
                        </div>
                        <br>

                        {{-- Submit --}}
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-10">
                                {!! Form::submit('Update', ['class'=>'btn btn-primary']) !!}
                                <a href="{{ URL::previous() ? URL::previous() : url('guide_type_table') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>

                    </div>
                {!! Form::close() !!}

                <hr>
                <a href="{{ url('guide_type_table') }}" class="btn btn-default btn-lg cent er-block">Back</a>
                <br><br>

            </div>
        </div>
    </div>
</div>
@endsection

@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add New Guide:
                </div>

                @if(isset($msge))
                    <div class="alert alert-danger">{{ $msge }}</div>
                @endif
                @if(isset($msgs))
                    <div class="alert alert-success">{{ $msgs }}</div>
                @endif

                {!! Form::open([
                    'url' => url('guides_add'),
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data'  
                ]) !!}

                    <div class="panel-body">

                        {{-- Guide Code --}}
                        <div class="form-group">
                            {!! Form::label('guide_code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('guide_code', null, ['class'=>'form-control', 'required']) !!}</div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            {!! Form::label('guide_description', 'Description', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('guide_description', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        {{-- Guide Type --}}
                        <div class="form-group">
                            {!! Form::label('guide_type_id', 'Guide Type', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::select('guide_type_id', $guide_types, null, ['class'=>'form-control', 'placeholder'=>'Select Type']) !!}</div>
                        </div>

                        {{-- Machine Class --}}
                        <div class="form-group">
                            {!! Form::label('machine_class', 'Machine Class', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('machine_class', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        {{-- Fold --}}
                        <div class="form-group">
                            {!! Form::label('fold', 'Fold', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('fold', null, ['class'=>'form-control']) !!}</div>
                        </div>
                       
                        {{-- Entry/Exit/Thickness/Elastic --}}
                        <div class="form-group">
                            {!! Form::label('entry_mm', 'Entry (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('entry_mm', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('exit_mm', 'Exit (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('exit_mm', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('tickness_mm', 'Thickness (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('tickness_mm', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('elastic_mm', 'Elastic (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('elastic_mm', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        {{-- Style --}}
                        <div class="form-group">
                            {!! Form::label('style', 'Style', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('style', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        {{-- Operation --}}
                        <div class="form-group">
                            {!! Form::label('operation', 'Operation', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('operation', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        {{-- Notes --}}
                        <div class="form-group">
                            {!! Form::label('notes', 'Notes', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::textarea('notes', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        {{-- Supplier --}}
                        <div class="form-group">
                            {!! Form::label('suplier_id', 'Supplier', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::select('suplier_id', $suppliers, null, ['class'=>'form-control', 'placeholder'=>'Select Supplier']) !!}</div>
                        </div>

                        {{-- Location --}}
                        <div class="form-group">
                            {!! Form::label('location_g_id', 'Location', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('location_g_id', $locations, null, ['class'=>'form-control', 'placeholder'=>'']) !!}
                            </div>
                        </div>

                        {{-- Calz Code --}}
                        <div class="form-group">
                            {!! Form::label('calz_code', 'Calz Code', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('calz_code', null, ['class'=>'form-control']) !!}</div>
                        </div>

                        {{-- Picture & Video --}}
                        <div class="form-group">
                            {!! Form::label('picture', 'Picture (upload)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                <input type="file" name="picture" class="form-control">
                               
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('video', 'Video (upload)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                <input type="file" name="video" class="form-control">
                                
                            </div>
                        </div>

                        

                        {{-- Status --}}
                        <div class="form-group">
                            {!! Form::label('status', 'Status', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('status', ['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'], 'ACTIVE', ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-10">
                                {!! Form::submit('Save', ['class'=>'btn btn-success']) !!}
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

@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit Guide:
                </div>

                @if(isset($msge))
                    <div class="alert alert-danger">{{ $msge }}</div>
                @endif
                @if(isset($msgs))
                    <div class="alert alert-success">{{ $msgs }}</div>
                @endif

                {!! Form::open([
                    'url' => url('guide_update_post/'.$data->id),
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data'  
                ]) !!}

                    <div class="panel-body">

                        {{-- Guide Code --}}
                        <div class="form-group">
                            {!! Form::label('guide_code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('guide_code', $data->guide_code, ['class'=>'form-control', 'required']) !!}
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            {!! Form::label('guide_description', 'Description', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('guide_description', $data->guide_description, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Guide Type --}}
                        <div class="form-group">
                            {!! Form::label('guide_type_id', 'Guide Type', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('guide_type_id', $guide_types, $data->guide_type_id, ['class'=>'form-control', 'placeholder'=>'Select Type']) !!}
                            </div>
                        </div>

                        {{-- Machine Class --}}
                        <div class="form-group">
                            {!! Form::label('machine_class', 'Machine Class', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('machine_class', $data->machine_class, ['class'=>'form-control']) !!}
                            </div>
                        </div>


                        {{-- Fold --}}
                        <div class="form-group">
                            {!! Form::label('fold', 'Fold', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('fold', $data->fold, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Entry / Exit / Thickness / Elastic --}}
                        <div class="form-group">
                            {!! Form::label('entry_mm', 'Entry (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('entry_mm', $data->entry_mm, ['class'=>'form-control']) !!}</div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('exit_mm', 'Exit (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('exit_mm', $data->exit_mm, ['class'=>'form-control']) !!}</div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('tickness_mm', 'Thickness (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('tickness_mm', $data->tickness_mm, ['class'=>'form-control']) !!}</div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('elastic_mm', 'Elastic (mm)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::text('elastic_mm', $data->elastic_mm, ['class'=>'form-control']) !!}</div>
                        </div>

                        {{-- Style --}}
                        <div class="form-group">
                            {!! Form::label('style', 'Style', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('style', $data->style, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Operation --}}
                        <div class="form-group">
                            {!! Form::label('operation', 'Operation', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('operation', $data->operation, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="form-group">
                            {!! Form::label('notes', 'Notes', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">{!! Form::textarea('notes', $data->notes, ['class'=>'form-control']) !!}</div>
                        </div>

                        {{-- Supplier --}}
                        <div class="form-group">
                            {!! Form::label('suplier_id', 'Supplier', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('suplier_id', $suppliers, $data->suplier_id, ['class'=>'form-control', 'placeholder'=>'Select Supplier']) !!}
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="form-group">
                            {!! Form::label('location_g_id', 'Location', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('location_g_id', $locations, $data->location_g_id, ['class'=>'form-control', 'placeholder'=>'Select Location']) !!}
                            </div>
                        </div>

                        {{-- Calz Code --}}
                        <div class="form-group">
                            {!! Form::label('calz_code', 'Calz Code', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('calz_code', $data->calz_code, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Picture --}}
                        <div class="form-group">
                            {!! Form::label('picture', 'Picture (upload)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                <input type="file" name="picture" class="form-control">
                                @if(isset($data) && $data->picture)
                                    <p class="mt-2">Current: 
                                        <a href="javascript:void(0);" 
                                           onclick="window.open(
                                               '{{ asset('public/storage/GuidesFiles/' . $data->picture) }}', 
                                               '_blank', 
                                               'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600'
                                           )">
                                           {{ $data->picture }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Video --}}
                        <div class="form-group">
                            {!! Form::label('video', 'Video (upload)', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                <input type="file" name="video" class="form-control">
                                @if(isset($data) && $data->video)
                                    <p class="mt-2">Current: 
                                        <a href="javascript:void(0);" 
                                           onclick="window.open(
                                               '{{ asset('public/storage/GuidesFiles/' . $data->video) }}', 
                                               '_blank', 
                                               'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600'
                                           )">
                                           {{ $data->video }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>

                       

                        {{-- Status --}}
                        <div class="form-group">
                            {!! Form::label('status', 'Status', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('status', ['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'], $data->status, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-10">
                                {!! Form::submit('Update', ['class'=>'btn btn-primary']) !!}
                                <a href="{{ url('guides') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>

                    </div>

                {!! Form::close() !!}
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

@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit attachment:
                </div>

                @if(isset($msge))
                    <div class="alert alert-danger">{{ $msge }}</div>
                @endif
                @if(isset($msgs))
                    <div class="alert alert-success">{{ $msgs }}</div>
                @endif

                {!! Form::open([
                    'url' => url('attachment_update_post/'.$data->id),
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data'  
                ]) !!}

                    <div class="panel-body">

                        {{-- attachment Code --}}
                        <div class="form-group">
                            {!! Form::label('attachment_code', 'Code', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('attachment_code', $data->attachment_code, ['class'=>'form-control', 'required']) !!}
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            {!! Form::label('attachment_description', 'Description', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('attachment_description', $data->attachment_description, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        {{-- attachment Type --}}
                        <div class="form-group">
                            {!! Form::label('attachment_type_id', 'attachment Type', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('attachment_type_id', $attachment_types, $data->attachment_type_id, ['class'=>'form-control', 'placeholder'=>'Select Type']) !!}
                            </div>
                        </div>

                        {{-- Machine Class --}}
                        <div class="form-group">
                            {!! Form::label('machine_class', 'Machine Class', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('machine_class', $data->machine_class, ['class'=>'form-control']) !!}
                            </div>
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
                            {!! Form::label('location_a_id', 'Location', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('location_a_id', $locations, $data->location_a_id, ['class'=>'form-control', 'placeholder'=>'Select Location']) !!}
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
                                               '{{ asset('public/storage/attachmentsFiles/' . $data->picture) }}', 
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
                                               '{{ asset('public/storage/attachmentsFiles/' . $data->video) }}', 
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
                                <a href="{{ url('attachments') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>

                    </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>


// <script>
// document.addEventListener('DOMContentLoaded', function() {
//     const form = document.querySelector('form');
//     const submitButton = form.querySelector('input[type="submit"]');

//     submitButton.addEventListener('click', function(event) {
//         const password = prompt("Enter password to confirm update:");
//         if (password !== "1234") {
//             event.preventDefault();
//             alert("Incorrect password. Update canceled.");
//         }
//     });
// });
// </script>


@endsection

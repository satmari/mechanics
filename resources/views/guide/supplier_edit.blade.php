@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit Supplier Information:
                </div>

                @if (session('success'))
                    <div class="panel-heading">
                        <small><i>&nbsp;&nbsp;&nbsp; <span style="color:green"><b>{{ session('success') }}</b></span></i></small>
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

                {!! Form::open(['url' => 'supplier_edit_post']) !!}

                    <div class="panel-body">
                        {!! Form::hidden('id', $id) !!}

                        <p>Guide Type:</p>
                        {!! Form::text('supplier', $supplier, ['class' => 'form-control', 'required' => true]) !!}
                        <br>

                        <p>Description:</p>
                        {!! Form::text('location', $location, ['class' => 'form-control']) !!}
                        <br>

                        <p>Contact:</p>
                        {!! Form::text('contact', $contact, ['class' => 'form-control']) !!}
                        <br>

                        {!! Form::submit('Update', ['class' => 'btn btn-primary btn-lg center-block']) !!}
                    </div>

                {!! Form::close() !!}

                <hr>
                <a href="{{ url('guide_type_table') }}" class="btn btn-default btn-lg center-block">Back</a>
                <br><br>
            </div>
        </div>
    </div>
</div>
@endsection

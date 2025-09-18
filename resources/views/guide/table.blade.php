@extends('app')

@section('content')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <style>
        .tableFixHead {
            overflow-y: auto;
            height: 500px;
        }
        .tableFixHead thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
        }
        /* Optional: show pointer cursor for hoverable rows */
        tbody tr:hover {
            cursor: pointer;
            background-color: #f5f5f5; /* subtle highlight */
        }
    </style>
    
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Guides Table</div>

                <div class="input-group">
                    <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <table class="table table-striped table-bordered tableFixHead" id="sort">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Guide Type</th>
                            <th>Machine Class</th>
                            <th>Supplier</th>
                            <th>Calz Code</th>
                            <th>Style</th>
                            <th>Fold</th>
                            <th>Entry mm</th>
                            <th>Exit mm</th>
                            <th>Tickness mm</th>
                            <th>Elastic mm</th>
                            <th>Picture</th>
                            <th>Video</th>
                            <th>Status</th>
                            <th style="color:red;">Su Qty</th>
                            <th style="color:red;">Ki Qty</th>
                            <th style="color:red;">Se Qty</th>
                            <th style="color:red;">Va Qty</th>
                            <th style="color:blue;">Total</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $req)
                        <tr 
                            title="Operation: {{ $req->operation }}&#10;Note: {{ $req->note }}" 
                            style="cursor: pointer;"
                        >
                            <td>{{ $req->guide_code }}</td>
                            <td>{{ $req->guide_description }}</td>
                            <td>{{ $req->location }}</td>
                            <td>{{ $req->guide_type }}</td>
                            <td>{{ $req->machine_class }}</td>
                            <td>{{ $req->supplier }}</td>
                            <td>{{ $req->calz_code }}</td>
                            <td>{{ $req->style }}</td>
                            <td>{{ $req->fold }}</td>

                            <td>{{ (isset($req->entry_mm) && round($req->entry_mm, 2) != 0) ? round($req->entry_mm, 2) : '' }}</td>
                            <td>{{ (isset($req->exit_mm) && round($req->exit_mm, 2) != 0) ? round($req->exit_mm, 2) : '' }}</td>
                            <td>{{ (isset($req->tickness_mm) && round($req->tickness_mm, 2) != 0) ? round($req->tickness_mm, 2) : '' }}</td>
                            <td>{{ (isset($req->elastic_mm) && round($req->elastic_mm, 2) != 0) ? round($req->elastic_mm, 2) : '' }}</td>

                            <td>{{ $req->picture }}</td>
                            <td>{{ $req->video }}</td>
                            <td>{{ $req->status }}</td>

                            <td>{{ $req->qty_su }}</td>
                            <td>{{ $req->qty_ki }}</td>
                            <td>{{ $req->qty_se }}</td>
                            <td>{{ $req->qty_valy }}</td>

                            <td>{{ round(
                                (isset($req->qty_su) ? $req->qty_su : 0) +
                                (isset($req->qty_ki) ? $req->qty_ki : 0) +
                                (isset($req->qty_se) ? $req->qty_se : 0) +
                                (isset($req->qty_valy) ? $req->qty_valy : 0)
                            , 0) }}</td>

                            <td>
                                <a href="{{ url('guide_edit/'.$req->id) }}" class="btn btn-info btn-xs center-block">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tableFixHead {
        overflow-y: auto;
        height: 500px;
    }
    .tableFixHead thead th {
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 1;
    }
    tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>


<!-- jQuery (required before Bootstrap JS) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- Tooltip activation -->
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush

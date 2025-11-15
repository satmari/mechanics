@extends('app')

@section('content')
<style>
/* Make column filter inputs smaller and uniform */
.column-filter {
    width: 80px;        /* adjust to your desired width */
    padding: 2px 4px;   /* optional, smaller padding */
    font-size: 12px;    /* optional, smaller font */
    box-sizing: border-box; /* ensures padding doesn't increase width */
}

/* If some columns need wider inputs */
.column-filter[data-column="1"] {
    width: 200px;
}
.column-filter[data-column="8"] {
    width: 150px;
}
</style>


<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6"><h3>attachment Table</h3></div>
                        <div class="col-md-6 text-right">
                            <a href="{{ url('attachments_add') }}" class="btn btn-success btn-add">Add new attachment</a>&nbsp;&nbsp;
                            <a href="{{ url('attachments_transfer') }}" class="btn btn-warning btn-add">Transfer</a>&nbsp;&nbsp;
                            <a href="{{ url('attachments_add_remove') }}" class="btn btn-danger btn-add">Add or Reduce from stock</a>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>

                <div class="input-group"> 
                    <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <div>
                    <table class="table table-striped table-bordered tableFixHead" id="sort"
                        data-export-types="['excel']"
                        data-show-export="true">

                        <thead>
                            <!-- Main header -->
                            <tr>
                                <th>Code</th>
                                <!-- <th>Description</th> -->
                                <th>Machine Class</th>
                                
                                <th>Style</th>
                                <th>Operation</th>
                                <th>Notes</th>
                                <th>Location</th>
                                <th>Supplier</th>
                                <!-- <th>Calz Code</th> -->
                                <th>Picture</th>
                                <th>Video</th>
                                <th>Status</th>
                                <th style="color:red;">Su Qty</th>
                                <th style="color:red;">Ki Qty</th>
                                <th style="color:red;">Se Qty</th>
                                <th style="color:blue;">Total</th>
                                <th>Edit</th>
                                <th>History</th>
                            </tr>

                            <!-- Filters row -->
                            <tr>
                                <th><input type="text" class="form-control form-control-sm column-filter" data-column="0" placeholder="Filter…"></th>
                                <th><input type="text" class="form-control form-control-sm column-filter" data-column="1" placeholder="Filter…"></th>
                                <th><input type="text" class="form-control form-control-sm column-filter" data-column="2" placeholder="Filter…"></th>
                                <th><input type="text" class="form-control form-control-sm column-filter" data-column="3" placeholder="Filter…"></th>
                                <th><input type="text" class="form-control form-control-sm column-filter" data-column="4" placeholder="Filter…"></th>
                                <th><input type="text" class="form-control form-control-sm column-filter" data-column="5" placeholder="Filter…"></th>
                                <th><input type="text" class="form-control form-control-sm column-filter" data-column="6" placeholder="Filter…"></th>
                                
                                
                                <th></th>
                                <th></th>
                                <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                            </tr>
                        </thead>

                        <tbody class="searchable">
                            @foreach ($data as $req)
                            <tr title="Operation: {{ $req->operation }}&#10;Note: {{ $req->notes }}">
                                <td class="red-text">{{ $req->attachment_code }}</td>
                                <!-- <td>{{ $req->attachment_description }}</td> -->
                                <td>{{ $req->machine_class }}</td>
                               
                                <td>{{ $req->style }}</td>
                                <td>{{ $req->operation }}</td>
                                <td>{{ $req->notes }}</td>
                                <td>{{ $req->location }}</td>
                                <td>{{ $req->supplier }}</td>
                                <!-- <td>{{ $req->calz_code }}</td> -->

                                <td>
                                    @if($req->picture)
                                        <a href="javascript:void(0);" 
                                           onclick="window.open(
                                               '{{ asset('public/storage/attachmentsFiles/'.$req->picture) }}', 
                                               '_blank',
                                               'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600'
                                           )">View</a>
                                    @endif
                                </td>

                                <td>
                                    @if($req->video)
                                        <a href="javascript:void(0);" 
                                           onclick="window.open(
                                               '{{ asset('public/storage/attachmentsFiles/'.$req->video) }}', 
                                               '_blank',
                                               'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600'
                                           )">View</a>
                                    @endif
                                </td>

                                <td>{{ $req->status }}</td>

                                <td class="red-text">{{ $req->qty_su }}</td>
                                <td class="red-text">{{ $req->qty_ki }}</td>
                                <td class="red-text">{{ $req->qty_se }}</td>
                                <td class="red-total">
                                    {{
                                        (isset($req->qty_su)?$req->qty_su:0) +
                                        (isset($req->qty_ki)?$req->qty_ki:0) +
                                        (isset($req->qty_se)?$req->qty_se:0)
                                    }}
                                </td>

                                <td>
                                    <a href="{{ url('attachment_edit/'.$req->id) }}" class="btn btn-primary btn-xs">Edit</a>
                                </td>

                                <td>
                                    <a href="{{ url('attachment_history/'.$req->id) }}" class="btn btn-info btn-xs">History</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection



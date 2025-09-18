@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Suppliers table<br></div>
                    
                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>
                <table class="table table-striped table-bordered tableFixHead" id="sort" 
                data-export-types="['excel']"
                data-show-export="true"
                
                >
                <!--
                data-export-types="['excel']"
                data-search="true"
                data-show-refresh="true"
                data-show-toggle="true"
                data-query-params="queryParams" 
                data-pagination="true"
                data-height="300"
                data-show-columns="true" 
                data-export-options='{
                         "fileName": "preparation_app", 
                         "worksheetName": "test1",         
                         "jspdf": {                  
                           "autotable": {
                             "styles": { "rowHeight": 20, "fontSize": 10 },
                             "headerStyles": { "fillColor": 255, "textColor": 0 },
                             "alternateRowStyles": { "fillColor": [60, 69, 79], "textColor": 255 }
                           }
                         }
                       }'
                -->
                    <thead>
                        <tr>
                            <th data-sortable="true"><span style=""><b>Supplier</b></span>    </th>
                            <th data-sortable="true"><span style=""><b>Location</b></span> </th>
                            <th data-sortable="true"><span style=""><b>Contact</b></span>  </th>
                            
                            <th></th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                       <tr>
                            <td>{{ $req->supplier }}</td>
                            <td>{{ $req->location }}</td>
                            <td>{{ $req->contact }}</td>

                            <td><a href="{{ url('supplier_edit/'.$req->id) }}" class="btn btn-info btn-xs center-block">Edit</a></td>
                           

                        </tr>
                    @endforeach
                    </tbody>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Machines in Mechanic app</div>
              
                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <table class="table table-strip ed table-bor dered" id="sort" 
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
                            
                            <th><span style=""><b>OS</b></span></th>
                            <th><span style=""><b>Brand</b></span></th>
                            <th><span style=""><b>Code</b></span></th>
                            <th><span style=""><b>Type</b></span></th>
                            <th><span style=""><b>Remark SU</b></span></th>
                            <th><span style=""><b>Remark KI</b></span></th>
                            <th><span style=""><b>Inteos destination</b></span></th>
                            <th><span style=""><b>Inteos machine status</b></span></th>
                            <th><span style=""><b>Inteos line</b></span></th>
                            <th><span style="color:red;"><big><b>App Status</span></b></big></th>
                            <th><span style="color:blue;"><big><b>App Location</span></b></big></th>
                                                       
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                       <tr>
                            
                            <td>{{ $req->os }}</td>
                            <td>{{ $req->brand }}</td>
                            <td>{{ $req->code }}</td>
                            <td>{{ $req->type }}</td>
                            <td>{{ $req->remark_su }}</td>
                            <td>{{ $req->remark_ki }}</td>
                            <td>{{ $req->inteos_status }}</td>
                            <td>{{ $req->inteos_machine_status }}</td>
                            <td>{{ $req->inteos_line }}</td>
                            
                            <td><span style="color:red;"><big><b>{{ $req->machine_status }}</b></big></span></td>
                            <td><span style="color:blue;"><big><b>{{ $req->location }}</b></big></span></td>
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection

@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Machines in Inteos</div>
              
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
                            
                            <th><span style=""><b>MachNum</b></span></th>
                            <th><span style=""><b>Brand</b></span></th>
                            <th><span style=""><b>MaCod</b></span></th>
                            <th><span style=""><b>MaTyp</b></span></th>
                            <th></th>
                            <th><span style="color:red;"><big><b>Subotica status</span></b></big></th>
                            <th><span style="color:red;"><big><b>Inteos status</span></b></big></th>
                            <th><span style="color:red;"><big><b>Line</span></b></big></th>
                            <th></th>
                            <th><span style="color:blue;"><big><b>Kikinda status</span></b></big></th>
                            <th><span style="color:blue;"><big><b>Inteos status</span></b></big></th>
                            <th><span style="color:blue;"><big><b>Line</span></b></big></th>
                                                       
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                       <tr>
                            
                            <td>{{ $req->MachNum }}</td>
                            <td>{{ $req->Brand }}</td>
                            <td>{{ $req->MaCod }}</td>
                            <td>{{ $req->MaTyp }}</td>
                            <td></td>
                            @if ($req->Subotica_main_status == 'ON')
                                <td><span style="color:red;"><big><b>{{ $req->Subotica_main_status }}</b></big></span></td>
                                <td><span style="color:red;"><big><b>{{ $req->Subotica_status }}</b></big></span></td>
                                <td><span style="color:red;"><big><b>{{ $req->Subotica_line }}</b></big></span></td>
                            @else
                            <td></td><td></td><td></td>
                            @endif
                            <td></td>
                            @if ($req->Kikinda_main_status == 'ON')
                                <td><span style="color:blue;"><big><b>{{ $req->Kikinda_main_status }}</b></big></span></td>
                                <td><span style="color:blue;"><big><b>{{ $req->Kikinda_status }}</b></big></span></td>
                                <td><span style="color:blue;"><big><b>{{ $req->Kikinda_line }}</b></big></span></td>
                            @else
                            <td></td><td></td><td></td>
                            @endif

                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection

@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Advanced search with criteria <br><i>{{ $query }}</i></div>
                <button style="btn btn-sm btn-info" onclick="history.back()">Go Back</button>
              
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
                            <th data-sortable="true"><span style=""><b>OS</b></span>    </th>
                            <th data-sortable="true"><span style=""><b>Brand</b></span> </th>
                            <th data-sortable="true"><span style=""><b>Code</b></span>  </th>
                            <th data-sortable="true"><span style=""><b>Type</b></span>  </th>
                            <th><span style=""><b>Comment</b></span>  </th>
                            <!-- <th><span style=""><b>Comment in app</b></span>  </th> -->
                            <th data-sortable="true"><span style="">Gauge</span></th>
                            <th data-sortable="true"><span style="">Gadget</span></th>
                            <th data-sortable="true"><span style="">Ten.Dev. Small Brand Qty</span></th>
                            <!-- <th data-sortable="true"><span style="">Ten.Dev. Small Qty</span></th> -->
                            <th data-sortable="true"><span style="">Ten.Dev. Big Brand Qty</span></th>
                            <!-- <th data-sortable="true"><span style="">Ten.Dev. Big Qty</span></th> -->
                            <th data-sortable="true"><span style="">Puller</span></th>
                            <th data-sortable="true"><span style="">Rollers</b></span></th>
                            <!-- <th><span style=""><b>Inteos destination</b></span></th>    -->
                            <!-- <th><span style=""><b>Inteos machine status</b></span></th> -->
                            <!-- <th><span style=""><b>Inteos line</b></span></th>           -->
                            <th data-sortable="true"><span style="color:red;"><big><b>App Status</span></b></big></th>
                            <th data-sortable="true"><span style="color:blue;"><big><b>App Location</span></b></big></th>
                            <th data-sortable="true"><span style=""><b>Plant</b></span></th>   
                            <!-- <th data-sortable="true"></th>                -->
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                       <tr>
                        
                            <td>{{ $req->os }}</td>
                            <td>{{ $req->brand }}</td>
                            <td>{{ $req->code }}</td>
                            <td>{{ $req->type }}</td>
                            <!-- <td style="width:10px !important;">{{ $req->remark_su }} {{ $req->remark_ki }}</td> -->
                            {{-- <td><big>{{ str_replace("|", " \\n", $req->comment) }}</big></td> --}}
                            {{-- <td><big>{{ nl2br($req->comment) }}</big></td> --}}
                            <td><i>{{ $req->comment }}</i></td>
                            <td>
                                @if ($req->gauge == 0) 
                                @else 
                                    {{ round($req->gauge,1) }}
                                @endif
                            </td>

                            <td>{{ $req->gadget }}</td>
                            <td>{{ $req->el_dev_small_brand }} 
                                @if ($req->el_dev_small_quantity == 0)
                                @else
                                    - {{ round($req->el_dev_small_quantity,0) }}
                                @endif</td>
                           <!--  <td>
                                @if ($req->el_dev_small_quantity == 0)
                                @else
                                    {{ round($req->el_dev_small_quantity,0) }}
                                @endif
                            </td> -->
                            <td>{{ $req->el_dev_big_brand }}  
                                @if ($req->el_dev_big_quantity == 0)
                                @else
                                    - {{ round($req->el_dev_big_quantity,0) }}
                                @endif</td>
                            <!-- <td>
                                @if ($req->el_dev_big_quantity == 0)
                                @else
                                    {{ round($req->el_dev_big_quantity,0) }}
                                @endif
                            </td> -->
                            <td>
                                @if ( $req->puller == '1')
                                Yes
                                @else
                                No
                                @endif
                            </td>
                            <td>
                                @if ( $req->rollers == '1')
                                Yes
                                @else
                                No
                                @endif
                            </td>
                            <!-- <td>{{ $req->inteos_status }}</td> -->
                            <!-- <td>{{ $req->inteos_machine_status }}</td> -->
                            <!-- <td>{{ $req->inteos_line }}</td> -->
                            <td><span style="color:red;"><big><b>{{ $req->machine_status }}</b></big></span></td>
                            <td><span style="color:blue;"><big><b>{{ $req->location }}</b></big></span></td>
                            <td>{{ $req->plant }}</td>

                        </tr>
                    @endforeach
                    </tbody>
            </div>
        </div>
    </div>
</div>
@endsection

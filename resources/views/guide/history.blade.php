@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>History for Guide: {{ $guide->guide_code }} - {{ $guide->guide_description }}</strong>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ url('guides') }}" class="btn btn-default btn-add">Back</a>
                        </div>
                    </div>
                </div>

                <div class="input-group" style="margin: 10px 15px;">
                    <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <div >
                    <table class="table table-striped table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Plant</th>
                                <th>Quantity</th>
                                <!-- <th>Type</th> -->
                                <th>Comment</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stocks as $stock)
                                <tr>
                                    <!-- <td>{{ $stock->id }}</td> -->
                                    <td>{{ $stock->plant }}</td>
                                    <td>{{ $stock->qty }}</td>
                                    <!-- <td>{{ $stock->type }}</td> -->
                                    <td>{{ $stock->comment }}</td>
                                    <td>{{ $stock->created_at }}</td>
                                    <td>{{ $stock->updated_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No stock history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


@endpush

@endsection

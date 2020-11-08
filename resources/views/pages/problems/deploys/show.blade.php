@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('展開済一覧')])

@section('content')
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-danger">
                        VM展開済一覧
                    </div>
                    <div class="card-body">
                        <table class="table">
                            @foreach($vmList as $vm)
                            <tr>
                                <td>{{ var_dump($vm) }}</td>
                            </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header card-header-danger">
                        BS展開済一覧
                    </div>
                    <div class="card-body">
                        <table class="table">
                            @foreach($bsList as $bs)
                            <tr>
                                <td>{{ var_dump($bs) }}</td>
                            </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header card-header-danger">
                        Net展開済一覧
                    </div>
                    <div class="card-body">
                        <table class="table">
                            @foreach($netList as $net)
                            <tr>
                                <td>{{ var_dump($net) }}</td>
                            </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __($team->name.': 展開済一覧')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <a href="{{route('problems.show', ['problem' => $problem])}}" class="btn btn-default">
                戻る
            </a>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-danger">
                        VM展開済一覧
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>id</th>
                                <th>vcpus</th>
                                <th>memory</th>
                                <th>bsIDs</th>
                                <th>nics</th>
                                <th>state</th>
                                <th></th>
                            </thead>

                            <tbody>
                                @foreach($vmList as $vm)
                                <tr>
                                    <td>{{ $vm->meta->id }}</td>
                                    <td>{{ $vm->spec->limitVcpus }}</td>
                                    <td>{{ $vm->spec->limitMemory}}</td>
                                    <td>
                                        <ul>
                                            @foreach($vm->spec->blockStorageIDs as $bsID)
                                            <li>{{ $bsID }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($vm->spec->nics as $nic)
                                            <li>{{ $nic->networkID }}: {{$nic->ipv4Address}}</li>
                                            @endforeach
                                            <ul>
                                    </td>

                                    <td>{{ $vm->status->state }}</td>
                                    <td>
                                        <a class="btn btn-info" target="_blank" href="{{config('apiServerURL', 'http://localhost:8080')}}/static/vnc.html?path=api/v0/groups/{{$problem->group->name}}/namespaces/{{$problem->name}}/virtualmachines/{{$vm->meta->id}}/ws">
                                            OpenConsole
                                        </a>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header card-header-danger">
                        BS展開済一覧
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>id</th>
                                <th>size</th>
                                <th>state</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach($bsList as $storageID => $bs)
                                <tr>
                                    <td>{{ $bs->meta->id }}</td>
                                    <td>{{ $bs->spec->limitSize }}</td>
                                    <td>{{ $bs->status->state }}</td>
                                    <td>
                                        <a href="{{route('problems.deploys.storages.show', [
                                            'problem' => $problem,
                                            'team' => $team,
                                            'storage' => $storageID])}}" class="btn btn-info">
                                            イメージ化
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header card-header-danger">
                        Net展開済一覧
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>id</th>
                                <th>vlanID</th>
                                <th>ipv4CIDR</th>
                            </thead>
                            <tbody>
                                @foreach($netList as $net)
                                <tr>
                                    <td>{{$net->meta->id}}</td>
                                    <td>{{$net->spec->template->spec->id}}</td>
                                    <td>{{$net->spec->template->spec->ipv4CIDR}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

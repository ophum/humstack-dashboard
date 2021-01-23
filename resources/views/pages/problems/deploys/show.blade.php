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
                                <th>arch</th>
                                <th>vcpus</th>
                                <th>memory</th>
                                <th>bsIDs</th>
                                <th>nics</th>
                                <th>vnc(address:display_number)</th>
                                <th>telnet(address port)</th>
                                <th>state</th>
                                <th></th>
                            </thead>

                            <tbody>
                                @foreach($vmList as $machineID => $vm)
                                <?php 
                                    $arch = "";
                                    if (isset($vm->meta->annotations) && isset($vm->meta->annotations["virtualmachinev0/arch"])) {
                                        $arch = $vm->meta->annotations["virtualmachinev0/arch"];
                                    }
                                ?>
                                <tr>
                                    <td>{{ $vm->meta->id }}</td>
                                    <td>{{ $arch }}</td>
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
                                    <td>
                                        @if($arch != "aarch64" && isset($vm->meta->annotations) && isset($vm->meta->annotations['virtualmachinev0/vnc_display_number']))
                                        {{$node->spec->address}}:{{$vm->meta->annotations['virtualmachinev0/vnc_display_number']}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($arch == "aarch64" && isset($vm->meta->annotations) && isset($vm->meta->annotations['virtualmachinev0/vnc_display_number']))
                                        {{$node->spec->address}} {{$vm->meta->annotations['virtualmachinev0/vnc_display_number'] + 7900}}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-pill @if($vm->status->state == 'Running') badge-success @else badge-warning @endif">
                                            {{ $vm->status->state }}
                                        </span>
                                    </td>
                                    <td>
                                        <a class="btn btn-info" target="_blank" href="{{config('humstack.apiServerURL', 'http://localhost:8080')}}/static/vnc.html?path=api/v0/groups/{{$problem->group->name}}/namespaces/{{$problem->code}}/virtualmachines/{{$vm->meta->id}}/ws">
                                            OpenConsole
                                        </a>
                                        @if(isset($vm->meta->annotations) && isset($vm->meta->annotations['virtualmachinev0/ignore']) && $vm->meta->annotations['virtualmachinev0/ignore'] === 'true')
                                        <form style="display: inline;" action="{{route('problems.deploys.unset_ignore', [
                                            'problem' => $problem,
                                            'team' => $team,
                                            'machine' => $machineID
                                        ])}}" method="POST">
                                            {{csrf_field()}}
                                            <button type="submit" class="btn btn-success">管理対象にする</button>
                                        </form>
                                        @else
                                        <form style="display: inline;" action="{{route('problems.deploys.set_ignore', [
                                            'problem' => $problem,
                                            'team' => $team,
                                            'machine' => $machineID
                                        ])}}" method="POST">
                                            {{csrf_field()}}
                                            <button type="submit" class="btn btn-danger">管理対象から外す</button>
                                        </form>
                                        @endif
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

@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('展開設定 : ' . $problem->name)])

@section('content')
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-danger">
                        bulk action 全て同じ設定にする
                    </div>
                    <div class="card-body">
                        <form action="{{route('problems.deploys.bulk-set-deploy-settings', ['problem' => $problem])}}" method="POST">
                            {{csrf_field()}}
                            <div>
                                <a href="{{route('problems.show', ['problem' => $problem])}}" class="btn btn-default">戻る</a>
                                <input type="submit" value="更新" class="btn btn-warning" style="margin-left: 20px">
                            </div>
                            ※ 未展開の展開設定を一括操作できます。<br>
                            ※ NO SELECTを選択すると展開設定は削除されます。
                            <select class="form-control" name="node_id">
                                <option value="-1">NO SELECT</option>
                            @foreach($nodes as $node)
                                <option value="{{$node->id}}">{{$node->name}}</option>
                            @endforeach
                            </select>

                            <select class="form-control" name="storage_type">
                                <option value="Local">Local</option>
                                <option value="Ceph">Ceph</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header card-header-danger">
                        bulk action 一括更新
                    </div>
                    <div class="card-body">
                        <form action="{{route('problems.deploys.bulk-store', ['problem' => $problem])}}" method="POST">
                            {{csrf_field()}}
                            <div>
                                <a href="{{route('problems.show', ['problem' => $problem])}}" class="btn btn-default">戻る</a>
                                <input type="submit" value="更新" class="btn btn-warning" style="margin-left: 20px">
                            </div>
                            ※ 未展開の展開設定を一括操作できます。<br>
                            ※ NO SELECTを選択すると展開設定は削除されます。
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>チームID</th>
                                        <th>展開先ノード</th>
                                        <th>ストレージの種類</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teams as $team)
                                        @if (!isset($settingMap[$team->name]) ||
                                            $settingMap[$team->name]->status === "未展開" ||
                                            $settingMap[$team->name]->status === "")
                                        <tr>
                                            <td>
                                                {{$team->name}}
                                                <input type="hidden" name="teams[]" value="{{$team->id}}">
                                            </td>
                                            <td>
                                                <select class="form-control" name="node_id[]">
                                                    <option value="-1">NO SELECT</option>
                                                @foreach($nodes as $node)
                                                    <option value="{{$node->id}}" @if(isset($settingMap[$team->name]) && $settingMap[$team->name]->node_id === $node->id) selected @endif>{{$node->name}}</option>
                                                @endforeach
                                                </select>

                                            </td>
                                            <td>
                                                <select class="form-control" name="storage_type[]">
                                                    <option value="Local" @if(isset($settingMap[$team->name]) && $settingMap[$team->name]->storage_type === "Local") selected @endif>Local</option>
                                                    <option value="Ceph" @if(isset($settingMap[$team->name]) && $settingMap[$team->name]->storage_type === "Ceph") selected @endif>Ceph</option>
                                                </select>
                                            </td>

                                        @else
                                        <tr style="background-color: lightgrey;">
                                            <td>{{$team->name}}</td>
                                            <td>
                                                {{$settingMap[$team->name]->node->name}}<br>
                                                ※ 展開を破棄すると変更できます。
                                            </td>
                                            <td>
                                                {{$settingMap[$team->name]->storage_type}}<br>
                                                ※ 展開を破棄すると変更できます。
                                            </td>
                                        @endif
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

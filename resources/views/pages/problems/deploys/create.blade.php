@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('展開設定')])

@section('content')
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-danger">
                        展開設定
                    </div>
                    <div class="card-body">
                        <form action="{{ route('problems.deploys.store', ['problem' => $problem, 'team' => $team]) }}"
                            method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="node_id">ノード名</label>
                                <select class="form-control" id="node_id" name="node_id">
                                    @foreach($nodes as $node)
                                    <option value="{{$node->id}}">{{$node->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="storage_type">ストレージの種類</label>
                                <select class="form-control" id="storage_type" name="storage_type">
                                    <option value="Local">Local</option>
                                    <option value="Ceph">Ceph</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">作成</button>
                            <a class="btn btn-secondary"
                                href="{{ route('problems.show', ['problem' => $problem]) }}">キャンセル</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
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
                        {{$problem->name}}/{{$team->name}}/{{$storage->name}} イメージ化
                    </div>
                    <div class="card-body">

                        <form
                            action="{{route('problems.deploys.storages.to_image', ['problem' => $problem, 'team' => $team, 'storage' => $storage]) }}"
                            method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="image_id">イメージ名</label>
                                <select class="form-control" id="image_id" name="image_id">
                                    @foreach($imageList as $image)
                                    <option value="{{$image->meta->id}}">{{$image->meta->id}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tag">タグ</label>
                                <input class="form-control" type="text" id="tag" name="tag">
                            </div>

                            <button type="submit" class="btn btn-success">イメージ化</button>
                            <a class="btn btn-secondary"
                                href="{{route('problems.deploys.show', ['problem' => $problem, 'team' => $team]) }}">キャンセル</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
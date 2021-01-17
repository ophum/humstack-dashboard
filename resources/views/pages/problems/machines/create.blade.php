@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('VM追加')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            VMを追加する
          </div>
          <div class="card-body">
            <form action="{{ route('problems.machines.store', ['problem' => $problem]) }}" method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="name">VM名</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
              <div class="form-group">
                <label for="hostname">hostname</label>
                <input type="text" class="form-control" id="hostname" name="hostname">
              </div>
              <div class="form-group">
                <label for="vcpus">vcpus</label>
                <input type="text" class="form-control" id="vcpus" name="vcpus">
              </div>
              <div class="form-group">
                <label for="memory">memory(単位はG, M, Kを使用可能)</label>
                <input type="text" class="form-control" id="memory" name="memory">
              </div>
              <button type="submit" class="btn btn-success">作成</button>
              <a class="btn btn-secondary" href="{{ route('problems.show', ['problem' => $problem]) }}">キャンセル</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('VM更新')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            VMを更新する
          </div>
          <div class="card-body">
            <form action="{{ route('problems.machines.update', ['problem' => $problem, 'machine' => $machine]) }}"
              method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="name">VM名</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $machine->name }}">
              </div>
              <div class="form-group">
                <label for="vcpus">vcpus</label>
                <input type="text" class="form-control" id="vcpus" name="vcpus" value="{{ $machine->vcpus }}">
              </div>
              <div class="form-group">
                <label for="memory">memory(単位はG, M, Kを使用可能)</label>
                <input type="text" class="form-control" id="memory" name="memory" value="{{ $machine->memory }}">
              </div>
              <button type="submit" class="btn btn-success">更新</button>
              <a class="btn btn-secondary" href="{{ route('problems.index') }}">キャンセル</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
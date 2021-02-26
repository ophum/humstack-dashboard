@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('問題更新')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            問題を更新する
          </div>
          <div class="card-body">
            <form action="{{ route('problems.update', ['problem' => $problem]) }}"
              method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="name">問題名</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $problem->name }}">
              </div>
              <div class="form-group">
                <label for="code">問題コード(編集不可)</label>
                <input type="text" class="form-control" id="code" name="code" value="{{ $problem->code }}" disabled>
              </div>
              <div class="form-group">
                <label for="author">作者</label>
                <input type="text" class="form-control" id="author" name="author" value="{{ $problem->author}}">
              </div>
              <button type="submit" class="btn btn-success">更新</button>
              <a class="btn btn-secondary" href="{{ route('problems.show', ['problem' => $problem]) }}">キャンセル</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

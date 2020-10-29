@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('問題追加')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            問題を追加する
          </div>
          <div class="card-body">
            <form>
              <div class="form-group">
                <label for="name">問題名</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
              <div class="form-group">
                <label for="code">問題コード</label>
                <input type="text" class="form-control" id="code" name="code">
              </div>
              <div class="form-group">
                <label for="author">作者</label>
                <input type="text" class="form-control" id="author" name="author">
              </div>
              <button type="submit" class="btn btn-success">作成</button>
              <a class="btn btn-secondary" href="{{ route('problems.index') }}">キャンセル</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
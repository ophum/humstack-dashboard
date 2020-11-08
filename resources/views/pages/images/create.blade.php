@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('イメージの作成')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            イメージを追加する
          </div>
          <div class="card-body">
            <form action="{{ route('images.store') }}" method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="name">イメージ名</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
              <div class="form-group">
                <label for="description">description</label>
                <input type="text" class="form-control" id="description" name="description">
              </div>
              <button type="submit" class="btn btn-success">作成</button>
              <a class="btn btn-secondary" href="{{ route('images')}}">キャンセル</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
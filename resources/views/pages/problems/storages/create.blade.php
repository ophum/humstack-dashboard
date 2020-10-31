@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('Storage追加')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            Storageを追加する
          </div>
          <div class="card-body">
            <form action="{{ route('problems.storages.store', ['problem' => $problem]) }}" method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="name">Storage名</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
              <div class="form-group">
                <label for="size">size(単位はG, M, Kを使用できます)</label>
                <input type="text" class="form-control" id="size" name="size">
              </div>
              <div class="form-group">
                <label for="image_tag_id">image_tag_id</label>
                <input type="text" class="form-control" id="image_tag_id" name="image_tag_id">
              </div>
              <button type="submit" class="btn btn-success">作成</button>
              <a class="btn btn-secondary" href="{{ route('problems.show', [
                'problem' => $problem,
              ]) }}">キャンセル</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
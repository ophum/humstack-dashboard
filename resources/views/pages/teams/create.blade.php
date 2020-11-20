@extends('layouts.app', ['activePage' => 'teams', 'titlePage' => __('チーム追加')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            チームを追加する
          </div>
          <div class="card-body">

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <form action="{{ route('teams.store') }}" method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="name">チーム名</label>
                <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
              </div>
              <div class="form-group">
                <label for="id_prefix">ID Prefix</label>
                <input type="text" class="form-control" id="id_prefix" name="id_prefix" value="{{old('id_prefix')}}">
              </div>
              <div class="form-group">
                <label for="vlan_prefix">VLAN Prefix</label>
                <input type="text" class="form-control" id="vlan_prefix" name="vlan_prefix" value="{{old('vlan_prefix')}}">
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

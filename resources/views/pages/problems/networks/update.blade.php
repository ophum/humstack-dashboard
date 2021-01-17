@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('Network更新')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            Networkを更新する
          </div>
          <div class="card-body">
            <form action="{{ route('problems.networks.update', ['problem' => $problem, 'network' => $network]) }}"
              method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="name">Network名</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $network->name }}">
              </div>
              <div class="form-group">
                <label for="vlan_id">vlan id</label>
                <input type="text" class="form-control" id="vlan_id" name="vlan_id" value="{{ $network->vlan_id }}">
              </div>
              <div class="form-group">
                <label for="ipv4_cidr">ipv4 cidr</label>
                <input type="text" class="form-control" id="ipv4_cidr" name="ipv4_cidr" placeholder="xxx.xxx.xxx.xxx/xx"
                  value="{{ $network->ipv4_cidr }}">
              </div>
              <div class="form-group mt-4">
                <label>
                  <input type="checkbox" id="require_gateway" name="require_gateway" value="require" @if($network->require_gateway) checked @endif />
                  Gateway
                </label>
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
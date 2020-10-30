@extends('layouts.app', ['activePage' => 'teams', 'titlePage' => __('チーム: ' . $team->name)])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">チーム詳細</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <ul>
                <li>{{ $team->id }}</li>
                <li>{{ $team->name }}</li>
                <li>{{ $team->id_prefix }}</li>
                <li>{{ $team->vlan_prefix }}</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

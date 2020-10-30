@extends('layouts.app', ['activePage' => 'teams', 'titlePage' => __('Table List')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">チーム一覧</h4>
            <p class="card-category">チーム一覧</p>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <div>
                <a href="{{ route('teams.create') }}" class="btn btn-primary">追加</a>
              </div>
              <table class="table">
                <thead class=" text-primary">
                  <th>
                    ID
                  </th>
                  <th>
                    チーム名
                  </th>
                  <th>
                    ID Prefix
                  </th>
                  <th>
                    VLAN Prefix
                  </th>
                </thead>
                <tbody>
                  @foreach($teams as $t)
                  <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->name }}</td>
                    <td>{{ $t->id_prefix }}</td>
                    <td>{{ $t->vlan_prefix }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

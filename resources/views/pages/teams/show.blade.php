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
            <div>
              <form action="{{ route('teams.delete', ['team' => $team]) }}" method="POST" onsubmit="return check('本当に削除しますか?')">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-danger">削除</button>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table">
                <tbody>
                  <tr>
                    <td style="width: 10em">ID</td>
                    <td >{{ $team->id }}</td>
                  </tr>
                  <tr>
                    <td>Name</td>
                    <td>{{ $team->name }}</td>
                  </tr>
                  <tr>
                    <td>ID Prefix</td>
                    <td>{{ $team->id_prefix }}</td>
                  </tr>
                  <tr>
                    <td>VLAN Prefix</td>
                    <td>{{ $team->vlan_prefix }}</td>
                  </tr>
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

<script>
  
function check(msg) {
    return window.confirm(msg);
}
</script>
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
                <button type="button" class="btn btn-primary">追加</button>
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
                    VLAN Prefix
                  </th>
                  <th>
                    ID Prefix
                  </th>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>team01</td>
                    <td>1</td>
                    <td>team01-</td>
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

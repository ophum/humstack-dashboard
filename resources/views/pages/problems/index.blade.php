@extends('layouts.app', ['activePage' => 'problems', 'titlePage' => __('Table List')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">問題一覧</h4>
          </div>
          <div class="card-body">
            <div>
              <a href="{{ route('problems.create') }}" class="btn btn-primary">追加</a>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead class=" text-primary">
                  <th>
                    ID
                  </th>
                  <th>
                    Name
                  </th>
                  <th>
                    Code
                  </th>
                  <th>
                    Author
                  </th>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="{{ route('problems.show', 1) }}">
                        1
                      </a>
                    </td>
                    <td>
                      hogehoge
                    </td>
                    <td>
                      abc
                    </td>
                    <td>
                      aaa
                    </td>
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
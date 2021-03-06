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
              <a href="{{ route('problems.communicatingIPList')}}" class="btn btn-info" style="margin-left: 20px">疎通性のあるIP一覧</a>
              <a href="{{ route('problems.resourcePerProblemList')}}" class="btn btn-info">問題毎リソース量一覧</a>
            </div>
            <div>
              <form action="" method="GET">
                問題コード :
                <input type="text" class="input-form" name="code">
                <input type="submit" value="移動">
              </form>
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
                  @foreach($problems as $p)
                  <tr>
                    <td>
                      <a href="{{ route('problems.show', $p->id) }}">
                        {{ $p->id }}
                      </a>
                    </td>
                    <td>
                      {{ $p->name }}
                    </td>
                    <td>
                      {{ $p->code }}
                    </td>
                    <td>
                      {{ $p->author }}
                    </td>
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

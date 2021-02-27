@extends('layouts.app', ['activePage' => 'problems', 'titlePage' => __('Table List')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <a href="{{ route('problems.index')}}" class="btn btn-default">戻る</a>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">問題毎のリソース量一覧</h4>
          </div>
          <div class="card-body">
            <div>
              <a href="{{ route('problems.resourcePerProblemListCSV')}}" class="btn btn-info">csv</a>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead class=" text-primary">
                  <th>
                    問題コード
                  </th>
                  <th>
                    CPU
                  </th>
                  <th>
                    メモリ(GB)
                  </th>
                  <th>
                    ストレージ(GB)
                  </th>
                </thead>
                <tbody>
                  @foreach($resourceList as $r)
                    <tr>
                      <td>
                        <a href="{{ route('problems.show', $r['pid']) }}">
                          {{ $r["code"]}}
                        </a>
                      </td>
                      <td>
                        {{ $r["vcpus"] }}
                      </td>
                      <td>
                        {{ $r["mem"] }}
                      </td>
                      <td>
                        {{ $r["storage"] }}
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

@extends('layouts.app', ['activePage' => 'problems', 'titlePage' => __('Table List')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <a href="{{ route('problems.index')}}" class="btn btn-default">戻る</a>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">疎通性のあるIP一覧</h4>
          </div>
          <div class="card-body">
            <div>
              <a href="{{ route('problems.communicatingIPListCSV')}}" class="btn btn-info">csv</a>
              <a href="{{ route('problems.communicatingIPListNAVTCSV')}}" class="btn btn-info">navt ver csv</a>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead class=" text-primary">
                  <th>
                    問題コード
                  </th>
                  <th>
                    VM名
                  </th>
                  <th>
                    ネットワーク名
                  </th>
                  <th>
                    IPアドレス
                  </th>
                </thead>
                <tbody>
                  @foreach($ipList as $ip)
                    <tr>
                      <td>
                        <a href="{{ route('problems.show', $ip->pid) }}">
                          {{ $ip->code}}
                        </a>
                      </td>
                      <td>
                        {{ $ip->vm_name }}
                      </td>
                      <td>
                        {{ $ip->net_name }}
                      </td>
                      <td>
                        {{ $ip->ipv4_address }}
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

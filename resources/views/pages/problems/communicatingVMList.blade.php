@extends('layouts.app', ['activePage' => 'problems', 'titlePage' => __('Table List')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <a href="{{ route('problems.index')}}" class="btn btn-default">戻る</a>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">疎通性のあるVM一覧</h4>
          </div>
          <div class="card-body">
            <div>
              <a href="{{ route('problems.communicatingVMListCSV')}}" class="btn btn-info">csvのあるVMリスト</a>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead class=" text-primary">
                  <th>
                    問題ID
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
                  @foreach($vmList as $pid => $vms)
                    @foreach($vms as $vm => $nets)
                      @foreach($nets as $net => $ip)
                      <tr>
                        <td>
                          <a href="{{ route('problems.show', $pid) }}">
                            {{ $pid }}
                          </a>
                        </td>
                        <td>
                          {{ $vm }}
                        </td>
                        <td>
                          {{ $net }}
                        </td>
                        <td>
                          {{ $ip}}
                        </td>
                      </tr>
                      @endforeach
                    @endforeach
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
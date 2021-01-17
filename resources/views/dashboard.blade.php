@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')
<div class="content">
  <div class="container-fluid">
    @foreach($nodes as $node)
    <div class="row">
      <div class="card">
        <div class="card-header card-header-info">
          <h3 class="card-title">
            Node: {{ $node->name }}
          </h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">

              <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">computer</i>
                  </div>
                  <p class="card-category">Requested Vcpus</p>
                  <h3 class="card-title">{{ $vcpus[$node->name] ?? 0 }}/{{ $node->limit_vcpus }}
                    <small>vcore</small>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">memory</i>
                  </div>
                  <p class="card-category">Requested Memory</p>
                  <h3 class="card-title">
                    {{($memoryBytes[$node->name] ?? 0) / (1024 * 1024 * 1024)}}/{{ $node->limit_memory }}
                    <small>GB</small>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">storage</i>
                  </div>
                  <p class="card-category">Requested Storage</p>
                  <h3 class="card-title">0/1000GB</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
</div>
@endsection

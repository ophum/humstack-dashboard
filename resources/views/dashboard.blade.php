@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <span class="nav-tabs-title"></span>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#node" data-toggle="tab">
                      <i class="material-icons">dns</i> Nodes
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#problem" data-toggle="tab">
                      <i class="material-icons">library_books</i> Problems
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#team" data-toggle="tab">
                      <i class="material-icons">groups</i> Teams
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active" id="node">
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

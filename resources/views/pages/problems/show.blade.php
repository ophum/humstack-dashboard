@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('問題: ' . $problem->name)])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            基本的な情報
          </div>
          <div class="card-body">

            問題コード: {{ $problem->code }}<br>
            作問者 : {{ $problem->author }}
          </div>
        </div>
      </div>
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-warning">
            展開
          </div>
          <div class="card-body">
            <div>
              <button class="btn btn-success">全展開</button>
              <button class="btn btn-danger">全破棄</button>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th>チームID</th>
                  <th>ステータス</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>team01</td>
                  <td><span class="badge badge-pill badge-danger">未展開</span></td>
                  <td>
                    <button class="btn btn-success">展開</button>
                  </td>
                </tr>
                <tr>
                  <td>team02</td>
                  <td><span class="badge badge-pill badge-success">展開済</span></td>
                  <td>
                    <button class="btn btn-danger">破棄</button>
                  </td>
                </tr>
                <tr>
                  <td>team02</td>
                  <td><span class="badge badge-pill badge-info">展開中</span></td>
                  <td>
                  </td>
                </tr>
                <tr>
                  <td>team02</td>
                  <td><span class="badge badge-pill badge-warning">破棄中</span></td>
                  <td>
                  </td>
                </tr>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <span class="nav-tabs-title">リソース:</span>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#machine" data-toggle="tab">
                      <i class="material-icons">memory</i> Machine
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#storage" data-toggle="tab">
                      <i class="material-icons">storage</i> Storage
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#network" data-toggle="tab">
                      <i class="material-icons">sync_alt</i> Network
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active" id="machine">
                <div>
                  <a href="{{ route('problems.machines.create', ['problem' => $problem ]) }}" type="button"
                    class="btn btn-primary">追加</a>
                </div>
                <table class="table">
                  <thead>
                    <tr>
                      <th></th>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Vcpus</th>
                      <th>Memory</th>
                      <th>Storages</th>
                      <th>Networks</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($problem->machines as $m)
                    <tr>
                      <td>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value="" checked>
                            <span class="form-check-sign">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                      </td>
                      <td>{{ $m->id }}</td>
                      <td>{{ $m->name }}</td>
                      <td>{{ $m->vcpus }}</td>
                      <td>{{ $m->memory }}</td>
                      <td>
                        <a href="{{ route('problems.machines.storages', [
                          'problem' => $problem,
                          'machine' => $m,
                        ])}}" class="btn btn-primary">Storageを追加する</a>

                        @foreach($m->attachedStorages as $index => $storage)
                        <div class="card">
                          <div class="card-header card-header-info">
                            {{$index}}
                          </div>
                          <div class="card-body">
                            <form
                              action="{{ route('problems.machines.storages.detach', ['problem' => $problem, 'machine' => $m, 'storage' => $storage])}}"
                              method="POST">
                              {{ csrf_field() }}
                              <button type="submit" class="btn btn-danger btn-sm">
                                <i class="material-icons">close</i>
                              </button>
                            </form>
                            <li>{{$storage->name}}</li>
                          </div>
                        </div>
                        @endforeach
                      </td>
                      <td>
                        <a href="{{ route('problems.machines.nics', [
                          'problem' => $problem,
                          'machine' => $m,
                        ])}}" class="btn btn-primary">NICを追加する</a>

                        @foreach($m->attachedNics as $index => $nic)
                        <div class="card">
                          <div class="card-header card-header-info">
                            NIC: eth{{$index}}
                          </div>
                          <div class="card-body">
                            <form
                              action="{{ route('problems.machines.nics.detach', ['problem' => $problem, 'machine' => $m, 'network' => $nic])}}"
                              method="POST">
                              {{ csrf_field() }}
                              <button type="submit" class="btn btn-danger btn-sm">
                                <i class="material-icons">close</i>
                              </button>
                            </form>
                            <li>network: {{ $nic->name }}</li>
                            <li>IPv4 Address: {{ $nic->pivot->ipv4_address }}</li>
                            <li>Default Gateway: {{ $nic->pivot->default_gateway }}</li>
                          </div>
                        </div>
                        @endforeach
                      </td>
                      <td class="td-actions text-right">
                        <a href="{{ route('problems.machines.edit', ['problem' => $problem, 'machine' => $m])}}"
                          rel="tooltip" title="Edit VM" class="btn btn-primary btn-link btn-sm">
                          <i class="material-icons">edit</i>
                        </a>
                        <form action="{{ route('problems.machines.delete', ['problem' => $problem, 'machine' => $m]) }}"
                          method="POST">
                          {{ csrf_field() }}
                          <button type="submit" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="storage">
                <div>
                  <a href="{{ route('problems.storages.create', [
                    'problem' => $problem,
                  ]) }}" class="btn btn-primary">追加</a>
                </div>
                <table class="table">
                  <thead>
                    <tr>
                      <th></th>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Size</th>
                      <th>Image:Tag</th>
                      <th>AttachedVM</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($problem->storages as $s)
                    <tr>
                      <td>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value="" checked>
                            <span class="form-check-sign">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                      </td>
                      <td>{{ $s->id }}</td>
                      <td>{{ $s->name }}</td>
                      <td>{{ $s->size }}</td>
                      <td>{{ $s->image_tag_id }}</td>
                      <td></td>
                      </td>
                      <td class="td-actions text-right">
                        <a href="{{ route('problems.storages.edit', ['problem' => $problem, 'storage' => $s])}}"
                          rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                          <i class="material-icons">edit</i>
                        </a>
                        @if(count($s->machines()->get()) == 0)
                        <form action="{{ route('problems.storages.delete', ['problem' => $problem, 'storage' => $s]) }}"
                          method="POST">
                          {{ csrf_field() }}
                          <button type="submit" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </form>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="network">
                <div>
                  <a href="{{ route('problems.networks.create', ['problem' => $problem]) }}"
                    class="btn btn-primary">追加</a>
                </div>
                <table class="table">
                  <thead>
                    <tr>
                      <th></th>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Base VLAN ID</th>
                      <th>IPv4 CIDR</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($problem->networks as $n)
                    <tr>
                      <td>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value="">
                            <span class="form-check-sign">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                      </td>
                      <td>{{ $n->id }}</td>
                      <td>{{ $n->name }}</td>
                      <td>{{ $n->vlan_id}}</td>
                      <td>{{ $n->ipv4_cidr }}</td>
                      <td class="td-actions text-right">
                        <a href="{{ route('problems.networks.edit', ['problem' => $problem, 'network' => $n]) }}"
                          rel="tooltip" title="Edit Task" class="btn btn-primary btn-link btn-sm">
                          <i class="material-icons">edit</i>
                        </a>
                        @if(count($n->machines()->get()) == 0)
                        <form action="{{ route('problems.networks.delete', ['problem' => $problem, 'network' => $n]) }}"
                          method="POST">
                          {{ csrf_field() }}
                          <button type="submit" rel="tooltip" title="Remove" class="btn btn-danger btn-link btn-sm">
                            <i class="material-icons">close</i>
                          </button>
                        </form>
                        @endif
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
</div>
@endsection
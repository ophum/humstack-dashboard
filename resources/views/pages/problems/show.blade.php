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
            <div>
              <a href="{{route('problems.edit', ['problem' => $problem])}}" type="button" class="btn btn-info">
                編集
              </a>
              <form style="margin-left: 20px; display: inline;" action="{{ route('problems.delete', ['problem' => $problem]) }}" method="POST" onsubmit="return check('本当に削除しますか?')">
                {{csrf_field()}}
                <button type="submit" class="btn btn-danger">削除</button>
              </form>
            </div>
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
              <a href="{{route('problems.deploys.bulk', ['problem' => $problem])}}" class="btn btn-default">展開設定 一括操作</a>
              <button id="all_deploy_button"class="btn btn-success">全展開</button>
              <button id="all_poweron_button" class="btn btn-info">全VMスタート</button>
              <button id="all_destroy_button" class="btn btn-danger" style="margin-left: 20px">全破棄</button>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" id="all_check">
                        <span class="form-check-sign">
                          <span class="check"></span>
                        </span>
                      </label>
                    </div>
                  </th>
                  <th>チームID</th>
                  <th>展開先ノード</th>
                  <th>ストレージの種類</th>
                  <th>ステータス</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($problem->group->teams as $t)
                <tr>
                  <td>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="deployTeamCheckbox[]" value="{{$t->id}}" checked>
                        <span class="form-check-sign">
                          <span class="check"></span>
                        </span>
                      </label>
                    </div>
                  </td>
                  <td>
                    <a href="{{route('problems.deploys.show', ['problem' => $problem, 'team' => $t])}}">
                      {{ $t->name }}
                    </a>
                  </td>
                  @if($t->deployedProblems()->where('problem_id', $problem->id)->exists())
                    <?php
                      $deploySetting = $t->deployedProblems()->where('problem_id', $problem->id)->first();
                      $status = $deploySetting->pivot->status;
                    ?>
                    <td>{{$deploySetting->pivot->node->name }}</td>
                    <td>{{$deploySetting->pivot->storage_type}}</td>
                    @if ($status == "未展開")
                      <td><span class="badge badge-pill badge-danger">未展開</span></td>
                      <td>
                        <form style="display: inline; " action="{{ route('problems.deploys.deploy', ['problem' => $problem, 'team' => $t]) }}" method="POST">
                          {{ csrf_field() }}
                          <button type="submit" class="btn btn-success">展開</button>
                        </form>
                        <form style="display: inline; " action="{{ route('problems.deploys.delete_deploy_setting', ['problem' => $problem, 'team' => $t]) }}" method="POST" onsubmit="return check('本当に設定を削除しますか?')">
                          {{ csrf_field() }}
                          <button type="submit" class="btn btn-danger">設定削除</button>
                        </form>


                      </td>
                    @elseif ($status == "展開中")
                      @if ($activeBSCountMap[$t->name] === $problem->storages->count())
                        <td>
                          <span class="badge badge-pill badge-warning">bs展開済み</span>
                          <div>
                            started vm: {{$runningVMCountMap[$t->name]}}/{{$problem->machines->count()}}<br>
                            power-off vm: {{$powerOffVMCountMap[$t->name]}}/{{$problem->machines->count()}}<br>
                            active  bs: {{$activeBSCountMap[$t->name]}}/{{$problem->storages->count()}}<br>
                            net: {{$netCountMap[$t->name]}}/{{$problem->networks->count()}}
                          </div>
                        </td>
                      @else
                        <td>
                          <span class="badge badge-pill badge-danger">展開中</span>
                          <div>
                            running vm: {{$runningVMCountMap[$t->name]}}/{{$problem->machines->count()}}<br>
                            power-off vm: {{$powerOffVMCountMap[$t->name]}}/{{$problem->machines->count()}}<br>
                            active  bs: {{$activeBSCountMap[$t->name]}}/{{$problem->storages->count()}}<br>
                            net: {{$netCountMap[$t->name]}}/{{$problem->networks->count()}}
                          </div>
                      </td>
                      @endif
                      <td>
                        <form action="{{ route('problems.deploys.virtualmachines.powerOn', ['problem' => $problem, 'team' => $t]) }}" method="POST">
                          {{ csrf_field() }}
                          <button type="submit" class="btn btn-success">全てのVMを起動する</button>
                        </form>
                        <form action="{{ route('problems.deploys.destroy', ['problem' => $problem, 'team' => $t]) }}" method="POST" onsubmit="return check('本当に破棄しますか?')">
                          {{ csrf_field() }}
                          <button type="submit" class="btn btn-danger">破棄</button>
                        </form>
                      </td>

                    @elseif ($status == "展開済")
                      <td><span class="badge badge-pill badge-success">展開済</span></td>
                      <td>
                        <form action="{{ route('problems.deploys.destroy', ['problem' => $problem, 'team' => $t]) }}" method="POST" onsubmit="return check('本当に破棄しますか?')">
                          {{ csrf_field() }}
                          <button type="submit" class="btn btn-danger">破棄</button>
                        </form>
                      </td>
                    @elseif ($status == "削除中")
                      <td><span class="badge badge-pill badge-danger">削除中</span></td>
                      <td>
                        <form action="{{ route('problems.deploys.destroy', ['problem' => $problem, 'team' => $t]) }}" method="POST">
                          {{ csrf_field() }}
                          <button type="submit" class="btn btn-danger">破棄 再実行</button>
                        </form>
                      </td>
                    @endif
                  @else
                  <td></td>
                  <td><span class="badge badge-pill badge-danger"></span></td>
                  <td>
                    <a href="{{ route('problems.deploys.create', ['problem' => $problem, 'team' => $t]) }}" class="btn btn-primary">展開設定</a>
                  </td>
                  @endif
                </tr>
                @endforeach
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
                  <li class="nav-item">
                    <form action="{{route('problems.deploys.toProdImage', ['problem' => $problem])}}" method="POST">
                      {{csrf_field()}}
                      <button type="submit" class="btn btn-warning" style="margin-left: 40px">
                        本番イメージを作成する(storageのtagも変更されます)
                      </button>
                    </form>
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
                      <th>Arch</th>
                      <th>Hostname</th>
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
                      <td>{{ $m->arch }}</td>
                      <td>{{ $m->hostname }}</td>
                      <td>{{ $m->vcpus }}</td>
                      <td>{{ $m->memory }}</td>
                      <td>
                        <a href="{{ route('problems.machines.storages', [
                          'problem' => $problem,
                          'machine' => $m,
                        ])}}" class="btn btn-primary btn-sm">Storageを追加する</a>

                        @foreach($m->attachedStorages as $index => $storage)
                        <div class="card m-1">
                          <div class="card-body p-1">
                            <span class="badge badge-secondary">storage{{$index}}</span>
                            <span class="badge badge-secondary">{{$storage->name}}</span>
                            @if($storage->from_type == "HTTP")
                            <span class="badge badge-secondary">{{$storage->url}}</span>
                            @else
                            <span class="badge badge-secondary">{{$storage->image_name}}:{{$storage->image_tag}}</span>
                            @endif
                            <form
                              style="display: inline; float: right;"
                              action="{{ route('problems.machines.storages.detach', ['problem' => $problem, 'machine' => $m, 'storage' => $storage])}}"
                              method="POST">
                              {{ csrf_field() }}
                              <button type="submit" class="btn btn-danger btn-sm">
                                <i class="material-icons">close</i>
                              </button>
                            </form>
                          </div>
                        </div>
                        @endforeach
                      </td>
                      <td>
                        <a href="{{ route('problems.machines.nics', [
                          'problem' => $problem,
                          'machine' => $m,
                        ])}}" class="btn btn-primary btn-sm">NICを追加する</a>

                        @foreach($m->attachedNics as $index => $nic)
                        <div class="card m-1">
                          <div class="card-body p-1">
                            <span class="badge badge-secondary">NIC: eth{{$index}} to {{ $nic->name }}</span>
                            <span class="badge badge-secondary">IPv4 Address: {{ $nic->pivot->ipv4_address }}</span>
                            <span class="badge badge-secondary">Default Gateway: {{ $nic->pivot->default_gateway }}</span>
                            <span class="badge badge-secondary">Base VLAN ID: {{sprintf("%02d", (int)$nic->vlan_id)}}</span>

                            <form style="display: inline; float: right"
                              action="{{ route('problems.machines.nics.detach', ['problem' => $problem, 'machine' => $m, 'network' => $nic])}}"
                              method="POST">
                              {{ csrf_field() }}
                              <button type="submit" class="btn btn-danger btn-sm">
                                <i class="material-icons">close</i>
                              </button>
                            </form>
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
                      <th>Type</th>
                      <th>Base</th>
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
                      <td>{{ $s->size }}GB</td>
                      <td>{{ $s->from_type }}</td>
                      @if($s->from_type == "HTTP")
                      <td>{{ $s->url }}</td>
                      @else
                      <td>{{ $s->image_name }}:{{ $s->image_tag }}</td>
                      @endif
                      <td>
                      @foreach($s->machines as $m)
                        {{$m->name}}
                      @endforeach
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
                      <th>Gateway</th>
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
                      <td>
                        @if($n->require_gateway)
                          YES
                        @else
                          NO
                        @endif
                      </td>
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
  <!--<div id="topo"></div>-->
</div>

<!--<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>-->
<script>
  const width = 1200;
  const height = 800;

/*
  fetch("/problems/{{$problem->id}}/topo")
    .then(res => res.json())
    .then(data => {
      var edges = [];
      data.links.forEach((e) => {
        var sourceNode = data.nodes.filter((n) => {
          return n.id === e.source;
        })[0];
        var targetNode = data.nodes.filter((n) => {
          return n.id === e.target;
        })[0];
        edges.push({
          source: sourceNode,
          target: targetNode,
        });
      });
      var force = d3.layout.force()
        .nodes(data.nodes)
        .links(edges)
        .size([width, height])
        .distance(300) // node同士の距離
        .friction(0.9) // 摩擦力(加速度)的なものらしい。
        .charge(-2000) // 寄っていこうとする力。推進力(反発力)というらしい。
        .gravity(0.1) // 画面の中央に引っ張る力。引力。
        .start();

      var svg = d3.select("#topo")
        .append("svg")
        .attr({
          width: width,
          height: height
        });

      var link = svg.selectAll("line")
        .data(edges)
        .enter()
        .append("line")
        .style({
          stroke: "#ccc",
          "stroke-width": 1,
        });

      var node = svg.selectAll("rect")
        .data(data.nodes)
        .enter()
        .append("rect")
        .attr({
          x: function(d) {
            return d.x
          },
          y: function(d) {
            return d.y
          },
          width: 50,
          height: 20,
          //r: function() {
          //  return 20;
          //}
        })
        .style({
          fill: function(d) {
            return d.type === "machine" ? "orange" : "skyblue";
          }
        })
        .call(force.drag);

      var label = svg.selectAll('text')
        .data(data.nodes)
        .enter()
        .append('text')
        .attr({
          "text-anchor": "middle",
          "fill": "black",
          "font-size": "9px"
        })
        .text(function(data) {
          if (data.type === "network") {
            return data.label + JSON.stringify({
              cidr: data.cidr,
              vlan: data.vlan
            });
          }

          return data.label + JSON.stringify(data.nics);
        });

      force.on("tick", function() {
        link.attr({
          x1: function(data) {
            return data.source.x;
          },
          y1: function(data) {
            return data.source.y;
          },
          x2: function(data) {
            return data.target.x;
          },
          y2: function(data) {
            return data.target.y;
          }
        });
        node.attr({
          x: function(data) {
            return data.x - 25;
          },
          y: function(data) {
            return data.y - 10;
          },
          cx: function(data) {
            return data.x;
          },
          cy: function(data) {
            return data.y;
          }
        });
        label.attr({
          x: function(data) {
            return data.x;
          },
          y: function(data) {
            return data.y;
          }
        });
      });

    });
*/

document.getElementById('all_check').addEventListener('change', (e) => {
    const checkboxies = document.getElementsByName('deployTeamCheckbox[]');
    for(var i = 0; i < checkboxies.length; i++) {
      checkboxies[i].checked = e.target.checked;
    }
});

document.getElementById('all_deploy_button').addEventListener("click", () => {
  const checkboxies = document.getElementsByName('deployTeamCheckbox[]');
  let deployTeamIDs = [];

  var form = document.createElement("form");
  form.action="{{route('problems.deploys.deploy.multi', ['problem' => $problem])}}";
  form.method="POST";

  var token = document.createElement("input");
  token.type="hidden";
  token.name="_token";
  token.value="{{csrf_token()}}";
  form.appendChild(token);
  for(var i = 0; i < checkboxies.length; i++) {
    if(checkboxies[i].checked) {
      var input = document.createElement("input");
      input.name="teamIDs[]";
      input.value=checkboxies[i].value;
      form.appendChild(input);
    }
  }
  console.log(deployTeamIDs);
  document.body.appendChild(form);
  form.submit();

});

document.getElementById('all_poweron_button').addEventListener('click', () => {
  if (!check("選択された全てのVMを起動します。")) return;

  const checkboxies = document.getElementsByName('deployTeamCheckbox[]');
  let deployTeamIDs = [];

  var form = document.createElement("form");
  form.action="{{route('problems.deploys.virtualmachines.multiPowerOn', ['problem' => $problem])}}";
  form.method = "POST";

  var token = document.createElement("input");
  token.type="hidden";
  token.name="_token";
  token.value="{{csrf_token()}}";
  form.appendChild(token);
  for(var i = 0; i < checkboxies.length; i++) {
    if(checkboxies[i].checked) {
      var input = document.createElement("input");
      input.name="teamIDs[]";
      input.value=checkboxies[i].value;
      form.appendChild(input);
    }
  }
  console.log(deployTeamIDs);
  document.body.appendChild(form);
  form.submit();
})

document.getElementById('all_destroy_button').addEventListener('click', () => {
  if (!check("選択された全ての展開を破棄します。")) return;

  const checkboxies = document.getElementsByName('deployTeamCheckbox[]');
  let deployTeamIDs = [];

  var form = document.createElement("form");
  form.action="{{route('problems.deploys.destroy.multi', ['problem' => $problem])}}";
  form.method = "POST";

  var token = document.createElement("input");
  token.type="hidden";
  token.name="_token";
  token.value="{{csrf_token()}}";
  form.appendChild(token);
  for(var i = 0; i < checkboxies.length; i++) {
    if(checkboxies[i].checked) {
      var input = document.createElement("input");
      input.name="teamIDs[]";
      input.value=checkboxies[i].value;
      form.appendChild(input);
    }
  }
  console.log(deployTeamIDs);
  document.body.appendChild(form);
  form.submit();
})

function check(msg) {
    return window.confirm(msg);
}
</script>
@endsection

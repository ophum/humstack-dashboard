@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('VM追加')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            VMを追加する
          </div>
          <div class="card-body">
            <form action="{{ route('problems.machines.nics.attach', [
              'problem' => $problem,
              'machine' => $machine,
            ]) }}" method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="network_id">Network</label>
                <select id="network_id" name="network_id" class="form-control">
                  @foreach($problem->networks()->whereNotIn('id', array_column($machine->attachedNics->toArray(),
                  'id'))->get() as $n)
                  <option value="{{ $n->id }}">
                    {{ $n->name }} : {{ $n->ipv4_cidr }}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="ipv4_address">ipv4 address</label>
                <input type="text" class="form-control" id="ipv4_address" name="ipv4_address"
                  placeholder="xxx.xxx.xxx.xxx">
              </div>
              <div class="form-group">
                <label for="default_gateway">default gateway</label>
                <input type="text" class="form-control" id="default_gateway" name="default_gateway"
                  placeholder="xxx.xxx.xxx.xxx">
              </div>
              <div class="form-group">
                <label for="nameserver">nameserver</label>
                <input type="text" class="form-control" id="nameserver" name="nameserver"
                  placeholder="xxx.xxx.xxx.xxx">
              </div>
              <div class="form-group">
                <label for="order">順番</label>
                <input type="number" class="form-control" id="order" name="order">
              </div>
              <button type="submit" class="btn btn-success">作成</button>
              <a class="btn btn-secondary" href="{{ route('problems.show', [
                'problem' => $problem,
              ]) }}">キャンセル</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
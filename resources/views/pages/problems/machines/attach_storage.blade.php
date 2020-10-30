@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('StorageをMachineに追加')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            StorageをVMに追加する
          </div>
          <div class="card-body">
            <form action="{{ route('problems.machines.storages.attach', [
              'problem' => $problem,
              'machine' => $machine,
            ]) }}" method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="storage_id">Storage</label>
                <select id="storage_id" name="storage_id">
                  @foreach($problem->storages()->whereNotIn('id', array_column($machine->attachedStorages->toArray(), 'id'))->get() as $s)
                    <option value="{{ $s->id }}">
                      {{ $s->name }}
                    </option>
                  @endforeach
                </select>
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

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
            <?php
              $storages = $problem->storages()->whereNotIn('id', array_column($machine->attachedStorages->toArray(), 'id'))->get();
            ?>
            @if(count($storages) == 0)
              使用できるストレージがありません。
              <div>
                <a class="btn btn-secondary" href="{{ route('problems.show', [
                  'problem' => $problem,
                ]) }}">戻る</a>
              </div>
            @else 
            <form action="{{ route('problems.machines.storages.attach', [
              'problem' => $problem,
              'machine' => $machine,
            ]) }}" method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="storage_id">Storage</label>
                <select id="storage_id" name="storage_id" class="form-control">
                  @foreach($storages as $s)
                  @if(count($s->machines()->get()->toArray()) == 0)
                  <option value="{{ $s->id }}">
                    {{ $s->name }}
                  </option>
                  @endif
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
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('Storage追加')])

@section('content')
<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            Storageを追加する
          </div>
          <div class="card-body">
            <form action="{{ route('problems.storages.store', ['problem' => $problem]) }}" method="POST">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="name">Storage名</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
              <div class="form-group">
                <label for="size">size(単位はG, M, Kを使用できます)</label>
                <input type="text" class="form-control" id="size" name="size">
              </div>
              <div class="form-group">
                <label for="image_name">image_name</label>
                <select class="form-control" id="image_name" name="image_name">
                @foreach($imageList as $image)
                  <option value="{{$image->meta->id}}">{{$image->meta->id}}</option>
                @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="image_tag">image_tag</label>
                <select class="form-control" id="image_tag" name="image_tag">
                </select>
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

<script>

const imageList = @json($imageList);
console.log(imageList);
const imageNameSelect = document.getElementById('image_name');

const updateTagSelectOptions = (image_id) => {
  const image = imageList.find((i) => i.meta.id == image_id);

  const imageTagSelect = document.getElementById('image_tag');

  while(imageTagSelect.lastChild) {
    imageTagSelect.removeChild(imageTagSelect.lastChild);
  }

  let i = 0;
  image.spec.entityMap && Object.keys(image.spec.entityMap).forEach((k) => {
    imageTagSelect[i++] = new Option(k, k);
  })
}

updateTagSelectOptions(imageList[0].meta.id);
imageNameSelect.addEventListener('change', (e) => {
  updateTagSelectOptions(e.target.value);
})


</script>
@endsection

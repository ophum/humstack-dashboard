@extends('layouts.app', ['activePage' => 'images', 'titlePage' => __("Image")])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <a href="{{route('images')}}" class="btn btn-default">
                戻る
            </a>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-danger">
                        Image {{$image->meta->id}}
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>tag</th>
                                <th>entity id</th>
                                <th>status</th>
                                <th></th>
                            </thead>

                            <tbody>
                                @foreach($image->spec->entityMap ?? [] as $tag => $id)
                                <tr>
                                    <td>{{$tag}}</td>
                                    <td><a href="{{route('image-entities.show', ['id' => $id])}}">{{$id}}</a></td>
                                    <td>
                                        <span class="badge badge-pill
                                        @if($entityStatusMap[$tag] == 'Available')
                                            badge-success
                                        @endif
                                        ">{{$entityStatusMap[$tag]}}</span>
                                    </td>
                                    <td>
                                        <form
                                            action="{{ route('images.untag', ['imageName' => $image->meta->id]) }}"
                                            method="POST"
                                            onsubmit="return check()">
                                            {{ csrf_field() }}
                                            <input type="hidden" id="tag" name="tag" value="{{$tag}}">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="material-icons">close</i>
                                            </button>
                                        </form>
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
@endsection

<script>

function check() {
    return window.confirm('本当に削除しますか?');
}
</script>
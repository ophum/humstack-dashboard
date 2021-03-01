@extends('layouts.app', ['activePage' => 'images', 'titlePage' => __("ImageEntity")])

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
                        ImageEntity {{ $imageEntity->meta->id }}
                    </div>
                    <div class="card-body">
                        <strong>id</strong>
                        <p>{{$imageEntity->meta->id}}</p>
                        <strong>image type</strong>
                        <p>{{$imageEntity->spec->type === "" ? "Local" : $imageEntity->spec->type}}</p>
                        <strong>image source type</strong>
                        <p>{{$imageEntity->spec->source->type === "" ? "BlockStorage" : $imageEntity->spec->source->type}}</p>
                        <strong>image source</strong>
                        <p>
                            @if($imageEntity->spec->source->type === "Image")
                                {{$imageEntity->spec->source->imageName}}:{{$imageEntity->spec->source->imageTag}}
                            @else
                                {{$imageEntity->spec->source->namespace}}/{{$imageEntity->spec->source->blockStorageID}}
                            @endif
                        </p>
                        <strong>hash</strong>
                        <p>{{$imageEntity->spec->hash}}</p>
                        <strong>state</strong>
                        <p>
                            @if($imageEntity->meta->deleteState === "Delete")
                                <span class="badge badge-pill badge-danger">Delete</span>
                            @else
                                <span class="badge badge-pill @if($imageEntity->status->state == 'Available') badge-success @else badge-default @endif">{{$imageEntity->status->state}}</span>
                            @endif
                        </p>
                        <strong>created_at</strong>
                        <p>{{$imageEntity->meta->annotations['created_at']}}</p>
                        <td>
                        </td>
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

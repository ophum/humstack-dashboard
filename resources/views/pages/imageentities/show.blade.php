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
                        ImageEntity
                        {{var_dump($imageEntity)}}
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>id</th>
                                <th>source</th>
                                <th>hash</th>
                                <th>state</th>
                                <th>created_at</th>
                                <th></th>
                            </thead>

                            <tbody>
                                <td>{{$imageEntity->meta->id}}</td>
                                <td>{{$imageEntity->spec->source->namespace}}/{{$imageEntity->spec->source->blockStorageID}}</td>
                                <td>{{$imageEntity->spec->hash}}</td>
                                <td>
                                    @if($imageEntity->meta->deleteState === "Delete")
                                        <span class="badge badge-pill badge-danger">Delete</span>
                                    @else
                                        <span class="badge badge-pill @if($imageEntity->status->state == 'Available') badge-success @else badge-default @endif">{{$imageEntity->status->state}}</span>
                                    @endif
                                </td>
                                <td>{{$imageEntity->meta->annotations['created_at']}}</td>
                                <td>
                                </td>
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
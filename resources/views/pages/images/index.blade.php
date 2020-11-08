@extends('layouts.app', ['activePage' => 'problem', 'titlePage' => __('イメージ一覧')])

@section('content')
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-danger">
                        イメージ一覧
                    </div>
                    <div class="card-body">
                        <a href="{{route('images.create')}}" class="btn btn-primary">
                            追加
                        </a>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>image</th>
                                    <th>tag => entityID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($imageList as $image)
                                <tr>
                                    <td>{{ $image->meta->id }}</td>
                                    <td>
                                        <ul>
                                            @foreach($image->spec->entityMap ?? [] as $tag => $entityID)
                                            <li>{{$tag}} => {{$entityID}}</li>
                                            @endforeach
                                        </ul>
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
@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.post.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.posts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.post.fields.id') }}
                        </th>
                        <td>
                            {{ $post->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.post.fields.user') }}
                        </th>
                        <td>
                            {{ $post->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.post.fields.body') }}
                        </th>
                        <td>
                            {{ $post->body ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.post.fields.media') }}
                        </th>
                        <td>
                            @foreach($post->media as $key => $media)
                                @if ($media->type == 'image')
                                    <a href="{{ $media->media_link }}" target="_blank" style="display: inline-block">
                                        <img width="100" height="100" src="{{ $media->media_link }}">
                                    </a>
                                @elseif ($media->type == 'video')
                                    <a href="{{ $media->media_link }}" target="_blank" style="display: inline-block">
                                        <video width="400" height="400" src="{{ $media->media_link }}" controls />
                                    </a>
                                @else
                                <a href="{{ $media->media_link }}" target="_blank" style="display: inline-block">
                                    View file
                                </a>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.posts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>

        <h4 class="mt-4">Comments:</h4>

        <form method="post" action="{{ route('admin.posts.comment.store') }}">
            @csrf
            <div class="form-group">
                <textarea class="form-control" name="body" placeholder="Write Your Comment..."></textarea>
                <input type="hidden" name="post_id" value="{{ $post->id }}" />
            </div>
            <div class="form-group text-end">
                <button class="btn btn-success mt-2"><i class="fa fa-comment"></i> Add Comment</button>
            </div>
        </form>
        
        <hr/>
        @include('admin.posts.comments', ['comments' => $post->comments, 'post_id' => $post->id])
    </div>
</div>



@endsection
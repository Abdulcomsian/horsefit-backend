@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.post.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.posts.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="body">{{ trans('cruds.post.fields.body') }}</label>
                <textarea class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}" name="body" id="body" required>{{ old('body') }}</textarea>
                @if($errors->has('body'))
                    <div class="invalid-feedback">
                        {{ $errors->first('body') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.body_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">{{ trans('cruds.post.fields.status') }}</label>
                </div>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="visibility">{{ trans('cruds.post.fields.visibility') }}</label>
                <select class="form-control select2 {{ $errors->has('visibility') ? 'is-invalid' : '' }}" name="visibility" id="visibility" required>
                    @foreach($visibilityOptions as $id => $entry)
                        <option value="{{ $id }}" {{ old('visibility') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('visibility'))
                    <div class="invalid-feedback">
                        {{ $errors->first('visibility') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.visibility_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="media">{{ trans('cruds.post.fields.media') }}</label>
                <div class="needsclick dropzone {{ $errors->has('media') ? 'is-invalid' : '' }}" id="media-dropzone">
                </div>
                @if($errors->has('media'))
                    <div class="invalid-feedback">
                        {{ $errors->first('media') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.media_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    var uploadedMediaMap = {}
Dropzone.options.mediaDropzone = {
    url: '{{ route('admin.posts.storeMedia') }}',
    maxFilesize: 10, // MB
    acceptedFiles: 'image/*, video/*',
    maxFiles: 100,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    // params: {
    //   size: 2,
    //   width: 4096,
    //   height: 4096
    // },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="media[]" value="' + response.name + '">')
      uploadedMediaMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedMediaMap[file.name]
      }
      $('form').find('input[name="media[]"][value="' + name + '"]').remove()
    },
    init: function () {
    @if(isset($post) && $post->media)
        var files = {!! json_encode($post->media) !!}
            for (var i in files) {
            var file = files[i]
            this.options.addedfile.call(this, file)
            this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="media[]" value="' + file.file_name + '">')
            }
    @endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}

</script>
@endsection
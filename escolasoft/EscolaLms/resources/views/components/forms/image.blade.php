<label class="form-control-label">
  {{ $labelName  }}
  {!! $required ? '<span class="required">*</span>' : '' !!}
</label>

<input type="file"
       class="form-control p-1 @error($name) border-danger @enderror"
       name="{{$name}}"
       value="{{ old($name, $value) }}">

@if($value)
  <a href="{{Storage::url($value)}}" target="_blank" class="mt-10 block">
    Show: ({{$value}})
  </a>
@endif

@error($name)
<div class="alert alert-danger">{{ $message }}</div>
@enderror

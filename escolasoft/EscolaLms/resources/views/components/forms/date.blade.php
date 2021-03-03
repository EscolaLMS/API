<label class="form-control-label">
  {{ $labelName  }}
  {!! $required ? '<span class="required">*</span>' : '' !!}
</label>
<input type="date" class="{{ $class  }} @error($name) border-danger @enderror" name="{{$name}}"
       placeholder="{{ $labelName  }}" value="{{ old($name, $value) }}">
@error($name)
<div class="alert alert-danger">{{ $message }}</div>
@enderror

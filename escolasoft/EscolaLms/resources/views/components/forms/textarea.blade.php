<label class="form-control-label">
  {{ ucfirst($name)  }}
  {!! $required ? '<span class="required">*</span>' : '' !!}
</label>
<textarea rows="{{ $rows }}" class="{{ $class  }} @error($name) border-danger @enderror" name="{{$name}}"
          placeholder="{{ ucfirst($name)  }}">{!! old($name, $value) !!}</textarea>
@error($name)
<div class="alert alert-danger">{{ $message }}</div>
@enderror

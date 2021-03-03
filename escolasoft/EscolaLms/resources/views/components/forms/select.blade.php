<label class="form-control-label">
  {{ $labelName }}
  {!! $required ? '<span class="required">*</span>' : '' !!}
</label>

<select name="{{$name}}" class="{{ $class  }} @error($name) border-danger @enderror">
  @if(!$hideFirst)
    <option value="">{{$labelName}}</option>
  @endif
  @foreach($options as $val => $label)
    <option value="{{$val}}"
            @if(($val == $value) && ($value !== '' && $value !== null)) selected @endif>{{$label}}</option>
  @endforeach
</select>

@error($name)
<div class="alert alert-danger">{{ $message }}</div>
@enderror


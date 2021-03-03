<div class="form-group block row px-20">
  <div class="float-right">
    @if(request()->get('page', 1) > 1)
      <a href="?{{ http_build_query(array_merge(request()->all(), ['page' => request()->get('page') - 1]))  }}"
         class="btn btn-default btn-outline">{{ __('mad::mad.Previous') }}</a>
    @endif

    @if($collection->count() >= 10)
      <a href="?{{ http_build_query(array_merge(request()->all(), ['page' => request()->get('page', 1) + 1]))  }}"
         class="btn btn-default btn-outline">{{ __('mad::mad.Next') }}</a>
    @endif
  </div>
</div>

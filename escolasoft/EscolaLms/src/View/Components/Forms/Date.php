<?php

namespace EscolaLms\Core\View\Components\Forms;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Date extends Component
{
    public string $name;
    public bool $required;
    public string $value;
    public string $labelName;
    public string $class;

    /**
     * Input constructor.
     * @param string $name
     * @param bool $required
     * @param string $value
     * @param string $type
     * @param string|null $labelName
     */
    public function __construct(
        string $name,
        bool $required = false,
        string $value = '',
        ?string $labelName = null,
        string $class = 'form-control',
        ?Model $model = null
    ) {
        $this->name = $name;
        $this->required = $required;
        $this->labelName = $labelName ?? Str::title(str_replace('_', ' ', $name));
        $this->class = $class;
        $model = $model ?? View::shared('model');
        $date = old($name, request()->get($name) ?? $model->$name ?? $value);
        $this->value = $date ? (new Carbon($date))->format('Y-m-d') : '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('escola-lms::components.forms.date');
    }
}

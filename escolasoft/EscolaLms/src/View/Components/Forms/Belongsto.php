<?php

namespace EscolaLms\Core\View\Components\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Belongsto extends Component
{
    public bool $required;
    public string $name;
    public string $value;
    public string $labelName;
    public array $options;
    public string $class;
    public bool $hideFirst;

    /**
     * BelongsTo constructor.
     * @param Model $model
     * @param string $relation
     * @param string $keyName
     * @param bool $required
     * @param string|null $labelName
     */
    public function __construct(
        string $relation,
        string $keyName,
        ?Model $model = null,
        bool $required = false,
        ?string $labelName = null,
        string $class = 'form-control',
        bool $hideFirst = false
    ) {
        $this->required = $required;
        $model = $model ?? View::shared('model');
        $this->name = $model->$relation()->getForeignKeyName();
        $this->value = (string)$model->{$this->name};
        $this->labelName = $labelName ?? Str::title(str_replace('_', ' ', $relation));
        $this->options = $model->$relation()->getRelated()->pluck($keyName, $model->$relation()->getRelated()->getKeyName())->toArray();
        $this->class = $class;
        $this->hideFirst = $hideFirst;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('escola-lms::components.forms.select');
    }
}

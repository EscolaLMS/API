<?php

namespace EscolaSoft\EscolaLms\View\Components\Forms;

use EscolaSoft\EscolaLms\View\Traits\MakeValues;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Image extends Component
{
    use MakeValues;

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
     * @param Model|null $model
     * @param string|null $labelName
     * @param string $class
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
        $this->makeValue($name, $model, $value);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('escola-lms::components.forms.image');
    }
}

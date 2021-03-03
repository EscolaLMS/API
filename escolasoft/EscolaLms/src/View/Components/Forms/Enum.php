<?php

namespace EscolaSoft\EscolaLms\View\Components\Forms;

use EscolaSoft\EscolaLms\View\Traits\MakeValues;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Enum extends Component
{
    use MakeValues;

    public bool $required;
    public string $name;
    public ?string $value;
    public string $labelName;
    public array $options;
    public string $class;
    public bool $hideFirst;

    public function __construct(
        string $enum,
        string $name,
        bool $hideFirst = true,
        bool $required = false,
        ?Model $model = null,
        ?string $value = null,
        ?string $labelName = null,
        string $class = 'form-control'
    ) {
        $this->required = $required;
        $this->class = $class;
        $this->name = $name;
        $this->labelName = $labelName ?? Str::title(str_replace('_', ' ', $name));
        $this->options = $enum::getAssoc();
        $this->hideFirst = $hideFirst;
        $this->makeValue($name, $model, $value);
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

<?php

namespace EscolaLms\Core\View\Components\Forms;

use EscolaLms\Core\View\Traits\MakeValues;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Textarea extends Component
{
    use MakeValues;

    public string $name;
    public bool $required;
    public string $value;
    public int $rows;
    public string $labelName;
    public string $class;

    /**
     * Textarea constructor.
     * @param string $name
     * @param bool $required
     * @param string $value
     * @param string|null $labelName
     * @param int $rows
     */
    public function __construct(
        string $name,
        bool $required = false,
        string $value = '',
        ?Model $model = null,
        ?string $labelName = null,
        int $rows = 5,
        string $class = 'form-control'
    ) {
        $this->name = $name;
        $this->required = $required;
        $this->rows = $rows;
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
        return view('escola-lms::components.forms.textarea');
    }
}

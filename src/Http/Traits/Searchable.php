<?php


namespace Devzone\Ams\Http\Traits;


use Devzone\Ams\Models\ChartOfAccount;
use Illuminate\Support\Str;

trait Searchable
{
    public $searchable_query = '';
    public $searchable_data = [];
    public $highlight_index = 0;
    public $searchable_modal = false;
    public $searchable_id;
    public $searchable_name;
    public $searchable_type;

    public $searchable_column = [
        'accounts' => ['name', 'code', 'type'],

    ];


    public function searchableOpenModal($id, $name, $type)
    {
        $this->searchable_modal = true;
        $this->searchable_id = $id;
        $this->searchable_name = $name;
        $this->searchable_type = $type;
        $this->emit('focusInput');
    }

    public function incrementHighlight()
    {
        if ($this->highlight_index === count($this->searchable_data) - 1) {
            $this->highlight_index = 0;
            return;
        }
        $this->highlight_index++;
    }

    public function decrementHighlight()
    {
        if ($this->highlight_index === 0) {
            $this->highlight_index = count($this->searchable_data) - 1;
            return;
        }
        $this->highlight_index--;
    }

    public function searchableSelection($key = null)
    {
        if (!empty($key)) {
            $this->highlight_index = $key;
        }
        $data = $this->searchable_data[$this->highlight_index] ?? null;

        $this->{$this->searchable_id} = $data['id'];
        $this->{$this->searchable_name} = $data['name'];
        $this->emitSelf(Str::camel('emit_' . $this->searchable_id));
        $this->searchableReset();
    }

    public function searchableReset()
    {
        $this->searchable_modal = false;
        $this->searchable_id = '';
        $this->searchable_name = '';
        $this->highlight_index = 0;
        $this->searchable_query = '';
        $this->searchable_type = '';
        $this->searchable_data = [];

    }

    public function updatedSearchableQuery($value)
    {
        if (strlen($value) > 1) {
            $this->highlight_index = 0;
            if ($this->searchable_type == 'accounts') {
                $search =
                    ChartOfAccount::where(function ($q) use ($value) {
                        return $q->orWhere('name', 'LIKE', '%' . $value . '%')
                            ->orWhere('code', 'LIKE', '%' . $value . '%')
                            ->orWhere('type', 'LIKE', '%' . $value . '%');
                    })->where('level', '5')->where('status', 't')
                        ->get();
                if ($search->isNotEmpty()) {
                    $this->searchable_data = $search->toArray();
                } else {
                    $this->searchable_data = [];
                }
            }


        } else {
            $this->searchable_data = [];
        }
    }


}

<?php


namespace Devzone\Ams\Http\Traits;


use App\Models\User;
use Illuminate\Support\Facades\Schema;

trait UserSearchable
{
    public $user_query = '';
    public $user_data = [];
    public $user_highlight_index = 0;
    public $user_modal = false;
    public $user_id_field;
    public $user_name_field;
    public $user_label = 'User';

    public $user_column = ['name'];

    /**
     * $id / $name are the properties the picked user is written back to and
     * may be dotted paths, e.g. 'admin_expenses.requisite_by'.
     * $label is the wording shown in the modal, e.g. 'Requisite By'.
     */
    public function userOpenModal($id, $name, $label = 'User')
    {
        $this->dispatchBrowserEvent('open-user-modal');
        $this->user_modal = true;
        $this->user_id_field = $id;
        $this->user_name_field = $name;
        $this->user_label = $label;
        $this->emit('focusUserInput');
    }

    public function userIncrementHighlight()
    {
        if ($this->user_highlight_index === count($this->user_data) - 1) {
            $this->user_highlight_index = 0;
            return;
        }
        $this->user_highlight_index++;
    }

    public function userDecrementHighlight()
    {
        if ($this->user_highlight_index === 0) {
            $this->user_highlight_index = count($this->user_data) - 1;
            return;
        }
        $this->user_highlight_index--;
    }

    public function userSelection($key = null)
    {
        if (!empty($key)) {
            $this->user_highlight_index = $key;
        }
        $data = $this->user_data[$this->user_highlight_index] ?? null;

        data_set($this, $this->user_id_field, !empty($data['id']) ? $data['id'] : '');
        data_set($this, $this->user_name_field, !empty($data['name']) ? $data['name'] : '');
        $this->userReset();
    }

    public function userReset()
    {
        $this->dispatchBrowserEvent('close-user-modal');
        $this->user_modal = false;
        $this->user_id_field = '';
        $this->user_name_field = '';
        $this->user_highlight_index = 0;
        $this->user_query = '';
        $this->user_data = [];
    }

    public function updatedUserQuery($value)
    {
        if (strlen($value) > 1) {
            $this->user_highlight_index = 0;
            // users table differs per host app: only filter on columns that exist.
            $this->user_data = User::select('id', 'name')
                ->where('name', 'LIKE', '%' . $value . '%')
                ->when(Schema::hasColumn('users', 'type'), function ($q) {
                    return $q->where('type', 'admin');
                })
                ->when(Schema::hasColumn('users', 'status'), function ($q) {
                    return $q->where('status', 't');
                })
                ->orderBy('name')->limit(50)->get()->toArray();
        } else {
            $this->user_data = [];
        }
    }
}

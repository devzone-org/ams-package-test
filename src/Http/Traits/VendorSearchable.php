<?php


namespace Devzone\Ams\Http\Traits;


use Devzone\Ams\Models\AccVendor;

trait VendorSearchable
{
    public $vendor_query = '';
    public $vendor_data = [];
    public $vendor_highlight_index = 0;
    public $vendor_modal = false;
    public $vendor_id_field;
    public $vendor_name_field;

    public $vendor_column = ['business_name', 'owner_name', 'contact_no', 'business_address'];

    /**
     * $id / $name are the properties the picked vendor is written back to and
     * may be dotted paths, e.g. 'admin_expenses.vendor_id'.
     */
    public function vendorOpenModal($id, $name)
    {
        $this->dispatchBrowserEvent('open-vendor-modal');
        $this->vendor_modal = true;
        $this->vendor_id_field = $id;
        $this->vendor_name_field = $name;
        $this->emit('focusVendorInput');
    }

    public function vendorIncrementHighlight()
    {
        if ($this->vendor_highlight_index === count($this->vendor_data) - 1) {
            $this->vendor_highlight_index = 0;
            return;
        }
        $this->vendor_highlight_index++;
    }

    public function vendorDecrementHighlight()
    {
        if ($this->vendor_highlight_index === 0) {
            $this->vendor_highlight_index = count($this->vendor_data) - 1;
            return;
        }
        $this->vendor_highlight_index--;
    }

    public function vendorSelection($key = null)
    {
        if (!empty($key)) {
            $this->vendor_highlight_index = $key;
        }
        $data = $this->vendor_data[$this->vendor_highlight_index] ?? null;

        data_set($this, $this->vendor_id_field, !empty($data['id']) ? $data['id'] : '');
        data_set($this, $this->vendor_name_field, !empty($data['business_name']) ? $data['business_name'] : '');
        $this->vendorReset();
    }

    public function vendorReset()
    {
        $this->dispatchBrowserEvent('close-vendor-modal');
        $this->vendor_modal = false;
        $this->vendor_id_field = '';
        $this->vendor_name_field = '';
        $this->vendor_highlight_index = 0;
        $this->vendor_query = '';
        $this->vendor_data = [];
    }

    public function updatedVendorQuery($value)
    {
        if (strlen($value) > 1) {
            $this->vendor_highlight_index = 0;
            $this->vendor_data = AccVendor::where(function ($q) use ($value) {
                return $q->orWhere('business_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('owner_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('contact_no', 'LIKE', '%' . $value . '%');
            })->select('id', 'business_name', 'owner_name', 'contact_no', 'business_address')
                ->orderBy('business_name')->limit(50)->get()->toArray();
        } else {
            $this->vendor_data = [];
        }
    }
}

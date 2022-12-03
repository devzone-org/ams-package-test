<?php

namespace Devzone\Ams\Http\Livewire\Sidebar;

use App\Models\Favourite;
use Livewire\Component;

class SidebarLinks extends Component
{

    public $fetch_favourites;

    public function selectedFavourite($name, $url)
    {
        try {

            $count = Favourite::count();
            $found = Favourite::where('name', $name)->where('url', $url)->first();
            if (!empty($found)) {
                $found->delete();
            } else {

                if ($count < 5) {

                    if (empty($found)) {
                        Favourite::updateOrCreate([
                            'name' => $name,
                            'url' => $url
                        ], []
                        );
                    }
                } else {
                    throw new \Exception('You can only add five links as favourites.');
                }
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('error', ['title' => 'Error', 'description' => $e->getMessage()]);
        }

    }

    public function deleteFavourite($id)
    {
        Favourite::find($id)->delete();
    }

    public function fetchFavourites()
    {
        $this->fetch_favourites = Favourite::select('url', 'name', 'id')->get()->toArray();
    }

    public function render()
    {
        $this->fetchFavourites();
        return view('ams::livewire.sidebar.sidebar-links');
    }

}
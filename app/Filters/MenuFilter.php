<?php

namespace App\Filters;

use Illuminate\Support\Facades\Gate;
// use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class MenuFilter implements FilterInterface
{
    public function transform($item)
    {

        echo "ada";
        // Jika item menu memiliki properti 'can', periksa izinnya.
        // Jika pengguna tidak memiliki izin, item tersebut tidak akan ditampilkan.
        if (isset($item['can']) && ! Gate::allows($item['can'])) {
            return false;
        }

        return $item;
    }
}

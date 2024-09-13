<?php

namespace App\Filters;

use App\Models\AccessModel;
use App\Models\MenuModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $request = \Config\Services::request();
        $menumodel = new MenuModel();
        $accessmodel = new AccessModel();

        $role = session()->get('role');
        $menu = $request->uri->getSegment(1);
        $queryMenu = $menumodel->getMenuAccess($menu);
        if ($queryMenu) {
            $menu_id = $queryMenu->id_menu;
            $userAccess = $accessmodel->getRoleAccess($role, $menu_id);
            if ($userAccess == null) {
                return redirect()->to('blocked');
            }
        } else {
            return redirect()->to('blocked'); // Handle case where menu does not exist
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}

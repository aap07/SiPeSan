<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Services;

class MobileBlocker implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $agent = $request->getUserAgent();

        if ($agent->isMobile()) {
            return redirect()->to('https://play.google.com/store/search?q=pubg&c=apps');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak diperlukan apa-apa di sini
    }
}

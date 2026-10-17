<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LocaleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // IDIOMA
        $locale = $session->get('locale') ?? 'es';
        $request->setLocale($locale);
        service('language')->setLocale($locale);

        // RECORDARMEEE
        if (! $session->get('user_id')) {
            $cookie = $request->getCookie('remember');

            if ($cookie && str_contains($cookie, ':')) {
                [$id, $token] = explode(':', $cookie, 2);
                $user = (new UserModel())->find((int) $id);

                if ($user
                    && $user['active']
                    && ! empty($user['remember_token'])
                    && hash_equals($user['remember_token'], hash('sha256', $token))
                ) {
                    $session->set([
                        'user_id'  => $user['id'],
                        'name'     => $user['name'],
                        'is_admin' => (bool) $user['is_admin'],
                    ]);
                    $session->regenerate(true);
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
<?php

namespace App\Http\Admin;

trait WebDestinations
{
    public function destinationRoute(?string $route = null, array $routeParams = []): string
    {
        if ($route) {
            return $this->makeDestinationUrl(route($route, $routeParams));
        }

        return $this->makeDestinationUrl();
    }

    public function destinationUrl(?string $url = null): string
    {
        if ($url) {
            return $this->makeDestinationUrl(url($url));
        }

        return $this->makeDestinationUrl();
    }

    private function makeDestinationUrl(?string $altUrl = null): string
    {
        if (request()->session()->has('_destination')) {
            return request()->session()->pull('_destination');
        } if (request()->get('_back')) {
            return request()->get('_back');
        } if (request()->get('_destination')) {
            return request()->get('_destination');
        } elseif ($altUrl) {
            return url($altUrl);
        }

        return url()->previous();
    }
}

<?php

namespace NI\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NIUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

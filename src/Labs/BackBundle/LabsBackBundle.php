<?php

namespace Labs\BackBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LabsBackBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

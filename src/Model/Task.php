<?php

namespace CalendArt\Adapter\Office365\Model;

use CalendArt\AbstractTask;

class Task extends AbstractTask
{
    private $id;

    public function getId()
    {
        return $this->id;
    }


}
<?php

namespace CalendArt\Adapter\Office365\Api;

use CalendArt\Adapter\Office365\Office365Adapter;
use CalendArt\Adapter\TaskGroupApiInterface;

class TaskGroupApi implements TaskGroupApiInterface
{
    /**
     * @var Office365Adapter
     */
    private $adapter;

    /**
     * TaskGroupApi constructor.
     * @param Office365Adapter $adapter
     */
    public function __construct(Office365Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritdoc
     */
    public function getList()
    {
        // TODO: Implement getList() method.
    }

    /**
     * @inheritdoc
     */
    public function get($identifier)
    {
        // TODO: Implement get() method.
    }
}
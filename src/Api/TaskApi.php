<?php

namespace CalendArt\Adapter\Office365\Api;

use CalendArt\AbstractTask;
use CalendArt\Adapter\Office365\Office365Adapter;
use CalendArt\Adapter\TaskApiInterface;
use Doctrine\Common\Collections\ArrayCollection;

class TaskApi implements TaskApiInterface
{
    /**
     * @var Office365Adapter Office365 Adapter used
     */
    private $adapter;

    /**
     * TaskApi constructor.
     * @param Office365Adapter $adapter
     */
    public function __construct(Office365Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritDoc}
     */
    public function getList()
    {
        //TODO: Implement getList function
    }

    /**
     * {@inheritDoc}
     */
    public function get($identifier)
    {
        // TODO: Implement get function
    }

    /**
     * {@inheritDoc}
     */
    public function persist(AbstractTask $event, array $options = [])
    {
        // TODO: Implement persist function
    }
}

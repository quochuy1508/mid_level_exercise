<?php

namespace Magenest\CLI\Api;

interface CancelOrderWithStatusInterface
{
    /**
     * @param string $statusOrder
     * @return boolean
     */
    public function execute($statusOrder);
}

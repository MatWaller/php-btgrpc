<?php

namespace Waller\Gold\Exceptions;

use RuntimeException;

class GolddException extends RuntimeException
{
    /**
     * Constructs new Goldd exception.
     *
     * @param object $error
     *
     * @return void
     */
    public function __construct($error)
    {
        parent::__construct($error['message'], $error['code']);
    }
}

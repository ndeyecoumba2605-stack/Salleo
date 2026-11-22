<?php

namespace App\Exceptions;

use RuntimeException;

class SalleIndisponibleException extends RuntimeException
{
    public function __construct(
        string $message = "Cette salle vient d'être réservée entre-temps. "
            ."Relancez une recherche."
    ) {
        parent::__construct($message);
    }
}
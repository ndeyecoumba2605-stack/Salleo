<?php
 
namespace App\Exceptions;
 
use RuntimeException;
 
class AnnulationRefuseeException extends RuntimeException
{
    public function __construct(string $message = "")
    {
        parent::__construct($message ?: sprintf(
            "Annulation impossible : le délai autorisé (%d h avant le début) "
            ."est dépassé.",
            config('salleo.delai_annulation_heures')
        ));
    }
}

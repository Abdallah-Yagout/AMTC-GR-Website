<?php

namespace App\Games\Contracts;

interface MemoryGameVerifier
{
    /**
     * @param  array<int, int>  $deck  symbol id at each card index
     * @param  array<int, int>  $flipSequence  card indices in click order
     */
    public function isWinningSequence(array $deck, array $flipSequence): bool;
}

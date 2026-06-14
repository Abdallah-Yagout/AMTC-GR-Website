<?php

namespace App\Games\Matching;

use App\Games\Contracts\MemoryGameVerifier;

class MatchingGameEngine implements MemoryGameVerifier
{
    /**
     * Build shuffled deck: each symbol 0..pairCount-1 appears exactly twice.
     *
     * @return array<int, int>
     */
    public function buildShuffledDeck(int $pairCount): array
    {
        $pairCount = max(4, min(10, $pairCount));
        $symbols = range(0, $pairCount - 1);
        $deck = array_merge($symbols, $symbols);

        for ($i = count($deck) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$deck[$i], $deck[$j]] = [$deck[$j], $deck[$i]];
        }

        return $deck;
    }

    /**
     * Replay standard memory rules: flip two at a time; match stays, mismatch flips back.
     *
     * @param  array<int, int>  $deck
     * @param  array<int, int>  $flipSequence
     */
    public function isWinningSequence(array $deck, array $flipSequence): bool
    {
        $n = count($deck);
        if ($n === 0 || $n % 2 !== 0) {
            return false;
        }

        $matched = [];
        $pending = null;

        foreach ($flipSequence as $idx) {
            if (! is_int($idx) && ! is_numeric($idx)) {
                return false;
            }
            $idx = (int) $idx;
            if ($idx < 0 || $idx >= $n) {
                return false;
            }
            if (isset($matched[$idx])) {
                return false;
            }
            if ($pending === null) {
                $pending = $idx;

                continue;
            }
            if ($pending === $idx) {
                return false;
            }
            if ($deck[$pending] === $deck[$idx]) {
                $matched[$pending] = true;
                $matched[$idx] = true;
                $pending = null;
            } else {
                $pending = null;
            }
        }

        if ($pending !== null) {
            return false;
        }

        return count($matched) === $n;
    }

    public function minFlipsForWin(int $pairCount): int
    {
        return 2 * max(4, min(10, $pairCount));
    }

    public function maxFlipsAllowed(int $pairCount): int
    {
        $pairCount = max(4, min(10, $pairCount));

        return $pairCount * 80;
    }
}

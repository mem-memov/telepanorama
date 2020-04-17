<?php

declare(strict_types=1);

namespace Telepanorama\Showcase;

class ShowpieceCollection implements WithDescription
{
    private array $showPieces = [];

    public function add(ShowPiece $showPiece): void
    {
        $hasShowPiece = array_reduce(
            $this->showPieces,
            fn (bool $hasShowPiece, ShowPiece $currentShowPiece) => $hasShowPiece || $currentShowPiece->isEqual($showPiece),
            false
        );

        if ($hasShowPiece) {
            return;
        }

        $this->showPieces[] = $showPiece;
    }

    public function remove(ShowPiece $showPiece): void
    {
        $index = array_reduce(
            $this->showPieces,
            fn (array $indexes, ShowPiece $currentShowPiece) => $currentShowPiece->isEqual($showPiece)
                ? ['current' => ($indexes['current'] + 1), 'selected' => $indexes['current']]
                : ['current' => ($indexes['current'] + 1), 'selected' => $indexes['selected']],
            ['current' => 0, 'selected' => null]
        );

        if (null === $index['selected']) {
            return;
        }

        unset($this->showPieces[$index['selected']]);

        $this->remove($showPiece);
    }

    public function iterate(callable $use): void
    {
        foreach ($this->showPieces as $showPiece) {
            $use($showPiece);
        }
    }

    public function getDescription(): array
    {
        return array_map(
            function (ShowPiece $showPiece) {
                return $showPiece->getDescription();
            },
            $this->showPieces
        );
    }
}
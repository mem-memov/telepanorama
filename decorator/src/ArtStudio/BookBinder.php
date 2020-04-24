<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class BookBinder
{
    private Rectangles $rectangles;

    public function __construct(
        Rectangles $rectangles
    ) {
        $this->rectangles = $rectangles;
    }

    public function bind(Original $original): SketchBook
    {
        $prefix = basename($original->getAbsolutePath()) . '_';

        $sketches = [
            new Sketch(
                $this->rectangles->make8K(),
                $prefix . 'sketch8K.jpeg'
            ),
            new Sketch(
                $this->rectangles->make4K(),
                $prefix . 'sketch4K.jpeg'
            ),
            new Sketch(
                $this->rectangles->make1K(),
                $prefix . 'sketch1K.jpeg'
            ),
            new Sketch(
                $this->rectangles->makeHalfK(),
                $prefix . 'sketchHalfK.jpeg'
            ),
            new Sketch(
                $this->rectangles->makeQuoterK(),
                $prefix . 'sketchQuoterK.jpeg'
            )
        ];

        $sketchBook = $original->createSketchBook($sketches);

        return $sketchBook;
    }
}
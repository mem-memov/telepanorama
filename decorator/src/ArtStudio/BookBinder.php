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
        $sketchBook = $original->createSketchBook();

        $sketchBook->add(
            new Sketch(
                $this->rectangles->make8K(),
                'sketch8K.jpeg'
            )
        );

        $sketchBook->add(
            new Sketch(
                $this->rectangles->make4K(),
                'sketch4K.jpeg'
            )
        );

        $sketchBook->add(
            new Sketch(
                $this->rectangles->make1K(),
                'sketch1K.jpeg'
            )
        );

        $sketchBook->add(
            new Sketch(
                $this->rectangles->makeHalfK(),
                'sketchHalfK.jpeg'
            )
        );

        $sketchBook->add(
            new Sketch(
                $this->rectangles->makeQuoterK(),
                'sketchQuoterK.jpeg'
            )
        );

        $sketchBook->add(
            new Sketch(
                $this->rectangles->make8K(),
                'path'
            )
        );

        return $sketchBook;
    }
}
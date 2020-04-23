<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Artist
{
    private Glass $glass;
    private Ruler $ruler;
    private BookBinder $bookBinder;

    public function __construct(
        Glass $glass,
        Ruler $ruler,
        BookBinder $bookBinder
    ) {
        $this->glass = $glass;
        $this->ruler = $ruler;
        $this->bookBinder = $bookBinder;
    }

    public function paint(string $file): Album
    {
        $original = new Original($file);
        $sketchBook = $this->bookBinder->bind($original);

        $measuredOriginal = $this->ruler->putOnOriginal($original)->measureOriginal();

        $sketchBook->eachSketch(function (SketchInSketchBook $sketchInSketchBook) use ($measuredOriginal) {
            $this->glass->diminish(
                $measuredOriginal,
                $sketchInSketchBook
            );
        });

        return $sketchBook->toAlbum();
    }
}
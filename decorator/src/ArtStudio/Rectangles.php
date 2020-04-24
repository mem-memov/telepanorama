<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Rectangles
{
    public function make8K(): Rectangle
    {
        return new Rectangle(new Width(7680), new Height(4320));
    }

    public function make4K(): Rectangle
    {
        return new Rectangle(new Width(3840), new Height(2160));
    }

    public function make1K(): Rectangle
    {
        return new Rectangle(new Width(1920), new Height(1080));
    }

    public function makeHalfK(): Rectangle
    {
        return new Rectangle(new Width(960), new Height(540));
    }

    public function makeQuoterK(): Rectangle
    {
        return new Rectangle(new Width(480), new Height(270));
    }
}
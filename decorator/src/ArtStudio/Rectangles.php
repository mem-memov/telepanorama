<?php

declare(strict_types=1);

namespace Telepanorama\ArtStudio;

class Rectangles
{
    public function make8K(): Rectangle
    {
        return new Rectangle(7680, 4320);
    }

    public function make4K(): Rectangle
    {
        return new Rectangle(3840, 2160);
    }

    public function make1K(): Rectangle
    {
        return new Rectangle(1920, 1080);
    }

    public function makeHalfK(): Rectangle
    {
        return new Rectangle(960, 540);
    }

    public function makeQuoterK(): Rectangle
    {
        return new Rectangle(480, 270);
    }
}
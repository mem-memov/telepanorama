<?php

namespace Telepanorama\Site;

class Factory
{
    public function createSite(): Application
    {
        return new Application(
            new Mode(),
            new Routing()
        );
    }
}

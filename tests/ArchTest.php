<?php

arch()
    ->expect('Lowel\\Telepath')
    ->toUseStrictTypes()
    ->not->toUse(['die', 'dd', 'dump']);

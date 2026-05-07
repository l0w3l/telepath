<?php

use Lowel\Telepath\Core\Router\Context\GroupContext;
use Lowel\Telepath\Core\Router\Context\RouteContext;
use Lowel\Telepath\Core\Router\Context\RouteContextParams;
use Lowel\Telepath\Enums\UpdateTypeEnum;

it('collects nested route contexts correctly', function () {
    // Arrange
    $rootGroup = new GroupContext;
    $mRoot = fn () => 'root';
    $rootGroup->middleware($mRoot);

    $subGroup = $rootGroup->wrap(new RouteContextParams);
    $mSub = fn () => 'sub';
    $subGroup->middleware($mSub);

    $route1 = new RouteContext(new RouteContextParams);
    $route1->type(UpdateTypeEnum::MESSAGE);
    $subGroup->appendRouteContext($route1);

    $route2 = new RouteContext(new RouteContextParams);
    $route2->type(UpdateTypeEnum::CALLBACK_QUERY);
    $rootGroup->appendRouteContext($route2);

    // Act
    $executors = $rootGroup->collect();

    // Assert
    expect($executors)->toHaveCount(2);

    // Route 1 (nested)
    expect($executors[0]->params()->getMiddlewares())->toBe([$mRoot, $mSub]);
    expect($executors[0]->params()->getUpdateTypeEnum())->toBe(UpdateTypeEnum::MESSAGE);

    // Route 2 (direct)
    expect($executors[1]->params()->getMiddlewares())->toBe([$mRoot]);
    expect($executors[1]->params()->getUpdateTypeEnum())->toBe(UpdateTypeEnum::CALLBACK_QUERY);
});

it('handles deeply nested groups', function () {
    $g1 = new GroupContext;
    $m1 = fn () => 'm1';
    $g1->middleware($m1);

    $g2 = $g1->wrap(new RouteContextParams);
    $m2 = fn () => 'm2';
    $g2->middleware($m2);

    $g3 = $g2->wrap(new RouteContextParams);
    $m3 = fn () => 'm3';
    $g3->middleware($m3);

    $r = new RouteContext(new RouteContextParams);
    $mr = fn () => 'mr';
    $r->middleware($mr);
    $r->type(UpdateTypeEnum::MESSAGE);
    $g3->appendRouteContext($r);

    $executors = $g1->collect();

    expect($executors)->toHaveCount(1);
    expect($executors[0]->params()->getMiddlewares())->toBe([$m1, $m2, $m3, $mr]);
});

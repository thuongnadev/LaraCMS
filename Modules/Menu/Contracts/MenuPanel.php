<?php

declare(strict_types=1);

namespace Modules\Menu\Contracts;

interface MenuPanel
{
    public function getIdentifier(): string;

    public function getName(): string;

    public function getItems(): array;

    public function getSort(): int;
}

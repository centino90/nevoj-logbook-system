<?php

namespace App\Filament\Resources\Filters;

use Filament\Tables\Filters\TernaryFilter as FiltersTernaryFilter;

class ServedFilter extends FiltersTernaryFilter
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->trueLabel('Only Served');
        $this->falseLabel('Without Served');
        $this->placeholder('All');

        $this->boolean();

        $this->queries(
            true: fn ($query) => $query->whereNotNull('served_at'),
            false: fn ($query) => $query->whereNull('served_at'),
        );

        $this->indicateUsing(function (array $state): array {
            if (blank($state['value'] ?? null)) {
                return [];
            }

            $stateLabel = $state['value'] ?
                $this->getTrueLabel() :
                $this->getFalseLabel();

            return ["{$this->getIndicator()}: {$stateLabel}"];
        });
    }
}

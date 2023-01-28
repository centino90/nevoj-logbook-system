<?php

namespace App\Filament\Resources\Filters;

use Filament\Tables\Filters\TernaryFilter as FiltersTernaryFilter;

class TernaryFilter extends FiltersTernaryFilter
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->trueLabel(__('forms::components.select.boolean.true'));
        $this->falseLabel(__('forms::components.select.boolean.false'));
        $this->placeholder('All');

        $this->boolean();

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

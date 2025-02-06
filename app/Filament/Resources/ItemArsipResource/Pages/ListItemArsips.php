<?php

namespace App\Filament\Resources\ItemArsipResource\Pages;

use App\Filament\Resources\ItemArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemArsips extends ListRecords
{
    protected static string $resource = ItemArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ItemArsipResource\Pages;

use App\Filament\Resources\ItemArsipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemArsip extends EditRecord
{
    protected static string $resource = ItemArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\InstallerResource\Pages;

use App\Filament\Resources\InstallerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInstaller extends CreateRecord
{
    protected static string $resource = InstallerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['operator_tag'] = 'نصاب';
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'نصاب جدید ثبت شد';
    }
}
<?php

namespace App\Filament\Pages;

use App\Filament\Schemas\QrCodeSettingsSchema;
use App\Models\QrCodeSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class QrSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?string $navigationLabel = 'QR code defaults';

    protected static ?string $title = 'QR code defaults';

    protected static ?string $slug = 'qr-settings';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->form->fill(QrCodeSetting::current()->toGeneratorSettings());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components(
            QrCodeSettingsSchema::schema(withPreview: true),
        );
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([$this->getSaveFormAction()])
                        ->alignment(Alignment::Start),
                ]),
        ]);
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Save defaults')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        unset($data['preview_url']);

        QrCodeSetting::current()->applySettings($data);

        Notification::make()
            ->title('QR code defaults saved')
            ->success()
            ->send();

        $this->fillForm();
    }
}

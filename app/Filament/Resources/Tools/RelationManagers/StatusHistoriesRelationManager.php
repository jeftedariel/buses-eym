<?php

namespace App\Filament\Resources\Tools\RelationManagers;

use BackedEnum;
use Dom\Text;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StatusHistoriesRelationManager extends RelationManager
{

    protected static string $relationship = 'statusHistories';

    protected static ?string $title = 'Historial de Estados';

    protected static ?string $modelLabel = 'Estado';

    protected static ?string $pluralModelLabel = 'Estados';

    protected static string|BackedEnum|null $icon = 'heroicon-o-clock';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsSection::make('Cambio de Estado')
                    ->description('Registra un nuevo estado para la herramienta')
                    ->icon('heroicon-o-arrow-path')
                    ->schema([
                        Select::make('status_id')
                            ->label('Estado')
                            ->relationship('status', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->placeholder('Selecciona un estado')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('nombre')
                            ]),

                        Textarea::make('description')
                            ->label('Descripción / Motivo')
                            ->placeholder('Describe el motivo del cambio de estado...')
                            ->helperText('Opcional: Agrega detalles sobre este cambio de estado')
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status.name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('status.name')
                    ->label('Estado')
                    ->badge()
                    ->color(fn($record) => match ($record->status?->name) {
                        'Disponible' => 'success',
                        'En Uso' => 'warning',
                        'En Mantenimiento' => 'info',
                        'Dañada' => 'danger',
                        'Fuera de Servicio' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn($record) => match ($record->status?->name) {
                        'Disponible' => 'heroicon-o-check-circle',
                        'En Uso' => 'heroicon-o-clock',
                        'En Mantenimiento' => 'heroicon-o-wrench',
                        'Dañada' => 'heroicon-o-exclamation-triangle',
                        'Fuera de Servicio' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->placeholder('Sin descripción')
                    ->tooltip(fn($record) => $record->description)
                    ->searchable()
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Fecha de Cambio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->tooltip(fn($record) => $record->created_at->format('l, d \d\e F \d\e Y \a \l\a\s H:i:s'))
                    ->color('gray')
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('created_at')
                    ->label('Hace')
                    ->since()
                    ->dateTimeTooltip('d/m/Y H:i:s')
                    ->color('gray')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filtrar por Estado')
                    ->relationship('status', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->placeholder('Todos los estados'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Agregar Estado')
                    ->icon('heroicon-o-plus-circle')
                    ->modalHeading('Registrar Nuevo Estado')
                    ->modalDescription('Cambia el estado de la herramienta y opcionalmente agrega una descripción')
                    ->modalIcon('heroicon-o-arrow-path')
                    ->successNotificationTitle('Estado registrado correctamente')
                    ->createAnother(false),

            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar')
                    ->modalHeading('Editar Estado')
                    ->modalDescription('Modifica los detalles de este cambio de estado')
                    ->modalIcon('heroicon-o-pencil')
                    ->modalWidth('lg')
                    ->successNotificationTitle('Estado actualizado correctamente'),

                DeleteAction::make()
                    ->label('Eliminar')
                    ->successNotificationTitle('Estado eliminado correctamente')
                    ->requiresConfirmation()
                    ->modalHeading('Eliminar Estado')
                    ->modalDescription('¿Estás seguro de que deseas eliminar permanentemente este registro del historial?')
                    ->modalIcon('heroicon-o-trash'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                    DeleteBulkAction::make()
                        ->label('Eliminar seleccionados')
                        ->successNotificationTitle('Estados eliminados correctamente')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Estados Seleccionados')
                        ->modalDescription('¿Estás seguro de que deseas eliminar permanentemente estos registros del historial?')
                        ->modalIcon('heroicon-o-trash'),
                ]),
            ])
            ->emptyStateHeading('Sin historial de estados')
            ->emptyStateDescription('Este vehículo aún no tiene estados registrados.')
            ->emptyStateIcon('heroicon-o-clock')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Agregar Primer Estado')
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }
}

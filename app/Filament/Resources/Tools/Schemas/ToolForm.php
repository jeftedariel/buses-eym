<?php
namespace App\Filament\Resources\Tools\Schemas;

use App\Models\Manufacturer;
use App\Models\ToolCategory;
use Dom\Text;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ToolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Código')
                    ->required(),
                TextInput::make('description')
                    ->label('Descripción'),
                FileUpload::make('images')
                            ->label('Imágenes')
                            ->image()
                            ->multiple()
                            ->imageEditor()
                            ->optimize('webp')
                            ->resize(50)
                            ->visibility('public')
                            ->directory('tools'),
                Select::make('type_id')
                    ->label('Tipo de Herramienta')
                    ->relationship('type', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre'),
                        TextInput::make('description')
                            ->label('Descripción'),
                        Select::make('manufacturer_id')
                            ->label('Fabricante')
                            ->relationship('manufacturer', 'name')
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->label('Nombre'),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return Manufacturer::create($data)->id;
                            }),
                        Select::make('category_id')
                            ->label('Categoría')
                            ->relationship('category', 'name')
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->label('Nombre'),
                                TextInput::make('description')
                                    ->label('Descripcion')
                            ])
                            ->createOptionUsing(function (array $data) {
                                return ToolCategory::create($data)->id;
                            }),

                    ]),
            ]);
        }
}

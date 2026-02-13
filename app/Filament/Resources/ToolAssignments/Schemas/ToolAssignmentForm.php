<?php

namespace App\Filament\Resources\ToolAssignments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ToolAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tool_id')
                    ->relationship('tool', 'id')
                    ->required(),
                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->required(),
                DateTimePicker::make('assigned_at')
                    ->required(),
                DateTimePicker::make('returned_at'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}

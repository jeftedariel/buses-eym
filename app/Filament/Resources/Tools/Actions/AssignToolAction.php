<?php

namespace App\Filament\Resources\Tools\Actions;

use App\Models\Employee;
use App\Models\ToolAssignment;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class AssignToolAction extends ActionsAction
{
    public static function getDefaultName(): ?string
    {
        return 'assignTool';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn ($record) => $record->isAssigned() ? 'Marcar Devolución' : 'Asignar')
            ->icon(fn ($record) => $record->isAssigned() ? 'heroicon-o-arrow-uturn-left' : 'heroicon-o-user-plus')
            ->color(fn ($record) => $record->isAssigned() ? 'success' : 'primary')
            ->modalHeading(fn ($record) => $record->isAssigned() ? 'Marcar Devolución de Herramienta' : 'Asignar Herramienta a Empleado')
            ->modalSubmitActionLabel(fn ($record) => $record->isAssigned() ? 'Registrar Devolución' : 'Asignar Herramienta')
            ->form(function ($record) {
                // Si está asignada, mostrar formulario de devolución
                if ($record->isAssigned()) {
                    $currentAssignment = $record->currentAssignment;
                    return [
                        DateTimePicker::make('returned_at')
                            ->label('Fecha y Hora de Devolución')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false)
                            ->helperText("Asignada a: {$currentAssignment->employee->name} el " . $currentAssignment->assigned_at->format('d/m/Y H:i')),

                        Textarea::make('return_notes')
                            ->label('Notas de Devolución (opcional)')
                            ->rows(3)
                            ->placeholder('Estado de la herramienta, observaciones, etc.'),
                    ];
                }

                // Si no está asignada, mostrar formulario de asignación
                return [
                    Select::make('employee_id')
                        ->label('Empleado')
                        ->options(Employee::all()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->native(false)
                        ->createOptionForm([
                            \Filament\Forms\Components\TextInput::make('name')
                                ->label('Nombre del Empleado')
                                ->required(),
                            // Agrega más campos según tu modelo Employee
                        ])
                        ->createOptionUsing(function (array $data): int {
                            return Employee::create($data)->getKey();
                        }),

                    DateTimePicker::make('assigned_at')
                        ->label('Fecha y Hora de Asignación')
                        ->default(now())
                        ->required()
                        ->native(false)
                        ->displayFormat('d/m/Y H:i')
                        ->seconds(false),

                    Textarea::make('notes')
                        ->label('Notas (opcional)')
                        ->rows(3)
                        ->placeholder('Propósito de la asignación, condiciones, etc.'),
                ];
            })
            ->action(function ($record, array $data) {
                // Si tiene asignación activa, marcarla como devuelta
                if ($record->isAssigned()) {
                    $currentAssignment = $record->currentAssignment;
                    $currentAssignment->update([
                        'returned_at' => $data['returned_at'],
                    ]);

                    // Si hay notas de devolución, agregarlas
                    if (!empty($data['return_notes'])) {
                        $currentNotes = $currentAssignment->notes ?? '';
                        $currentAssignment->update([
                            'notes' => $currentNotes . "\n\n--- Devolución ---\n" . $data['return_notes'],
                        ]);
                    }

                    Notification::make()
                        ->success()
                        ->title('Herramienta Devuelta')
                        ->body("La herramienta ha sido devuelta por {$currentAssignment->employee->name}")
                        ->send();
                } else {
                    // Crear nueva asignación
                    ToolAssignment::create([
                        'tool_id' => $record->id,
                        'employee_id' => $data['employee_id'],
                        'assigned_at' => $data['assigned_at'],
                        'notes' => $data['notes'] ?? null,
                    ]);

                    $employeeName = Employee::find($data['employee_id'])->name;

                    Notification::make()
                        ->success()
                        ->title('Herramienta Asignada')
                        ->body("La herramienta ha sido asignada a {$employeeName}")
                        ->send();
                }
            })
            ->requiresConfirmation(false);
    }
}

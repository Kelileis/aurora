<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScanResource\Pages;
use App\Filament\Resources\ScanResource\RelationManagers;
use App\Models\Scan;
use App\Services\ConfigurationService;
use App\Tables\Columns\ScanProgressIndicatorColumn;
use App\Tables\Columns\VideoColumn;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScanResource extends Resource
{
    protected static ?string $model = Scan::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                /**
                 * Name
                 */
                TextInput::make('name')
                    ->helperText('Add a clear description of the scan.')
                    ->required(),
                /**
                 * Media
                 */
                Fieldset::make('Media')
                    ->schema([
                        Select::make('media_id')
                            ->label('Media')
                            ->relationship('media', 'description') // 'media' = relation method, 'name' = column in Media model
                            ->searchable()
                            ->preload()
                            ->required()
                    ]),
                /**
                 * Configuration
                 */
                Fieldset::make('Configuration')
                    ->schema([
                        ...ConfigurationService::constructForm()
                    ])
                    ->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                VideoColumn::make('media.path')
                    ->label('Media preview'),
                TextColumn::make('name'),
                TextColumn::make('updated_at'),
                TextColumn::make('created_at'),
                ScanProgressIndicatorColumn::make('progress'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScans::route('/'),
            'create' => Pages\CreateScan::route('/create'),
            'edit' => Pages\EditScan::route('/{record}/edit'),
        ];
    }
}

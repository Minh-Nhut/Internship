<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Danh mục')
                    ->schema(Category::form()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(Category::tableColumns())
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Trạng thái'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(true),
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
            'index' => Pages\ListCategories::route('/'),
            'tree' => Pages\CategoryTree::route('/tree'),
        ];
    }
}

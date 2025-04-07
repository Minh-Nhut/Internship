<?php

namespace App\Models;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SolutionForest\FilamentTree\Concern\ModelTree;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ToggleColumn;

class Category extends Model
{
    use ModelTree;
    protected $fillable = [
        'title',
        'parent_id',
        'slug',
        'status',
    ];

    protected $casts = [
        'parent_id' => 'int',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public static function form(): array
    {
        return [
            Toggle::make('status')
                ->label('Trạng thái'),
            Select::make('parent_id')
                ->relationship('parent', 'title', ignoreRecord: true)
                ->label('Danh mục cha')
                ->searchable()
                ->preload(),
            TextInput::make('title')
                ->required()
                ->maxLength(255),
            TextInput::make('slug')
                ->required()
                ->maxLength(255),
        ];
    }

    public static function tableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->label('Tên danh mục')
                ->searchable(),
            TextColumn::make('slug')
                ->label('Đường dẫn')
                ->searchable(),
            ToggleColumn::make('status')
                ->label('Trạng thái'),
            TextColumn::make('parent.title')
                ->label('Danh mục cha'),
        ];
    }
}

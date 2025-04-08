<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class ArticleResource extends Resource
{
    const RESCOURCE_TITLE = 'Bài đăng';

    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $label = self::RESCOURCE_TITLE;

    protected static ?string $navigationLabel = self::RESCOURCE_TITLE;

    protected static ?string $pluralLabel = self::RESCOURCE_TITLE;

    protected static ?string $navigationGroup = 'Nội dung';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Fieldset::make()
                            ->label('Tiêu đề')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                        $slug = \Illuminate\Support\Str::slug($state);
                                        $slugExist = \App\Models\Article::where('slug', $slug)->first();
                                        if ($slugExist) {
                                            $set('slug', $slug . '-' . \Illuminate\Support\Str::random(3));
                                        } else {
                                            $set('slug', $slug);
                                        }
                                    })                    
                                    ->label('Tiêu đề')
                                    ->columnSpanFull()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(255),
                                Forms\Components\Select::make('category_id')
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->relationship('category', 'title')
                                    ->preload(),
                            ])->columnSpan(1),
                        Forms\Components\FileUpload::make('cover')
                            ->image()
                            ->imageEditor()
                            ->columnSpan(1),
                    ]),
                Forms\Components\MarkdownEditor::make('description')
                    ->columnSpanFull(),
                
                Forms\Components\MarkdownEditor::make('content')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}

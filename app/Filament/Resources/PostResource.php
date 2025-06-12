<?php

namespace App\Filament\Resources;

use Dom\Text;
use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PostResource\Pages;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Postingan';
    protected static ?string $pluralModelLabel = 'Postingan';

    public static function form(Form $form): Form
    {
        $form->statePath('data');
        return $form->schema([
            Grid::make(2)->schema([
                // TextInput::make('author_id')
                //     ->default(Auth::user()->username)
                //     ->required()
                //     ->readOnly(),

                Hidden::make('author_id')
                ->default(Auth::user()->id),

                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->live(onBlur: true) // hanya saat selesai diketik
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                FileUpload::make('thumbnail')
                    ->label('Thumbnail')
                    ->directory('posts')
                    ->image()
                    ->imageEditor()
                    ->previewable()
                    ->columnSpanFull(),

                RichEditor::make('body')
                    ->label('Isi')
                    ->columnSpanFull()
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'draft' => 'Disimpan',
                        'scheduled' => 'Terjadwal',
                        'published' => 'Diterbitkan',
                    ])
                    ->default('draft')
                    ->reactive(),

                DateTimePicker::make('published_at')
                    ->label('Tanggal Publikasi')
                    ->visible(fn (Get $get) => $get('status') === 'scheduled')
                    ->required(fn (Get $get) => $get('status') === 'scheduled')
                    ->minDate(now()) // hanya masa depan
                    ->seconds(false),

            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->height(50)
                    ->square(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('author.name')
                    ->label('Penulis'),

                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->dateTime('d M Y'),

                TextColumn::make('views')
                    ->sortable(),

                TextColumn::make('likes_count')
                    ->label('Likes'),

                TextColumn::make('comments_count')
                    ->label('Komentar'),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($post) {
            $post->author_id = Auth::id();
        });
    }
}

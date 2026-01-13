<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages;
use App\Models\Post;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; // PENTING: Menggunakan Schema, bukan Form
use Filament\Schemas\Components; // Komponen input ada di sini
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use BackedEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Berita & Artikel';

    protected static ?string $recordTitleAttribute = 'title';

    // PERUBAHAN UTAMA: Menggunakan Schema $schema
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // BAGIAN KIRI (Form Utama)
                Components\Group::make()
                    ->schema([
                        Components\Section::make()
                            ->schema([
                                // Input Judul
                                Components\TextInput::make('title')
                                    ->label('Judul Artikel')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Components\Actions\Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                // Input Slug
                                Components\TextInput::make('slug')
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(Post::class, 'slug', ignoreRecord: true),

                                // Input Konten
                                Components\RichEditor::make('content')
                                    ->label('Isi Artikel')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(2),

                // BAGIAN KANAN (Sidebar)
                Components\Group::make()
                    ->schema([
                        Components\Section::make()
                            ->schema([
                                // Upload Gambar
                                Components\FileUpload::make('thumbnail')
                                    ->label('Gambar Utama')
                                    ->image()
                                    ->directory('posts')
                                    ->required(),

                                // Pilihan Kategori
                                Components\Select::make('category')
                                    ->label('Kategori Menu')
                                    ->options([
                                        'Geneologi' => 'Geneologi',
                                        'Opini' => 'Opini',
                                        'Esai' => 'Esai',
                                        'Artikel' => 'Artikel',
                                        'Berita' => 'Berita',
                                        'Resensi' => 'Resensi',
                                        'Buletin' => 'Buletin',
                                        'Sastra' => 'Sastra',
                                    ])
                                    ->required()
                                    ->searchable(),

                                // Penulis
                                Components\TextInput::make('author')
                                    ->label('Penulis')
                                    ->default(fn () => auth()->user()->name)
                                    ->required(),

                                // Status Publish
                                Components\Toggle::make('is_published')
                                    ->label('Tayangkan?')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->default(true),
                            ]),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->circular(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Berita' => 'info',
                        'Opini' => 'warning',
                        'Sastra' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Tayang')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Geneologi' => 'Geneologi',
                        'Opini' => 'Opini',
                        'Esai' => 'Esai',
                        'Artikel' => 'Artikel',
                        'Berita' => 'Berita',
                        'Resensi' => 'Resensi',
                        'Buletin' => 'Buletin',
                        'Sastra' => 'Sastra',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
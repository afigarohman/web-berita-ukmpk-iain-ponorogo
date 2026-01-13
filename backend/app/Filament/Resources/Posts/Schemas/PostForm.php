<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('thumbnail'),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Select::make('category')
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
                    ->required(),
                TextInput::make('author'),
                Toggle::make('is_published')
                    ->required(),
            ]);
    }
}

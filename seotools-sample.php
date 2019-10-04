<?php

// SEOTools::setTitle($post->title);
// SEOTools::setDescription($post->resume);
// SEOTools::addMeta('article:published_time', $post->published_date->toW3CString(), 'property');
// SEOTools::addMeta('article:section', $post->category, 'property');
// SEOTools::addKeyword(['key1', 'key2', 'key3']);
// SEOTools::setCanonical('https://codecasts.com.br/lesson');

SEOTools::opengraph()->setDescription($post->resume);
SEOTools::opengraph()->setTitle($post->title);
SEOTools::opengraph()->setUrl('http://current.url.com');
SEOTools::opengraph()->addProperty('type', 'article');
SEOTools::opengraph()->addProperty('locale', 'pt-br');

SEOTools::opengraph()->addImage($post->cover->url);
SEOTools::opengraph()->addImage('http://image.url.com/cover.jpg', ['height' => 300, 'width' => 300]);

// SEOTools::jsonLd()->setTitle($post->title);
// SEOTools::jsonLd()->setDescription($post->resume);
// SEOTools::jsonLd()->setType('Article');
// SEOTools::jsonLd()->addImage($post->images->list('url'));


// Namespace URI: http://ogp.me/ns/article#
// article
SEOTools::opengraph()->setTitle('Article')
    ->setDescription('Some Article')
    ->setType('article')
    ->setArticle([
        'published_time' => 'datetime',
        'modified_time' => 'datetime',
        'expiration_time' => 'datetime',
        'author' => 'profile / array',
        'section' => 'string',
        'tag' => 'string / array'
    ]);

// Namespace URI: http://ogp.me/ns/book#
// book
SEOTools::opengraph()->setTitle('Book')
    ->setDescription('Some Book')
    ->setType('book')
    ->setBook([
        'author' => 'profile / array',
        'isbn' => 'string',
        'release_date' => 'datetime',
        'tag' => 'string / array'
    ]);

// Namespace URI: http://ogp.me/ns/profile#
// profile
SEOTools::opengraph()->setTitle('Profile')
     ->setDescription('Some Person')
    ->setType('profile')
    ->setProfile([
        'first_name' => 'string',
        'last_name' => 'string',
        'username' => 'string',
        'gender' => 'enum(male, female)'
    ]);

// Namespace URI: http://ogp.me/ns/music#
// music.song
SEOTools::opengraph()->setType('music.song')
    ->setMusicSong([
        'duration' => 'integer',
        'album' => 'array',
        'album:disc' => 'integer',
        'album:track' => 'integer',
        'musician' => 'array'
    ]);

// music.album
SEOTools::opengraph()->setType('music.album')
    ->setMusicAlbum([
        'song' => 'music.song',
        'song:disc' => 'integer',
        'song:track' => 'integer',
        'musician' => 'profile',
        'release_date' => 'datetime'
    ]);

 //music.playlist
SEOTools::opengraph()->setType('music.playlist')
    ->setMusicPlaylist([
        'song' => 'music.song',
        'song:disc' => 'integer',
        'song:track' => 'integer',
        'creator' => 'profile'
    ]);

// music.radio_station
SEOTools::opengraph()->setType('music.radio_station')
    ->setMusicRadioStation([
        'creator' => 'profile'
    ]);

// Namespace URI: http://ogp.me/ns/video#
// video.movie
SEOTools::opengraph()->setType('video.movie')
    ->setVideoMovie([
        'actor' => 'profile / array',
        'actor:role' => 'string',
        'director' => 'profile /array',
        'writer' => 'profile / array',
        'duration' => 'integer',
        'release_date' => 'datetime',
        'tag' => 'string / array'
    ]);

// video.episode
SEOTools::opengraph()->setType('video.episode')
    ->setVideoEpisode([
        'actor' => 'profile / array',
        'actor:role' => 'string',
        'director' => 'profile /array',
        'writer' => 'profile / array',
        'duration' => 'integer',
        'release_date' => 'datetime',
        'tag' => 'string / array',
        'series' => 'video.tv_show'
    ]);

// video.tv_show
SEOTools::opengraph()->setType('video.tv_show')
    ->setVideoTVShow([
        'actor' => 'profile / array',
        'actor:role' => 'string',
        'director' => 'profile /array',
        'writer' => 'profile / array',
        'duration' => 'integer',
        'release_date' => 'datetime',
        'tag' => 'string / array'
    ]);

// video.other
SEOTools::opengraph()->setType('video.other')
    ->setVideoOther([
        'actor' => 'profile / array',
        'actor:role' => 'string',
        'director' => 'profile /array',
        'writer' => 'profile / array',
        'duration' => 'integer',
        'release_date' => 'datetime',
        'tag' => 'string / array'
    ]);

// og:video
SEOTools::opengraph()->addVideo('http://example.com/movie.swf', [
        'secure_url' => 'https://example.com/movie.swf',
        'type' => 'application/x-shockwave-flash',
        'width' => 400,
        'height' => 300
    ]);

// og:audio
SEOTools::opengraph()->addAudio('http://example.com/sound.mp3', [
        'secure_url' => 'https://secure.example.com/sound.mp3',
        'type' => 'audio/mpeg'
    ]);

// og:place
SEOTools::opengraph()->setTitle('Place')
     ->setDescription('Some Place')
    ->setType('place')
    ->setPlace([
        'location:latitude' => 'float',
        'location:longitude' => 'float',
    ]);
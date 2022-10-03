@extends('layouts.default')
@section('title', 'Page Title')

@section('content')

    <p>This is my ads content.</p>
    @foreach ($ads as $ad)
        @if ($loop->first)
            This is ad id: title | description
        @endif
        <p>This is ad {{ $ad->id }}: {{$ad->title}} | {{$ad->description}} </p>
    @endforeach
@endsection

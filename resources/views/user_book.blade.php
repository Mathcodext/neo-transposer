@extends('_base')

@section('content')

<h1>@lang('Choose language')</h1>

<ul class="books">
@foreach ($books as $book)
    <li><a href="{{ route('set_user_data', ['book' => $book->idBook()]) }}">
        <div>
            {{ $book->langName() }}
            <small>{{ $book->details() }}</small>
        </div>
        <span class="book-song-count">@lang(':count songs', ['count' => $book->songCount()])</span>
    </a></li>
@endforeach
</ul>

@endsection

<div class="ui ten stackable cards">
        @foreach($movies as $movie)
        <div class="ui card">
        <a class="image" href="{{url('phim/'. $movie->slug)}}">
            <img style="height: 250px;object-fit: cover;" src="{{ $movie->poster_url }}">
        </a>
        <div class="content">
            <a class="header" href="{{url('phim/'. $movie->slug)}}">{{ $movie->name }}</a>
            <div class="meta">
            <div class="ui teal label">{{$movie->year}}</div>
            <div class="ui black label">{{$movie->quality}}</div>
            <p>{{$movie->episode_current}}</p>
            </div>
        </div>
        </div>
        @endforeach
    </div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LK2 Movies</title>
</head>
<body>

@include('components.header')

<style>
    .ui.vertical.stripe h3 {
      font-size: 2em;
    }
    .ui.vertical.stripe .button + h3,
    .ui.vertical.stripe p + h3 {
      margin-top: 0em;
    }
    .ui.vertical.stripe .floated.image {
      clear: both;
    }
    .ui.vertical.stripe p {
      font-size: 1.1em;
    }
    .ui.vertical.stripe .horizontal.divider {
      margin: 3em 0em;
    }
</style>

<div class="ui inverted segment">

@php 
    $movie = $movie_detail->movie;
    $episodes = $movie_detail->episodes;
@endphp
 
<div class="ui vertical stripe segment" style="width: 75%;margin: 0 auto ;background-color: #222d38;">
    <div class="ui middle aligned stackable grid container" style="width: 100%;">
      <div class="row" style="background: url({{$movie->thumb_url}}); background-size: contain;padding: 30px;">
      <div class="four wide column">
          <img style="height: 320px;width: 220px;box-shadow: 10px 10px 10px black;" src="{{$movie->poster_url}}" class="ui large bordered rounded image">
        </div>
        <div class="ten wide right floated column" style="background: rgba(0, 0, 0, 0.8); padding: 10px;">
          <h3 class="ui inverted header">{{$movie->name}}</h3>
          <p style="color: white;">{{$movie->origin_name}}</p>
          <a class="ui teal label">
        <i class="calendar icon"></i>
        {{$movie->year}}
        </a>
        <a class="ui label">
        <i class="clock icon"></i>
        {{$movie->time}}
        </a>
        <br>
        <br>
        <p style="color: white;">Quốc gia:
        @php $count = count($movie->country); @endphp
        @foreach($movie->country as $index => $country)
            <a href="#">{{ $country->name }}</a>
            @if($index < $count - 1),@endif
        @endforeach
        </p>
        <p style="color: white;">Đạo diễn:
        @php $count = count($movie->director); @endphp
        @foreach($movie->director as $index => $director)
        <a href="#">{{ $director }}</a>
            @if($index < $count - 1),@endif
        @endforeach
        </p>
        <p style="color: white;">Diễn viên:
        @php $count = count($movie->actor); @endphp
        @foreach($movie->actor as $index => $actor)
        <a href="#">{{ $actor }}</a>
            @if($index < $count - 1),@endif
        @endforeach
        </p>
        <p style="color: white;">Thể loại:
        @php $count = count($movie->category); @endphp
        @foreach($movie->category as $index => $category)
        <a href="#">{{ $category->name }}</a>
            @if($index < $count - 1),@endif
        @endforeach
        </p>
        </div>
      </div>
      <div class="row">
        <div class="center aligned column">
          <a class="ui right labeled icon big white button" target="_blank" href="{{$movie->trailer_url}}">Xem trailer
          <i class="right play icon"></i>
          <a class="ui right labeled icon big teal button" href="{{url('xem-phim/' . $movie->slug)}}">Xem phim
          <i class="right play icon"></i>
          </a>
        </div>
      </div>

      <div class="row">
      <h4 class="ui top inverted attached header" style="background-color: #1b1c1d;">
        Danh sách tập phim
        </h4>
        <div class="ui attached inverted segment">
        @foreach($episodes as $index => $episode)
        <a class="ui inverted teal button {{ request()->is('xem-phim/' .$movie->slug . '/tap-' . $index += 1) ? 'active' : '' }}" href="{{ url('/xem-phim/' .$movie->slug . '/tap-' . $index) }}" style="margin-top: 10px;">
        {{$index }}
        </a>
        @endforeach
        </div>
      </div>

      <div class="row">
      <h4 class="ui top inverted attached header" style="background-color: #1b1c1d;">
        Nội dung phim
        </h4>
        <div class="ui attached inverted segment">
        <p>{!! $movie->content !!}</p>
        </div>
      </div>

      <div class="row">
      <div class="ui five stackable cards">
        @foreach($similar_movies as $movie)
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
      </div>

    </div>
  </div>
    
</body>
</html>
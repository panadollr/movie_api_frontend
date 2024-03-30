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
    $episode_current = $movie_detail->episodeCurrent;
@endphp
 
<div class="ui vertical stripe segment" style="width: 75%;margin: 0 auto ;background-color: #222d38;">
    <div class="ui middle aligned stackable grid container" style="width: 100%;">
     
      <div class="row">
      <iframe id="movie_frame" width="100%" height="550" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>

      <div class="row">
       
      <div>
      <h4 class="ui inverted header">
        Chuyển đổi server
        </h4>
            @foreach($episode_current->sources as $index => $source)
  <a class="ui teal inverted button movie-source {{$index === 0 ? 'active' : ''}}" data-link_embed="{{$source->link_embed}}">{{$source->server_name}}</a>
  @endforeach
</div>

        <div class="ui attached inverted segment" style="margin-top: 20px;">
        <h4 class="ui header" style="background-color: #1b1c1d;;">
        Danh sách tập phim
        </h4>
            @foreach($episodes as $index => $episode)
        <a class="ui inverted teal button {{ request()->is('xem-phim/' .$movie->slug . '/tap-' . $index += 1) ? 'active' : '' }}" href="{{ url('/xem-phim/' .$movie->slug . '/tap-' . $index) }}" style="margin-top: 10px;">
        {{$index }}
        </a>
        @endforeach
        </div>
      </div>

      <div class="row">
     
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


@include('components.footer')
<script>
    $('.menu .item').tab()
;
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var buttons = document.querySelectorAll('.movie-source');
    var iframe = document.getElementById('movie_frame');

    // Kiểm tra xem có nút nào tồn tại không
    if (buttons.length > 0) {
        // Mặc định chọn nút đầu tiên
        buttons[0].classList.add('active');
        iframe.src = buttons[0].getAttribute('data-link_embed');

        // Lặp qua từng nút button và thêm sự kiện click
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                buttons.forEach(function(btn) {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                iframe.src = this.getAttribute('data-link_embed');
            });
        });
    }
});
</script>

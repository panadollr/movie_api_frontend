<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LK2 Movies</title>
</head>
<body>

@include('components.header')

<div class="ui inverted segment">

<h2>Phim thịnh hành</h2>
@include('components.movie_cards', ['movies' => $trending_movies])
<br>

<h2>Hôm nay xem gì</h2>
@include('components.movie_cards', ['movies' => $today_movies])

<h2>Phim bộ mới cập nhật</h2>
@include('components.movie_cards', ['movies' => $series_movies])

<h2>Phim lẻ mới cập nhật</h2>
@include('components.movie_cards', ['movies' => $single_movies])


</div>
    
</body>
</html>
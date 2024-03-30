<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LK2 Movies</title>
</head>
<body>

@include('components.header')

@if(isset($message))
<h1 class="ui center aligned inverted header">{{$message}}</h1>
@endif

<div class="ui inverted segment">
@include('components.movie_cards', ['movies' => $movies])
<br>


</div>
    
</body>
</html>
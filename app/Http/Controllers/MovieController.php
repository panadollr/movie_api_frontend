<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    protected $api_domand, $categories, $countries;

    public function __construct()
    {
        $this->api_domand = 'https://movies-api-amber-chi.vercel.app/api';
        $this->categories = Cache::rememberForever('categories', function () {
            return $this->fetchApi('/the-loai')->data->items;
        });
        $this->countries = Cache::rememberForever('countries', function () {
            return $this->fetchApi('/quoc-gia')->data->items;
        });
    }

    protected function fetchApi($endpoint){
        return json_decode(file_get_contents($this->api_domand . $endpoint));
    }

    public function welcome(){
        $minutes = 7200;
        $trending_movies = Cache::remember('trending_movies', $minutes, function () {
            return $this->fetchApi('/xu-huong?limit=10')->data;
        });
    
        $today_movies = Cache::remember('today_movies', $minutes, function () {
            return $this->fetchApi('/hom-nay-xem-gi?limit=10')->data;
        });
    
        $series_movies = Cache::remember('series_movies', $minutes, function () {
            return $this->fetchApi('/moi-cap-nhat/phim-bo?limit=10')->data;
        });
        $single_movies = Cache::remember('single_movies', $minutes, function () {
            return $this->fetchApi('/moi-cap-nhat/phim-le?limit=10')->data;
        });
        $categories = $this->categories;
        $countries = $this->countries;

        return view('welcome', compact('categories', 'countries', 'trending_movies', 'today_movies', 'series_movies', 'single_movies'));
    }

    public function movieDetail($slug){
        $movie_detail = Cache::remember("movie_detail_$slug", 7200, function () use ($slug) {
            return $this->fetchApi("/phim/$slug");
        });
    
        $similar_movies = Cache::remember("similar_movies_$slug", 7200, function () use ($slug) {
            return $this->fetchApi("/phim-tuong-tu/$slug")->data;
        });

        $categories = $this->categories;
        $countries = $this->countries;

        return view('movie_detail', compact('categories', 'countries', 'movie_detail', 'similar_movies'));
    }

    public function watchMovie($slug, $episode_slug){
        $movie_detail = Cache::remember("movie_detail_$slug", 7200, function () use ($slug, $episode_slug) {
            return $this->fetchApi("/phim/$slug/$episode_slug");
        });
    
        $similar_movies = Cache::remember("similar_movies_$slug", 7200, function () use ($slug) {
            return $this->fetchApi("/phim-tuong-tu/$slug")->data;
        });

        $categories = $this->categories;
        $countries = $this->countries;

        return view('watch_movie', compact('categories', 'countries', 'movie_detail', 'similar_movies'));
    }

    public function moviesByType($type_slug){
        $movies = Cache::remember("movies_$type_slug", 7200, function () use ($type_slug) {
            return $this->fetchApi("/$type_slug?limit=24")->data;
        });
        $categories = $this->categories;
        $countries = $this->countries;

        return view('movies_by_type', compact('categories', 'countries', 'movies'));
    }

    public function moviesByCategory($category_slug){
        $movies = Cache::remember("movies_$category_slug", 7200, function () use ($category_slug) {
            return $this->fetchApi("/the-loai/$category_slug?limit=24")->data;
        });
        $categories = $this->categories;
        $countries = $this->countries;

        return view('movies_by_type', compact('categories', 'countries', 'movies'));
    }

    public function moviesByCountry($country_slug){
        $movies = Cache::remember("movies_$country_slug", 7200, function () use ($country_slug) {
            return $this->fetchApi("/quoc-gia/$country_slug?limit=24")->data;
        });
        $categories = $this->categories;
        $countries = $this->countries;

        return view('movies_by_type', compact('categories', 'countries', 'movies'));
    }

    public function search(){
        $search_query = urlencode(request()->input('search_query'));
        $movies = $this->fetchApi("/tim-kiem?keyword=$search_query")->data;
        $categories = $this->categories;
        $countries = $this->countries;
        $message = "Tìm kiếm cho từ khóa $search_query";

        return view('movies_by_type', compact('categories', 'countries', 'movies', 'message'));
    }
}

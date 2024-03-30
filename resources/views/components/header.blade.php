<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.css" integrity="sha512-KXol4x3sVoO+8ZsWPFI/r5KBVB/ssCGB5tsv2nVOKwLg33wTFP3fmnXa47FdSVIshVTgsYk/1734xSk9aFIa4A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    body{
        background-color: black;
    }
</style>

<div class="ui top fixed inverted menu">
  <div class="header item">
    <h1 class="ui teal header">LK2 MOVIES</h1>
  </div>
  <a class="item {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Trang chủ</a>
  <div class="ui simple dropdown item">
    Thể loại
    <i class="dropdown icon"></i>
    <div class="menu">
      @foreach($categories as $category)
      <a class="item {{ request()->is('the-loai/' . $category->slug) ? 'active' : '' }}" href="{{ url('/the-loai/' . $category->slug) }}">{{$category->name}}</a>
      @endforeach
    </div>
  </div>
  <div class="ui simple dropdown item">
    Quốc gia
    <i class="dropdown icon"></i>
    <div class="menu">
      @foreach($countries as $country)
      <a class="item {{ request()->is('quoc-gia/' . $country->slug) ? 'active' : '' }}" href="{{ url('/quoc-gia/' . $country->slug) }}">{{$country->name}}</a>
      @endforeach
    </div>
  </div>
</div>

<div class="ui inverted pointing menu" style="margin-top: 3%;">
  <a class="item {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
    Tất cả
  </a>
  <a class="item {{ request()->is('danh-sach/xu-huong') ? 'active' : '' }}" href="{{ url('/danh-sach/xu-huong') }}">
    Phim thịnh hành
  </a>
  <a class="item {{ request()->is('danh-sach/hom-nay-xem-gi') ? 'active' : '' }}" href="{{ url('/danh-sach/hom-nay-xem-gi') }}">
    Hôm nay xem gì
  </a>
  <a class="item {{ request()->is('danh-sach/moi-cap-nhat/phim-bo') ? 'active' : '' }}" href="{{ url('/danh-sach/moi-cap-nhat/phim-bo') }}">
    Phim bộ mới cập nhật
  </a>
  <a class="item {{ request()->is('danh-sach/moi-cap-nhat/phim-le') ? 'active' : '' }}" href="{{ url('/danh-sach/moi-cap-nhat/phim-le') }}">
    Phim lẻ mới cập nhật
  </a>
  <div class="right menu">
  <div class="item">
    <form method="GET" action="{{url('/tim-kiem')}}">
      @csrf <!-- Đảm bảo sử dụng CSRF token khi gửi dữ liệu qua POST -->
      <div class="ui icon input">
        <input type="text" name="search_query" placeholder="Tìm kiếm...">
      </div>
    </form>
  </div>
</div>
</div>

<div class="sidebar" data-color="orange" data-background-color="white"
  data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="/home" class="simple-text logo-normal">
      {{ config('app.name') }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
          <p>{{ __('Dashboard') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'teams' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('teams.index') }}">
          <i class="material-icons">groups</i>
          <p>チーム</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'problems' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('problems.index') }}">
          <i class="material-icons">library_books</i>
          <p>問題</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'images' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('images') }}">
          <i class="material-icons">inventory_2</i>
          <p>イメージ</p>
        </a>
      </li>
    </ul>
  </div>
</div>

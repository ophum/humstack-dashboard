<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="https://creative-tim.com/" class="simple-text logo-normal">
      {{ config('app.name')  }}
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
      <li class="nav-item{{ $activePage == 'env' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('env') }}">
          <i class="material-icons">content_paste</i>
            <p>環境</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'problem' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('problem') }}">
          <i class="material-icons">content_paste</i>
            <p>問題</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'team' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('team') }}">
          <i class="material-icons">content_paste</i>
            <p>チーム</p>
        </a>
      </li>
    </ul>
  </div>
</div>

<li class="mt-3 nav-item">
    <a class="nav-link @yield('home-active')" href="/dashboard">
        <span class="icon"><i class="fas fa-home"></i></span>
        <span class="item">Home</span>
    </a>
</li>
<li class="mt-3 nav-item">
    <a class="nav-link @yield('image-active')" href="/admin-image">
        <span class="icon"><i class="fas fa-photo-video"></i></span>
        <span class="item">Banner</span>
    </a>
</li>
<li class="mt-3 nav-item">
    <a class="nav-link @yield('photo-active')" href="/admin-photo">
        <span class="icon"><i class="fas fa-camera-retro"></i></span>
        <span class="item">Foto</span>
    </a>
</li>
<li class="mt-3 nav-item">
    <a class="nav-link @yield('video-active')" href="/admin-video">
        <span class="icon"><i class="fas fa-video"></i></span>
        <span class="item">Video</span>
    </a>
</li>
<li class="mt-3 nav-item">
    <a class="nav-link @yield('schedule-active')" href="/admin-schedule">
        <span class="icon"><i class="fas fa-calendar-alt"></i></span>
        <span class="item">Agenda</span>
    </a>
</li>
<li class="mt-3 nav-item">
    <a class="nav-link" href="{{ route('logout') }}">
        <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
        <span class="item d-inline-flex">Log Out
            <!-- <form method="POST" action="{{ route('logout') }}">
  @csrf
  <div :href="route('logout')" class="" onclick="event.preventDefault();
                                        this.closest('form').submit();">
    {{ __('Log Out') }}
  </div>
</form> -->
        </span>
    </a>
</li>
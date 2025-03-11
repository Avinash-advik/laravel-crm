<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
    <a class="navbar-brand" href="{{ route('contact.index') }}">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('contact.index') }}">Contact</a>
            </li>
            <li class="nav-item {{ request()->is('customfield') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('customfield.index') }}">Custom Fields</a>
            </li>
        </ul>
    </div>
</nav>

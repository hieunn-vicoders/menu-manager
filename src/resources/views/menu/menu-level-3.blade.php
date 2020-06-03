<div class="container">
<nav class="navbar navbar-expand-lg navbar-light bg-light style-menus">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="menu-level-3">
        <ul class="navbar-nav mr-auto">
            @foreach ($getMenus as $item)
            <li id="menus-{{$item->id}}" class="menu nav-item ">
                <a class="nav-link" href="{{ $item->link }}">{{ $item->label }}</a>
                {!! $item->renderSubmenu() !!}
            </li>
            @endforeach
        </ul>
        </div>

    </div>
</nav>
</div>

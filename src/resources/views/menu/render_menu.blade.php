@if($menu_type === $menu_horizontal)
<div class="horizontal-menu">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    @if ($main_menu)
                        @foreach ($main_menu->menuItems as $item)
                            @if (count($item->subMenus) === 0)
                                <li id="menus-{{$item->id}}" class="menu nav-item ">
                                    <a class="nav-link" href="{{ $item->link }}">{{ $item->label }}</a>
                                </li>
                            @else
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href=" {{ $item->link }}" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ $item->label }}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
                                        @foreach ($item->subMenus as $itemsub)
                                            <a class="dropdown-item" href="{{ $itemsub->link }}">{{ $itemsub->label }}</a>
                                        @endforeach
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>
@else
    <div class="vertical-menu">
        <ul class="vertical-menu-ul-parent">
            @if ($main_menu)
                @foreach ($main_menu->menuItems as $item)
                    @if (count($item->subMenus) === 0)
                        <li class="vertical-menu-li-parent">
                            <div class="vertical-menu-a-parent">
                                <a href="{{$item->link}}">{{$item->label}}</a>
                            </div>
                        </li>
                    @else
                        <li class="vertical-menu-li-parent">
                            <div class="vertical-menu-a-parent">
                                <a href="{{$item->link}}">{{$item->label}}</a>
                                <ul class="vertical-menu-ul-child">
                                    @foreach ($item->subMenus as $itemsub)
                                    <li class="vertical-menu-li-child">
                                        <div class="vertical-menu-a-child">
                                            <a href="{{ $itemsub->link }}">{{ $itemsub->label }}</a>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
@endif
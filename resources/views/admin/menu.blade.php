<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        @if ($active == 'Overview')
            <li class="active"><a href="/admin/">Overview <span class="sr-only">(current)</span></a></li>
        @else
            <li><a href="/admin/">Overview</a></li>
        @endif

        @if ($active == 'Users')
            <li class="active"><a href="/admin/users">Users <span class="sr-only">(current)</span></a></li>
        @else
            <li><a href="/admin/users">Users</a></li>
        @endif

        @if ($active == 'Adventures')
            <li class="active"><a href="/admin/adventures">Adventures <span class="sr-only">(current)</span></a></li>
        @else
            <li><a href="/admin/adventures">Adventures</a></li>
        @endif

        @if ($active == 'Add adventures')
            <li class="active"><a href="/admin/adventures/create">- Add new adventure<span class="sr-only">(current)</span></a></li>
        @else
            <li><a href="/admin/adventures/create">- Add new adventure</a></li>
        @endif

        @if ($active == 'Items')
            <li class="active"><a href="/admin/items">Items <span class="sr-only">(current)</span></a></li>
        @else
            <li><a href="/admin/items">Items</a></li>
        @endif

        @if ($active == 'Add item')
            <li class="active"><a href="/admin/items/create">- Add new item<span class="sr-only">(current)</span></a></li>
        @else
            <li><a href="/admin/items/create">- Add new item</a></li>
        @endif

        @if ($active == 'Prices')
            <li class="active"><a href="/admin/prices">Prices <span class="sr-only">(current)</span></a></li>
        @else
            <li><a href="/admin/prices">Prices</a></li>
        @endif
    </ul>
</div>
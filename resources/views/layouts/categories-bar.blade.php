<div class="category-button">
    <ul>
        <li>
            <a href="{{ route('home')}}">Shop All</a>
        </li>
        @foreach (\App\Models\Category::all() as $categorie)
        <li>
            <a href="{{ route('collection', ['slug' => $categorie->slug]) }}">{{$categorie->name}}</a>
        </li>
        @endforeach
    </ul>

</div>

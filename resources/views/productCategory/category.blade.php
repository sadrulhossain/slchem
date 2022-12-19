<li>
    @if ($category->subCategory()->count() > 0 )
    <ul>
        @foreach($category->subCategory as $category)

        @include('category', $category) //the magic is in here

        @endforeach
    </ul>

    @endif
</li>
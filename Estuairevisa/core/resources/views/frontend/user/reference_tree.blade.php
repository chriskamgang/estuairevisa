<li>
    <a href="#">
        <img src="{{ getFile('user', $user->image) }}" class="ref-img" alt="">
        <p class="mb-0">{{ $user->full_name }} <span class="font-weight-bolder">
            @if($level > 0)
            ( {{ $level }} )
            @endif
            </span></p>
    </a>
    @if (!empty($user->children) && $user->children->count() > 0)
        <ul>
            @foreach ($user->children as $child)
                @include('frontend.user..reference_tree', ['user' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>

@if (Auth::user()->is_favorite($micropost->id))
    {{-- お気に入り解除ボタンのフォーム --}}
    <form method="POST" action="{{ route('favorites.unfavorite', $micropost->id) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline btn-accent btn-sm normal-case" 
            onclick="return confirm('id = {{ $micropost->id }} のお気に入りを解除します。よろしいですか？')">Unfavorite</button>
    </form>
@else
    {{-- お気に入りボタンのフォーム --}}
    <form method="POST" action="{{ route('favorites.favorite', $micropost->id) }}">
        @csrf
        <button type="submit" class="btn btn-accent btn-sm normal-case">Favorite</button>
    </form>
@endif
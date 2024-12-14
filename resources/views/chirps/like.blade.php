@foreach ($chirps as $chirp)
    <div>
        <p>{{ $chirp->content }}</p>
        <p>Likes: <span id="likes-count-{{ $chirp->id }}">{{ $chirp->likes }}</span></p>
        <button onclick="likeChirp({{ $chirp->id }})">Like</button>
    </div>
@endforeach

<script>
    function likeChirp(chirpId) {
        fetch(`/chirps/${chirpId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById(`likes-count-${chirpId}`).innerText = data.likes;
        });
    }
</script>

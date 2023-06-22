@extends('layouts.app')

@section('content')
  <h1 class="mb-10 text-2xl">Add Review for {{ $book->title }}</h1>
  <form method="POST" action="{{ route('books.reviews.store', $book) }}" class="mb-4">
    @csrf
    <label for="review">Review</label>
    <textarea name="review" id="review" required class="input mb-4"></textarea>

    <label for="rating">Rating</label>

    <select name="rating" id="rating" required class="input mb-4">
      <option value="">Select a rating</option>
      @foreach (range(1,5) as $rating)
        <option value="{{ $rating }}">{{ $rating }}</option>
      @endforeach
    </select>

    <button type="submit" class="btn">Add Review</button>
  </form>
@endsection
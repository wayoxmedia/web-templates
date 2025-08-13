@extends('theme::layouts.app')

@section('content')
  <h1>{{ $page['title'] ?? 'Page' }}</h1>

  {{-- Very basic renderer for a few common blocks --}}
  @if(!empty($content['sections']))
    @foreach($content['sections'] as $block)
      @if(($block['type'] ?? '') === 'richtext')
        {!! $block['html'] ?? '' !!}
      @elseif(($block['type'] ?? '') === 'gallery')
        <div class="gallery">
          @foreach(($block['items'][0] ?? []) as $img)
            <img src="{{ $img }}" alt="">
          @endforeach
        </div>
      @endif
    @endforeach
  @endif
@endsection

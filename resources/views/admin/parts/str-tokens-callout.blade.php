@if(count($tokens))
    <div class="callout callout-info" @style(['height: 100%' => $height ?? 0])>
        <h5><i class="fas fa-info"></i> Токени:</h5>
        @foreach ($tokens as $item)
            <span @style(['display:block' => $height ?? 0])><span>{{ $item['name'] }}:</span>&nbsp;<code href="#" title="Копіювати" class="js-clipboard" data-text="{{ $item['key'] }}">{{ $item['key'] }}</code></span>
        @endforeach
    </div>
@endif

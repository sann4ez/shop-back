@foreach ($comments ?: [] as $comment)
@if($variation = $comment->model?->variation)
<review>
    <review_id>{{ $comment->id }}</review_id>
    <reviewer>
        @if($author = $comment->getAuthorName())
            <name is_anonymous="false">{{ $author }}</name>
        @else
            <name is_anonymous="true">Anonymous</name>
        @endif
    </reviewer>
    <review_timestamp>{{ $comment->created_at->format('Y-m-d\TH:i:s\Z') }}</review_timestamp>
    <content>{{ $comment->body }}</content>
    <review_url type="singleton">{{ $comment->model->getUrlClient() }}#comments</review_url>
    <ratings>
        <overall min="1" max="5">{{ $comment->rating }}</overall>
    </ratings>
    <products>
        <product>
            <product_ids>
                <skus>
                    <sku>{{ $variation->sku }}</sku>
                </skus>
                @if($comment->model->brand)
                    <brands>
                        <brand>{{ $comment->model->brand?->name }}</brand>
                    </brands>
                @endif
            </product_ids>
            <product_name>{{ $variation->getName() }}</product_name>
            <product_url>{{ $comment->model->getUrlClient() }}</product_url>
        </product>
    </products>
    <is_spam>false</is_spam>
</review>
@endif
@endforeach

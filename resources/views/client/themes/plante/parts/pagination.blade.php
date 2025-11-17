{{ $items->onEachSide(1)->appends(\Request::except('page'))->links('parts.pagination-view') }}

@isset($items)
<div class="row">
    <div class="col">
        <hr class="mt-5 mb-4">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto mb-3 mb-sm-0">
                @php($form = $items->currentPage() * $items->perPage() - $items->perPage() +  1)
                <span>Showing {{ $form }}-{{ $form + $items->count() - 1 }} of {{ $items->total() }} results</span>
            </div>
            <div class="col-auto">
                <?php echo $items->appends(\Request::except('page'))->render(); ?>
                {{--
                <nav aria-label="Page navigation example">
                    <ul class="pagination mb-0">
                        <li class="page-item">
                            <a class="page-link prev" href="#" aria-label="Previous">
                                <span><i class="fas fa-angle-left" aria-label="Previous"></i></span>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">...</li>
                        <li class="page-item"><a class="page-link" href="#">15</a></li>
                        <li class="page-item">
                            <a class="page-link next" href="#" aria-label="Next">
                                <span><i class="fas fa-angle-right" aria-label="Next"></i></span>
                            </a>
                        </li>
                    </ul>
                </nav>
                --}}
            </div>
        </div>
    </div>
</div>
@endunless

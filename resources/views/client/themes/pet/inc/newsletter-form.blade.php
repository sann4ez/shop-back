<section class="section bg-dark-4 py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2">
                <h2 class="text-color-light text-4">NEWSLETTER</h2>
            </div>
            <div class="col-md-4">
                <p>Get the latest news on our products and promotions. No spam, just a few emails per year.</p>
            </div>
            <div class="col-md-6">

                <form class="" action="{{ route('incomings.store') }}" method="POST">
                    @csrf
                    @honeypot
                    <input type="hidden" name="type" value="newsletter">
                    <div class="newsletter-form-success alert alert-success d-none">
                        <strong>Success!</strong> You've been added to our email list.
                    </div>
                    <div class="newsletter-form-error alert alert-danger d-none">
                        <strong>Error!</strong> There was an error to add your email.
                    </div>

                    <div class="input-group bg-light rounded">
                        <input type="email" name="email" class="newsletter-email form-control border-0 rounded" placeholder="Enter Email address" aria-label="Enter Email address" required>
                        <span class="input-group-btn p-1">
                            <button class="btn btn-primary font-weight-semibold btn-h-2 rounded h-100" type="submit">SUBSCRIBE</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
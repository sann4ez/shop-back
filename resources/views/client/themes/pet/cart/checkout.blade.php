@extends('layouts.app')

@section('content')
    <div role="main" class="main">

            <div class="container">

                {{ Breadcrumbs::render('cart.checkout') }}

                @if(($order = \Cart::order()) && $order->purchases)

                <div class="row">
                    <div class="col">
                        <form id="shopCheckout" action="{{ route('cart.checkout.save') }}" method="POST">
                        @csrf
                            <!-- ADDRESS -->
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <h2 class="font-weight-bold mb-3">Shipping Address</h2>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_email">EMAIL:</label>
                                            <input type="text" value="{{ old('shipping.email') }}" class="form-control line-height-1 bg-light-5" name="shipping[email]" id="shipping_email" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="">COUNTRY:</label>
                                            <div class="custom-select-1">
                                                <select name="shipping[country]" class="form-control bg-light-5 text-color-dark border-0" aria-label="Choose a country" required>
                                                    {{--\Domain::getSelected()->country->code--}}
                                                    @foreach(\App\Models\Location\Country::all() as $country)
                                                    <option value="{{ $country->code }}" @if($country->code === old('shipping.country')) selected @endif>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_name">CONTACT NAME:</label>
                                            <input type="text" value="{{ old('shipping.name') }}" class="form-control line-height-1 bg-light-5" name="shipping[name]" id="shipping_name" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_phone">PHONE:</label>
                                            <input type="text" value="{{ old('shipping.phone') }}" class="form-control line-height-1 bg-light-5" name="shipping[phone]" id="shipping_phone" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_address">ADDRESS:</label>
                                            <input type="text" value="{{ old('shipping.address') }}" class="form-control line-height-1 bg-light-5" name="shipping[address]" id="shipping_address" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_apartment">APARTMENT:</label>
                                            <input type="text" value="{{ old('shipping.apartment') }}" class="form-control line-height-1 bg-light-5" name="shipping[apartment]" id="shipping_apartment" placeholder="Optional">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_city">CITY:</label>
                                            <input type="text" value="{{ old('shipping.city') }}" class="form-control line-height-1 bg-light-5" name="shipping[city]" id="shipping_city" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_zipcode">ZIPCODE:</label>
                                            <input type="text" value="{{ old('shipping.zipcode') }}" class="form-control line-height-1 bg-light-5" name="shipping[zipcode]" id="shipping_zip" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="text-color-dark font-weight-semibold" for="shipping_comment">COMMENT:</label>
                                            <textarea class="form-control line-height-1 bg-light-5" name="shipping[comment]" id="shipping_comment" rows="7" placeholder="Your message">{{ old('shipping.comment') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- PROMOCODE -->
                        <div class="row pb-4 mb-3">
                            <div class="col-md-12">
                                <div class="accordion accordion-default accordion-toggle accordion-style-1" role="tablist">
                                    <div class="card">
                                        <div class="card-header accordion-header accordion-header-shrink" role="tab" id="shopCheckoutCoupon">
                                            <span class="mb-0">
                                                <a href="#" class="text-color-dark" data-toggle="collapse" data-target="#toggleShopCheckoutCoupon" aria-expanded="true" aria-controls="toggleShopCheckoutCoupon">Have a Coupon? <span class="text-color-primary">Click here to enter your code</span></a>
                                            </span>
                                        </div>
                                        <div id="toggleShopCheckoutCoupon" class="accordion-body collapse show" role="tabpanel" aria-labelledby="shopCheckoutCoupon">
                                            <div class="card-body">
                                                <form action="{{ route('cart.promocode.apply') }}" method="POST">
                                                    @csrf
                                                    <div class="input-group input-group-style-3 rounded">
                                                        <input value="{{ \Cart::promocode('code') }}" type="text" name="promocode" class="form-control bg-light-5 border-0" placeholder="Enter Coupon Code..." aria-label="Enter Coupon Code" required>
                                                        @if(\Cart::promocode('code'))
                                                            <span class="input-group-btn bg-light-5 p-1" style="width: unset">
                                                            <button data-url="{{ route('cart.promocode.remove', ['promocode' => \Cart::promocode('code')]) }}" data-method="DELETE" data-confirm="123" class="btn font-weight-semibold btn-h-3 js-action-form" type="button" style="width: 50px; color: #c00f30">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            </span>
                                                        @else
                                                            <span class="input-group-btn bg-light-5 p-1">
                                                            <button class="btn btn-primary font-weight-semibold btn-h-3 rounded h-100" type="submit">APPLY</button>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- INFO -->
                        <div class="row">
                            <!-- YOUR ORDERS -->
                            <div class="col-md-6 mb-4 mb-md-0">
                                <h3 class="font-weight-bold text-4">Your Orders</h3>
                                <div class="shop-cart">

                                    <div class="table-responsive">
                                        <table class="shop-cart-table w-100">
                                            <thead>
                                            <tr>
                                                <th class="product-thumbnail"></th>
                                                <th class="product-name"><strong>Product</strong></th>
                                                <th class="product-price"><strong>Price</strong></th>
                                                <th class="product-quantity"><strong>Quantity</strong></th>
                                                <th class="product-subtotal"><strong>Total</strong></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(\Cart::purchases() as $purchase)
                                                <tr class="cart-item">
                                                    <td class="product-thumbnail">
                                                        <img src="{{ $purchase->getImageUrl() ?: Theme::url('img/products/product-1.jpg') }}" class="img-fluid" width="67" alt="" />
                                                    </td>
                                                    <td class="product-name">
                                                        <a href="{{ $purchase->model->getUrlClient() }}">{{ $purchase->model->getName() }}</a>
                                                        <p class="text-color-light-3 text-1">
                                                            {{ $purchase->model->getAttributesPropertiesListStr() }}
                                                        </p>
                                                    </td>
                                                    <td class="product-price">
                                                        <span class="unit-price" data-currency="{{\Cart::order()->currency_code}}">{{$purchase->price}}</span>
                                                    </td>
                                                    <td class="product-quantity">{{ $purchase->quantity }}</td>
                                                    <td class="product-subtotal">
                                                        <span class="sub-total"><strong data-currency="{{\Cart::order()->currency_code}}">{{ $purchase->price * $purchase->quantity }}</strong></span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <!-- TOTAL -->
                            <div class="col-md-6">
                                <h3 class="font-weight-bold text-4 mb-3">Cart Totals</h3>
                                <div class="table-responsive mb-4">
                                    <table class="cart-totals w-100">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <span class="cart-total-label">Cart Products</span>
                                            </td>
                                            <td>
                                                <span class="cart-total-value" data-currency="{{\Cart::order()->currency_code}}">{{ \Cart::purchasesSum() }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="cart-total-label">Shipping</span>
                                            </td>
                                            <td>
                                                <span class="cart-total-value" data-currency="{{\Cart::order()->currency_code}}">{{ \Cart::deliverySum() > 0 ? \Cart::deliverySum() : 'Free Delivery' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="cart-total-label">Discount</span>
                                            </td>
                                            <td>
                                                <span class="cart-total-value" data-currency="{{\Cart::order()->currency_code}}">{{ \Cart::totalDiscountSum() }}</span>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom-0">
                                            <td>
                                                <span class="cart-total-label">Total</span>
                                            </td>
                                            <td>
                                                <span class="cart-total-value text-color-primary text-4" data-currency="{{\Cart::order()->currency_code}}">{{ \Cart::totalSum() }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <h3 class="font-weight-bold text-4 mb-3">Payment</h3>
                                <div id="shopPayment">
                                    <div class="">
                                        @if(\Variable::getArray('payments.fondy.active'))
                                        <input type="hidden" name="payment[gateway]" value="fondy" checked>
                                        @endif
                                        @include('parts.payments')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-right">
                                <button class="btn btn-primary btn-rounded font-weight-bold btn-h-2 btn-v-3 js-form-submit" data-form="shopCheckout" type="submit">PLACE ORDER</button>
                            </div>
                        </div>

                    </div>
                </div>
                @else
                    @include('cart.inc.empty')
                @endif
            </div>
        </section>
        <div class="mb-5"></div>
        @include('inc.newsletter-form')
    </div>
@endsection

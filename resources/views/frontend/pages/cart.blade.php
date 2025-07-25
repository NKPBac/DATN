@extends('frontend.layouts.master')
@section('title','Cart Page')
@section('main-content')
    <link rel="stylesheet" href="https://pc.baokim.vn/css/bk.css">
	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="{{('home')}}">Trang chủ<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="">Giỏ hàng</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumbs -->

	<!-- Shopping Cart -->
	<div class="shopping-cart section">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<!-- Shopping Summery -->
					<table class="table shopping-summery">
						<thead>
							<tr class="main-hading">
								<th>SẢN PHẨM</th>
								<th>TÊN</th>
								<th class="text-center">ĐƠN GIÁ</th>
								<th class="text-center">SỐ LƯỢNG</th>
								<th class="text-center">TỔNG</th>
								<th class="text-center"><i class="ti-trash remove-icon"></i></th>
							</tr>
						</thead>
						<tbody id="cart_item_list">
							<form action="{{route('cart.update')}}" method="POST">
								@csrf
								@if(Helper::getAllProductFromCart())
									@foreach(Helper::getAllProductFromCart() as $key=>$cart)
										<tr>
											@php
											$photo=explode(',',$cart->product['photo']);
											@endphp
											<td class="image bk-product-image" data-title="No"><img src="{{$photo[0]}}" alt="{{$photo[0]}}"></td>
											<td class="product-des" data-title="Description">
												<p class="product-name bk-product-name"><a href="{{route('product-detail',$cart->product['slug'])}}" target="_blank">{{$cart->product['title']}}</a></p>
												<p class="product-des">{!!($cart['summary']) !!}</p>
											</td>
											<td class="price bk-product-price" data-title="Price"><span>${{number_format($cart['price'],2)}}</span></td>
											<td class="qty bk-product-qty" data-title="Qty"><!-- Input Order -->
												<div class="input-group">
													<div class="button minus">
														<button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[{{$key}}]">
															<i class="ti-minus"></i>
														</button>
													</div>
													<input type="text" name="quant[{{$key}}]" class="input-number cart-qty-input"  data-min="1" data-max="1000" value="{{$cart->quantity}}" data-stock="{{$cart->product->stock}}">
													<input type="hidden" name="qty_id[]" value="{{$cart->id}}">
													<div class="button plus">
														<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[{{$key}}]">
															<i class="ti-plus"></i>
														</button>
													</div>
												</div>
												<!--/ End Input Order -->
											</td>
											<td class="total-amount cart_single_price" data-title="Total"><span class="money">${{$cart['amount']}}</span></td>

											<td class="action" data-title="Remove"><a href="{{route('cart-delete',$cart->id)}}"><i class="ti-trash remove-icon"></i></a></td>
										</tr>
									@endforeach
									<track>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td class="float-right">
											<button class="btn float-right" type="submit">Câp nhật</button>
										</td>
									</track>
								@else
										<tr>
											<td class="text-center">
												There are no any carts available. <a href="{{route('product-grids')}}" style="color:blue;">Tiếp tục mua hàng</a>

											</td>
										</tr>
								@endif

							</form>
						</tbody>
					</table>
					<!--/ End Shopping Summery -->
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<!-- Total Amount -->
					<div class="total-amount">
						<div class="row">
							<div class="col-lg-8 col-md-5 col-12">
								<div class="left">
									<div class="coupon">
									<form action="{{route('coupon-store')}}" method="POST">
											@csrf
											<input name="code" placeholder="Nhập mã giảm giá">
											<button class="btn">Lưu</button>
										</form>
									</div>
									{{-- <div class="checkbox">`
										@php
											$shipping=DB::table('shippings')->where('status','active')->limit(1)->get();
										@endphp
										<label class="checkbox-inline" for="2"><input name="news" id="2" type="checkbox" onchange="showMe('shipping');"> Shipping</label>
									</div> --}}
								</div>
							</div>
							<div class="col-lg-4 col-md-7 col-12">
								<div class="right">
									<ul>
										<li class="order_subtotal" data-price="{{Helper::totalCartPrice()}}">Tổng tiền<span>{{number_format(Helper::totalCartPrice(),2)}} VND</span></li>
                                        <li class="sale" data-price="">Giảm giá<span>{{ $sale * 100 }}%</span></li>
										@if(session()->has('coupon'))
										<li class="coupon_price" data-price="{{Session::get('coupon')['value']}}">You Save<span>${{number_format(Session::get('coupon')['value'],2)}}</span></li>
										@endif
										@php
											$total_amount=Helper::totalCartPrice();
											if(session()->has('coupon')){
												$total_amount=$total_amount-Session::get('coupon')['value'];
											}
										@endphp
										@if(session()->has('coupon'))
											<li class="last" id="order_total_price">Cần trả<span>{{number_format($total_amount - ($total_amount * $sale),2)}} VND</span></li>
										@else
											<li class="last" id="order_total_price">Cần trả<span>{{number_format($total_amount - ($total_amount * $sale),2)}} VND</span></li>
										@endif
									</ul>
									<div class="button5">
										<a href="{{route('checkout')}}" class="btn">Thanh toán</a>
										<a href="{{route('product-grids')}}" class="btn">Tiếp tục mua hàng</a>
									</div>
                                    <div class='bk-btn'></div>
                                    <div class='bk-btn'></div>
								</div>
							</div>
						</div>
					</div>
					<!--/ End Total Amount -->
				</div>
			</div>
		</div>
	</div>
	<!--/ End Shopping Cart -->

	<!-- Start Shop Services Area  -->
	<section class="shop-services section">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6 col-12">
					<!-- Start Single Service -->
					<div class="single-service">
						<i class="ti-rocket"></i>
						<h4>Miễn phí ship</h4>
						<p>Cho đơn từ 1000000</p>
					</div>
					<!-- End Single Service -->
				</div>
				<div class="col-lg-3 col-md-6 col-12">
					<!-- Start Single Service -->
					<div class="single-service">
						<i class="ti-reload"></i>
						<h4>Hoàn trả</h4>
						<p>Trong vòng 30 ngày</p>
					</div>
					<!-- End Single Service -->
				</div>
				<div class="col-lg-3 col-md-6 col-12">
					<!-- Start Single Service -->
					<div class="single-service">
						<i class="ti-lock"></i>
						<h4>Thanh toán an toàn</h4>
						<p>100% thanh toán an toàn</p>
					</div>
					<!-- End Single Service -->
				</div>
				<div class="col-lg-3 col-md-6 col-12">
					<!-- Start Single Service -->
					<div class="single-service">
						<i class="ti-tag"></i>
						<h4>Giá tốt</h4>
						<p>Giá tốt nhất</p>
					</div>
					<!-- End Single Service -->
				</div>
			</div>
		</div>
	</section>
	<!-- End Shop Newsletter -->

	<!-- Start Shop Newsletter  -->
	@include('frontend.layouts.newsletter')
	<!-- End Shop Newsletter -->
    <script src="https://pc.baokim.vn/js/bk_plus_v2.popup.js"></script>
@endsection
@push('styles')
	<style>
		li.shipping{
			display: inline-flex;
			width: 100%;
			font-size: 14px;
		}
		li.shipping .input-group-icon {
			width: 100%;
			margin-left: 10px;
		}
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
	</style>
@endpush
@push('scripts')
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script>
		document.addEventListener("DOMContentLoaded", function () {
			const qtyInputs = document.querySelectorAll('.cart-qty-input');

			qtyInputs.forEach(input => {
				const minusBtn = input.closest('.input-group').querySelector('.btn-number[data-type="minus"]');
				const plusBtn = input.closest('.input-group').querySelector('.btn-number[data-type="plus"]');
				const maxStock = parseInt(input.getAttribute('data-stock'));

				// Gõ trực tiếp
				let typingTimer;
				input.addEventListener('input', function () {
					clearTimeout(typingTimer);
					typingTimer = setTimeout(() => {
						let val = parseInt(this.value);

						if (isNaN(val) || val < 1) {
							this.value = 1;
						} else if (val > maxStock) {
							Swal.fire({
								icon: 'error',
								title: 'Vượt tồn kho',
								text: 'Tối đa chỉ còn ' + maxStock + ' sản phẩm!',
								confirmButtonColor: '#d33'
							});
							this.value = maxStock;
						}
					}, 300);
				});

				// Nút +
				if (plusBtn) {
					plusBtn.addEventListener('click', function () {
						let val = parseInt(input.value) || 0;
						if (val >= maxStock) {
							Swal.fire({
								icon: 'error',
								title: 'Vượt tồn kho',
								text: 'Tối đa chỉ còn ' + maxStock + ' sản phẩm!',
								confirmButtonColor: '#d33'
							});
							input.value = maxStock;
						}
					});
				}

				// Nút -
				if (minusBtn) {
					minusBtn.addEventListener('click', function () {
						let val = parseInt(input.value) || 0;
						if (val <= 1) {
							input.value = 1;
						}
					});
				}
			});
		});
	</script>

	<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>
	<script>
		$(document).ready(function(){
			$('.shipping select[name=shipping]').change(function(){
				let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') );
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0;
				// alert(coupon);
				$('#order_total_price span').text('$'+(subtotal + cost-coupon).toFixed(2));
			});

		});

	</script>

@endpush

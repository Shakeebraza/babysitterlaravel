@extends('layouts.app')
@section('content')
	<!-- banner-start -->
	<div class="bannerMain">
		<div class="bannerSlide" style="background-image: url('{{ URL::asset('assets/website/images/banner1-bg.png') }}');">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="bannerContent d-flex flex-column">
							<h1 class="display-1 mb-2"><span>{{__(trans('messages.childcare'))}}</span> {{__(trans('messages.daily_life'))}}</h1>
							<p class="mb-4">{{__(trans('messages.discover_the_ideal'))}}</p>
							<!-- <a href="javaScript:;" class="btn btn-primary round">Read More</a> -->
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="bannerImg">
							<img src="{{ URL::asset('assets/website/images/banner-img1.jpg') }}" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- banner-end -->

	<!-- features-start -->
	<div class="features-main py-5" id="featuresMain">
		<div class="container">
			<div class="title-main mb-4">
				<h2 class="display-5 text-center fw-bold">{{__(trans('messages.the_right_babysitter'))}}</h2>
			</div>
			<div class="row">
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="feature-box">
						<div class="feature-box-img">
							<img src="{{ URL::asset('assets/website/images/info-box-img1-min.jpg') }}" alt="">
						</div>
						<div class="feature-box-txt">
							<h4>{{__(trans('messages.evening_sitters'))}}</h4>
							<p>{{__(trans('messages.would_you_like'))}}</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="feature-box">
						<div class="feature-box-img">
							<img src="{{ URL::asset('assets/website/images/info-box-img2-min.jpg') }}" alt="">
						</div>
						<div class="feature-box-txt">
							<h4>{{__(trans('messages.daytime_help'))}}</h4>
							<p>{{__(trans('messages.are_you_looking'))}}</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="feature-box">
						<div class="feature-box-img">
							<img src="{{ URL::asset('assets/website/images/info-box-img3-min.jpg') }}" alt="">
						</div>
						<div class="feature-box-txt">
							<h4>{{__(trans('messages.after_school'))}}</h4>
							<p>{{__(trans('messages.should_someone'))}}</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="feature-box">
						<div class="feature-box-img">
							<img src="{{ URL::asset('assets/website/images/info-box-img4-min.jpg') }}" alt="">
						</div>
						<div class="feature-box-txt">
							<h4>{{__(trans('messages.health_care'))}}</h4>
							<p>{{__(trans('messages.are_you_ill'))}}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- features-end -->

	<!-- about-start -->
	<div class="about-box-main py-5" id="aboutMain">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="about-img">
						<img src="{{ URL::asset('assets/website/images/how-it-works-img.png') }}" alt="">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="about-content">
                        <h2 class="display-5 fw-bold mb-3">{{__(trans('messages.no_problem'))}} <span>{{__(trans('messages.babysitter_app'))}}</span></h2>
						<p>{{__(trans('messages.find_the_right'))}}</p>
						<p>{{__(trans('messages.find_a_nanny'))}}</p>
                        <p>{{__(trans('messages.app_supports'))}}</p>
						<ul class="checkmark">
							<li><span>{{__(trans('messages.only_verified'))}}</span>&nbsp;<span><svg style="width: 20px" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="#2dd36f" viewBox="0 0 20 20">
                                    <path fill="#2dd36f" d="m18.774 8.245-.892-.893a1.5 1.5 0 0 1-.437-1.052V5.036a2.484 2.484 0 0 0-2.48-2.48H13.7a1.5 1.5 0 0 1-1.052-.438l-.893-.892a2.484 2.484 0 0 0-3.51 0l-.893.892a1.5 1.5 0 0 1-1.052.437H5.036a2.484 2.484 0 0 0-2.48 2.481V6.3a1.5 1.5 0 0 1-.438 1.052l-.892.893a2.484 2.484 0 0 0 0 3.51l.892.893a1.5 1.5 0 0 1 .437 1.052v1.264a2.484 2.484 0 0 0 2.481 2.481H6.3a1.5 1.5 0 0 1 1.052.437l.893.892a2.484 2.484 0 0 0 3.51 0l.893-.892a1.5 1.5 0 0 1 1.052-.437h1.264a2.484 2.484 0 0 0 2.481-2.48V13.7a1.5 1.5 0 0 1 .437-1.052l.892-.893a2.484 2.484 0 0 0 0-3.51Z"/>
                                    <path fill="#fff" d="M8 13a1 1 0 0 1-.707-.293l-2-2a1 1 0 1 1 1.414-1.414l1.42 1.42 5.318-3.545a1 1 0 0 1 1.11 1.664l-6 4A1 1 0 0 1 8 13Z"/>
                                </svg></span></li>
							<li>{{__(trans('messages.you_decide'))}}</li>
							<li>{{__(trans('messages.can_be_rated'))}}</li>
							<li>{{__(trans('messages.contact_details'))}}</li>
                            <li>{{__(trans('messages.hire_a_babysitter'))}}</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
    </div>
    <div class="store-app-main py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="about-content">
                        <h2 class="display-5 fw-bold mb-3">{{__(trans('messages.usp'))}}</h2>
                        <p>{{__(trans('messages.usp_detail'))}}</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="about-img">
                        <img src="{{ URL::asset('assets/website/images/combine_two_worlds.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="store-app-main py-5">
        <div class="container">
            <div class="title-main mb-4">
                <h2 class="display-5 text-center fw-bold">{{__(trans('messages.insights'))}}</h2>
            </div>

            <div class="col-12 col-md-6 mx-auto">
			<div id="carouselExampleCaptions" class="carousel slide" data-interval="false">
				<div class="carousel-indicators">
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
				</div>
				<div class="carousel-inner">
					<div class="carousel-item active">
						<img src="{{ URL::asset('assets/website/images/create_profile.png') }}" class="d-block mx-auto" alt="...">
						<div class="carousel-caption d-none d-md-block">
							<p>{{__(trans('messages.create_profile'))}}</p>
						</div>
					</div>
					<div class="carousel-item">
						<img src="{{ URL::asset('assets/website/images/create_groups.png') }}" class="d-block w-100" alt="...">
						<div class="carousel-caption d-none d-md-block">
							<p>{{__(trans('messages.create_groups'))}}</p>
						</div>
					</div>
					<div class="carousel-item">
						<img src="{{ URL::asset('assets/website/images/show_requests.png') }}" class="d-block w-100" alt="...">
						<div class="carousel-caption d-none d-md-block">
							<p>{{__(trans('messages.show_requests'))}}</p>
						</div>
					</div>
					<div class="carousel-item">
						<img src="{{ URL::asset('assets/website/images/manage_request_apply.png') }}" class="d-block w-100" alt="...">
						<div class="carousel-caption d-none d-md-block">
							<p>{{__(trans('messages.manage_request_apply'))}}</p>
						</div>
					</div>
				</div>
				<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</button>
			</div>
            </div>
        </div>
	</div>
	<!-- about-end -->

	<!-- store-app-start -->
	<div class="store-app-main py-5" id="appStoreMain">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
					<div class="store-app-content">
						<h2 class="display-5 fw-bold mb-2">{{__(trans('messages.children_is_always'))}} <span>{{__(trans('messages.babysitter_app'))}}</span></h2>
						<p class="mb-4">{{__(trans('messages.quick_view'))}}</p>
						<div class="d-flex flex-row flex-wrap align-items-center gap-2">
							<a href="https://apps.apple.com/us/app/babysitter-app/id6463607853" class="btn btn-primary btn-lg round" target="_blank"><i class="fab fa-apple fa-fw me-1"></i>App Store</a>
							<a href="https://play.google.com/store/apps/details?id=com.babysitter.application&pli=1" class="btn btn-primary btn-lg round" target="_blank"><i class="fab fa-google-play fa-fw me-1"></i>Google Play</a>
						</div>
					</div>
				</div>
				<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
					<div class="store-app-img">
						<img src="{{ URL::asset('assets/website/images/app-preview.png') }}" alt="">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- store-app-end -->

    <!-- testimonial-start -->
    <div class="testimonial-main py-5" id="testimonialMain">
        <div class="container">
            <div class="title-main mb-4">
                <h2 class="display-5 text-center fw-bold" style="max-width: unset!important;">
                	{{__(trans('messages.client_says'))}}
                </h2>
            </div>
            <div class="testimonial-slider-main">
                <div class="testimonial-slide">
                    <i class="fas fa-quote-left fa-fw"></i>
                    <p>{{__(trans('messages.testimonial1'))}}</p>
                    <h5>{{__(trans('messages.client1'))}}</h5>
                </div>
                <div class="testimonial-slide">
                    <i class="fas fa-quote-left fa-fw"></i>
                    <p>{{__(trans('messages.testimonial2'))}}</p>
                    <h5>{{__(trans('messages.client2'))}}</h5>
                </div>
                <div class="testimonial-slide">
                    <i class="fas fa-quote-left fa-fw"></i>
                    <p>{{__(trans('messages.testimonial3'))}}</p>
                    <h5>{{__(trans('messages.client3'))}}</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- testimonial-end -->

	<!-- contact-start -->
	<div class="contact-main" id="contactMain">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ms-auto">
					<form class="contact-form" id="contactUsForms">
                        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
                        <div class="title-main">
							<h2 class="display-5 fw-bold">{{__(trans('messages.get_in_touch'))}}</h2>
							<p>{{__(trans('messages.any_question'))}}</p>
						</div>
						<div class="form-input">
							<input type="text" name="full_name" placeholder="{{__(trans('messages.full_name'))}}" class="form-field" required>
						</div>
						<div class="form-input">
							<input type="email" name="email" placeholder="{{__(trans('messages.email'))}}" class="form-field" required>
						</div>
						<div class="form-input">
							<input type="tel" name="phone" placeholder="{{__(trans('messages.phone'))}}" class="form-field">
						</div>
						<div class="form-input">
							<textarea name="message" rows="4" placeholder="{{__(trans('messages.message'))}}" class="form-field" required></textarea>
						</div>
						<div class="form-input-btn">
							<input type="submit" id="contactBtn" value="{{__(trans('messages.send'))}}" class="btn btn-primary round">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- contact-end -->

	<!-- action-start -->
	<div class="action-main py-5">
		<div class="container">
			<div class="action-inner">
				<h2 class="display-5 fw-bold mb-2"><span>{{__(trans('messages.babysitter_app'))}}</span> {{__(trans('messages.makes_daily_life'))}}</h2>
				<p class="mx-auto mb-3">{{__('messages.are_you_unsure')}}</p>
			</div>
		</div>
	</div>
	<!-- action-end -->

    <!-- about-us-start -->
    <div class="about-us" id="aboutUs">
        <div class="container">
            <div class="row">
                <div class="col-lg-1 col-md-1">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="about-img">
                        <img src="{{ URL::asset('assets/website/images/Raffael_Santschi.jpg') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="about-content">
                        <h2 class="display-5 fw-bold mb-3">{{__(trans('messages.person_behind'))}} <span>{{__(trans('messages.babysitter_app'))}}</span>
                            {{__(trans('messages.idea'))}}</h2>
                        <p>{{__(trans('messages.profile_para1'))}}</p>
                        <p>{{__(trans('messages.profile_para2'))}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about-us-end -->

	<!-- footer-start -->
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<ul class="horizontal-menu">
                        @php
                            $locale = app()->getLocale();
                            $privacyPolicyRouteName = "$locale.privacyPolicy";
                            $gtcRouteName = "$locale.gtc";
                        @endphp
						<li><a href="{{ route($privacyPolicyRouteName) }}">{{__(trans('messages.privacy_policy'))}}</a></li>
						<li><a href="{{ route($gtcRouteName) }}">{{__(trans('messages.terms'))}}</a></li>
					</ul>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<p class="cpy-right text-center">{{__(trans('messages.copyright'))}} {{__(trans('messages.babysitter_app_team'))}}.<br>
                        {{__(trans('messages.all_rights_reserved'))}}</p>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<ul class="social-icons">
						<li><a href="https://www.facebook.com/babysitter.appcom"><i class="fa-brands fa-facebook-f"></i></a></li>
						<li><a href="https://www.instagram.com/babysitter_app_com"><i class="fa-brands fa-instagram"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
	<!-- footer-end -->
@endsection

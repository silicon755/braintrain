{{--
* Brain Train - Find the job you love!
 * Copyright (c) Brain Train Kenya. All Rights Reserved
 *
 * Website: http://www.braintrainke.com
 *
 * CODED WITH LOVE
 * ---------------
 * 	@author : Wanekeya Sam
 *  Title   : Full-stack Developer
 * 	created	: 31 August, 2017
 *	version : 1.0
 * 	website : https://www.wanekeyasam.co.ke
 *	Email   : contact@wanekeyasam.co.ke
--}}
@extends('layouts.master')

@section('content')
	<div class="main-container">
		<div class="container">
			<div class="row">

				@if (Session::has('flash_notification.message'))
					<div class="container" style="margin-bottom: -10px; margin-top: -10px;">
						<div class="row">
							<div class="col-lg-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-sm-3 page-sidebar">
					@include('account/inc/sidebar-left')
				</div>
				<!--/.page-sidebar-->

				<div class="col-sm-9 page-content">
					<div class="inner-box">
						<h2 class="title-2"><i class="icon-star-circled"></i> {{ t('Saved search') }} </h2>
						<div class="row">

							<div class="col-sm-4">
								<ul class="list-group list-group-unstyle">
									@if (isset($saved_search) and $saved_search->getCollection()->count() > 0)
										@foreach ($saved_search->getCollection() as $search)
											<li class="list-group-item {{ (Request::get('q')==$search->keyword) ? 'active' : '' }}">
												<a href="{{ lurl('account/saved-search/?'.$search->query.'&pag='.Request::get('pag')) }}" class="">
													<span> {{ str_limit(strtoupper($search->keyword), 20) }} </span>
													<span class="label label-warning" id="{{ $search->id }}">{{ $search->count }}+</span>
												</a>
												<span class="delete-search-result">
                                                    <a href="{{ lurl('account/saved-search/delete/'.$search->id) }}">&times;</a>
                                                </span>
											</li>
										@endforeach
									@else
										<div>
                                            {{ t('You have no save search.') }}
										</div>
									@endif
								</ul>
								<div class="pagination-bar text-center">
									{{ (isset($saved_search)) ? $saved_search->links() : '' }}
								</div>
							</div>

							<div class="col-sm-8">
								<div class="adds-wrapper">

                                    @if (isset($saved_search) and $saved_search->getCollection()->count() > 0)
                                        @if (isset($ads) and $ads->getCollection()->count() > 0)
                                            <?php
                                            foreach($ads->getCollection() as $key => $ad):
                                            if (!$countries->has($ad->country_code)) continue;

                                            // Get AdType Info
                                            $adType = \App\Models\AdType::transById($ad->ad_type_id);
                                            if (empty($adType)) continue;

                                            // Ad URL setting
                                            $adUrl = lurl(slugify($ad->title) . '/' . $ad->id . '.html');
                                            ?>
                                            <div class="item-list">
                                                <div class="col-sm-2 no-padding photobox">
                                                    <div class="add-image">
                                                        <span class="photo-count"><i class="fa fa-camera"></i> </span>
                                                        <a href="{{ $adUrl }}">
                                                            <img class="thumbnail no-margin" src="{{ resize($ad->logo, 'medium') }}" alt="img">
                                                        </a>
                                                    </div>
                                                </div>
                                                <!--/.photobox-->
                                                <div class="col-sm-8 add-desc-box">
                                                    <div class="ads-details">
                                                        <h5 class="add-title"><a href="{{ $adUrl }}"> {{ $ad->title }} </a></h5>
                                            <span class="info-row">
                                                <span class="add-type business-ads tooltipHere" data-toggle="tooltip" data-placement="right" title="{{ $adType->name }}">
                                                    {{ strtoupper(mb_substr($adType->name, 0, 1)) }}
                                                </span>
                                                <?php
                                                // Convert the created_at date to Carbon object
                                                $ad->created_at = \Carbon\Carbon::parse($ad->created_at)->timezone(config('timezone.id'));
                                                $ad->created_at = time_ago($ad->created_at, config('timezone.id'), config('app.locale'));
                                                ?>
                                                <span class="date"><i class=" icon-clock"> </i> {{ $ad->created_at }} </span>
                                                - <span class="category">{{ \App\Models\Category::find($ad->category_id)->name }} </span>
                                                - <span class="item-location"><i
                                                            class="fa fa-map-marker"></i> {{ \App\Models\City::find($ad->city_id)->name }} </span>
                                            </span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-2 text-right text-center-xs price-box">
                                                    <h4 class="item-price">
                                                        {!! \App\Helpers\Number::money($ad->salary_max) !!}
                                                    </h4>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        @else
                                            <div class="text-center">
                                                {{ t('Please select a saved search to show the result') }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center">
                                            {{-- t('No result. Refine your search using other criteria.') --}}
                                        </div>
                                    @endif
								</div>
                                <div class="pagination-bar text-center">
                                    {{ (isset($ads)) ? $ads->appends(Request::except(['page']))->links() : '' }}
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<!-- include checkRadio plugin //Custom check & Radio  -->
	<script type="text/javascript" src="{{ url('assets/plugins/icheck/icheck.min.js') }}"></script>

	<!-- include footable   -->
	<script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
	<script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
	<script type="text/javascript">
		$(function () {
			$('#addManageTable').footable().bind('footable_filtering', function (e) {
				var selected = $('.filter-status').find(':selected').text();
				if (selected && selected.length > 0) {
					e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
					e.clear = !e.filter;
				}
			});

			$('.clear-filter').click(function (e) {
				e.preventDefault();
				$('.filter-status').val('');
				$('table.demo').trigger('footable_clear_filter');
			});

		});
	</script>
	<!-- include custom script for ads table [select all checkbox]  -->
	<script>

		function checkAll(bx) {
			var chkinput = document.getElementsByTagName('input');
			for (var i = 0; i < chkinput.length; i++) {
				if (chkinput[i].type == 'checkbox') {
					chkinput[i].checked = bx.checked;
				}
			}
		}

	</script>
@endsection

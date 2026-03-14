@php
    $locale = \Illuminate\Support\Facades\App::getLocale();
    $themeColor = \App\Helpers\AppHelper::getThemeColor();
    $user = \App\Helpers\AppHelper::getAuthUserCode();
    $defaultCountryCode = $user->company->country_code;
@endphp
{{-- @dd($defaultCountryCode) --}}

<!DOCTYPE html>
<html lang="{{ $locale ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Digital HR Complete HR Attendance System">
    <meta name="author" content="Digital HR">
    <meta name="keywords" content="Digital HR">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.5.5/build/css/intlTelInput.css"/>

    <title>@yield('title')</title>
    <style>
        :root {
            --primary-color: {{ $themeColor->primary_color ?? '#ff3366' }};
            --hover-color: {{ $themeColor->hover_color ?? '#ff3366' }};
            --dark-primary-color: {{ $themeColor->dark_primary_color ?? '#ff3366' }};
            --dark-hover-color: {{ $themeColor->dark_hover_color ?? '#ff3366' }};
        }

        .indicator {
            display: none;
        }
        span.\31 5dys {
            font-size: 16px;
            font-weight: 700;
        }

        .tox.tox-tinymce .tox-statusbar a {
            display: none !important;
        }
        .phone-country + .select2 .select2-selection--single {
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            padding-right: 16px !important;
        }
        .phone-country { width: 90px !important; }
        .phone-country + .select2 { min-width: 90px !important; }
        .phone-country + .select2 .select2-selection__rendered {
            padding-left: 2px !important;
            padding-right: 12px !important;
            display: flex !important;
            align-items: center !important;
            line-height: 38px !important;
        }
        .phone-country + .select2 .select2-selection__arrow {
            position: absolute !important;
            right: 2px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
        }
        .phone-country + .select2 .select2-selection__arrow b {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
        }
        .phone-country + .select2 .fi {
            margin-right: 2px !important;
            vertical-align: middle !important;
        }
        .phone-country + .select2 .select2-results__option {
            padding: 6px 8px !important;
        }
        .phone-group .form-control {
            height: 38px !important;
            line-height: 38px !important;
        }
    </style>
    @include('admin.section.head_links')
    @yield('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@6.11.0/css/flag-icons.min.css">
    {{-- fontawosome icon --}}
</head>

<body>
<div id="preloader" >
    @include('admin.section.preloader')
</div>

@php
    use Carbon\Carbon;

    $user = auth()->guard('admin')->user();

    if($user)
    {
        $startDate = $user->created_at;

        $trialEndDate = $startDate->copy()->addDays(15);

        if (Carbon::now()->lessThan($trialEndDate)) {
            $remainingDays = Carbon::now()->diffInDays($trialEndDate);
        } else {
            $remainingDays = 0;
        }
    }
@endphp

@if (isset($user) && $user->plan_id == 1 && $remainingDays > 0)
    <div style="text-align: center; padding: 20px; width: 100%" class="bg-dark">
        <span class="text-white fw-bold">{!! __('trial_period_will_expire', ['days' => $remainingDays]) !!}</span>
                    <a href="{{ route('admin.subscription.plans') }}" class="btn fw-bold text-white" style="background-color: #fb9d06">{{ __('upgrade_now') }}</a>
    </div>
@endif

<div class="main-wrapper">
    @include('admin.section.sidebar')
    <div class="page-wrapper">
        @include('admin.section.nav')

        <div class="page-content">
            @include('admin.section.page_header')
            @yield('main-content')
        </div>

        <!-- partial -->
        @include('admin.section.footer')
    </div>
</div>

@include('admin.section.body_links')

@include('layouts.nav_notification_scripts')
@include('layouts.nav_search_scripts')
@include('layouts.theme_scripts')

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.5.5/build/js/intlTelInput.min.js"></script>

@yield('scripts')
{{-- <script type="text/javascript">
    let url = "{{ route('admin.language.change') }}";

    $(".changeLang").click(function() {
        let lang = $(this).data('lang');
        window.location.href = url + "?lang=" + lang;
    });
</script> --}}
<script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");
   var DEFAULT_COUNTRY_CODE = "{{ $defaultCountryCode }}";
    (function () {
        function formatFlagWithNameAndCode(state) {
            if (!state.id) return state.text;
            var el = state.element;
            var code = el.getAttribute('data-country');
            var text = el.textContent || el.innerText;
            var span = document.createElement('span');
            span.className = 'd-inline-flex align-items-center';
            var flag = document.createElement('span');
            flag.className = 'fi fi-' + code;
            var label = document.createElement('span');
            label.textContent = text;
            span.appendChild(flag);
            span.appendChild(label);
            return span;
        }

        function formatFlagWithCodeOnly(state) {
            if (!state.id) return state.text;
            var el = state.element;
            var code = el.getAttribute('data-country');
            var dial = el.getAttribute('data-dial') || '';
            var span = document.createElement('span');
            span.className = 'd-inline-flex align-items-center';
            var flag = document.createElement('span');
            flag.className = 'fi fi-' + code;
            var label = document.createElement('span');
            label.textContent = dial ? ('+' + dial) : (el.textContent || el.innerText);
            span.appendChild(flag);
            span.appendChild(label);
            return span;
        }

        function populatePhoneCountrySelect(select) {
            if (!select) return;
            if (select.options.length > 100) return;
            var countries = [];
            if (window.intlTelInputGlobals && typeof window.intlTelInputGlobals.getCountryData === 'function') {
                countries = window.intlTelInputGlobals.getCountryData();
            } else {
                countries = [
                    { iso2: 'ad', dialCode: '376' }, { iso2: 'ae', dialCode: '971' }, { iso2: 'af', dialCode: '93' }, { iso2: 'ag', dialCode: '1' },
                    { iso2: 'ai', dialCode: '1' }, { iso2: 'al', dialCode: '355' }, { iso2: 'am', dialCode: '374' }, { iso2: 'ao', dialCode: '244' },
                    { iso2: 'ar', dialCode: '54' }, { iso2: 'as', dialCode: '1' }, { iso2: 'at', dialCode: '43' }, { iso2: 'au', dialCode: '61' },
                    { iso2: 'aw', dialCode: '297' }, { iso2: 'ax', dialCode: '358' }, { iso2: 'az', dialCode: '994' }, { iso2: 'ba', dialCode: '387' },
                    { iso2: 'bb', dialCode: '1' }, { iso2: 'bd', dialCode: '880' }, { iso2: 'be', dialCode: '32' }, { iso2: 'bf', dialCode: '226' },
                    { iso2: 'bg', dialCode: '359' }, { iso2: 'bh', dialCode: '973' }, { iso2: 'bi', dialCode: '257' }, { iso2: 'bj', dialCode: '229' },
                    { iso2: 'bl', dialCode: '590' }, { iso2: 'bm', dialCode: '1' }, { iso2: 'bn', dialCode: '673' }, { iso2: 'bo', dialCode: '591' },
                    { iso2: 'bq', dialCode: '599' }, { iso2: 'br', dialCode: '55' }, { iso2: 'bs', dialCode: '1' }, { iso2: 'bt', dialCode: '975' },
                    { iso2: 'bw', dialCode: '267' }, { iso2: 'by', dialCode: '375' }, { iso2: 'bz', dialCode: '501' }, { iso2: 'ca', dialCode: '1' },
                    { iso2: 'cc', dialCode: '61' }, { iso2: 'cd', dialCode: '243' }, { iso2: 'cf', dialCode: '236' }, { iso2: 'cg', dialCode: '242' },
                    { iso2: 'ch', dialCode: '41' }, { iso2: 'ci', dialCode: '225' }, { iso2: 'ck', dialCode: '682' }, { iso2: 'cl', dialCode: '56' },
                    { iso2: 'cm', dialCode: '237' }, { iso2: 'cn', dialCode: '86' }, { iso2: 'co', dialCode: '57' }, { iso2: 'cr', dialCode: '506' },
                    { iso2: 'cu', dialCode: '53' }, { iso2: 'cv', dialCode: '238' }, { iso2: 'cw', dialCode: '599' }, { iso2: 'cx', dialCode: '61' },
                    { iso2: 'cy', dialCode: '357' }, { iso2: 'cz', dialCode: '420' }, { iso2: 'de', dialCode: '49' }, { iso2: 'dj', dialCode: '253' },
                    { iso2: 'dk', dialCode: '45' }, { iso2: 'dm', dialCode: '1' }, { iso2: 'do', dialCode: '1' }, { iso2: 'dz', dialCode: '213' },
                    { iso2: 'ec', dialCode: '593' }, { iso2: 'ee', dialCode: '372' }, { iso2: 'eg', dialCode: '20' }, { iso2: 'eh', dialCode: '212' },
                    { iso2: 'er', dialCode: '291' }, { iso2: 'es', dialCode: '34' }, { iso2: 'et', dialCode: '251' }, { iso2: 'fi', dialCode: '358' },
                    { iso2: 'fj', dialCode: '679' }, { iso2: 'fk', dialCode: '500' }, { iso2: 'fm', dialCode: '691' }, { iso2: 'fo', dialCode: '298' },
                    { iso2: 'fr', dialCode: '33' }, { iso2: 'ga', dialCode: '241' }, { iso2: 'gb', dialCode: '44' }, { iso2: 'gd', dialCode: '1' },
                    { iso2: 'ge', dialCode: '995' }, { iso2: 'gf', dialCode: '594' }, { iso2: 'gg', dialCode: '44' }, { iso2: 'gh', dialCode: '233' },
                    { iso2: 'gi', dialCode: '350' }, { iso2: 'gl', dialCode: '299' }, { iso2: 'gm', dialCode: '220' }, { iso2: 'gn', dialCode: '224' },
                    { iso2: 'gp', dialCode: '590' }, { iso2: 'gq', dialCode: '240' }, { iso2: 'gr', dialCode: '30' }, { iso2: 'gs', dialCode: '500' },
                    { iso2: 'gt', dialCode: '502' }, { iso2: 'gu', dialCode: '1' }, { iso2: 'gw', dialCode: '245' }, { iso2: 'gy', dialCode: '592' },
                    { iso2: 'hk', dialCode: '852' }, { iso2: 'hn', dialCode: '504' }, { iso2: 'hr', dialCode: '385' }, { iso2: 'ht', dialCode: '509' },
                    { iso2: 'hu', dialCode: '36' }, { iso2: 'id', dialCode: '62' }, { iso2: 'ie', dialCode: '353' }, { iso2: 'il', dialCode: '972' },
                    { iso2: 'im', dialCode: '44' }, { iso2: 'in', dialCode: '91' }, { iso2: 'io', dialCode: '246' }, { iso2: 'iq', dialCode: '964' },
                    { iso2: 'ir', dialCode: '98' }, { iso2: 'is', dialCode: '354' }, { iso2: 'it', dialCode: '39' }, { iso2: 'je', dialCode: '44' },
                    { iso2: 'jm', dialCode: '1' }, { iso2: 'jo', dialCode: '962' }, { iso2: 'jp', dialCode: '81' }, { iso2: 'ke', dialCode: '254' },
                    { iso2: 'kg', dialCode: '996' }, { iso2: 'kh', dialCode: '855' }, { iso2: 'ki', dialCode: '686' }, { iso2: 'km', dialCode: '269' },
                    { iso2: 'kn', dialCode: '1' }, { iso2: 'kp', dialCode: '850' }, { iso2: 'kr', dialCode: '82' }, { iso2: 'kw', dialCode: '965' },
                    { iso2: 'ky', dialCode: '1' }, { iso2: 'kz', dialCode: '7' }, { iso2: 'la', dialCode: '856' }, { iso2: 'lb', dialCode: '961' },
                    { iso2: 'lc', dialCode: '1' }, { iso2: 'li', dialCode: '423' }, { iso2: 'lk', dialCode: '94' }, { iso2: 'lr', dialCode: '231' },
                    { iso2: 'ls', dialCode: '266' }, { iso2: 'lt', dialCode: '370' }, { iso2: 'lu', dialCode: '352' }, { iso2: 'lv', dialCode: '371' },
                    { iso2: 'ly', dialCode: '218' }, { iso2: 'ma', dialCode: '212' }, { iso2: 'mc', dialCode: '377' }, { iso2: 'md', dialCode: '373' },
                    { iso2: 'me', dialCode: '382' }, { iso2: 'mf', dialCode: '590' }, { iso2: 'mg', dialCode: '261' }, { iso2: 'mh', dialCode: '692' },
                    { iso2: 'mk', dialCode: '389' }, { iso2: 'ml', dialCode: '223' }, { iso2: 'mm', dialCode: '95' }, { iso2: 'mn', dialCode: '976' },
                    { iso2: 'mo', dialCode: '853' }, { iso2: 'mp', dialCode: '1' }, { iso2: 'mq', dialCode: '596' }, { iso2: 'mr', dialCode: '222' },
                    { iso2: 'ms', dialCode: '1' }, { iso2: 'mt', dialCode: '356' }, { iso2: 'mu', dialCode: '230' }, { iso2: 'mv', dialCode: '960' },
                    { iso2: 'mw', dialCode: '265' }, { iso2: 'mx', dialCode: '52' }, { iso2: 'my', dialCode: '60' }, { iso2: 'mz', dialCode: '258' },
                    { iso2: 'na', dialCode: '264' }, { iso2: 'nc', dialCode: '687' }, { iso2: 'ne', dialCode: '227' }, { iso2: 'nf', dialCode: '672' },
                    { iso2: 'ng', dialCode: '234' }, { iso2: 'ni', dialCode: '505' }, { iso2: 'nl', dialCode: '31' }, { iso2: 'no', dialCode: '47' },
                    { iso2: 'np', dialCode: '977' }, { iso2: 'nr', dialCode: '674' }, { iso2: 'nu', dialCode: '683' }, { iso2: 'nz', dialCode: '64' },
                    { iso2: 'om', dialCode: '968' }, { iso2: 'pa', dialCode: '507' }, { iso2: 'pe', dialCode: '51' }, { iso2: 'pf', dialCode: '689' },
                    { iso2: 'pg', dialCode: '675' }, { iso2: 'ph', dialCode: '63' }, { iso2: 'pk', dialCode: '92' }, { iso2: 'pl', dialCode: '48' },
                    { iso2: 'pm', dialCode: '508' }, { iso2: 'pn', dialCode: '64' }, { iso2: 'pr', dialCode: '1' }, { iso2: 'ps', dialCode: '970' },
                    { iso2: 'pt', dialCode: '351' }, { iso2: 'pw', dialCode: '680' }, { iso2: 'py', dialCode: '595' }, { iso2: 'qa', dialCode: '974' },
                    { iso2: 're', dialCode: '262' }, { iso2: 'ro', dialCode: '40' }, { iso2: 'rs', dialCode: '381' }, { iso2: 'ru', dialCode: '7' },
                    { iso2: 'rw', dialCode: '250' }, { iso2: 'sa', dialCode: '966' }, { iso2: 'sb', dialCode: '677' }, { iso2: 'sc', dialCode: '248' },
                    { iso2: 'sd', dialCode: '249' }, { iso2: 'se', dialCode: '46' }, { iso2: 'sg', dialCode: '65' }, { iso2: 'sh', dialCode: '290' },
                    { iso2: 'si', dialCode: '386' }, { iso2: 'sj', dialCode: '47' }, { iso2: 'sk', dialCode: '421' }, { iso2: 'sl', dialCode: '232' },
                    { iso2: 'sm', dialCode: '378' }, { iso2: 'sn', dialCode: '221' }, { iso2: 'so', dialCode: '252' }, { iso2: 'sr', dialCode: '597' },
                    { iso2: 'ss', dialCode: '211' }, { iso2: 'st', dialCode: '239' }, { iso2: 'sv', dialCode: '503' }, { iso2: 'sx', dialCode: '1' },
                    { iso2: 'sy', dialCode: '963' }, { iso2: 'sz', dialCode: '268' }, { iso2: 'tc', dialCode: '1' }, { iso2: 'td', dialCode: '235' },
                    { iso2: 'tg', dialCode: '228' }, { iso2: 'th', dialCode: '66' }, { iso2: 'tj', dialCode: '992' }, { iso2: 'tk', dialCode: '690' },
                    { iso2: 'tl', dialCode: '670' }, { iso2: 'tm', dialCode: '993' }, { iso2: 'tn', dialCode: '216' }, { iso2: 'to', dialCode: '676' },
                    { iso2: 'tr', dialCode: '90' }, { iso2: 'tt', dialCode: '1' }, { iso2: 'tv', dialCode: '688' }, { iso2: 'tw', dialCode: '886' },
                    { iso2: 'tz', dialCode: '255' }, { iso2: 'ua', dialCode: '380' }, { iso2: 'ug', dialCode: '256' }, { iso2: 'us', dialCode: '1' },
                    { iso2: 'uy', dialCode: '598' }, { iso2: 'uz', dialCode: '998' }, { iso2: 'va', dialCode: '379' }, { iso2: 'vc', dialCode: '1' },
                    { iso2: 've', dialCode: '58' }, { iso2: 'vg', dialCode: '1' }, { iso2: 'vi', dialCode: '1' }, { iso2: 'vn', dialCode: '84' },
                    { iso2: 'vu', dialCode: '678' }, { iso2: 'wf', dialCode: '681' }, { iso2: 'ws', dialCode: '685' }, { iso2: 'xk', dialCode: '383' },
                    { iso2: 'ye', dialCode: '967' }, { iso2: 'yt', dialCode: '262' }, { iso2: 'za', dialCode: '27' }, { iso2: 'zm', dialCode: '260' },
                    { iso2: 'zw', dialCode: '263' }
                ];
            }
            select.innerHTML = '';
            for (var i = 0; i < countries.length; i++) {
                var option = document.createElement('option');
                option.value = countries[i].dialCode;
                var name = countries[i].name || '';
                option.text = name ? (name + ' (+' + countries[i].dialCode + ')') : ('+' + countries[i].dialCode);
                option.setAttribute('data-country', countries[i].iso2);
                option.setAttribute('data-dial', countries[i].dialCode);
                select.appendChild(option);
            }
            if (!select.value) select.value = '92';
        }

        function initPhoneSelects() {
            document.querySelectorAll('.phone-country').forEach(function (s) { populatePhoneCountrySelect(s); });
            if (window.jQuery && jQuery.fn.select2) {
                jQuery('.phone-country').select2({
                    templateResult: formatFlagWithNameAndCode,
                    templateSelection: formatFlagWithCodeOnly,
                    minimumResultsForSearch: 0,
                    width: 'style'
                });
                jQuery(document).on('select2:open', function () {
                    var input = document.querySelector('.select2-container--open .select2-search__field');
                    // if (input) input.placeholder = 'Search country or code';
                    if (input) input.placeholder = "{{__('search')}}";
                });
            }
            var tries = 0;

            function ensureLibraryData() {
                if (window.intlTelInputGlobals && typeof window.intlTelInputGlobals.getCountryData === 'function') {
                    document.querySelectorAll('.phone-country').forEach(function (s) {
                        var prev = s.value;
                        populatePhoneCountrySelect(s);
                        if (prev) s.value = prev;
                        if (window.jQuery && jQuery.fn.select2) {
                            jQuery(s).trigger('change.select2');
                        }
                    });
                } else if (tries < 10) {
                    tries++;
                    setTimeout(ensureLibraryData, 500);
                }
            }
            ensureLibraryData();

            // add default country code

            // document.querySelectorAll('.phone-group').forEach(function (group) {
            //     var DEFAULT_COUNTRY_CODE = "{{ $defaultCountryCode }}";
            //     console.log("Default country code:", DEFAULT_COUNTRY_CODE);
            //     var select = group.querySelector('.phone-country');
            //     var input = group.querySelector('input[type="tel"]');
            //     var form = group.closest('form');
            //     // if (select && input) {
            //     //     var digits = (input.value || '').replace(/[^0-9]/g, '');
            //     //     var codes = ['971','966','92','91','44','49','39','41','33','1', '48'];
            //     //     var raw = (input.value || '').replace(/^\+/, '');
            //     //     for (var i = 0; i < codes.length; i++) {
            //     //         var c = codes[i];
            //     //         if (raw.startsWith(c)) {
            //     //             select.value = c;
            //     //             if (window.jQuery && jQuery.fn.select2) {
            //     //                 jQuery(select).trigger('change.select2');
            //     //             }
            //     //             input.value = raw.slice(c.length);
            //     //             break;
            //     //         }
            //     //     }
            //     //     if (!raw) {
            //     //         input.value = digits;
            //     //     }
            //     // }

            //    if (!select) return;

            //     // Set default company country code
            //     if (DEFAULT_COUNTRY_CODE) {

            //         select.value = DEFAULT_COUNTRY_CODE;

            //         if (window.jQuery && jQuery.fn.select2) {
            //             jQuery(select).val(DEFAULT_COUNTRY_CODE).trigger('change.select2');
            //         }

            //     }

            //     if (select && input && form) {
            //         form.addEventListener('submit', function () {
            //             var digits = (input.value || '').replace(/[^0-9]/g, '');
            //             var selectedCode = select.value;

            //             if (digits.startsWith(selectedCode)) {
            //                 digits = digits.slice(selectedCode.length);
            //             }

            //             input.value = '+' + selectedCode + ' ' + digits;
            //         });
            //     }
            // });
            // $(document).ready(function () {

            //     var DEFAULT_COUNTRY_CODE = "{{ $defaultCountryCode }}";
            //     console.log("Default country code:", DEFAULT_COUNTRY_CODE);

            //     $('.phone-group').each(function () {

            //         var select = $(this).find('.phone-country');
            //         var input = $(this).find('input[type="tel"]');
            //         var form = $(this).closest('form');

            //         if (!select.length) return;

            //         // set default country code
            //         if (DEFAULT_COUNTRY_CODE) {
            //             select.val(DEFAULT_COUNTRY_CODE).trigger('change');
            //         }

            //         // on form submit combine country code + phone
            //         if (form.length) {
            //             form.on('submit', function () {

            //                 var digits = (input.val() || '').replace(/[^0-9]/g, '');
            //                 var selectedCode = select.val();

            //                 if (digits.startsWith(selectedCode)) {
            //                     digits = digits.slice(selectedCode.length);
            //                 }

            //                 input.val('+' + selectedCode + ' ' + digits);
            //             });
            //         }

            //     });

            // });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPhoneSelects);
        } else {
            initPhoneSelects();
        }
    })();

    document.addEventListener('DOMContentLoaded', function () {

        const input = document.getElementById('address');
        const suggestionsBox = document.getElementById('address-suggestions');

        if (!input || !suggestionsBox) return;

        let debounceTimer;

        input.addEventListener('keyup', function () {
            let query = this.value.trim();

            clearTimeout(debounceTimer);

            if (query.length < 2) {
                suggestionsBox.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {

                fetch(`https://us-central1-flecso-98e70.cloudfunctions.net/placesAutocomplete?input=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {

                        suggestionsBox.innerHTML = '';

                        if (data.status === "OK" && data.predictions.length > 0) {

                            data.predictions.forEach(item => {

                                let option = document.createElement('a');
                                option.classList.add('list-group-item', 'list-group-item-action');
                                option.textContent = item.description;

                                option.addEventListener('click', function () {

                                    input.value = item.description;
                                    suggestionsBox.innerHTML = '';

                                    input.dispatchEvent(new CustomEvent('placeSelected', {
                                        detail: {
                                            placeId: item.place_id,
                                            description: item.description
                                        }
                                    }));
                                });

                                suggestionsBox.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Autocomplete Error:", error);
                    });

            }, 300);
        });

        input.addEventListener('placeSelected', function (e) {

            const placeId = e.detail.placeId;

            fetch(`https://us-central1-flecso-98e70.cloudfunctions.net/placeDetails?place_id=${placeId}`)
                .then(res => res.json())
                .then(data => {
                    
                    if (data.status === "OK") {

                        const location = data.result.geometry.location;

                        // Set latitude and longitude (only if fields exist)
                        try {
                            const latField = document.getElementById('branch_location_latitude');
                            const lngField = document.getElementById('branch_location_longitude');
                            
                            if (latField) {
                                latField.value = location.lat;
                            }
                            
                            if (lngField) {
                                lngField.value = location.lng;
                            }
                        } catch (error) {
                            // Silently handle lat/lng field errors
                        }

                        // Extract address components for autofill
                        let city = '';
                        let state = '';
                        let postalCode = '';

                        if (data.result.address_components && data.result.address_components.length > 0) {
                            // Extract from address_components
                            data.result.address_components.forEach(component => {
                                const types = component.types;
                                
                                if (types.includes('locality') || types.includes('postal_town')) {
                                    city = component.long_name;
                                }
                                if (types.includes('administrative_area_level_1')) {
                                    state = component.long_name;
                                }
                                if (types.includes('postal_code')) {
                                    postalCode = component.long_name;
                                }
                            });
                        } else {
                            // Fallback: try to parse from formatted_address (better than description)
                            const formattedAddress = data.result.formatted_address || e.detail.description;
                            
                            const parts = formattedAddress.split(',');
                            
                            if (parts.length >= 2) {
                                // Usually format: "Street Address, City, State ZIP, Country"
                                // Or: "City, State ZIP, Country"
                                
                                // Try to identify which part contains the city
                                let cityPart = '';
                                let statePart = '';
                                
                                if (parts.length === 4) {
                                    // Format: Street, City, State ZIP, Country
                                    cityPart = parts[1].trim();
                                    statePart = parts[2].trim();
                                } else if (parts.length === 3) {
                                    // Format: City, State ZIP, Country
                                    cityPart = parts[0].trim();
                                    statePart = parts[1].trim();
                                } else if (parts.length >= 2) {
                                    // Fallback: use first two parts
                                    cityPart = parts[0].trim();
                                    statePart = parts[1].trim();
                                }
                                
                                city = cityPart;
                                
                                // Extract state and potential postal code from state part
                                // Handle formats like "IA 51503", "Council Bluffs IA 51503", etc.
                                const stateZipMatch = statePart.match(/^(.+?)\s+([A-Za-z]{2})\s+(\d{5}(?:-\d{4})?)$/);
                                if (stateZipMatch) {
                                    // If we have "City State ZIP" format
                                    if (parts.length === 3) {
                                        state = stateZipMatch[2]; // The state abbreviation
                                        postalCode = stateZipMatch[3]; // The postal code
                                    } else {
                                        // If we have "State ZIP" format
                                        state = stateZipMatch[2];
                                        postalCode = stateZipMatch[3];
                                    }
                                } else {
                                    // Try simpler pattern: "State ZIP" or "State ZIP Country"
                                    const simpleMatch = statePart.match(/^([A-Za-z\s]+?)(?:\s+(\d{5}(?:-\d{4})?))?$/);
                                    if (simpleMatch) {
                                        state = simpleMatch[1].trim();
                                        if (simpleMatch[2]) {
                                            postalCode = simpleMatch[2];
                                        }
                                    }
                                }
                                
                                // Also check if postal code might be in any part
                                if (!postalCode) {
                                    parts.forEach((part, index) => {
                                        const postalMatch = part.match(/\b(\d{5}(?:-\d{4})?)\b/);
                                        if (postalMatch) {
                                            postalCode = postalMatch[1];
                                        }
                                    });
                                }
                            }
                        }

                        // Find form fields
                        const cityField = document.getElementById('city');
                        const stateField = document.getElementById('province');
                        const postalCodeField = document.getElementById('postal_code');

                        // Set values if fields exist
                        try {
                            if (cityField) {
                                cityField.value = city || '';
                                // Trigger input event for any form validation
                                cityField.dispatchEvent(new Event('input', { bubbles: true }));
                            }

                            if (stateField) {
                                stateField.value = state || '';
                                // Trigger input event for any form validation
                                stateField.dispatchEvent(new Event('input', { bubbles: true }));
                            }

                            if (postalCodeField) {
                                postalCodeField.value = postalCode || '';
                                // Trigger input event for any form validation
                                postalCodeField.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                        } catch (fieldError) {
                            // Silently handle form field errors
                        }
                    }
                })
                .catch(err => {
                    // Silently handle API errors
                });
        });

        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.innerHTML = '';
            }
        });

    });

</script>

</body>

</html>



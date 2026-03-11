@extends('layouts.master')

@section('title', __('company_profile'))

{{--@section('nav-head', __('company_profile'))--}}

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

       <!-- <nav class="d-flex align-items-center justify-content-between">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{ __('index.dashboard') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('index.company_profile') }}</li>
            </ol>
        </nav>-->

        <div class="card">
            <div class="pb-0">
                <form class="forms-sample" enctype="multipart/form-data" method="POST" action="{{route('admin.company.store')}}">
                    @csrf

                    <input type="hidden" name="company_id" value="{{ (!empty($companyDetail)) ? $companyDetail->id : '' }}" />
                    @include('admin.company.form')
                </form>
            </div>
        </div>

    </section>
@endsection
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@6.11.0/css/flag-icons.min.css">
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.5.5/build/js/intlTelInput.min.js"></script>
    <script>
        (function () {
            function formatResult(state) {
                if (!state.id) return state.text;
                var el = state.element;
                var iso = el.getAttribute('data-country');
                var text = el.textContent || el.innerText;
                var span = document.createElement('span');
                span.className = 'd-inline-flex align-items-center';
                var flag = document.createElement('span');
                flag.className = 'fi fi-' + iso;
                flag.style.marginRight = '2px';
                var label = document.createElement('span');
                label.textContent = text;
                span.appendChild(flag);
                span.appendChild(label);
                return span;
            }

            function formatSelection(state) {
                if (!state.id) return state.text;
                var el = state.element;
                var iso = el.getAttribute('data-country');
                var dial = el.getAttribute('data-dial') || '';
                var span = document.createElement('span');
                span.className = 'd-inline-flex align-items-center';
                var flag = document.createElement('span');
                flag.className = 'fi fi-' + iso;
                flag.style.marginRight = '2px';
                var label = document.createElement('span');
                label.textContent = dial ? ('+' + dial) : (el.textContent || el.innerText);
                span.appendChild(flag);
                span.appendChild(label);
                return span;
            }

            function populateAllCountries(selectEl) {
                if (!selectEl) return;
                var countries = [];
                if (window.intlTelInputGlobals && typeof window.intlTelInputGlobals.getCountryData === 'function') {
                    countries = window.intlTelInputGlobals.getCountryData();
                } else {
                    countries = [
                        { iso2: 'ad', dialCode: '376', name: 'Andorra' }, { iso2: 'ae', dialCode: '971', name: 'United Arab Emirates' }, { iso2: 'af', dialCode: '93', name: 'Afghanistan' }, { iso2: 'ag', dialCode: '1', name: 'Antigua and Barbuda' },
                        { iso2: 'ai', dialCode: '1', name: 'Anguilla' }, { iso2: 'al', dialCode: '355', name: 'Albania' }, { iso2: 'am', dialCode: '374', name: 'Armenia' }, { iso2: 'ao', dialCode: '244', name: 'Angola' },
                        { iso2: 'ar', dialCode: '54', name: 'Argentina' }, { iso2: 'as', dialCode: '1', name: 'American Samoa' }, { iso2: 'at', dialCode: '43', name: 'Austria' }, { iso2: 'au', dialCode: '61', name: 'Australia' },
                        { iso2: 'aw', dialCode: '297', name: 'Aruba' }, { iso2: 'ax', dialCode: '358', name: 'Åland Islands' }, { iso2: 'az', dialCode: '994', name: 'Azerbaijan' }, { iso2: 'ba', dialCode: '387', name: 'Bosnia and Herzegovina' },
                        { iso2: 'bb', dialCode: '1', name: 'Barbados' }, { iso2: 'bd', dialCode: '880', name: 'Bangladesh' }, { iso2: 'be', dialCode: '32', name: 'Belgium' }, { iso2: 'bf', dialCode: '226', name: 'Burkina Faso' },
                        { iso2: 'bg', dialCode: '359', name: 'Bulgaria' }, { iso2: 'bh', dialCode: '973', name: 'Bahrain' }, { iso2: 'bi', dialCode: '257', name: 'Burundi' }, { iso2: 'bj', dialCode: '229', name: 'Benin' },
                        { iso2: 'bl', dialCode: '590', name: 'Saint Barthélemy' }, { iso2: 'bm', dialCode: '1', name: 'Bermuda' }, { iso2: 'bn', dialCode: '673', name: 'Brunei Darussalam' }, { iso2: 'bo', dialCode: '591', name: 'Bolivia' },
                        { iso2: 'bq', dialCode: '599', name: 'Caribbean Netherlands' }, { iso2: 'br', dialCode: '55', name: 'Brazil' }, { iso2: 'bs', dialCode: '1', name: 'Bahamas' }, { iso2: 'bt', dialCode: '975', name: 'Bhutan' },
                        { iso2: 'bw', dialCode: '267', name: 'Botswana' }, { iso2: 'by', dialCode: '375', name: 'Belarus' }, { iso2: 'bz', dialCode: '501', name: 'Belize' }, { iso2: 'ca', dialCode: '1', name: 'Canada' },
                        { iso2: 'cc', dialCode: '61', name: 'Cocos (Keeling) Islands' }, { iso2: 'cd', dialCode: '243', name: 'Congo (DRC)' }, { iso2: 'cf', dialCode: '236', name: 'Central African Republic' }, { iso2: 'cg', dialCode: '242', name: 'Congo' },
                        { iso2: 'ch', dialCode: '41', name: 'Switzerland' }, { iso2: 'ci', dialCode: '225', name: "Côte d'Ivoire" }, { iso2: 'ck', dialCode: '682', name: 'Cook Islands' }, { iso2: 'cl', dialCode: '56', name: 'Chile' },
                        { iso2: 'cm', dialCode: '237', name: 'Cameroon' }, { iso2: 'cn', dialCode: '86', name: 'China' }, { iso2: 'co', dialCode: '57', name: 'Colombia' }, { iso2: 'cr', dialCode: '506', name: 'Costa Rica' },
                        { iso2: 'cu', dialCode: '53', name: 'Cuba' }, { iso2: 'cv', dialCode: '238', name: 'Cabo Verde' }, { iso2: 'cw', dialCode: '599', name: 'Curaçao' }, { iso2: 'cx', dialCode: '61', name: 'Christmas Island' },
                        { iso2: 'cy', dialCode: '357', name: 'Cyprus' }, { iso2: 'cz', dialCode: '420', name: 'Czechia' }, { iso2: 'de', dialCode: '49', name: 'Germany' }, { iso2: 'dj', dialCode: '253', name: 'Djibouti' },
                        { iso2: 'dk', dialCode: '45', name: 'Denmark' }, { iso2: 'dm', dialCode: '1', name: 'Dominica' }, { iso2: 'do', dialCode: '1', name: 'Dominican Republic' }, { iso2: 'dz', dialCode: '213', name: 'Algeria' },
                        { iso2: 'ec', dialCode: '593', name: 'Ecuador' }, { iso2: 'ee', dialCode: '372', name: 'Estonia' }, { iso2: 'eg', dialCode: '20', name: 'Egypt' }, { iso2: 'eh', dialCode: '212', name: 'Western Sahara' },
                        { iso2: 'er', dialCode: '291', name: 'Eritrea' }, { iso2: 'es', dialCode: '34', name: 'Spain' }, { iso2: 'et', dialCode: '251', name: 'Ethiopia' }, { iso2: 'fi', dialCode: '358', name: 'Finland' },
                        { iso2: 'fj', dialCode: '679', name: 'Fiji' }, { iso2: 'fk', dialCode: '500', name: 'Falkland Islands' }, { iso2: 'fm', dialCode: '691', name: 'Micronesia' }, { iso2: 'fo', dialCode: '298', name: 'Faroe Islands' },
                        { iso2: 'fr', dialCode: '33', name: 'France' }, { iso2: 'ga', dialCode: '241', name: 'Gabon' }, { iso2: 'gb', dialCode: '44', name: 'United Kingdom' }, { iso2: 'gd', dialCode: '1', name: 'Grenada' },
                        { iso2: 'ge', dialCode: '995', name: 'Georgia' }, { iso2: 'gf', dialCode: '594', name: 'French Guiana' }, { iso2: 'gg', dialCode: '44', name: 'Guernsey' }, { iso2: 'gh', dialCode: '233', name: 'Ghana' },
                        { iso2: 'gi', dialCode: '350', name: 'Gibraltar' }, { iso2: 'gl', dialCode: '299', name: 'Greenland' }, { iso2: 'gm', dialCode: '220', name: 'Gambia' }, { iso2: 'gn', dialCode: '224', name: 'Guinea' },
                        { iso2: 'gp', dialCode: '590', name: 'Guadeloupe' }, { iso2: 'gq', dialCode: '240', name: 'Equatorial Guinea' }, { iso2: 'gr', dialCode: '30', name: 'Greece' }, { iso2: 'gs', dialCode: '500', name: 'South Georgia & South Sandwich Islands' },
                        { iso2: 'gt', dialCode: '502', name: 'Guatemala' }, { iso2: 'gu', dialCode: '1', name: 'Guam' }, { iso2: 'gw', dialCode: '245', name: 'Guinea-Bissau' }, { iso2: 'gy', dialCode: '592', name: 'Guyana' },
                        { iso2: 'hk', dialCode: '852', name: 'Hong Kong' }, { iso2: 'hn', dialCode: '504', name: 'Honduras' }, { iso2: 'hr', dialCode: '385', name: 'Croatia' }, { iso2: 'ht', dialCode: '509', name: 'Haiti' },
                        { iso2: 'hu', dialCode: '36', name: 'Hungary' }, { iso2: 'id', dialCode: '62', name: 'Indonesia' }, { iso2: 'ie', dialCode: '353', name: 'Ireland' }, { iso2: 'il', dialCode: '972', name: 'Israel' },
                        { iso2: 'im', dialCode: '44', name: 'Isle of Man' }, { iso2: 'in', dialCode: '91', name: 'India' }, { iso2: 'io', dialCode: '246', name: 'British Indian Ocean Territory' }, { iso2: 'iq', dialCode: '964', name: 'Iraq' },
                        { iso2: 'ir', dialCode: '98', name: 'Iran' }, { iso2: 'is', dialCode: '354', name: 'Iceland' }, { iso2: 'it', dialCode: '39', name: 'Italy' }, { iso2: 'je', dialCode: '44', name: 'Jersey' },
                        { iso2: 'jm', dialCode: '1', name: 'Jamaica' }, { iso2: 'jo', dialCode: '962', name: 'Jordan' }, { iso2: 'jp', dialCode: '81', name: 'Japan' }, { iso2: 'ke', dialCode: '254', name: 'Kenya' },
                        { iso2: 'kg', dialCode: '996', name: 'Kyrgyzstan' }, { iso2: 'kh', dialCode: '855', name: 'Cambodia' }, { iso2: 'ki', dialCode: '686', name: 'Kiribati' }, { iso2: 'km', dialCode: '269', name: 'Comoros' },
                        { iso2: 'kn', dialCode: '1', name: 'Saint Kitts and Nevis' }, { iso2: 'kp', dialCode: '850', name: 'North Korea' }, { iso2: 'kr', dialCode: '82', name: 'South Korea' }, { iso2: 'kw', dialCode: '965', name: 'Kuwait' },
                        { iso2: 'ky', dialCode: '1', name: 'Cayman Islands' }, { iso2: 'kz', dialCode: '7', name: 'Kazakhstan' }, { iso2: 'la', dialCode: '856', name: 'Laos' }, { iso2: 'lb', dialCode: '961', name: 'Lebanon' },
                        { iso2: 'lc', dialCode: '1', name: 'Saint Lucia' }, { iso2: 'li', dialCode: '423', name: 'Liechtenstein' }, { iso2: 'lk', dialCode: '94', name: 'Sri Lanka' }, { iso2: 'lr', dialCode: '231', name: 'Liberia' },
                        { iso2: 'ls', dialCode: '266', name: 'Lesotho' }, { iso2: 'lt', dialCode: '370', name: 'Lithuania' }, { iso2: 'lu', dialCode: '352', name: 'Luxembourg' }, { iso2: 'lv', dialCode: '371', name: 'Latvia' },
                        { iso2: 'ly', dialCode: '218', name: 'Libya' }, { iso2: 'ma', dialCode: '212', name: 'Morocco' }, { iso2: 'mc', dialCode: '377', name: 'Monaco' }, { iso2: 'md', dialCode: '373', name: 'Moldova' },
                        { iso2: 'me', dialCode: '382', name: 'Montenegro' }, { iso2: 'mf', dialCode: '590', name: 'Saint Martin' }, { iso2: 'mg', dialCode: '261', name: 'Madagascar' }, { iso2: 'mh', dialCode: '692', name: 'Marshall Islands' },
                        { iso2: 'mk', dialCode: '389', name: 'North Macedonia' }, { iso2: 'ml', dialCode: '223', name: 'Mali' }, { iso2: 'mm', dialCode: '95', name: 'Myanmar' }, { iso2: 'mn', dialCode: '976', name: 'Mongolia' },
                        { iso2: 'mo', dialCode: '853', name: 'Macau' }, { iso2: 'mp', dialCode: '1', name: 'Northern Mariana Islands' }, { iso2: 'mq', dialCode: '596', name: 'Martinique' }, { iso2: 'mr', dialCode: '222', name: 'Mauritania' },
                        { iso2: 'ms', dialCode: '1', name: 'Montserrat' }, { iso2: 'mt', dialCode: '356', name: 'Malta' }, { iso2: 'mu', dialCode: '230', name: 'Mauritius' }, { iso2: 'mv', dialCode: '960', name: 'Maldives' },
                        { iso2: 'mw', dialCode: '265', name: 'Malawi' }, { iso2: 'mx', dialCode: '52', name: 'Mexico' }, { iso2: 'my', dialCode: '60', name: 'Malaysia' }, { iso2: 'mz', dialCode: '258', name: 'Mozambique' },
                        { iso2: 'na', dialCode: '264', name: 'Namibia' }, { iso2: 'nc', dialCode: '687', name: 'New Caledonia' }, { iso2: 'ne', dialCode: '227', name: 'Niger' }, { iso2: 'nf', dialCode: '672', name: 'Norfolk Island' },
                        { iso2: 'ng', dialCode: '234', name: 'Nigeria' }, { iso2: 'ni', dialCode: '505', name: 'Nicaragua' }, { iso2: 'nl', dialCode: '31', name: 'Netherlands' }, { iso2: 'no', dialCode: '47', name: 'Norway' },
                        { iso2: 'np', dialCode: '977', name: 'Nepal' }, { iso2: 'nr', dialCode: '674', name: 'Nauru' }, { iso2: 'nu', dialCode: '683', name: 'Niue' }, { iso2: 'nz', dialCode: '64', name: 'New Zealand' },
                        { iso2: 'om', dialCode: '968', name: 'Oman' }, { iso2: 'pa', dialCode: '507', name: 'Panama' }, { iso2: 'pe', dialCode: '51', name: 'Peru' }, { iso2: 'pf', dialCode: '689', name: 'French Polynesia' },
                        { iso2: 'pg', dialCode: '675', name: 'Papua New Guinea' }, { iso2: 'ph', dialCode: '63', name: 'Philippines' }, { iso2: 'pk', dialCode: '92', name: 'Pakistan' }, { iso2: 'pl', dialCode: '48', name: 'Poland' },
                        { iso2: 'pm', dialCode: '508', name: 'Saint Pierre and Miquelon' }, { iso2: 'pn', dialCode: '64', name: 'Pitcairn' }, { iso2: 'pr', dialCode: '1', name: 'Puerto Rico' }, { iso2: 'ps', dialCode: '970', name: 'Palestine' },
                        { iso2: 'pt', dialCode: '351', name: 'Portugal' }, { iso2: 'pw', dialCode: '680', name: 'Palau' }, { iso2: 'py', dialCode: '595', name: 'Paraguay' }, { iso2: 'qa', dialCode: '974', name: 'Qatar' },
                        { iso2: 're', dialCode: '262', name: 'Réunion' }, { iso2: 'ro', dialCode: '40', name: 'Romania' }, { iso2: 'rs', dialCode: '381', name: 'Serbia' }, { iso2: 'ru', dialCode: '7', name: 'Russia' },
                        { iso2: 'rw', dialCode: '250', name: 'Rwanda' }, { iso2: 'sa', dialCode: '966', name: 'Saudi Arabia' }, { iso2: 'sb', dialCode: '677', name: 'Solomon Islands' }, { iso2: 'sc', dialCode: '248', name: 'Seychelles' },
                        { iso2: 'sd', dialCode: '249', name: 'Sudan' }, { iso2: 'se', dialCode: '46', name: 'Sweden' }, { iso2: 'sg', dialCode: '65', name: 'Singapore' }, { iso2: 'sh', dialCode: '290', name: 'Saint Helena' },
                        { iso2: 'si', dialCode: '386', name: 'Slovenia' }, { iso2: 'sj', dialCode: '47', name: 'Svalbard and Jan Mayen' }, { iso2: 'sk', dialCode: '421', name: 'Slovakia' }, { iso2: 'sl', dialCode: '232', name: 'Sierra Leone' },
                        { iso2: 'sm', dialCode: '378', name: 'San Marino' }, { iso2: 'sn', dialCode: '221', name: 'Senegal' }, { iso2: 'so', dialCode: '252', name: 'Somalia' }, { iso2: 'sr', dialCode: '597', name: 'Suriname' },
                        { iso2: 'ss', dialCode: '211', name: 'South Sudan' }, { iso2: 'st', dialCode: '239', name: 'São Tomé and Príncipe' }, { iso2: 'sv', dialCode: '503', name: 'El Salvador' }, { iso2: 'sx', dialCode: '1', name: 'Sint Maarten' },
                        { iso2: 'sy', dialCode: '963', name: 'Syria' }, { iso2: 'sz', dialCode: '268', name: 'Eswatini' }, { iso2: 'tc', dialCode: '1', name: 'Turks and Caicos Islands' }, { iso2: 'td', dialCode: '235', name: 'Chad' },
                        { iso2: 'tg', dialCode: '228', name: 'Togo' }, { iso2: 'th', dialCode: '66', name: 'Thailand' }, { iso2: 'tj', dialCode: '992', name: 'Tajikistan' }, { iso2: 'tk', dialCode: '690', name: 'Tokelau' },
                        { iso2: 'tl', dialCode: '670', name: 'Timor-Leste' }, { iso2: 'tm', dialCode: '993', name: 'Turkmenistan' }, { iso2: 'tn', dialCode: '216', name: 'Tunisia' }, { iso2: 'to', dialCode: '676', name: 'Tonga' },
                        { iso2: 'tr', dialCode: '90', name: 'Türkiye' }, { iso2: 'tt', dialCode: '1', name: 'Trinidad and Tobago' }, { iso2: 'tv', dialCode: '688', name: 'Tuvalu' }, { iso2: 'tw', dialCode: '886', name: 'Taiwan' },
                        { iso2: 'tz', dialCode: '255', name: 'Tanzania' }, { iso2: 'ua', dialCode: '380', name: 'Ukraine' }, { iso2: 'ug', dialCode: '256', name: 'Uganda' }, { iso2: 'us', dialCode: '1', name: 'United States' },
                        { iso2: 'uy', dialCode: '598', name: 'Uruguay' }, { iso2: 'uz', dialCode: '998', name: 'Uzbekistan' }, { iso2: 'va', dialCode: '379', name: 'Vatican City' }, { iso2: 'vc', dialCode: '1', name: 'Saint Vincent and the Grenadines' },
                        { iso2: 've', dialCode: '58', name: 'Venezuela' }, { iso2: 'vg', dialCode: '1', name: 'British Virgin Islands' }, { iso2: 'vi', dialCode: '1', name: 'U.S. Virgin Islands' }, { iso2: 'vn', dialCode: '84', name: 'Vietnam' },
                        { iso2: 'vu', dialCode: '678', name: 'Vanuatu' }, { iso2: 'wf', dialCode: '681', name: 'Wallis and Futuna' }, { iso2: 'ws', dialCode: '685', name: 'Samoa' }, { iso2: 'xk', dialCode: '383', name: 'Kosovo' },
                        { iso2: 'ye', dialCode: '967', name: 'Yemen' }, { iso2: 'yt', dialCode: '262', name: 'Mayotte' }, { iso2: 'za', dialCode: '27', name: 'South Africa' }, { iso2: 'zm', dialCode: '260', name: 'Zambia' },
                        { iso2: 'zw', dialCode: '263', name: 'Zimbabwe' }
                    ];
                }
                var current = selectEl.getAttribute('data-current') || '92';
                selectEl.innerHTML = '';
                for (var i = 0; i < countries.length; i++) {
                    var c = countries[i];
                    var opt = document.createElement('option');
                    opt.value = c.dialCode;
                    var name = c.name || '';
                    opt.text = name ? (name + ' (+' + c.dialCode + ')') : ('+' + c.dialCode);
                    opt.setAttribute('data-country', c.iso2);
                    opt.setAttribute('data-dial', c.dialCode);
                    selectEl.appendChild(opt);
                }
                selectEl.value = current;
            }

            function initCompanyPhone() {
                var selectEl = document.getElementById('company_phone_code');
                if (!selectEl) return;

                populatePhoneCountrySelect(selectEl);

                var currentCode = selectEl.dataset.current || '92';
                currentCode = currentCode.replace('+', '').trim();

                if (window.jQuery && jQuery.fn.select2) {

                    jQuery(selectEl).select2({
                        templateResult: formatResult,
                        templateSelection: formatSelection,
                        minimumResultsForSearch: 0,
                        width: 'style'
                    });

                    jQuery(selectEl).val(currentCode).trigger('change');

                    jQuery(document).on('select2:open', function () {
                        var input = document.querySelector(
                            '.select2-container--open .select2-search__field'
                        );
                        if (input) input.placeholder = 'Search country or code';
                    });
                } else {
                    selectEl.value = currentCode;
                }
            }

            document.querySelectorAll('.phone-group').forEach(function (group) {
                var select = group.querySelector('.phone-country');
                var input = group.querySelector('input[type="tel"]');

                if (select && input) {
                    // Use the data-current attribute for preselected country code
                    var currentCode = select.dataset.current || '92';
                    select.value = currentCode;

                    // If select2 is used, update it
                    if (window.jQuery && jQuery.fn.select2) {
                        jQuery(select).trigger('change.select2');
                    }

                    // input already has the number from Blade, so no need to change it
                    // it will remain as: {{ old('contact_number', $companyDetail->contact_number ?? '') }}
                }
            });

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

                var currentCode = select.dataset.current || '92';
                select.value = currentCode;

                if (window.jQuery && jQuery.fn.select2) {
                    jQuery(select).trigger('change.select2');
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCompanyPhone);
            } else {
                initCompanyPhone();
            }
        })();
    </script>

    <style>
        #company_phone_code { width: 90px !important; }
        #company_phone_code + .select2 { min-width: 90px !important; }
        #company_phone_code + .select2 .select2-selection--single {
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            padding-right: 16px !important;
        }
        #company_phone_code + .select2 .select2-selection__rendered {
            padding-left: 2px !important;
            padding-right: 12px !important;
            display: flex !important;
            align-items: center !important;
            line-height: 38px !important;
        }
        #company_phone_code + .select2 .select2-selection__arrow {
            position: absolute !important;
            right: 2px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
        }
        #company_phone_code + .select2 .select2-selection__arrow b {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
        }
        .phone-group .form-control {
            height: 38px !important;
            line-height: 38px !important;
        }
    </style>
@endsection

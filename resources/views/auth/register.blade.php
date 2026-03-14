@extends('auth.main')

@section('title', __('auth.register'))

@section('page-styles')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<style>
    .split-reg-container { display: flex; min-height: 100vh; background: #fff; }
    .reg-left {flex: 1; display: flex; flex-direction: column; justify-content: center;padding: 40px 60px; max-width: 650px; overflow-y: auto;}
    .reg-logo { text-align: center; margin-bottom: 20px; }
    .reg-header { text-align: center; margin-bottom: 25px; }
    .reg-header h2 { font-weight: 800; color: var(--text-dark); font-size: 26px; margin-bottom: 8px; }
    .reg-header p { color: #64748b; font-size: 14px; }
    .reg-row { display: flex; gap: 14px; flex-wrap: wrap; }
    .reg-field { margin-bottom: 16px; text-align: left; width: 100%; }
    .reg-field.half { width: calc(50% - 7px); }
    .reg-label { font-weight: 600; color:#fb8233; margin-bottom: 6px; display: block; font-size: 13px; }
    .reg-input, .phone-wrap select {width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0;background: #f8fafc; color: var(--text-dark); font-size: 14px; transition: 0.3s; box-sizing: border-box;}
    .reg-input:focus, .phone-wrap select:focus { outline: none; border-color: #057db0; box-shadow: 0 0 0 4px rgba(5, 125, 176, 0.1); }
    .password-wrapper { position: relative; }
    .password-toggle { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 18px; cursor: pointer; color: #94a3b8; }
    .password-toggle.active { color: #057db0; }
    .phone-wrap { display: flex; gap: 10px; }
    .phone-wrap select { max-width: 140px; }
    .reg-btn, .reg-btn-outline {padding: 12px 30px; border-radius: 12px; font-size: 15px; font-weight: 700; cursor: pointer; transition: 0.3s;}
    .reg-btn { background: #057db0; border: none; color: #fff; flex: 1; }
    .reg-btn:hover { background:#fb8233; transform: translateY(-1px); }
    .reg-btn-outline { background: transparent; border: 1px solid #cbd5e1; color: #64748b; }
    .reg-btn-outline:hover { background: #f1f5f9; }
    .reg-step { display: none; }
    .reg-step.active { display: block; animation: slideIn 0.4s ease-out; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .error-text { color: #dc2626; font-size: 12px; margin-top: 4px; font-weight: 500; }
    .reg-links { text-align: center; margin-top: 20px; font-size: 14px; color: #64748b; }
    .reg-links a { color: #057db0; text-decoration: none; font-weight: 700; }
    .reg-right {flex: 1.2; background-color: #057db0;display: flex; flex-direction: column; align-items: center; justify-content: center;position: relative; color: #fff; padding: 40px;}
    .slider-container { width: 100%; max-width: 500px; text-align: center; }
    .slide { display: none; }
    .slide.active { display: block; animation: fadeEffect 0.6s ease-in-out; }
    @keyframes fadeEffect { from { opacity: 0; } to { opacity: 1; } }
    .lottie-box { height: 400px; margin-bottom: 20px; display: flex; justify-content: center; align-items: center; }
    .slide-content h3 { font-size: 24px; margin-bottom: 12px; }
    /*.slide-content p { font-size: 15px; opacity: 0.8; line-height: 1.6; }*/
    .dots-container { margin-top: 30px; display: flex; justify-content: center; gap: 8px; }
    .dot { height: 8px; width: 8px; background-color: rgba(255,255,255,0.3); border-radius: 50%; cursor: pointer; transition: 0.3s; }
    .dot.active { background-color: #fff; width: 25px; border-radius: 4px; }
    @media (max-width: 992px) { .reg-right { display: none; } .reg-left { max-width: 100%; padding: 40px 20px; } }
</style>
@endsection

@section('auth-content')
<div class="split-reg-container">
    
    <div class="reg-left">
        <div class="reg-logo">
            <a href="https://teamiy.com/" target="_blank">
                <img src="{{ asset('assets/images/company-logo.png') }}" style="max-width: 180px;">
            </a>
        </div>

        <div class="reg-header">
            <h3 style="color: #057db0;">{{ __('signup_free_heading') }}</h3>
            <p>{{ __('trial_message') }}</p>
        </div>

        @include('admin.section.flash_message')

        <form method="POST" action="{{ route('admin.company.register.process') }}" id="registerForm">
            @csrf

            <div class="reg-step active">
                <div class="reg-row">
                    <div class="reg-field half">
                        <label class="reg-label">First Name</label>
                        <input type="text" name="first_name" class="reg-input" required pattern="[A-Za-z ]{2,}">
                        @error('first_name')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="reg-field half">
                        <label class="reg-label">Last Name</label>
                        <input type="text" name="last_name" class="reg-input" required pattern="[A-Za-z ]{2,}">
                        @error('last_name')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="reg-field">
                    <label class="reg-label">Email Address</label>
                    <input type="email" name="email" class="reg-input" required placeholder="name@company.com">
                    @error('email')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="reg-row">
                    <div class="reg-field half">
                        <label class="reg-label">Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="reg-input password-field" required minlength="8">
                            <svg class="password-toggle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </div>
                        @error('password')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="reg-field half">
                        <label class="reg-label">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="confirmPassword" class="reg-input password-field" required minlength="8">
                            <svg class="password-toggle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <div class="error-text" id="passMatchError" style="display:none">Passwords do not match</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="reg-step">
                <div class="reg-field">
                    <label class="reg-label">Company Name</label>
                    <input type="text" name="name" class="reg-input" required placeholder="e.g. Acme Corp">
                    @error('name')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="reg-field">
                    <label class="reg-label">Number of Employees</label>
                    <input type="number" name="no_of_employees" class="reg-input" min="1" required placeholder="10">
                    @error('no_of_employees')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="reg-field">
                    <label class="reg-label">Phone Number</label>
                    <div class="phone-wrap">
                        <select name="country_code" required>
                            <option value="" disabled selected>Country Code</option>
                            <option value="+93">Afghanistan +93</option>
                            <option value="+355">Albania +355</option>
                            <option value="+213">Algeria +213</option>
                            <option value="+1">American Samoa +1</option>
                            <option value="+376">Andorra +376</option>
                            <option value="+244">Angola +244</option>
                            <option value="+1">Anguilla +1</option>
                            <option value="+1">Antigua &amp; Barbuda +1</option>
                            <option value="+54">Argentina +54</option>
                            <option value="+374">Armenia +374</option>
                            <option value="+297">Aruba +297</option>
                            <option value="+61">Australia +61</option>
                            <option value="+43">Austria +43</option>
                            <option value="+994">Azerbaijan +994</option>
                            <option value="+1">Bahamas +1</option>
                            <option value="+973">Bahrain +973</option>
                            <option value="+880">Bangladesh +880</option>
                            <option value="+1">Barbados +1</option>
                            <option value="+375">Belarus +375</option>
                            <option value="+32">Belgium +32</option>
                            <option value="+501">Belize +501</option>
                            <option value="+229">Benin +229</option>
                            <option value="+1">Bermuda +1</option>
                            <option value="+975">Bhutan +975</option>
                            <option value="+591">Bolivia +591</option>
                            <option value="+387">Bosnia &amp; Herzegovina +387</option>
                            <option value="+267">Botswana +267</option>
                            <option value="+55">Brazil +55</option>
                            <option value="+1">British Virgin Islands +1</option>
                            <option value="+673">Brunei +673</option>
                            <option value="+359">Bulgaria +359</option>
                            <option value="+226">Burkina Faso +226</option>
                            <option value="+257">Burundi +257</option>
                            <option value="+855">Cambodia +855</option>
                            <option value="+237">Cameroon +237</option>
                            <option value="+1">Canada +1</option>
                            <option value="+238">Cape Verde +238</option>
                            <option value="+1">Cayman Islands +1</option>
                            <option value="+236">Central African Rep. +236</option>
                            <option value="+235">Chad +235</option>
                            <option value="+56">Chile +56</option>
                            <option value="+86">China +86</option>
                            <option value="+57">Colombia +57</option>
                            <option value="+269">Comoros +269</option>
                            <option value="+243">Congo (DRC) +243</option>
                            <option value="+242">Congo (Republic) +242</option>
                            <option value="+682">Cook Islands +682</option>
                            <option value="+506">Costa Rica +506</option>
                            <option value="+385">Croatia +385</option>
                            <option value="+53">Cuba +53</option>
                            <option value="+599">Curacao +599</option>
                            <option value="+357">Cyprus +357</option>
                            <option value="+420">Czech Republic +420</option>
                            <option value="+45">Denmark +45</option>
                            <option value="+253">Djibouti +253</option>
                            <option value="+1">Dominica +1</option>
                            <option value="+1">Dominican Republic +1</option>
                            <option value="+593">Ecuador +593</option>
                            <option value="+20">Egypt +20</option>
                            <option value="+503">El Salvador +503</option>
                            <option value="+240">Equatorial Guinea +240</option>
                            <option value="+291">Eritrea +291</option>
                            <option value="+372">Estonia +372</option>
                            <option value="+251">Ethiopia +251</option>
                            <option value="+679">Fiji +679</option>
                            <option value="+358">Finland +358</option>
                            <option value="+33">France +33</option>
                            <option value="+594">French Guiana +594</option>
                            <option value="+241">Gabon +241</option>
                            <option value="+220">Gambia +220</option>
                            <option value="+995">Georgia +995</option>
                            <option value="+49">Germany +49</option>
                            <option value="+233">Ghana +233</option>
                            <option value="+350">Gibraltar +350</option>
                            <option value="+30">Greece +30</option>
                            <option value="+299">Greenland +299</option>
                            <option value="+1">Grenada +1</option>
                            <option value="+502">Guatemala +502</option>
                            <option value="+44">Guernsey +44</option>
                            <option value="+224">Guinea +224</option>
                            <option value="+245">Guinea-Bissau +245</option>
                            <option value="+592">Guyana +592</option>
                            <option value="+509">Haiti +509</option>
                            <option value="+504">Honduras +504</option>
                            <option value="+36">Hungary +36</option>
                            <option value="+354">Iceland +354</option>
                            <option value="+91">India +91</option>
                            <option value="+62">Indonesia +62</option>
                            <option value="+98">Iran +98</option>
                            <option value="+964">Iraq +964</option>
                            <option value="+353">Ireland +353</option>
                            <option value="+44">Isle of Man +44</option>
                            <option value="+972">Israel +972</option>
                            <option value="+39">Italy +39</option>
                            <option value="+1">Jamaica +1</option>
                            <option value="+81">Japan +81</option>
                            <option value="+44">Jersey +44</option>
                            <option value="+962">Jordan +962</option>
                            <option value="+7">Kazakhstan +7</option>
                            <option value="+254">Kenya +254</option>
                            <option value="+686">Kiribati +686</option>
                            <option value="+383">Kosovo +383</option>
                            <option value="+965">Kuwait +965</option>
                            <option value="+996">Kyrgyzstan +996</option>
                            <option value="+856">Laos +856</option>
                            <option value="+371">Latvia +371</option>
                            <option value="+423">Liechtenstein +423</option>
                            <option value="+370">Lithuania +370</option>
                            <option value="+352">Luxembourg +352</option>
                            <option value="+261">Madagascar +261</option>
                            <option value="+265">Malawi +265</option>
                            <option value="+60">Malaysia +60</option>
                            <option value="+960">Maldives +960</option>
                            <option value="+223">Mali +223</option>
                            <option value="+356">Malta +356</option>
                            <option value="+692">Marshall Islands +692</option>
                            <option value="+596">Martinique +596</option>
                            <option value="+222">Mauritania +222</option>
                            <option value="+230">Mauritius +230</option>
                            <option value="+52">Mexico +52</option>
                            <option value="+377">Monaco +377</option>
                            <option value="+976">Mongolia +976</option>
                            <option value="+382">Montenegro +382</option>
                            <option value="+1">Montserrat +1</option>
                            <option value="+212">Morocco +212</option>
                            <option value="+258">Mozambique +258</option>
                            <option value="+95">Myanmar +95</option>
                            <option value="+264">Namibia +264</option>
                            <option value="+674">Nauru +674</option>
                            <option value="+977">Nepal +977</option>
                            <option value="+31">Netherlands +31</option>
                            <option value="+1">Netherlands Antilles +1</option>
                            <option value="+687">New Caledonia +687</option>
                            <option value="+64">New Zealand +64</option>
                            <option value="+505">Nicaragua +505</option>
                            <option value="+227">Niger +227</option>
                            <option value="+234">Nigeria +234</option>
                            <option value="+683">Niue +683</option>
                            <option value="+672">Norfolk Island +672</option>
                            <option value="+1">Northern Mariana Islands +1</option>
                            <option value="+977">North Korea +977</option>
                            <option value="+47">Norway +47</option>
                            <option value="+968">Oman +968</option>
                            <option value="+92">Pakistan +92</option>
                            <option value="+680">Palau +680</option>
                            <option value="+970">Palestine +970</option>
                            <option value="+507">Panama +507</option>
                            <option value="+675">Papua New Guinea +675</option>
                            <option value="+595">Paraguay +595</option>
                            <option value="+51">Peru +51</option>
                            <option value="+63">Philippines +63</option>
                            <option value="+48">Poland +48</option>
                            <option value="+351">Portugal +351</option>
                            <option value="+1">Puerto Rico +1</option>
                            <option value="+974">Qatar +974</option>
                            <option value="+40">Romania +40</option>
                            <option value="+7">Russia +7</option>
                            <option value="+250">Rwanda +250</option>
                            <option value="+262">Réunion +262</option>
                            <option value="+290">Saint Helena +290</option>
                            <option value="+1">Saint Kitts &amp; Nevis +1</option>
                            <option value="+1">Saint Lucia +1</option>
                            <option value="+508">Saint Pierre &amp; Miquelon +508</option>
                            <option value="+1">Saint Vincent &amp; Grenadines +1</option>
                            <option value="+685">Samoa +685</option>
                            <option value="+378">San Marino +378</option>
                            <option value="+239">Sao Tome &amp; Principe +239</option>
                            <option value="+966">Saudi Arabia +966</option>
                            <option value="+221">Senegal +221</option>
                            <option value="+381">Serbia +381</option>
                            <option value="+248">Seychelles +248</option>
                            <option value="+232">Sierra Leone +232</option>
                            <option value="+65">Singapore +65</option>
                            <option value="+421">Slovakia +421</option>
                            <option value="+386">Slovenia +386</option>
                            <option value="+677">Solomon Islands +677</option>
                            <option value="+252">Somalia +252</option>
                            <option value="+27">South Africa +27</option>
                            <option value="+82">South Korea +82</option>
                            <option value="+211">South Sudan +211</option>
                            <option value="+34">Spain +34</option>
                            <option value="+94">Sri Lanka +94</option>
                            <option value="+249">Sudan +249</option>
                            <option value="+597">Suriname +597</option>
                            <option value="+268">Swaziland +268</option>
                            <option value="+46">Sweden +46</option>
                            <option value="+41">Switzerland +41</option>
                            <option value="+963">Syria +963</option>
                            <option value="+886">Taiwan +886</option>
                            <option value="+992">Tajikistan +992</option>
                            <option value="+255">Tanzania +255</option>
                            <option value="+66">Thailand +66</option>
                            <option value="+670">Timor-Leste +670</option>
                            <option value="+228">Togo +228</option>
                            <option value="+690">Tokelau +690</option>
                            <option value="+676">Tonga +676</option>
                            <option value="+216">Tunisia +216</option>
                            <option value="+90">Turkey +90</option>
                            <option value="+993">Turkmenistan +993</option>
                            <option value="+1">Turks &amp; Caicos +1</option>
                            <option value="+688">Tuvalu +688</option>
                            <option value="+256">Uganda +256</option>
                            <option value="+380">Ukraine +380</option>
                            <option value="+971">United Arab Emirates +971</option>
                            <option value="+44">United Kingdom +44</option>
                            <option value="+1">United States +1</option>
                            <option value="+598">Uruguay +598</option>
                            <option value="+998">Uzbekistan +998</option>
                            <option value="+678">Vanuatu +678</option>
                            <option value="+58">Venezuela +58</option>
                            <option value="+84">Vietnam +84</option>
                            <option value="+681">Wallis &amp; Futuna +681</option>
                            <option value="+967">Yemen +967</option>
                            <option value="+260">Zambia +260</option>
                            <option value="+263">Zimbabwe +263</option>
                            </select>
                        <input type="tel" name="contact_number" class="reg-input" required pattern="[0-9]{7,15}" placeholder="3001234567">
                    </div>
                    @error('contact_number')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="reg-step">
                <div style="background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
                    <div style="display: flex; gap: 10px; align-items: flex-start;">
                        <input type="checkbox" name="terms_conditions" id="terms" required style="margin-top: 4px;">
                        <label for="terms" style="font-size: 14px; color: #475569; line-height: 1.5;">
                            I agree to the <a href="https://teamiy.com/privacy-policy/" target="_blank" style="color:#057db0; font-weight:600;">Privacy Policy</a> & <a href="https://teamiy.com/terms-and-conditions/" target="_blank" style="color:#057db0; font-weight:600;">Terms of Service</a>.
                        </label>
                    </div>
                </div>
                @error('terms_conditions')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="reg-row" style="margin-top:20px; gap:10px;">
                <button type="button" class="reg-btn-outline" id="prevBtn">Previous</button>
                <button type="button" class="reg-btn" id="nextBtn">Next Step</button>
                <button type="submit" class="reg-btn" id="submitBtn">Create Account</button>
            </div>

            <div class="reg-links">
                Already have an account? <a href="{{ route('admin.login') }}">Sign In</a>
            </div>
        </form>
    </div>

    <div class="reg-right">
        <div class="slider-container">
            <div class="slide active">
                <div class="lottie-box">
                    <lottie-player 
                    src="{{ asset('assets/lottie/signin-data.json') }}" 
                    background="transparent" speed="1" 
                    
                    loop autoplay>
                </lottie-player>
                </div>
                <div class="slide-content">
                    <h3>Empower Your Team</h3>
                    <p>Join thousands of companies using Teamiy to automate HR, payroll, and attendance management.</p>
                </div>
            </div>

            <div class="slide">
                <div class="lottie-box">
                    <lottie-player 
                    src="{{ asset('assets/lottie/newstart.json') }}" 
                    background="transparent" speed="1" 
                     
                    loop autoplay>
                </lottie-player>
                </div>
                <div class="slide-content">
                    <h3>Data Driven Insights</h3>
                    <p>Get real-time reports and analytics to make better decisions for your organization's growth.</p>
                </div>
            </div>

            <div class="dots-container">
                <span class="dot active" onclick="currentSlide(0)"></span>
                <span class="dot" onclick="currentSlide(1)"></span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
    // Form Wizard Logic
    const steps = [...document.querySelectorAll('.reg-step')],
          nextBtn = document.getElementById('nextBtn'),
          prevBtn = document.getElementById('prevBtn'),
          submitBtn = document.getElementById('submitBtn'),
          form = document.getElementById('registerForm');

    let currentStepIndex = 0;

    function showStep(index) {
        steps.forEach((s, i) => s.classList.toggle('active', i === index));
        prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
        nextBtn.style.display = index === steps.length - 1 ? 'none' : 'inline-block';
        submitBtn.style.display = index === steps.length - 1 ? 'inline-block' : 'none';
    }
    showStep(0);

    nextBtn.onclick = () => {
        const inputs = steps[currentStepIndex].querySelectorAll('input, select');
        let valid = true;
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.reportValidity();
                valid = false;
            }
        });
        if (!valid) return;
        
        currentStepIndex++;
        showStep(currentStepIndex);
    };

    prevBtn.onclick = () => {
        currentStepIndex--;
        showStep(currentStepIndex);
    };

    // Password Toggle
    document.querySelectorAll('.password-toggle').forEach((icon, i) => {
        icon.onclick = () => {
            const field = document.querySelectorAll('.password-field')[i];
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.add('active');
            } else {
                field.type = 'password';
                icon.classList.remove('active');
            }
        };
    });

    // Slider Logic
    let slideIndex = 0;
    let autoSlideTimer;

    function showSlides(n) {
        let slides = document.getElementsByClassName("slide");
        let dots = document.getElementsByClassName("dot");
        if (n >= slides.length) slideIndex = 0;
        if (n < 0) slideIndex = slides.length - 1;
        for (let i = 0; i < slides.length; i++) {
            slides[i].classList.remove("active");
            dots[i].classList.remove("active");
        }
        slides[slideIndex].classList.add("active");
        dots[slideIndex].classList.add("active");
    }

    function currentSlide(n) {
        clearInterval(autoSlideTimer);
        slideIndex = n;
        showSlides(slideIndex);
        startAutoSlide();
    }

    function startAutoSlide() {
        autoSlideTimer = setInterval(() => {
            slideIndex++;
            showSlides(slideIndex);
        }, 5000);
    }

    window.onload = () => {
        startAutoSlide();
    };
</script>
@endsection

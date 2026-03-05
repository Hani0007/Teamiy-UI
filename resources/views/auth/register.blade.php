@extends('auth.main')

@section('title', __('auth.register'))

@section('page-styles')
<style>
.reg-wrapper{min-height:100vh;display:flex;justify-content:center;align-items:center;background:linear-gradient(135deg,#057db0,#045a80);padding:20px}.reg-card{width:100%;max-width:560px;padding:45px;border-radius:20px;text-align:center}.glass-effect{background:rgba(255,255,255,.15);backdrop-filter:blur(18px);border:1px solid rgba(255,255,255,.25);box-shadow:0 25px 55px rgba(0,0,0,.3)}.reg-logo img{max-width:190px;margin-bottom:30px}.reg-title{color:#fff;font-size:20px;margin-bottom:12px}.reg-subtitle{color:#dff3ff;font-size:15px;margin-bottom:30px}.reg-row{display:flex;gap:14px;flex-wrap:wrap}.reg-field{margin-bottom:16px;text-align:left;width:100%}.reg-field.half{width:48%}.reg-field label{color:#e9f7ff;font-size:14px;margin-bottom:6px;display:block}.reg-input,select{width:100%;padding:12px 16px;border-radius:12px;border:none;background:rgba(255,255,255,.22);color:#fff}.reg-input:focus,select:focus{outline:none;box-shadow:0 0 0 2px rgba(251,118,51,.5)}.reg-btn,.reg-btn-outline{padding:12px 80px;border-radius:40px;font-size:16px;font-weight:600;cursor:pointer}.reg-btn{background:#fb7633;border:none;color:#fff}.reg-btn-outline{background:transparent;border:1px solid #fff;color:#fff}.reg-links a{color:#e0f3ff;text-decoration:none}.reg-checkbox{color:#e9f7ff;font-size:14px;margin-bottom:15px}.reg-step{display:none}.reg-step.active{display:block}.password-wrapper{position:relative}.password-toggle{position:absolute;right:14px;top:50%;transform:translateY(-50%);width:20px;height:20px;stroke:rgba(255,255,255,.6);stroke-width:1.6;fill:none;cursor:pointer}.password-toggle.active{stroke:#fff}.password-toggle.active line{display:none}.phone-wrap{display:flex;gap:10px}.phone-wrap select{max-width:150px;background-color: #3b89ac; border: 1px solid rgba(255,255,255,0.2);border-radius: 15px;}.reg-checkbox a{color:#e0f3ff;text-decoration:underline}.error-text{color:#ffd2d2;font-size:13px;margin-top:6px}.alert.alert-danger{background-color:#ffffff;color:var(--primary-color);border-color:var(--primary-color);padding:10px 15px;border-radius:8px;margin-bottom:10px;opacity:1;transition:opacity 1s ease}.alert{transition:opacity 1s ease;opacity:1}@media(max-width:768px){.reg-field.half{width:100%}.reg-btn,.reg-btn-outline{width:100%;padding:12px}.reg-card{padding:24px}.phone-wrap{flex-direction:column}}
</style>
@endsection

@section('auth-content')
<section class="reg-wrapper">
    <div class="reg-card glass-effect">

        <div class="reg-logo">
            <a href="https://teamiy.com/" target="_blank">
                <img src="{{ asset('assets/images/teamiy-wh-logo.webp') }}">
            </a>
        </div>

        <h3 class="reg-title">{{ __('signup_free_heading') }}</h3>
        <p class="reg-subtitle">{{ __('trial_message') }}</p>

        @include('admin.section.flash_message')

        <form method="POST" action="{{ route('admin.company.register.process') }}" id="registerForm">
            @csrf

            <!-- STEP 1 -->
            <div class="reg-step active">
                <div class="reg-row">
                    <div class="reg-field half">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="reg-input" required pattern="[A-Za-z ]{2,}">
                        @error('first_name')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div class="reg-field half">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="reg-input" required pattern="[A-Za-z ]{2,}">
                        @error('last_name')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="reg-field">
                    <label>Email</label>
                    <input type="email" name="email" class="reg-input" required>
                    @error('email')<div class="text-danger fw-bolder">{{ $message }}</div>@enderror
                </div>

                <div class="reg-row">
                    <div class="reg-field half">
                        <label>Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="reg-input password-field" required minlength="8">
                            <svg class="password-toggle" viewBox="0 0 24 24">
                                <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                                <line x1="3" y1="21" x2="21" y2="3"/>
                            </svg>
                        </div>
                        @error('password')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="reg-field half">
                        <label>Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="confirmPassword" class="reg-input password-field" required minlength="8">
                            <svg class="password-toggle" viewBox="0 0 24 24">
                                <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                                <line x1="3" y1="21" x2="21" y2="3"/>
                            </svg>
                        </div>
                        <div class="error-text" id="passMatchError" style="display:none">Passwords do not match</div>
                    </div>
                </div>
            </div>

            <!-- STEP 2 -->
            <div class="reg-step">
                <div class="reg-field">
                    <label>Company Name</label>
                    <input type="text" name="name" class="reg-input" required>
                    @error('name')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="reg-field">
                    <label>Number of Employees</label>
                    <input type="number" name="no_of_employees" class="reg-input" min="1" required>
                    @error('no_of_employees')<div class="error-text">{{ $message }}</div>@enderror
                </div>

                <div class="reg-field">
                    <label>Phone Number</label>
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
                            <!-- Full country codes here (as provided earlier) -->
                            <!-- baki list bilkul same rahegi -->
                        </select>
                        <input type="tel" name="contact_number" class="reg-input" required pattern="[0-9]{7,15}">
                    </div>
                    @error('country_code')<div class="error-text">{{ $message }}</div>@enderror
                    @error('contact_number')<div class="error-text">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="reg-step">
                <div class="reg-checkbox">
                    <input type="checkbox" name="terms_conditions" required>
                    <span>I agree to
                        <a href="https://teamiy.com/privacy-policy/" target="_blank"><b>Privacy Policy</b></a> &
                        <a href="https://teamiy.com/terms-and-conditions/" target="_blank"><b>Terms</b></a>
                    </span>
                </div>
                @error('terms_conditions')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <!-- NAV -->
            <div class="reg-row" style="justify-content:center;gap:10px;margin-top:20px">
                <button type="button" class="reg-btn-outline" id="prevBtn">Previous</button>
                <button type="button" class="reg-btn" id="nextBtn">Next</button>
                <button type="submit" class="reg-btn" id="submitBtn">Register</button>
            </div>

            <div class="reg-links" style="margin-top:15px">
                <a href="{{ route('admin.login') }}">Already have an account? Sign in</a>
            </div>

        </form>
    </div>
</section>
@endsection

@section('page-scripts')
<script>
const steps=[...document.querySelectorAll('.reg-step')],
nextBtn=document.getElementById('nextBtn'),
prevBtn=document.getElementById('prevBtn'),
submitBtn=document.getElementById('submitBtn'),
form=document.getElementById('registerForm');

let currentStep=0;

function showStep(){
    steps.forEach((s,i)=>s.classList.toggle('active',i===currentStep));
    prevBtn.style.display=currentStep===0?'none':'inline-block';
    nextBtn.style.display=currentStep===steps.length-1?'none':'inline-block';
    submitBtn.style.display=currentStep===steps.length-1?'inline-block':'none';
}
showStep();

nextBtn.onclick=()=>{
    const invalid=steps[currentStep].querySelector('input:invalid, select:invalid');
    if(invalid){invalid.reportValidity();return;}
    currentStep++;showStep();
};
prevBtn.onclick=()=>{currentStep--;showStep();};

document.querySelectorAll('.password-toggle').forEach((icon,i)=>{
    icon.onclick=()=>{
        const field=document.querySelectorAll('.password-field')[i];
        if(field.type==='password'){field.type='text';icon.classList.add('active')}
        else{field.type='password';icon.classList.remove('active')}
    }
});

const pass=document.getElementById('password');
const confirmPass=document.getElementById('confirmPassword');
const passError=document.getElementById('passMatchError');

function checkPasswords(){
    if(confirmPass.value && pass.value!==confirmPass.value){
        passError.style.display='block';
        confirmPass.setCustomValidity('Passwords do not match');
    }else{
        passError.style.display='none';
        confirmPass.setCustomValidity('');
    }
}
pass.oninput=checkPasswords;
confirmPass.oninput=checkPasswords;

form.addEventListener('submit',e=>{
    const invalid=form.querySelector('input:invalid, select:invalid');
    if(invalid){
        invalid.reportValidity();
        currentStep=[...steps].indexOf(invalid.closest('.reg-step'));
        showStep();
        e.preventDefault();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 1s ease';
        alert.style.opacity = '1';
        setTimeout(() => {
            alert.style.opacity = '0';
        }, 15000);
        setTimeout(() => {
            if(alert.parentNode) alert.parentNode.removeChild(alert);
        }, 16000);
    });
});
</script>
@endsection

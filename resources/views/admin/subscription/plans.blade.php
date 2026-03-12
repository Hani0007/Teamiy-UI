@extends('layouts.master')

@section('title', __('index.users'))
@section('action', __('index.add'))

<style>
    /* ===== Title Section ===== */
    .title-bar {
        text-align: center;
        font-size: 26px;
        font-weight: 700;
        color: #057db0;
        margin-bottom: 10px;
    }

    .pricing-subtitle {
        text-align: center;
        font-size: 14px;
        color: #555;
        margin-bottom: 30px;
    }

    /* ===== Plans Container ===== */
    .plans-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    /* ===== Individual Plan Card ===== */
    .plan-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        width: 320px;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 30px 25px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .plan-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
    }

    /* ===== Plan Name ===== */
    .plan-header {
        font-size: 20px;
        font-weight: 700;
        color: #057db0;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    /* ===== Price ===== */
    .plan-price-container {
        text-align: center;
        margin: 15px 0;
    }

    .plan-price {
        font-size: 30px;
        font-weight: 700;
        color: #fb7633;
        display: block;
    }

    .plan-cycle {
        font-size: 14px;
        color: #555;
        margin-top: 4px;
    }

    /* ===== Plan Description ===== */
    /* Removed the 'Perfect for your team' line */

    /* ===== Features List ===== */
    .plan-modules {
        width: 100%;
        margin-bottom: 20px;
    }

    .plan-modules div {
        display: flex;
        align-items: center;
        font-size: 14px;
        margin: 6px 0;
        color: #555;
    }

    .check {
        color: #fb7633;
        margin-right: 10px;
        font-weight: 600;
    }

    /* ===== Cycle + Quantity + Button ===== */
    .plan-footer {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: center;
        margin-top: auto;
        /* ensure same height cards */
    }

    .plan-controls {
        display: flex;
        gap: 10px;
        width: 100%;
    }

    .cycle-toggle {
        flex: 1;
        display: flex;
        justify-content: space-between;
        background: #f1f1f1;
        border-radius: 25px;
        padding: 5px;
        cursor: pointer;
    }

    .cycle-toggle div {
        flex: 1;
        text-align: center;
        padding: 6px 0;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        user-select: none;
    }

    .cycle-toggle .active {
        background-color: #057db0;
        color: #fff;
    }

    .employee-count {
        width: 70px;
        padding: 6px 10px;
        border-radius: 25px;
        border: 1px solid #ced4da;
        text-align: center;
    }

    /* ===== Subscribe Button ===== */
    .subscribe-btn {
        background-color: #057db0 !important;
        color: #fff !important;
        font-weight: 600 !important;
        border-radius: 25px !important;
        width: 100%;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
    }

    .subscribe-btn:hover {
        background-color: #0f436f !important;
    }

    /* ===== Stripe Inputs ===== */
    .stripe-input {
        padding: 12px 14px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        background: #fff;
    }

    .StripeElement {
        width: 100%;
    }

    .StripeElement iframe {
        position: relative !important;
        z-index: 1056 !important;
    }

    /* ===== Stripe Modal Header Flex ===== */
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* ===== Responsive ===== */
    @media screen and (max-width: 992px) {
        .plans-container {
            gap: 15px;
        }

        .plan-card {
            width: 45%;
        }
    }

    @media screen and (max-width: 768px) {
        .plans-container {
            flex-direction: column;
            align-items: center;
        }

        .plan-card {
            width: 90%;
        }
    }
</style>

@section('button')
    <div class="float-end">
        <a href="{{ route('admin.users.index') }}">
            <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i>
                {{ __('index.back') }}</button>
        </a>
    </div>
@endsection

@section('main-content')
    <section class="content">
        @if (auth()->user()->trial_expiry && \Carbon\Carbon::parse(auth()->user()->trial_expiry)->lt(\Carbon\Carbon::today()))
            <div class="alert alert-danger text-center">
                Your {{ $planName }} plan has expired. To continue using the dashboard and access all features smoothly,
                please upgrade your plan.
            </div>
        @endif

        <div class="card-user">
            <div class="profile-detail container">
                <div class="title-bar">{{ __('subscription_plans') }}</div>
                <div class="pricing-subtitle">Flexible Plans For Every Team</div>

                <div class="plans-container">
                    @foreach ($plans as $plan)
                        <div class="plan-card">
                            <div class="plan-header">{{ $plan->name }}</div>

                            <div class="plan-price-container">
                                <span class="plan-price" data-month="{{ $plan->price_per_month }}"
                                    data-year="{{ $plan->price_per_year }}">
                                    €{{ $plan->price_per_month }}/employee
                                </span>
                                {{-- Removed plan-cycle text for /year --}}
                            </div>

                            <div class="plan-modules">
                                @php
                                    $basicFeatures = $plans
                                        ->firstWhere('name', 'Basic')
                                        ->packageModules->pluck('module.name')
                                        ->toArray();
                                    $standardFeatures = $plans
                                        ->firstWhere('name', 'Standard')
                                        ->packageModules->pluck('module.name')
                                        ->toArray();
                                    $currentFeatures = $plan->packageModules->pluck('module.name')->toArray();

                                    if ($plan->name == 'Premium') {
                                        $additionalFeatures = array_diff($currentFeatures, $standardFeatures);
                                    } else {
                                        $additionalFeatures = array_diff($currentFeatures, $basicFeatures);
                                    }
                                @endphp

                                @if ($plan->name != 'Basic')
                                    @if ($plan->name == 'Standard' && count($basicFeatures))
                                        <div><span class="check">➜</span>Same Basic plan features</div>
                                    @elseif($plan->name == 'Premium' && count($standardFeatures))
                                        <div><span class="check">➜</span>Same Standard plan features</div>
                                    @endif

                                    @foreach ($additionalFeatures as $feature)
                                        <div><span class="check">➜</span>{{ $feature }}</div>
                                    @endforeach
                                @else
                                    @foreach ($currentFeatures as $feature)
                                        <div><span class="check">➜</span>{{ $feature }}</div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="plan-footer">
                                <div class="plan-controls">
                                    <div class="cycle-toggle">
                                        <div class="monthly active">Monthly</div>
                                        <div class="yearly">Yearly</div>
                                    </div>
                                    <input type="number" min="1" value="1" class="employee-count">
                                </div>

                                <button class="btn subscribe-btn w-100" data-plan-id="{{ $plan->id }}">
                                    Get Started
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Stripe Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Secure Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Card Number</label>
                                    <div id="card-number" class="stripe-input"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expiry</label>
                                        <div id="card-expiry" class="stripe-input"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">CVC</label>
                                        <div id="card-cvc" class="stripe-input"></div>
                                    </div>
                                </div>

                                <div id="card-errors" class="text-danger small mt-2"></div>

                                <button class="btn btn-success w-100 mt-3" id="payNowBtn">Pay Securely</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('admin.users.common.scripts')

    <script>
        $(document).ready(function() {
            let stripe, elements;
            let cardNumber, cardExpiry, cardCvc;
            let clientSecret = null;
            let subscriptionId = null;
            let activeButton = null;

            stripe = Stripe("{{ config('services.stripe.key') }}");
            elements = stripe.elements();

            const style = {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a'
                }
            };

            $('#exampleModal').on('shown.bs.modal', function() {
                if (!cardNumber) {
                    cardNumber = elements.create('cardNumber', {
                        style
                    });
                    cardNumber.mount('#card-number');
                    cardExpiry = elements.create('cardExpiry', {
                        style
                    });
                    cardExpiry.mount('#card-expiry');
                    cardCvc = elements.create('cardCvc', {
                        style
                    });
                    cardCvc.mount('#card-cvc');

                    [cardNumber, cardExpiry, cardCvc].forEach(el => {
                        el.on('change', function(event) {
                            $('#card-errors').text(event.error ? event.error.message : '');
                        });
                    });
                }
            });

            $('.cycle-toggle div').on('click', function() {
                const parent = $(this).closest('.cycle-toggle');
                parent.find('div').removeClass('active');
                $(this).addClass('active');
                const card = $(this).closest('.plan-card');
                updatePrice(card);
            });

            function updatePrice(card) {
                const cycle = card.find('.cycle-toggle .active').hasClass('monthly') ? 'monthly' : 'yearly';
                const employees = parseInt(card.find('.employee-count').val()) || 1;
                const priceEl = card.find('.plan-price');
                const basePrice = cycle === 'monthly' ? parseFloat(priceEl.data('month')) : parseFloat(priceEl.data(
                    'year'));
                const total = basePrice * employees;
                priceEl.text('€' + total.toFixed(2) + '/employee');
                card.find('.plan-cycle').text(cycle === 'monthly' ? '/ Month' : '/ Year');
            }

            $('.employee-count').on('keyup change', function() {
                updatePrice($(this).closest('.plan-card'));
            });

            $('.subscribe-btn').on('click', async function() {
                const btn = $(this);
                if (btn.data('processing')) return;
                btn.data('processing', true);
                activeButton = btn;

                const card = btn.closest('.plan-card');
                const planId = btn.data('plan-id');
                const cycle = card.find('.cycle-toggle .active').hasClass('monthly') ? 'monthly' :
                    'yearly';
                const employees = parseInt(card.find('.employee-count').val()) || 1;

                btn.prop('disabled', true).text('Processing...');
                $('#exampleModal').modal('show');

                try {
                    const res = await fetch("{{ route('admin.stripe.create.subscription') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            plan_id: planId,
                            cycle,
                            total_employees: employees
                        })
                    });
                    const result = await res.json();
                    if (!result.client_secret || !result.subscription_id) throw new Error(
                        "Payment initialization failed");
                    clientSecret = result.client_secret;
                    subscriptionId = result.subscription_id;
                } catch (e) {
                    $('#card-errors').text(e.message);
                    btn.prop('disabled', false).text('Get Started');
                    btn.data('processing', false);
                }
            });

            $('#payNowBtn').on('click', async function() {
                if (!clientSecret || !subscriptionId) {
                    $('#card-errors').text('Payment not initialized.');
                    return;
                }
                $('#card-errors').text('');
                $(this).prop('disabled', true).text('Processing...');

                const payment = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardNumber
                    }
                });

                if (payment.error) {
                    $('#card-errors').text(payment.error.message);
                    $('#payNowBtn').prop('disabled', false).text('Pay Securely');
                    activeButton.prop('disabled', false).text('Get Started');
                    activeButton.data('processing', false);
                    return;
                }

                await fetch("{{ route('admin.stripe.success') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        subscription_id: subscriptionId
                    })
                });

                window.location.href = "{{ route('admin.subscription.thankyou') }}";
            });

        });
    </script>
@endsection

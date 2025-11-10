@php
    $content = content('contact.content');
@endphp
<section class="conact-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="section-header">
                    <span class="section-caption"><i class="fa-solid fa-plane-departure"></i> {{ __("Contact Us") }}</span>
                    <h2 class="section-title mb-2 mt-3">{{ __("We Always Here To Help You") }}</h2>
                    <p>{{ __("Reference site about Lorem Ipsum, giving information on its origins, as well as a random Lipsum generator.") }}</p>
                </div>
            </div>
        </div>

        <div class="row gy-4">
            <div class="col-lg-6">
                <contact-section>
                    <form action="{{ route('contact') }}" method="post" class="php non-editable-area"
                        data-gjs-stylable='false' data-gjs-draggable='false' data-gjs-editable='false'
                        data-gjs-removable='false' data-gjs-propagate='["removable","editable","draggable","stylable"]'>
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6 form-group mt-3 mt-md-0">
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="Your Email" required>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <input type="text" class="form-control" name="subject" id="subject"
                                placeholder="Subject" required>
                        </div>
                        <div class="form-group mt-3">
                            <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary w-100" type="submit">{{ __('Send Message') }}</button>
                        </div>
                    </form>
                </contact-section>
            </div>
            <div class="col-lg-6">
                <div class="row gy-4">
                    <div class="col-lg-12">
                        <div class="contact-item">
                            <h5 class="title">
                                <i class="fas fa-map-marker-alt"></i> 
                                {{ __('Address') }}
                            </h5>
                            <p class="mb-0">{{ __("District, Taipei City 106409, Taiwan united kingdom") }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="contact-item">
                            <h5 class="title">
                                <i class="fas fa-phone-alt"></i>
                                {{ __('Mobile Number') }}
                            </h5>
                            <p class="mb-0">{{ __("+1 112 2333 3399") }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="contact-item">
                            <h5 class="title">
                                <i class="fas fa-envelope"></i>
                                {{ __('Email Address') }}
                            </h5>
                            <p class="mb-0">{{ __("info@example.com") }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="contact-item" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: white; border: none;">
                            <h5 class="title" style="color: white;">
                                <i class="bi bi-whatsapp"></i>
                                {{ __('Contact via WhatsApp') }}
                            </h5>
                            <p class="mb-3" style="color: rgba(255,255,255,0.9);">{{ __("+237 640 387 258") }}</p>
                            <a href="https://wa.me/237640387258?text=Hello,%20I%20would%20like%20to%20consult%20about%20visa%20services"
                               target="_blank"
                               class="btn btn-light btn-sm"
                               style="color: #25D366; font-weight: 600;">
                                <i class="bi bi-whatsapp"></i> {{ __('Chat on WhatsApp') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="map-area">
            <iframe class="map" src="{{ strpos($content->data->map, 'http') === 0 ? $content->data->map : 'https://' . $content->data->map }}" allowfullscreen></iframe>
        </div>
    </div>
</section>

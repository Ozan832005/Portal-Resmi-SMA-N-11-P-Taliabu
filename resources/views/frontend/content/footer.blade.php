<div class="footer-area-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="footer-box">
                    <a href="/" aria-label="{{ __('Beranda SMA Negeri 11 Pulau Taliabu') }}">
                        @if (@$footer->logo == NULL)
                            <img class="img-responsive" src="{{asset('Assets/Frontend/img/logo-footer.png')}}" alt="{{ __('Logo sekolah') }}">
                        @else
                            <img class="img-responsive" src="{{asset('storage/images/logo/' .$footer->logo)}}" alt="{{ __('Logo SMA Negeri 11 Pulau Taliabu') }}">
                        @endif
                    </a>
                    <div class="footer-about">
                        <p>{{@$footer->desc}}</p>
                    </div>
                    <ul class="footer-social">
                        @if (!empty($footer->linkedln))
                            <li><a href="{{ 'https://www.linkedin.com/in/' . $footer->linkedln }}" target="_blank" rel="noopener" aria-label="LinkedIn sekolah"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                        @endif
                        @if (!empty($footer->twitter))
                            <li><a href="{{ 'https://www.twitter.com/' . $footer->twitter }}" target="_blank" rel="noopener" aria-label="Twitter sekolah"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        @endif
                        @if (!empty($footer->facebook))
                            <li><a href="{{ 'https://www.facebook.com/' . $footer->facebook }}" target="_blank" rel="noopener" aria-label="Facebook sekolah"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        @endif
                        @if (!empty($footer->instagram))
                            <li><a href="{{ 'https://www.instagram.com/' . $footer->instagram }}" target="_blank" rel="noopener" aria-label="Instagram sekolah"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="footer-box">
                    <h3>{{ __('Informasi') }}</h3>
                    <ul class="corporate-address">
                        @if (@$footer->telp)
                            <li><i class="fa fa-phone" aria-hidden="true"></i><a href="tel:{{@$footer->telp}}">{{@$footer->telp}}</a></li>
                        @endif
                        @if (@$footer->email)
                            <li><i class="fa fa-envelope-o" aria-hidden="true"></i><a href="mailto:{{@$footer->email}}">{{@$footer->email}}</a></li>
                        @endif
                    </ul>
                    <div class="newsletter-area">
                        <h3>{{ __('Ingin mendapat berita terupdate ?') }}</h3>
                        <div class="input-group stylish-input-group">
                            <input type="email" placeholder="{{ __('Masukan email kamu disini') }}" class="form-control" aria-label="{{ __('Email untuk berlangganan berita') }}">
                            <span class="input-group-addon">
                                <button type="submit" aria-label="{{ __('Kirim email') }}">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="footer-box">
                    <h3>{{ __('Galeri') }}</h3>
                    @php
                        $galeriFooter = $footerGalleries ?? collect();
                    @endphp

                    @if ($galeriFooter->isNotEmpty())
                        <ul class="km-footer-gallery">
                            @foreach ($galeriFooter->take(6) as $foto)
                                <li>
                                    <a href="{{ asset('storage/images/footer/' . $foto->image) }}" target="_blank" rel="noopener" title="{{ $foto->caption }}">
                                        <img src="{{ asset('storage/images/footer/' . $foto->image) }}" alt="{{ $foto->caption ?: __('Foto kegiatan sekolah') }}" loading="lazy">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <ul class="km-footer-gallery km-footer-gallery--empty">
                            @for ($i = 1; $i <= 6; $i++)
                                <li aria-hidden="true"><i class="fa fa-camera"></i></li>
                            @endfor
                        </ul>
                        <p class="km-footer-hint">{{ __('Foto bisa diganti dari panel admin › Halaman situs › Galeri footer.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer-area-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <p>&copy; {{date('Y')}} SMA Negeri 11 Pulau Taliabu. {{ __('Seluruh hak cipta dilindungi.') }}</p>
            </div>
        </div>
    </div>
</div>

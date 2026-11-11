    <div class="container">
        <h1 class="about-title">Kurikulum</h1>
        <p class="about-sub-title">Kurikulum pembelajaran SMA Negeri 11 Pulau Taliabu</p>
    </div>
    <div class="container">
        <div class="row">
            @foreach ($curriculum as $item)
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 wow fadeIn" data-wow-duration="2s" data-wow-delay=".{{ ($loop->index * 3) + 1 }}s">
                    <div class="service-box2">
                        <div class="service-box-icon">
                            <a href="{{ route('kurikulum') }}"><i class="fa fa-book" aria-hidden="true"></i></a>
                        </div>
                        <h3><a href="{{ route('kurikulum') }}">{{ $item->title }}</a></h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
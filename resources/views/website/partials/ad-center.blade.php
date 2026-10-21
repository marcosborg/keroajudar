@php($needsAdsense = $homeAdSlots->contains(fn($slot) => $slot->type === 'adsense'))
<section class="advertising-center py-5" aria-labelledby="advertising-center-title">
    <div class="container">
        <div class="advertising-center-heading">
            <div>
                <span class="advertising-kicker">Parceiros que ajudam a ajudar</span>
                <h2 id="advertising-center-title">Centro de Publicidade</h2>
            </div>
            <a href="{{ route('website.contactos') }}" class="btn btn-outline-success">Quero anunciar</a>
        </div>

        <div class="advertising-filters" aria-label="Filtros de publicidade">
            <div class="advertising-category-filter" role="group" aria-label="Categoria">
                <a class="ad-filter-pill {{ $adFilters['category'] === 'all' ? 'active' : '' }}" href="{{ route('website.home', array_filter(['ad_region' => $adFilters['region'] === 'all' ? null : $adFilters['region']])) }}#advertising-center-title">Todas</a>
                @foreach(config('advertising.categories') as $code => $label)
                    <a class="ad-filter-pill {{ $adFilters['category'] === $code ? 'active' : '' }}" href="{{ route('website.home', array_filter(['ad_category' => $code, 'ad_region' => $adFilters['region'] === 'all' ? null : $adFilters['region']])) }}#advertising-center-title">{{ $label }}</a>
                @endforeach
            </div>
            <form method="GET" action="{{ route('website.home') }}" class="advertising-region-filter">
                @if($adFilters['category'] !== 'all')
                    <input type="hidden" name="ad_category" value="{{ $adFilters['category'] }}">
                @endif
                <label for="ad-region">Região</label>
                <select name="ad_region" id="ad-region" onchange="this.form.submit()">
                    <option value="">Todo o país</option>
                    @foreach(config('advertising.regions') as $code => $label)
                        <option value="{{ $code }}" {{ $adFilters['region'] === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <noscript><button class="btn btn-sm btn-success" type="submit">Aplicar</button></noscript>
            </form>
        </div>

        <div class="advertising-grid">
            @foreach($homeAdSlots->groupBy(fn($slot) => $slot->position->row) as $row => $slots)
                <div class="advertising-row advertising-row-{{ $row }}">
                    @foreach($slots as $slot)
                        <article class="advertising-slot advertising-slot-{{ $slot->position->code }}" style="--desktop-ratio: {{ $slot->position->desktop_width }} / {{ $slot->position->desktop_height }}; --slot-width: {{ $slot->position->desktop_width }}px;" aria-label="{{ $slot->position->name }}">
                            @if($slot->type === 'campaign')
                                <a href="{{ $slot->click_url }}" class="direct-ad" target="_blank" rel="noopener sponsored" data-impression-token="{{ $slot->impression_token }}">
                                    <picture>
                                        <source media="(max-width: 767px)" srcset="{{ $slot->campaign->mobile_creative->url }}">
                                        <img src="{{ $slot->campaign->desktop_creative->url }}" alt="{{ $slot->campaign->advertiser_name }} — {{ $slot->campaign->title }}" width="{{ $slot->position->desktop_width }}" height="{{ $slot->position->desktop_height }}" loading="lazy">
                                    </picture>
                                    <span class="ad-disclosure">Publicidade</span>
                                </a>
                            @elseif($slot->type === 'adsense')
                                <div class="adsense-ad" aria-label="Publicidade Google">
                                    <ins class="adsbygoogle" style="display:block" data-ad-client="{{ config('advertising.adsense.client') }}" data-ad-slot="{{ $slot->adsense_slot }}" data-ad-format="auto" data-full-width-responsive="true"></ins>
                                </div>
                            @else
                                <a href="{{ route('website.contactos') }}" class="available-ad">
                                    <i class="bi bi-megaphone" aria-hidden="true"></i>
                                    <strong>Anuncie aqui</strong>
                                    <span>{{ $slot->position->desktopDimensions() }}</span>
                                </a>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
@if($needsAdsense)
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ urlencode(config('advertising.adsense.client')) }}" crossorigin="anonymous"></script>
<script>
document.querySelectorAll('.adsbygoogle').forEach(function () {
    (window.adsbygoogle = window.adsbygoogle || []).push({});
});
</script>
@endif
<script>
(function () {
    const ads = document.querySelectorAll('[data-impression-token]');
    if (!ads.length || !('IntersectionObserver' in window)) return;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const timers = new WeakMap();
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting && entry.intersectionRatio >= 0.5) {
                if (timers.has(entry.target)) return;
                timers.set(entry.target, window.setTimeout(function () {
                    fetch(@json(route('advertising.impressions')), {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf},
                        body: JSON.stringify({tokens: [entry.target.dataset.impressionToken]})
                    }).catch(function () {});
                    observer.unobserve(entry.target);
                    timers.delete(entry.target);
                }, 1000));
            } else if (timers.has(entry.target)) {
                window.clearTimeout(timers.get(entry.target));
                timers.delete(entry.target);
            }
        });
    }, {threshold: [0.5]});
    ads.forEach(function (ad) { observer.observe(ad); });
})();
</script>
@endpush

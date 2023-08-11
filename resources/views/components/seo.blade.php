<meta name="robots" content="Index,Follow">
@php
    $seoDescription = config('app.seo_description');
    $seoAuthor = config('app.seo_author');
    $seoKeywords = config('app.seo_keywords');
    $seoSubject = config('app.seo_subject');
    $seoImage = config('app.seo_image');
@endphp
@if ($seoDescription)
    <meta name="description" content="{{ $seoDescription }}"/>
@endif
@if ($seoAuthor)
    <meta name="author" content="{{ $seoAuthor }}" />
@endif
@if ($seoKeywords)
    <meta name="keywords" content="{{ $seoKeywords }}">
@endif
@if ($seoSubject)
    <meta name="subject" content="{{ $seoSubject }}">
@endif
<meta name="twitter:card" content="summary">
@if ($seoDescription)
    <meta name="twitter:description" content="{{ $seoDescription }}">
@endif
<meta name="twitter:image" content="{{ $seoImage? $seoImage : asset('storage/images/favicon.png') }}">
<meta name="twitter:image:alt" content="{{ $title ?? config('app.title') }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $seoImage? $seoImage : asset('storage/images/favicon.png') }}">
<meta property="og:image:width" content="94">
<meta property="og:image:height" content="94">
<meta property="og:title" content="{{ $title ?? config('app.title') }}">
@if ($seoDescription)
    <meta property="og:description" content="{{ $seoDescription }}">
@endif
<meta property="og:locale" content="es_ES">
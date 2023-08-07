<meta name="robots" content="Index,Follow">
@php
    $seoDescription = config('app.seo_description');
    $seoAuthor = config('app.seo_author');
    $seoKeywords = config('app.seo_keywords');
    $seoSubject = config('app.seo_subject');
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
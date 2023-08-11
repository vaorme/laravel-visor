@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('storage/images/logo-site.png') }}" class="logo" alt="{{ $title ?? config('app.title') }}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>

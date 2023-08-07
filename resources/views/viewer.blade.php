<x-viewer-layout>
    <x-slot:title>{{ $currentChapter->name }}</x-slot>
    @php
        $paginado = request()->route('reader_type');
    @endphp
    <div id="viewer">
        <section class="view__options">
            <div class="view__col view__left">
                <div class="view__title">
                    <a href="{{ $manga->url() }}" class="view__back">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.6668 6.16671H3.52516L8.1835 1.50837L7.00016 0.333374L0.333496 7.00004L7.00016 13.6667L8.17516 12.4917L3.52516 7.83337H13.6668V6.16671Z" fill="white"/>
                        </svg>
                    </a>
                    <h2>{{ $manga->name }}: {{ $currentChapter->name }}</h2>
                </div>
            </div>
            <div class="view__col view__center">
                <div class="view__select chapter__list">
                    <select id="slct_chapter_list" placeholder="Capítulos..." autocomplete="off">
                        <option value="">Capitulos...</option>
                        @foreach ($chapters as $chapter)
                            <option value="{{ $chapter->slug }}" {{ ($chapter_slug == $chapter->slug)? 'selected' : null }} data-url="{{ route('chapter_viewer.index', [
                                'manga_slug' => $manga->slug,
                                'chapter_slug' => $chapter->slug
                            ]) }}">{{ Str::limit($chapter->name, 18); }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($currentChapter->type == "manga")
                    <div class="view__select chapter__reader_type">
                        <select id="slct_reader_type" placeholder="Tipo de lector" autocomplete="off">
                            <option value="0">Tipo de lector</option>
                            <option value="1" data-url="{{ route('chapter_viewer.index', [
                                'manga_slug' => $manga->slug,
                                'chapter_slug' => $currentChapter->slug
                            ]) }}" {{ (!$paginado)? "selected" : null }}>Cascada</option>
                            <option value="2" data-url="{{ route('chapter_viewer.index', [
                                'manga_slug' => $manga->slug,
                                'chapter_slug' => $currentChapter->slug,
                                'reader_type' => "paginado"
                            ]) }}" {{ ($paginado)? "selected" : null }}>Paginado</option>
                        </select>
                    </div>
                @endif
                <div class="view__select chapter__reader_size">
                    <select id="slct_reader_size" placeholder="Tamaño" autocomplete="off">
                        <option value="0">Tamaño</option>
                        <option value="1">Ancho</option>
                        <option value="2" selected>Ajustado</option>
                    </select>
                </div>
                @if ($currentChapter->type == "novel")
                    <div class="view__select chapter__font_size">
                        <select id="slct_font_size" placeholder="Tamaño de letra" autocomplete="off">
                            <option value="0">Tamaño de letra</option>
                            <option value="14" >14px</option>
                            <option value="16" selected>16px</option>
                            <option value="18">18px</option>
                            <option value="20">20px</option>
                            <option value="22">22px</option>
                            <option value="24">24px</option>
                            <option value="26">26px</option>
                        </select>
                    </div>
                @endif
            </div>
            <div class="view__col view__right">
                <div class="view__buttons">
                    @if (isset($prev_chapter->slug))
                        <a href="{{ route('chapter_viewer.index', [
                            'manga_slug' => $manga->slug,
                            'chapter_slug' => $prev_chapter->slug
                        ]) }}">Anterior</a>
                    @endif
                    @if (isset($next_chapter->slug))
                        <a href="{{ route('chapter_viewer.index', [
                            'manga_slug' => $manga->slug,
                            'chapter_slug' => $next_chapter->slug
                        ]) }}">Siguiente</a>
                    @endif
                </div>
            </div>
        </section>
        @php
            $ad_9 = config('app.ads_9');
        @endphp
        @if ($ad_9)
            <div class="vealo">
                {!! $ad_9 !!}
            </div>
        @endif
        <section class="view__reader">
            @if ($currentChapter->type == "novel")
                <div class="view__colors">
                    <div class="color__item">
                        <div class="color__field bg__field">
                            <label for="choose_background">Cambiar fondo</label>
                            <input type="text" class="coloris input_bg_color" id="choose_background" value="rgb(255, 0, 0)">
                        </div>
                    </div>
                    <div class="color__item">
                        <div class="color__field text__field">
                            <label for="choose_color">Cambiar color</label>
                            <input type="text" class="coloris input_font_color" id="choose_color" value="rgb(255, 0, 0)">
                        </div>
                    </div>
                </div>
            @endif
            @if ($currentChapter->type == "manga")
                @if (isset($paginado))
                    <div class="view__paged">
                        @if (isset($prev_paged))
                            <a href="{{ route('chapter_viewer.index', [
                                'manga_slug' => $manga->slug,
                                'chapter_slug' => $currentChapter->slug,
                                'reader_type' => "paginado",
                                'current_page' => $prev_paged
                            ]) }}" class="paged__item paged__prev paged__link">Anterior</a>
                        @else
                            @if (isset($next_paged))
                                <button class="paged__item paged__disabled">Anterior</button>
                            @endif
                        @endif
                        @if (isset($total_pages))
                            <div class="view__select chapter__paged_list">
                                <select id="slct_paged_list" placeholder="Tamaño" autocomplete="off">
                                    <option value="0">Tamaño</option>
                                    @for ($i = 1; $i <= $total_pages; $i++)
                                        <option value="{{ $i }}" data-url="{{ route('chapter_viewer.index', [
                                            'manga_slug' => $manga->slug,
                                            'chapter_slug' => $currentChapter->slug,
                                            'reader_type' => "paginado",
                                            'current_page' => $i
                                        ]) }}" {{ (isset($current_page) && $current_page == $i)? "selected": null }}>{{ $i }}</option>
                                    @endfor  
                                </select>
                            </div>
                        @endif
                        @if (isset($next_paged))
                            <a href="{{ route('chapter_viewer.index', [
                                'manga_slug' => $manga->slug,
                                'chapter_slug' => $currentChapter->slug,
                                'reader_type' => "paginado",
                                'current_page' => $next_paged
                            ]) }}" class="paged__item paged__next paged__link">Siguiente</a>
                        @else
                            @if (isset($prev_paged))
                                <button class="paged__item paged__disabled">Siguiente</button>
                            @endif
                        @endif
                    </div>
                @endif
            @endif
            <div class="view__content{{ ($currentChapter->type == "novel")? ' view__novel' : null}}">
                @switch($currentChapter->type)
                    @case("novel")
                        {!! $content !!}
                    @break
                    @case("manga")
                        @if(isset($images) && !empty($images))
                            @foreach ($images as $image)
                                <div class="reader__item">
                                    <img src="{{ Storage::disk($currentChapter->disk)->url($image) }}">
                                </div>
                            @endforeach
                        @endif    
                    @break
                    @default
                        
                @endswitch
            </div>
            @if ($currentChapter->type == "novel")
                <div class="view__colors">
                    <div class="color__item">
                        <div class="color__field bg__field">
                            <label for="choose_background_bottom">Cambiar fondo</label>
                            <input type="text" class="coloris input_bg_color" id="choose_background_bottom" value="rgb(255, 0, 0)">
                        </div>
                    </div>
                    <div class="color__item">
                        <div class="color__field text__field">
                            <label for="choose_color_bottom">Cambiar color</label>
                            <input type="text" class="coloris input_font_color" id="choose_color_bottom" value="rgb(255, 0, 0)">
                        </div>
                    </div>
                </div>
            @endif
            @if ($currentChapter->type == "manga")
                @if (isset($paginado))
                    <div class="view__paged">
                        @if (isset($prev_paged))
                            <a href="{{ route('chapter_viewer.index', [
                                'manga_slug' => $manga->slug,
                                'chapter_slug' => $currentChapter->slug,
                                'reader_type' => "paginado",
                                'current_page' => $prev_paged
                            ]) }}" class="paged__item paged__prev paged__link">Anterior</a>
                        @else
                            @if (isset($next_paged))
                                <button class="paged__item paged__disabled">Anterior</button>
                            @endif
                        @endif
                        @if (isset($total_pages))
                            <div class="view__select chapter__paged_list">
                                <select id="slct_paged_list_bottom" placeholder="Tamaño" autocomplete="off">
                                    <option value="0">Tamaño</option>
                                    @for ($i = 1; $i <= $total_pages; $i++)
                                        <option value="{{ $i }}" data-url="{{ route('chapter_viewer.index', [
                                            'manga_slug' => $manga->slug,
                                            'chapter_slug' => $currentChapter->slug,
                                            'reader_type' => "paginado",
                                            'current_page' => $i
                                        ]) }}" {{ (isset($current_page) && $current_page == $i)? "selected": null }}>{{ $i }}</option>
                                    @endfor  
                                </select>
                            </div>
                        @endif
                        @if (isset($next_paged))
                            <a href="{{ route('chapter_viewer.index', [
                                'manga_slug' => $manga->slug,
                                'chapter_slug' => $currentChapter->slug,
                                'reader_type' => "paginado",
                                'current_page' => $next_paged
                            ]) }}" class="paged__item paged__next paged__link">Siguiente</a>
                        @else
                            @if (isset($prev_paged))
                                <button class="paged__item paged__disabled">Siguiente</button>
                            @endif
                        @endif
                    </div>
                @endif
            @endif
        </section>
        @php
            $ad_10 = config('app.ads_10');
        @endphp
        @if ($ad_10)
            <div class="vealo">
                {!! $ad_10 !!}
            </div>
        @endif
        <section class="view__options bottom">
            <div class="view__col view__left">
                <div class="view__title">
                    <a href="{{ $manga->url() }}" class="view__back">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.6668 6.16671H3.52516L8.1835 1.50837L7.00016 0.333374L0.333496 7.00004L7.00016 13.6667L8.17516 12.4917L3.52516 7.83337H13.6668V6.16671Z" fill="white"/>
                        </svg>
                    </a>
                    <h2>{{ $manga->name }}: {{ $currentChapter->name }}</h2>
                </div>
            </div>
            <div class="view__col view__center">
                <div class="view__select chapter__list">
                    <select id="slct_chapter_list_bottom" placeholder="Capítulos..." autocomplete="off">
                        <option value="">Capitulos...</option>
                        @foreach ($chapters as $chapter)
                            <option value="{{ $chapter->slug }}" {{ ($chapter_slug == $chapter->slug)? 'selected' : null }} data-url="{{ route('chapter_viewer.index', [
                                'manga_slug' => $manga->slug,
                                'chapter_slug' => $chapter->slug
                            ]) }}">{{ Str::limit($chapter->name, 18); }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($currentChapter->type == "manga")
                    <div class="view__select chapter__reader_type">
                        <select id="slct_reader_type_bottom" placeholder="Tipo de lector" autocomplete="off">
                            <option value="0">Tipo de lector</option>
                            <option value="1" data-url="{{ route('chapter_viewer.index', [
                                'manga_slug' => $manga->slug,
                                'chapter_slug' => $currentChapter->slug
                            ]) }}" {{ (!$paginado)? "selected" : null }}>Cascada</option>
                            <option value="2" data-url="{{ route('chapter_viewer.index', [
                                'manga_slug' => $manga->slug,
                                'chapter_slug' => $currentChapter->slug,
                                'reader_type' => "paginado"
                            ]) }}" {{ ($paginado)? "selected" : null }}>Paginado</option>
                        </select>
                    </div>
                @endif
                <div class="view__select chapter__reader_size">
                    <select id="slct_reader_size_bottom" placeholder="Tamaño" autocomplete="off">
                        <option value="0">Tamaño</option>
                        <option value="1">Ancho</option>
                        <option value="2" selected>Ajustado</option>
                    </select>
                </div>
                @if ($currentChapter->type == "novel")
                    <div class="view__select chapter__font_size">
                        <select id="slct_font_size_bottom" placeholder="Tamaño de letra" autocomplete="off">
                            <option value="0">Tamaño de letra</option>
                            <option value="14" >14px</option>
                            <option value="16" selected>16px</option>
                            <option value="18">18px</option>
                            <option value="20">20px</option>
                            <option value="22">22px</option>
                            <option value="24">24px</option>
                            <option value="26">26px</option>
                        </select>
                    </div>
                @endif
            </div>
            <div class="view__col view__right">
                <div class="view__buttons">
                    @if (isset($prev_chapter->slug))
                        <a href="{{ route('chapter_viewer.index', [
                            'manga_slug' => $manga->slug,
                            'chapter_slug' => $prev_chapter->slug
                        ]) }}">Anterior</a>
                    @endif
                    @if (isset($next_chapter->slug))
                        <a href="{{ route('chapter_viewer.index', [
                            'manga_slug' => $manga->slug,
                            'chapter_slug' => $next_chapter->slug
                        ]) }}">Siguiente</a>
                    @endif
                </div>
            </div>
        </section>
    </div>
    @if ($currentChapter->type == "novel")
        <script>
            const inputBgColors = document.querySelectorAll('.input_bg_color');
            Coloris({
                el: '.coloris',
                themeMode: 'dark',
                swatches: [
                    '#141414'
                ],
                onChange: (color, input) =>{
                    const fieldColors = document.querySelectorAll('.view__colors .bg__field .clr-field');
                    fieldColors.forEach(item => {
                        item.style.color = color;
                    });
                    inputBgColors.forEach(item => {
                        item.value = color;
                        item.style.color = color;
                    });

                    const bdy = document.querySelector('#viewer .view__content');
                    localStorage.setItem('content_body_color', color);
                    bdy.style.backgroundColor = color;
                }
            });

            const inputColors = document.querySelectorAll('.input_font_color');
            Coloris.setInstance('.input_font_color', {
                // Focus the color value input when the color picker dialog is opened.
                focusInput: true,

                // Select and focus the color value input when the color picker dialog is opened.
                selectInput: true,
                onChange: (color, input) =>{
                    const fieldColors = document.querySelectorAll('.view__colors .text__field .clr-field');
                    fieldColors.forEach(item => {
                        item.style.color = color;
                    });
                    inputColors.forEach(item => {
                        item.value = color;
                        item.style.color = color;
                    });
                    const contentColor = document.querySelector('#viewer .view__content');
                    localStorage.setItem('content_font_color', color);
                    contentColor.style.color = color;
                }
            });
        </script>
    @endif
</x-viewer-layout>
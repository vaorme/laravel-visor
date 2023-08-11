<x-app-layout>
    <aside class="filter">
        <form action="" class="filter__form">
            <div class="filter__widget">
                <h2 class="widget__title">Filtros</h2>
                <div class="widget__content">
                    @if ($types->isNotEmpty())
                        <div class="widget__item">
                            <select name="filter_type" id="select-type">
                                <option value="" selected>Tipo</option>
                                @foreach ($types as $item)
                                    <option value="{{ $item->slug }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($demographics->isNotEmpty())
                        <div class="widget__item">
                            <select name="filter_demography" id="select-demography">
                                <option value="" selected>Demografia</option>
                                @foreach ($demographics as $item)
                                    <option value="{{ $item->slug }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($bookStatus->isNotEmpty())
                        <div class="widget__item">
                            <select name="filter_bookstatus" id="select-bookstatus">
                                <option value="" selected>Estado</option>
                                @foreach ($bookStatus as $item)
                                    <option value="{{ $item->slug }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <button class="filter__button" type="submit">Filtrar</button>
                </div>
            </div>
            @if ($categories->isNotEmpty())
                <div class="filter__widget filter__categories">
                    <h2 class="widget__title">Categorías</h2>
                    <div class="widget__content">
                        <div class="widget__scl">
                            @foreach ($categories as $key => $item)
                                <label for="cck-{{ $key }}">
                                    <input type="checkbox" name="filter_categories[]" value="{{ $item->slug }}" id="cck-{{ $key }}">
                                    <div class="checkbox">
                                        <div class="check__field"></div>
                                        <div class="check__name">{{ $item->name }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="filter__widget filter__categories">
                    <h2 class="widget__title">Excluir categorías</h2>
                    <div class="widget__content">
                        <div class="widget__scl">
                            @foreach ($categories as $key => $item)
                                <label for="cck-{{ $key }}">
                                    <input type="checkbox" name="filter_excategories[]" value="{{ $item->slug }}" id="cck-{{ $key }}">
                                    <div class="checkbox">
                                        <div class="check__field"></div>
                                        <div class="check__name">{{ $item->name }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </aside>
    <section class="library">
        <div class="manga">
            @if ($list->isNotEmpty())
                <div class="manga__list">
                    @foreach ($list as $item)
                        <x-manga-loop-item :item="$item" />
                    @endforeach
                </div>
                {{ $list->links() }}
            @else
                <div class="empty">No hay elementos para mostrar</div>
            @endif
        </div>
    </section>
</x-app-layout>
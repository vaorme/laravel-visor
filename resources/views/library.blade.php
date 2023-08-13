<x-app-layout>
    <aside class="filter">
        <form action="" class="filter__form">
            <div class="filter__widget">
                <h2 class="widget__title">Filtros</h2>
                <div class="widget__content">
                    @if ($types->isNotEmpty())
                        <div class="widget__item">
                            <select name="type" id="select-type">
                                <option value="" selected>Tipo</option>
                                @foreach ($types as $item)
                                    <option value="{{ $item->slug }}" {{ (request()->type == $item->slug)? 'selected' : null }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($demographics->isNotEmpty())
                        <div class="widget__item">
                            <select name="demography" id="select-demography">
                                <option value="" selected>Demografia</option>
                                @foreach ($demographics as $item)
                                    <option value="{{ $item->slug }}" {{ (request()->demography == $item->slug)? 'selected' : null }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($bookStatus->isNotEmpty())
                        <div class="widget__item">
                            <select name="bookstatus" id="select-bookstatus">
                                <option value="" selected>Estado</option>
                                @foreach ($bookStatus as $item)
                                    <option value="{{ $item->slug }}" {{ (request()->bookstatus == $item->slug)? 'selected' : null }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($categories->isNotEmpty())
                        <div class="widget__item select__categories">
                            <select name="categories[]" id="select-categories" multiple>
                                <option value="" selected>Categorías</option>
                                @foreach ($categories as $key => $item)
                                    @php
                                        $in = explode(',', request()->categories);
                                    @endphp
                                    <option value="{{ $item->slug }}" value="{{ $item->slug }}" {{ (isset($in) && in_array($item->slug, $in))? 'selected' : null }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="widget__item select__categories">
                            <select name="excategories[]" id="select-excategories" multiple>
                                <option value="" selected>Excluir categorías</option>
                                @foreach ($categories as $key => $item)
                                    @php
                                        $notIn = explode(',', request()->excategories);
                                    @endphp
                                    <option value="{{ $item->slug }}" value="{{ $item->slug }}" {{ (isset($notIn) && in_array($item->slug, $notIn))? 'selected' : null }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <button class="filter__button" type="submit">Filtrar</button>
                </div>
            </div>
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
                {{ $list->links('vendor.pagination.default') }}
            @else
                <div class="empty">No hay elementos para mostrar</div>
            @endif
        </div>
    </section>
</x-app-layout>
<div class="bar flex flex-col justify-center">
    <div class="title @if ($backTo != '') flex items-center @endif">
        @if ($backTo != '')
            <div class="back">
                <a href="{{ $backTo }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <line x1="5" y1="12" x2="11" y2="18" />
                        <line x1="5" y1="12" x2="11" y2="6" />
                    </svg>
                </a>
            </div>
        @endif
        <h4 class="name text-white text-2xl font-bold">{{ $title }}</h4>
    </div>
    @if ($slot != '' || $buttonTo != '')
        <div class="actions flex justify-between items-center">
            @if ($slot != '')
                <div class="filters">
                    {{ $slot }}
                </div>
            @endif
            @if ($buttonTo != '')
                <div class="buttons">
                    <a href="{{ $buttonTo }}" class="botn flex items-center text-white bg-vo-green py-3 px-4 rounded-lg font-medium text-center hover:bg-vo-green-over transition-colors">
                        <div class="icon mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </div>
                        {{ $buttonText }}
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
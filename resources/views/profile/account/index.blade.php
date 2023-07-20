<x-app-layout>
    <div class="main__wrap account">
        <div class="account__box">
            <div class="account__goback">
                <a href="{{ route('profile.index', ['username' => Auth::user()->username]) }}" class="goback__link">
                    <div class="goback__icon">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.6668 6.16671H3.52516L8.1835 1.50837L7.00016 0.333374L0.333496 7.00004L7.00016 13.6667L8.17516 12.4917L3.52516 7.83337H13.6668V6.16671Z" fill="white"></path>
                        </svg>
                    </div>
                    <span class="goback__name">Volver al perfil</span>
                </a>
            </div>
            <form action="" class="account__form">
                <div class="form__col">
                    <div class="form__item">
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="{{ old('name', $user->profile->name) }}">
                    </div>
                    <div class="form__item">
                        <label>País</label>
                        @php
                            $country = $user->profile->getCountry();
                        @endphp
                        <select name="country">
                            <option value="" selected>Seleccionar país</option>
                            @foreach ($countries as $item)
                                <option value="{{ $item->id }}" {{ ($country && $country->id == $item->id)? "selected" : null }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form__group">
                        <h2 class="group__title">Cambiar contraseña</h2>
                        <div class="form__item">
                            <label>Contraseña actual</label>
                            <input type="password" name="current_password">
                        </div>
                        <div class="form__item">
                            <label>Nueva contraseña</label>
                            <input type="password" name="password">
                            <div class="password__validation">
                                <h3>La contraseña debe contener lo siguiente:</h3>
                                <p id="letter" class="invalid">Una letra <strong>minúscula</strong></p>
                                <p id="capital" class="invalid">Una letra en <strong>mayuscula</strong></p>
                                <p id="number" class="invalid">Un <b>número</b></p>
                                <p id="length" class="invalid">Minimo <b>8 caracteres</b></p>
                            </div>
                        </div>
                        <div class="form__item">
                            <label>Confirmar nueva contraseña</label>
                            <input type="password" name="password_confirmation">
                        </div>
                    </div>
                </div>
                <div class="form__col">
                    <div class="form__item form__avatares">
                        <label>Avatar</label>
                        <div class="list grid grid-cols-8 gap-x-8 gap-y-4">
                            @foreach ($avatares as $key => $item)
                                <div class="item">
                                    <label for="av-{{ $key }}">
                                        <input type="radio" name="default_avatar" id="av-{{ $key }}" value="{{ $item }}">
                                        <div class="avatar">
                                            <img src="{{ asset("storage/".$item) }}" alt="avatar-{{ $key }}">
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                            <div class="item" id="userAvatar">
                                <div id="choose">
                                    <div class="preview">
                                        <img src="" alt="" class="image-preview">
                                    </div>
                                    <div class="add">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </div>
                                </div>
                                <input type="file" name="choose_avatar" accept="image/jpg,image/png,image/jpeg,image/gif" hidden>
                            </div>
                        </div>
                    </div>
                    <div class="form__item form__cover">
                        <label>URL Portada</label>
                        <input type="text" name="cover" data-validated="false">
                        <div class="preview">
                            <img src="" alt="cover-preview">
                        </div>
                    </div>
                    <div class="form__item">
                        <label>Mensaje</label>
                        <textarea name="message" cols="30" rows="4"></textarea>
                    </div>
                    <div class="form__profile_public">
                        <label>Perfil publico</label>
                        <div class="rdos grid grid-cols-2 gap-4">
                            <label for="op-1">
                                <input type="radio" id="op-1" name="public_profile" value="1" checked>
                                <div class="rd-input">
                                    <div class="rd-option">
                                        Si
                                    </div>
                                </div>
                            </label>
                            <label for="op-2">
                                <input type="radio" id="op-2" name="public_profile" value="0">
                                <div class="rd-input">
                                    <div class="rd-option">
                                        No
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="form__item form__redes">
                        <h4>Redes</h4>
                        <div class="list">
                            <div class="item add">
                                <div class="icon">
                                    <svg width="28px" height="28px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="8" stroke="#fff" stroke-width="2"/>
                                        <path d="M18.572 7.20637C17.8483 8.05353 16.8869 8.74862 15.7672 9.23422C14.6475 9.71983 13.4017 9.98201 12.1326 9.99911C10.8636 10.0162 9.60778 9.78773 8.4689 9.33256C7.33002 8.87739 6.34077 8.20858 5.58288 7.38139" stroke="#fff" stroke-width="2"/>
                                        <path d="M18.572 16.7936C17.8483 15.9465 16.8869 15.2514 15.7672 14.7658C14.6475 14.2802 13.4017 14.018 12.1326 14.0009C10.8636 13.9838 9.60778 14.2123 8.4689 14.6674C7.33002 15.1226 6.34077 15.7914 5.58288 16.6186" stroke="#fff" stroke-width="2"/>
                                        <path d="M12 4V20" stroke="#fff" stroke-width="2"/>
                                    </svg>
                                </div>
                                <input type="text" name="new_red">
                                <div class="action">
                                    <button class="new">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @include('profile.account.partials.update-profile-information-form')
            @include('profile.account.partials.update-password-form')
            @include('profile.account.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>

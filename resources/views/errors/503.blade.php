@extends('errors.layout')

@section('title', 'Service Unavailable')

@section('content')
<div class="page-content error-cover">

    @php $error_code = 403 @endphp

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="card mb-0 border-none box-shadowed">
                        <div class="card-body text-center">
                            <!-- 503 -->
                            <svg width="21" height="10" viewBox="0 0 21 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <mask id="path-1-inside-1_2_23" fill="white">
                                    <path d="M2.17383 5.11523L0.826172 4.79297L1.3125 0.46875H6.10547V1.83398H2.70117L2.49023 3.72656C2.60352 3.66016 2.77539 3.58984 3.00586 3.51562C3.23633 3.4375 3.49414 3.39844 3.7793 3.39844C4.19336 3.39844 4.56055 3.46289 4.88086 3.5918C5.20117 3.7207 5.47266 3.9082 5.69531 4.1543C5.92188 4.40039 6.09375 4.70117 6.21094 5.05664C6.32812 5.41211 6.38672 5.81445 6.38672 6.26367C6.38672 6.64258 6.32812 7.00391 6.21094 7.34766C6.09375 7.6875 5.91602 7.99219 5.67773 8.26172C5.43945 8.52734 5.14062 8.73633 4.78125 8.88867C4.42188 9.04102 3.99609 9.11719 3.50391 9.11719C3.13672 9.11719 2.78125 9.0625 2.4375 8.95312C2.09766 8.84375 1.79102 8.68164 1.51758 8.4668C1.24805 8.25195 1.03125 7.99219 0.867188 7.6875C0.707031 7.37891 0.623047 7.02734 0.615234 6.63281H2.29102C2.31445 6.875 2.37695 7.08398 2.47852 7.25977C2.58398 7.43164 2.72266 7.56445 2.89453 7.6582C3.06641 7.75195 3.26758 7.79883 3.49805 7.79883C3.71289 7.79883 3.89648 7.75781 4.04883 7.67578C4.20117 7.59375 4.32422 7.48047 4.41797 7.33594C4.51172 7.1875 4.58008 7.01562 4.62305 6.82031C4.66992 6.62109 4.69336 6.40625 4.69336 6.17578C4.69336 5.94531 4.66602 5.73633 4.61133 5.54883C4.55664 5.36133 4.47266 5.19922 4.35938 5.0625C4.24609 4.92578 4.10156 4.82031 3.92578 4.74609C3.75391 4.67188 3.55273 4.63477 3.32227 4.63477C3.00977 4.63477 2.76758 4.68359 2.5957 4.78125C2.42773 4.87891 2.28711 4.99023 2.17383 5.11523Z" />
                                    <path d="M13.2188 3.98438V5.46094C13.2188 6.10156 13.1504 6.6543 13.0137 7.11914C12.877 7.58008 12.6797 7.95898 12.4219 8.25586C12.168 8.54883 11.8652 8.76562 11.5137 8.90625C11.1621 9.04688 10.7715 9.11719 10.3418 9.11719C9.99805 9.11719 9.67773 9.07422 9.38086 8.98828C9.08398 8.89844 8.81641 8.75977 8.57812 8.57227C8.34375 8.38477 8.14062 8.14844 7.96875 7.86328C7.80078 7.57422 7.67188 7.23047 7.58203 6.83203C7.49219 6.43359 7.44727 5.97656 7.44727 5.46094V3.98438C7.44727 3.34375 7.51562 2.79492 7.65234 2.33789C7.79297 1.87695 7.99023 1.5 8.24414 1.20703C8.50195 0.914062 8.80664 0.699219 9.1582 0.5625C9.50977 0.421875 9.90039 0.351562 10.3301 0.351562C10.6738 0.351562 10.9922 0.396484 11.2852 0.486328C11.582 0.572266 11.8496 0.707031 12.0879 0.890625C12.3262 1.07422 12.5293 1.31055 12.6973 1.59961C12.8652 1.88477 12.9941 2.22656 13.084 2.625C13.1738 3.01953 13.2188 3.47266 13.2188 3.98438ZM11.5254 5.68359V3.75586C11.5254 3.44727 11.5078 3.17773 11.4727 2.94727C11.4414 2.7168 11.3926 2.52148 11.3262 2.36133C11.2598 2.19727 11.1777 2.06445 11.0801 1.96289C10.9824 1.86133 10.8711 1.78711 10.7461 1.74023C10.6211 1.69336 10.4824 1.66992 10.3301 1.66992C10.1387 1.66992 9.96875 1.70703 9.82031 1.78125C9.67578 1.85547 9.55273 1.97461 9.45117 2.13867C9.34961 2.29883 9.27148 2.51367 9.2168 2.7832C9.16602 3.04883 9.14062 3.37305 9.14062 3.75586V5.68359C9.14062 5.99219 9.15625 6.26367 9.1875 6.49805C9.22266 6.73242 9.27344 6.93359 9.33984 7.10156C9.41016 7.26562 9.49219 7.40039 9.58594 7.50586C9.68359 7.60742 9.79492 7.68164 9.91992 7.72852C10.0488 7.77539 10.1895 7.79883 10.3418 7.79883C10.5293 7.79883 10.6953 7.76172 10.8398 7.6875C10.9883 7.60938 11.1133 7.48828 11.2148 7.32422C11.3203 7.15625 11.3984 6.9375 11.4492 6.66797C11.5 6.39844 11.5254 6.07031 11.5254 5.68359Z" />
                                    <path d="M16.0957 4.00195H16.998C17.2871 4.00195 17.5254 3.95312 17.7129 3.85547C17.9004 3.75391 18.0391 3.61328 18.1289 3.43359C18.2227 3.25 18.2695 3.03711 18.2695 2.79492C18.2695 2.57617 18.2266 2.38281 18.1406 2.21484C18.0586 2.04297 17.9316 1.91016 17.7598 1.81641C17.5879 1.71875 17.3711 1.66992 17.1094 1.66992C16.9023 1.66992 16.7109 1.71094 16.5352 1.79297C16.3594 1.875 16.2188 1.99023 16.1133 2.13867C16.0078 2.28711 15.9551 2.4668 15.9551 2.67773H14.2617C14.2617 2.20898 14.3867 1.80078 14.6367 1.45312C14.8906 1.10547 15.2305 0.833984 15.6562 0.638672C16.082 0.443359 16.5508 0.345703 17.0625 0.345703C17.6406 0.345703 18.1465 0.439453 18.5801 0.626953C19.0137 0.810547 19.3516 1.08203 19.5938 1.44141C19.8359 1.80078 19.957 2.24609 19.957 2.77734C19.957 3.04688 19.8945 3.30859 19.7695 3.5625C19.6445 3.8125 19.4648 4.03906 19.2305 4.24219C19 4.44141 18.7188 4.60156 18.3867 4.72266C18.0547 4.83984 17.6816 4.89844 17.2676 4.89844H16.0957V4.00195ZM16.0957 5.28516V4.41211H17.2676C17.7324 4.41211 18.1406 4.46484 18.4922 4.57031C18.8438 4.67578 19.1387 4.82812 19.377 5.02734C19.6152 5.22266 19.7949 5.45508 19.916 5.72461C20.0371 5.99023 20.0977 6.28516 20.0977 6.60938C20.0977 7.00781 20.0215 7.36328 19.8691 7.67578C19.7168 7.98438 19.502 8.24609 19.2246 8.46094C18.9512 8.67578 18.6309 8.83984 18.2637 8.95312C17.8965 9.0625 17.4961 9.11719 17.0625 9.11719C16.7031 9.11719 16.3496 9.06836 16.002 8.9707C15.6582 8.86914 15.3457 8.71875 15.0645 8.51953C14.7871 8.31641 14.5645 8.0625 14.3965 7.75781C14.2324 7.44922 14.1504 7.08398 14.1504 6.66211H15.8438C15.8438 6.88086 15.8984 7.07617 16.0078 7.24805C16.1172 7.41992 16.2676 7.55469 16.459 7.65234C16.6543 7.75 16.8711 7.79883 17.1094 7.79883C17.3789 7.79883 17.6094 7.75 17.8008 7.65234C17.9961 7.55078 18.1445 7.41016 18.2461 7.23047C18.3516 7.04688 18.4043 6.83398 18.4043 6.5918C18.4043 6.2793 18.3477 6.0293 18.2344 5.8418C18.1211 5.65039 17.959 5.50977 17.748 5.41992C17.5371 5.33008 17.2871 5.28516 16.998 5.28516H16.0957Z" />
                                </mask>
                                <path d="M2.17383 5.11523L0.826172 4.79297L1.3125 0.46875H6.10547V1.83398H2.70117L2.49023 3.72656C2.60352 3.66016 2.77539 3.58984 3.00586 3.51562C3.23633 3.4375 3.49414 3.39844 3.7793 3.39844C4.19336 3.39844 4.56055 3.46289 4.88086 3.5918C5.20117 3.7207 5.47266 3.9082 5.69531 4.1543C5.92188 4.40039 6.09375 4.70117 6.21094 5.05664C6.32812 5.41211 6.38672 5.81445 6.38672 6.26367C6.38672 6.64258 6.32812 7.00391 6.21094 7.34766C6.09375 7.6875 5.91602 7.99219 5.67773 8.26172C5.43945 8.52734 5.14062 8.73633 4.78125 8.88867C4.42188 9.04102 3.99609 9.11719 3.50391 9.11719C3.13672 9.11719 2.78125 9.0625 2.4375 8.95312C2.09766 8.84375 1.79102 8.68164 1.51758 8.4668C1.24805 8.25195 1.03125 7.99219 0.867188 7.6875C0.707031 7.37891 0.623047 7.02734 0.615234 6.63281H2.29102C2.31445 6.875 2.37695 7.08398 2.47852 7.25977C2.58398 7.43164 2.72266 7.56445 2.89453 7.6582C3.06641 7.75195 3.26758 7.79883 3.49805 7.79883C3.71289 7.79883 3.89648 7.75781 4.04883 7.67578C4.20117 7.59375 4.32422 7.48047 4.41797 7.33594C4.51172 7.1875 4.58008 7.01562 4.62305 6.82031C4.66992 6.62109 4.69336 6.40625 4.69336 6.17578C4.69336 5.94531 4.66602 5.73633 4.61133 5.54883C4.55664 5.36133 4.47266 5.19922 4.35938 5.0625C4.24609 4.92578 4.10156 4.82031 3.92578 4.74609C3.75391 4.67188 3.55273 4.63477 3.32227 4.63477C3.00977 4.63477 2.76758 4.68359 2.5957 4.78125C2.42773 4.87891 2.28711 4.99023 2.17383 5.11523Z" stroke="#0000FF" stroke-width="2" mask="url(#path-1-inside-1_2_23)" />
                                <path d="M13.2188 3.98438V5.46094C13.2188 6.10156 13.1504 6.6543 13.0137 7.11914C12.877 7.58008 12.6797 7.95898 12.4219 8.25586C12.168 8.54883 11.8652 8.76562 11.5137 8.90625C11.1621 9.04688 10.7715 9.11719 10.3418 9.11719C9.99805 9.11719 9.67773 9.07422 9.38086 8.98828C9.08398 8.89844 8.81641 8.75977 8.57812 8.57227C8.34375 8.38477 8.14062 8.14844 7.96875 7.86328C7.80078 7.57422 7.67188 7.23047 7.58203 6.83203C7.49219 6.43359 7.44727 5.97656 7.44727 5.46094V3.98438C7.44727 3.34375 7.51562 2.79492 7.65234 2.33789C7.79297 1.87695 7.99023 1.5 8.24414 1.20703C8.50195 0.914062 8.80664 0.699219 9.1582 0.5625C9.50977 0.421875 9.90039 0.351562 10.3301 0.351562C10.6738 0.351562 10.9922 0.396484 11.2852 0.486328C11.582 0.572266 11.8496 0.707031 12.0879 0.890625C12.3262 1.07422 12.5293 1.31055 12.6973 1.59961C12.8652 1.88477 12.9941 2.22656 13.084 2.625C13.1738 3.01953 13.2188 3.47266 13.2188 3.98438ZM11.5254 5.68359V3.75586C11.5254 3.44727 11.5078 3.17773 11.4727 2.94727C11.4414 2.7168 11.3926 2.52148 11.3262 2.36133C11.2598 2.19727 11.1777 2.06445 11.0801 1.96289C10.9824 1.86133 10.8711 1.78711 10.7461 1.74023C10.6211 1.69336 10.4824 1.66992 10.3301 1.66992C10.1387 1.66992 9.96875 1.70703 9.82031 1.78125C9.67578 1.85547 9.55273 1.97461 9.45117 2.13867C9.34961 2.29883 9.27148 2.51367 9.2168 2.7832C9.16602 3.04883 9.14062 3.37305 9.14062 3.75586V5.68359C9.14062 5.99219 9.15625 6.26367 9.1875 6.49805C9.22266 6.73242 9.27344 6.93359 9.33984 7.10156C9.41016 7.26562 9.49219 7.40039 9.58594 7.50586C9.68359 7.60742 9.79492 7.68164 9.91992 7.72852C10.0488 7.77539 10.1895 7.79883 10.3418 7.79883C10.5293 7.79883 10.6953 7.76172 10.8398 7.6875C10.9883 7.60938 11.1133 7.48828 11.2148 7.32422C11.3203 7.15625 11.3984 6.9375 11.4492 6.66797C11.5 6.39844 11.5254 6.07031 11.5254 5.68359Z" stroke="#0000FF" stroke-width="2" mask="url(#path-1-inside-1_2_23)" />
                                <path d="M16.0957 4.00195H16.998C17.2871 4.00195 17.5254 3.95312 17.7129 3.85547C17.9004 3.75391 18.0391 3.61328 18.1289 3.43359C18.2227 3.25 18.2695 3.03711 18.2695 2.79492C18.2695 2.57617 18.2266 2.38281 18.1406 2.21484C18.0586 2.04297 17.9316 1.91016 17.7598 1.81641C17.5879 1.71875 17.3711 1.66992 17.1094 1.66992C16.9023 1.66992 16.7109 1.71094 16.5352 1.79297C16.3594 1.875 16.2188 1.99023 16.1133 2.13867C16.0078 2.28711 15.9551 2.4668 15.9551 2.67773H14.2617C14.2617 2.20898 14.3867 1.80078 14.6367 1.45312C14.8906 1.10547 15.2305 0.833984 15.6562 0.638672C16.082 0.443359 16.5508 0.345703 17.0625 0.345703C17.6406 0.345703 18.1465 0.439453 18.5801 0.626953C19.0137 0.810547 19.3516 1.08203 19.5938 1.44141C19.8359 1.80078 19.957 2.24609 19.957 2.77734C19.957 3.04688 19.8945 3.30859 19.7695 3.5625C19.6445 3.8125 19.4648 4.03906 19.2305 4.24219C19 4.44141 18.7188 4.60156 18.3867 4.72266C18.0547 4.83984 17.6816 4.89844 17.2676 4.89844H16.0957V4.00195ZM16.0957 5.28516V4.41211H17.2676C17.7324 4.41211 18.1406 4.46484 18.4922 4.57031C18.8438 4.67578 19.1387 4.82812 19.377 5.02734C19.6152 5.22266 19.7949 5.45508 19.916 5.72461C20.0371 5.99023 20.0977 6.28516 20.0977 6.60938C20.0977 7.00781 20.0215 7.36328 19.8691 7.67578C19.7168 7.98438 19.502 8.24609 19.2246 8.46094C18.9512 8.67578 18.6309 8.83984 18.2637 8.95312C17.8965 9.0625 17.4961 9.11719 17.0625 9.11719C16.7031 9.11719 16.3496 9.06836 16.002 8.9707C15.6582 8.86914 15.3457 8.71875 15.0645 8.51953C14.7871 8.31641 14.5645 8.0625 14.3965 7.75781C14.2324 7.44922 14.1504 7.08398 14.1504 6.66211H15.8438C15.8438 6.88086 15.8984 7.07617 16.0078 7.24805C16.1172 7.41992 16.2676 7.55469 16.459 7.65234C16.6543 7.75 16.8711 7.79883 17.1094 7.79883C17.3789 7.79883 17.6094 7.75 17.8008 7.65234C17.9961 7.55078 18.1445 7.41016 18.2461 7.23047C18.3516 7.04688 18.4043 6.83398 18.4043 6.5918C18.4043 6.2793 18.3477 6.0293 18.2344 5.8418C18.1211 5.65039 17.959 5.50977 17.748 5.41992C17.5371 5.33008 17.2871 5.28516 16.998 5.28516H16.0957Z" stroke="#0000FF" stroke-width="2" mask="url(#path-1-inside-1_2_23)" />
                            </svg>
                            <p>Service Unavailable</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
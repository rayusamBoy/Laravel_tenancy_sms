@extends('errors.layout')

@section('title', 'Unauthorized')

@section('content')
<div class="page-content error-cover">

    @php $error_code = 401 @endphp

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="card mb-0 border-none box-shadowed">
                        <div class="card-body text-center">
                            <!-- 401 - red -->
                            <svg width="19" height="10" viewBox="0 0 19 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.3809 8.5H16.8809V4.04297C16.8809 3.90625 16.8828 3.73828 16.8867 3.53906C16.8897 3.38358 16.8938 3.22581 16.8991 3.06575C16.9344 3.03331 16.9701 3.00005 17.0064 2.96599L17.0067 2.96566C17.022 2.95135 17.0369 2.93726 17.0514 2.92342C17.0515 2.92339 17.0515 2.92335 17.0515 2.92332C17.1621 2.81826 17.253 2.7276 17.3094 2.66086L16.9277 2.33789L16.4282 2.31519C16.4277 2.32634 16.4272 2.33759 16.4267 2.34892L16.3217 2.23714M17.3809 8.5V9H18.1914V8.5M17.3809 8.5V4.04297C17.3809 3.91062 17.3828 3.74615 17.3866 3.54886L17.3866 3.54868C17.3905 3.34863 17.3962 3.14456 17.404 2.93647L17.404 2.93614C17.4117 2.72245 17.4195 2.53064 17.4272 2.36059L17.4921 0.933594M17.3809 8.5H18.1914M18.1914 8.5H18.6914V0.933594H18.1914M18.1914 8.5V0.933594M18.1914 0.933594V0.433594H17.2031M18.1914 0.933594H17.4921M17.2031 0.433594L14.8345 2.31942L15.1471 2.70965M17.2031 0.433594V0.933594H17.3779M17.2031 0.433594L17.5146 0.824756L17.3779 0.933594M15.1471 2.70965L14.756 3.02109L15.0038 3.33047L15.3936 3.01728M15.1471 2.70965L15.3936 3.01728M15.1471 2.70965L17.3779 0.933594M15.3936 3.01728L15.7062 3.40751L16.3008 2.92969M15.3936 3.01728L15.9808 2.54542M16.3008 2.92969C16.3354 2.89981 16.3708 2.86884 16.4069 2.83677C16.4135 2.65951 16.4201 2.49702 16.4267 2.34937L16.3214 2.23746C16.3215 2.23735 16.3216 2.23725 16.3217 2.23714M16.3008 2.92969L15.9876 2.53993L15.9808 2.54542M16.3008 2.92969L15.9743 2.55098C15.9765 2.54913 15.9786 2.54728 15.9808 2.54542M16.3217 2.23714C16.4564 2.1104 16.5241 2.04091 16.546 2.01492L17.461 0.933594M16.3217 2.23714C16.1996 2.35186 16.086 2.45452 15.9808 2.54542M17.461 0.933594H17.3779M17.461 0.933594L17.4938 0.894786L17.4921 0.933594M17.461 0.933594H17.4921M5.63086 6.31836H6.16211V6.72461H5.63086H5.13086V7.22461V8.5H4.36133V7.22461V6.72461H3.86133H0.705078V6.11856L4.2258 0.933594H5.13086V5.81836V6.31836H5.63086ZM3.43697 2.29758C3.36727 2.44799 3.29404 2.59448 3.21732 2.73708L4.36675 3.89972L4.3668 3.89862L4.36685 3.89752L4.36689 3.89643L4.36694 3.89533L4.36698 3.89423L4.36703 3.89313L4.36707 3.89203L4.36712 3.89093L4.36717 3.88983L4.36721 3.88874L4.36726 3.88764L4.3673 3.88654L4.36735 3.88544L4.36739 3.88434L4.36744 3.88324L4.36749 3.88214L4.36753 3.88104L4.36758 3.87995L4.36762 3.87885L4.36767 3.87775L4.36772 3.87665L4.36776 3.87555L4.36781 3.87445L4.36785 3.87335L4.3679 3.87226L4.36794 3.87116L4.36799 3.87006L4.36804 3.86896L4.36808 3.86786L4.36813 3.86676L4.36817 3.86566L4.36822 3.86457L4.36826 3.86347L4.36831 3.86237L4.36836 3.86127L4.3684 3.86017L4.36845 3.85907L4.36849 3.85797L4.36854 3.85687L4.36859 3.85578L4.36863 3.85468L4.36868 3.85358L4.36872 3.85248L4.36877 3.85138L4.36881 3.85028L4.36886 3.84918L4.36891 3.84809L4.36895 3.84699L4.369 3.84589L4.36904 3.84479L4.36909 3.84369L4.36913 3.84259L4.36918 3.84149L4.36923 3.8404L4.36927 3.8393L4.36932 3.8382L4.36936 3.8371L4.36941 3.836L4.36945 3.8349L4.3695 3.8338L4.36955 3.83271L4.36959 3.83161L4.36964 3.83051L4.36968 3.82941L4.36973 3.82831L4.36978 3.82721L4.36982 3.82611L4.36987 3.82501L4.36991 3.82392L4.36996 3.82282L4.37 3.82172L4.37005 3.82062L4.3701 3.81952L4.37014 3.81842L4.37019 3.81732L4.37023 3.81623L4.37028 3.81513L4.37032 3.81403L4.37037 3.81293L4.37042 3.81183L4.37046 3.81073L4.37051 3.80963L4.37055 3.80854L4.3706 3.80744L4.37065 3.80634L4.37069 3.80524L4.37074 3.80414L4.37078 3.80304L4.37083 3.80194L4.37087 3.80084L4.37092 3.79975L4.37097 3.79865L4.37101 3.79755L4.37106 3.79645L4.3711 3.79535L4.37115 3.79425L4.37119 3.79315L4.37124 3.79206L4.37129 3.79096L4.37133 3.78986L4.37138 3.78876L4.37142 3.78766L4.37147 3.78656L4.37151 3.78546L4.37156 3.78437L4.37161 3.78327L4.37165 3.78217L4.3717 3.78107L4.37174 3.77997L4.37179 3.77887L4.37184 3.77777L4.37188 3.77667L4.37193 3.77558L4.37197 3.77448L4.37202 3.77338L4.37206 3.77228L4.37211 3.77118L4.37216 3.77008L4.3722 3.76898L4.37225 3.76789L4.37229 3.76679L4.37234 3.76569L4.37238 3.76459L4.37243 3.76349L4.37248 3.76239L4.37252 3.76129L4.37257 3.7602L4.37261 3.7591L4.37266 3.758L4.3727 3.7569L4.37275 3.7558L4.3728 3.7547L4.37284 3.7536L4.37289 3.7525L4.37293 3.75141L4.37298 3.75031L4.37303 3.74921L4.37307 3.74811L4.37312 3.74701L4.37316 3.74591L4.37321 3.74481L4.37325 3.74372L4.3733 3.74262L4.37335 3.74152L4.37339 3.74042L4.37344 3.73932L4.37348 3.73822L4.37353 3.73712L4.37357 3.73603L4.37362 3.73493L4.37367 3.73383L4.37371 3.73273L4.37376 3.73163L4.3738 3.73053L4.37385 3.72943L4.3739 3.72833L4.37394 3.72724L4.37399 3.72614L4.37403 3.72504L4.37408 3.72394L4.37412 3.72284L4.37417 3.72174L4.37422 3.72064L4.37426 3.71955L4.37431 3.71845L4.37435 3.71735L4.3744 3.71625L4.37444 3.71515L4.37449 3.71405L4.37454 3.71295L4.37458 3.71186L4.37463 3.71076L4.37467 3.70966L4.37472 3.70856L4.37476 3.70746L4.37481 3.70636L4.37486 3.70526L4.3749 3.70416L4.37495 3.70307L4.37499 3.70197L4.37504 3.70087L4.37509 3.69977L4.37513 3.69867L4.37518 3.69757L4.37522 3.69647L4.37527 3.69538L4.37531 3.69428L4.37536 3.69318L4.37541 3.69208L4.37545 3.69098L4.3755 3.68988L4.37554 3.68878L4.37559 3.68769L4.37563 3.68659L4.37568 3.68549L4.37573 3.68439L4.37577 3.68329L4.37582 3.68219L4.37586 3.68109L4.37591 3.68L4.37596 3.6789L4.376 3.6778L4.37605 3.6767L4.37609 3.6756L4.37614 3.6745L4.37618 3.6734L4.37623 3.6723L4.37628 3.67121L4.37632 3.67011L4.37637 3.66901L4.37641 3.66791L4.37646 3.66681L4.3765 3.66571L4.37655 3.66461L4.3766 3.66352L4.37664 3.66242L4.37669 3.66132L4.37673 3.66022L4.37678 3.65912L4.37682 3.65802L4.37687 3.65692L4.37692 3.65583L4.37696 3.65473L4.37701 3.65363L4.37705 3.65253L4.3771 3.65143L4.37715 3.65033L4.37719 3.64923L4.37724 3.64813L4.37728 3.64704L4.37733 3.64594L4.37737 3.64484L4.37742 3.64374L4.37747 3.64264L4.37751 3.64154L4.37756 3.64044L4.3776 3.63935L4.37765 3.63825L4.37769 3.63715L4.37774 3.63605L4.37779 3.63495L4.37783 3.63385L4.37788 3.63275L4.37792 3.63166L4.37797 3.63056L4.37802 3.62946L4.37806 3.62836L4.37811 3.62726L4.37815 3.62616L4.3782 3.62506L4.37824 3.62396L4.37829 3.62287L4.37834 3.62177L4.37838 3.62067L4.37843 3.61957L4.37847 3.61847L4.37852 3.61737L4.37856 3.61627L4.37861 3.61518L4.37866 3.61408L4.3787 3.61298L4.37875 3.61188L4.37879 3.61078L4.37884 3.60968L4.37888 3.60858L4.37893 3.60749L4.37898 3.60639L4.37902 3.60529L4.37907 3.60419L4.37911 3.60309L4.37916 3.60199L4.37921 3.60089L4.37925 3.59979L4.3793 3.5987L4.37934 3.5976L4.37939 3.5965L4.37943 3.5954L4.37948 3.5943L4.37953 3.5932L4.37957 3.5921L4.37962 3.59101L4.37966 3.58991L4.37971 3.58881L4.37975 3.58771L4.3798 3.58661L4.37985 3.58551L4.37989 3.58441L4.37994 3.58332L4.37998 3.58222L4.38003 3.58112L4.38007 3.58002L4.38012 3.57892L4.38017 3.57782L4.38021 3.57672L4.38026 3.57562L4.3803 3.57453L4.38035 3.57343L4.3804 3.57233L4.38044 3.57123L4.38049 3.57013L4.38053 3.56903L4.38058 3.56793L4.38062 3.56684L4.38067 3.56574L4.38072 3.56464L4.38076 3.56354L4.38081 3.56244L4.38085 3.56134L4.3809 3.56024L4.38094 3.55915L4.38099 3.55805L4.38104 3.55695L4.38108 3.55585L4.38113 3.55475L4.38117 3.55365L4.38122 3.55255L4.38127 3.55146L4.38131 3.55036L4.38136 3.54926L4.3814 3.54816L4.38145 3.54706L4.38149 3.54596L4.38154 3.54486L4.38159 3.54376L4.38163 3.54267L4.38168 3.54157L4.38172 3.54047L4.38177 3.53937L4.38181 3.53827L4.38186 3.53717L4.38191 3.53607L4.38195 3.53498L4.382 3.53388L4.38204 3.53278L4.38209 3.53168L4.38213 3.53058L4.38218 3.52948L4.38223 3.52838L4.38227 3.52729L4.38232 3.52619L4.38236 3.52509L4.38241 3.52399L4.38246 3.52289L4.3825 3.52179L4.38255 3.52069L4.38259 3.51959L4.38264 3.5185L4.38268 3.5174L4.38273 3.5163L4.38278 3.5152L4.38282 3.5141L4.38287 3.513L4.38291 3.5119L4.38296 3.51081L4.383 3.50971L4.38305 3.50861L4.3831 3.50751L4.38314 3.50641L4.38319 3.50531L4.38323 3.50421L4.38328 3.50312L4.38333 3.50202L4.38337 3.50092L4.38342 3.49982L4.38346 3.49872L4.38351 3.49762L4.38355 3.49652L4.3836 3.49542L4.38365 3.49433L4.38369 3.49323L4.38374 3.49213L4.38378 3.49103L4.38383 3.48993L4.38387 3.48883L4.38392 3.48773L4.38397 3.48664L4.38401 3.48554L4.38406 3.48444L4.3841 3.48334L4.38415 3.48224L4.38419 3.48114L4.38424 3.48004L4.38429 3.47895L4.38433 3.47785L4.38438 3.47675L4.38442 3.47565L4.38447 3.47455L4.38452 3.47345L4.38456 3.47235L4.38461 3.47125L4.38465 3.47016L4.3847 3.46906L4.38474 3.46796L4.38479 3.46686L4.38484 3.46576L4.38488 3.46466L4.38493 3.46356L4.38497 3.46247L4.38502 3.46137L4.38506 3.46027L4.38511 3.45917L4.38516 3.45807L4.3852 3.45697L4.38525 3.45587L4.38529 3.45478L4.38534 3.45368L4.38539 3.45258L4.38543 3.45148L4.38548 3.45038L4.38552 3.44928L4.38557 3.44818L4.38561 3.44708L4.38566 3.44599L4.38571 3.44489L4.38575 3.44379L4.3858 3.44269L4.38584 3.44159L4.38589 3.44049L4.38593 3.43939L4.38598 3.4383L4.38603 3.4372L4.38607 3.4361L4.38612 3.435L4.38616 3.4339L4.38621 3.4328L4.38625 3.4317L4.3863 3.43061L4.38635 3.42951L4.38639 3.42841L4.38644 3.42731L4.38648 3.42621L4.38653 3.42511L4.38658 3.42401L4.38662 3.42291L4.38667 3.42182L4.38671 3.42072L4.38676 3.41962L4.3868 3.41852L4.38685 3.41742L4.3869 3.41632L4.38694 3.41522L4.38699 3.41413L4.38703 3.41303L4.38708 3.41193L4.38712 3.41083L4.38717 3.40973L4.38722 3.40863L4.38726 3.40753L4.38731 3.40644L4.38735 3.40534L4.3874 3.40424L4.38744 3.40314L4.38749 3.40204L4.38754 3.40094L4.38758 3.39984L4.38763 3.39875L4.38767 3.39765L4.38772 3.39655L4.38777 3.39545L4.38781 3.39435L4.38786 3.39325L4.3879 3.39215L4.38795 3.39105L4.38799 3.38996L4.38804 3.38886L4.38809 3.38776L4.38813 3.38666L4.38818 3.38556L4.38822 3.38446L4.38827 3.38336L4.38831 3.38227L4.38836 3.38117L4.38841 3.38007L4.38845 3.37897L4.3885 3.37787L4.38854 3.37677L4.38859 3.37567L4.38864 3.37458L4.38868 3.37348L4.38873 3.37238L4.38877 3.37128L4.38882 3.37018L4.38886 3.36908L4.38891 3.36798L4.38896 3.36688L4.389 3.36579L4.38905 3.36469L4.38909 3.36359L4.38914 3.36249L4.38918 3.36139L4.38923 3.36029L4.38928 3.35919L4.38932 3.3581L4.38937 3.357L4.38941 3.3559L4.38946 3.3548L4.3895 3.3537L4.38955 3.3526L4.3896 3.3515L4.38964 3.35041L4.38969 3.34931L4.38973 3.34821L4.38978 3.34711L4.38983 3.34601L4.38987 3.34491L4.38992 3.34381L4.38996 3.34271L4.39001 3.34162L4.39005 3.34052L4.3901 3.33942L4.39015 3.33832L4.39019 3.33722C4.39783 3.15389 4.40544 2.99205 4.413 2.8515C4.42445 2.70642 4.43232 2.60221 4.43639 2.54107L4.47194 2.00781H3.9375H3.89062H3.57125L3.43697 2.29758ZM12.6348 6.46962L12.6342 6.47302C12.5378 6.96917 12.3848 7.36909 12.1861 7.685L12.1841 7.68815C11.9955 7.99308 11.753 8.21856 11.4528 8.37483C11.1578 8.52835 10.7765 8.61719 10.2891 8.61719C9.68863 8.61719 9.24094 8.467 8.90615 8.20375C8.55803 7.92611 8.28067 7.51398 8.09334 6.93142C7.90236 6.33354 7.80078 5.59864 7.80078 4.7168C7.80078 3.82331 7.8934 3.08246 8.06761 2.4847C8.24067 1.90027 8.50879 1.49036 8.8503 1.21593C9.1753 0.954773 9.63781 0.798828 10.2891 0.798828C10.8842 0.798828 11.3297 0.950213 11.6651 1.21728L11.665 1.2173L11.6689 1.22029C12.0193 1.49388 12.3002 1.90533 12.4913 2.49262L12.4916 2.49346C12.6854 3.0855 12.7891 3.82288 12.7891 4.7168C12.7891 5.38283 12.7363 5.96599 12.6348 6.46962ZM8.71141 6.4309L8.71139 6.43091L8.71225 6.43592C8.79314 6.90852 8.93631 7.3275 9.18106 7.63899C9.45761 7.99096 9.84769 8.1582 10.2891 8.1582C10.7253 8.1582 11.1117 7.99398 11.3881 7.64879C11.6389 7.33647 11.7847 6.91587 11.8659 6.44178L11.866 6.44105C11.9466 5.96623 11.9844 5.38914 11.9844 4.7168C11.9844 4.04817 11.9465 3.4731 11.866 2.9984C11.7853 2.52293 11.6406 2.10095 11.3908 1.78824C11.1171 1.43604 10.7307 1.26367 10.2891 1.26367C9.84502 1.26367 9.45495 1.43497 9.17974 1.79042C8.93523 2.10247 8.79268 2.52351 8.71212 2.9984L8.7121 2.9984L8.71141 3.00269C8.63542 3.47554 8.59961 4.04905 8.59961 4.7168C8.59961 5.38454 8.63542 5.95806 8.71141 6.4309Z" stroke="#FF0000" />
                            </svg>

                            <!-- 401 - blue -->
                            <!-- <svg viewBox="0 0 19 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.33943 1.0285L9.33943 1.02852L9.3439 1.02674C9.62898 0.912707 9.95548 0.851562 10.3301 0.851562C10.6301 0.851562 10.8985 0.89073 11.1386 0.964355L11.1385 0.964415L11.1461 0.96661C11.3845 1.03562 11.5954 1.14239 11.7827 1.2867C11.9642 1.42652 12.1259 1.61158 12.265 1.85082L12.2649 1.85082L12.2664 1.85338C12.4028 2.08481 12.5152 2.37577 12.5962 2.73498L12.5965 2.73602C12.6762 3.08604 12.7188 3.50068 12.7188 3.98438V5.46094C12.7188 6.06802 12.6537 6.57076 12.5341 6.97771C12.4133 7.38474 12.246 7.69581 12.0444 7.92802L12.044 7.92839C11.8411 8.16259 11.6037 8.33173 11.328 8.44201C11.0429 8.55604 10.7164 8.61719 10.3418 8.61719C10.0411 8.61719 9.769 8.57978 9.52283 8.50885C9.28602 8.43682 9.0759 8.32741 8.88886 8.18054C8.70692 8.03466 8.54284 7.84635 8.39907 7.60863C8.26249 7.37263 8.15043 7.0797 8.06978 6.72205C8.06379 6.69544 8.05799 6.66846 8.05241 6.64109C8.00108 6.38947 7.96771 6.10663 7.95415 5.79104C7.94958 5.68457 7.94727 5.57455 7.94727 5.46094V3.98438C7.94727 3.37743 8.01226 2.87996 8.13099 2.48245C8.25574 2.07409 8.42311 1.76445 8.62078 1.53589C8.82728 1.30166 9.0657 1.13495 9.33943 1.0285ZM5.0957 7.13672V8.5H4.4082V7.13672V6.63672H3.9082H0.877613L0.845655 6.23724L4.1665 0.96875H4.35115L3.479 2.42819L3.35104 2.64232L1.58247 5.55285L1.12087 6.3125H2.00977H3.9082H4.4082V5.8125V2.68468V0.96875H5.0957V5.8125V6.3125H5.5957H6.0625V6.63672H5.5957H5.0957V7.13672ZM8.72678 2.68378L8.72675 2.68377L8.72569 2.68932C8.66712 2.99568 8.64062 3.35339 8.64062 3.75586V5.68359C8.64062 6.00803 8.65699 6.30241 8.69189 6.56413L8.69182 6.56414L8.69303 6.57222C8.73218 6.83322 8.79095 7.07313 8.87486 7.28539L8.87747 7.292L8.88027 7.29852C8.96641 7.49951 9.07476 7.68339 9.21223 7.83804L9.21874 7.84536L9.22552 7.85241C9.372 8.00476 9.54557 8.12213 9.74436 8.19668L9.74435 8.1967L9.74905 8.19841C9.93845 8.26728 10.1376 8.29883 10.3418 8.29883C10.5965 8.29883 10.8429 8.24801 11.0682 8.13228L11.0683 8.13231L11.0727 8.12996C11.3109 8.00461 11.4979 7.81641 11.6392 7.58865C11.7875 7.35189 11.8825 7.06904 11.9406 6.76054C11.9989 6.45093 12.0254 6.08983 12.0254 5.68359V3.75586C12.0254 3.43128 12.0071 3.13686 11.9676 2.87596C11.9324 2.6187 11.8754 2.38106 11.7889 2.17177C11.7045 1.96397 11.5916 1.77345 11.4405 1.61634C11.294 1.46399 11.1204 1.34662 10.9217 1.27207C10.7332 1.20139 10.534 1.16992 10.3301 1.16992C10.073 1.16992 9.8245 1.22014 9.59671 1.33404L9.59669 1.33401L9.59191 1.33647C9.35328 1.45901 9.16765 1.64748 9.02745 1.87321C8.88288 2.10206 8.7884 2.38007 8.72678 2.68378ZM17.293 2.39648V1.71286L16.6415 1.91999L15.2656 2.3574V2.06349L17.9805 1.09551V8.5H17.293V2.39648Z" stroke="#0000FF"/>
                            </svg> -->

                            <p class="text-danger">Unauthorized<p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
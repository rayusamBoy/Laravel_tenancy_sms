@extends('errors.layout')

@section('title', 'Bad Request')

@section('content')
<div class="page-content error-cover">

    @php $error_code = 400 @endphp

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="card mb-0 border-none box-shadowed">
                        <div class="card-body text-center">
                            <!-- 400 -->
                            <svg width="20" height="10" viewBox="0 0 20 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.8672 0.884766C18.6367 0.701172 18.375 0.566406 18.082 0.480469C17.793 0.394531 17.4766 0.351562 17.1328 0.351562C16.707 0.351562 16.3203 0.419922 15.9727 0.556641L18.8672 0.884766ZM18.8672 0.884766C19.1016 1.06445 19.3008 1.29883 19.4648 1.58789L18.8672 0.884766ZM5.02539 7.10742V8.5H4.47266V7.10742V6.60742H3.97266H0.859383L0.838194 6.29921L4.21835 0.96875H4.30479L3.54186 2.26372L3.41538 2.4784L1.47844 5.62667L1.00962 6.38867H1.9043H3.97266H4.47266V5.88867V2.51753V0.96875H5.02539V5.88867V6.38867H5.52539H6.03906V6.60742H5.52539H5.02539V7.10742ZM9.29562 1.02372L9.29563 1.02374L9.30018 1.02195C9.58198 0.911129 9.90552 0.851562 10.2773 0.851562C10.5795 0.851562 10.8472 0.889314 11.0841 0.95974L11.0858 0.960253C11.3189 1.02863 11.5222 1.13406 11.7002 1.27585L11.7001 1.27591L11.7075 1.28157C11.8825 1.4157 12.0394 1.59658 12.1745 1.83469L12.1745 1.8347L12.176 1.83724C12.3085 2.06762 12.4177 2.35995 12.4954 2.72358L12.4954 2.72359L12.4966 2.72912C12.5764 3.08303 12.6191 3.50573 12.6191 4.00195V5.4375C12.6191 6.06267 12.5556 6.57431 12.4392 6.98178C12.3213 7.39425 12.1587 7.70714 11.9643 7.93841C11.7658 8.17065 11.5336 8.33821 11.264 8.44768C10.9827 8.55794 10.6599 8.61719 10.2891 8.61719C9.99274 8.61719 9.72518 8.57991 9.48352 8.50936C9.25021 8.43744 9.04385 8.33002 8.86084 8.18739C8.68466 8.04328 8.52491 7.85494 8.38476 7.61427C8.25102 7.37801 8.14004 7.08243 8.0588 6.71947C8.04904 6.67365 8.03985 6.6267 8.03125 6.57861C7.99255 6.36235 7.96585 6.12392 7.95213 5.86248C7.94501 5.72677 7.94141 5.58514 7.94141 5.4375V4.00195C7.94141 3.37759 8.00481 2.86903 8.12054 2.46649C8.24211 2.05737 8.40653 1.74782 8.60105 1.51987C8.7987 1.28825 9.02892 1.1256 9.29562 1.02372ZM8.5864 2.65327L8.58638 2.65326L8.58536 2.65847C8.52262 2.98116 8.49414 3.35898 8.49414 3.78516V5.64844C8.49414 5.99322 8.51254 6.30492 8.5519 6.58048L8.55184 6.58048L8.55306 6.58815C8.59624 6.86018 8.65901 7.10906 8.7467 7.32827L8.74662 7.32831L8.75031 7.33706C8.84083 7.55145 8.95578 7.74649 9.10232 7.90994L9.10839 7.9167L9.11469 7.92324C9.26768 8.0819 9.44765 8.20468 9.65219 8.2865L9.65214 8.28665L9.6638 8.29098C9.86274 8.36487 10.0729 8.39844 10.2891 8.39844C10.5622 8.39844 10.8254 8.34411 11.0642 8.21924C11.3155 8.08994 11.5133 7.89308 11.6628 7.65282L11.6628 7.65284L11.6654 7.64865C11.8147 7.40327 11.9129 7.11025 11.9749 6.78821C12.0417 6.45741 12.0723 6.07544 12.0723 5.64844V3.78516C12.0723 3.44053 12.052 3.12906 12.008 2.85454C11.9688 2.58225 11.9079 2.33251 11.8182 2.11342C11.7293 1.89612 11.6094 1.69822 11.4486 1.53642C11.294 1.37699 11.1112 1.25523 10.9026 1.17777C10.7037 1.10388 10.4935 1.07031 10.2773 1.07031C10.0069 1.07031 9.74695 1.12509 9.50877 1.24615C9.25642 1.37035 9.05708 1.56297 8.90629 1.79992C8.75347 2.04007 8.65242 2.33199 8.5864 2.65327ZM15.4419 2.65327L15.4418 2.65326L15.4408 2.65847C15.3781 2.98116 15.3496 3.35898 15.3496 3.78516V5.64844C15.3496 5.99322 15.368 6.30492 15.4074 6.58048L15.4073 6.58048L15.4085 6.58815C15.4517 6.86018 15.5145 7.10906 15.6022 7.32827L15.6021 7.32831L15.6058 7.33706C15.6963 7.55145 15.8113 7.74649 15.9578 7.90994L15.9639 7.9167L15.9702 7.92324C16.1231 8.0819 16.3031 8.20468 16.5077 8.2865L16.5076 8.28665L16.5193 8.29098C16.7182 8.36487 16.9284 8.39844 17.1445 8.39844C17.4177 8.39844 17.6808 8.34412 17.9196 8.21927C18.171 8.08997 18.3688 7.8931 18.5183 7.65282L18.5183 7.65284L18.5208 7.64865C18.6702 7.40327 18.7684 7.11026 18.8304 6.78821C18.8972 6.45741 18.9277 6.07544 18.9277 5.64844V3.78516C18.9277 3.44053 18.9074 3.12906 18.8635 2.85454C18.8243 2.58225 18.7633 2.33251 18.6737 2.11342C18.5848 1.89612 18.4648 1.69822 18.3041 1.53642C18.1495 1.37699 17.9666 1.25523 17.7581 1.17777C17.5591 1.10388 17.3489 1.07031 17.1328 1.07031C16.8624 1.07031 16.6024 1.12509 16.3642 1.24615C16.1119 1.37035 15.9126 1.56297 15.7618 1.79992C15.6089 2.04007 15.5079 2.33199 15.4419 2.65327ZM16.1511 1.02372L16.1511 1.02374L16.1556 1.02195C16.4375 0.911129 16.761 0.851562 17.1328 0.851562C17.4349 0.851562 17.7027 0.889314 17.9395 0.95974L17.9413 0.960253C18.1744 1.02863 18.3777 1.13406 18.5556 1.27585L18.5556 1.27591L18.563 1.28157C18.7379 1.4157 18.8949 1.59658 19.03 1.8347L19.0315 1.83724C19.164 2.06762 19.2732 2.35995 19.3509 2.72358L19.3508 2.72359L19.3521 2.72912C19.4319 3.08303 19.4746 3.50573 19.4746 4.00195V5.4375C19.4746 6.06267 19.411 6.57431 19.2946 6.98178C19.1768 7.39429 19.0141 7.7072 18.8197 7.93848C18.6212 8.17069 18.389 8.33824 18.1194 8.4477C17.8381 8.55795 17.5153 8.61719 17.1445 8.61719C16.8482 8.61719 16.5806 8.57991 16.339 8.50935C16.1057 8.43744 15.8993 8.33002 15.7163 8.18739C15.5401 8.04328 15.3804 7.85494 15.2402 7.61427C15.1065 7.37801 14.9955 7.08244 14.9143 6.71949C14.8376 6.35974 14.7969 5.93379 14.7969 5.4375V4.00195C14.7969 3.37756 14.8603 2.86899 14.976 2.46644C15.0976 2.05735 15.262 1.74781 15.4565 1.51987C15.6542 1.28825 15.8844 1.1256 16.1511 1.02372Z" stroke="#0000FF" />
                            </svg>
                            <p>Bad Request!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
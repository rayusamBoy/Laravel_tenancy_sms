@extends('errors.layout')

@section('title', 'Page Not Found')

@section('content')
<div class="page-content error-cover">

    @php $error_code = 404 @endphp

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="card mb-0 border-none box-shadowed">
                        <div class="card-body text-center">
                            <!-- 404 -->
                            <svg viewBox="0 0 21 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.877 7.13672V8.5H18.1895V7.13672V6.63672H17.6895H14.6589L14.6269 6.23724L17.9478 0.96875H18.1324L17.2603 2.42819L17.1332 2.64074L17.1313 2.6439L15.3637 5.55285L14.9021 6.3125H15.791H17.6895H18.1895V5.8125V2.68468V0.96875H18.877V5.8125V6.3125H19.377H19.8438V6.63672H19.377H18.877V7.13672ZM9.33943 1.0285L9.33943 1.02852L9.3439 1.02674C9.62898 0.912707 9.95548 0.851562 10.3301 0.851562C10.6301 0.851562 10.8985 0.89073 11.1386 0.964355L11.1385 0.964415L11.1461 0.96661C11.3845 1.03562 11.5954 1.14239 11.7827 1.2867C11.9642 1.42652 12.1259 1.61158 12.265 1.85082L12.2649 1.85082L12.2664 1.85338C12.4028 2.08481 12.5152 2.37577 12.5962 2.73498L12.5965 2.73602C12.6762 3.08604 12.7188 3.50068 12.7188 3.98438V5.46094C12.7188 5.52514 12.718 5.58824 12.7166 5.65024C12.7126 5.82137 12.7031 5.98393 12.6884 6.13807C12.6782 6.24427 12.6656 6.34635 12.6505 6.44439C12.6208 6.63833 12.5817 6.81582 12.5341 6.97761C12.4133 7.38469 12.246 7.69579 12.0444 7.92802L12.044 7.92839C11.8411 8.16259 11.6037 8.33173 11.328 8.44201C11.0429 8.55604 10.7164 8.61719 10.3418 8.61719C10.0411 8.61719 9.769 8.57978 9.52283 8.50885C9.28602 8.43682 9.0759 8.32741 8.88886 8.18054C8.70692 8.03466 8.54284 7.84635 8.39907 7.60863C8.26249 7.37263 8.15043 7.0797 8.06978 6.72205C8.06379 6.69544 8.05799 6.66846 8.05241 6.64109C8.00108 6.38947 7.96771 6.10663 7.95415 5.79104C7.94958 5.68457 7.94727 5.57455 7.94727 5.46094V3.98438C7.94727 3.37743 8.01226 2.87996 8.13099 2.48245C8.25574 2.07409 8.42311 1.76445 8.62078 1.53589C8.82728 1.30166 9.0657 1.13495 9.33943 1.0285ZM5.0957 7.13672V8.5H4.4082V7.13672V6.63672H3.9082H0.877613L0.845655 6.23724L4.1665 0.96875H4.35115L3.479 2.42819L3.35104 2.64232L1.58247 5.55285L1.12087 6.3125H2.00977H3.9082H4.4082V5.8125V2.68468V0.96875H5.0957V5.8125V6.3125H5.5957H6.0625V6.63672H5.5957H5.0957V7.13672ZM8.72678 2.68378L8.72675 2.68377L8.72569 2.68932C8.66712 2.99568 8.64062 3.35339 8.64062 3.75586V5.68359C8.64062 6.00803 8.65699 6.30241 8.69189 6.56413L8.69182 6.56414L8.69303 6.57222C8.73218 6.83322 8.79095 7.07313 8.87486 7.28539L8.87747 7.292L8.88027 7.29852C8.96641 7.49951 9.07476 7.68339 9.21223 7.83804L9.21874 7.84536L9.22552 7.85241C9.372 8.00476 9.54557 8.12213 9.74436 8.19668L9.74435 8.1967L9.74905 8.19841C9.93845 8.26728 10.1376 8.29883 10.3418 8.29883C10.5965 8.29883 10.8429 8.24801 11.0682 8.13228L11.0683 8.13231L11.0727 8.12996C11.3109 8.00461 11.4979 7.81641 11.6392 7.58865C11.7875 7.35189 11.8825 7.06904 11.9406 6.76054C11.9989 6.45093 12.0254 6.08983 12.0254 5.68359V3.75586C12.0254 3.43128 12.0071 3.13686 11.9676 2.87596C11.9324 2.6187 11.8754 2.38106 11.7889 2.17177C11.7045 1.96397 11.5916 1.77345 11.4405 1.61634C11.294 1.46399 11.1204 1.34662 10.9217 1.27207C10.7332 1.20139 10.534 1.16992 10.3301 1.16992C10.073 1.16992 9.8245 1.22014 9.59671 1.33404L9.59669 1.33401L9.59191 1.33647C9.35328 1.45901 9.16765 1.64748 9.02745 1.87321C8.88288 2.10206 8.7884 2.38007 8.72678 2.68378Z" stroke="#0000FF" />
                            </svg>
                            <p>Page Not Found!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
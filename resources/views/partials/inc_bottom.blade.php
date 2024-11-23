{{-- Theme JS files --}}
<script src="{{ asset('global_assets/js/plugins/extensions/jquery_ui/interactions.min.js') }} "></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }} "></script>

{{-- Forms --}}
<script src="{{ asset('global_assets/js/plugins/forms/wizards/steps.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/inputs/inputmask.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/validation/validate.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/extensions/cookie.js') }}"></script>

{{-- Notifications --}}
<script type="text/javascript" src="{{ asset('global_assets/js/plugins/notifications/sweetalert2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('global_assets/js/plugins/notifications/toastr.min.js') }}"></script>

{{-- Table --}}
<script src="{{ asset('global_assets/js/plugins/tables/floathead/jquery.floatThead.min.js') }}"></script>

{{-- DataTables --}}
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>

{{-- Date Pickers --}}
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>

{{-- Uploaders --}}
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>

{{-- Core app JS file --}}
<script src="{{ asset('assets/js/app-min.js') }}"></script>

{{-- Plugins --}}
<script src="{{ asset('global_assets/js/plugins/forms/wizards/form_wizard.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/form_select2.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/datatables_extension_buttons_html5.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/uploaders/uploader_bootstrap.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/ui/autosize.min.js') }}"></script>

{{-- Scrollers --}}
<script src="{{ asset('global_assets/js/plugins/scrollers/jquery.floatingscroll.min.js') }}"></script>

{{-- Theme JS files --}}
<script src="{{ asset('assets/js/custom-min.js') }} "></script>

{{-- Prints --}}
<script src="{{ asset('global_assets/js/plugins/prints/printThis-min.js') }}"></script>

@vite('resources/js/app.js')

@include('partials.js.custom')

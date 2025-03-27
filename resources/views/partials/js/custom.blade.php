<script>
    /**
     *-------------------------------------------------------------
     * Login and related pages texts and background preview
     *-------------------------------------------------------------
     */
    @if (session()->has('show_login_and_related_pgs_preview'))
    $('#auth-pages-preview').modal('show');
    @endif

    /**
     *-------------------------------------------------------------
     * Notifications
     *-------------------------------------------------------------
     */
    @if (session('pop_error'))
    pop({msg: '{{ session('pop_error') }}', type: 'error'});
    @endif

    @if (session('pop_warning'))
    pop({msg: '{{ session('pop_warning') }}', type: 'warning'});
    @endif

    @if (session('pop_success'))
    pop({msg: '{{ session('pop_success') }}', type: 'success', title: 'GREAT!'});
    @endif

    @if (session('pop_error_confirm'))
    popConfirm({msg: '{{ session('pop_error_confirm') }}', type: 'error'});
    @endif

    @if (session('pop_warning_confirm'))
    popConfirm({msg: '{{ session('pop_warning_confirm') }}', type: 'warning'});
    @endif

    @if (session('pop_success_confirm'))
    popConfirm({msg: '{{ session('pop_success_confirm') }}', type: 'success', title: 'GREAT!'});
    @endif

    @if (session('flash_info'))
      flash({msg: '{{ session('flash_info') }}', type: 'info'});
    @endif

    @if (session('flash_success'))
      flash({msg: '{{ session('flash_success') }}', type: 'success'});
    @endif

    @if (session('flash_warning'))
      flash({msg: '{{ session('flash_warning') }}', type: 'warning'});
    @endif

    @if (session('flash_error') || session('flash_danger'))
      flash({msg: '{{ session('flash_error') ?: session('flash_danger') }}', type: 'error'});
    @endif

    /**
     *-------------------------------------------------------------
     * Pop-up modals/notifications
     *-------------------------------------------------------------
     */
    function pop(data)
    {
        Toast.fire({
            position: "top-right",
            title: data.title ?? 'Oops...',
            text: data.msg,
            icon: data.type,
            timer: data.timer ?? {{ session('pop_timer') ?? 20000 }}, // You can set timer to 0 to prevent autohide
            showConfirmButton: false,
        });
    }
    
    /**
     *-------------------------------------------------------------
     * Useful js URLs
     *-------------------------------------------------------------
     */
    const set_notice_as_viewed_url = '{{ route("notices.set_viewed") }}';
    const firebase_url = "{{ route('notifications.firebase.update_device_token') }}";
    const event_create_url = "{{ route('events.create') }}";
    const update_hidden_alerts_url = "{{ route('my_account.update_hidden_alerts') }}";
    const clear_hidden_alerts_url = "{{ route('my_account.clear_hidden_alerts') }}";
    const get_table_cols_url = '{{ route("get_table_columns", [":name"]) }}';
    const get_state_url = '{{ route("get_state", [":id"]) }}';
    const get_lga_url = '{{ route("get_lga", [":id"]) }}';
    const get_pre_defined_subjects_url = "{{ route('get_pre_defined_subjects') }}";
    const get_class_students_url = '{{ route("get_class_students", [":id"]) }}';
    const get_class_sections_url = "{{ route('get_class_sections', [':id']) }}";
    const get_teacher_class_sections_url = "{{ route('get_teacher_class_sections', [':id']) }}";
    const get_subject_section_teacher_url = "{{ route('get_subject_section_teacher', [':sub_id', ':sec_id']) }}";
    const get_year_exams_url = "{{ route('get_year_exams', [':year']) }}";
    const get_class_subjects_url = "{{ route('get_class_subjects', [':id']) }}";
    const update_exam_lock_state_url = "{{ route('exams.update_lock_state') }}";
    const update_exam_edit_state_url = "{{ route('exams.update_edit_state') }}";
    const update_staff_data_edit_state_url = "{{ route('users.update_staff_data_edit_state') }}";
    const update_user_blocked_state_url = "{{ route('users.update_user_blocked_state') }}";

</script>
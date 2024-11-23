<script>
    /**
     *-------------------------------------------------------------
     * Reusable swal 2 modal
     *-------------------------------------------------------------
     */
    const Modal = Swal.mixin({
        focusConfirm: false,
        returnFocus: false,
        reverseButtons: true,
        showCancelButton: true,
        cancelButtonText: "No, Cancel",
        customClass: {
            cancelButton: "bg-secondary",
        }
    });

    /**
     *-------------------------------------------------------------
     * Re-usable swal2 toast
     *-------------------------------------------------------------
     */
    const Toast = Swal.mixin({
        toast: true,
        reverseButtons: true,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    /**
     *-------------------------------------------------------------
     * Captilize first letter of a string
     *-------------------------------------------------------------
     */
    function capitalize(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    /**
     *-------------------------------------------------------------
     * Handle notices
     *-------------------------------------------------------------
     */
    function updateNoticeStatus(el){
        var badge = $(el).parents("div.notices").siblings(".card-header").children(".card-title").children(".badge");
        // Remove class 'unviewed', turn off 'click' event handler, and remove iteration indicator.
        $("button#" + el.id).removeClass("unviewed").off("click").siblings(".iteration").remove();
        // Update Badge
        badge.text((badge.text() == 0) ? 0 : badge.text() - 1);
    }
    
    $(document).on("click", ".notices button.unviewed", function(e){
        setNoticeAsViwed(this);
    });

    function setNoticeAsViwed(el){
        var url = '{{ route("notices.set_viewed") }}';
        $.ajax({
            dataType: 'json',
            method: "post",
            data: {
                _token: "{{ csrf_token() }}",
                "id": el.id},
            url: url,
            success: function (resp) {
               return (resp.ok === true) ? updateNoticeStatus(el) : false;
            },
        });
    }

    $(document).on('click', '.pagination a',function(event)
    {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');  
        getNoticesData($(this).attr('href'));
    });

    function getNoticesData(url){
        var status = url.includes('unviewed') ? 'unviewed' : 'viewed';
        const notices = $('.notices').find('#' + status);

        notices.empty().append(`<div class="notices-loading">${noticesLoadingSkin(4, status)}</div>`);

        $.ajax(
        {
            url: url,
            type: "get",
            datatype: "html"
        }).done(function(data){
            notices.replaceWith(data);
            window.location.hash = url;
        }).fail(function(jqXHR, ajaxOptions, thrownError){
            flash({msg: "Sorry, something went wrong.", type: 'error'});
        });
    }

    // Handle haschange when the user changed it manually in the address bar
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var url = window.location.hash.replace('#', '');
            getNoticesData(url);
        }
    });

    function noticesLoadingSkin(duplicate_times, status) {
    let template = "";
    // Starting codes - two opening div's reserved
    var a = `<div class="card m-0 border-bottom-0 text-muted">
                <div class="card-header position-relative">
                    <span class="float-left pr-10 status-styled">` + capitalize(status) + `</span><i class="text-muted float-right name skeleton"></i>
                </div>
                <div class="card-body p-1">
                    <div id="accordion-">`;
    // With iteration indicator
    var bb = `       <div class="card mb-1">
                            <div class="card-header">
                                <h5 class="mb-0 d-flex">
                                <span class="text-muted iteration skeleton mr-1"></span>
                                <button class="btn btn-link w-100 pl-1 p-0 border-left-1 border-left-info">
                                    <span class="float-left pr-10 title skeleton"></span><i class="text-muted float-right time skeleton"></i>
                                </button>
                            </h5>
                         </div>
                    </div>`;
    // Without iteration indicator
    var cc = `  <div class="card mb-1">
                    <div class="card-header">
                        <h5 class="mb-0 d-flex">
                            <button class="btn btn-link w-100 pl-1 p-0 border-left-1 border-left-info">
                                <span class="float-left pr-10 title skeleton"></span><i class="text-muted float-right time skeleton"></i>
                            </button>
                        </h5>
                    </div>
                </div>`;
    // Closing codes - two closing div's for the two opening div's in variable 'a' above
    var d = `       <div class="position-relative pt-2">
                            <span class="float-right">
                                <nav>
                                    <ul class="pagination">
                                        <li class="page-item disabled"><span class="page-link skeleton">‹</span></li>
                                        <li class="page-item disabled"><span class="page-link skeleton"></span></li>
                                        <li class="page-item disabled"><a class="page-link skeleton"></a></li>
                                        <li class="page-item disabled"><a class="page-link skeleton">›</a></li>
                                    </ul>
                                </nav>
                            </span>
                            <span class="float-left showing skeleton"></span>
                        </div>
                    </div>
                </div>
            </div>`;
            
        var b = c = "";
        for (let i = 0; i < duplicate_times; i++) {
            b += bb;
            c += cc;
        }
        
        // With iteration indicator - unviewed notices loading skeleton
        if (status === "unviewed")
            template += a + b + d;
        // Without iteration indicator - viewed notices loading skeleton
        else 
            template += a + c + d;

        return template;
    }
    
    /**
     *-------------------------------------------------------------
     * Update elements properties
     *-------------------------------------------------------------
     */
    function toggleElDisableState(value, selector){
        if(value == 'none')
            return disableElement(selector);
        enableElement(selector);
    } 

    function enableElement(selector){
        $(selector).removeAttr('disabled');
    }
    
    function disableElement(selector){
        $(selector).attr("disabled", 'true');
    }

    /**
     *-------------------------------------------------------------
     * Query builder handler events
     *-------------------------------------------------------------
     */
    $('#return-to-query').click(function(e){
        e.preventDefault();
        return scrollTo('#query-box');
    });

    $("#secured-query").mouseenter(function(e){
        $(this).find("span").removeClass("text-secured");
    });

    $("#secured-query").mouseleave(function(e){
        $(this).find("span").addClass("text-secured");
    });

    function removeClassCursorNotAllowed(selector)
    {
        $(selector).removeClass("cursor-not-allowed");
    }
   
    /**
     *-------------------------------------------------------------
     * Get table columns by table name
     *-------------------------------------------------------------
     */
    function getTableColumns(table_name){
        var btn = $('button[type=submit]');
        toggleElDisableState('NULL', btn);
        var url = '{{ route("get_table_columns", [':name']) }}';
        url = url.replace(':name', table_name);
        var select = $('#select');
        var where = $('.where');
        var orderby_column = $('.orderby_column');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                where.empty();
                where.append('<option disabled selected>where</option><option value="none" selected>none (default)</option>');
                $.each(resp, function (i, data) {
                    if(i === "photo") return true; // Skip photo in where options if set.
                    where.append($('<option>', {
                        value: i,
                        text: data,
                    }));
                });

                orderby_column.empty();
                orderby_column.append('<option value="id" selected>id (default)</option>');
                $.each(resp, function (i, data) {
                    if(i === "photo") return true;
                    orderby_column.append($('<option>', {
                        value: i,
                        text: data,
                    }));
                });
        
                select.empty();
                select.append('<option disabled>select</option><option value="*" selected>all (default)</option>');
                $.each(resp, function (i, data) {
                    select.append($('<option>', {
                        value: i,
                        text: data,
                    }));
                }); 
            }
        });
    }

    function alertInfo(number) {
        Toast.fire({
            position: "top-right",
            title: number + " total records found.",
            icon: "info",
            timer: 5000,
            showConfirmButton: false,
        });
    }

    /**
     *-------------------------------------------------------------
     * Get states by nationality id
     *-------------------------------------------------------------
     */
    function getState(nal_id){
        var url = '{{ route("get_state", [":id"]) }}';
        url = url.replace(':id', nal_id);
        var state = $('#state_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                state.empty();
                $.each(resp, function (i, data) {
                    state.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        });
    }

    /**
     *-------------------------------------------------------------
     * Get LGA or cities by state id
     *-------------------------------------------------------------
     */
    function getLGA(state_id){
        var url = '{{ route("get_lga", [":id"]) }}';
        url = url.replace(':id', state_id);
        var lga = $('#lga_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                lga.empty();
                $.each(resp, function (i, data) {
                    lga.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        });
    }

    /**
     *-------------------------------------------------------------
     * Get subjects by class type id
     *-------------------------------------------------------------
     */
    function getClassTypeSubjects(class_type_id, destination){
        var url = "{{ route('get_class_type_subjects', [':id']) }}";
        url = url.replace(':id', class_type_id);
        var section = destination ? $(destination) : $('#name');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                section.empty();
                $.each(resp, function (i, data) {
                    section.append($('<option><input type="checkbox" name', {
                        value: data,
                        text: data
                    }));
                });
                if (section.hasClass("append-editable-option")){
                    appendEditableOption(".append-editable-option");
                }
            }
        });
    }

    /**
     *-------------------------------------------------------------
     * Get students of a class by class id
     *-------------------------------------------------------------
     */
    function getClassStudents(class_id, destination){
        var url = '{{ route("get_class_students", [":id"]) }}';
        url = url.replace(':id', class_id);
        var section = destination ? $(destination) : $('#students-ids');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                section.empty();
                $.each(resp, function (i, data) {
                    section.append($('<option><input type="checkbox" name', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        });
    }

    /**
     *-------------------------------------------------------------
     * Get sections of a class by a class id
     *-------------------------------------------------------------
     */
    function getClassSections(class_id, destination){
        var url = "{{ route('get_class_sections', [':id']) }}";
        url = url.replace(':id', class_id);
        var section = destination ? $(destination) : $('#section_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                section.empty();
                $.each(resp, function (i, data) {
                    section.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
                if(destination){
                    if(destination.includes('add_all'))
                        section.append('<option value="all" title="All Sections">All</option>');
                    if(destination.includes('add_not_applicable'))
                        section.prepend('<option selected value=" ">Not Applicable</option>');
                }
            }
        });
    }
    
    /**
     *-------------------------------------------------------------
     * Get teachers of a class sections by a class id
     *-------------------------------------------------------------
     */
    function getTeacherClassSections(class_id, destination){
        var url = "{{ route('get_teacher_class_sections', [':id']) }}";
        url = url.replace(':id', class_id);
        var section = destination ? $(destination) : $('#section_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                section.empty();
                $.each(resp, function (i, data) {
                    section.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        });
    }

    /**
     *-------------------------------------------------------------
     * Get subject section teacher
     *-------------------------------------------------------------
     */
    function getSubjectSectionTeacher(subject_id, section_id, destination){
        var url = "{{ route('get_subject_section_teacher', [':sec_id', ':teach_id']) }}";
        url = url.replace(':sec_id', subject_id);
        url = url.replace(':teach_id', section_id);
        var section = destination ? $(destination) : $('#teacher_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                section.children('option').each(function(){
                    if($(this).val() == resp.id){
                        $(this).attr('selected', 'selected');
                        $('#select2-teacher_id-container').text(resp.name).attr('title', resp.name);
                    }
                    else
                      $(this).removeAttr('selected');
                });
            }
        });
    }

    /**
     *-------------------------------------------------------------
     * Get exams of the particular year
     *-------------------------------------------------------------
     */
     function getYearExams(year){
        var url = "{{ route('get_year_exams', [':year']) }}";
        url = url.replace(':year', year);
        var exam = $('#exam_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {console.log(resp)
                exam.empty();
                $.each(resp, function (i, data) {
                    exam.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            },
            error: function(errorThrown){
                exam.empty();
            }
        });
    }

    /**
     *-------------------------------------------------------------
     * Get class subjects
     *-------------------------------------------------------------
     */
    function getClassSubjects(class_id, destination){
        var url = "{{ route('get_class_subjects', [':id']) }}";
        url = url.replace(':id', class_id);
        var subject = destination ? $(destination) : $('#subject_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                subject.empty();
                $.each(resp, function (i, data) {
                    subject.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
                if(destination && destination.includes('add_not_applicable')){
                    subject.prepend('<option selected value=" ">Not Applicable</option>');
                }
            },
            error: function(errorThrown){
                subject.empty();
            }
        });
    }

    /**
     *-------------------------------------------------------------
     * Handler for class sections visibility
     *-------------------------------------------------------------
     */
    function hideShowSection(value, section_id){
        if(value == 'class')
            return unhideSection(section_id);
        return hideSection(section_id);
    }

    function hideSection(section_id){
        var section = section_id ? $(section_id) : $('#section_id');
        return $(section).parents('div.form-group').hide(150);
    }

    function unhideSection(section_id){
        var section = section_id ? $(section_id) : $('#section_id');
        return $(section).parents('div.form-group').show(150);
    }

    /**
     *-------------------------------------------------------------
     * Login and related pages texts and background preview
     *-------------------------------------------------------------
     */
    @if(session()->has('show_login_and_related_pgs_preview'))
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
    function pop(data){
        Toast.fire({
            position: "top-right",
            title: data.title ?? 'Oops...',
            text: data.msg,
            icon: data.type,
            timer: data.timer ?? {{ session('pop_timer') ?? 20000 }}, // You can set timer to 0 to prevent autohide
            showConfirmButton: false,
        });
    }
    
    function popConfirm(data){
        // If the message does not exist in localStorage; show the message.
        // Otherwise; marked as never show it again. Thus, do not show it.
        if(!sessionStorage.getItem(data.msg))
        Toast.fire({
            position: "top-right",
            title: data.title ?? 'Oops...',
            text: data.msg,
            icon: data.type,
            showConfirmButton: true,
            confirmButtonText: "Got it",
            customClass: {
                confirmButton: 'w-100 swal2-restyled',
                footer: 'swal2-footer-class'
            },
            footer: '<button class="btn btn-sm" value="'+ data.msg +'" id="do-not-show-for-this-session">Do not show for this session.</button>'
        });
    }

    $(document).on("click", "#do-not-show-for-this-session", function(e){
        Swal.close();
        var value = this.value;
        // Store the message to session storage, marked as do not show it again for the current browser's session.
        sessionStorage.setItem(value, "do-not-show-for-this-session");
        return flash({msg : "Success. The message will not be shown again for this browser's tab session.", type : 'success'});
    });

    function flash(data){
        toastr.options = {
            "closeButton": true,
            "closeMethod": 'hide',
            "closeDuration": 500,
            "showEasing": getRandomEasingMethod(),
            "hideEasing": getRandomEasingMethod(),
            "closeEasing": getRandomEasingMethod(),
            "showMethod": getRandomShowMethod(),
            "hideMethod": getRandomHideMethod(),
            "positionClass": 'toast-top-right',
            "preventDuplicates": true,
            "progressBar": true,
        }
        // Unblock UI if any
        $.unblockUI();
        displayToast(data.type, data.msg);
    }

    function displayToast(type, message){
        if(type === "success")
            return toastr.success(message);
        else if(type === "info")
            return toastr.info(message)
        else if(type === "error"){
            toastr.options = {
                "timeOut": 0,
                "extendedTimeOut": 0,
                "preventDuplicates": true,
                "closeButton": true,
            }
            return toastr.error(message)
        }
        else if(type === "warning")
            return toastr.info(message)
        else return false;
    }

    /**
     *-------------------------------------------------------------
     * Get random value methods
     *-------------------------------------------------------------
     */
    function getRandomShowMethod() {
        var methods = ["show", "slideDown", "fadeIn"];
        var method = methods[Math.floor(Math.random() * methods.length)];
        return method;
    }

    function getRandomHideMethod() {
        var methods = ["hide", "slideUp", "fadeOut"];
        var method = methods[Math.floor(Math.random() * methods.length)];
        return method;
    }

    function getRandomEasingMethod() {
        var methods = ["swing", "linear"];
        var method = methods[Math.floor(Math.random() * methods.length)];
        return method;
    }

    /**
     *-------------------------------------------------------------
     * Handler methods for confirm operations
     *-------------------------------------------------------------
     */
    function confirmOperation(id) {
        Modal.fire({
            title: "Do you want to proceed?",
            icon: "warning",
            confirmButtonText: "Sure, Proceed",
            customClass: {
                confirmButton: "bg-danger",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $('form#item-confirm-operation-'+id).submit();
            } 
        });
    }

    function confirmDelete(id) {
        Modal.fire({
            title: "Are you sure?",
            text: "This item will be deleted.",
            icon: "warning",
            confirmButtonText: "Sure, Delete",
            customClass: {
                confirmButton: "bg-danger",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $('form#item-delete-'+id).submit();
            } 
        });
    }

    function confirmPermanentDelete(id) {
        Modal.fire({
            title: "Are you sure?",
            text: "This item will be permanently deleted.",
            icon: "warning",
            confirmButtonText: "Sure, Delete Permanently",
            customClass: {
                confirmButton: "bg-danger",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $('form#item-delete-'+id).submit();
            } 
        });
    }

    function confirmPermanentDeleteTwice(id) {
        Modal.fire({
            title: "Just in case",
            text: "Do you really wish do delete this item.",
            icon: "warning",
            confirmButtonText: "Yes, I do",
            customClass: {
                confirmButton: "bg-danger",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                confirmPermanentDelete(id);
            } 
        });
    }

    function confirmForceDelete(id, model) {
        Modal.fire({
            title: "Delete Permanetly?",
            text: "Once deleted, you will not be able to recover this item!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sure, Delete Permanently",
            customClass: {
                confirmButton: "bg-danger",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                return $('form#'+model+'-force-delete-'+id).submit();
            } 
        });
    }

    function confirmPublish(id) {
        Modal.fire({
            title: "Are you sure?",
            text: "This item and its related data (ie., results) will be published (ie., made public to parents, teachers, and/or students).",
            icon: "warning",
            confirmButtonText: "Confirm",
            customClass: {
                confirmButton: "bg-success",
            }
        }).then((result) => {
            if (result.isConfirmed) {
               return $('form#item-publish-'+id).submit();
            } 
        });
    }
    
    /**
     *-------------------------------------------------------------
     * Update exam lock state by exam id
     *-------------------------------------------------------------
     */
    function updateExamLockState(exam_id, el){
        if(sessionStorage.getItem("update-exam-lock-ok") != "false"){
            var url = '{{ route("exams.update_lock_state") }}'
            var value = (el.checked ? 1 : 0);
            $.ajax({
                method: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    "id": exam_id,
                    "locked": value
                },
                url: url,
                success: function (resp) {
                    if(!resp.ok){
                        if(resp.msg)
                            sessionStorage.setItem("resp.msg", resp.msg);
                        return rollbackUpdateExamEditAction(el);
                    } else return true;
                },
                error: function(errorThrown){
                    return rollbackUpdateExamLockAction(el);
                }
            });
        } else {
            removeItemFromSessionStorageAndFlashErrMsg("update-exam-lock-ok");
        }
    }

    function rollbackUpdateExamLockAction(el)
    {
        sessionStorage.setItem("update-exam-lock-ok", "false");
        reverseCheckBoxAction(el);;
    }

    /**
     *-------------------------------------------------------------
     * Update exam edit state
     *-------------------------------------------------------------
     */
    function updateExamEditState(exam_id, el){
        if(sessionStorage.getItem("update-exam-edit-ok") != "false"){
            var url = '{{ route("exams.update_edit_state") }}'
            var value = (el.checked ? 1 : 0);
            $.ajax({
                method: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    "id": exam_id,
                    "editable": value
                },
                url: url,
                success: function (resp) {
                    if(!resp.ok){
                        if(resp.msg)
                            sessionStorage.setItem("resp.msg", resp.msg);
                        return rollbackUpdateExamEditAction(el);
                    } else return true;
                },
                error: function(errorThrown){
                    return rollbackUpdateExamEditAction(el);
                }
            });
        } else {
            removeItemFromSessionStorageAndFlashErrMsg("update-exam-edit-ok");
        }
    }

    function rollbackUpdateExamEditAction(el)
    {
        sessionStorage.setItem("update-exam-edit-ok", "false");
        reverseCheckBoxAction(el);;
    }

    /**
     *-------------------------------------------------------------
     * Update staff data edit state
     *-------------------------------------------------------------
     */
    function updateStaffDataEdtiState(user_id, el){
    if(sessionStorage.getItem("update-staff-data-edit-ok") != "false"){
        var url = '{{ route("users.update_staff_data_edit_state") }}'
        var value = (el.checked ? 1 : 0);
        $.ajax({
            method: "post",
            data: {
                 _token: "{{ csrf_token() }}",
                "id": user_id,
                "staff_data_edit": value
            },
            url: url,
            success: function (resp) {
                if(!resp.ok){
                    if(resp.msg)
                        sessionStorage.setItem("resp.msg", resp.msg);
                    return rollbackUpdateExamEditAction(el);
                } else return true;
            },
            error: function(errorThrown){
                return rollbackUpdateStaffDataEditAction(el);
                }
            });
        } else {
            removeItemFromSessionStorageAndFlashErrMsg("update-staff-data-edit-ok");
        }
    }

    function rollbackUpdateStaffDataEditAction(el)
    {
        sessionStorage.setItem("update-staff-data-edit-ok", "false");
        reverseCheckBoxAction(el);;
    }

    /**
     *-------------------------------------------------------------
     * Update user blocked state
     *-------------------------------------------------------------
     */
   function updateUserBlockedState(user_id, el){
    if(sessionStorage.getItem("update-user-blocked-state-ok") != "false"){
        var url = '{{ route("users.update_user_blocked_state") }}'
        var value = (el.checked ? 1 : 0);
        $.ajax({
            method: "post",
            data: {
                 _token: "{{ csrf_token() }}",
                "id": user_id,
                "blocked": value
            },
            url: url,
            success: function (resp) {
                if(!resp.ok){
                    if(resp.msg)
                        sessionStorage.setItem("resp.msg", resp.msg);
                    return rollbackUpdateExamEditAction(el);
                } else return true;
            },
            error: function(errorThrown){
                return rollbackUpdateUserBlockedState(el);
                }
            });
        } else {
            removeItemFromSessionStorageAndFlashErrMsg("update-user-blocked-state-ok");
        }
    }

    function rollbackUpdateUserBlockedState(el)
    {
        sessionStorage.setItem("update-user-blocked-state-ok", "false");
        reverseCheckBoxAction(el);;
        return;
    }

    function reverseCheckBoxAction(el){
        return $(el).removeAttr("checked").click();
    }

    function removeItemFromSessionStorageAndFlashErrMsg(item){
        sessionStorage.removeItem(item);
        let msg = sessionStorage.getItem("resp.msg") ?? "Something went wrong. Please try again. If the problem persists try reloading the page.";
        flash({msg: msg, type: 'error'});
        return;
    }

    /**
     *-------------------------------------------------------------
     * Confirm reset
     *-------------------------------------------------------------
     */
    function confirmReset(id, href = null) {
        Modal.fire({
            title: "Are you sure?",
            text: "This will reset this item to default state",
            icon: "warning",
            confirmButtonText: "Sure, Reset",
            customClass: {
                confirmButton: "bg-primary",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if (id == null && href != null)
                    return window.location = href;
                $('form#item-reset-'+id).submit();
            } 
        });
    }

    function confirmResetTwice(id, href = null) {
        Modal.fire({
            title: "Just in case",
            text: "Do you really want to reset this item?",
            icon: "warning",
            confirmButtonText: "Sure, Reset",
            customClass: {
                confirmButton: "bg-primary",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                confirmReset(id, href);
            } 
        });
    }

    function confirmResetPassword(href, default_pass) {
        Modal.fire({
            title: "Are you sure?",
            text: "This will reset password to " + default_pass ?? 'user',
            icon: "warning",
            confirmButtonText: "Sure, Reset",
            customClass: {
                confirmButton: "bg-primary",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if (href)
                    return window.location = href;
                return flash({msg: 'Oops! Invalid URL', type: 'info'});
            } 
        });
    }

    /**
     *-------------------------------------------------------------
     * Confirm reset password anker element click handler
     *-------------------------------------------------------------
     */
    $('a.needs-reset-pass-confirmation').on('click', function(ev){
        ev.preventDefault();
        var href = $(this).data('href');
        var default_pass = $(this).data('default_pass');
        confirmResetPassword(href, default_pass);
    });

    /**
     *-------------------------------------------------------------
     * Payment pay handler on form submit
     *-------------------------------------------------------------
     */
    $('form.ajax-pay').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this), 'store');

        // Retrieve IDS
        var form_id = $(this).attr('id');
        var td_amt = $('td#amt-' + form_id);
        var td_amt_paid = $('td#amt_paid-' + form_id);
        var td_bal = $('td#bal-' + form_id);
        var input = $('#val-' + form_id);

        // Get Values
        var amt = parseInt(td_amt.data('amount'));
        var amt_paid = parseInt(td_amt_paid.data('amount'));
        var amt_input = parseInt(input.val());

        // Update Values
        amt_paid = amt_paid + amt_input;
        var bal = amt - amt_paid;

        td_bal.text('' + bal);
        td_amt_paid.text(''+amt_paid).data('amount', '' + amt_paid);
        input.attr('max', bal);
        bal < 1 ? $('#' + form_id).fadeOut('slow').remove() : '';
    });

    /**
     *-------------------------------------------------------------
     * Ajax store, ajax update and ajax register form handler 
     * for submit event
     *-------------------------------------------------------------
     */
    $(document).find('form.ajax-store').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this), 'store');
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
    });

    $(document).find('form.ajax-update').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this));
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
    });

    $(document).find('form#ajax-reg').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this), 'store');
        $('#ajax-reg-t-0').get(0).click();
    });

    $(document).find('.download-receipt').on('click', function(ev){
        ev.preventDefault();
        $.get($(this).attr('href'));
        flash({msg: '{{ "Download in Progress" }}', type: 'info'});
    });

    function reloadDiv(div, url = window.location.href){
        url = url + ' ' + div;
        $(div).parent().load(url);
    }

    /**
     *-------------------------------------------------------------
     * Submit form (the base method for submitting forms)
     *-------------------------------------------------------------
     */
    function submitForm(form, formType){
        // Normal form Submit button
        var btn = form.find('button[type=submit]');
        
        // Wizard form - Steps validation Submit button
        if(!btn.length)
            btn = form.find('.actions li:last-child a');
          
        const btn_html = btn.html();

        if(btn.hasClass("needs-time-counter"))
            setTimeCounter(btn);

        disableBtn(btn);
        var ajaxOptions = {
            url:form.attr('action'),
            type:'POST',
            cache:false,
            processData:false,
            dataType:'json',
            contentType:false,
            data:new FormData(form[0])
        };
        var req = $.ajax(ajaxOptions);
        req.done(function(resp){
            closeConfirmMessageDeleteModal();

            if(btn.hasClass("needs-time-counter"))
                stopTimeCounter();

            hideAjaxAlert();
            enableBtn(btn);

            (resp.ok && resp.msg) 
            ? (resp.pop ? (typeof resp.pop_timer != 'undefined' ? pop({msg:resp.msg, type:'success', timer:resp.pop_timer}) : pop({msg:resp.msg, type:'success'})) : flash({msg:resp.msg, type:'success'})) 
            : (resp.pop ? (typeof resp.pop_timer != 'undefined' ? pop({msg:resp.msg, type:'success', timer:resp.pop_timer}) : pop({msg:resp.msg, type:'success'})) : flash({msg:resp.msg, type:'error'}));
            
            formType == 'store' ? clearForm(form) : '';
            btn.html(btn_html);

            if(resp.scrollToBtn){
                scrollTo(btn);
                return resp;
            }

            if(resp.complete)
                return resp;
                            
            scrollTo('body');

            return resp;
        });
        req.fail(function(e){
            closeConfirmMessageDeleteModal();
            unblockUI();

            if(btn.hasClass("needs-time-counter"))
                stopTimeCounter();

            if (e.status == 422){
                var errors = e.responseJSON.errors;
                displayAjaxErr(errors);
            }
            if(e.status == 500){
               displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])
            }
            if(e.status == 404){
               displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])
            }

            enableBtn(btn, btn_html);
            return e.status;
        });
    }

    /**
     *-------------------------------------------------------------
     * Time counter
     *-------------------------------------------------------------
     */
    var sec = 0;
    var time_interval;

    function pad(val) {
        return val > 9 ? val : "0" + val;
    }

    function setTimeCounter(el) {
        $('<span id="time-counter" class="status-styled d-inline-block"></span>').insertAfter(el);
        startTimeCounter();
    }

    function updateTimer() {
        $("#time-counter").html(pad(parseInt(sec / 60, 10)) + ":" + pad(++sec % 60) + " elaspsed.");
    }

    function startTimeCounter(){
        time_interval = setInterval(updateTimer, 1000);
    }

    function stopTimeCounter() {
        return clearInterval(time_interval);
    }

    function closeConfirmMessageDeleteModal(){
        return $("#confirm-message-delete").find('.close').click(); // Close message action modal if open.
    }

    /**
     *-------------------------------------------------------------
     * Disable anker elements with 'is-disabled' class
     *-------------------------------------------------------------
     */
    disableAnchor();
    
    function disableAnchor(){
        let anchor = $('a.is-disabled');
        if(anchor.length){
            anchor.addClass('cursor-not-allowed');
            anchor.click(function(e){
            e.preventDefault();
                return false;
            });
        }
    }

    /**
     *-------------------------------------------------------------
     * Disable or enable button.
     *-------------------------------------------------------------
     */
    function disableBtn(btn){
        var btnText = btn.data('text') ? btn.data('text') : 'Submitting...';
        btn.prop('disabled', true).html('<i class="material-symbols-rounded mr-2 spinner">progress_activity</i>' + btnText);
    }

    function enableBtn(btn, btnHtml){
        // var btnText = btn.data('text') ? btn.data('text') : 'Submit Form';
        // btnHtml = btnHtml ? btnHtml : btnText + '<i class="material-symbols-rounded ml-2">send</i>';
        btn.prop('disabled', false).html(btnHtml);
    }

    /**
     *-------------------------------------------------------------
     * Display ajax errors
     *-------------------------------------------------------------
     */
    function displayAjaxErr(errors){
        $('#ajax-alert').show().html('<div class="alert alert-danger border-0 alert-dismissible" id="ajax-msg"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>');
        $.each(errors, function(k, v){
            $('#ajax-msg').append('<span><i class="material-symbols-rounded pb-2px">hdr_strong</i> '+ v +'</span><br/>');
        });
        scrollTo('body');
    }

    /**
     *-------------------------------------------------------------
     * Scroll to element
     *-------------------------------------------------------------
     */
    function scrollTo(el){
        $('html, body').animate({
            scrollTop: $(el).offset().top
        }, 2000);
    }

    /**
     *-------------------------------------------------------------
     * Scroll to bottom
     *-------------------------------------------------------------
     */
    function scrollToBottom(){
        const scrollingElement = (document.scrollingElement || document.body);
        $('html, body').animate({
            scrollTop: scrollingElement.scrollHeight
        }, 2000);
    }

    /**
     *-------------------------------------------------------------
     * Hide ajax alert
     *-------------------------------------------------------------
     */
    function hideAjaxAlert(){
        $('#ajax-alert').hide();
    }

    /**
     *-------------------------------------------------------------
     * Clear form inputs
     *-------------------------------------------------------------
     */
    function clearForm(form){
        form.find('.checked').removeClass('checked');
        form.find('.select, .select-search').val([]).select2({ placeholder: 'Select...'});
        form[0].reset();
    }

    /**
     *-------------------------------------------------------------
     * Print this plugin elements handler
     *-------------------------------------------------------------
     */
    $('.print-this').addClass('position-relative').append('<button class="position-absolute btn btn-sm pb-1 pt-1 right-0 bottom-0 print-none this" type="button">Print</button>');
    $('.print-this button.this').on("click", function () {
        var parent = $(this).parents('.print-this');

        if (getPreferredTheme() === "dark" || (getPreferredTheme() === "auto" && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            Modal.fire({
                text: "Please, print while in light color mode for best visual and print quality.",
                icon: "warning",
                showDenyButton: true,
                denyButtonText: "Let me switch",
                confirmButtonText: "Just Print",
                customClass: {
                    confirmButton: "bg-warning",
                    denyButton: "bg-info",
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    doElementPrint(parent);
                } else if (result.isDenied) {
                    scrollTo('body');
                }
            });
        } else {
            doElementPrint(parent);
        }
    });

    function doElementPrint(element) {
        if(element.hasClass('card-collapsed'))
            element.removeClass('.card-collapsed');
            
        element.printThis({
            base: "https://jasonday.github.io/printThis/",
        });
    }

    // Resize chart to available page area
    function resizeChartInstances () {
        for (let id in Chart.instances) {
            Chart.instances[id].resize();
        }
    }

    /**
     *-------------------------------------------------------------
     * Firebase route url
     *-------------------------------------------------------------
     */
    const firebase_url = "{{ route('notifications.firebase.update_device_token') }}";

    /**
     *-------------------------------------------------------------
     * Url for adding event to the DB
     *-------------------------------------------------------------
     */
    const event_create_url = "{{ route('events.create') }}";
</script>
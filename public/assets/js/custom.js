/**
 *-------------------------------------------------------------
 * Block UI method
 *-------------------------------------------------------------
 */
function blockUI(message = "Please wait...", text_color = "orange") {
    return $.blockUI({
        message: '<i class="mr-1 m-auto material-symbols-rounded spinner">progress_activity</i> ' + message,
        css: {
            padding: "3px",
            color: text_color,
            backgroundColor: "rgba(189, 189, 189, 0.05)",
            border: "none",
        },
    });
}

/**
 *-------------------------------------------------------------
 * Initialize auto size plugin for textarea
 *-------------------------------------------------------------
 */
autosize($('textarea'));

/**
 *-------------------------------------------------------------
 * Unblock UI method
 *-------------------------------------------------------------
 */
function unblockUI() {
    return $.unblockUI();
}

$(window).on("load", unblockUI);

$(document).ready(function () {
    $("form.page-block").submit(function (e) {
        blockUI();
    });

    $(".date-pick").datepicker({
        format: "mm/dd/yyyy",
        autoclose: true,
    });

    $(".date-pick-day-none").datepicker({
        format: "M, yyyy",
        autoclose: true,
    });

    /**
     *-------------------------------------------------------------
     * Handle user type change on select.
     *-------------------------------------------------------------
     */
    $("select#user_type").on("change", function () {
        let parent_data = $(this).parents("fieldset").find("#parent-data");
        let parent_data_inputs = $("#parent-data input");
        let last_tab = $(".steps ul li.last");
        let submit_btn = $(".actions ul li:last-child");
        let navigation_btns = $(".actions ul li:not(:last-child)");
        // Middle vertical and horizontal aligned line that separates the steps headers.
        let steps_line_separator = $(".wizard > .steps > ul > li.current");
        // Get the class of the selected class type option
        let option_class = $(this).find(":selected").attr("class");
        // If the option class is parent show extra parent's info, and enable the input fields.
        if (option_class == "parent") {
            last_tab.hide();
            parent_data.show("150");
            navigation_btns.hide();
            submit_btn.show();
            steps_line_separator.addClass("d-li-current-none");
            parent_data_inputs.removeAttr("disabled");
        } else {
            // If the option class is not parent hide extra parent's info, and disable input fileds.
            // Preventing them from being submitted hence not validated.
            last_tab.show();
            parent_data.hide("150");
            navigation_btns.show();
            submit_btn.hide();
            steps_line_separator.removeClass("d-li-current-none");
            parent_data_inputs.attr("disabled", "true");

            const next_btn = $('.wizard>.actions>ul>li + li');
            if (next_btn.hasClass('disabled'))
                next_btn.removeClass('disabled').removeAttr('disabled').children('a').removeClass('disabled');
        }
    });

    /**
     *-------------------------------------------------------------
     * Handle marks conversion
     *-------------------------------------------------------------
     */
    const wrapper = $(".items-wrapper");

    $(document).on("click", "#add-item", function (e) {
        e.preventDefault();
        var item_input_number = $(this).siblings('input').val();

        if (item_input_number < 0) {
            for (let i = 0; i > item_input_number; i--) {
                let items = wrapper.find('.item');
                if (items.length === 1)
                    // If is the first item; do not remove.
                    return;
                else
                    items.last().remove();
            }
        } else {
            for (let i = 0; i < item_input_number; i++) {
                // Clone the first item and append it to the wrapper
                var item = $(".item:first").clone(false, false).appendTo(wrapper);
                // Remove input value
                item.find(".item-input").val("");
                // Remove output text
                item.find(".item-output").text("");
                // Show remove only to cloned items
                item.find(".icon-close").show(250);
                // Disable item copier
                item.find(".item-copy").prop('disabled', true);
            }
        }
    });

    $(document).on("click", ".item-copy", function (e) {
        e.preventDefault();
        var output_els = $(this).siblings(".item-output");
        let combined_text = '';
        output_els.each(function(index, el) {
            combined_text += $(el).text() + '\n';
        });
        text = combined_text.trim();
        copyToClipboard(text, this);
    });

    // Function to copy text to clipboard using the Clipboard API
    function copyToClipboard(text, element) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(
                () => {
                    // Hint as copied
                    showTooltipTitle(element, 'Copied');
                },
                (err) => {
                    showTooltipTitle(element, 'Failed! Try copying manually');
                    console.error('Failed to copy text: ', err);
                }
            );
        } else {
            // Fallback for browsers that do not support the Clipboard API
            const temp = $('<textarea>').val(text).appendTo('body').select();

            try {
                document.execCommand('copy');
                // Hint as copied
                showTooltipTitle(element, 'Copied');
            } catch (err) {
                showTooltipTitle(element, 'Failed! Try copying manually');
                console.error('Failed to copy text: ', err);
            }

            temp.remove();
        }

        function showTooltipTitle(element, title) {
            $(element).attr('data-original-title', title).tooltip('show');
        }

        // Reset back to default
        $(element).on("mouseleave", function () {
            $(element).attr('data-original-title', $(this).data('title') ?? 'Copy').tooltip('hide')
        });
    }

    $(document).on("click", "#btn-convert", function (e) {
        spin(this.children);
        var from_out_of = $("#from-out-of");
        var to_out_of = $("#to-out-of");
        var inputs = $(".item-input");

        if (!validateOutOfs(from_out_of, to_out_of)) {
            spinStop(this.children);
            return;
        }

        var from_val = from_out_of.val();
        var to_val = to_out_of.val();

        inputs.each(function (index, input) {
            var output = $(this).parent().siblings().find(".item-output");
            var item_copier = $(this).parent().siblings().find(".item-copy");

            // If input value has been validated
            if (inputValueIsValid(this, from_val)) {
                output.text(convert($(this).val(), from_val, to_val));
                item_copier.prop('disabled', false);
            }
        });
        spinStop(this.children);
    });

    function spin(el) {
        return $(el).addClass("spinner");
    }

    function spinStop(el) {
        return $(el).removeClass("spinner");
    }

    function validateOutOfs(from_out_of_el, to_out_of_el) {
        if (isEmpty(from_out_of_el) || isEmpty(to_out_of_el)) {
            if (isEmpty(from_out_of_el)) isRequired(from_out_of_el);
            else removeIsInvalidClass(from_out_of_el);

            if (isEmpty(to_out_of_el)) isRequired(to_out_of_el);
            else removeIsInvalidClass(to_out_of_el);
            return false;
        } else {
            removeIsInvalidClass(from_out_of_el);
            removeIsInvalidClass(to_out_of_el);
            return true;
        }
    }

    function inputValueIsValid(el, from_out_of_val) {
        // If the input has no mark
        if (isEmpty(el)) isRequired(el);

        // If the input has mark
        if (!isEmpty(el)) {
            // If the input has a value (mark) greater then its denominator (from_out_of)
            if (parseInt($(el).val()) > parseInt(from_out_of_val)) {
                //console.log($(el).val() + ' dd ' + from_out_of_val)
                isInvalid(
                    el,
                    "The mark must not be greater than " + from_out_of_val,
                    from_out_of_val
                );
            } else {
                removeIsInvalidClass(el);
                return true;
            }
        }
    }

    // Remove invalid feedback for any input element on any input event
    $(document).on("input", "input", function (e) {
        if (isEmpty(this)) addIsInvalidClass(this);
        else removeIsInvalidClass(this);
    });

    function isRequired(el) {
        return $(el)
            .addClass("is-invalid")
            .siblings(".invalid-feedback")
            .children("span")
            .text("This field is required");
    }

    function removeIsInvalidClass(el) {
        return $(el).removeClass("is-invalid");
    }

    function addIsInvalidClass(el) {
        return $(el).addClass("is-invalid");
    }

    function isInvalid(el, msg) {
        return $(el)
            .addClass("is-invalid")
            .siblings(".invalid-feedback")
            .children("span")
            .text(msg);
    }

    function isEmpty(el) {
        return $(el).val() === "";
    }

    function convert(mark, from_out_of, to_out_of) {
        var result = (mark / from_out_of) * to_out_of;
        return Math.round(result);
    }
});

/**
 *-------------------------------------------------------------
 * Toggle full screen handler
 *-------------------------------------------------------------
 */
$(".full-screen-handle").on("click", function (event) {
    event.preventDefault();
    if (
        (document.fullScreenElement && document.fullScreenElement !== null) ||
        (!document.mozFullScreen && !document.webkitIsFullScreen)
    ) {
        if (document.documentElement.requestFullScreen) {
            document.documentElement.requestFullScreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullScreen) {
            document.documentElement.webkitRequestFullScreen(
                Element.ALLOW_KEYBOARD_INPUT
            );
        }
        $(this).find('a').text('fullscreen_exit').attr('title', 'Exit Full Screen');
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
        $(this).find('a').text('fullscreen').attr('title', 'Request Full Screen');
    }
});

/**
 *-------------------------------------------------------------
 * Dashboard couter up for numbers (counts)
 *-------------------------------------------------------------
 */
const counterUp = window.counterUp.default;
const callback = (entries) => {
    entries.forEach((entry) => {
        const el = entry.target;
        if (entry.isIntersecting && !el.classList.contains("is-visible")) {
            counterUp(el, {
                duration: 2000,
                delay: 16,
            });
            el.classList.add("is-visible");
        }
    });
}

const IO = new IntersectionObserver(callback, { threshold: 1 });

var elements = document.querySelectorAll(".counter");

elements.forEach((el) => {
    IO.observe(el);
});
// Counter ends

// Initialize popover everywhere
function initializePopover() {
    return $('[data-toggle="popover"]').popover();
};

// Initialize tootip everywhere
function initializeTooltip() {
    return $('[data-toggle="tooltip"]').tooltip();
};

function hideAllPopover() {
    return $('[data-toggle="popover"]').popover('hide');
}

initializePopover();
initializeTooltip();

/**
 *-------------------------------------------------------------
 * Student ID Card print chexboxes
 *-------------------------------------------------------------
 */
$(".st-id-checkbox").click(function () {
    // Get form
    const form = $("form.print-selected");
    // Get all checked checkboxes count
    var checked_count = $(":checkbox:checked").length;
    if (checked_count === 0) {
        form.find("button[type='submit']")
            .attr("disabled", "true")
            .addClass("cursor-not-allowed");
    } else {
        form.find("button[type='submit']")
            .removeAttr("disabled")
            .removeClass("cursor-not-allowed");
    }
    // Update count
    form.find("#checked-count").html(checked_count);
});

function enableElement(selector) {
    $(selector).prop("disabled", false);
}

function disableElement(selector) {
    $(selector).prop("disabled", true);
}

function updateSectionDisableState() {
    var values = $("#students-ids").val();
    return values.length
        ? disableElement("#sec_id_add_not_applicable")
        : enableElement("#sec_id_add_not_applicable");
}

function hide(selector, duration = 0) {
    $(selector).hide(duration);
}

function show(selector, duration = 0) {
    $(selector).show(duration);
}

/**
 *-------------------------------------------------------------
 * Message form (hide & show)
 *-------------------------------------------------------------
 */
function showFormCard() {
    show(".card#create-message", 500);
    hide("button#new-message", 50);
}

function hideFormCard() {
    hide(".card#create-message", 150);
    show("button#new-message", 250);
}

/**
 *-------------------------------------------------------------
 * Toggle element disabled state method
 *-------------------------------------------------------------
 */
function toggleElementDisabledState(value, target_el) {
    if (value.length) {
        $(target_el).attr("disabled", true);
    } else {
        $(target_el).attr("disabled", false);
    }
}

/**
 *-------------------------------------------------------------
 * Validate students selection when creating or sending msgs
 *-------------------------------------------------------------
 */
$(document).on("change", "select#user_types-ids", function (e) {
    validateStudentsSelection(this);
});

function validateStudentsSelection(el) {
    const active_students_value = 0;
    const grad_students_value = 1;
    const all_students_value = 'students';
    var values = $(el).val();
    let students_selection_count = 0;

    values.forEach(function (value) {
        if (value == active_students_value || value == grad_students_value || value == all_students_value)
            students_selection_count++;
    });

    if (students_selection_count > 1) {
        $("#user_types-ids").val("").trigger("change");
        pop({
            msg: "Please, select only one option under students user type group.",
            type: "info",
        });
    }
}

/**
 *-------------------------------------------------------------
 * Add has editable option to the options list
 *-------------------------------------------------------------
 */
$(document).ready(function () {
    appendEditableOption();
});

function appendEditableOption(selector = ".has-editable-option") {
    $(selector).siblings("input.edit-option").remove();
    $(selector).append('<option class="editable" value="other">Other</option>').attr("onchange", "handleEditableOption(this);")
        .parent()
        .append('<input class="form-control edit-option display-none text-capitalize" placeholder="Please Specify">');
}

/**
 *-------------------------------------------------------------
 * Inline function attached above to the select element
 *-------------------------------------------------------------
 */
function handleEditableOption(el) {
    var selected = $(el).children("option:selected", this).attr("class");

    if (selected == "editable") {
        $(el).siblings(".edit-option").show(150);

        $(".edit-option").keyup(function () {
            var editText = $(el).siblings(".edit-option").val();
            $(el).children("option.editable").val(editText);
            $(el).children("option.editable").html(editText);
        });
    } else {
        $(el).siblings(".edit-option").hide();
    }
}

/**
 *-------------------------------------------------------------
 * Promotion - Graduates
 *-------------------------------------------------------------
 */
$("button.opt-all-as-graduate").on("click", function (ev) {
    // Select form
    const form = $(this).siblings("form.page-block");
    // Select graduated option by value
    form.find('select option[value="G"]').prop("selected", true);
    // Display 'Graduated' text matching the selected option
    form.find(".select2-selection__rendered").text("Graduated");
});

/**
 *-------------------------------------------------------------
 * Do not show this again button handler. 
 * The codes will apply necessary changes to any element 
 * with class 'has-do-not-show-again-button'
 *-------------------------------------------------------------
 */
$(".has-do-not-show-again-button").append('<button type="button" class="btn btn-sm btn-info pt-0 pb-0 pr-1 pl-1 position-absolute right-0 bottom-0 font-size-xs do-not-show-again" onclick="doNotShowAgain(this)">Do not show again</button>');

function doNotShowAgain(el) {
    var parent_el = $(el).parents('.has-do-not-show-again-button');

    var id = $(parent_el).attr("id");
    if (typeof id == 'undefined')
        return console.warn('Missing required unique id attribute for the element.');

    if ($('[id=' + JSON.stringify(id) + ']').length > 1)
        return console.warn('Multiple ' + id + ' id attributes were found. Id needs to be unique for each element.')

    removeElement(parent_el);
    var ids = JSON.parse(window.localStorage.getItem("do_not_show_els_with_these_ids_again")) ?? [];

    ids.push(id);
    window.localStorage.setItem("do_not_show_els_with_these_ids_again", JSON.stringify(ids));
    flash({ msg: 'Success, it will not be shown.', type: 'info' });

    const timeout = 5000;
    window.setTimeout(() => {
        flash({ msg: 'To see it again, clear this browser local storage data for this site.', type: 'info' });
    }, timeout);
};

/**
 *-------------------------------------------------------------
 * Update do not show again state method.
 * Hide elements that needs not to be shown again.
 *-------------------------------------------------------------
 */
function updatedoNotShowAgainElsState() {
    $(document).ready(function () {
        var values = JSON.parse(window.localStorage.getItem("do_not_show_els_with_these_ids_again")) ?? [];
        values.forEach(function (value) {
            if (value != '')
                $("#" + value).remove(0);
        });
    });
}

updatedoNotShowAgainElsState();

/**
 *-------------------------------------------------------------
 * Remove element method - with animation
 *-------------------------------------------------------------
 */
function removeElement(el) {
    $(el).animate({
        'height': "0px"
    }, 500, function () {
        $(el).remove();
    });
}

/**
 *-------------------------------------------------------------
 * Handle load go to bottom btn click event
 *-------------------------------------------------------------
 */
const go_to_bottom_btn = $('.go-to-bottom button');

go_to_bottom_btn.on('click', function (e) {
    e.preventDefault();
    scrollToBottom();
});

$(window).scroll(function () {
    var scrollBottom = $(window).scrollTop() + $(window).height();
    const scrollingElement = (document.scrollingElement || document.body);
    const offset_from_bottom = 300;

    if (scrollingElement.scrollHeight - offset_from_bottom < scrollBottom)
        go_to_bottom_btn.fadeOut();
    else
        go_to_bottom_btn.fadeIn();
});

/**
 *-------------------------------------------------------------
 * Handle exam number formats displaying on class type change
 *-------------------------------------------------------------
 */
$(document).on("change", "select#class-type", function (e) {
    let value = this.value;
    $('.number-formats div').removeClass('d-flex').addClass('d-none');
    $('.number-format-' + value).removeClass('d-none').addClass('d-flex');
});

/**
 *-------------------------------------------------------------
 * Handle CA displaying on exam category change
 *-------------------------------------------------------------
 */
$(document).on("change", "select#category", function (e) {
    let value = this.value;
    let ids = $(this).data('summative_exm_cat_ids');

    if (ids) {
        var has_id = Object.values(ids).includes(parseInt(value))
        if (has_id)
            return $('.ca-setup').show(150).find("input[type='number']").attr("required", "required");
        else
            return $('.ca-setup').hide(150).find("input[type='number']").removeAttr("required");
    }
});

/**
 *-------------------------------------------------------------
 * System Sound Notification
 *-------------------------------------------------------------
 */
function playNotificationSound(sound_name, condition = false) {
    if (condition && $(error_sound).data('allow_system_sounds') === 1) {
        const sound = new Audio(`${$(error_sound).data('base_url')}/${sound_name}`);
        sound.play();
    }
}

/**
 *-------------------------------------------------------------
 * Handle CA and Exam marks savings on page refresh
 *-------------------------------------------------------------
 */
const error_sound = document.getElementById('notification-sounds');

$(document).on("input", "table.data-table input", function (e) {
    const sound_name = 'error-call-to-attention.mp3';
    var max_value = parseFloat($(this).attr('max'));
    var value = parseFloat($(this).val());
    var name_attr = $(this).attr('name');

    $("table.data-table input").removeAttr('style').removeClass('is-invalid'); // Remove red colored border line from all inputs

    if (value > max_value) {
        playNotificationSound(sound_name, true);
        sessionStorage.setItem(name_attr, '') // Re-set value to empty if previously stored.
        return $(this).val('').blur().attr('style', 'border: 1px solid rgb(151, 23, 23) !important'); // Add red colored border to the current input.
    }

    return sessionStorage.setItem(name_attr, value); // Store value in storage
});

// Check if page was reloaded
const page_accessed_by_reload = (
    window.performance
        .getEntriesByType('navigation')
        .map((nav) => nav.type)
        .includes('reload')
);

// If the user has input some values and the page become inaccessible anymore (ie., unresponsive),
// and the user is forced to refresh the page. Restore the available values.
if ($("table.data-table input").length > 0) {
    if (page_accessed_by_reload) {
        for (let key in sessionStorage) {
            if (sessionStorage.hasOwnProperty(key) && (key.startsWith('cw_') || key.startsWith('hw_') || key.startsWith('tt_'))) {
                // For Continous Assessment (CA)
                $('.ca-tbody').find('input[name="' + key + '"]').val(sessionStorage[key]); // Restore values if available on page refresh
            }
            if (sessionStorage.hasOwnProperty(key) && key.startsWith('exm_')) {
                // For Exam
                $('.exam-tbody').find('input[name="' + key + '"]').val(sessionStorage[key]); // Restore values if available on page refresh
            }
        }
    }
}

/**
 *-------------------------------------------------------------
 * Handle auth pages texts and bg preview buttons
 *-------------------------------------------------------------
 */
var auth_pages_bg_file_input = $('#login-and-related-pgs-bg-input');
var auth_pages_preview_btns = $('div.auth-pages-preview');

$(auth_pages_bg_file_input).change(function (ev) {
    // If the input has at least one file (photo)
    if (auth_pages_bg_file_input.get(0).files.length >= 1) {
        auth_pages_preview_btns.find('select').prop('disabled', false);
        auth_pages_preview_btns.find('input').prop('disabled', false);
    } else {
        auth_pages_preview_btns.find('select').prop('disabled', true);
        auth_pages_preview_btns.find('input').prop('disabled', true);
    }
});

/**
 *-------------------------------------------------------------
 * Refresh button click event (target; show on standalone mode)
 *-------------------------------------------------------------
 */
$(document).on("click", ".refresh", function (e) {
    $(this).html('<i class="material-symbols-rounded mt-auto mb-auto spinner">refresh</i>');
    return window.location.reload();
});
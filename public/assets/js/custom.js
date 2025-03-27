'use strict';

/**
 *-------------------------------------------------------------
 * Set up the CSRF token header for all AJAX requests in this app
 *-------------------------------------------------------------
 */
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

/**
 *-------------------------------------------------------------
 * Initialize the float head plugin
 *-------------------------------------------------------------
 */
$(() => $('table.float-head').floatThead());

$('table.float-head').on("floatThead", function (e, isFloated, $floatContainer) {
    $("table tr.hide").toggle(!isFloated);
});

/**
 *-------------------------------------------------------------
 * Block UI method
 *-------------------------------------------------------------
 */
const blockUI = (message = "Please wait...", textColor = "orange") =>
    $.blockUI({
        message: `<i class="mr-1 m-auto material-symbols-rounded spinner">progress_activity</i> ${message}`,
        css: {
            padding: "3px",
            color: textColor,
            backgroundColor: "rgba(189, 189, 189, 0.05)",
            border: "none",
        },
    });

/**
 *-------------------------------------------------------------
 * Unblock UI method
 *-------------------------------------------------------------
 */
const unblockUI = () => $.unblockUI();

$(window).on("load", unblockUI);

/**
 *-------------------------------------------------------------
 * Initialize auto size plugin for textarea
 *-------------------------------------------------------------
 */
autosize($('textarea'));

/**
 *-------------------------------------------------------------
 * Form and Date Picker Handlers
 *-------------------------------------------------------------
 */
$(document).ready(() => {
    $("form.page-block").on("submit", () => blockUI());

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
        const $fieldset = $(this).closest("fieldset");
        const $parentData = $fieldset.find("#parent-data");
        const $parentDataInputs = $("#parent-data input");
        const $lastTab = $(".steps ul li.last");
        const $submitBtn = $(".actions ul li:last-child");
        const $navigationBtns = $(".actions ul li").not(":last-child");
        // Middle vertical and horizontal aligned line that separates the steps headers.
        const $stepsLineSeparator = $(".wizard > .steps > ul > li.current");
        // Get the class of the selected class type option
        const optionClass = $(this).find(":selected").attr("class");

        if (optionClass === "parent") {
            $lastTab.hide();
            $parentData.show("150");
            $navigationBtns.hide();
            $submitBtn.show();
            $stepsLineSeparator.addClass("d-li-current-none");
            $parentDataInputs.prop("disabled", false);
        } else {
            $lastTab.show();
            $parentData.hide("150");
            $navigationBtns.show();
            $submitBtn.hide();
            $stepsLineSeparator.removeClass("d-li-current-none");
            $parentDataInputs.attr("disabled", true);

            const $nextBtn = $('.wizard > .actions > ul > li + li');
            if ($nextBtn.hasClass("disabled")) {
                $nextBtn.removeClass("disabled").removeAttr("disabled").children("a").removeClass("disabled");
            }
        }
    });

    /**
     *-------------------------------------------------------------
     * Handle marks conversion
     *-------------------------------------------------------------
     */
    const $wrapper = $(".items-wrapper");

    $(document).on("click", "#add-item", function (e) {
        e.preventDefault();
        const itemInputNumber = $(this).siblings("input").val();

        if (itemInputNumber < 0) {
            for (let i = 0; i > itemInputNumber; i--) {
                const $items = $wrapper.find(".item");
                if ($items.length === 1) return; // Do not remove if only one item remains.
                $items.last().remove();
            }
        } else {
            for (let i = 0; i < itemInputNumber; i++) {
                const $item = $(".item:first").clone().appendTo($wrapper);
                $item.find(".item-input").val("");
                $item.find(".item-output").text("");
                $item.find(".icon-close").show(250);
                $item.find(".item-copy").prop("disabled", true);
            }
        }
    });

    $(document).on("click", ".item-copy", function (e) {
        e.preventDefault();
        const $outputEls = $(this).siblings(".item-output");
        let combinedText = "";
        $outputEls.each((_, el) => {
            combinedText += $(el).text() + "\n";
        });
        const text = combinedText.trim();
        copyToClipboard(text, this);
    });

    // Function to copy text to clipboard using the Clipboard API
    const copyToClipboard = (text, element) => {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(
                () => showTooltipTitle(element),
                (err) => {
                    showTooltipTitle(element, "Failed! Try copying manually");
                    console.error("Failed to copy text:", err);
                }
            );
        } else {
            const $temp = $("<textarea>").val(text).appendTo("body").select();
            try {
                document.execCommand("copy");
                showTooltipTitle(element);
            } catch (err) {
                showTooltipTitle(element, "Failed! Try copying manually");
                console.error("Failed to copy text:", err);
            }
            $temp.remove();
        }

        function showTooltipTitle(el, title = "Copied") {
            title == "Copied" ? $(el).text("check") : $(el).text("error");
            $(el).attr("data-original-title", title).tooltip("show");
        }

        setTimeout(() => {
            const title = $(element).data("title") ?? "Copy";
            $(element).text("content_copy");
            $(element).attr("data-original-title", title).tooltip("hide");
        }, 3000);
    };

    $(document).on("click", "#btn-convert", function (e) {
        spin(this.children);
        const $fromOutOf = $("#from-out-of");
        const $toOutOf = $("#to-out-of");
        const $inputs = $(".item-input");

        if (!validateOutOfs($fromOutOf, $toOutOf)) {
            spinStop(this.children);
            return;
        }

        const fromVal = $fromOutOf.val();
        const toVal = $toOutOf.val();

        $inputs.each(function () {
            const $output = $(this).parent().siblings().find(".item-output");
            const $itemCopier = $(this).parent().siblings().find(".item-copy");

            if (inputValueIsValid(this, fromVal)) {
                $output.text(convert($(this).val(), fromVal, toVal));
                $itemCopier.prop("disabled", false);
            }
        });
        spinStop(this.children);
    });

    const spin = (el) => $(el).addClass("spinner");
    const spinStop = (el) => $(el).removeClass("spinner");

    const validateOutOfs = ($fromEl, $toEl) => {
        if (isEmpty($fromEl) || isEmpty($toEl)) {
            isEmpty($fromEl) ? isRequired($fromEl) : removeIsInvalidClass($fromEl);
            isEmpty($toEl) ? isRequired($toEl) : removeIsInvalidClass($toEl);
            return false;
        }
        removeIsInvalidClass($fromEl);
        removeIsInvalidClass($toEl);
        return true;
    };

    const inputValueIsValid = (el, fromVal) => {
        if (isEmpty($(el))) isRequired($(el));
        if (!isEmpty($(el))) {
            if (parseInt($(el).val(), 10) > parseInt(fromVal, 10)) {
                isInvalid(el, `The mark must not be greater than ${fromVal}`);
            } else {
                removeIsInvalidClass(el);
                return true;
            }
        }
    };

    $(document).on("input", "input", function () {
        isEmpty($(this)) ? addIsInvalidClass(this) : removeIsInvalidClass(this);
    });

    const isRequired = ($el) =>
        $el
            .addClass("is-invalid")
            .siblings(".invalid-feedback")
            .children("span")
            .text("This field is required");

    const removeIsInvalidClass = (el) => $(el).removeClass("is-invalid");
    const addIsInvalidClass = (el) => $(el).addClass("is-invalid");
    const isInvalid = (el, msg) =>
        $(el)
            .addClass("is-invalid")
            .siblings(".invalid-feedback")
            .children("span")
            .text(msg);

    const isEmpty = ($el) => $el.val() === "";
    const convert = (mark, fromOutOf, toOutOf) =>
        Math.round((mark / fromOutOf) * toOutOf);
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
        $(this).find("a").text("fullscreen_exit").attr("title", "Exit Full Screen");
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
        $(this).find("a").text("fullscreen").attr("title", "Request Full Screen");
    }
});

/**
 *-------------------------------------------------------------
 * Dashboard counter up for numbers (counts)
 *-------------------------------------------------------------
 */
const counterUp = window.counterUp.default;
const callback = (entries) => {
    entries.forEach((entry) => {
        const el = entry.target;
        if (entry.isIntersecting && !$(el).hasClass("is-visible")) {
            counterUp(el, {
                duration: 2000,
                delay: 16,
            });
            $(el).addClass("is-visible");
        }
    });
};

const IO = new IntersectionObserver(callback, { threshold: 1 });
$(".counter").each((_, el) => IO.observe(el));

/**
 *-------------------------------------------------------------
 * Initialize popover and tooltip everywhere
 *-------------------------------------------------------------
 */
const initializePopover = () => $('[data-toggle="popover"]').popover();
const initializeTooltip = () => $('[data-toggle="tooltip"]').tooltip();
const hideAllPopover = () => $('[data-toggle="popover"]').popover("hide");

$(document).ready(() => {
    initializePopover();
    initializeTooltip();
});

/**
 *-------------------------------------------------------------
 * Student ID Card print checkboxes
 *-------------------------------------------------------------
 */
$(".st-id-checkbox").on("click", function () {
    const $form = $("form.print-selected");
    const checkedCount = $(":checkbox:checked").length;
    $form.find("button[type='submit']")
        .prop("disabled", checkedCount === 0)
        .toggleClass("cursor-not-allowed", checkedCount === 0);
    $form.find("#checked-count").html(checkedCount);
});

const enableElement = (selector) => $(selector).prop("disabled", false);
const disableElement = (selector) => $(selector).prop("disabled", true);
const updateSectionDisableState = () => {
    const values = $("#students-ids").val();
    return values.length
        ? disableElement("#sec_id_add_not_applicable")
        : enableElement("#sec_id_add_not_applicable");
};

const hide = (selector, duration = 0) => $(selector).hide(duration);
const show = (selector, duration = 0) => $(selector).show(duration);

/**
 *-------------------------------------------------------------
 * Message form (hide & show)
 *-------------------------------------------------------------
 */
const showFormCard = () => {
    show(".card#create-message", 500);
    hide("button#new-message", 50);
};
const hideFormCard = () => {
    hide(".card#create-message", 150);
    show("button#new-message", 250);
};

/**
 *-------------------------------------------------------------
 * Toggle element disabled state method
 *-------------------------------------------------------------
 */
const toggleElementDisabledState = (value, targetEl) => {
    $(targetEl).attr("disabled", value.length > 0);
};

/**
 *-------------------------------------------------------------
 * Validate students selection when creating or sending msgs
 *-------------------------------------------------------------
 */
$(document).on("change", "select#user_types-ids", function () {
    const activeStudents = 0;
    const gradStudents = 1;
    const allStudents = "students";
    const values = $(this).val();
    let count = 0;

    values.forEach((value) => {
        if ([activeStudents, gradStudents, allStudents].includes(value)) count++;
    });

    if (count > 1) {
        $("#user_types-ids").val("").trigger("change");
        pop({ msg: "Please, select only one option under students user type group.", type: "info" });
    }
});

/**
 *-------------------------------------------------------------
 * Promotion - Graduates
 *-------------------------------------------------------------
 */
$("button.opt-all-as-graduate").on("click", function () {
    const $form = $(this).siblings("form.page-block");
    $form.find('select option[value="G"]').prop("selected", true);
    $form.find(".select2-selection__rendered").text("Graduated");
});

/**
 *-------------------------------------------------------------
 * Do not show this again button handler.
 *-------------------------------------------------------------
 */
const $doNotShowButton = $(`<button type="button" class="btn btn-sm btn-info pt-0 pb-0 pr-1 pl-1 position-absolute right-0 font-size-xs do-not-show-again" style="bottom: -10px;" onclick="doNotShowAgain(this)">Do not show again</button>`);
$(".has-do-not-show-again-button").append($doNotShowButton);

const doNotShowAgain = (el) => {
    const $parent = $(el).closest(".has-do-not-show-again-button");
    const id = $parent.attr("id");
    if (!id) return console.warn("Missing required unique id attribute for the element.");
    if ($(`[id="${id}"]`).length > 1)
        return console.warn(`Multiple ${id} id attributes were found. Id needs to be unique.`);
    let ids = JSON.parse(Cookies.get("do_not_show_els_with_these_ids_again") || "[]");
    ids.push(id);
    Cookies.set("do_not_show_els_with_these_ids_again", JSON.stringify(ids));
    updateHiddenIDs(ids, $parent);
};

const updateHiddenIDs = (ids, $el) => {
    const $btn = $el.find("button.do-not-show-again");
    $btn.hide();
    $.ajax({
        url: update_hidden_alerts_url,
        method: "POST",
        data: { hidden_alert_ids: ids },
        success: (resp) => {
            if (resp.ok && resp.msg) {
                removeElement($el);
                flash({ msg: 'Done. To view this again, go to <strong>My Account</strong> to reveal all hidden messages.', type: 'info' });
                return console.log(resp.msg);
            }
            $btn.show(250);
            flash({ msg: "Something went wrong, can't hide alert.", type: "warning" });
            console.warn("Something went wrong.");
        },
        error: (xhr, status, error) => {
            $btn.show(250);
            flash({ msg: "Failed to update hidden alert IDs.", type: "error" });
            console.error("Failed to update hidden alert IDs.", status, error);
        },
    });
};

/**
 *-------------------------------------------------------------
 * Handle alert messages reveal on button click action in settings.
 *-------------------------------------------------------------
 */
$("button#clear-do-not-show-again-alert-msgs").click(function () {
    Modal.fire({
        title: "Are you sure?",
        text: "This will reveal all hidden alert messages.",
        icon: "warning",
        confirmButtonText: "Sure",
        customClass: { confirmButton: "bg-danger" },
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: clear_hidden_alerts_url,
                method: "POST",
                success: (response) => {
                    Cookies.remove("do_not_show_els_with_these_ids_again");
                    flash({ msg: "Success. All alert messages are now displayed.", type: "success" });
                },
                error: (xhr, status, error) => {
                    flash({ msg: "Something went wrong, cannot clear IDs.", type: "error" });
                    console.error("Failed to clear hidden alert IDs data.", status, error);
                },
            });
        }
    });
});

const updatedoNotShowAgainElsState = () => {
    $(document).ready(() => {
        const ids = JSON.parse(Cookies.get("do_not_show_els_with_these_ids_again") || "[]");
        ids.forEach((id) => {
            if (id) $(`#${id}`).remove();
        });
    });
};

updatedoNotShowAgainElsState();

/**
 *-------------------------------------------------------------
 * Remove element method - with animation
 *-------------------------------------------------------------
 */
const removeElement = ($el) => {
    $el.animate({ height: "0px" }, 500, function () {
        $(this).remove();
    });
};

/**
 *-------------------------------------------------------------
 * Handle go to bottom btn click event
 *-------------------------------------------------------------
 */
$(function () {
    // Cache the "go to bottom" button element using a more modern variable name
    const $goToBottomBtn = $('.go-to-bottom button');

    $goToBottomBtn.on('click', (e) => {
        e.preventDefault();
        scrollToBottom();
    });

    $(window).on('scroll', () => {
        const scrollBottom = $(window).scrollTop() + $(window).height();
        const scrollingElement = document.scrollingElement || document.body;
        const offsetFromBottom = 300;

        if (scrollingElement.scrollHeight - offsetFromBottom < scrollBottom) {
            $goToBottomBtn.fadeOut();
        } else {
            $goToBottomBtn.fadeIn();
        }
    });
});

/**
 *-------------------------------------------------------------
 * Handle exam number formats displaying on class type change
 *-------------------------------------------------------------
 */
$(document).on("change", "select#class-type", function () {
    const value = this.value;
    $(".number-formats div").removeClass("d-flex").addClass("d-none");
    $(`.number-format-${value}`).removeClass("d-none").addClass("d-flex");
});

/**
 *-------------------------------------------------------------
 * Handle CA displaying on exam category change
 *-------------------------------------------------------------
 */
$(document).on("change", "select#category", function () {
    const value = this.value;
    const ids = $(this).data("summative_exm_cat_ids");
    if (ids) {
        const hasId = Object.values(ids).includes(parseInt(value, 10));
        if (hasId)
            $(".ca-setup").show(150).find("input[type='number']").attr("required", "required");
        else
            $(".ca-setup").hide(150).find("input[type='number']").removeAttr("required");
    }
});

/**
 *-------------------------------------------------------------
 * System Sound Notification
 *-------------------------------------------------------------
 */
const errorSoundElement = document.getElementById("notification-sounds");
const notificationSound = new Audio();

const playNotificationSound = (soundName, condition = false) => {
    if (condition && $(errorSoundElement).data("allow_system_sounds") === 1) {
        notificationSound.src = `${$(errorSoundElement).data("base_url")}/${soundName}`;
        notificationSound.play();
    }
};

$(document).on("input", "table.data-table input", function () {
    const soundName = "error-call-to-attention.mp3";
    const maxVal = parseFloat($(this).attr("max"));
    const value = parseFloat($(this).val());
    const nameAttr = $(this).attr("name");

    if (value > maxVal) {
        playNotificationSound(soundName, true);
        sessionStorage.setItem(nameAttr, "");
        $(this).val("").blur().attr("style", "border: 1px solid rgb(151, 23, 23) !important");
        return;
    } else {
        $(this).removeAttr("style");
    }
    sessionStorage.setItem(nameAttr, value);
});

// Restore values on page reload
const pageReloaded = window.performance.getEntriesByType("navigation").some((nav) => nav.type === "reload");
if ($("table.data-table input").length > 0 && pageReloaded) {
    Object.keys(sessionStorage).forEach((key) => {
        if (key.startsWith("cw_") || key.startsWith("hw_") || key.startsWith("tt_"))
            $('.ca-tbody').find(`input[name="${key}"]`).val(sessionStorage[key]);
        if (key.startsWith("exm_"))
            $('.exam-tbody').find(`input[name="${key}"]`).val(sessionStorage[key]);
    });
}

/**
 *-------------------------------------------------------------
 * Handle auth pages texts and bg preview buttons
 *-------------------------------------------------------------
 */
const $authPagesInput = $("#login-and-related-pgs-bg-input");
const $authPagesPreviewBtns = $("div.auth-pages-preview");

$authPagesInput.on("change", function () {
    if (this.files.length >= 1) {
        $authPagesPreviewBtns.find("select, input").prop("disabled", false);
    } else {
        $authPagesPreviewBtns.find("select, input").prop("disabled", true);
    }
});

/**
 *-------------------------------------------------------------
 * Refresh button click event (target; show on standalone mode)
 *-------------------------------------------------------------
 */
$(document).on("click", ".refresh", function (e) {
    e.preventDefault();
    $(this).html('<i class="material-symbols-rounded mt-auto mb-auto spinner">refresh</i>');
    window.location.reload();
});

/**
 *-------------------------------------------------------------
 * Build and initialize JQuery calendar
 *-------------------------------------------------------------
 */
(function ($) {
    "use strict";
    const date = new Date();
    const today = date.getDate();
    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    $(document).ready(function () {
        // Event listeners
        $(".right-button").on("click", () => nextYear(date));
        $(".left-button").on("click", () => prevYear(date));
        $(".month").on("click", function () { monthClick($(this), date); });
        $("#add-button").on("click", () => newEvent(date));
        // Set the current month as active
        $(".months-row > td").eq(date.getMonth()).addClass("active-month");
        // Initialize the calendar and show events
        initCalendar(date);
        const events = checkEvents(today, date.getMonth() + 1, date.getFullYear());
        showEvents(events, months[date.getMonth()], today);
    });

    const initCalendar = (date) => {
        $(".events-container").empty();

        const $tbody = $(".tbody").empty();
        const month = date.getMonth();
        const year = date.getFullYear();
        const dayCount = daysInMonth(month, year);
        const today = date.getDate();

        date.setDate(1);
        const firstDay = date.getDay();

        let $row = $("<tr class='table-row'></tr>");
        for (let i = 0; i < 35 + firstDay; i++) {
            const day = i - firstDay + 1;
            const $currDate = $("<td class='table-date'></td>");
            if (i % 7 === 0) {
                $tbody.append($row);
                $row = $("<tr class='table-row'></tr>");
            }
            if (i < firstDay || day > dayCount) {
                $currDate.addClass("nil");
            } else {
                $currDate.text(day);
                const events = checkEvents(day, month + 1, year);
                if (today === day && $(".active-date").length === 0) {
                    $currDate.addClass("active-date");
                    showEvents(events, months[month], day);
                }
                if (events.length !== 0) $currDate.addClass("event-date");
                $currDate.on("click", (e) => dateClick(events, months[month], day, e));
            }
            $row.append($currDate);
        }
        $tbody.append($row);
        $(".year").text(year);
    };

    const daysInMonth = (month, year) => new Date(year, month + 1, 0).getDate();

    const dateClick = (events, month, day, e) => {
        $(".events-container").show(250);
        $("#dialog").hide(250);
        $(".active-date").removeClass("active-date");
        $(e.target).addClass("active-date");
        showEvents(events, month, day);
    };

    const monthClick = (monthElement, date) => {
        $(".events-container").show(250);
        $("#dialog").hide(250);
        $(".active-month").removeClass("active-month");
        monthElement.addClass("active-month");
        const newMonth = $(".month").index(monthElement);
        date.setMonth(newMonth);
        initCalendar(date);
    };

    const nextYear = (date) => {
        $("#dialog").hide(250);
        const newYear = date.getFullYear() + 1;
        $(".year").text(newYear);
        date.setFullYear(newYear);
        initCalendar(date);
    };

    const prevYear = (date) => {
        $("#dialog").hide(250);
        const newYear = date.getFullYear() - 1;
        $(".year").text(newYear);
        date.setFullYear(newYear);
        initCalendar(date);
    };

    const newEvent = (date) => {
        if ($(".active-date").length === 0) return;
        // Remove red error input on click
        $("input").on("click", function () { $(this).removeClass("error-input"); });
        // Empty inputs and hide events
        $("#dialog input[type=text], #dialog textarea").val("");
        $(".events-container").hide(250);
        $("#dialog").show(250);

        $("#cancel-button").on("click", () => {
            $("#name, #count").removeClass("error-input");
            $("#dialog").hide(250);
            $(".events-container").show(250);
        });

        $("#ok-button").off().on("click", () => {
            const name = $("#name").val().trim();
            const count = $("#count").val().trim();
            const day = parseInt($(".active-date").text(), 10);
            // Basic form validation
            if (name.length === 0) {
                $("#name").addClass("error-input");
            } else if (count.length === 0) {
                $("#count").addClass("error-input");
            } else {
                $("#dialog").hide(250);
                newEventJson(name, count, date, day);
                date.setDate(day);
                initCalendar(date);
            }
        });
    };

    const newEventJson = (name, count, date, day) => {
        const event = {
            name,
            description: count,
            year: date.getFullYear(),
            month: date.getMonth() + 1,
            day,
        };
        events_data.events.push(event);
        addEventToDb(event);
    };

    const addEventToDb = (event) => {
        $.ajax({
            type: "POST",
            url: event_create_url,
            dataType: "json",
            data: event,
            success: (data) => {
                $(".events-container").html(data.msg).addClass('text-success');
            },
            error: (errorThrown) => {
                console.error(errorThrown);
                $(".events-container").text("Something went wrong. Event add failed!").addClass('text-danger');
            },
        });
    };

    // Display all events for a selected date
    const showEvents = (events, month, day) => {
        const $container = $(".events-container").empty().show(250);
        if (events.length === 0) {
            const $card = $("<div class='event-card'></div>");
            const $eventName = $("<div class='event-name'>There are no Events Planned for " + month + " " + day + ".</div>");
            $card.css({ "border-left": "10px solid #FF1744" }).append($eventName);
            $container.append($card);
        } else {
            events.forEach((event) => {
                const $card = $("<div class='event-card'></div>");
                const $eventName = $("<div class='event-name'>" + event.name + ":</div>");
                const $eventDescription = $("<div class='event-description pl-1'>" + event.description + ".</div>");
                let $eventStatus = "";
                switch (event.status) {
                    case "cancelled":
                        $eventStatus = $("<div class='event-cancelled text-danger pl-1'>(Cancelled)</div>");
                        break;
                    case "active":
                        $eventStatus = $("<div class='event-active text-orange pl-1'>(Active now)</div>");
                        break;
                    case "completed":
                        $eventStatus = $("<div class='event-completed text-success pl-1'>(Completed)</div>");
                        break;
                    default:
                        break;
                }
                $card.append($eventName).append($eventStatus).append($eventDescription);
                $container.append($card);
            });
        }
    };

    const checkEvents = (day, month, year) => {
        return typeof events_data !== 'undefined' ? events_data.events.filter(event => event.day === day && event.month === month && event.year === year) : [];
    };
})(jQuery);

/**
 *-------------------------------------------------------------
 * Reusable swal2 modal and toast
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
    },
});

const Toast = Swal.mixin({
    toast: true,
    reverseButtons: true,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
});

/**
 *-------------------------------------------------------------
 * Capitalize first letter of a string
 *-------------------------------------------------------------
 */
const capitalize = (string) =>
    string.charAt(0).toUpperCase() + string.slice(1);

/**
 *-------------------------------------------------------------
 * Handle notices
 *-------------------------------------------------------------
 */
const updateNoticeStatus = (el) => {
    const $badge = $(el).closest("div.notices").siblings(".card-header").find(".card-title .badge");
    // Remove class 'unviewed', turn off 'click' event handler, and remove iteration indicator.
    $(`#${el.id}`).removeClass("unviewed").off("click").siblings(".iteration").remove();
    // Update Badge
    $badge.text($badge.text() == 0 ? 0 : $badge.text() - 1);
};

$(document).on("click", ".notices button.unviewed", function () {
    setNoticeAsViwed(this);
});

const setNoticeAsViwed = (el) => {
    $.ajax({
        dataType: "json",
        method: "post",
        data: { id: el.id },
        url: set_notice_as_viewed_url,
        success: (resp) => (resp.ok === true ? updateNoticeStatus(el) : false),
    });
};

$(document).on("click", ".pagination a", function (event) {
    event.preventDefault();
    $("li").removeClass("active");
    $(this).parent("li").addClass("active");
    getNoticesData($(this).attr("href"));
});

const getNoticesData = (url) => {
    const status = url.includes("unviewed") ? "unviewed" : "viewed";
    const $notices = $(".notices").find(`#${status}`);
    $notices.empty().append(`<div class="notices-loading">${noticesLoadingSkin(4, status)}</div>`);
    $.ajax({
        url,
        type: "get",
        datatype: "html",
    })
        .done((data) => {
            $notices.replaceWith(data);
            window.location.hash = url;
        })
        .fail(() => {
            flash({ msg: "Sorry, something went wrong.", type: "error" });
        });
};

$(window).on("hashchange", () => {
    if (window.location.hash) {
        const url = window.location.hash.replace("#", "");
        getNoticesData(url);
    }
});

const noticesLoadingSkin = (duplicateTimes, status) => {
    let template = "";
    const a =
        `<div class="card m-0 border-bottom-0 text-muted">
        <div class="card-header position-relative">
            <span class="float-left pr-10 status-styled">${capitalize(status)}</span><i class="text-muted float-right name skeleton"></i>
        </div>
        <div class="card-body p-1">`;
    // With iteration indicator 
    const bb =
        `<div class="card mb-1">
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
    const cc =
        `<div class="card mb-1">
                <div class="card-header">
                    <h5 class="mb-0 d-flex">
                        <button class="btn btn-link w-100 pl-1 p-0 border-left-1 border-left-info">
                            <span class="float-left pr-10 title skeleton"></span><i class="text-muted float-right time skeleton"></i>
                        </button>
                    </h5>
                </div>
            </div>`;
    const d =
        `<div class="position-relative pt-2">
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
    </div>`;

    let b = "", c = "";
    for (let i = 0; i < duplicateTimes; i++) {
        b += bb;
        c += cc;
    }
    template += status === "unviewed" ? a + b + d : a + c + d;
    return template;
};

/**
 *-------------------------------------------------------------
 * Update elements properties (enable/disable)
 *-------------------------------------------------------------
 */
const toggleElDisableState = (value, selector) =>
    value === "none" ? disableElement(selector) : enableElement(selector);

/**
 *-------------------------------------------------------------
 * Query builder handler events
 *-------------------------------------------------------------
 */
$("#return-to-query").on("click", (e) => {
    e.preventDefault();
    scrollTo("#query-box");
});

$("#secured-query")
    .on("mouseenter", function () {
        $(this).find("span").removeClass("text-secured");
    })
    .on("mouseleave", function () {
        $(this).find("span").addClass("text-secured");
    });

const removeClassCursorNotAllowed = (selector) =>
    $(selector).removeClass("cursor-not-allowed");

/**
 *-------------------------------------------------------------
 * Get table columns by table name
 *-------------------------------------------------------------
 */
function getTableColumns(tableName) {
    const $btn = $("button[type=submit]");
    toggleElDisableState("NULL", $btn);
    let url = get_table_cols_url.replace(":name", tableName);
    const $select = $("#select");
    const $where = $(".where");
    const $orderby = $(".orderby_column");

    $.ajax({
        dataType: "json",
        url,
        success: (resp) => {
            $where.empty().append('<option disabled selected>where</option><option value="none" selected>none (default)</option>');
            $.each(resp, (i, data) => {
                if (i !== "photo") $where.append($("<option>", { value: i, text: data }));
            });
            $orderby.empty().append('<option value="id" selected>id (default)</option>');
            $.each(resp, (i, data) => {
                if (i !== "photo") $orderby.append($("<option>", { value: i, text: data }));
            });
            $select.empty().append('<option disabled>select</option><option value="*" selected>all (default)</option>');
            $.each(resp, (i, data) => {
                $select.append($("<option>", { value: i, text: data }));
            });
        },
    });
}

const alertInfo = (number) => {
    Toast.fire({
        position: "top-right",
        title: `${number} total records found.`,
        icon: "info",
        timer: 5000,
        showConfirmButton: false,
    });
};

/**
 *-------------------------------------------------------------
 * Get states by nationality id
 *-------------------------------------------------------------
 */
function getState(nalId, destination) {
    let url = get_state_url.replace(":id", nalId);
    const $state = destination ? $(destination) : $("#state_id");
    $.ajax({
        dataType: "json",
        url,
        success: (resp) => {
            $state.empty();
            $.each(resp, (i, data) => {
                $state.append($("<option>", { value: data.id, text: data.name }));
            });
        },
    });
}

/**
 *-------------------------------------------------------------
 * Get LGA or cities by state id
 *-------------------------------------------------------------
 */
function getLGA(stateId, destination) {
    let url = get_lga_url.replace(":id", stateId);
    const $lga = destination ? $(destination) : $("#lga_id");
    $.ajax({
        dataType: "json",
        url,
        success: (resp) => {
            $lga.empty();
            $.each(resp, (i, data) => {
                $lga.append($("<option>", { value: data.id, text: data.name }));
            });
        },
    });
}

/**
 *-------------------------------------------------------------
 * Get subjects by class type id
 *-------------------------------------------------------------
 */
function getPreDefinedSubjects(destination) {
    const url = get_pre_def_subjects_url;
    const $section = destination ? $(destination) : $("#name");
    $.ajax({
        dataType: "json",
        url,
        success: (resp) => {
            $section.empty();
            $.each(resp, (i, data) => {
                $section.append($("<option>", { value: data, text: data }));
            });
            if ($section.hasClass("append-editable-option")) {
                appendEditableOption(".append-editable-option");
            }
        },
    });
}

/**
 *-------------------------------------------------------------
 * Get students of a class by class id
 *-------------------------------------------------------------
 */
function getClassStudents(classId, destination) {
    let url = get_class_students_url.replace(":id", classId);
    const $section = destination ? $(destination) : $("#students-ids");
    $.ajax({
        dataType: "json",
        url,
        success: (resp) => {
            $section.empty();
            $.each(resp, (i, data) => {
                $section.append($("<option>", { value: data.id, text: data.name }));
            });
        },
    });
}

/**
 *-------------------------------------------------------------
 * Get sections of a class by a class id
 *-------------------------------------------------------------
 */
function getClassSections(classId, destination) {
    let url = get_class_sections_url.replace(":id", classId);
    const $section = destination ? $(destination) : $("#section_id");
    $.ajax({
        dataType: "json",
        url,
        success: (resp) => {
            $section.empty();
            $.each(resp, (i, data) => {
                $section.append($("<option>", { value: data.id, text: data.name }));
            });
            if (destination) {
                if (destination.includes("add_all"))
                    $section.append('<option value="all" title="All Sections">All</option>');
                if (destination.includes("add_not_applicable"))
                    $section.prepend('<option selected value=" ">Not Applicable</option>');
            }
        },
        error: () => {
            $section.empty();
        },
    });
}

/**
 *-------------------------------------------------------------
 * Get teachers of a class sections by a class id
 *-------------------------------------------------------------
 */
function getTeacherClassSections(classId, destination) {
    let url = get_teacher_class_sections_url.replace(":id", classId);
    const $section = destination ? $(destination) : $("#section_id");
    $.ajax({
        dataType: "json",
        url,
        success: (resp) => {
            $section.empty();
            $.each(resp, (i, data) => {
                $section.append($("<option>", { value: data.id, text: data.name }));
            });
        },
    });
}

/**
 *-------------------------------------------------------------
 * Get subject section teacher
 *-------------------------------------------------------------
 */
function getSubjectSectionTeacher(subjectId, sectionId, destination) {
    let url = get_subject_section_teacher_url.replace(":sub_id", subjectId).replace(":sec_id", sectionId);
    const $section = destination ? $(destination) : $("#teacher_id");
    $.ajax({
        dataType: "json",
        url,
        success: (resp) => {
            $section.children("option").each(function () {
                if ($(this).val() == resp.id) {
                    $(this).attr("selected", "selected");
                    $("#select2-teacher_id-container").text(resp.name).attr("title", resp.name);
                } else {
                    $(this).removeAttr("selected");
                }
            });
        },
    });
}

/**
 *-------------------------------------------------------------
 * Get exams of the particular year
 *-------------------------------------------------------------
 */
function getYearExams(year) {
    let url = get_year_exms_url.replace(":year", year);
    const $exam = $("#exam_id");
    $.ajax({
        dataType: "json",
        url,
        success: (resp) => {
            $exam.empty();
            $.each(resp, (i, data) => {
                $exam.append($("<option>", { value: data.id, text: data.name }));
            });
        },
        error: () => {
            $exam.empty();
        },
    });
}

/**
 *-------------------------------------------------------------
 * Get class subjects
 *-------------------------------------------------------------
 */
function getClassSubjects(classId, destination) {
    let url = get_class_subjects_url.replace(":id", classId);
    const $subject = destination ? $(destination) : $("#subject_id");
    $.ajax({
        dataType: "json",
        url,
        success: (resp) => {
            $subject.empty();
            $.each(resp, (i, data) => {
                $subject.append($("<option>", { value: data.id, text: data.name }));
            });
            if (destination && destination.includes("add_not_applicable")) {
                $subject.prepend('<option selected value=" ">Not Applicable</option>');
            }
        },
        error: () => {
            $subject.empty();
        },
    });
}

/**
 *-------------------------------------------------------------
 * Handler for class sections visibility
 *-------------------------------------------------------------
 */
function hideShowSection(value, sectionId) {
    return value === "class" ? unhideSection(sectionId) : hideSection(sectionId);
}

function hideSection(sectionId) {
    const $section = sectionId ? $(sectionId) : $("#section_id");
    return $section.closest('div.form-group').hide(150);
}

function unhideSection(sectionId) {
    const $section = sectionId ? $(sectionId) : $("#section_id");
    return $section.closest('div.form-group').show(150);
}

function popConfirm(data) {
    if (!sessionStorage.getItem(data.msg))
        Toast.fire({
            position: "top-right",
            title: data.title ?? "Oops...",
            text: data.msg,
            icon: data.type,
            showConfirmButton: true,
            confirmButtonText: "Got it",
            customClass: {
                confirmButton: "w-100 swal2-restyled",
                footer: "swal2-footer-class",
            },
            footer: `<button class="btn btn-sm" value="${data.msg}" id="do-not-show-for-this-session">Do not show for this session.</button>`,
        });
}

$(document).on("click", "#do-not-show-for-this-session", function () {
    Swal.close();
    sessionStorage.setItem(this.value, "do-not-show-for-this-session");
    flash({ msg: "Success. The message will not be shown again for this browser's tab session.", type: "success" });
});

function flash(data) {
    toastr.options = {
        closeButton: true,
        closeMethod: 'hide',
        closeDuration: 500,
        showEasing: getRandomEasingMethod(),
        hideEasing: getRandomEasingMethod(),
        closeEasing: getRandomEasingMethod(),
        showMethod: getRandomShowMethod(),
        hideMethod: getRandomHideMethod(),
        positionClass: 'toast-top-right',
        preventDuplicates: true,
        progressBar: true,
    };
    $.unblockUI();
    displayToast(data.type, data.msg);
}

function displayToast(type, message) {
    if (type === "success")
        return toastr.success(message);
    else if (type === "info")
        return toastr.info(message);
    else if (type === "error") {
        toastr.options = {
            timeOut: 0,
            extendedTimeOut: 0,
            preventDuplicates: true,
            closeButton: true,
        };
        return toastr.error(message);
    } else if (type === "warning")
        return toastr.info(message);
    else return false;
}

function getRandomShowMethod() {
    const methods = ["show", "slideDown", "fadeIn"];
    return methods[Math.floor(Math.random() * methods.length)];
}

function getRandomHideMethod() {
    const methods = ["hide", "slideUp", "fadeOut"];
    return methods[Math.floor(Math.random() * methods.length)];
}

function getRandomEasingMethod() {
    const methods = ["swing", "linear"];
    return methods[Math.floor(Math.random() * methods.length)];
}

/**
 *-------------------------------------------------------------
 * Confirm operations handlers
 *-------------------------------------------------------------
 */
function confirmOperation(id) {
    Modal.fire({
        title: "Do you want to proceed?",
        icon: "warning",
        confirmButtonText: "Sure, Proceed",
        customClass: { confirmButton: "bg-danger" },
    }).then((result) => {
        if (result.isConfirmed) {
            $(`form#item-confirm-operation-${id}`).submit();
        }
    });
}

function confirmDelete(id) {
    Modal.fire({
        title: "Are you sure?",
        text: "This item will be deleted.",
        icon: "warning",
        confirmButtonText: "Sure, Delete",
        customClass: { confirmButton: "bg-danger" },
    }).then((result) => {
        if (result.isConfirmed) {
            $(`form#item-delete-${id}`).submit();
        }
    });
}

function confirmPermanentDelete(id) {
    Modal.fire({
        title: "Are you sure?",
        text: "This item will be permanently deleted.",
        icon: "warning",
        confirmButtonText: "Sure, Delete Permanently",
        customClass: { confirmButton: "bg-danger" },
    }).then((result) => {
        if (result.isConfirmed) {
            $(`form#item-delete-${id}`).submit();
        }
    });
}

function confirmPermanentDeleteTwice(id) {
    Modal.fire({
        title: "Just in case",
        text: "Do you really wish to delete this item?",
        icon: "warning",
        confirmButtonText: "Yes, I do",
        customClass: { confirmButton: "bg-danger" },
    }).then((result) => {
        if (result.isConfirmed) {
            confirmPermanentDelete(id);
        }
    });
}

function confirmForceDelete(id, model) {
    Modal.fire({
        title: "Delete Permanently?",
        text: "Once deleted, you will not be able to recover this item!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sure, Delete Permanently",
        customClass: { confirmButton: "bg-danger" },
    }).then((result) => {
        if (result.isConfirmed) {
            $(`form#${model}-force-delete-${id}`).submit();
        }
    });
}

function confirmPublish(id) {
    Modal.fire({
        title: "Are you sure?",
        text: "This item and its related data will be published.",
        icon: "warning",
        confirmButtonText: "Confirm",
        customClass: { confirmButton: "bg-success" },
    }).then((result) => {
        if (result.isConfirmed) {
            $(`form#item-publish-${id}`).submit();
        }
    });
}

function confirmArchive(id) {
    Modal.fire({
        title: "Are you sure?",
        text: "This item will be archived.",
        icon: "warning",
        confirmButtonText: "Confirm",
        customClass: { confirmButton: "bg-success" },
    }).then((result) => {
        if (result.isConfirmed) {
            $(`form#item-archive-${id}`).submit();
        }
    });
}

/**
 *-------------------------------------------------------------
 * Update exam lock, exam edit, staff data edit, and user blocked states
 *-------------------------------------------------------------
 */
function reverseCheckBoxAction(el) {
    $(el).removeAttr("checked").trigger("click");
}

function removeItemFromSessionStorageAndFlashErrMsg(item) {
    sessionStorage.removeItem(item);
    const msg = sessionStorage.getItem("resp.msg") ?? "Something went wrong. Please try again.";
    flash({ msg, type: "error" });
}

function updateExamLockState(exam_id, el) {
    if (sessionStorage.getItem("update-exam-lock-ok") !== "false") {
        const url = update_exam_lock_state_url;
        const value = el.checked ? 1 : 0;
        $.ajax({
            method: "post",
            data: { id: exam_id, locked: value },
            url,
            success: (resp) => {
                if (!resp.ok) {
                    if (resp.msg) sessionStorage.setItem("resp.msg", resp.msg);
                    return rollbackUpdateExamLockAction(el);
                }
            },
            error: () => rollbackUpdateExamLockAction(el),
        });
    } else {
        removeItemFromSessionStorageAndFlashErrMsg("update-exam-lock-ok");
    }
}

function rollbackUpdateExamLockAction(el) {
    sessionStorage.setItem("update-exam-lock-ok", "false");
    reverseCheckBoxAction(el);
}

function updateExamEditState(exam_id, el) {
    if (sessionStorage.getItem("update-exam-edit-ok") !== "false") {
        const url = update_exam_edit_state_url;
        const value = el.checked ? 1 : 0;
        $.ajax({
            method: "post",
            data: { id: exam_id, editable: value },
            url,
            success: (resp) => {
                if (!resp.ok) {
                    if (resp.msg) sessionStorage.setItem("resp.msg", resp.msg);
                    return rollbackUpdateExamEditAction(el);
                }
            },
            error: () => rollbackUpdateExamEditAction(el),
        });
    } else {
        removeItemFromSessionStorageAndFlashErrMsg("update-exam-edit-ok");
    }
}

function rollbackUpdateExamEditAction(el) {
    sessionStorage.setItem("update-exam-edit-ok", "false");
    reverseCheckBoxAction(el);
}

function updateStaffDataEdtiState(user_id, el) {
    if (sessionStorage.getItem("update-staff-data-edit-ok") !== "false") {
        const url = update_staff_data_edit_state_url;
        const value = el.checked ? 1 : 0;
        $.ajax({
            method: "post",
            data: { id: user_id, staff_data_edit: value },
            url,
            success: (resp) => {
                if (!resp.ok) {
                    if (resp.msg) sessionStorage.setItem("resp.msg", resp.msg);
                    return rollbackUpdateStaffDataEditAction(el);
                }
            },
            error: () => rollbackUpdateStaffDataEditAction(el),
        });
    } else {
        removeItemFromSessionStorageAndFlashErrMsg("update-staff-data-edit-ok");
    }
}

function rollbackUpdateStaffDataEditAction(el) {
    sessionStorage.setItem("update-staff-data-edit-ok", "false");
    reverseCheckBoxAction(el);
}

function updateUserBlockedState(user_id, el) {
    if (sessionStorage.getItem("update-user-blocked-state-ok") !== "false") {
        const url = update_user_blocked_state_url;
        const value = el.checked ? 1 : 0;
        $.ajax({
            method: "post",
            data: { id: user_id, blocked: value },
            url,
            success: (resp) => {
                if (!resp.ok) {
                    if (resp.msg) sessionStorage.setItem("resp.msg", resp.msg);
                    return rollbackUpdateUserBlockedState(el);
                }
            },
            error: () => rollbackUpdateUserBlockedState(el),
        });
    } else {
        removeItemFromSessionStorageAndFlashErrMsg("update-user-blocked-state-ok");
    }
}

function rollbackUpdateUserBlockedState(el) {
    sessionStorage.setItem("update-user-blocked-state-ok", "false");
    reverseCheckBoxAction(el);
}

/**
 *-------------------------------------------------------------
 * Confirm reset operations
 *-------------------------------------------------------------
 */
function confirmReset(id, href = null) {
    Modal.fire({
        title: "Are you sure?",
        text: "This will reset this item to default state",
        icon: "warning",
        confirmButtonText: "Sure, Reset",
        customClass: { confirmButton: "bg-primary" },
    }).then((result) => {
        if (result.isConfirmed) {
            if (id === null && href !== null) return window.location = href;
            $(`form#item-reset-${id}`).submit();
        }
    });
}

function confirmResetTwice(id, href = null) {
    Modal.fire({
        title: "Just in case",
        text: "Do you really want to reset this item?",
        icon: "warning",
        confirmButtonText: "Sure, Reset",
        customClass: { confirmButton: "bg-primary" },
    }).then((result) => {
        if (result.isConfirmed) {
            confirmReset(id, href);
        }
    });
}

function confirmResetPassword(href, default_pass) {
    Modal.fire({
        title: "Are you sure?",
        text: `This will reset password to ${default_pass ?? 'user'}`,
        icon: "warning",
        confirmButtonText: "Sure, Reset",
        customClass: { confirmButton: "bg-primary" },
    }).then((result) => {
        if (result.isConfirmed) {
            if (href) return window.location = href;
            flash({ msg: 'Oops! Invalid URL', type: 'info' });
        }
    });
}

$('a.needs-reset-pass-confirmation').on('click', function (ev) {
    ev.preventDefault();
    const href = $(this).data('href');
    const default_pass = $(this).data('default_pass');
    confirmResetPassword(href, default_pass);
});

/**
 *-------------------------------------------------------------
 * Payment pay handler on form submit
 *-------------------------------------------------------------
 */
$('form.ajax-pay').on('submit', function (ev) {
    ev.preventDefault();
    submitForm($(this), 'store');
    // Retrieve IDS
    const formId = $(this).attr('id');
    const $tdAmt = $(`td#amt-${formId}`);
    const $tdAmtPaid = $(`td#amt_paid-${formId}`);
    const $tdBal = $(`td#bal-${formId}`);
    const $input = $(`#val-${formId}`);
    // Get Values
    let amt = parseInt($tdAmt.data('amount'));
    let amtPaid = parseInt($tdAmtPaid.data('amount'));
    const amtInput = parseInt($input.val());
    // Update Values
    amtPaid += amtInput;
    const bal = amt - amtPaid;
    $tdBal.text(`${bal}`);
    $tdAmtPaid.text(`${amtPaid}`).data('amount', `${amtPaid}`);
    $input.attr('max', bal);
    if (bal < 1) $(`#${formId}`).fadeOut('slow').remove();
});

/**
 *-------------------------------------------------------------
 * Ajax store, update, and register form handler
 *-------------------------------------------------------------
 */
$(document).on('submit', 'form.ajax-store, form.ajax-update, form#ajax-reg', function (ev) {
    ev.preventDefault();
    const formType = $(this).hasClass('ajax-store') || $(this).attr('id') === 'ajax-reg' ? 'store' : '';
    submitForm($(this), formType);
    const reloadDiv = $(this).data('reload');
    if (reloadDiv) reloadDivFn(reloadDiv);
});

$(document).on('click', '.download-receipt', function (ev) {
    ev.preventDefault();
    $.get($(this).attr('href'));
    flash({ msg: "Download in Progress", type: 'info' });
});

function reloadDivFn(div, url = window.location.href) {
    $(div).parent().load(`${url} ${div}`);
}

/**
 *-------------------------------------------------------------
 * Submit form (base method for submitting forms)
 *-------------------------------------------------------------
 */
function submitForm($form, formType) {
    // Normal form Submit button
    let $btn = $form.find('button[type=submit]');
    // Wizard form - Steps validation Submit button
    if (!$btn.length) $btn = $form.find('.actions li:last-child a');
    const btnHtml = $btn.html();
    if ($btn.hasClass('needs-time-counter')) setTimeCounter($btn);
    disableBtn($btn);
    const ajaxOptions = {
        url: $form.attr('action'),
        type: 'POST',
        cache: false,
        processData: false,
        dataType: 'json',
        contentType: false,
        data: new FormData($form[0])
    };
    $.ajax(ajaxOptions)
        .done((resp) => {
            closeConfirmMessageDeleteModal();
            if ($btn.hasClass('needs-time-counter')) stopTimeCounter();
            hideAjaxAlert();
            enableBtn($btn, btnHtml);
            if (resp.ok && resp.msg) {
                resp.pop
                    ? pop({ msg: resp.msg, type: 'success', timer: resp.pop_timer })
                    : flash({ msg: resp.msg, type: 'success' });
            } else {
                resp.pop
                    ? pop({ msg: resp.msg, type: 'success', timer: resp.pop_timer })
                    : flash({ msg: resp.msg, type: 'error' });
            }
            if (formType === 'store') clearForm($form);
            $btn.html(btnHtml);
            if (resp.scrollToBtn) return scrollTo($btn);
            if (resp.complete) return resp;
            scrollTo('body');
        })
        .fail((e) => {
            closeConfirmMessageDeleteModal();
            unblockUI();
            if ($btn.hasClass('needs-time-counter')) stopTimeCounter();
            if (e.status === 422) displayAjaxErr(e.responseJSON.errors);
            if (e.status === 500) displayAjaxErr([`${e.status} ${e.statusText} Please Check for Duplicate entry or Contact Administrator`]);
            if (e.status === 404) displayAjaxErr([`${e.status} ${e.statusText} - Requested Resource Not Found`]);
            enableBtn($btn, btnHtml);
            return e.status;
        });
}

function setTimeCounter($btn) {
    $('<span id="time-counter" class="status-styled d-inline-block"></span>').insertAfter($btn);
    startTimeCounter();
}

let sec = 0, time_interval;
const pad = (val) => (val > 9 ? val : `0${val}`);

function updateTimer() {
    $('#time-counter').html(`${pad(parseInt(sec / 60, 10))}:${pad(++sec % 60)} elapsed.`);
}

function startTimeCounter() {
    time_interval = setInterval(updateTimer, 1000);
}

function stopTimeCounter() {
    clearInterval(time_interval);
}

function closeConfirmMessageDeleteModal() {
    return $('#confirm-message-delete .close').click();
}

function disableBtn($btn) {
    const btnText = $btn.data('text') ? $btn.data('text') : 'Submitting...';
    $btn.prop('disabled', true).html(`<i class="material-symbols-rounded mr-2 spinner">progress_activity</i>${btnText}`);
}

function enableBtn($btn, btnHtml) {
    $btn.prop('disabled', false).html(btnHtml);
}

function displayAjaxErr(errors) {
    $('#ajax-alert').show().html('<div class="alert alert-danger border-0 alert-dismissible" id="ajax-msg"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>');
    $.each(errors, (k, v) => {
        $('#ajax-msg').append(`<span><i class="material-symbols-rounded pb-2px">hdr_strong</i> ${v}</span><br/>`);
    });
    scrollTo('body');
}

function scrollTo(el) {
    $('html, body').animate({ scrollTop: $(el).offset().top }, 2000);
}

function scrollToBottom() {
    const scrollingElement = (document.scrollingElement || document.body);
    $('html, body').animate({
        scrollTop: scrollingElement.scrollHeight
    }, 2000);
}

function hideAjaxAlert() {
    $('#ajax-alert').hide();
}

function clearForm($form) {
    $form.find('.checked').removeClass('checked');
    $form.find('.select, .select-search').val([]).select2({ placeholder: 'Select...' });
    $form[0].reset();
}

$('.print-this').addClass('position-relative').append('<button class="position-absolute btn btn-sm pb-1 pt-1 right-0 bottom-0 print-none this" type="button">Print</button>');
$('.print-this button.this').on('click', function () {
    const $parent = $(this).closest('.print-this');
    if (getPreferredTheme() === "dark" || (getPreferredTheme() === "auto" && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        Modal.fire({
            text: "Please, print while in light color mode for best visual quality.",
            icon: "warning",
            showDenyButton: true,
            denyButtonText: "Let me switch",
            confirmButtonText: "Just Print",
            customClass: { confirmButton: "bg-warning", denyButton: "bg-info" },
        }).then((result) => {
            if (result.isConfirmed) {
                doElementPrint($parent);
            } else if (result.isDenied) {
                scrollTo('body');
            }
        });
    } else {
        doElementPrint($parent);
    }
});

function doElementPrint($element) {
    if ($element.hasClass('card-collapsed'))
        $element.removeClass('card-collapsed');
    $element.printThis({ base: "https://jasonday.github.io/printThis/" });
}

function resizeChartInstances() {
    for (let id in Chart.instances) {
        Chart.instances[id].resize();
    }
}

/**
 *-------------------------------------------------------------
 * Handle Offline Indicator
 *-------------------------------------------------------------
 */
const offlineDiv = document.getElementById("offline-alert");

const updateOfflineStatus = () => {
    offlineDiv.style.display = navigator.onLine ? 'none' : 'inline-flex';
};

window.addEventListener("offline", updateOfflineStatus);
window.addEventListener("online", updateOfflineStatus);

updateOfflineStatus();

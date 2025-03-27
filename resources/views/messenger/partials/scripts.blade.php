<script type="module">
    /**
     *-------------------------------------------------------------
     * Authentication and element caching
     *-------------------------------------------------------------
     */
    const authId = {{ auth()->id() }};
    const authUserType = "{{ auth()->user()->user_type }}";
    const $textarea = $('#message');
    const $sendBtn = $('#send-message-btn');
    const $typingStatus = $('#user-is-typing');
    const $typingIndicator = $('#typing-indicator');
    const $messagesRow = $('#messages-row');
  
    let typingNow = 0, typingTimeout;
  
    // Open a private channel using Echo (server-side values injected)
    const channel = Echo.private(`messages.{{ $thread->id }}.{{ tenant("id") }}`);

    /**
     *-------------------------------------------------------------
     * Trigger a typing event via whisper
     *-------------------------------------------------------------
     */
    const isTyping = (status) => {
        channel.whisper('typing', {
            user_name: "{{ auth()->user()->name }}",
            typing: status
        });
    };
  
    /**
     *-------------------------------------------------------------
     * On keydown in the message textarea, trigger typing indicator
     *-------------------------------------------------------------
     */
    $textarea.on('keydown', () => {
        if (typingNow < 1) {
            isTyping(true);
            typingNow = 1;
        }

        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            isTyping(false);
            typingNow = 0;
        }, 1000);
    });
  
    /**
     *-------------------------------------------------------------
     * Bind connection state change and check internet connectivity
     *-------------------------------------------------------------
     */
    Echo.connector.pusher.connection.bind('state_change', (states) => {
        checkInternet(states.current);
    });
  
    /**
     *-------------------------------------------------------------
     * Listen for typing whispers from other users
     *-------------------------------------------------------------
     */
    channel.listenForWhisper('typing', (e) => {
        if (e.typing) {
            $typingStatus.html(`${e.user_name} is typing`);
            $typingIndicator.css("display", "inline-block");
        } else {
            $typingIndicator.hide();
        }
    });
  
    /**
     *-------------------------------------------------------------
     * Listen for new messages and update UI accordingly
     *-------------------------------------------------------------
     */
    channel.listen('NewMessage', (e) => {
        $messagesRow.append(getMessage(authId, e));
        updateParticipantLastRead(e.message.thread_id);
        playNotificationSound('message-notification.mp3', true);
        initializePopover(); // Re-initialize popover for new elements
        scrollTo($sendBtn);  // Scroll to bottom (or to button)
    });
  
    /**
     *-------------------------------------------------------------
     * Listen for message deletion events
     *-------------------------------------------------------------
     */
    channel.listen('MessageDeleted', (e) => {
        $("#confirm-message-delete").find('.close').click(); // Close modal if open
        $(`#message-${e.message.id}`).replaceWith(getMessage(authId, e));
    });
  
    /**
     *-------------------------------------------------------------
     * Build and return a jQuery element representing a message
     *-------------------------------------------------------------
     */
    const getMessage = (authId, e) => {
        const { message } = e;
        // Build container using template literal
        const $container = $(`
            <div id="message-${message.id}" class="col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
                <li class="position-relative ${message.user_id == authId ? 'float-right' : 'float-left'}"></li>
            </div>
        `);
        const $listItem = $container.find("li");
  
        // If sender is not the authenticated user, add photo and delete (if super admin)
        if (message.user.id != authId) {
            const $userPhoto = $(`
                <img src="${message.user.photo}" alt="${message.user.name}" class="rounded-circle f-left">
            `);
            $listItem.append($userPhoto);
            if (authUserType === 'super_admin' && message.deleted_at === null) {
                const $deleteBtn = $(`
                    <button data-toggle="popover" data-placement="right" data-html="true" class="material-symbols-rounded position-absolute right-neg-25 text-info-400 opacity-75 opacity-100-on-hover border-0 outline-0 action-btn"
                        data-content="
                            <div class='d-flex justify-center'>
                                <button type='button' data-text='Deleting...' id='${message.id}' onclick='prepareDeleteUserMessageForm(this.id)' data-toggle='modal' data-target='#confirm-message-delete' class='btn btn-sm p-0 pr-1 pl-1 m-auto text-danger-800 w-100 text-left bg-transparent'>
                                    <span class='text-danger-800'>Delete</span>
                                </button>
                            </div>">
                            more_vert
                    </button>
                `);
                $listItem.append($deleteBtn);
            }
        }
  
        // Message box and heading
        const senderName = message.user.id == authId
            ? "Me"
            : `${message.user.name} (${message.user.user_type.replace('_', ' ')})`;
        const $messageBox = $(`<div class="${message.user.id == authId ? 'right' : 'left'}"></div>`);
        const $messageContent = $(`
            <span class="media-body break-all">
                <small class="media-heading" style="color: ${message.user.message_media_heading_color};">
                    ${senderName}<br>
                </small>
            </span>
        `);
        $messageBox.append($messageContent);
        $listItem.append($messageBox);
  
        // Append message body or deletion notice
        if (message.deleted_at) {
            let deletionNotice = "";
            if (message.deleted_by == authId) {
                deletionNotice = `<p class="text-muted"><i class="material-symbols-rounded font-size-sm pb-lg-1">block</i> You deleted this message.</p>`;
            } else if (message.deletor.user_type === 'super_admin' && message.user_id != message.deleted_by) {
                deletionNotice = `<p class="text-muted"><i class="material-symbols-rounded font-size-sm pb-lg-1">block</i> This message was deleted by super admin ${message.deletor.name}.</p>`;
            } else {
                deletionNotice = `<p class="text-muted"><i class="material-symbols-rounded font-size-sm pb-lg-1">block</i> This message was deleted.</p>`;
            }
            $messageContent.append(deletionNotice);
        } else {
            const formattedTime = moment().parseZone(message.created_at).format('DD MMMM YYYY, h:mm:ss a');
            const $body = $(`
                <span>
                    ${message.body}<br>
                    <span class="text-muted float-right"><small><i>${formattedTime}</i></small></span>
                </span>
            `);
            $messageContent.append($body);
        }
  
        // If the message is from the authenticated user, add delete option and photo after message box
        if (message.user.id == authId) {
            if (!message.deleted_at) {
                const $deleteBtn = $(`
                    <button data-toggle="popover" data-placement="left" data-html="true" class="material-symbols-rounded position-absolute left-neg-25 text-info-400 opacity-75 opacity-100-on-hover bg-transparent border-0 outline-0"
                        data-content="
                            <div class='d-flex justify-center'>
                                <button type='button' data-text='Deleting...' id='${message.id}' onclick='prepareDeleteUserMessageForm(this.id)' data-toggle='modal' data-target='#confirm-message-delete' class='btn btn-sm p-0 pr-1 pl-1 m-auto text-danger-800 w-100 text-left bg-transparent'>
                                    <span class='text-danger-800'>Delete</span>
                                </button>
                            </div>">
                            more_vert
                    </button>
                `);
                $listItem.append($deleteBtn);
            }
            const $userPhoto = $(`
                <img src="${message.user.photo}" alt="${message.user.name}" class="rounded-circle f-right">
            `);
            $listItem.append($userPhoto);
        }
  
        return $container;
    };
</script>
<script type="text/javascript">
    /**
     *-------------------------------------------------------------
     * Cache frequently used jQuery selectors
     *-------------------------------------------------------------
     */
    const $loadPrevBtn = $('button.load-previous');
    const $loadingPrevDiv = $('div.loading-previous');
    const $messagesRow = $('#messages-row');

    /**
     *-------------------------------------------------------------
     * Prepare deletion form attributes dynamically
     *-------------------------------------------------------------
     */
    function prepareDeleteUserMessageForm(messageId) {
        hideAllPopover();
        const actionUrl = '{{ route("messages.user_delete", ":message_id") }}'.replace(':message_id', messageId);
        $("form#delete-user-message").attr("action", actionUrl);
        popOutIntendedMesg(messageId);
    }

    function popOutIntendedMesg(messageId) {
        $("div#messages-row").find("div.right, div.left").css("z-index", "initial");
        $(`#message-${messageId}`).find('div.right, div.left').css("z-index", "99999");
    }

    /**
     *-------------------------------------------------------------
     * Handle "Load Previous Messages" button click
     *-------------------------------------------------------------
     */
    $loadPrevBtn.on('click', (e) => {
        e.preventDefault();
        const currentFirstMsgId = $messagesRow.children(":first").attr('id').split('-')[1];
        let url = '{{ route("messages.fetch_previous", [":thread_id", ":current_first_msg_id_in_view"]) }}'.replace(':thread_id', '{{ $thread->id }}').replace(':current_first_msg_id_in_view', currentFirstMsgId);
        const requestData = {
            url, 
            prepend_previous_messages: true, 
            show_loading_previous: true, 
        };
        processRequest(requestData);
    });

    /**
     *-------------------------------------------------------------
     * Update participant's last read timestamp (if document is visible)
     *-------------------------------------------------------------
     */
    function updateParticipantLastRead(threadId) {
        if (document.hidden) return;
        let url = '{{ route("messages.update_participant_last_read", [":thread_id"]) }}'.replace(':thread_id', threadId);
        processRequest({
            url
        });
    }

    /**
     *-------------------------------------------------------------
     * Process an AJAX request with optional UI feedback
     *-------------------------------------------------------------
     */
    async function processRequest(data) {
        const formData = new FormData();

        if (data.show_loading_previous) {
            $loadPrevBtn.hide();
            $loadingPrevDiv.fadeIn();
        }

        $.ajax({
                url: data.url, 
                type: 'POST', 
                cache: false, 
                processData: false, 
                dataType: 'json', 
                contentType: false, 
                data: formData
            })
            .done((resp) => {
                if (data.prepend_previous_messages) {
                    if (!resp.msg) {
                        $loadingPrevDiv.hide();
                        $loadPrevBtn.hide('slow');
                    } else {
                        $messagesRow.prepend(resp.msg).fadeIn('slow');
                        initializePopover();
                        if (data.show_loading_previous) {
                            $loadingPrevDiv.hide();
                            $loadPrevBtn.fadeIn();
                        }
                    }
                }
                return resp;
            })
            .fail((e) => {
                pop({
                    msg: 'Sorry, something went wrong. You may try again!', 
                    type: 'error'
                });
                if (data.show_loading_previous) {
                    $loadingPrevDiv.hide();
                    $loadPrevBtn.fadeIn();
                }
            });
    }

    /**
     *-------------------------------------------------------------
     * Check internet connection based on pusher state
     *-------------------------------------------------------------
     */
    function checkInternet(state) {
        const $msgRow = $('.message-row');
        const $alertRow = $('.alert-row');
        const dotIndicatorClass = getRandomDotIndicatorClass();

        if (state === "connected") {
            $alertRow.hide();
            $msgRow.show();
        } else if (state === "connecting") {
            $msgRow.hide();
            $alertRow.find('.alert').removeClass('text-center');
            $alertRow.find('.dot-indicator')
                .removeClass('display-none')
                .css({
                    top: '45%', 
                    right: '2rem', 
                    position: 'absolute'
                })
                .addClass(dotIndicatorClass);
            $alertRow.show().find('i').text("Connecting");
        } else {
            $msgRow.hide();
            $alertRow.find('.alert').addClass('text-center');
            $alertRow.find('.dot-indicator')
                .addClass('display-none')
                .removeClass(dotIndicatorClass);
            $alertRow.show().find('i').text("No connection");
        }
    }

    /**
     *-------------------------------------------------------------
     * Return a random dot indicator class from an array
     *-------------------------------------------------------------
     */
    function getRandomDotIndicatorClass() {
        const dotClasses = ["dot-flashing", "dot-elastic", "dot-collision"];
        return dotClasses[Math.floor(Math.random() * dotClasses.length)];
    }

</script>

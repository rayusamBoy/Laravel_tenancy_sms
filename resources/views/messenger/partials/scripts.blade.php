<script type="module">
    const auth_id = {{ auth()->id() }};
    const auth_user_type = "{{ auth()->user()->user_type }}";
    const auth_user_message_media_heading_color = "{{ auth()->user()->message_media_heading_color }}";
    const throttle_time = 900; // 0.9 seconds
    const wait_time = 1100; // 1.1 seconds
    const textarea = $('#message');
    const btn = $(document).find('#send-message-btn');
    const typing_status = $('#user-is-typing');
    const typing_indicator = $('#typing-indicator');

    var can_publish = true;

    let channel = Echo.private('messages.{{ $thread->id }}.{{ tenant("id") }}');

    /**
     *-------------------------------------------------------------
     * Initiate client indicator event on input
     *-------------------------------------------------------------
    */
    textarea.on('keydown', function() {
        //console.log('e');
        if (can_publish)
            channel.whisper('typing', {
                user_name: '{{ auth()->user()->name }}',
                typing: true
            });

        can_publish = false;
        setTimeout(function() {
            can_publish = true;
        }, throttle_time);
    });

    /**
     *-------------------------------------------------------------
     * Check for connection
     *-------------------------------------------------------------
    */
    Echo.connector.pusher.connection.bind('state_change', (states) => {
        //console.log(states.current)
        checkInternet(states.current);
    });

    /**
     *-------------------------------------------------------------
     * Listen for typing client event
     *-------------------------------------------------------------
    */
    channel.listenForWhisper('typing', (e) => {
            //console.log(e);
            typing_status.html(e.user_name + ' is typing');
            typing_indicator.show();
            clearTimeout(time_out);

            // Remove is typing indicator ater wait time.
            var time_out = setTimeout(function() {
                typing_indicator.hide();
            }, wait_time);
        });

    /**
     *-------------------------------------------------------------
     * Listen for a new message event
     *-------------------------------------------------------------
    */
    channel.listen('NewMessage', (e) => {
            //console.log(e);
            $('#messages-row').append(updateMessage(auth_id, e));
            updateParticipantLastRead(e.message.thread_id);
            playNotificationSound('message-notification.mp3', true);
            initializePopover(); // Actually re-initilize for the new created popover in the new/updated message.
            scrollTo(btn); // Move message body to bottom.
        });

    /**
     *-------------------------------------------------------------
     * Listen for message deleted event
     *-------------------------------------------------------------
    */
    channel.listen('MessageDeleted', (e) => {
            //console.log(e);
            $("#confirm-message-delete").find('.close').click(); // Close message action modal if open.
            $('#message-' + e.message.id).replaceWith(updateMessage(auth_id, e));
        });

    /**
     *-------------------------------------------------------------
     * Update message - new vs delete
     *-------------------------------------------------------------
    */
    function updateMessage(auth_id, e) {
        // Create div item element
        var divItem = document.createElement('div');
        divItem.classList.add('col-lg-8', 'offset-lg-2', 'col-xl-6', 'offset-xl-3');
        divItem.id = "message-" + e.message.id;
        document.getElementById('messages-row').appendChild(divItem);

        // Create list item element
        var listItem = document.createElement('li');
        listItem.classList.add('position-relative');
        // Check if the message user id is equal to the authenticated user id
        var floatDirection = e.message.user_id == auth_id ? "float-right" : "float-left";
        listItem.classList.add(floatDirection);
        divItem.appendChild(listItem);

        // If the message user id is not equal to the authenticated user id
        if (e.message.user.id != auth_id) {
            // Create and append user photo
            var userPhoto = document.createElement('img');
            userPhoto.src = e.message.user.photo;
            userPhoto.alt = e.message.user.name;
            userPhoto.classList.add('rounded-circle', 'f-left');
            listItem.appendChild(userPhoto);

            // If user is super admin and message is not deleted, create and append delete link and form
            if (auth_user_type === 'super_admin' && e.message.deleted_at == null) {
                var button = document.createElement("button");

                button.setAttribute("data-toggle", "popover");
                button.setAttribute("data-placement", "right");
                button.setAttribute("data-html", "true");
                button.setAttribute("class", "material-symbols-rounded position-absolute right-neg-25 text-info-400 opacity-75 opacity-100-on-hover border-0 outline-0 action-btn");
                button.setAttribute("data-content", `
                    <div class="d-flex justify-center">
                        <button type="button" data-text="Deleting..." id="` + e.message.id + `" onclick="prepareDeleteUserMessageForm(this.id)" data-toggle="modal" data-target="#confirm-message-delete" class="btn btn-sm p-0 pr-1 pl-1 m-auto text-danger-800 w-100 text-left bg-transparent"><span class="text-danger-800">Delete</span></button>
                    </div>
                `);
                button.textContent = 'more_vert';
                listItem.appendChild(button);
            }
        }

        // Create and append message box
        var messageBox = document.createElement('div');
        messageBox.classList.add(e.message.user.id == auth_id ? "right" : "left");
        listItem.appendChild(messageBox);

        var messageSpan = document.createElement('span');
        messageSpan.classList.add('media-body', 'break-all');
        messageBox.appendChild(messageSpan);

        var messageHeading = document.createElement('small');
        messageHeading.classList.add('media-heading');
        var e_mesg_user_type = e.message.user.user_type;
        var headingText = e.message.user.id == auth_id ? "Me" : e.message.user.name + ' (' + e_mesg_user_type.replace('_', ' ') + ')';
        messageHeading.appendChild(document.createTextNode(headingText));
        messageHeading.appendChild(document.createElement('br'));
        messageHeading.setAttribute('style', 'color: ' + auth_user_message_media_heading_color);
        messageSpan.appendChild(messageHeading);

        // If the message is deleted
        if (e.message.deleted_at != null) {
            if (e.message.deleted_by == auth_id) {
                var deletedMessage = document.createElement('p');
                deletedMessage.classList.add('text-muted');
                var deletedIcon = document.createElement('i');
                deletedIcon.classList.add('material-symbols-rounded', 'font-size-sm');
                deletedIcon.textContent = 'block'; // Material symbol
                var deletedText = document.createElement('i');
                deletedText.appendChild(document.createTextNode('You deleted this message.'));
                deletedMessage.appendChild(deletedIcon);
                deletedMessage.appendChild(deletedText);
                messageSpan.appendChild(deletedMessage);
            } else if (e.message.deleted_by != auth_id) {
                if (e.message.deletor.user_type === 'super_admin') {
                    var superAdminDeletedMessage = document.createElement('p');
                    superAdminDeletedMessage.classList.add('text-muted');
                    var superAdminDeletedIcon = document.createElement('i');
                    superAdminDeletedIcon.classList.add('material-symbols-rounded', 'font-size-sm');
                    superAdminDeletedIcon.textContent = 'block'; // Symbol
                    var superAdminDeletedText = document.createElement('i');
                    superAdminDeletedText.appendChild(document.createTextNode(
                        'This message was deleted by super admin ' + e.message.deletor.name + '.'));
                    superAdminDeletedMessage.appendChild(superAdminDeletedIcon);
                    superAdminDeletedMessage.appendChild(superAdminDeletedText);
                    messageSpan.appendChild(superAdminDeletedMessage);
                } else {
                    var regularDeletedMessage = document.createElement('p');
                    regularDeletedMessage.classList.add('text-muted');
                    var regularDeletedIcon = document.createElement('i');
                    regularDeletedIcon.classList.add('material-symbols-rounded', 'font-size-sm');
                    regularDeletedIcon.textContent = 'block'; // Symbol
                    var regularDeletedText = document.createElement('i');
                    regularDeletedText.appendChild(document.createTextNode('This message was deleted.'));
                    regularDeletedMessage.appendChild(regularDeletedIcon);
                    regularDeletedMessage.appendChild(regularDeletedText);
                    messageSpan.appendChild(regularDeletedMessage);
                }
            }
        } else {
            // If the message is not deleted
            var messageText = document.createElement('span');
            messageText.appendChild(document.createTextNode(e.message.body));
            messageText.appendChild(document.createElement('br'));
            messageSpan.appendChild(messageText);

            var timePosted = document.createElement('span');
            timePosted.classList.add('text-muted', 'float-right');
            var timePostedSmall = document.createElement('small');
            var timePostedItalic = document.createElement('i');
            timePostedItalic.appendChild(document.createTextNode(moment().parseZone(e.message.created_at).format('DD MMMM YYYY, h:mm:ss a')));
            timePostedSmall.appendChild(timePostedItalic);
            timePosted.appendChild(timePostedSmall);
            messageText.appendChild(timePosted);
        }

        // If the message user id is equal to the authenticated user id
        if (e.message.user.id == auth_id) {
            if (e.message.deleted_at == null) {
                var button = document.createElement("button");

                button.setAttribute("data-toggle", "popover");
                button.setAttribute("data-placement", "left");
                button.setAttribute("data-html", "true");
                button.setAttribute("class", "material-symbols-rounded position-absolute left-neg-25 text-info-400 opacity-75 opacity-100-on-hover bg-transparent border-0 outline-0");
                button.setAttribute("data-content", `
                    <div class="d-flex justify-center">
                        <button type="button" data-text="Deleting..." id="` + e.message.id + `" onclick="prepareDeleteUserMessageForm(this.id)" data-toggle="modal" data-target="#confirm-message-delete" class="btn btn-sm p-0 pr-1 pl-1 m-auto text-danger-800 w-100 text-left bg-transparent"><span class="text-danger-800">Delete</span></button>
                    </div>
                `);
                button.textContent = 'more_vert'; // Material symbol
                listItem.appendChild(button);
            }

            // Display the message box first followed by the user photo
            var userPhoto = document.createElement('img');
            userPhoto.src = e.message.user.photo;
            userPhoto.alt = e.message.user.name;
            userPhoto.classList.add('rounded-circle', 'f-right');
            listItem.appendChild(userPhoto);
        }
        return divItem;
    }
</script>
<script type="text/javascript">
    const load_previous_msgs_btn = $('button.load-previous');
    const loading_previous_msgs_div = $('div.loading-previous');
    const messages_row = $(document).find('#messages-row');

    /**
     *-------------------------------------------------------------
     * Set the appropriate form attributes on run time
     *-------------------------------------------------------------
    */
    function prepareDeleteUserMessageForm(message_id) {
        hideAllPopover();
        var action = '{{ route("messages.user_delete", ":message_id") }}';
        $("form#delete-user-message").attr("action", action.replace(':message_id', message_id));
        popOutIntendedMesg(message_id);
        return;
    }

    /**
     *-------------------------------------------------------------
     * Show only current on delete message
     *-------------------------------------------------------------
    */
    function popOutIntendedMesg(message_id) {
        $("div#messages-row").find("div.right, div.left").css("z-index", "initial"); // Reset any popped out message's z-index.
        $("div#message-" + message_id).find('div.right, div.left').css("z-index", "99999"); // Pop out the intended one.
    }

    /**
     *-------------------------------------------------------------
     * Handle load previous messages btn click event
     *-------------------------------------------------------------
    */
    load_previous_msgs_btn.on('click', function(e){
        e.preventDefault();
        // Id is like 'messages-123'; so we split the id and take the second part (the actual message id)
        var current_first_msg_id_in_view = messages_row.children(":first").attr('id').split('-')[1];
        var url = '{{ route("messages.fetch_previous", [":thread_id", ":current_first_msg_id_in_view"]) }}';
        url = url.replace(':thread_id', '{{ $thread->id }}');
        url = url.replace(':current_first_msg_id_in_view', current_first_msg_id_in_view);
        var data = {
            'url': url,
            'prepend_previous_messages': true,
            'show_loading_previous': true,
        };

        processRequest(data);
    });

    /**
     *-------------------------------------------------------------
     * Update participant last read when new message received
     *-------------------------------------------------------------
    */
    function updateParticipantLastRead(thread_id) {
        if (document?.hidden) {
            return;
        }
      
        var url = '{{ route("messages.update_participant_last_read", [":thread_id"]) }}';
        url = url.replace(':thread_id', thread_id);
        var data = {
            'url': url
        };

        processRequest(data); 
    }

    /**
     *-------------------------------------------------------------
     * Process ajax request method
     *-------------------------------------------------------------
    */
    async function processRequest(data){
        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        //console.log(data)
        if(data.hasOwnProperty('show_loading_previous')){
            load_previous_msgs_btn.hide();
            loading_previous_msgs_div.fadeIn();
        }

        var ajaxOptions = {
            url: data.url,
            type:'POST',
            cache: false,
            processData: false,
            dataType: 'json',
            contentType: false,
            data: formData
        };
        var req = $.ajax(ajaxOptions);
        req.done(function(resp){
            if(data.hasOwnProperty('prepend_previous_messages')){
                // If no data coming
                if(resp.msg === ""){
                    loading_previous_msgs_div.hide();
                    load_previous_msgs_btn.hide('slow');
                } else {
                    messages_row.prepend(resp.msg).fadeIn('slow');
                    initializePopover();
                    
                    if(data.hasOwnProperty('show_loading_previous')){
                        loading_previous_msgs_div.hide();
                        load_previous_msgs_btn.fadeIn();
                    }
                }
            }
          
            return resp;
        });
        req.fail(function(e){
            pop({msg: 'Sorry, something went wrong. You may try again!', type: 'error'});
            if(data.hasOwnProperty('show_loading_previous')){
                loading_previous_msgs_div.hide();
                load_previous_msgs_btn.fadeIn();
            }
            //console.log(e)
        });
    }

    /**
     *-------------------------------------------------------------
     * Check internet connection using pusher states
     *-------------------------------------------------------------
    */
    function checkInternet(state) {
        let net_errs = 0;
        var msg_row = $('.message-row');
        var alert_row = $('.alert-row');
        var dot_indicator_class = getRandomDotIndicatorClass();

        switch (state) {
            case "connected":
                if (net_errs < 1) {
                    alert_row.hide();
                    msg_row.show();
                }
                break;
            case "connecting":
                msg_row.hide();
                alert_row.find('.alert').removeClass('text-center');
                alert_row.find('.dot-indicator').removeClass('display-none').css({
                    'top': '45%',
                    'right': '2rem',
                    'position': 'absolute'
                }).addClass(dot_indicator_class);
                alert_row.show().find('i').text("Connecting");
                break;
                // Not connected
            default:
                msg_row.hide();
                alert_row.find('.alert').addClass('text-center');
                alert_row.find('.dot-indicator').addClass('display-none').css({
                    'top': '45%',
                    'right': '2rem',
                    'position': 'absolute'
                }).removeClass(dot_indicator_class);
                alert_row.show().find('i').text("Not connected.");
                net_errs = 1;
                break;
        }
    }

    /**
     *-------------------------------------------------------------
     * Get random dot indicator class
     *-------------------------------------------------------------
    */
    function getRandomDotIndicatorClass() {
        var dot_classes = ["dot-flashing", "dot-elastic", "dot-collision"];
        var dot_class = dot_classes[Math.floor(Math.random() * dot_classes.length)];
        return dot_class;
    }
</script>

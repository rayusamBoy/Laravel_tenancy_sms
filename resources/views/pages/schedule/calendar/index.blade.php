@php
// Get events from the DB
$calendar_events = json_encode(['events' => Evt::all() ]);
@endphp

<div class="card-body p-0">
    <div class="content main">
        <div class="calendar-container">
            <div class="calendar">

                <div class="year-header">
                    <span class="left-button" id="prev">&#8592;</span>
                    <span class="year" id="label"></span>
                    <span class="right-button" id="next">&#8594;</span>
                </div>

                <table class="months-table">
                    <tbody>
                        <tr class="months-row">
                            <td class="month">Jan</td>
                            <td class="month">Feb</td>
                            <td class="month">Mar</td>
                            <td class="month">Apr</td>
                            <td class="month">May</td>
                            <td class="month">Jun</td>
                            <td class="month">Jul</td>
                            <td class="month">Aug</td>
                            <td class="month">Sep</td>
                            <td class="month">Oct</td>
                            <td class="month">Nov</td>
                            <td class="month">Dec</td>
                        </tr>
                    </tbody>
                </table>

                <table class="days-table">
                    <td class="day">Sun</td>
                    <td class="day">Mon</td>
                    <td class="day">Tue</td>
                    <td class="day">Wed</td>
                    <td class="day">Thu</td>
                    <td class="day">Fri</td>
                    <td class="day">Sat</td>
                </table>

                <div class="frame">
                    <table class="dates-table">
                        <tbody class="tbody">
                        </tbody>
                    </table>
                </div>

                @if(Qs::userIsTeamSA())
                <button class="btn btn-info btn-sm float-right" id="add-button">Add Event</button>
                @endif

            </div>
        </div>

        <div class="events-container pb-2"></div>

        <div class="dialog" id="dialog" style="font-size: inherit; font-family: inherit">

            <h2 class="dialog-header"> Add New Event </h2>

            <form class="form" id="form" style="font-size: inherit; font-family: inherit">
                {{-- If you change maxlength attribute of name and/or description elements in this form; you must also change the ones in the edit form. --}}
                <div class="form-container">
                    <label class="form-label" id="valueFromMyButton" for="name">Event name</label>
                    <input class="input" type="text" width="100%" id="name" maxlength="50">
                    <label class="form-label" id="valueFromMyButton" for="count">Event Description</label>
                    <textarea maxlength="150" class="input break-all" placeholder="Try to make it short, and clear as possible..." id="count" rows="3" cols="auto"></textarea>
                    <input type="button" value="Cancel" class="btn btn-warning mr-2" id="cancel-button">
                    <input type="button" value="Submit" class="btn btn-primary mb-0" id="ok-button">
                </div>
            </form>

        </div>
    </div>

    <script>
        // Assign events data to js variable used in calendar.js
        var events_data = {!! $calendar_events !!}
    </script>

</div>
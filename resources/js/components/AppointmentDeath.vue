<template>
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                
                <div class="panel-body" style="background-color: rgba(52, 144, 220, 0.5)!important;">
                    <div class="row">
                        <div class="col-md-4 pull-right"></div>
                        <div class="col-md-5">
                            <p>Select day and time to have an appointment.</p>
                            <datepicker v-show="showDatePicker" v-model="dateSelected" @selected="changeView" @input="changeDate" :disabled="disabled" :inline="true" language="es"></datepicker>
                            <div v-show="showTimePicker" style="margin-bottom: 1%;">
                                <button v-on:click="changeView" class="form-control btn-primary btn-sm" style="width: 20%!important;">back</button>
                                <p> Selected Date :{{dateSelectedFormant}}</p>
                            </div>
                            <div style="margin-bottom: 5%;">
                                <timepicker v-show="showTimePicker" v-for="(hour, key) in timetable" :hour="hour" :key="key" v-on:selectedTime="selectedTime"></timepicker>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Datepicker from 'vuejs-datepicker'
    import sweetalert from 'sweetalert2';

    var moment = require('moment')

    export default {
        components: {
            Datepicker,
        },
        computed:   {
            dateSelectedFormant: function () {
                return moment(this.dateSelected).format('YYYY-MM-DD')
            }
        },
        data() {
            return {
                timetable:      [
                    {
                        time:      '09:00:00',
                        period:    'am',
                        available: true
                    },
                    {
                        time:      '10:00:00',
                        period:    'am',
                        available: true
                    },
                    {
                        time:      '11:00:00',
                        period:    'am',
                        available: true
                    },
                    {
                        time:      '12:00:00',
                        period:    'pm',
                        available: true
                    },
                    {
                        time:      '01:00:00',
                        period:    'pm',
                        available: true
                    },
                    {
                        time:      '02:00:00',
                        period:    'pm',
                        available: true
                    },
                    {
                        time:      '03:00:00',
                        period:    'pm',
                        available: true
                    },
                    {
                        time:      '04:00:00',
                        period:    'pm',
                        available: true
                    },
                    {
                        time:      '05:00:00',
                        period:    'pm',
                        available: true
                    },
                    {
                        time:      '06:00:00',
                        period:    'pm',
                        available: true
                    },
                    {
                        time:      '07:00:00',
                        period:    'pm',
                        available: true
                    }
                ],
                showTimePicker: false,
                showDatePicker: true,
                dateSelected:   '',
                disabled:       {
                    to:   new Date(moment().format('YYYY-MM-DD')),
                    days: [6, 0],
                },
            }
        },

        methods: {
            changeView() {
                if (this.showDatePicker) {
                    this.showTimePicker = true
                    this.showDatePicker = false
                } else {
                    this.showTimePicker = false
                    this.showDatePicker = true
                }

            },

            changeDate() {
                let dateSelected = moment(this.dateSelected).format('YYYY-MM-DD')

                const $this         = this
                this.showTimePicker = false

                axios.get('api/1.0/appointment/date/' + dateSelected)
                    .then(function (response) {
                        $this.refreshTimetable(response.data.data)
                        $this.showTimePicker = true
                    })
                    .catch(function (error) {
                        console.log(error)
                    })

            },

            refreshTimetable(data) {
                const $this    = this
                this.timetable = this.timetable.filter(function item(item) {
                    item.available = true
                    return item
                })

                $.each(data, function (key, value) {
                    $.each($this.timetable, function (keyTimetable, valueTimetable) {
                        if (value.time == moment(valueTimetable.time + " " + valueTimetable.period, "h:mm:ss A").format("HH:mm:ss")) {
                            $this.timetable[keyTimetable].available = false
                            return false
                        }
                    })
                })
            },

            selectedTime(hour) {
                const $this = this
                sweetalert.mixin({
                    title:             "Enter data for death",
                    confirmButtonText: 'Enviar',
                    buttonsStyling:    false,
                    input:             'text',
                }).queue([
                    {
                        html:
                            "<form class='formulario' action='' method='post'>" +
                            "<div class='fila'>" +
                            "<input id='name' class='input_custom' name='name' type='text' placeholder='Name' maxlength='20' required>" +
                            "</div>" +
                            "<div class='fila'>" +
                            "<input id='email' class='input_custom' name='email' type='text' placeholder='Email' >" +
                            "</div>" +
                            "</form>",
                        inputValidator: () => {
                            if (document.getElementById('name').value === '') {
                                return "'Writter name'"
                            }
                            if (document.getElementById('email').value === '') {
                                return "'Writter email'"
                            }
                            var emailValidator = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

                            if (!document.getElementById('email').value.match(emailValidator)) {
                                return "Not a valid Email Address"
                            }
                        },
                        preConfirm:     function () {
                            var array = {
                                'name':  document.getElementById("name").value,
                                'email': document.getElementById("email").value,
                            }
                            return array;
                        },
                        onBeforeOpen:   function (dom) {
                            sweetalert.getInput().style.display = 'none';
                        }
                    }
                ])
                    .then((result) => {

                        axios.post('api/1.0/appointment/store', {
                            date:  moment($this.dateSelected).format('YYYY-MM-DD'),
                            time:  moment(hour.time + " " + hour.period, "h:mm:ss A").format("HH:mm:ss"),
                            name:  result.value[0].name,
                            email: result.value[0].email,
                        })
                            .then(function (response) {
                                hour.available = false
                                sweetalert.fire(
                                    'Great!',
                                    'you have scheduled an appointment to dance with death!',
                                    'success'
                                )
                            
                            })
                            .catch(function (errors) {
                                if (errors.response.status == 400) {
                                    const Toast = sweetalert.mixin({
                                        toast:             true,
                                        position:          'top-end',
                                        showConfirmButton: false,
                                        timer:             3000
                                    });

                                    for (var error in errors.response.data.data) {
                                        errors.response.data.data[error].forEach(e => {
                                            Toast.fire({
                                                type:  'error',
                                                title: e
                                            })
                                        })
                                    }

                                } else {
                                    console.log(errors)
                                }

                                return false
                            })
                    })

            },

        },
    }
</script>
@once
<link rel="stylesheet" href="{{ asset('css/date.css') }}">
<script src="{{ asset('js/date.js') }}"></script>
<script>
    function initDatePicker(selector) {
        document.querySelectorAll(`input${selector}`).forEach(el => {
            if (el.dataset.datepickerInitialized) return;

            new Datepicker(el, {
                autohide: true,
                format: "yyyy-mm-dd",
                clearBtn: true,
                todayBtn: true,
                todayBtnMode: 1
            });

            el.dataset.datepickerInitialized = "true";
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        initDatePicker(".datePicker");
    });
</script>
@endonce
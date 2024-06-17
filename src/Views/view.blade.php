@if(config('lcl.enabled') && config('app.env')!=='production'))


<!--  EventSource pollyfill  -->
<script src="https://cdn.jsdelivr.net/npm/event-source-polyfill@1.0.31/src/eventsource.min.js"></script>

<script>
    if (window.EventSource !== undefined) {

        var es = new EventSource("{{route('lcl-stream-log')}}");

        es.addEventListener("stream-console-log", function(e) {

            var data = JSON.parse(e.data);
            var js_console_log = {{config('lcl.js_console_log_enabled') ? config('lcl.js_console_log_enabled') : 'false'}};

            if (data.message) {

                if (js_console_log === true || js_console_log === 1) {
                    console.log(data);
                }
            }
        }, false);

        es.addEventListener("error", event => {
            if (event.readyState == EventSource.CLOSED) {
                console.log("lcl Connection Closed.");
            }
        }, false);

    } else {
        alert("lcl is not supported in this browser!");
    }
</script>

@endif
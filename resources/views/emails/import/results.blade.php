@component('mail::message')
# Import Completed

The import has completed on {{ $date }}

# Results

Total rows processed: {{ $total }}

Successful rows inserted: {{ $success }}

Errored rows: {{ $errored }}

## Errors

@foreach ($errors as $error => $msgs)
**Row {{ $error }} errors:**

@foreach ($msgs as $msg)
- {{ $msg }}
@endforeach

@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent

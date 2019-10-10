@extends('template')

@section('content')

    <div class="wrapper">
      <h1>
        Import Completed
      </h1>

       <p>
        You have imported {{ $count = $subscriber->count() }} {{ srt_plural('record', $count) }}
       </p>
    </div>
@endsection
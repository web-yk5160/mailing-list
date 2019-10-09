@extends('template')

@section('content')

  <div class="wrapper">
    <h1>
      Mailing List <br>
      Filter and Import
    </h1>

    @if($errors->count() > 0)
      @foreach($errors->all() as $error)
        <span class="warning">{{$error}}</span>
      @endforeach
    @endif


      <form action="/" method="post" enctype="multipart/form-data"
      >
      {{ csrf_field() }}
      <input
          type="file"
          name="file"
      >
      <input
          type="submit"
          value="UPLOAD"
          class="expanded button"
      >
      </form>
  </div>

@endsection
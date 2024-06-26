@extends('layouts.app')

@section('title', '診断する')

@section('content')
    <h1>診断する</h1>
    <form action="{{ url('/analyze') }}" method="post" enctype="multipart/form-data">
      @csrf
      <input type="text" name="userName" placeholder="あなたの名前を入力してください" required />
      <input type="text" name="line_name" placeholder="LINEの名前を入力してください" required />
      <input type="file" name="chatHistoryFile" required />
      <button type="submit">Analyze</button>
    </form>
@endsection

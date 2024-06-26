@extends('layouts.app')

@section('title', '診断履歴')

@section('content')
    <h1>過去の診断結果</h1>
    <ul>
      @foreach ($consultations as $consultation)
        <li>
          <strong>{{ $consultation->partner_name }} さんの診断結果</strong><br>
          恋愛可能性: {{ $consultation->compatibility }}%<br>
          診断日: {{ $consultation->diagnosis_date }}<br>
          判定: {{ $consultation->go_or_wait }}<br>
          <button onclick="showDetails('{{ $consultation->diagnosis_content }}')">詳細を見る</button>
          <form action="{{ url('/history', ['id' => $consultation->id]) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit">削除</button>
          </form>
        </li>
      @endforeach
    </ul>
  </div>

  <script>
    function showDetails(details) {
      alert(details);
    }
  </script>
@endsection

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>相談履歴</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <nav>
    <a href="{{ url('/admin') }}">管理画面トップ</a>
    <a href="{{ url('/admin/history') }}">相談履歴</a>
  </nav>
  <div class="container">
    <h1>相談履歴</h1>
    <table>
      <thead>
        <tr>
          <th>相談者</th>
          <th>相談相手</th>
          <th>恋愛可能性(%)</th>
          <th>診断日</th>
          <th>WAIT/GO</th>
          <th>診断内容</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($consultations as $consultation)
          <tr>
            <td>{{ $consultation->user->name }}</td>
            <td>{{ $consultation->partner_name }}</td>
            <td>{{ $consultation->compatibility }}</td>
            <td>{{ $consultation->diagnosis_date }}</td>
            <td>{{ $consultation->go_or_wait }}</td>
            <td>{{ $consultation->diagnosis_content }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</body>
</html>

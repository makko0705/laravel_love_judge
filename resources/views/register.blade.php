@extends('layouts.app')

@section('title', '診断結果')

@section('content')
    <h1>診断結果</h1>
    <div id="result">
        <img id="resultImage" alt="診断結果の画像" />
        <p id="diagnosis"></p>
        <p id="compatibility"></p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const diagnosisResponse = sessionStorage.getItem('diagnosisResponse');
            if (diagnosisResponse) {
                const diagnosis = JSON.parse(diagnosisResponse);
                const resultImage = document.getElementById('resultImage');
                const diagnosisElement = document.getElementById('diagnosis');
                const compatibilityElement = document.getElementById('compatibility');

                const analysis = diagnosis.analysis;
                const partnerName = diagnosis.partnerName;

                if (analysis.GOorWAIT === 'GO') {
                    resultImage.src = '{{ asset('images/GO!.png') }}';
                } else {
                    resultImage.src = '{{ asset('images/WAIT!.png') }}';
                }

                diagnosisElement.textContent = analysis.診断結果;
                compatibilityElement.textContent = `${partnerName}さんとの恋愛可能性: ${analysis.恋愛可能性}`;
            } else {
                document.getElementById('result').textContent = '診断結果を取得できませんでした。';
            }
        });
    </script>
@endsection

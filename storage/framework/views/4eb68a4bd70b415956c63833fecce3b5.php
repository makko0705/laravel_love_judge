<?php $__env->startSection('title', '進行中'); ?>

<?php $__env->startSection('content'); ?>
    <h1>進行中...</h1>
    <div id="progressBarContainer">
        <div id="progressBar" style="width: 0%;">0%</div>
    </div>
    <p id="progress"></p>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const progressBar = document.getElementById('progressBar');
            const progressElement = document.getElementById('progress');

            const userName = <?php echo json_encode(session('userName'), 15, 512) ?>;
            const lineName = <?php echo json_encode(session('lineName'), 15, 512) ?>;
            const chatHistory = <?php echo json_encode(session('chatHistory'), 15, 512) ?>;
            const partnerName = <?php echo json_encode(session('partnerName'), 15, 512) ?>;

            const apiKeys = [
                <?php echo json_encode(env('OPENAI_API_KEY1'), 15, 512) ?>,
                <?php echo json_encode(env('OPENAI_API_KEY2'), 15, 512) ?>,
                <?php echo json_encode(env('OPENAI_API_KEY3'), 15, 512) ?>
            ];
            let apiKeyIndex = 0;

            const apiEndpoint = 'https://api.openai.com/v1/chat/completions';

            const headers = () => ({
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${apiKeys[apiKeyIndex++ % apiKeys.length]}`
            });

            const prompt = `以下のチャット履歴を分析し、診断結果を提供してください。診断内容を以下のJSON形式で返してください:
            {
              "恋愛可能性": "◯%",
              "GOorWAIT": "GO or WAIT",
              "診断結果": "テキストテキストテキストテキストテキストテキスト"
            }\n\n${chatHistory}`;

            const data = {
                model: 'gpt-4',
                messages: [{ role: 'user', content: prompt }],
            };

            const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));

            const fetchWithRetry = async (url, options, retries = 5, delayTime = 30000) => {
                for (let i = 0; i < retries; i++) {
                    const response = await fetch(url, options);
                    if (response.status !== 429) {
                        return response;
                    }
                    const retryAfter = response.headers.get('Retry-After');
                    const waitTime = retryAfter ? parseInt(retryAfter) * 1000 : delayTime;
                    console.log(`429 Too Many Requests - Retrying after ${waitTime} ms`);
                    await delay(waitTime);
                }
                throw new Error('Too many requests after multiple retries');
            };

            try {
                progressElement.textContent = 'Processing...';
                progressBar.style.width = `50%`;
                progressBar.textContent = `50%`;

                const response = await fetchWithRetry(apiEndpoint, {
                    method: 'POST',
                    headers: headers(),
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                let aggregatedResponse = '';
                if (result.choices && result.choices[0] && result.choices[0].message) {
                    aggregatedResponse = result.choices[0].message.content;
                } else {
                    console.error('Unexpected response structure:', result);
                    progressElement.textContent = 'Error: Unexpected response structure.';
                }

                const finalResult = {
                  analysis: JSON.parse(aggregatedResponse), // 修正
                  partnerName: partnerName // パートナーの名前も保存
                };

                // データベースに保存
                const response2 = await fetch('/save-consultation', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        userName,
                        lineName,
                        chatHistory,
                        partnerName,
                        diagnosisResult: finalResult.analysis
                    })
                });

                if (!response2.ok) {
                    throw new Error('HTTP error! status: ' + response2.status);
                }

                sessionStorage.setItem('diagnosisResponse', JSON.stringify(finalResult, null, 2));
                progressElement.textContent = 'Processing complete.';
                progressBar.style.width = `100%`;
                progressBar.textContent = `100%`;
                progressBar.style.color = 'white';

                window.location.href = '<?php echo e(route("result")); ?>';
            } catch (error) {
                console.error('Error:', error);
                progressElement.textContent = `Error: ${error.message}`;
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/progress.blade.php ENDPATH**/ ?>
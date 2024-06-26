document.addEventListener('DOMContentLoaded', async () => {
    if (window.location.pathname.endsWith('/progress')) {
      const chatHistory = sessionStorage.getItem('chatHistory');
      const userName = sessionStorage.getItem('userName');
      const progressBar = document.getElementById('progressBar');
      const progressElement = document.getElementById('progress');

      // 相手の名前を判別する関数
      const findPartnerName = (chatHistory, userName) => {
        const lines = chatHistory.split('\n');
        for (const line of lines) {
          const parts = line.split('\t');
          if (parts.length > 2) {
            const name = parts[1].trim();
            if (name !== userName) {
              return name;
            }
          }
        }
        return null;
      };

      const partnerName = findPartnerName(chatHistory, userName);
      sessionStorage.setItem('partnerName', partnerName);

      const fetchWithRetry = async (url, options, retries = 5, delayTime = 30000) => {
          for (let i = 0; i < retries; i++) {
              const response = await fetch(url, options);
              if (response.status !== 429) {
                  return response;
              }
              const retryAfter = response.headers.get('Retry-After');
              const waitTime = retryAfter ? parseInt(retryAfter) * 1000 : delayTime;
              console.log(`429 Too Many Requests - Retrying after ${waitTime} ms`);
              await new Promise(resolve => setTimeout(resolve, waitTime));
          }
          throw new Error('Too many requests after multiple retries');
      };

      const updateProgressBar = (percentage) => {
          progressBar.style.width = `${percentage}%`;
          progressBar.textContent = `${percentage}%`;
          if (percentage > 0) {
              progressBar.style.color = 'white';
          }
      };

      try {
          progressElement.textContent = 'Processing...';
          updateProgressBar(25);

          const response = await fetchWithRetry('/analyze', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({ chatHistory: chatHistory })
          });

          if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
          }

          updateProgressBar(50);

          const result = await response.json();
          console.log('API Response:', result); // Debug: Log the API response
          if (result.error) {
              throw new Error(result.error);
          }

          updateProgressBar(75);

          // Save the diagnosis response in session storage
          sessionStorage.setItem('diagnosisResponse', JSON.stringify(result));
          progressElement.textContent = 'Processing complete.';
          updateProgressBar(100);

          window.location.href = '/result';
      } catch (error) {
          console.error('Error:', error);
          progressElement.textContent = `Error: ${error.message}`;
      }
    }
  });

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Consultation;
use Illuminate\Support\Carbon;

class ChatController extends Controller
{
    private $apiKeys;
    private $apiEndpoint;

    public function __construct()
    {
        $this->apiKeys = [
            env('OPENAI_API_KEY1'),
            env('OPENAI_API_KEY2'),
            env('OPENAI_API_KEY3')
        ];
        $this->apiEndpoint = 'https://api.openai.com/v1/chat/completions';
    }

    public function index()
    {
        return view('index');
    }

    public function progress()
    {
        return view('progress');
    }

    public function result()
    {
        return view('result');
    }

    public function history()
    {
        $consultations = Consultation::with('user')->get();
        return view('history', compact('consultations'));
    }

    public function adminHistory()
    {
        $consultations = Consultation::with('user')->get();
        return view('admin.history', compact('consultations'));
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'userName' => 'required|string|max:255',
            'line_name' => 'required|string|max:255',
            'chatHistoryFile' => 'required|file|mimes:txt',
        ]);

        $userName = $request->input('userName');
        $lineName = $request->input('line_name');
        $chatHistoryFile = $request->file('chatHistoryFile');

        try {
            if (empty($userName) || empty($lineName)) {
                throw new \Exception('ユーザー名またはLINE名が空です');
            }

            // 自分の名前の登録
            $user = User::where('name', $userName)->where('line_name', $lineName)->first();
            if (!$user) {
                throw new \Exception('ユーザーが見つかりません');
            }

            // ファイルからテキストを読み込む
            $chatHistory = file_get_contents($chatHistoryFile->getRealPath());

            // 相手の名前を履歴の冒頭から取得
            $partnerName = $this->extractPartnerName($chatHistory);
            if (!$partnerName) {
                throw new \Exception('相手の名前が見つかりません');
            }

            // セッションに必要なデータを保存
            session([
                'userName' => $userName,
                'lineName' => $lineName,
                'chatHistory' => $chatHistory,
                'partnerName' => $partnerName
            ]);

            return redirect()->route('progress');
        } catch (\Exception $e) {
            Log::error('API Request failed: ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
            return response()->json(['error' => 'API Request failed: ' . $e->getMessage()], 500);
        }
    }

    private function fetchWithRetry($data, &$apiKeyIndex, $retries = 5, $delayTime = 30000)
    {
        for ($i = 0; $i < $retries; $i++) {
            $apiKey = $this->apiKeys[$apiKeyIndex++ % count($this->apiKeys)];
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$apiKey}",
            ])->post($this->apiEndpoint, $data);

            if ($response->status() !== 429) {
                return $response->json();
            }

            $retryAfter = $response->header('Retry-After');
            $waitTime = $retryAfter ? (int)$retryAfter * 1000 : $delayTime;
            usleep($waitTime * 1000);
        }

        throw new \Exception('Too many requests after multiple retries');
    }

    private function extractPartnerName($chatHistory)
    {
        // "おくやまいたるとのトーク履歴" の部分を取得
        preg_match('/\[LINE\] (.*?)とのトーク履歴/', $chatHistory, $matches);
        return $matches[1] ?? null;
    }

    public function saveConsultation(Request $request)
    {
        try {
            $user = User::where('name', $request->input('userName'))->where('line_name', $request->input('lineName'))->first();
            if (!$user) {
                throw new \Exception('ユーザーが見つかりません');
            }

            $diagnosisResult = $request->input('diagnosisResult');

            Consultation::create([
                'user_id' => $user->id,
                'partner_name' => $request->input('partnerName'),
                'diagnosis_content' => $diagnosisResult['診断結果'],
                'compatibility' => (int) str_replace('%', '', $diagnosisResult['恋愛可能性']),
                'diagnosis_date' => Carbon::now(),
                'go_or_wait' => $diagnosisResult['GOorWAIT']
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('API Request failed: ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
            return response()->json(['error' => 'API Request failed: ' . $e->getMessage()], 500);
        }
    }
}

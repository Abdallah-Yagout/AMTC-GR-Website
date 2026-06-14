<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:120'],
            'page_url' => ['nullable', 'url', 'max:1200'],
        ]);

        $webhookUrl = (string) config('services.n8n.webhook_url');
        $token = (string) config('services.n8n.token');
        $timeout = (int) config('services.n8n.timeout', 20);

        if ($webhookUrl === '') {
            return response()->json([
                'ok' => false,
                'reply' => __('Chat service is not configured yet. Please try again later.'),
            ], 503);
        }

        try {
            $payload = [
                'message' => $validated['message'],
                'locale' => app()->getLocale(),
                'page_url' => $validated['page_url'] ?? $request->headers->get('referer'),
                'session_id' => $validated['session_id'] ?? $request->session()->getId(),
                'request_id' => (string) Str::uuid(),
            ];

            $http = Http::acceptJson()
                ->timeout($timeout)
                ->withOptions(['verify' => true]);

            if ($token !== '') {
                $http = $http->withToken($token);
            }

            $response = $http->post($webhookUrl, $payload);

            if ($response->failed()) {
                return response()->json([
                    'ok' => false,
                    'reply' => __('The assistant is temporarily unavailable. Please try again in a moment.'),
                ], 502);
            }

            $data = $response->json();
            $reply = $this->extractReplyFromN8nResponse($data);

            if ($reply === null || trim($reply) === '') {
                $reply = __('I received your message, but I could not generate a response yet.');
            }

            return response()->json([
                'ok' => true,
                'reply' => $reply,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'reply' => __('The assistant is temporarily unavailable. Please try again in a moment.'),
            ], 500);
        }
    }

    private function extractReplyFromN8nResponse(mixed $data): ?string
    {
        if (is_string($data)) {
            return $data;
        }

        if (! is_array($data)) {
            return null;
        }

        foreach (['reply', 'message', 'output', 'text', 'response'] as $key) {
            if (isset($data[$key]) && is_string($data[$key]) && trim($data[$key]) !== '') {
                return $data[$key];
            }
        }

        if (isset($data['data']) && is_array($data['data'])) {
            foreach (['reply', 'message', 'output', 'text', 'response'] as $key) {
                if (isset($data['data'][$key]) && is_string($data['data'][$key]) && trim($data['data'][$key]) !== '') {
                    return $data['data'][$key];
                }
            }
        }

        if (isset($data[0]) && is_array($data[0])) {
            foreach (['reply', 'message', 'output', 'text', 'response'] as $key) {
                if (isset($data[0][$key]) && is_string($data[0][$key]) && trim($data[0][$key]) !== '') {
                    return $data[0][$key];
                }
            }
        }

        return null;
    }
}

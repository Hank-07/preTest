<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(OrderRequest $request): JsonResponse
    {
        try {
            // 通過驗證的資料
            $validatedData = $request->validated();

            // 在服務層進行額外的檢查
            $processedData = $this->orderService->processOrder($validatedData);

            // 返回成功的回應
            return response()->json($processedData);
        } catch (ValidationException $e) {
            // 驗證錯誤回應
            $errorMessage = Arr::first(Arr::first($e->errors()));
            // 返回自訂格式的錯誤訊息
            return response()->json([
                'status' => false,
                'errors' => $errorMessage
            ], 400);
        }
    }
}

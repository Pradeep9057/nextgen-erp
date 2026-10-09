<?php

namespace App\Modules\Sales\Services;

use App\Modules\Sales\Models\SalesOrder;
use App\Modules\Sales\Models\OrderApproval;
use App\Core\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class ApprovalService extends BaseService
{
    public function __construct(\App\Modules\Sales\Models\OrderApproval $approvalModel)
    {
        parent::__construct($approvalModel);
    }

    public function requestApproval(int $orderId, string $comments = null): OrderApproval
    {
        return DB::transaction(function () use ($orderId, $comments) {
            $order = SalesOrder::findOrFail($orderId);

            if ($order->status !== 'Pending') {
                throw new Exception("Only Pending orders can request approval.");
            }

            return OrderApproval::create([
                'sales_order_id' => $order->id,
                'user_id' => Auth::id() ?? 1,
                'status' => OrderApproval::STATUS_PENDING,
                'comments' => $comments,
            ]);
        });
    }

    public function processApproval(int $approvalId, string $status, string $comments = null): OrderApproval
    {
        return DB::transaction(function () use ($approvalId, $status, $comments) {
            $approval = OrderApproval::findOrFail($approvalId);
            $order = SalesOrder::findOrFail($approval->sales_order_id);

            if (!in_array($status, [OrderApproval::STATUS_APPROVED, OrderApproval::STATUS_REJECTED])) {
                throw new Exception("Invalid approval status.");
            }

            $approval->update([
                'status' => $status,
                'comments' => $comments,
                'approved_at' => now(),
            ]);

            // Update order status based on approval
            $orderStatus = ($status === OrderApproval::STATUS_APPROVED) ? 'Approved' : 'Rejected';
            $order->update(['status' => $orderStatus]);

            return $approval;
        });
    }
}

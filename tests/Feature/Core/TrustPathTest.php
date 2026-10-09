<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Core\Services\IntegrityManager;
use App\Core\Services\IntegrityService;
use App\Core\Models\IntegrityLog;

class TrustPathTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamper_detection_workflow()
    {
        $integrityManager = app(IntegrityManager::class);

        $data = ['amount' => 1000, 'status' => 'Paid'];

        // 1. Seal the record
        $hash1 = $integrityManager->sealRecord('Invoice', 1, $data);

        // 2. Create a second seal (update)
        $data['status'] = 'Refunded';
        $hash2 = $integrityManager->sealRecord('Invoice', 1, $data);

        // 3. Verify chain is intact
        $this->assertTrue($integrityManager->verifyChain('Invoice', 1));

        // 4. Simulate Tampering (manually modify a log entry)
        IntegrityLog::where('hash', $hash1)->update(['hash' => 'corrupted_hash']);

        // 5. Verify chain is now broken
        $this->assertFalse($integrityManager->verifyChain('Invoice', 1));
    }
}

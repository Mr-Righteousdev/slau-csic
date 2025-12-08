<?php

use App\Models\User;
use App\Models\Transaction;
use App\Models\BudgetCategory;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_treasurer_can_access_transaction_management(): void
    {
        $user = User::factory()->create();
        $user->assignRole('treasurer');

        $response = $this->actingAs($user)
            ->get('/admin/transactions');

        $response->assertStatus(200);
        $response->assertSeeLivewire('transaction-management');
    }

    /** @test */
    public function test_president_can_access_transaction_management(): void
    {
        $user = User::factory()->create();
        $user->assignRole('president');

        $response = $this->actingAs($user)
            ->get('/admin/transactions');

        $response->assertStatus(200);
        $response->assertSeeLivewire('transaction-management');
    }

    /** @test */
    public function test_super_admin_can_access_transaction_management(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $response = $this->actingAs($user)
            ->get('/admin/transactions');

        $response->assertStatus(200);
        $response->assertSeeLivewire('transaction-management');
    }

    /** @test */
    public function test_regular_user_cannot_access_transaction_management(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/admin/transactions');

        $response->assertStatus(403);
    }

    /** @test */
    public function test_can_create_transaction(): void
    {
        $user = User::factory()->create();
        $user->assignRole('treasurer');

        Livewire::test('transaction-management')
            ->actingAs($user)
            ->call('mount')
            ->set('formData.type', 'income')
            ->set('formData.category', 'Membership Dues')
            ->set('formData.amount', 100)
            ->set('formData.description', 'Test transaction')
            ->set('formData.paid_to_from', 'Test Member')
            ->set('formData.payment_method', 'cash')
            ->call('create')
            ->assertDispatched('notification')
            ->assertDatabaseHas('transactions', [
                'type' => 'income',
                'category' => 'Membership Dues',
                'amount' => 100.00,
                'description' => 'Test transaction',
                'paid_to_from' => 'Test Member',
                'payment_method' => 'cash',
                'created_by' => $user->id,
            ]);
    }

    /** @test */
    public function test_can_approve_transaction(): void
    {
        $approver = User::factory()->create();
        $approver->assignRole('treasurer');
        
        $transaction = Transaction::factory()->create([
            'status' => 'pending',
            'amount' => 150.00,
        ]);

        Livewire::test('transaction-management')
            ->actingAs($approver)
            ->call('mount')
            ->call('tableAction', 'approve', $transaction)
            ->assertDispatched('notification')
            ->assertDatabaseHas('transactions', [
                'status' => 'approved',
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);
    }

    /** @test */
    public function test_can_reject_transaction(): void
    {
        $approver = User::factory()->create();
        $approver->assignRole('treasurer');
        
        $transaction = Transaction::factory()->create([
            'status' => 'pending',
            'amount' => 150.00,
        ]);

        Livewire::test('transaction-management')
            ->actingAs($approver)
            ->call('mount')
            ->call('tableAction', 'reject', $transaction)
            ->assertDispatched('notification')
            ->assertDatabaseHas('transactions', [
                'status' => 'rejected',
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);
    }

    /** @test */
    public function test_can_bulk_approve_transactions(): void
    {
        $approver = User::factory()->create();
        $approver->assignRole('treasurer');
        
        $transactions = Transaction::factory()->count(3)->create([
            'status' => 'pending',
        ]);

        Livewire::test('transaction-management')
            ->actingAs($approver)
            ->call('mount')
            ->call('bulkAction', 'approve', $transactions)
            ->assertDispatched('notification')
            ->assertDatabaseHas('transactions', [
                'status' => 'approved',
                'approved_by' => $approver->id,
            ]);
    }

    /** @test */
    public function test_filters_work_correctly(): void
    {
        $user = User::factory()->create();
        $user->assignRole('treasurer');

        // Create test data
        Transaction::factory()->income()->count(2)->create();
        Transaction::factory()->expense()->count(3)->create();

        Livewire::test('transaction-management')
            ->actingAs($user)
            ->call('mount')
            ->assertSet('table.filters.type', 'income')
            ->assertSee('Income Transaction 1')
            ->assertSee('Income Transaction 2')
            ->assertDontSee('Expense Transaction')
            ->call('clearFilter', 'type')
            ->assertSet('table.filters.type', 'expense')
            ->assertSee('Expense Transaction 1')
            ->assertSee('Expense Transaction 2')
            ->assertSee('Expense Transaction 3')
            ->assertDontSee('Income Transaction');
    }
}
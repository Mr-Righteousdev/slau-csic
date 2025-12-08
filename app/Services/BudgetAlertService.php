<?php

namespace App\Services;

use App\Models\BudgetCategory;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class BudgetAlertService
{
    public static function checkBudgetAlerts(): void
    {
        $activeCategories = BudgetCategory::where('is_active', true)->get();
        
        foreach ($activeCategories as $category) {
            $spent = Transaction::where('category', $category->name)
                ->where('status', 'approved')
                ->where('type', $category->type)
                ->whereYear('date', now()->year)
                ->sum('amount');
                
            $allocated = $category->allocated_amount;
            
            if ($allocated > 0) {
                $percentage = ($spent / $allocated) * 100;
                
                // Check for different alert levels
                if ($percentage >= 100) {
                    self::sendAlert($category, 'over_budget', $spent, $allocated, $percentage);
                } elseif ($percentage >= 90) {
                    self::sendAlert($category, 'critical', $spent, $allocated, $percentage);
                } elseif ($percentage >= 80) {
                    self::sendAlert($category, 'warning', $spent, $allocated, $percentage);
                }
            }
        }
    }
    
    private static function sendAlert(BudgetCategory $category, string $alertLevel, float $spent, float $allocated, float $percentage): void
    {
        $message = self::formatAlertMessage($category, $alertLevel, $spent, $allocated, $percentage);
        
        // Log the alert
        Log::warning('Budget Alert', [
            'category' => $category->name,
            'alert_level' => $alertLevel,
            'spent' => $spent,
            'allocated' => $allocated,
            'percentage' => $percentage,
            'message' => $message,
        ]);
        
        // Here you could send notifications to treasurer/president
        // For now, we'll just log it
    }
    
    public static function formatAlertMessage(BudgetCategory $category, string $alertLevel, float $spent, float $allocated, float $percentage): string
    {
        $categoryType = ucfirst($category->type);
        
        switch ($alertLevel) {
            case 'over_budget':
                return "Budget Alert: {$categoryType} category '{$category->name}' has exceeded budget by $" . number_format($spent - $allocated, 2) . " ({$percentage}% used).";
                
            case 'critical':
                return "Critical Budget Alert: {$categoryType} category '{$category->name}' has used {$percentage}% of budget ($" . number_format($spent, 2) . " / $" . number_format($allocated, 2) . "). Only 10% remaining.";
                
            case 'warning':
                return "Budget Warning: {$categoryType} category '{$category->name}' has used {$percentage}% of budget ($" . number_format($spent, 2) . " / $" . number_format($allocated, 2) . ").";
                
            default:
                return "Budget Alert for {$categoryType} category '{$category->name}': {$percentage}% used.";
        }
    }
    
    public static function getBudgetStatus(): array
    {
        $activeCategories = BudgetCategory::where('is_active', true)->get();
        $alerts = [];
        
        foreach ($activeCategories as $category) {
            $spent = Transaction::where('category', $category->name)
                ->where('status', 'approved')
                ->where('type', $category->type)
                ->whereYear('date', now()->year)
                ->sum('amount');
                
            $allocated = $category->allocated_amount;
            $percentage = $allocated > 0 ? ($spent / $allocated) * 100 : 0;
            
            if ($percentage >= 80) {
                $alerts[] = [
                    'category' => $category->name,
                    'type' => $category->type,
                    'spent' => $spent,
                    'allocated' => $allocated,
                    'percentage' => $percentage,
                    'status' => $percentage >= 100 ? 'over_budget' : ($percentage >= 90 ? 'critical' : 'warning'),
                    'remaining' => max(0, $allocated - $spent),
                ];
            }
        }
        
        return $alerts;
    }
}
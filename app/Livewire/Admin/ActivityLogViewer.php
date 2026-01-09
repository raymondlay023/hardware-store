<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogViewer extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    
    #[Url]
    public $filterUser = '';
    
    #[Url]
    public $filterModel = '';
    
    #[Url]
    public $filterAction = '';
    
    public $dateFrom = '';
    public $dateTo = '';
    public $expandedLog = null;

    public function mount()
    {
        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterUser()
    {
        $this->resetPage();
    }

    public function updatedFilterModel()
    {
        $this->resetPage();
    }

    public function updatedFilterAction()
    {
        $this->resetPage();
    }

    public function toggleExpand($logId)
    {
        $this->expandedLog = $this->expandedLog === $logId ? null : $logId;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterUser = '';
        $this->filterModel = '';
        $this->filterAction = '';
        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function exportCsv()
    {
        $logs = $this->getFilteredLogs()->get();

        $csv = "ID,User,Action,Model,Model ID,IP Address,Created At\n";
        foreach ($logs as $log) {
            $csv .= implode(',', [
                $log->id,
                '"' . ($log->user ? $log->user->name : 'System') . '"',
                $log->action,
                class_basename($log->model_type),
                $log->model_id,
                $log->ip_address,
                $log->created_at->toDateTimeString(),
            ]) . "\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'activity-logs-' . now()->format('Y-m-d') . '.csv');
    }

    private function getFilteredLogs()
    {
        $query = ActivityLog::with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('model_type', 'like', '%' . $this->search . '%')
                  ->orWhere('action', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->filterUser) {
            $query->where('user_id', $this->filterUser);
        }

        if ($this->filterModel) {
            $query->where('model_type', 'like', '%' . $this->filterModel);
        }

        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function render()
    {
        $logs = $this->getFilteredLogs()->paginate(20);

        // Get unique model types for filter
        $modelTypes = ActivityLog::select('model_type')
            ->distinct()
            ->pluck('model_type')
            ->map(fn($type) => class_basename($type));

        $users = User::orderBy('name')->get(['id', 'name']);

        $actions = ['created', 'updated', 'deleted'];

        return view('livewire.admin.activity-log-viewer', [
            'logs' => $logs,
            'modelTypes' => $modelTypes,
            'users' => $users,
            'actions' => $actions,
        ]);
    }
}

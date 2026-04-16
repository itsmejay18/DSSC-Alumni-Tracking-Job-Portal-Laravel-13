<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public array $userIds,
        public string $type,
        public string $title,
        public string $message,
        public array $data = [],
    ) {
    }

    public function handle(NotificationService $notificationService): void
    {
        $users = User::query()->whereIn('id', $this->userIds)->get();

        $notificationService->sendBulk($users, $this->type, $this->title, $this->message, $this->data);
    }
}

<?php

namespace Modules\Post\Traits;

use Modules\Post\Entities\Post;
use Illuminate\Support\Facades\Queue;
use Modules\Post\Jobs\UpdatePostStatus;
use Illuminate\Support\Carbon;

trait SchedulePostStatusUpdate
{
    protected function schedulePostStatusUpdate(Post $post): void
    {
        $this->cancelExistingUpdateJob($post);
    
        if ($post->published_at) {
            $publishedAt = Carbon::parse($post->published_at);
            if ($publishedAt->isFuture() && $post->status === 'draft') {
                UpdatePostStatus::dispatch($post->id)->delay($publishedAt);
            }
        }
    }

    protected function cancelExistingUpdateJob(Post $post): void
    {
        $connection = Queue::connection();

        if (method_exists($connection, 'getJobsOfQueue')) {
            $jobs = $connection->getJobsOfQueue('default');
            foreach ($jobs as $job) {
                $this->deleteJobIfMatches($job, $post->id);
            }
        } elseif ($connection instanceof \Illuminate\Queue\SyncQueue) {
            return;
        } else {
            // Handle other queue types or log a warning
        }
    }

    protected function deleteJobIfMatches($job, $postId): void
    {
        $payload = $job->payload();
        if (
            isset($payload['data']['command']) &&
            $payload['data']['command'] === UpdatePostStatus::class &&
            $payload['data']['command_data']['postId'] === $postId
        ) {
            $job->delete();
        }
    }
}
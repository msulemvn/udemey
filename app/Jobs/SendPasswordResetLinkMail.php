<?php

namespace App\Jobs;

use App\Mail\PasswordResetLinkMail;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPasswordResetLinkMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->data['email'])->send(new PasswordResetLinkMail($this->data));
    }

    public function sendMail(Request $request)
    {
        $data = $request['data'];

        SendPasswordResetMail::dispatch($data);

        // Assert mail was sent
        Mail::assertSent(PasswordResetLinkMail::class, function ($mail) use ($data) {
            return $mail->data === $data;
        });
    }
}

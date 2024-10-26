<?php

namespace App\Jobs;

use Illuminate\Http\Request;
use App\Mail\VerifyEmailMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendEmailVerificationMail implements ShouldQueue
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
        Mail::to($this->data['email'])->send(new VerifyEmailMail($this->data));
    }

    public function sendMail(Request $request)
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'Hello, this is a custom mail!',
        ];

        SendEmailVerificationMail::dispatch($data);

        // Assert mail was sent
        Mail::assertSent(VerifyEmailMail::class, function ($mail) use ($data) {
            return $mail->data === $data;
        });
    }
}

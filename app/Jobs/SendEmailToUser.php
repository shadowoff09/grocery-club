<?php

namespace App\Jobs;

use App\Mail\GenericEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The user to send the email to.
     *
     * @var string|User
     */
    protected $recipient;

    /**
     * The email subject.
     *
     * @var string
     */
    protected $subject;

    /**
     * The email template view.
     *
     * @var string
     */
    protected $view;

    /**
     * Data to pass to the email template.
     *
     * @var array
     */
    protected $data;

    /**
     * File paths to attach to the email.
     *
     * @var array
     */
    protected $attachments;

    /**
     * Create a new job instance.
     *
     * @param string|User $recipient
     * @param string $subject
     * @param string $view
     * @param array $data
     * @param array $attachments Array of file paths to attach
     */
    public function __construct($recipient, string $subject, string $view, array $data = [], array $attachments = [])
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
        $this->attachments = $attachments;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipient = $this->recipient instanceof User ? $this->recipient->email : $this->recipient;

        $email = new GenericEmail($this->subject, $this->view, $this->data);

        // Add attachments if any
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $path) {
                $email->attach($path);
            }
        }

        Mail::to($recipient)->send($email);
    }
}

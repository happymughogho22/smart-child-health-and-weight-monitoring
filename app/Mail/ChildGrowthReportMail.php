<?php

namespace App\Mail;

use App\Models\Child;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChildHealthReportMail extends Mailable
{
    use Queueable, SerializesModels;


    public Child $child;

    public array $reportData;

    public string $pdfContent;

    public string $fileName;


    /**
     * Create a new message instance.
     */
    public function __construct(
        Child $child,
        array $reportData,
        string $pdfContent,
        string $fileName
    ) {

        $this->child =
            $child;

        $this->reportData =
            $reportData;

        $this->pdfContent =
            $pdfContent;

        $this->fileName =
            $fileName;
    }


    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject(
                'Child Health Monitoring Report - ' .
                $this->child->first_name .
                ' ' .
                $this->child->last_name
            )
            ->view(
                'emails.child-health-report'
            )
            ->attachData(
                $this->pdfContent,
                $this->fileName,
                [
                    'mime' =>
                        'application/pdf',
                ]
            );
    }
}
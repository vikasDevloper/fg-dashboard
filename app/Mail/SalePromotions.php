<?php

namespace Dashboard\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SalePromotions extends Mailable implements ShouldQueue {
	use Queueable, SerializesModels;

	/**
	 * The mails instance.
	 *
	 * @var Mails
	 */
	protected $mails;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	
	public function __construct($mails) {
		//
		$this->mails = $mails;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {

		return $this->view($this->mails['template'])
		           
		            ->subject($this->mails['subject'])
			->replyTo(config('mail.reply-to.address'), config('mail.reply-to.name'))
			->with([
				'subject'       => $this->mails['subject'],
				'mcPreviewText' => $this->mails['mcPreviewText'],
				'name'          => $this->mails['name'],
				'url'           => $this->mails['url']
			]);

	}
}

<?php
declare(strict_types=1);
/**
 * @package         Joomla.Site
 * @subpackage      mod_liqpay
 *
 * @author          M.Kulyk
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 * @since
 */

namespace Joomla\Module\Liqpay\Site\Library\Traits;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Mail\Mail;


use Joomla\CMS\Factory;
use PHPMailer\PHPMailer\Exception as PHPMailerException;


trait MailerTrait
{

    private function mailer(): Mail
    {
        return Factory::getMailer();
    }


    /**
     * @param CMSApplicationInterface $app
     * @param string                  $recipient
     * @param string                  $subject
     * @param string                  $body
     * @param array                   $options
     *
     * @return bool
     * @throws PHPMailerException
     * @author overnet
     * @since
     */
    final public function sendServiceMail(CMSApplicationInterface $app, string $recipient, string $subject, string $body, array $options = [
        'mode' => false, 'cc' => null, 'bcc' => null, 'attachment' => null, 'replyTo' => null, 'replyToName' => null
    ]): bool
    {
        return $this->mailer()->sendMail(
            $app->get('mailfrom'),
            $app->get('fromname'),
            $recipient,
            $subject,
            $body,
            $options['mode'],
            $options['cc'],
            $options['bcc'],
            $options['attachment'],
            $options['replyTo'],
            $options['replyToName'],
        );
    }
}
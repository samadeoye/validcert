<?php
namespace ValidCert\SendMail;

use Exception;

class SendMail
{
    static $isSent = false;

    /**
     * Send an email
     * @param array $arParams
     * @throws \Exception
     * @return void
     */
    public static function sendDefaultMail($arParams)
    {
        global $arSiteSettings;

        try
        {
            $mailTo = $arParams['mailTo'];
            $mailFrom = $arParams['mailFrom'];
            $fromName = $arParams['fromName'];
            $subject =  (array_key_exists('subject', $arParams)) ? $arParams['subject'] : 'Mail From '.$arSiteSettings['name'];
            $body = $arParams['body'];

            
            $emailHeaders = "From: $fromName <$mailFrom>";

            if (mail($mailTo, $subject, $body, $emailHeaders))
            {
                self::$isSent = true;
            }
            else
            {
                throw new Exception('An error occured. Please try again.');
            }
        }
        catch(Exception $e)
        {
            self::$isSent = false;
            getJsonRow(false, 'An error occured');
        }
    }
}
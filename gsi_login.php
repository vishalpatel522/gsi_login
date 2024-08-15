<?php
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;

class PlgSystemGsi_Login extends CMSPlugin
{
    protected $app;

    public function onAfterRender()
    {
        $user = Factory::getUser();

        if ($user->guest) {

            $app = Factory::getApplication();

            $doc = Factory::getDocument();
            $baseUri = Uri::base();
            $loginUri = $this->params->get('login_uri', '');
            $client_id =  $this->params->get('client_id', '');
            if ($client_id != "") {
                // Add Google Sign-In div and script
                $script = "
                <script src='https://accounts.google.com/gsi/client' async></script>
                <div id='g_id_onload'
                    data-client_id='" . $client_id . "'
                    data-context='signin'
                    data-login_uri='" .$baseUri. $loginUri . "'
                    data-itp_support='true'>
                </div>";

                $option = $app->input->getCmd('option');
                if ($option === 'com_community') {
                    $body = $app->getBody();
                    $body = str_replace('</body>', $script . '</body>', $body);
                    $app->setBody($body);
                } else {
                    $doc->addCustomTag($script);
                }
            }
        }
    }
}

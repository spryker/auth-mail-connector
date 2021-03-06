<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Business\Mail;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig;
use Spryker\Zed\AuthMailConnector\Communication\Plugin\Mail\RestorePasswordMailTypePlugin;

class MailBuilder implements MailBuilderInterface
{
    /**
     * @uses \Spryker\Zed\Auth\Communication\Controller\PasswordController::PARAM_TOKEN
     */
    protected const PARAM_TOKEN = 'token';

    /**
     * @var \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\AuthMailConnectorExtension\Dependency\Plugin\AuthMailExpanderPluginInterface[]
     */
    protected $authMailExpanderPlugins;

    /**
     * @param \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig $config
     * @param \Spryker\Zed\AuthMailConnectorExtension\Dependency\Plugin\AuthMailExpanderPluginInterface[] $authMailExpanderPlugins
     */
    public function __construct(
        AuthMailConnectorConfig $config,
        array $authMailExpanderPlugins
    ) {
        $this->config = $config;
        $this->authMailExpanderPlugins = $authMailExpanderPlugins;
    }

    /**
     * @param string $email
     * @param string $token
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function buildResetPasswordMailTransfer(string $email, string $token): MailTransfer
    {
        $mailTransfer = new MailTransfer();
        $mailTransfer->setType(RestorePasswordMailTypePlugin::MAIL_TYPE);
        $mailTransfer->addRecipient($this->createMailRecipientTransfer($email));
        $mailTransfer->setResetPasswordLink($this->generateResetPasswordLink($token));

        $mailTransfer = $this->executeAuthMailExpanderPlugins($mailTransfer);

        return $mailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function executeAuthMailExpanderPlugins(MailTransfer $mailTransfer): MailTransfer
    {
        foreach ($this->authMailExpanderPlugins as $authMailExpanderPlugin) {
            $mailTransfer = $authMailExpanderPlugin->expand($mailTransfer);
        }

        return $mailTransfer;
    }

    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\MailRecipientTransfer
     */
    protected function createMailRecipientTransfer(string $email): MailRecipientTransfer
    {
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);

        return $mailRecipientTransfer;
    }

    /**
     * @param string $token
     *
     * @return string
     */
    protected function generateResetPasswordLink(string $token): string
    {
        $query = $this->generateResetPasswordLinkQuery($token);

        return sprintf('%s%s?%s', $this->config->getBaseUrlZed(), $this->config->getAuthPasswordResetPath(), $query);
    }

    /**
     * @param string $token
     *
     * @return string
     */
    protected function generateResetPasswordLinkQuery(string $token): string
    {
        return http_build_query([
            static::PARAM_TOKEN => $token,
        ]);
    }
}

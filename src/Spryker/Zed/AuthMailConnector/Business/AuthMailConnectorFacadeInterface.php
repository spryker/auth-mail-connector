<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Business;

interface AuthMailConnectorFacadeInterface
{
    /**
     * Specification:
     * - Generates MailTransfer for reset password functionality.
     * - Calls a stack of `AuthMailExpanderPluginInterface` to expand the MailTransfer with an additional data.
     * - Uses {@link \Spryker\Zed\Mail\Business\MailFacadeInterface::handleMail()} to handle generated MailTransfer.
     *
     * @api
     *
     * @param string $email
     * @param string $token
     *
     * @return void
     */
    public function sendResetPasswordMail(string $email, string $token): void;
}

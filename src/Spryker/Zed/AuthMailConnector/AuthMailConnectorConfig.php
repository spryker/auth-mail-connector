<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector;

use Spryker\Shared\AuthMailConnector\AuthMailConnectorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AuthMailConnectorConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getBaseUrlZed(): string
    {
        return $this->get(AuthMailConnectorConstants::BASE_URL_ZED);
    }
}

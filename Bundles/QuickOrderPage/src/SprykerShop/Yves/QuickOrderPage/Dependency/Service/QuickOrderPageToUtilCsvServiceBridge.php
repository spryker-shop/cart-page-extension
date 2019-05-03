<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Service;

use Generated\Shared\Transfer\CsvFileTransfer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuickOrderPageToUtilCsvServiceBridge implements QuickOrderPageToUtilCsvServiceInterface
{
    /**
     * @var \Spryker\Service\UtilCsv\UtilCsvServiceInterface
     */
    protected $utilCsvService;

    /**
     * @param \Spryker\Service\UtilCsv\UtilCsvServiceInterface $utilCsvService
     */
    public function __construct($utilCsvService)
    {
        $this->utilCsvService = $utilCsvService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return array
     */
    public function readUploadedFile(UploadedFile $file): array
    {
        return $this->utilCsvService->readUploadedFile($file);
    }

    /**
     * @param \Generated\Shared\Transfer\CsvFileTransfer $csvFileTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportFile(CsvFileTransfer $csvFileTransfer): StreamedResponse
    {
        return $this->utilCsvService->exportFile($csvFileTransfer);
    }
}

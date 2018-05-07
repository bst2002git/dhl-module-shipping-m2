<?php
/**
 * Dhl Shipping
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to
 * newer versions in the future.
 *
 * PHP version 7
 *
 * @package   Dhl\Shipping\Plugin
 * @author    Sebastian Ertner <sebastian.ertner@netresearch.de>
 * @copyright 2018 Netresearch GmbH & Co. KG
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.netresearch.de/
 */

namespace Dhl\Shipping\Plugin\Adminhtml\Packaging;

use Magento\Framework\Json\DecoderInterface;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\UrlInterface;
use Magento\Shipping\Block\Adminhtml\Order\Packaging;

/**
 * PackagingPlugin
 *
 * @package  Dhl\Shipping\Plugin
 * @author   Sebastian Ertner <sebastian.ertner@netresearch.de>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     http://www.netresearch.de/
 */
class PackagingPlugin
{
    /**
     * @var DecoderInterface
     */
    private $jsonDecoder;

    /**
     * @var EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * PackagingPlugin constructor.
     * @param DecoderInterface $jsonDecoder
     * @param EncoderInterface $jsonEncoder
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        DecoderInterface $jsonDecoder,
        EncoderInterface $jsonEncoder,
        UrlInterface $urlBuilder
    ) {
        $this->jsonEncoder = $jsonEncoder;
        $this->jsonDecoder = $jsonDecoder;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param Packaging $subject
     * @param string $result
     * @return string
     */
    public function afterGetConfigDataJson(Packaging $subject, $result)
    {
        $data = $this->jsonDecoder->decode($result);
        $urlParams = [
            'shipment_id' => $subject->getRequest()->getParam('shipment_id'),
            'order_id' => $subject->getRequest()->getParam('order_id'),
        ];

        $data['itemsGridUrl'] = $this->urlBuilder->getUrl('dhl/order_shipment/getShippingItemsGrid', $urlParams);

        return $this->jsonEncoder->encode($data);
    }
}
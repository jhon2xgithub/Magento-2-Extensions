<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

?>
<?php
/**
 * Template for displaying new products widget
 *
 * @var $block \Magento\Catalog\Block\Product\Widget\NewWidget
 */
if ($exist = ($block->getProductCollection() && $block->getProductCollection()->getSize())) {
    $type = 'widget-new-list';

    $mode = 'list';

    $image = 'new_products_content_widget_list';
    $title = __('New Products');
    $items = $block->getProductCollection()->getItems();
    $_helper = $this->helper('Magento\Catalog\Helper\Output');

    $showWishlist = true;
    $showCompare = true;
    $showCart = true;
    $templateType = \Magento\Catalog\Block\Product\ReviewRendererInterface::DEFAULT_VIEW;
    $description = true;
}
?>

<?php if ($exist):?>
    <div class="block widget block-new-products <?php /* @escapeNotVerified */ echo $mode; ?>">
        <div class="block-title">
            <strong role="heading" aria-level="2"><?php /* @escapeNotVerified */ echo $title; ?></strong>
        </div>
        <div class="block-content">
            <?php /* @escapeNotVerified */ echo '<!-- ' . $image . '-->' ?>
            <div class="products-<?php /* @escapeNotVerified */ echo $mode; ?> <?php /* @escapeNotVerified */ echo $mode; ?>">
                <ol class="product-items <?php /* @escapeNotVerified */ echo $type; ?>">
                    <?php $iterator = 1; ?>
                    <?php foreach ($items as $_item): ?>
                        <?php /* @escapeNotVerified */ echo($iterator++ == 1) ? '<li class="product-item">' : '</li><li class="product-item">' ?>
                        <div class="product-item-info">
                            <a href="<?php /* @escapeNotVerified */ echo $block->getProductUrl($_item) ?>" class="product-item-photo">
                                <?php echo $block->getImage($_item, $image)->toHtml(); ?>
                            </a>
                            <div class="product-item-details">
                                <strong class="product-item-name">
                                    <a title="<?php echo $block->escapeHtml($_item->getName()) ?>"
                                       href="<?php /* @escapeNotVerified */ echo $block->getProductUrl($_item) ?>"
                                       class="product-item-link">
                                        <?php echo $block->escapeHtml($_item->getName()) ?>
                                    </a>
                                </strong>
                                <?php echo $block->getProductPriceHtml($_item, $type); ?>

                                <?php if ($templateType): ?>
                                    <?php echo $block->getReviewsSummaryHtml($_item, $templateType) ?>
                                <?php endif; ?>

                                <?php if ($showWishlist || $showCompare || $showCart): ?>
                                    <div class="product-item-actions">
                                        <?php if ($showCart): ?>
                                            <div class="actions-primary">
                                                <?php if ($_item->isSaleable()): ?>
                                                    <?php if ($_item->getTypeInstance()->hasRequiredOptions($_item)): ?>
                                                        <button class="action tocart primary"
                                                                data-mage-init='{"redirectUrl":{"url":"<?php /* @escapeNotVerified */ echo $block->getAddToCartUrl($_item) ?>"}}'
                                                                type="button" title="<?php /* @escapeNotVerified */ echo __('Add to Cart') ?>">
                                                            <span><?php /* @escapeNotVerified */ echo __('Add to Cart') ?></span>
                                                        </button>
                                                    <?php else: ?>
                                                        <?php
                                                            $postDataHelper = $this->helper('Magento\Framework\Data\Helper\PostHelper');
                                                            $postData = $postDataHelper->getPostData($block->getAddToCartUrl($_item), ['product' => $_item->getEntityId()])
                                                        ?>
                                                        <button class="action tocart primary"
                                                                data-post='<?php /* @escapeNotVerified */ echo $postData; ?>'
                                                                type="button" title="<?php /* @escapeNotVerified */ echo __('Add to Cart') ?>">
                                                            <span><?php /* @escapeNotVerified */ echo __('Add to Cart') ?></span>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?php if ($_item->getIsSalable()): ?>
                                                        <div class="stock available"><span><?php /* @escapeNotVerified */ echo __('In stock') ?></span></div>
                                                    <?php else: ?>
                                                        <div class="stock unavailable"><span><?php /* @escapeNotVerified */ echo __('Out of stock') ?></span></div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($showWishlist || $showCompare): ?>
                                            <div class="actions-secondary" data-role="add-to-links">
                                                <?php if ($this->helper('Magento\Wishlist\Helper\Data')->isAllow() && $showWishlist): ?>
                                                    <a href="#"
                                                       data-post='<?php /* @escapeNotVerified */ echo $block->getAddToWishlistParams($_item); ?>'
                                                       class="action towishlist" data-action="add-to-wishlist"
                                                       title="<?php /* @escapeNotVerified */ echo __('Add to Wish List') ?>">
                                                        <span><?php /* @escapeNotVerified */ echo __('Add to Wish List') ?></span>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($block->getAddToCompareUrl() && $showCompare): ?>
                                                    <?php $compareHelper = $this->helper('Magento\Catalog\Helper\Product\Compare'); ?>
                                                    <a href="#" class="action tocompare"
                                                       title="<?php /* @escapeNotVerified */ echo __('Add to Compare') ?>"
                                                       data-post='<?php /* @escapeNotVerified */ echo $compareHelper->getPostDataParams($_item);?>'>
                                                        <span><?php /* @escapeNotVerified */ echo __('Add to Compare') ?></span>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($description):?>
                                    <div class="product-item-description">
                                        <?php /* @escapeNotVerified */ echo $_helper->productAttribute($_item, $_item->getShortDescription(), 'short_description') ?>
                                        <a title="<?php echo $block->escapeHtml($_item->getName()) ?>"
                                           href="<?php /* @escapeNotVerified */ echo $block->getProductUrl($_item) ?>"
                                           class="action more"><?php /* @escapeNotVerified */ echo __('Learn More') ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php echo($iterator == count($items)+1) ? '</li>' : '' ?>
                    <?php endforeach ?>
                </ol>
            </div>
            <?php echo $block->getPagerHtml() ?>
        </div>
    </div>
<?php endif;?>

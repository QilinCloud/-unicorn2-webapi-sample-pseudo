<?php
declare(strict_types=1);

/**
 * Pseudo-marketplace implementation for a standalone ApiWeb sample connector.
 *
 * Every function below is a real ApiWeb method entry point. The comments marked
 * "replace this block" show where a third-party developer should call their own
 * marketplace API. The sample returns valid ApiWeb DTOs so it can be executed
 * locally before any real marketplace transport is added.
 */

/**
 * Returns a configured failure mode for one method.
 *
 * @param string $method ApiWeb method name.
 * @return string Failure mode or an empty string.
 */
function apiWebPseudoFailureMode(string $method): string
{
    $failure = (string)ApiWebConfig::nested('debug', 'failureMode', '');
    if ($failure === '' || strpos($failure, ':') === false) {
        return $failure;
    }

    list($configuredMethod, $mode) = explode(':', $failure, 2);
    return $configuredMethod === $method ? $mode : '';
}

/**
 * Adds a deterministic item error for configured negative test modes.
 *
 * @param Result $result Result that may receive the error.
 * @param string $method ApiWeb method name.
 * @return bool True when an error was added and normal processing should stop for this item.
 */
function apiWebPseudoApplyFailure(Result $result, string $method): bool
{
    $failure = apiWebPseudoFailureMode($method);
    if ($failure === '') {
        return false;
    }

    switch ($failure) {
        case 'api_down':
            $result->addError(503, 'Pseudo marketplace API is not reachable. Replace this simulation with your marketplace outage handling.');
            return true;
        case 'quota':
            $result->addError(429, 'Pseudo marketplace quota is exceeded. Back off, retry later, and tell the Unicorn user which limit was hit.');
            return true;
        case 'unknown':
            $result->addError(999, 'Pseudo marketplace returned an unknown error. Log method, payload and marketplace correlation id.');
            return true;
        default:
            return false;
    }
}

/**
 * Marks a write operation as successful and assigns a marketplace identifier.
 *
 * @param Result $result Result to populate.
 * @param string $prefix Identifier prefix for generated sample IDs.
 * @param string $method ApiWeb method name.
 * @return void
 */
function apiWebPseudoSuccess(Result $result, string $prefix, string $method): void
{
    if (apiWebPseudoApplyFailure($result, $method)) {
        return;
    }

    apiWebSuccessWithShopId($result, $prefix);
}

/**
 * Validates credentials for the pseudo marketplace.
 *
 * @param Result $result Result that receives the validation object.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function validateCredentials(Result $result, ?Request $request = null): void
{
    if (apiWebPseudoFailureMode('validateCredentials') === 'invalid_credentials') {
        $result->Item = (object)array(
            'Valid' => false,
            'Message' => 'Pseudo marketplace credentials are invalid. Check API key, seller account and required scopes.'
        );
        return;
    }

    $result->Item = (object)array(
        'Valid' => true,
        'Message' => 'Pseudo marketplace credentials are accepted. Replace this with a real credentials check.'
    );
}

/**
 * Returns all capabilities that this pseudo sample demonstrates.
 *
 * @param Result $result Result that receives the capability DTO.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function getCapabilities(Result $result, ?Request $request = null): void
{
    $result->Item = apiWebCapabilities();
}

/**
 * Returns shipping profiles that Unicorn can show in its UI.
 *
 * @param Result $result Result that receives shipping profile entries.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function getShippingProfiles(Result $result, ?Request $request = null): void
{
    foreach (apiWebShippingProfiles() as $profile) {
        $result->addCollectionEntry($profile);
    }
}

/**
 * Creates an article on the pseudo marketplace.
 *
 * @param Result $result Result whose Item contains the Unicorn article.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function addArticle(Result $result, ?Request $request = null): void
{
    /* Replace this block with: POST /marketplace/products */
    apiWebPseudoSuccess($result, 'pseudo-article', 'addArticle');
}

/**
 * Updates an article on the pseudo marketplace.
 *
 * @param Result $result Result whose Item contains the Unicorn article.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function setArticle(Result $result, ?Request $request = null): void
{
    /* Replace this block with: PUT /marketplace/products/{ShopId or SKU} */
    apiWebPseudoSuccess($result, 'pseudo-article', 'setArticle');
}

/**
 * Deletes or deactivates an article on the pseudo marketplace.
 *
 * @param Result $result Result whose Item contains the Unicorn article.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function delArticle(Result $result, ?Request $request = null): void
{
    /* Prefer deactivate over destructive delete when the marketplace supports both. */
    apiWebPseudoSuccess($result, 'pseudo-article-deleted', 'delArticle');
}

/**
 * Updates stock for one article.
 *
 * @param Result $result Result whose Item contains stock data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function setStock(Result $result, ?Request $request = null): void
{
    /* Replace this block with a stock endpoint call. */
    apiWebPseudoSuccess($result, 'pseudo-stock', 'setStock');
}

/**
 * Updates price for one article.
 *
 * @param Result $result Result whose Item contains price data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function setPrice(Result $result, ?Request $request = null): void
{
    /* Replace this block with a price endpoint call. */
    apiWebPseudoSuccess($result, 'pseudo-price', 'setPrice');
}

/**
 * Updates processing time or delivery promise for one article.
 *
 * @param Result $result Result whose Item contains processing-time data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function setProcessingTime(Result $result, ?Request $request = null): void
{
    /* Replace this block with a delivery-information endpoint call. */
    apiWebPseudoSuccess($result, 'pseudo-processing-time', 'setProcessingTime');
}

/**
 * Creates a cross-selling relation.
 *
 * @param Result $result Result whose Item contains the relation.
 * @param Request|null $request Parsed ApiWeb request with optional article reference.
 * @return void
 */
function addArticleCrossselling(Result $result, ?Request $request = null): void
{
    /* Use $request->reference for the parent article if your marketplace needs it. */
    apiWebPseudoSuccess($result, 'pseudo-crossselling', 'addArticleCrossselling');
}

/**
 * Deletes a cross-selling relation.
 *
 * @param Result $result Result whose Item contains the relation.
 * @param Request|null $request Parsed ApiWeb request with optional article reference.
 * @return void
 */
function delArticleCrossselling(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-crossselling-deleted', 'delArticleCrossselling');
}

/**
 * Creates an article attribute.
 *
 * @param Result $result Result whose Item contains the attribute.
 * @param Request|null $request Parsed ApiWeb request with optional article reference.
 * @return void
 */
function addArticleAttribut(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-attribute', 'addArticleAttribut');
}

/**
 * Updates an article attribute.
 *
 * @param Result $result Result whose Item contains the attribute.
 * @param Request|null $request Parsed ApiWeb request with optional article reference.
 * @return void
 */
function setArticleAttribut(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-attribute', 'setArticleAttribut');
}

/**
 * Deletes an article attribute.
 *
 * @param Result $result Result whose Item contains the attribute.
 * @param Request|null $request Parsed ApiWeb request with optional article reference.
 * @return void
 */
function delArticleAttribut(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-attribute-deleted', 'delArticleAttribut');
}

/**
 * Uploads or links an article image.
 *
 * @param Result $result Result whose Item contains image data.
 * @param Request|null $request Parsed ApiWeb request with optional article reference.
 * @return void
 */
function addArticleImage(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-image', 'addArticleImage');
}

/**
 * Updates an article image.
 *
 * @param Result $result Result whose Item contains image data.
 * @param Request|null $request Parsed ApiWeb request with optional article reference.
 * @return void
 */
function setArticleImage(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-image', 'setArticleImage');
}

/**
 * Deletes an article image.
 *
 * @param Result $result Result whose Item contains image data.
 * @param Request|null $request Parsed ApiWeb request with optional article reference.
 * @return void
 */
function delArticleImage(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-image-deleted', 'delArticleImage');
}

/**
 * Creates a category.
 *
 * @param Result $result Result whose Item contains the category.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function addCategory(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-category', 'addCategory');
}

/**
 * Updates a category.
 *
 * @param Result $result Result whose Item contains the category.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function setCategory(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-category', 'setCategory');
}

/**
 * Deletes a category.
 *
 * @param Result $result Result whose Item contains the category.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function delCategory(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-category-deleted', 'delCategory');
}

/**
 * Creates a category link for one article.
 *
 * @param Result $result Result whose Item contains the category link.
 * @param Request|null $request Parsed ApiWeb request with optional article reference.
 * @return void
 */
function addCategoryLink(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-category-link', 'addCategoryLink');
}

/**
 * Deletes a category link for one article.
 *
 * @param Result $result Result whose Item contains the category link.
 * @param Request|null $request Parsed ApiWeb request with optional article reference.
 * @return void
 */
function delCategoryLink(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-category-link-deleted', 'delCategoryLink');
}

/**
 * Returns a pseudo portal category tree.
 *
 * @param Result $result Result that receives category tree roots.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function getPortalCategories(Result $result, ?Request $request = null): void
{
    if (apiWebPseudoApplyFailure($result, 'getPortalCategories')) {
        return;
    }

    /* Replace this block with: GET /marketplace/categories/tree */
    $r1 = new Category();
    $r1->Id = 'R1';
    $r1->Name = 'Root 1';

    $r11 = new Category();
    $r11->Id = 'R11';
    $r11->Name = 'Root 1 / Branch 1';

    $r111 = new Category();
    $r111->Id = 'R111';
    $r111->Name = 'Root 1 / Branch 1 / Leaf 1';

    $r112 = new Category();
    $r112->Id = 'R112';
    $r112->Name = 'Root 1 / Branch 1 / Leaf 2';

    $r11->addSubcategory($r111);
    $r11->addSubcategory($r112);
    $r1->addSubcategory($r11);

    $r2 = new Category();
    $r2->Id = 'R2';
    $r2->Name = 'Root 2';

    $r21 = new Category();
    $r21->Id = 'R21';
    $r21->Name = 'Root 2 / Branch 1';

    $r211 = new Category();
    $r211->Id = 'R211';
    $r211->Name = 'Root 2 / Branch 1 / Leaf 1';

    $r22 = new Category();
    $r22->Id = 'R22';
    $r22->Name = 'Root 2 / Branch 2';

    $r221 = new Category();
    $r221->Id = 'R221';
    $r221->Name = 'Root 2 / Branch 2 / Leaf 1';

    $r21->addSubcategory($r211);
    $r22->addSubcategory($r221);
    $r2->addSubcategory($r21);
    $r2->addSubcategory($r22);

    $result->addCollectionEntry($r1);
    $result->addCollectionEntry($r2);
}

/**
 * Downloads pseudo orders and maps them to Unicorn order DTOs.
 *
 * @param Result $result Result that receives order entries.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function getOrders(Result $result, ?Request $request = null): void
{
    if (apiWebPseudoApplyFailure($result, 'getOrders')) {
        return;
    }

    /* Replace this block with: GET /marketplace/orders?state=... */
    $result->addCollectionEntry(apiWebOrder('-pseudo-1'));
    $result->addCollectionEntry(apiWebOrder('-pseudo-2'));
}

/**
 * Marks one order as paid on the marketplace.
 *
 * @param Result $result Result whose Item contains order state.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function setOrderPaid(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-order-paid', 'setOrderPaid');
}

/**
 * Sends shipment data to the marketplace.
 *
 * @param Result $result Result whose Item contains shipment data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function setOrderSend(Result $result, ?Request $request = null): void
{
    /* Replace this block with: POST /marketplace/orders/{orderId}/shipments */
    apiWebPseudoSuccess($result, 'pseudo-shipment', 'setOrderSend');
}

/**
 * Cancels one order on the marketplace.
 *
 * @param Result $result Result whose Item contains cancellation data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function setOrderCancelled(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-order-cancelled', 'setOrderCancelled');
}

/**
 * Downloads pseudo return announcements.
 *
 * @param Result $result Result that receives return announcement entries.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function getReturned(Result $result, ?Request $request = null): void
{
    if (apiWebPseudoApplyFailure($result, 'getReturned')) {
        return;
    }

    $result->addCollectionEntry((object)array(
        'WawiId' => 0,
        'ShopId' => 'pseudo-return-' . gmdate('YmdHis'),
        'BestellungShopId' => 'pseudo-order-returned',
        'RetourenDatum' => gmdate('c'),
        'Artikel' => array()
    ));
}

/**
 * Uploads return handling data.
 *
 * @param Result $result Result whose Item contains return data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function setReturned(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-return-upload', 'setReturned');
}

/**
 * Returns fulfillment-by-marketplace stock entries.
 *
 * @param Result $result Result that receives warehouse stock entries.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function getFulfillmentByMarketplaceWarehouse(Result $result, ?Request $request = null): void
{
    $result->addCollectionEntry((object)array(
        'WawiId' => 0,
        'ShopId' => 'pseudo-fbm-stock-1',
        'ArtikelNummer' => 'PSEUDO-FBM-1',
        'Name' => 'Pseudo fulfillment stock sample',
        'Lagerbestand' => 12,
        'StockPolicy' => true
    ));
}

/**
 * Uploads an invoice file or data object.
 *
 * @param Result $result Result whose Item contains invoice data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function uploadInvoice(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-invoice-upload', 'uploadInvoice');
}

/**
 * Uploads a refund file.
 *
 * @param Result $result Result whose Item contains refund file data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function uploadRefund(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-refund-upload', 'uploadRefund');
}

/**
 * Uploads structured refund data.
 *
 * @param Result $result Result whose Item contains refund data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function uploadRefundData(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-refund-data', 'uploadRefundData');
}

/**
 * Downloads invoice documents.
 *
 * @param Result $result Result that receives invoice document entries.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function downloadInvoices(Result $result, ?Request $request = null): void
{
    $result->addCollectionEntry((object)array(
        'ShopId' => 'pseudo-invoice-1',
        'BestellungShopId' => apiWebReadProperty($result->Item, 'MarketplaceOrderId', 'pseudo-order'),
        'RechnungsNr' => 'R-PSEUDO-1',
        'RechnungsDateiFileExtension' => 'pdf',
        'RechnungsDateiBase64' => base64_encode('%PDF-1.4 Pseudo ApiWeb sample')
    ));
}

/**
 * Downloads refund documents.
 *
 * @param Result $result Result that receives refund document entries.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function downloadRefunds(Result $result, ?Request $request = null): void
{
    $result->addCollectionEntry((object)array(
        'ShopId' => 'pseudo-refund-1',
        'BestellungShopId' => apiWebReadProperty($result->Item, 'MarketplaceOrderId', 'pseudo-order'),
        'GutschriftsNr' => 'G-PSEUDO-1',
        'GutschriftsDateiFileExtension' => 'pdf',
        'GutschriftsDateiBase64' => base64_encode('%PDF-1.4 Pseudo ApiWeb sample refund')
    ));
}

/**
 * Purges marketplace data or acknowledges that purge is not supported.
 *
 * @param Result $result Result whose Item contains purge scope data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function purge(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-purge', 'purge');
}

/**
 * Purges article data.
 *
 * @param Result $result Result whose Item contains purge scope data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function purgeArticles(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-purge-articles', 'purgeArticles');
}

/**
 * Purges category data.
 *
 * @param Result $result Result whose Item contains purge scope data.
 * @param Request|null $request Parsed ApiWeb request.
 * @return void
 */
function purgeCategories(Result $result, ?Request $request = null): void
{
    apiWebPseudoSuccess($result, 'pseudo-purge-categories', 'purgeCategories');
}

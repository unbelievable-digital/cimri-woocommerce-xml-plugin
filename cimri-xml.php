<?php

require_once("wp-load.php");
$args = array('post_type' => 'product', 'posts_per_page' => -1);
$loop = new WP_Query( $args );
$xml = new SimpleXMLElement('<MerchantItems xmlns="http://www.cimri.com/schema/merchant/upload" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />');
//$xml->addAttribute('version', '1.0');
//$xml->addChild('datetime', date('Y-m-d H:i:s'));

while ( $loop->have_posts() ) : $loop->the_post();
global $product;

  $track = $xml->addChild('MerchantItem');
  $track->addChild('merchantItemId', $product->get_id());
  $track->addChild('merchantItemCategoryId', '1');
  $track->addChild('brand', get_post_meta($product->get_id(), 'brand', true));
  $track->addChild('model', get_post_meta($product->get_id(), 'model', true));
  $track->addChild('itemTitle', $product->get_name());
  $track->addChild('merchantItemField', get_post_meta($product->get_id(), 'offer', true));
  $track->addChild('itemUrl', get_permalink( $product->get_id()));
  $track->addChild('priceEft', $product->get_price());
  $track->addChild('pricePlusTax', $product->get_price());
  $track->addChild('itemUrlMobile', get_permalink( $product->get_id()));
  $track->addChild('itemImageUrl', get_the_post_thumbnail_url( $product->get_id(), 'full' ));
  $track->addChild('shippingFee', get_post_meta($product->get_id(), 'shipping-cost', true));
  $track->addChild('stockStatus', '1');
  $track->addChild('stockDetail', $product->get_stock_quantity());
  $track->addChild('shippingDay', get_post_meta($product->get_id(), 'shipping-day', true));
  $track->addChild('shippingDetail', get_post_meta($product->get_id(), 'cargo-detail', true));
  $track->addChild('typeOfWarranty', '1');
  $track->addChild('warrantyPeriod', get_post_meta($product->get_id(), 'warranty-period', true));

  $eans = $track->addChild('eans');
  $eans->addChild('ean', get_post_meta($product->get_id(), 'serial-no', true));


  //specs
  $specs = $track->addChild('specs');
  $spec = $specs->addChild('spec');
  $spec->addChild('description', 'Ürün Boyu');
  $spec->addChild('values', get_post_meta($product->get_id(), 'product-size', true));

  $spec = $specs->addChild('spec');
  $spec->addChild('description', 'Ürün Genişliği');
  $spec->addChild('values', get_post_meta($product->get_id(), 'product-width', true));
  //specs end


  //installments
  $installments = $track->addChild('installments');
  $installment = $installments->addChild('installment');
  $installment->addChild('card', get_post_meta($product->get_id(), 'card-round', true));
  $installment->addChild('month', get_post_meta($product->get_id(), 'installment-wp', true));
  $installment->addChild('installmentPrice', '');
  $installment->addChild('continuance', '');

  //$installment = $installments->addChild('installment');
  //$installment->addChild('card', 'world');
  //$installment->addChild('month', '6');
  //$installment->addChild('installmentPrice', '60.00');
  //$installment->addChild('continuance', '3');
  //installments end

  //shippings
  $shippings = $track->addChild('shippings');
  $shipping = $shippings->addChild('shipping');
  $shipping->addChild('city', '34');
  $shipping->addChild('shippingPrice', get_post_meta($product->get_id(), 'shipping-cost', true));
  //shippings end


endwhile;
wp_reset_query();

Header('Content-type: text/xml');
print($xml->asXML());
?>

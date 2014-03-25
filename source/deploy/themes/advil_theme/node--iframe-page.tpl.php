<div id="iframe_holder">
<?php 
// Get the parameter LINK
$get_name = 'pageid';

$parameter = check_plain(strip_tags(isset($_GET[$get_name]) ? $_GET[$get_name] : ''));
if (!empty($parameter)) {
  $parameter = '?' . $get_name . '=' . $parameter;
}
?>
<?php print $node->field_iframe_page_header[$node->language][0]['value'] ?>
<iframe src="<?php print $node->field_iframe_page_url[$node->language][0]['value'] . $parameter;?>"
        width="584"
        height="<?php print $node->field_iframe_page_height[$node->language][0]['value'] ?>"
        frameborder="0" scrolling="no" allowtransparency="true">
</iframe>
</div>
<?php
/**
 * Page shell for all HTML pages
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body_attrs']  Attributes of the <body> tag
 * @uses $vars['body']        The main content of the page
 */
// Set the content type
header("Content-type: text/html; charset=UTF-8");

$lang = get_current_language();

$attrs = "";
if (isset($vars['body_attrs'])) {
	$attrs = elgg_format_attributes($vars['body_attrs']);
	if ($attrs) {
		$attrs = " $attrs";
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" lang="<?php echo $lang; ?>">
	<head>
		<?php echo $vars["head"]; ?>
		<style type="text/css">
		<?php if (elgg_is_logged_in() && elgg_get_context()!='admin') : ?>
		@media (max-width: 820px) {
				.logo, .elgg-sidebar{
					display: none;
				}

				.sliderbar-user-menu {
					display: block;
				}	
				
		}
		@media (max-width: 600px) {
		
			.elgg-avatar-topbar, .logo, .elgg-sidebar{
				display: none;
			}

			.sliderbar-user-menu {
					display: block;
			}	
		}
		<?php endif ?>
		</style>
		
		<link href='//fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
	</head>
	<body<?php echo $attrs ?>>
		<?php echo $vars["body"]; ?>
	</body>
</html>

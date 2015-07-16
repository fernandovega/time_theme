<?php
/**
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 * Default layout: 990px wide, centered. Used in default page shell
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* <style> /**/

/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
<?php // the width is on the page rather than topbar to handle small viewports ?>
.elgg-page-default {
  min-width: 800px;
}
.elgg-page-default .elgg-page-header > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	min-height: 65px;
}
.elgg-page-default .elgg-page-navbar > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	height: auto;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	padding: 5px 0;
}

/***** TOPBAR ******/
.elgg-page-topbar {
	background: #000000;
	box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
	height: 32px;
  padding: 7px 5px;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 9000;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: fixed;
	top: 50px;
	right: 15px;
	max-width: 500px;
	z-index: 2000;
}
.elgg-system-messages li {
	margin-top: 10px;
}
.elgg-system-messages li p {
	margin: 0;
}

/***** PAGE HEADER ******/
.elgg-page-header {
	padding: 5px 20px 10px;
	position: relative;
	background: #50C28C;
}
.elgg-page-header > .elgg-inner {
	position: relative;
}
/***** PAGE NAVBAR ******/
.elgg-page-navbar {
	padding: 0 20px;
	position: relative;
	background: #50C28C;
}
.elgg-page-navbar > .elgg-inner {
	position: relative;
}

/***** PAGE BODY LAYOUT ******/
.elgg-page-body {
	padding: 0 20px;
}

.elgg-layout {
	min-height: 360px;
}
.elgg-layout-widgets > .elgg-widgets {
	float: right;
}
.elgg-sidebar {
	background: #fff;
  border: 1px solid #dcdcdc;
  border-left: 1px solid #ebebeb;
  float: right;
  margin: 32px 0;
  padding: 15px;
  position: relative;
  width: 21.2121%;
}
.elgg-sidebar-alt {
	position: relative;
	padding: 32px 30px 20px 0;
	float: left;
	width: 16.161616%;
	margin: 0 30px 0 0;
	border-right: 1px solid #EBEBEB;
}
.elgg-main {
	position: relative;
	min-height: 360px;
	padding: 12px 15px;
}
.elgg-main > .elgg-head {
	padding-bottom: 10px;
	margin-bottom: 0px;
}

.elgg-river-layout > .elgg-main > .elgg-head {
  padding-bottom: 10px;
  margin-bottom: 0px;
  border: 1px solid #dcdcdc;
  border-bottom: 0px;
}

.elgg-layout-one-sidebar .elgg-main {
	background:  #FFFFFF;
  float: left;
  margin: 35px 10px;
  width: 69%;
  border: 1px solid #dcdcdc;
}


.elgg-river-layout .elgg-main {
  background:  transparent;
  float: left;
  margin: 20px 10px;
  width: 69%;
  border: 0px;
}

.elgg-layout-two-sidebar .elgg-main {
	float: left;
	width: 50.101010%;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
	color: #999;
	padding: 0 10px;
	position: relative;
}

.elgg-page-footer a:hover {
	color: #666;
}

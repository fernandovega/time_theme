<?php
/**
 * Elgg Profile CSS
 *
 * @package Profile
 */
?>
/* <style> /**/
/* ***************************************
	Profile
*************************************** */
.profile {
	float: left;
	margin-bottom: 15px;
}

.profile h2 {
    font-size: 2.3em;
    line-height: 1.1em;
}

.profile-menu{
	margin: 5px 10px 15px;
  position: relative;
	background: none repeat scroll 0 0 #fff;
  border-color: #dcdcdc #dcdcdc #dcdcdc #ebebeb;
  border-image: none;
  border-style: solid;
  border-width: 1px;
  padding: 15px 20px;
}

.profile-details {
	border: 1px solid #DCDCDC;
	background: #FFFFFF;
	margin-right: 10px;
  padding: 0 10px;
}

#profile-details {
	padding: 15px;
	width: 70%;
	float: right;
}

.profile-action-menu {
	float: left;
	margin: 15px 10px;
}

/*** ownerblock ***/
#profile-owner-block {
	width: 200px;
	float: left;
	padding: 15px;
}
#profile-owner-block .large {
	margin-bottom: 10px;
}
#profile-owner-block a.elgg-button-action {
	margin-bottom: 4px;
	display: table;
}
.profile-admin-menu {
	display: none;
}
.profile-admin-menu-wrapper a {
	display: block;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 16px;
}
.profile-admin-menu-wrapper:before {
	content: "\00BB";
	float: left;
	padding-top: 1px;
}
.profile-admin-menu-wrapper li a {
	color: #FF0000;
	margin-bottom: 0;
}
.profile-admin-menu-wrapper a:hover {
	color: #000;
}
/*** profile details ***/
#profile-details .wire-status {
	margin-top: 10px;
}
#profile-details .odd {
	margin: 0;
	padding: 5px 0;
}
#profile-details .even {
	margin: 0;
	padding: 5px 0;
}
.profile-aboutme-title {
	margin: 0;
	padding: 5px 4px 2px 0;
}
.profile-aboutme-contents {
	padding: 0;
}
.profile-banned-user {
	margin: 10px 0;
	padding: 20px;
	color: #B94A48;
	background-color: #F8E8E8;
	border: 1px solid #E5B7B5;
	border-radius: 5px;
}
.profile-banned-user h4 {
	color: #B94A48;
}


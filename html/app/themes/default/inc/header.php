<?php
/**
 * SympoCM Template Header
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

$tmp_title = CMS_SITE_TITLE; 
if (isset($title)) { $tmp_title = $title . ' | ' . CMS_SITE_TITLE; }

$tmp_description = ''; 
if (isset($description)) { $tmp_description = $description; }

$tmp_keywords = '';
if (isset($keywords)) { $tmp_keywords = $keywords; }
?>
<!DOCTYPE html> 
<html>
    <head>
        <title><?= htmlentities($tmp_title); ?></title>
        <meta charset="utf-8" />
        <meta name="title" content="<?= htmlentities($tmp_title); ?>" />
        <meta name="keywords" content="<?= htmlentities($tmp_keywords); ?>" />
        <meta name="description" content="<?= htmlentities($tmp_description); ?>" />
        <link rel="stylesheet" type="text/css" href="<?= $this->theme_url ?>/css/sympocm.css" /> 
        <script type="text/javascript" src="<?= $this->theme_url ?>/js/sympocm.js"></script> 
    </head> 
    <body> 
    
<div id="wrapper"> 
    <div id="container"> 
        <div id="header"> 
        <div id="title"><?= link_to_self("Sympo<strong>CM</strong>"); ?></div> <!-- #title --> 
            <div id="subtitle">A Powerful and Easy-To-Use Content Management System</div> <!-- #subtitle --> 
        </div> <!-- #header --> 
        <div id="body"> 
            <div id="content">


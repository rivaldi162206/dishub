<?php
/**
 * HTML filtering
 * Compatible with PHP 5.6
 */

function HTMLFilter($body, $trans_image_path = "", $block_external_images = false)
{
    $tag_list = array(
        'a','b','blockquote','br','caption','center','cite','code','col',
        'colgroup','dd','div','dl','dt','em','font','h1','h2','h3','h4',
        'h5','h6','hr','i','img','li','ol','p','pre','s','small','span',
        'strike','strong','sub','sup','table','tbody','td','tfoot','th',
        'thead','tr','tt','u','ul'
    );

    $body = preg_replace('/<\?.*?\?>/s', '', $body); // remove php tags
    $body = preg_replace('/<!.*?>/s', '', $body);    // remove comments

    return $body;
}


/**
 * Fix image URL
 */
function tln_fixurl(&$attvalue, $trans_image_path)
{
    if ($trans_image_path != "" && strpos($attvalue, 'cid:') === 0) {
        $sQuote = '"';
        $attvalue = $sQuote . $trans_image_path . $sQuote;
    }
}


/**
 * Attribute parsing
 */
function tln_getattributes($tag)
{
    $attary = array();
    preg_match_all('/(\w+)\s*=\s*("|\')(.*?)\2/s', $tag, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $attary[strtolower($match[1])] = $match[3];
    }
    return $attary;
}


/**
 * Rebuild tag safely
 */
function tln_rebuildtag($tagname, $attary, $trans_image_path)
{
    $tag = '<' . $tagname;
    foreach ($attary as $attname => $attvalue) {

        if ($tagname == 'img' && strtolower($attname) == 'src') {
            tln_fixurl($attvalue, $trans_image_path);
        }

        $tag .= ' ' . $attname . '="' . htmlspecialchars($attvalue, ENT_QUOTES, 'UTF-8') . '"';
    }
    $tag .= '>';
    return $tag;
}
?>

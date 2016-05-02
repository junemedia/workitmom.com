<?php
/**
 * LiveRamp Match Partner tags
 */
$lr_ei_tagid = '424286';
$lr_rc_tagid = '424326';

// default to using recookie
$lr_recookie = true;

// open the tag...
$lr_tag = '<iframe name="_rlcdn" width=0 height=0 frameborder=0 src="';

// if we can get the userModel and the user is logged-in, serve
// the LR tag, and set recookie to false
if ($userModel = BluApplication::getModel('user')) {
  $currentUser = $userModel->getUser();

  // if user is logged in, serve match partner tag
  if ($currentUser->email) {
    $lr_tag .= '//ei.rlcdn.com/' . $lr_ei_tagid . '.html';
    $lr_tag .= '?s=' . sha1(strtolower($currentUser->email));

    // don't need to set recookier
    $lr_recookie = false;
  }

  unset($userModel);
}

// otherwise serve recookier tag
if ($lr_recookie == true) {
  $lr_tag .= '//rc.rlcdn.com/' . $lr_rc_tagid . '.html';
}

// ...close the tag
$lr_tag .= '"></iframe>';

echo $lr_tag;

<?php
include("includes/youtubei/createRequest.php");

// form a youtubei request to /youtubei/v1/browse, then get the first 10 results
// we then fetch data with /youtube1/v1/player 
$response_object = requestBrowse("FEwhat_to_watch");

$response = json_decode($response_object);
function homepageFeed($number)
{
    $response_object = requestBrowse("FEwhat_to_watch");
    $response = json_decode($response_object);
    $feedobj = $response->contents->twoColumnBrowseResultsRenderer->tabs[0]->tabRenderer->content->richGridRenderer->contents[$number];
    //print_r($feedobj);
    return $feedobj;
}
// ok we done now to the html section below...

?>
<!DOCTYPE html>
<html>
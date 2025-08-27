<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function hotlink_protect_theme() {
}else{
d.hotlink_disable = 1;
}
submitit(d, {
done_reload: window.location
});
}
</script>';
softfooter();
}
function getRandomUrl() {
}
$urls = make_random($primary_domain);
foreach ($domains_list as $dom => $opt) {
$urls = make_random($dom, $urls);
}
return $urls;
}
function make_random() {
}
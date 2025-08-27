<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function login_logs_theme() {
}
}else{
echo '
<tr class="text-center">
<td colspan=4>
<span>'.__('There are no login logs as of now').'</span>
</td>
</tr>';
}
echo '			</tbody>
</table>
</div>
</div>
</div>';
softfooter();
}
<?php

/** 
 * Enable hiding tables in menu list
 *
 * Install to Adminer on http://www.adminer.org/plugins/
 * @author Pavel Kutáč, http://www.kutac.cz/
 * 
 * Filter inspiration by Jakub Vrana: https://raw.githubusercontent.com/vrana/adminer/master/plugins/tables-filter.php
 * 
 */
class AdminerTablesHide {
  	
	function tablesPrint($tables) {
		?>
		<style>
		  p.toggleTableVisible{cursor:pointer}#menu.hiddenVisible .hideT{display:inline}#menu.hiddenVisible .showT,#tables span.hiddenTable,.hideT{display:none}#menu.hiddenVisible #tables span.hiddenTable{display:inline;opacity:.5}#menu.hiddenVisible #tables span.hiddenTable.filtered,#tables span.filtered{display:none}#tables a.toggleVisible{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAKCAYAAABv7tTEAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAANJJREFUeNp00cEGAkEcx/HYpEN0jei0RKfouqceoOsSsW8QEd16lD31DBG9QDp1WmIpS8QSER2yfSe/ydj057N27fxnfjPjVf5XF7neW9iijp07qIEpDijkiRESZPDdBvNx1MAUYwwQqOGECXq2oa0YpuGGDuYYaiLTEOGBq11t7cSJNXukFVNNEjtjNlUeNSemhzNWeGGpJD/la9k7QsVJFG9Rip9r5U/1tXnbEOggQkUs9M8v30mmhlBHbfdwwQzNckRzWntdot3boLTfb70FGACGeTlEq+2nVAAAAABJRU5ErkJggg==);display:inline-block;height:10px;visibility:hidden;width:13px}#tables span.hiddenTable a.toggleVisible{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAKCAYAAABv7tTEAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAALZJREFUeNpiZsAEokCsCcS+UP4fIP7GgAPoAvEKIP4Pxd+Q2Cug8iggBEnROqhtICAB5cMMCYFpsIc6ASRxD4j5gfgwlA+ieYD4BpT/B6qe4TaSM2YCcSAS/z+UPxOJf5sJSCxDcqYcEF8D4l9Q/i8oXxpJDVg9GxBvQbI+BOrpNCjth+T8LVD1YMAMxF1IgQGSnIZk2DeoPEgdAyOWOEoAYhUgVgfim0B8B4gXAPFrBkoAQIABAO6KOXvUtxGwAAAAAElFTkSuQmCC)}#tables.min a.toggleVisible{visibility:visible}
		</style>
		<script type="text/javascript">
	   	function qsa(e,n){return n=n||document,n.querySelectorAll(e)}function qs(e,n){return n=n||document,n.querySelector(e)}function addClass(e,n){hasClass(e,n)||(e.className+=" "+n)}function removeClass(e,n){e.className=e.className.replace(new RegExp("(\\s)*"+n+"(\\s)*","g")," ")}function hasClass(e,n){return new RegExp("(\\s)*"+n+"(\\s)*","g").test(e.className)}function bclick(){hasClass(menuBlock,"hiddenVisible")?removeClass(menuBlock,"hiddenVisible"):addClass(menuBlock,"hiddenVisible")}function toggleVisible(e){var n=e.parentElement,s=currentDatabase,a=e.href.indexOf("#");if(1>a)return!0;if(s+="-"+e.href.substring(a+1),hasClass(n,"hiddenTable")){removeClass(n,"hiddenTable");var l=inTables(s);l>-1&&hiddenTables.splice(l,1)}else addClass(n,"hiddenTable"),hiddenTables.push(s);var i=new Date;i.setYear(i.getYear()+1);var r=hiddenTables.join("|");return document.cookie="adminer_tablesHide="+r+"; expires="+i.toGMTString(),!1}function initTables(){for(var e="adminer_tablesHide=",n=document.cookie.split(";"),s=0;s<n.length;s++){for(var a=n[s];" "==a.charAt(0);)a=a.substring(1,a.length);if(0==a.indexOf(e)){hiddenTables=a.substring(e.length,a.length).split("|");for(var l=qsa("#tables .toggleVisible"),i=0;i<l.length;i++){var r=l[i].href.indexOf("#");r>1&&inTables(currentDatabase+"-"+l[i].href.substring(r+1))>-1&&addClass(l[i].parentElement,"hiddenTable")}}}addClass(qs("#tables"),"hidingTablesPlugin")}function filterTables(e){for(var n=qsa("#tables span"),s=0;s<n.length;s++){var a=qsa("a",n[s])[2];if(a){var l=a.innerText||a.textContent;l.indexOf(e)<0?(a.innerHTML=l,addClass(n[s],"filtered")):(a.innerHTML=l.replace(e,"<b>"+e+"</b>"),removeClass(n[s],"filtered"))}}}function inTables(e){for(var n=0;n<hiddenTables.length;n++)if(hiddenTables[n]==e)return n;return-1}var menuBlock=qs("#menu"),hiddenTables=[],currentDatabase="<?php echo $_GET[DRIVER]."-".$_GET["db"]; ?>";
    </script>
    <p class="jsonly">Filter: <input onkeyup="filterTables(this.value);"></p>
    <?php
    $adminer = adminer();
    
		echo "<p id='tables' onmouseover='menuOver(this, event);addClass(this,\"min\")' onmouseout='menuOut(this);removeClass(this,\"min\")'>\n";
    foreach ($tables as $table => $status) {
			echo '<span><a href="#'.urlencode($table).'" class="toggleVisible" onclick="return toggleVisible(this,event)"></a> <a href="' . h(ME) . 'select=' . urlencode($table) . '"' . bold($_GET["select"] == $table || $_GET["edit"] == $table, "select") . ">" . lang('select') . "</a> ";
			$name = $adminer->tableName($status);
			echo (support("table") || support("indexes")
				? '<a href="' . h(ME) . 'table=' . urlencode($table) . '"'
					. bold(in_array($table, array($_GET["table"], $_GET["create"], $_GET["indexes"], $_GET["foreign"], $_GET["trigger"], $_GET["select"], $_GET["edit"])), (is_view($status) ? "view" : ""), "structure")
					. " title='" . lang('Show structure') . "'>$name</a>"
				: $name
			) . "<br></span>\n";
		}?>
		</p>
		<p class="toggleTableVisible" onclick="bclick(event)"><span class="showT">Zobrazit</span><span class="hideT">Skrýt</span> skryté tabulky</p>
		<script>initTables();</script>
		<?php 
		return true;
	}	
}

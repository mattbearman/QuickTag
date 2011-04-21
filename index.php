<?php
// load quicktag
require('lib/quicktag.php');

// test HTML
$html = 
'<html>
	<body>
		<div.nav>
			<ul#main-menu.with-class>
				<li.this-has.two_classes#and-an-id>blah</li>
				<li.last#hi_light.anotherClass>something</li>
			</ul>
		</div>
	</body>
</html>';

echo $html;

$qt = new QuickTag($html);

echo $qt->getHTML();

echo $qt->getExecTime();
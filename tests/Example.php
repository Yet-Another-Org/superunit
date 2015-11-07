<?php
require_once __DIR__ . '/vendor/autoload.php';

use SuperUnit\SuperUnit;

$obj = new SuperUnit;
$obj->fromString(<<<EOF
title : V观习主席出访：习近平抵达新加坡总统府
content :
　　原标题：V观习主席出访：习近平抵达新加坡总统府

　　国家主席习近平6日下午抵达新加坡，开始对新加坡进行国事访问。（央视记者邓雪梅） http://t.cn/RUoxJyI

　　中新网台北11月7日电 (记者 刘舒凌)台湾领导人马英九7日清晨6时许率团自台北松山机场启程，赴新加坡与大陆领导人习近平会面。

　　这是1949年以来两岸双方领导人首次会面，备受世人瞩目。

　　登机前，马英九发表简短讲话。他说，我们马上就要出发，到新加坡与大陆领导人习近平先生会面。我们见面的目的就是回顾过去、前瞻未来，透过会面来巩固台海和平、维持两岸现状。

　　马英九再次指出，在过去7年半时间中，台湾方面推动两岸交流，在各领域都创造了巨大的和平红利；这也是66年来海峡两岸关系最稳定的时刻，是一 个适当的时机进一步推动两岸关系发展，同时把两岸领导人会面机制建立起来，让未来继任者都有机会在这个新平台上继续推动两岸关系。

author : 中国新闻网
date : 2015-11-07 08:03
EOF
)->determine();

print "=== add form ===\n";
foreach($obj->form() as $el) {
	print $el ."\n";
}
print "=== edit form ===\n";
foreach($obj->form(true) as $el) {
	print $el ."\n";
}
print "=== mysql ===\n";
foreach($obj->mysql() as $el) {
	print $el ."\n";
}
print "=== validate ===\n";
var_dump($obj->validate(<<<EOF
title : love
content : abcabcabcabcabcabcabcabcabcabcabcabcabcabcabcabcabcabcabcabcabcabcabcabcabc
author : only you
date : 2018-11-01 06:05
EOF
));



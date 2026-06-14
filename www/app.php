<?php
require('/srv/control/phlo/phlo.php');
phlo_app (
	id:    'PhloWhatsAppDemo',
	host:  'demo.whatsapp.qdev.nl',
	build: true,
	debug: true,
	app:   dirname(__DIR__).'/',
);

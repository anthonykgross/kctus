<?php
$this->container->setParameter('URL_PAD_MOBILE',                      'http://kctus.anthonykgross.fr/mobile/?key=');
$this->container->setParameter('URL_SERVER_IO',                       'http://kctus.anthonykgross.fr:1234');

//Creation d'un projet (upload & lien fichier)
$this->container->setParameter('PATH_UPLOAD',                       'upload/');
$this->container->setParameter('ABSOLUTE_PATH_UPLOAD',              $this->container->getParameter('kernel.root_dir').'/../web/'.$this->container->getParameter('PATH_UPLOAD'));
$this->container->setParameter('PATH_UPLOAD_QRCODE',                 $this->container->getParameter('PATH_UPLOAD').'qrcode/');
$this->container->setParameter('ABSOLUTE_PATH_UPLOAD_QRCODE',        $this->container->getParameter('kernel.root_dir').'/../web/'.$this->container->getParameter('PATH_UPLOAD_QRCODE'));
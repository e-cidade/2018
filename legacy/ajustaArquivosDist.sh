#!/bin/bash

pathToProject=$1

#alterar 
function _ajustaFontes() {
  
  #ajusta os arquivos .dist do diretorio libs do e-cidade
  sudo  cp -rf $pathToProject/libs/config.mail.php.dist           $pathToProject/libs/config.mail.php
  sudo  cp -rf $pathToProject/libs/db_config_horus.php.dist       $pathToProject/libs/db_config_horus.php  
  sudo  cp -rf $pathToProject/libs/db_conn.php.dist               $pathToProject/libs/db_conn.php
  sudo  cp -rf $pathToProject/libs/db_cubo_bi_config.php.dist     $pathToProject/libs/db_cubo_bi_config.php 
  
  #ajusta os arquivos .dist do diretorio config do e-cidade
  sudo cp -rf $pathToProject/config/pcasp.txt.dist               $pathToProject/config/pcasp.txt
  sudo cp -rf $pathToProject/config/plugins.json.dist            $pathToProject/config/plugins.json
  sudo cp -rf $pathToProject/config/require_extensions.xml.dist  $pathToProject/config/require_extensions.xml

  echo "Script finalizado."

}

function _main(){
 _ajustaFontes
}

_main

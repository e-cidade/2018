<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

class db_app {


  /**
   * Carremento de assets do sistema
   */
  public static function load($files) {

    $aFiles                       = array();
    $aFiles["estilos.css"]        = "<link href='estilos.css' rel='stylesheet' type='text/css'>";
    $aFiles["grid.style.css"]     = "<link href='estilos/grid.style.css' rel='stylesheet' type='text/css'>";
    $aFiles["scripts.js"]         = "<script language='JavaScript' type='text/javascript' src='scripts/scripts.js?version=".DB_VERSION."'></script>";
    $aFiles["strings.js"]         = "<script language='JavaScript' type='text/javascript' src='scripts/strings.js?version=".DB_VERSION."'></script>";
    $aFiles["datagrid.widget.js"] = "<script language='JavaScript' type='text/javascript' src='scripts/datagrid.widget.js?version=".DB_VERSION."'></script>";
    $aFiles["prototype.js"]       = "<script language='JavaScript' type='text/javascript' src='scripts/prototype.js?version=".DB_VERSION."'></script>";
    $aFileToLoad = is_array($files) ? $files : explode(",", $files);

    foreach ($aFileToLoad as $index => $filename) {

      if (isset($aFiles[trim($filename)])) {
        echo $aFiles[trim($filename)]."\n";
      } else {

        $extension  = explode(".", trim($filename));
        $extension  = array_reverse($extension);
        $directory  = "";
        $sStringPrefix = "";
        $sStringSufix  = "";
        if ($extension[0] == "js") {

          $sStringPrefix = "<script language='JavaScript' type='text/javascript' src='#filename'>";
          $sStringSufix  = "</script>";
          $directory     = "scripts";

        } else if ($extension[0] == "css") {

          $sStringPrefix = "<link href='#filename' rel='stylesheet' type='text/css'>";
          $sStringSufix  = "";
          $directory     = "estilos";

        }
        if (file_exists("{$directory}/".trim($filename))) {
          echo str_replace("#filename", "{$directory}/".trim($filename), $sStringPrefix)."{$sStringSufix}\n";
        }else if (file_exists("{$directory}/widgets/".trim($filename))) {
          echo str_replace("#filename", "{$directory}/widgets/".trim($filename), $sStringPrefix)."{$sStringSufix}\n";
        }else if (file_exists("{$directory}/classes/".trim($filename))) {
          echo str_replace("#filename", "{$directory}/classes/".trim($filename), $sStringPrefix)."{$sStringSufix}\n";
        } else if (file_exists("ext/javascript/".trim($filename))) {
          echo str_replace("#filename", "ext/javascript/".trim($filename), $sStringPrefix)."{$sStringSufix}\n";
        } else {
          echo "<!-- Arquivo não encontrado {$filename}. -->";
          throw new Exception("Include {$filename} não existe");
        }
      }
    }
  }


  /**
   * importa um pacote, ou uma classe,
   *
   * @param classe $sClasse nome do pacote/classe
   * @return bool
   */
  static public function import($sClasse) {

    return false;
    $aBasePath = array("model/", "libs/", "std/");
    if (preg_match('/(\.\*)$/', $sClasse)) {

      $sImportFilePath = substr($sClasse, 0, strlen($sClasse) - 2);
      $sImportFilePath = str_replace(".", "/", $sImportFilePath);
      foreach ($aBasePath as $sBasePath) {

        if (is_dir($sBasePath.$sImportFilePath)) {

          $oDirectory      = dir($sBasePath.$sImportFilePath);
          while(false !== ($sFile = $oDirectory->read())) {

            /*
             *Reject parent, current directories and sub directories
             */
            if (($sFile == '.') || ($sFile == '..') ||
              (is_dir($oDirectory->path . "/" . $sFile))) {
                continue;
              }
            /**
             * não carrega arquivos que não sejam php
             */
            if (substr($sFile, -3) !== "php") {
              continue;
            }
            if (is_file($sBasePath . $sImportFilePath . "/" . $sFile)) {
              require_once(modification($sBasePath . $sImportFilePath . "/" . $sFile));
            }
          }
        }
      }
    } else {

      /* If a single file is specified */
      foreach ($aBasePath as $sBasePath) {

        $sSufix = ".php";
        if ($sBasePath == "model/") {
          $sSufix = '.model.php';
        }
        $sImportFile = str_replace(".", "/", $sClasse);
        if (is_file($sBasePath . $sImportFile.$sSufix)) {
          require_once(modification($sBasePath . $sImportFile.$sSufix));
        }
        if (is_file($sBasePath . $sImportFile.".interface.php")) {
          require_once(modification($sBasePath . $sImportFile.".interface.php"));
        }
      }
    }
  }
}

<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/JSON.php');
require_once('libs/db_utils.php');

$oJson   = new services_json();

$sStr    = $_POST['string'];
$sStr    = crossUrlDecode($sStr);
$sStr    = html_entity_decode($sStr);

$iCodigo = $_GET['iCodigo'];

switch ($iCodigo) {

  case 1:
   
    $oDaoMatMater = db_utils::getdao('matmater');
    $sCampos      = 'm60_codmater as cod, m60_descr as label';
    $sWhere       = " m60_descr like upper('$sStr%') and m60_ativo ";
    $sSql         = $oDaoMatMater->sql_query_file(null, $sCampos, 'm60_descr', $sWhere);
    $rs           = $oDaoMatMater->sql_record($sSql);
    break;

  default:

    break;

}

$aDados = '';
if ($rs != false) {

  //echo $sSql;
  $aDados    = db_utils::getColectionByRecord($rs, false, false, true);

}
echo $oJson->encode($aDados);

function crossUrlDecode($sSource) {

  $sDecodedStr = '';
  $iPos        = 0;
  $iLen        = strlen($sSource);

  while ($iPos < $iLen) {

    $sCharAt = substr($sSource, $iPos, 1);
    if ($sCharAt == 'Ã') {

      $sChar2       = substr($sSource, $iPos, 2);
      $sDecodedStr .= htmlentities(utf8_decode($sChar2), ENT_QUOTES, 'ISO-8859-1');
      $iPos        += 2;

    } elseif(ord($sCharAt) > 127) {

      $sDecodedStr .= "&#".ord($sCharAt).";";
      $iPos++;

    } elseif($sCharAt == '%') {

      $iPos++;
      $sHex2   = substr($sSource, $iPos, 2);
      $sDecHex = chr(hexdec($sHex2));

      if ($sDecHex == 'Ã') {

          $iPos += 2;
          if (substr($sSource, $iPos, 1) == '%') {

            $iPos++;
            $sChar2a      = chr(hexdec(substr($sSource, $iPos, 2)));
            $sDecodedStr .= htmlentities(utf8_decode($sDecHex . $sChar2a), ENT_QUOTES, 'ISO-8859-1');

          } else {
            $sDecodedStr .= htmlentities(utf8_decode($sDecHex));
          }

      } else {
        $sDecodedStr .= $sDecHex;
      }
      $iPos += 2;

    } else {

      $sDecodedStr .= $sCharAt;
      $iPos++;

    }

  }

  return $sDecodedStr;

}
?>
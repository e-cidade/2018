<?
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

error_reporting(null);
session_start();
require_once(modification("libs/db_conn.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_stdlib.php"));

$oGet = db_utils::postMemory($_GET);

if (isset($oGet->verificarequisitossistema) && $oGet->verificarequisitossistema == 'true') {

	$DB_VALIDA_REQUISITOS = true;
  $lVerificaRequisitos  = true;
  $sMsgCabecalho        = "Voc&ecirc; precisa instalar as extens&otilde;es do servidor que est&atilde;o pendentes.";
} else {

  $lVerificaRequisitos = false;
  if (session_is_registered("DB_configuracao_ok")) {
    session_destroy();
  }
}

//var_dump($lVerificaRequisitos);

// Diretório das extensoes do servidor necessárias
$diretorio          = "config/require_extensions.xml";
$sDirDocumentRoot   = $_SERVER['DOCUMENT_ROOT'] . "/";

$lErro                = false;
$lBrowser             = false;
$lErroMod             = false;
$lErroParam           = false;
$lErroDirTmp          = false;
$lErroSetings         = false;
$lDirTmpRaizExist     = true;
$lDirTmpDbPortalExist = true;

if (file_exists($diretorio)) {

// Abre o arquivo XML e transforma em um objeto
   $oXmlEst   = simplexml_load_file($diretorio);
   $lErroConf = false;
} else {

// Se não existir o arquivo config/require_extensions.xml retorna mensagem
   $sMsgConf   = "Erro: 404 Diretório de Configuração Inexistente! \n";
   $sMsgConf  .= "Contate Administrador do Sistema.";
   $lErroConf  = true;
}


if ( isset($DB_VALIDA_REQUISITOS) && $DB_VALIDA_REQUISITOS == true ) {

	// conexao com o banco de dados postgreSQL
	$conn1 = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE user=$DB_USUARIO port=$DB_PORTA password=$DB_SENHA ");

	$aBrowserVersao   = getBrowser();

	switch ($aBrowserVersao["browser"]) {
	    case "MSIE" :
	        $sBrowser = "Internet Explorer ".$aBrowserVersao["version"];
	        break;
	    case "OPERA" :
	        $sBrowser = "Opera Browser ".$aBrowserVersao["version"];
	        break;
	    case "FIREFOX" :
	        $sBrowser = "Mozilla Firefox ".$aBrowserVersao["version"];
	        break;
	    case "MOZILLA" :
	        $sBrowser = "Mozilla Firefox ".$aBrowserVersao["version"];
	        break;
	    case "CHROME" :
	        $sBrowser = "Google Chrome ".$aBrowserVersao["version"];
	        break;
	    case "NETSCAPE" :
	        $sBrowser = "Netscape Browser ".$aBrowserVersao["version"];
	        break;
	    case "SAFARI" :
	        $sBrowser = "Safari Browser ".$aBrowserVersao["version"];
	        break;
	    case "LYNX" :
	        $sBrowser = "Lynx Browser ".$aBrowserVersao["version"];
	        break;
	    case "KONQUEROR" :
	        $sBrowser = "Konqueror Browser ".$aBrowserVersao["version"];
	        break;
	    default :
	       $sBrowser = "Browser Desconhecido";
	}

	// retorna versao do browser sem a sua subversao
	$aVersao = explode(".", $aBrowserVersao["version"]);
	// verifica modulos pendentes
	$aModulos = get_loaded_extensions();
	$sVersao = $aVersao[0];
	// versao do browser com subversao
	$oBrowserVersaoSub    = strtolower($aBrowserVersao["browser"].$aBrowserVersao["version"]);
	// versao do browser sem subversao
	$oBrowserVersaoSemSub = strtolower($aBrowserVersao["browser"].$sVersao);

	foreach ($oXmlEst->browsers->browser as $oValCampo ) {

	  $sStrpos = strpos($oValCampo['versao'],'*');

	  if ($sStrpos === false) {

	     $aListaBrowser = $oValCampo['name'].$oValCampo['versao'];
	     $iTam          = strlen($oValCampo['versao']);
	     if ($aListaBrowser == $oBrowserVersaoSub) {
         $lBrowser = true;
	       break;
	     }
	  } else {


	    $sVersao          = $oValCampo['versao'];
	    $aVersaoVerificar = explode(".", $sVersao);
	    $iTotalVersoesUsuario   = count($aVersao);
	    $iTotalVersoesVerificar = count($aVersaoVerificar);
	    /**
	     * deixamos as versoes do Browser com o mesmo Tamanho.
	     */
 	    if ($iTotalVersoesUsuario != $iTotalVersoesVerificar) {
	       if ($iTotalVersoesUsuario > $iTotalVersoesVerificar) {
	         array_pad($aVersao, $iTotalVersoesVerificar, 0);
	       } else if ($iTotalVersoesUsuario < $iTotalVersoesVerificar) {
	         array_pad($aVersaoVerificar, $iTotalVersoesUsuario, 0);
	       }
	    }

	    /**
	     * Caso devemos ignorar algum pedaço da versao, deixamos a versao do browser do usuario como *
	     */
	    foreach ($aVersaoVerificar as $iParte => $sVersaoVerificar) {

	      if ($sVersaoVerificar == "*") {
	        $aVersao[$iParte] = "*";
	      }
	    }
	    $sVersao = implode("", $aVersaoVerificar);
	    $oBrowserVersaoSemSub =  strtolower($aBrowserVersao["browser"]).implode("", $aVersao);
	    $aListaBrowser = $oValCampo['name'].$sVersao;
	    $sValCampo     = str_replace("*", "", $oValCampo['versao']);
	    $sValCampo     = str_replace(".", "", $sValCampo);
	    $iTam          = strlen($sValCampo);
	    if ($iTam >= 2) {

	      if ($aListaBrowser == $oBrowserVersaoSemSub) {

	        $lBrowser = true;
	        break;
	      }
	    } else {

	      $sMsgConf   = "Erro: Parametro(s) de Configuração do Browser não Configurados Corretamente! \n";
	      $lErroConf  = true;
	      $lErro      = true;
	    }
	  }
	}

	$i = 0;
	foreach( $oXmlEst->modulos->modulo as $aValCampo ) {

	  $aListaModVlrPadrao[$i] = $aValCampo['valorpadrao'];
	  if (!in_array($aListaModVlrPadrao[$i], $aModulos)) {

      $aListaModulos[$i] = $aValCampo['label'];
      $lErroMod = true;
    }
	  $i++;
	}

	$aListaParam = array();
	foreach ($oXmlEst->parametros->parametro as $aParametro) {

	  if ( !db_compara_conf_php(ini_get($aParametro['name']), $aParametro['valorpadrao'], $aParametro['bool'],
	                                                          $aParametro['operacao']) ) {
			$aListaParam[] = $aParametro['valorpadrao'];
		}
	}

	if (count(@$aListaParam) > 0) {
		 $lErroParam = true;
	}

	$i = 0;
	foreach ($oXmlEst->database->parametro as $aParametro) {

	  $sqlSettings  = " SELECT current_setting('{$aParametro['name']}') ";
	  $rsSettings   = db_query($sqlSettings);
	  $iSettings    = pg_num_rows($rsSettings);
	  if ($iSettings > 0) {

	    $oSettings = db_utils::fieldsMemory($rsSettings,0);
	    if (isset($oSettings->current_setting) != "") {

	      if ($aParametro['bool'] == 'true') {

	        $sqlSettingsServer  = " SELECT current_setting('server_version_num') ";
	        $rsSettingsServer   = db_query($sqlSettingsServer);
	        $iSettingsServer    = pg_num_rows($rsSettingsServer);
	        if ($iSettingsServer > 0) {

	          $oSettingsServer = db_utils::fieldsMemory($rsSettingsServer,0);
	          if ($aParametro['valor_min'] > $oSettingsServer->current_setting ||
	              $aParametro['valor_max'] < $oSettingsServer->current_setting) {

	          	$lErroSetings            = true;
	   	        $aListaParamPostgre[$i] = $aParametro['name'];
	   	        $aItemPostgre[$i]       = $oSettings->current_setting;
	  	      } else {

	  	        $sStrpos = strpos($aParametro['valorpadrao'],'*');
	            if ($sStrpos === false) {

	           	  if ($oSettings->current_setting != $aParametro['valorpadrao']) {

	             	  $lErroSetings            = true;
	   	            $aListaParamPostgre[$i] = $aParametro['name'];
	   	            $aItemPostgre[$i]       = $oSettings->current_setting;
	              }
	            } else {

	             	$sVersaoConfigurada = substr($aParametro['valorpadrao'], 0,$sStrpos-1);
	              $sVersaoPostgre    = substr($oSettings->current_setting, 0,$sStrpos-1);
	              if ($sVersaoPostgre != $sVersaoConfigurada) {

	                $lErroSetings            = true;
	   	            $aListaParamPostgre[$i] = $aParametro['name'];
	   	            $aItemPostgre[$i]       = $sVersaoPostgre;
	              }
	            }
	  	      }
	        } else {

	         	$lErroSetings            = true;
	          $aListaParamPostgre[$i]  = $aParametro['name'];
	          $aItemPostgre[$i]        = "Nenhum registro encontrado!";
	        }
	      } else {

	       	if ($oSettings->current_setting != $aParametro['valorpadrao']) {

	       	  $lErroSetings            = true;
	   	      $aListaParamPostgre[$i] = $aParametro['name'];
	   	      $aItemPostgre[$i]       = $oSettings->current_setting;
	        }
	      }
	    } else {

	      $lErroSetings            = true;
	      $aListaParamPostgre[$i]  = $aParametro['name'];
	      $aItemPostgre[$i]        = "Nenhum registro encontrado!";
	    }
	  } else {

	  	$lErroSetings            = true;
	    $aListaParamPostgre[$i] = $aParametro['name'];
	    $aItemPostgre[$i]       = "Nenhum registro encontrado!";
	  }
	  $i++;
	}

	pg_close($conn1);

	$sDiretorioDbportal = $oXmlEst->diretorio;

	if ( isset($sDiretorioDbportal['name']) ) {
  	$sNomePadrao = $sDiretorioDbportal['name'];
	}

	if (file_exists($sDirDocumentRoot.$sDiretorioDbportal['valorpadrao']."/tmp/")) {

	  $dirTmpDbPortal = fopen($sDirDocumentRoot.$sDiretorioDbportal['valorpadrao']."/tmp/dir.txt", "w");
	  if ($dirTmpDbPortal == false) {

	    $lDirTmpDbPortalExist = false;
	    $dirMsgDbportal       = $sDirDocumentRoot.$sDiretorioDbportal['valorpadrao']."/tmp/";
	  }
	} else {
	  $lDirTmpDbPortalExist   = false;
	  $dirMsgDbportal         = $sDirDocumentRoot.$sDiretorioDbportal['valorpadrao']."/tmp/";
	}

	if ($lDirTmpRaizExist == false || $lDirTmpDbPortalExist == false) {
	  $lErroDirTmp = true;
	}

	if ($lBrowser != true || $lErroMod == true || $lErroParam == true || $lErroDirTmp == true || $lErroSetings == true) {
		 $lErro = true;
	}

	if (!isset($sMsgCabecalho)) {

		if (!isset($sNomePadrao) && empty($sNomePadrao)) {
		  $sNomePadrao = 'sistema';
		}

	  $sMsgCabecalho  = "Antes de prosseguir com o login, no {$sNomePadrao}, voc&ecirc; ";
    $sMsgCabecalho .= "precisa instalar as extens&otilde;es do servidor que est&atilde;o pendentes.";
	}

  if (isset($lErro) && $lErro == true) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Instala&ccedil;&atilde;o de extens&otilde;es pendentes do servidor</title>
<link href='estilos/requisitos.css' rel='stylesheet' type='text/css'>
</head>
<body>
<table border="0" width="100%">
	<tr align="center">
		<td>
		<div id="lista_pendente" align="left">
		<div id="titulo">Verificação de Configurações </div>
		<div id="conteudo">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="0" height="0" nowrap="nowrap"> &nbsp;<?=$sMsgCabecalho?></td>
			</tr>
			<tr>
				<td>&nbsp;Se preferir entre em contato com o administrador de seu sistema.</td>
			</tr>
			<tr>
				<td>&nbsp;Segue a lista abaixo:</td>
			</tr>
		</table>
		</div>

<?
if (count(@$aListaModulos) > 0) {

  echo "<table border=0 cellpadding=0 cellspacing=2 width=100% height=100%>";
  echo "<tr rowspan=0>";
  echo "  <td><b>&nbsp;Módulos Pendentes:</b></td>";
  echo "</tr>";

  foreach ($aListaModulos as $aValCampo) {

      echo "<tr id=lista_pendente_tr rowspan=0>";
      echo "  <td id=lista_pendente_td>".$aValCampo."</td>";
      echo "</tr>";

  }
  echo "</table>";

}

if (count(@$aListaParam) > 0) {

  echo "<table border=0 cellpadding=0 cellspacing=2 width=100% height=100%>";
  echo "<tr rowspan=0>";
  echo "  <td><b>&nbsp;Parâmetro PHP.INI:</b></td>";
  echo "  <td><b>&nbsp;Nome no PHP.INI:</b></td>";
  echo "  <td><b>&nbsp;Valor Requirido:</b></td>";
  echo "  <td><b>&nbsp;Valor Encontrado:</b></td>";
  echo "</tr>";

  foreach ($oXmlEst->parametros->parametro as $aParametro) {

    if ( !db_compara_conf_php(ini_get($aParametro['name']), $aParametro['valorpadrao'], $aParametro['bool'],
                                      $aParametro['operacao']) ) {

      echo "<tr id=lista_pendente_tr rowspan=0>";
      echo "  <td id=lista_pendente_td>".$aParametro['label']."</td>";
            echo "  <td id=lista_pendente_td>".$aParametro['name']."</td>";
      echo "  <td id=lista_pendente_td>".$aParametro['label_valorpadrao']." </td>";

      if ($aParametro['bool'] == 'true'){

  	    if (ini_get($aParametro['name']) == 1) {
          echo "  <td id=lista_pendente_td>On</td>";
        } else if (ini_get($aParametro['name']) == 0) {
          echo "  <td id=lista_pendente_td>Off</td>";
        }else{
          echo "  <td id=lista_pendente_td>".ini_get($aParametro['name'])."</td>";
        }

      } else {

        if (ini_get($aParametro['name']) == 1) {
          echo "  <td id=lista_pendente_td>On</td>";
        } else if (ini_get($aParametro['name']) == 0) {
          echo "  <td id=lista_pendente_td>Off</td>";
        }else{
          echo "  <td id=lista_pendente_td>".ini_get($aParametro['name'])."</td>";
        }

      }
      echo "</tr>";
  	}
  }
  echo "</table>";
}

if (count(@$aListaParamPostgre) > 0) {

  echo "<table border=0 cellpadding=0 cellspacing=2 width=100% height=100%>";
  echo "<tr rowspan=0>";
  echo "  <td><b>&nbsp;Parâmetro POSTGRESQL:</b></td>";
  echo "  <td><b>&nbsp;Nome no POSTGRESQL:</b></td>";
  echo "  <td><b>&nbsp;Valor Requirido:</b></td>";
  echo "  <td><b>&nbsp;Valor Encontrado:</b></td>";
  echo "</tr>";

  $i = 0;
  foreach ($oXmlEst->database->parametro as $aParametro) {

    if ($aParametro['name'] == $aListaParamPostgre[$i]) {
       echo "<tr id=lista_pendente_tr rowspan=0>";
       echo "  <td id=lista_pendente_td>".$aParametro['label']."</td>";
       echo "  <td id=lista_pendente_td>".$aParametro['name']."</td>";

         if ($aParametro['bool'] == 'true') {
         	$sStrpos = strpos($aParametro['valorpadrao'],'*');

         	if ($sStrpos === false) {
         	   echo "  <td id=lista_pendente_td>".$aParametro['valorpadrao']."</td>";
         	} else {
         	   $sVersaoConfigurada = substr($aParametro['valorpadrao'], 0,$sStrpos-1);
         	   echo "  <td id=lista_pendente_td>".$sVersaoConfigurada."</td>";
         	}

         } else {
            echo "  <td id=lista_pendente_td>".$aParametro['valorpadrao']."</td>";
         }

       echo "  <td id=lista_pendente_td>".$aItemPostgre[$i]." </td>";
       echo "</tr>";
  	}
  	$i++;
  }
  echo "</table>";
}

if ($lDirTmpRaizExist == false || $lDirTmpDbPortalExist == false) {

  echo "<table border=0 width=100% height=100%>";
  echo "<tr>";
  echo "  <td><b>&nbsp;Diretório(s) não encontrado(s) ou sem permissão de escrita:</b></td>";
  echo "</tr>";
  echo "<tr id=lista_pendente_tr rowspan=0>";

    if ($lDirTmpRaizExist == false) {

       echo "<td id=lista_pendente_td>".$dirMsgRaiz."</td>";

     }
  echo "</tr>";
  echo "<tr id=lista_pendente_tr rowspan=0>";

    if ($lDirTmpDbPortalExist == false) {

       echo "<td id=lista_pendente_td>".$dirMsgDbportal."</td>";

     }
}

if ($lBrowser == false) {

  echo "<table border=0 width=100% height=100%>";
  echo "<tr rowspan=0>";
  echo "  <td><b>&nbsp;Browser Incompativel:</b></td>";
  echo "</tr>";
  echo "<tr id=lista_pendente_tr rowspan=0>";
  echo "  <td id=lista_pendente_td>".$sBrowser."</td>";
  echo "</tr>";
  echo "</table>";

}

if (isset($sMsgErro) && $sMsgErro != "") {

  echo "<table border=0 width=100% height=100%>";
  echo "<tr rowspan=0>";
  echo "  <td><b>&nbsp;Parametros de configuração do PostgreSQL:</b></td>";
  echo "</tr>";
  echo "<tr id=lista_pendente_tr rowspan=0>";
  echo "  <td id=lista_pendente_td>".$sMsgErro."</td>";
  echo "</tr>";
  echo "</table>";

}

if ($lErroConf == true) {

  echo "<table border=0 width=100% height=100%>";
  echo "<tr rowspan=0>";
  echo "  <td><b>&nbsp;Arquivo Inexistente ou Parametro(s) de Configuração não Configurado(s) Corretamente:</b></td>";
  echo "</tr>";
  echo "<tr id=lista_pendente_tr rowspan=0>";
  echo "  <td id=lista_pendente_td>".$sMsgConf."</td>";
  echo "</tr>";
  echo "</table>";

}

  echo "</tr>";
  echo "</table>";
  echo "<p></p>";
?>
  </div>
		</td>
	</tr>
</table>
<?
	} else {

		if ( isset($lVerificaRequisitos) && $lVerificaRequisitos == true ) {
?>
<table border="0" width="100%">
	<tr align="center">
		<td>
		<div id="lista_pendente" align="left">
		<div id="titulo">Verificação de Configurações </div>
		<div id="conteudo">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="0" height="0" nowrap="nowrap">&nbsp;</td>
			</tr>
		</table>
		</div>
    <?
			if ($lErroConf == true) {

			  echo "<table border=0 width=100% height=100%>";
			  echo "<tr rowspan=0>";
			  echo "  <td><b>&nbsp;Arquivo Inexistente ou Parametro(s) de Configuração não Configurado(s) Corretamente:</b></td>";
			  echo "</tr>";
			  echo "<tr id=lista_pendente_tr rowspan=0>";
			  echo "  <td id=lista_pendente_td>".$sMsgConf."</td>";
			  echo "</tr>";
			  echo "</table>";

			} else {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Instala&ccedil;&atilde;o de extens&otilde;es pendentes do servidor</title>
<link href='estilos/requisitos.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div id="conteudo">
	    <table width="100%" border="0" cellpadding="4" cellspacing="4">
	      <tr>
	        <td width="0" height="0" nowrap="nowrap"> &nbsp;<?=$sMsgCabecalho?></td>
	      </tr>
	      <tr>
	        <td>&nbsp;Se preferir entre em contato com o administrador de seu sistema.</td>
	      </tr>
	      <tr>
	        <td>&nbsp;Segue a lista abaixo:</td>
	      </tr>
	    </table>
    </div>

    <table border=0 width=100% height=100%>
      <tr rowspan=0>
        <td><b>&nbsp;Verifica Config. Homologadas do Sistema:</b></td>
      </tr>
      <tr id=lista_pendente_tr rowspan=0>
        <td id=lista_pendente_td>Configurações OK.</td>
      </tr>
    </table>

		<?} ?>
      </div>
		</td>
	</tr>
</table>
<?
		} else {

			if (isset($lErro) && $lErro == false) {
	      session_register("DB_configuracao_ok");
	      header("Location: login.php");
			}
		}
	}
} else {

	if ($lErroConf == true) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Instala&ccedil;&atilde;o de extens&otilde;es pendentes do servidor</title>
<link href='estilos/requisitos.css' rel='stylesheet' type='text/css'>
</head>
<body>
<table border='0' width='100%'>
	<tr align='center'>
		<td>
		<div id='lista_pendente' align='left'>
		<div id='titulo'>Verificação de Configurações <img src='' /></div>
		<div id='conteudo'>
		<table width='100%' border='0' cellpadding='0' cellspacing='0'>
			<tr>
				<td width='0' height='0' nowrap='nowrap'>&nbsp;</td>
			</tr>
		</table>
		</div>
		<table border='0' width='100%' height='100%'>
			<tr rowspan='0'>
				<td><b>&nbsp;Arquivo Inexistente ou Parametro(s) de Configuração não Configurado(s) Corretamente:</b></td>
			</tr>
			<tr id='lista_pendente_tr' rowspan='0'>
				<td id='lista_pendente_td'><?=$sMsgConf?></td>
			</tr>
		</table>
		</div>
		</td>
	</tr>
</table>
</body>
<html>
<?
	} else {

		if ( isset($lVerificaRequisitos) && $lVerificaRequisitos == true ) {
?>
<table border="0" width="100%">
  <tr align="center">
    <td>
    <div id="lista_pendente" align="left">
    <div id="titulo">Verificação de Configurações </div>
    <div id="conteudo">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="0" height="0" nowrap="nowrap">&nbsp;</td>
      </tr>
    </table>
    </div>
    <?
      if ($lErroConf == true) {

        echo "<table border=0 width=100% height=100%>";
        echo "<tr rowspan=0>";
        echo "  <td><b>&nbsp;Arquivo Inexistente ou Parametro(s) de Configuração não Configurado(s) Corretamente:</b></td>";
        echo "</tr>";
        echo "<tr id=lista_pendente_tr rowspan=0>";
        echo "  <td id=lista_pendente_td>".$sMsgConf."</td>";
        echo "</tr>";
        echo "</table>";

      } else {
    ?>
    <table border=0 width=100% height=100%>
      <tr rowspan=0>
        <td><b>&nbsp;Verifica Config. Homologadas do Sistema:</b></td>
      </tr>
      <tr id=lista_pendente_tr rowspan=0>
        <td id=lista_pendente_td>Configurações OK.</td>
      </tr>
    </table>
    <?} ?>
  </div>
    </td>
  </tr>
</table>
<?
		} else {

      session_register("DB_configuracao_ok");
      header("Location: login.php");
		}
	}
}
?>
</body>
<html>
<?
/**
 * retorna o browser do usuário
 * @return array
 */
function getBrowser()  {
     $var = $_SERVER['HTTP_USER_AGENT'];
     $info['browser'] = "OTHER";

     $browser = array ("MSIE", "OPERA", "CHROME", "FIREFOX", "MOZILLA",
                       "NETSCAPE", "SAFARI", "LYNX", "KONQUEROR");

     $bots = array('GOOGLEBOT', 'MSNBOT', 'SLURP');

     foreach ($bots as $bot) {
         if (strpos(strtoupper($var), $bot) !== FALSE) {
             return $info;
         }
     }

     foreach ($browser as $parent) {
         $s = strpos(strtoupper($var), $parent);
         $f = $s + strlen($parent);
         $version = substr($var, $f, 10);
         $version = preg_replace('/[^0-9,.]/','',$version);
         if (strpos(strtoupper($var), $parent) !== FALSE) {
             $info['browser'] = $parent;
             $info['version'] = $version;
             return $info;
         }
     }
     return $info;
}

function db_compara_conf_php($sValorIni, $sValorConfig, $lBoolean, $sOperacao='==') {

  if ($lBoolean == 'false') {
    $sValorIni    = ereg_replace('[^0-9]', '', $sValorIni);
    $sValorConfig = ereg_replace('[^0-9]', '', $sValorConfig);
  }

  $nValorIni    = (trim($sValorIni)=='')?0:$sValorIni;
  $nValorConfig = (trim($sValorConfig)=='')?0:$sValorConfig;

  switch ($sOperacao) {
    case "==":
    case "=":
      $lRetorno = ($nValorIni == $nValorConfig);
      break;
    case ">":
      $lRetorno = ($nValorIni > $nValorConfig);
      break;
    case ">=":
      $lRetorno = ($nValorIni >= $nValorConfig);
      break;
    case "<":
      $lRetorno = ($nValorIni < $nValorConfig);
      break;
    case "<=":
      $lRetorno = ($nValorIni <= $nValorConfig);
      break;
    default:
      $lRetorno = false;
      break;
  }

  return $lRetorno;

}
?>

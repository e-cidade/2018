<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require(modification("libs/db_stdlib.php"));
require(modification("std/db_stdClass.php"));
require(modification('libs/db_utils.php'));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_empnotaord_classe.php"));
include(modification("classes/db_empnota_classe.php"));
include(modification("classes/db_empnotadadospit_classe.php"));

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clempnotaord = new cl_empnotaord;
$clempnota = new cl_empnota;

$clrotulo = new rotulocampo();
$clrotulo->label("nome");
$clrotulo->label("z01_nome");
$clrotulo->label("m51_numcgm");
$clrotulo->label("m51_codordem");
$clrotulo->label("e69_numero");
$clrotulo->label("e69_codnota");
$clrotulo->label("e69_dtnota");
$clrotulo->label("e69_dtrecebe");
$clrotulo->label("e70_valor");
$clrotulo->label("m51_valortotal");
$clrotulo->label("m51_depto");
$clrotulo->label("descrdepto");
$clrotulo->label("e11_cfop");
$clrotulo->label("e10_cfop");
$clrotulo->label("e11_seriefiscal");
$clrotulo->label("e11_inscricaosubstitutofiscal");
$clrotulo->label("e11_valoricmssubstitutotrib");
$clrotulo->label("e11_basecalculoicmssubstitutotrib");
$clrotulo->label("e11_basecalculoicms");
$clrotulo->label("e11_valoricms");
$clrotulo->label("e12_descricao");
$clrotulo->label("e10_descricao");

$sPesquisa  = false;
$lMsgError  = false;
$lSqlErro   = false;
$lDisabled2 = false;
$iControlaPit = 2;
$db_opcao = 1;
$aParamKeys = array(
                    db_getsession("DB_instit")
                   );
$aParametrosPit   = db_stdClass::getParametro("matparaminstit",$aParamKeys);
if (count($aParametrosPit) > 0) {
  $iControlaPit = $aParametrosPit[0]->m10_controlapit;
}

if (isset($oPost->alterar)) {

  try {

    $lSqlErro = false;

    db_inicio_transacao();

    if (empty($oPost->e69_numero)) {
      $oPost->e69_numero = 'S/N';
    }

    /** [Extensao ContratosPADRS] valida serie nota */

    $clempnota->e69_codnota  = $oPost->e69_codnota;
    $clempnota->e69_dtnota   =  implode("-", array_reverse(explode("/", $oPost->e69_dtnota)));
    $clempnota->e69_dtrecebe =  implode("-", array_reverse(explode("/", $oPost->e69_dtrecebe)));
    $clempnota->e69_tipodocumentosfiscal  = $oPost->e69_tipodocumentosfiscal;
    $clempnota->e69_numero = $oPost->e69_numero;
    $clempnota->alterar($clempnota->e69_codnota);

    /** [Extensao ContratosPADRS] nota liquidacao */

    $erro_msg = $clempnota->erro_msg;
    if ($clempnota->erro_status == "0") {

      $lSqlErro = true;
      $erro_msg = $clempnota->erro_msg;
          
    }
    $oDaoEmpnotadados = new cl_empnotadadospit;
    $sSqlDadosPit     = $oDaoEmpnotadados->sql_query_notas(null, 
                                                          "empnotadadospit.*,e10_cfop,e10_descricao,e14_situacao",
                                                          "e14_sequencial desc limit 1",
                                                          "e13_empnota = {$oPost->e69_codnota}");
    $rsDadosPit       = $oDaoEmpnotadados->sql_record($sSqlDadosPit);
    if ($oDaoEmpnotadados->numrows > 0) {

      $oDadosPit = db_utils::fieldsMemory($rsDadosPit, 0);
      if ($oDadosPit->e14_situacao == 1) {
        $db_opcao = 3;
      } else {

        if ($e69_tipodocumentosfiscal ==  50) { 

          $oDaoEmpnotaDadosPit                                =  db_utils::getDao("empnotadadospit");
          $oDaoEmpnotaDadosPit->e11_cfop                      =  $e11_cfop;
          $oDaoEmpnotaDadosPit->e11_seriefiscal               =  $e11_seriefiscal;
          $oDaoEmpnotaDadosPit->e11_inscricaosubstitutofiscal =  $e11_inscricaosubstitutofiscal;
          $oDaoEmpnotaDadosPit->e11_basecalculoicms           = "$e11_basecalculoicms";
          $oDaoEmpnotaDadosPit->e11_valoricms                 = "$e11_valoricms";
          $oDaoEmpnotaDadosPit->e11_basecalculosubstitutotrib = "$e11_basecalculosubstitutotrib";
          $oDaoEmpnotaDadosPit->e11_valoricmssubstitutotrib   = "$e11_valoricmssubstitutotrib";
          $oDaoEmpnotaDadosPit->e11_sequencial                = $oDadosPit->e11_sequencial; 
          $oDaoEmpnotaDadosPit->alterar($oDadosPit->e11_sequencial);
          if ($oDaoEmpnotaDadosPit->erro_status == 0) {
            
            $lSqlErro = true;
            $erro_msg = $oDaoEmpnotaDadosPit->erro_msg;
            
          }      
        }
      }
    } else {
      
      if ($e11_cfop != "" && $e69_tipodocumentosfiscal == 50 && !$lSqlErro) {
        
        $oDaoEmpnotaDadosPit                                =  db_utils::getDao("empnotadadospit");
        $oDaoEmpnotaDadosPit->e11_cfop                      =  $e11_cfop;
        $oDaoEmpnotaDadosPit->e11_seriefiscal               =  $e11_seriefiscal;
        $oDaoEmpnotaDadosPit->e11_inscricaosubstitutofiscal =  $e11_inscricaosubstitutofiscal;
        $oDaoEmpnotaDadosPit->e11_basecalculoicms           = "$e11_basecalculoicms";
        $oDaoEmpnotaDadosPit->e11_valoricms                 = "$e11_valoricms";
        $oDaoEmpnotaDadosPit->e11_basecalculosubstitutotrib = "$e11_basecalculosubstitutotrib";
        $oDaoEmpnotaDadosPit->e11_valoricmssubstitutotrib   = "$e11_valoricmssubstitutotrib";
        $oDaoEmpnotaDadosPit->incluir(null);
        if ($oDaoEmpnotaDadosPit->erro_status == 0) {
          
          $lSqlErro = true;
          $erro_msg = $oDaoEmpnotaDadosPit->erro_msg;
          
        }
        
        if (!$lSqlErro) {
           
           $oDaoEmpnotaDadosPitNota  = db_utils::getDao("empnotadadospitnotas");
           $oDaoEmpnotaDadosPitNota->e13_empnota         = $oPost->e69_codnota;
           $oDaoEmpnotaDadosPitNota->e13_empnotadadospit = $oDaoEmpnotaDadosPit->e11_sequencial;
           $oDaoEmpnotaDadosPitNota->incluir(null);
           if ($oDaoEmpnotaDadosPitNota->erro_status == 0) {
             
             $lSqlErro = true;
             $erro_msg = $oDaoEmpnotaDadosPitNota->erro_msg;
             
           }
         }
      }
    }

    db_fim_transacao($lSqlErro);

  }	catch (Exception $e) {

    db_fim_transacao(true);

    $lSqlErro = true;
    $erro_msg = $e->getMessage();
  }
}

if (isset($oGet->chavepesquisa) && $oGet->chavepesquisa != "") {

	$sSqlNotas = $clempnotaord->sql_query_elemento($oGet->chavepesquisa);
  $rsNota    = $clempnotaord->sql_record($sSqlNotas);
  if ($clempnotaord->numrows > 0) {  	
    $oNota = db_utils::fieldsMemory($rsNota, 0);
    
    $m51_codordem     =  $oNota->m51_codordem;
    $m51_numcgm       =  $oNota->m51_numcgm;
    $z01_nome         =  $oNota->z01_nome;
    $m51_depto        =  $oNota->m51_depto;
    $descrdepto       =  $oNota->descrdepto;
    $m51_valortotal   =  $oNota->m51_valortotal;
    
    $e69_numero       =  $oNota->e69_numero;
    $e69_codnota      =  $oNota->e69_codnota;
    $e69_dtnota       =  explode("-", $oNota->e69_dtnota);
    $e69_dtnota_dia   =  $e69_dtnota[2];
    $e69_dtnota_mes   =  $e69_dtnota[1];
    $e69_dtnota_ano   =  $e69_dtnota[0];
    $e69_dtrecebe     =  explode("-", $oNota->e69_dtrecebe);
    $e69_dtrecebe_dia =  $e69_dtrecebe[2];
    $e69_dtrecebe_mes =  $e69_dtrecebe[1];
    $e69_dtrecebe_ano =  $e69_dtrecebe[0];
    $e69_tipodocumentosfiscal = $oNota->e69_tipodocumentosfiscal;
    $e70_valor        =  $oNota->e70_valor;
    
    $e70_vlranu       =  $oNota->e70_vlranu;
    $e70_vlrliq       =  $oNota->e70_vlrliq;

    /** [Extensao ContratosPADRS] consulta numeroserie */
    
    $oDaoEmpnotadados = new cl_empnotadadospit;
    $sSqlDadosPit     = $oDaoEmpnotadados->sql_query_notas(null, 
                                                          "empnotadadospit.*,e10_cfop,e10_descricao,e14_situacao",
                                                          "e14_sequencial desc limit 1",
                                                          "e13_empnota = {$e69_codnota}");
    $rsDadosPit       = $oDaoEmpnotadados->sql_record($sSqlDadosPit);
    if ($oDaoEmpnotadados->numrows > 0) {

      $oDadosPit = db_utils::fieldsMemory($rsDadosPit, 0);
      if ($oDadosPit->e14_situacao == 1) {
        $db_opcao = 3;
      }
      db_fieldsmemory($rsDadosPit, 0);
    }
  } else {
  	$lMsgError = true;
  }
} else {
	$sPesquisa = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="empenho.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<?php include(modification("forms/db_frmaltdadosnotafiscal.php"));?>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
 if ($lMsgError){
 	  $lDisabled2 = true;
    db_msgbox('Sistema não permite alterar nota fiscal \\nlançada sem vinculação com ordem de compra. \\n\\nContate suporte!');
    echo "<script>$('alterar').disabled = true;</script>";	
 }
 if (isset($erro_msg)){
 	db_msgbox($erro_msg);
 }
?>
<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("classes/db_gerfadi_classe.php"));
require_once(modification("classes/db_rhrubelemento_classe.php"));

db_postmemory($HTTP_GET_VARS);

$clgerfadi       = new cl_gerfadi();
$clrhrubelemento = new cl_rhrubelemento();
$sBaseSessao     = db_getsession('DB_base');
$lSqlErro        = false;
$sMsgFinal       = "";

/**
 * Verifica se a base escolhida é a mesma base de produção
 * $sMunicipio é passado por parâmetro pelo pes1_relrefeisul001.php
 */

if ( trim($sMunicipio) == trim($sBaseSessao) ) {

  db_msgbox("Esta rotina não pode ser executada na base de produção.");
  echo "<script>parent.db_iframe_relrefeisul.hide();</script>";
  exit; 
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<br><br>

 <?
    db_inicio_transacao();
    
    if ( $lSqlErro == false ) {
      
      $clgerfadi->r22_anousu = $iAnoFolha;
      $clgerfadi->r22_mesusu = $iMesFolha; 
      $clgerfadi->excluir($clgerfadi->r22_anousu, $clgerfadi->r22_mesusu);
      
      if ($clgerfadi->erro_status == "0") {
        
        $sMsgFinal = $clgerfadi->erro_msg;
        $lSqlErro  = true;
      }
    }
    
    if ( $lSqlErro == false ) {
      
      $clrhrubelemento->rh23_codele = 1300;
      $clrhrubelemento->rh23_rubric = 1900;
      $clrhrubelemento->rh23_instit = 1;
      $clrhrubelemento->alterar($clrhrubelemento->rh23_rubric, $clrhrubelemento->rh23_instit);
      
      if ($clrhrubelemento->erro_status == "0") {
        
        $sMsgFinal = $clrhrubelemento->erro_msg;
        $lSqlErro  = true;
      }      
    }
    
    if ( $lSqlErro == false ) {
      
      $sSqlDadosRefeisul  = "select rh49_anousu as r22_anousu, ";
      $sSqlDadosRefeisul .= "       rh49_mesusu as r22_mesusu, ";
      $sSqlDadosRefeisul .= "       rh49_regist as r22_regist, ";
      $sSqlDadosRefeisul .= "       '1900'      as r22_rubric, ";
      $sSqlDadosRefeisul .= "       (visa-calc) as r22_valor, ";
      $sSqlDadosRefeisul .= "       1           as r22_pd, ";
      $sSqlDadosRefeisul .= "       0           as r22_quant, ";
      $sSqlDadosRefeisul .= "       rh02_lota   as r22_lotac, ";
      $sSqlDadosRefeisul .= "       1           as r22_instit ";
      $sSqlDadosRefeisul .= "  from ( select rh49_anousu, ";
      $sSqlDadosRefeisul .= "                rh49_mesusu, ";
      $sSqlDadosRefeisul .= "                rh49_regist, ";
      $sSqlDadosRefeisul .= "                round(coalesce(rh49_valormes,0),2) as visa, ";
      $sSqlDadosRefeisul .= "                round(coalesce(r14_valor,0),2) as calc  ";
      $sSqlDadosRefeisul .= "           from rhvisavalecad  ";
      $sSqlDadosRefeisul .= "                left join ( select r14_anousu, "; 
      $sSqlDadosRefeisul .= "                                   r14_mesusu, ";
      $sSqlDadosRefeisul .= "                                   r14_regist, ";
      $sSqlDadosRefeisul .= "                                   r14_lotac, ";
      $sSqlDadosRefeisul .= "                                   r14_valor  ";
      $sSqlDadosRefeisul .= "                              from gerfsal  ";
      $sSqlDadosRefeisul .= "                             where r14_anousu = {$iAnoFolha} "; 
      $sSqlDadosRefeisul .= "                               and r14_mesusu = {$iMesFolha}  ";
      $sSqlDadosRefeisul .= "                               and r14_rubric in ('1705', '1706')";
      $sSqlDadosRefeisul .= "                      union  ";
      $sSqlDadosRefeisul .= "                            select r20_anousu, "; 
      $sSqlDadosRefeisul .= "                                   r20_mesusu, ";
      $sSqlDadosRefeisul .= "                                   r20_regist, ";
      $sSqlDadosRefeisul .= "                                   r20_lotac, ";
      $sSqlDadosRefeisul .= "                                   r20_valor  ";
      $sSqlDadosRefeisul .= "                             from gerfres  ";
      $sSqlDadosRefeisul .= "                             where r20_anousu = {$iAnoFolha} "; 
      $sSqlDadosRefeisul .= "                               and r20_mesusu = {$iMesFolha}  ";
      $sSqlDadosRefeisul .= "                               and r20_rubric in ('1705', '1706') ) as x on rh49_regist = r14_regist "; 
      $sSqlDadosRefeisul .= "          where rh49_anousu = {$iAnoFolha}  ";
      $sSqlDadosRefeisul .= "            and rh49_mesusu = {$iMesFolha} ) as xxx "; 
      $sSqlDadosRefeisul .= "       inner join rhpessoalmov on rh02_regist = rh49_regist   ";
      $sSqlDadosRefeisul .= "                              and rh02_anousu = {$iAnoFolha}  ";
      $sSqlDadosRefeisul .= "                              and rh02_mesusu = {$iMesFolha}; ";    
      
      $rsDadosRefeisul      = db_query($sSqlDadosRefeisul);
      $iLinhasDadosRefeisul = pg_num_rows($rsDadosRefeisul);
      
      db_criatermometro('termometro');
      
      if ( $iLinhasDadosRefeisul == 0 ) {
        
        $lSqlErro  = true;
        $sMsgFinal = "Registros não encontrados.";
      } else {
        
        for ( $iDados = 0; $iDados < $iLinhasDadosRefeisul; $iDados++ ) {
          
          if ( $lSqlErro == false ) {
          
            db_atutermometro($iDados, $iLinhasDadosRefeisul, 'termometro');
            $oGerfadi = db_utils::fieldsMemory($rsDadosRefeisul, $iDados);
  
            $clgerfadi->r22_anousu = $oGerfadi->r22_anousu;
            $clgerfadi->r22_instit = $oGerfadi->r22_instit;
            $clgerfadi->r22_lotac  = $oGerfadi->r22_lotac;
            $clgerfadi->r22_mesusu = $oGerfadi->r22_mesusu;
            $clgerfadi->r22_pd     = $oGerfadi->r22_pd;
            $clgerfadi->r22_quant  = $oGerfadi->r22_quant;
            $clgerfadi->r22_regist = $oGerfadi->r22_regist;
            $clgerfadi->r22_rubric = $oGerfadi->r22_rubric;
            $clgerfadi->r22_valor  = $oGerfadi->r22_valor;
            
            $lIncluiGerfadi = $clgerfadi->incluir($clgerfadi->r22_anousu,
                                                  $clgerfadi->r22_mesusu,
                                                  $clgerfadi->r22_regist,
                                                  $clgerfadi->r22_rubric);
            
            if ( $lIncluiGerfadi == false || $clgerfadi->erro_status == "0" ) {
              
              $sMsgFinal = $clgerfadi->erro_msg;
              $lSqlErro  = true;
            }
          }
        }
      }
    }
    
    if ( $lSqlErro == false ) {
      $sMsgFinal = "Processo concluído com sucesso.";
    }

    db_fim_transacao($lSqlErro);
    
    if (!empty($sMsgFinal)) {
      db_msgbox($sMsgFinal);
    }    
 ?>
<script>
  parent.db_iframe_relrefeisul.hide();
</script>
</body>
</html>
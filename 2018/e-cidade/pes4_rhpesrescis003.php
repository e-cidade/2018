<?php

/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_rhpessoal_classe.php"));
require_once(modification("classes/db_rhpesrescisao_classe.php"));
require_once(modification("classes/db_pontofr_classe.php"));
require_once(modification("classes/db_gerfres_classe.php"));
require_once(modification("classes/db_previden_classe.php"));
require_once(modification("classes/db_ajusteir_classe.php"));
require_once(modification("classes/db_pensao_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libpessoal.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clrhpesrescisao = new cl_rhpesrescisao;
$clrhpessoal     = new cl_rhpessoal;
$clpontofr       = new cl_pontofr;
$clgerfres       = new cl_gerfres;
$clpreviden      = new cl_previden;
$clajusteir      = new cl_ajusteir;
$clpensao        = new cl_pensao;

$db_opcao        = 1;
$db_botao        = true;

$iAnoFolha       = DBPessoal::getAnoFolha();
$iMesFolha       = DBPessoal::getMesFolha();

// Bloqueio da liberação do contracheque no DBPref
if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

  try {
    FolhaPagamentoRescisao::verificaLiberacaoDBPref();
  } catch (Exception $e) {

    $db_opcao = 3;
    $db_botao = false;
    db_msgbox($e->getMessage());
  }
}

if (isset($excluir)) {
	
  db_inicio_transacao();
  
  $lSqlErro  = false;
  
  $erro_msg = "Processo concluído com sucesso.";

  if (!$lSqlErro) {
                
    $clrhpesrescisao->excluir($rh02_seqpes);
    if ( $clrhpesrescisao->erro_status == 0 ) {
      $lSqlErro = true;
      $erro_msg = $clrhpesrescisao->erro_msg;
    }               
  }
  
  $sWhereRhpessoal  = "rh02_seqpes = {$rh02_seqpes}";
  $sCampos          = "rh01_regist as matric,rh01_numcgm,rh02_tbprev";
  $result_rhpessoal = $clrhpessoal->sql_record($clrhpessoal->sql_query_rescisao(null,$sCampos,"",$sWhereRhpessoal));
  if ($clrhpessoal->numrows > 0) {
  	
    db_fieldsmemory($result_rhpessoal,0);

    if (!$lSqlErro) {

      $sWherePontofr  = "     r19_regist = {$matric}";
      $sWherePontofr .= " and r19_anousu = {$iAnoFolha}";
      $sWherePontofr .= " and r19_mesusu = {$iMesFolha}";
      $sWherePontofr .= " and r19_instit = 1";
      $clpontofr->excluir(null,null,null,null,null,$sWherePontofr);
	    if ( $clpontofr->erro_status == 0 ) {
	      $lSqlErro = true;
	      $erro_msg = $clpontofr->erro_msg;
	    }               
    }   

    if (!$lSqlErro) {

      $sWhereGerfRes  = "     r20_regist = {$matric}     ";
      $sWhereGerfRes .= " and r20_anousu = {$iAnoFolha}  ";
      $sWhereGerfRes .= " and r20_mesusu = {$iMesFolha}  ";
      $sWhereGerfRes .= " and r20_instit = 1             ";
      $clgerfres->excluir(null,null,null,null,null,$sWhereGerfRes);
      if ( $clgerfres->erro_status == 0 ) {
        $lSqlErro = true;
        $erro_msg = $clgerfres->erro_msg;
      }               
    }     

    if (!$lSqlErro) {

      $sWhere  = "     r60_numcgm       = {$rh01_numcgm}   ";
      $sWhere .= " and r60_tbprev       = {$rh02_tbprev}   ";
      $sWhere .= " and r60_regist       = {$matric}        ";
      $sWhere .= " and lower(r60_folha) = 'r'              ";    	
      $clpreviden->excluir(null,null,null,null,null,$sWhere);
      if ( $clpreviden->erro_status == 0 ) {
        $lSqlErro = true;
        $erro_msg = $clpreviden->erro_msg;
      }               
    }

    if (!$lSqlErro) {

      $sWhere  = "     r61_numcgm       = {$rh01_numcgm}    ";
      $sWhere .= " and r61_regist       = {$matric}         ";
      $sWhere .= " and lower(r61_folha) = 'r'               ";     
      $clajusteir->excluir(null,null,null,null,null,null,$sWhere);
      if ($clajusteir->erro_status == 0) {
      	
        $lSqlErro = true;
        $erro_msg = $clajusteir->erro_msg;
      }               
    }     
    
    if (!$lSqlErro) {
    	
      $sSqlPensao   = " select distinct pensao.*,                                                                     "; 
      $sSqlPensao  .= "        rh01_regist as r01_regist,                                                             ";
      $sSqlPensao  .= "        trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac                                           "; 
      $sSqlPensao  .= "   from pensao                                                                                 ";
      $sSqlPensao  .= "        inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu        "; 
      $sSqlPensao  .= "                               and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu        ";
      $sSqlPensao  .= "                               and pensao.r52_regist         = rhpessoalmov.rh02_regist        ";
      $sSqlPensao  .= "        left  join pontofe      on pontofe.r29_anousu        = rhpessoalmov.rh02_anousu        ";
      $sSqlPensao  .= "                               and pontofe.r29_mesusu        = rhpessoalmov.rh02_mesusu        ";
      $sSqlPensao  .= "                               and pontofe.r29_regist        = rhpessoalmov.rh02_regist        ";
      $sSqlPensao  .= "        inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist        ";
      $sSqlPensao  .= "        inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota          ";
      $sSqlPensao  .= "                               and rhlota.r70_instit         = rhpessoalmov.rh02_instit        "; 
      $sSqlPensao  .= "        inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm           ";
      $sSqlPensao  .= "        left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes        ";
      $sSqlPensao  .= "  where r52_anousu = {$iAnoFolha}                                                              ";
      $sSqlPensao  .= "    and r52_mesusu = {$iMesFolha}                                                              ";
      $sSqlPensao  .= "    and rh05_recis is null                                                                     ";
      $sSqlPensao  .= "    and r52_regist = {$matric}                                                                 ";
      $sSqlPensao  .= "  order by r52_regist                                                                          ";

      $rsPensao     = db_query($sSqlPensao);
      $iNumrows     = pg_num_rows($rsPensao);

      if ($iNumrows > 0) {
      	
	      for ( $Ipensao = 0; $Ipensao < $iNumrows; $Ipensao++ ) {   
	      
	      	$oPensao = db_utils::fieldsMemory($rsPensao,$Ipensao);
	      	
	        if (!$lSqlErro) {
	          
	          $r52_regist = db_sqlformat($oPensao->r52_regist);
	          $r52_numcgm = db_sqlformat($oPensao->r52_numcgm);  
	          $clpensao->r52_regist = $oPensao->r52_regist;
	          $clpensao->r52_numcgm = $oPensao->r52_numcgm;
	          $clpensao->r52_mesusu = $iMesFolha;
	          $clpensao->r52_anousu = $iAnoFolha; 
	          $clpensao->r52_valres = 0;
	          $clpensao->alterar($iAnoFolha,$iMesFolha,$oPensao->r52_regist,$oPensao->r52_numcgm);
	          if ($clpensao->erro_status == 0) {
	            $lSqlErro = true;
	            $erro_msg = $clpensao->erro_msg;
	            break;
	          }               
	        } 
	      }      	
      }    	
    }
  }
  
  db_fim_transacao($lSqlErro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <br><br>
    <?php include(modification("forms/db_frmexcrhpesrescis.php")); ?>
    </center>
    </td>
  </tr>
</table>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?php
if (isset($excluir)) {
	
  db_msgbox($erro_msg);
  if( $lSqlErro == false ) {
    echo "
          <script>
            location.href = 'pes4_rhpesrescis003.php';
          </script>
         ";
  }
}
?>
<script>
js_tabulacaoforms("form1","rh01_regist",true,1,"rh01_regist",true);
</script>
<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("classes/db_rhpesrescisao_classe.php");
include("classes/db_rhpessoal_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrhpesrescisao = new cl_rhpesrescisao;
$clrhpessoal = new cl_rhpessoal;
$db_opcao = 1;
$db_botao = true;

$rh02_anousu = db_anofolha();
$rh02_mesusu = db_mesfolha();
if(isset($excluir)){
  db_inicio_transacao();
  $subpes = db_anofolha()."/".db_mesfolha();
  $erro = false;
  $erro_msg = "Processo concluído com sucesso.";
  $result_rhpesrescisao = db_delete("rhpesrescisao", " where rh05_seqpes = ".db_sqlformat($rh02_seqpes));
  if($result_rhpesrescisao == false){
    $erro_msg = "Erro ao excluir rescisão. Contate o suporte.";
    $erro = true;
  }

  $result_rhpessoal = $clrhpessoal->sql_record($clrhpessoal->sql_query_rescisao(null,"rh01_regist as matric,rh01_numcgm,rh02_tbprev","","rh02_seqpes = ".$rh02_seqpes));
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result_rhpessoal,0);
    $condicaoaux = " and r19_regist = ".db_sqlformat($matric);
    $result_pontofr = db_delete("pontofr", bb_condicaosubpes("r19_").$condicaoaux);
    if($result_pontofr == false){
      $erro_msg = "Erro ao excluir ponto de rescisão. Contate o suporte.";
      $erro = true;
    }

    $condicaoaux = " and r20_regist = ".db_sqlformat($matric);
    $result_gerfres = db_delete("gerfres", bb_condicaosubpes("r20_").$condicaoaux);
    if($result_gerfres == false){
      $erro_msg = "Erro ao excluir folha de rescisão. Contate o suporte.";
      $erro = true;
    }

    $condicaoaux  = " and r60_numcgm = ".db_sqlformat($rh01_numcgm);
    $condicaoaux .= " and r60_tbprev = ".db_sqlformat($rh02_tbprev);
    $condicaoaux .= " and r60_regist = ".db_sqlformat($matric);
    $condicaoaux .= " and lower(r60_folha)  = ".db_sqlformat('r');
    $result_previden = db_delete("previden", bb_condicaosubpes("r60_").$condicaoaux);
    if($result_previden == false){
      $erro_msg = "Erro ao excluir previdência. Contate o suporte.";
      $erro = true;
    }

    $condicaoaux  = " and r61_numcgm = ".db_sqlformat($rh01_numcgm);
    $condicaoaux .= " and r61_regist = ".db_sqlformat($matric);
    $condicaoaux .= " and lower(r61_folha)  = ".db_sqlformat('r');
    $result_ajusteir = db_delete("ajusteir", bb_condicaosubpes("r61_").$condicaoaux);
    if($result_ajusteir == false){
      $erro_msg = "Erro ao excluir ajuste do IR. Contate o suporte.";
      $erro = true;
    }
    global $pensao;
      $condicaoaux  = " and  rh05_recis is null ";
      $condicaoaux .= " and r52_regist = ".db_sqlformat($matric);
      $condicaoaux .= " order by r52_regist ";
      $sql = "select distinct(r52_regist+r52_numcgm), 
                                     pensao.*, 
                                     rh01_regist as r01_regist,
                                     trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac 
                              from pensao
                                  inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu 
                                                         and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu 
                                                         and pensao.r52_regist         = rhpessoalmov.rh02_regist
                                  left  join pontofe      on pontofe.r29_anousu        = rhpessoalmov.rh02_anousu 
                                                         and pontofe.r29_mesusu        = rhpessoalmov.rh02_mesusu 
                                                         and pontofe.r29_regist        = rhpessoalmov.rh02_regist
                                  inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
                                  inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                         and rhlota.r70_instit         = rhpessoalmov.rh02_instit  
                                  inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
                                  left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                                  ".bb_condicaosubpes("r52_" ).$condicaoaux ;
     //echo "<BR> ".count($pensao)." $sql";exit;
     db_selectmax("pensao", $sql);
     for ($Ipensao=0; $Ipensao<count($pensao); $Ipensao++) {
         $matriz1 = array();
         $matriz2 = array();
         $condicaoaux  = " and r52_regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"]);
         $condicaoaux .= " and r52_numcgm = ".db_sqlformat($pensao[$Ipensao]["r52_numcgm"]);
         
         $matriz1[1] = "r52_valres";
         $matriz2[1] = 0;
         //echo "<BR> ".bb_condicaosubpes("r52_").$condicaoaux ;
         $retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
     }
  }
  db_fim_transacao();
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
    <? 
    include("forms/db_frmexcrhpesrescis.php");
    ?>
    </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  db_msgbox($erro_msg);
  if($erro == false){
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
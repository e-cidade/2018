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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("classes/db_cadferia_classe.php");
include("classes/db_selecao_classe.php");
include("classes/db_cfpess_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcadferia = new cl_cadferia;
$clselecao = new cl_selecao;
$clcfpess = new cl_cfpess;
$db_opcao = 1;
$db_botao = true;
$exclusao_ferias = true;
if(isset($excluir)){

  $erro_msg = "";
  $anofolha = db_anofolha();
  $mesfolha = db_mesfolha();
  $subpes = $anofolha."/".$mesfolha;
  $subant = $subpes;

  $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($r44_selec, db_getsession('DB_instit'), "r44_where"));
  
  if($clselecao->numrows > 0) {
    db_fieldsmemory($result_selecao, 0);
    $sql_cadferia = $clcadferia->sql_query_file(null, " r30_regist ", "", " r30_anousu = " . $anofolha . " and r30_mesusu = " . $mesfolha . " and (r30_proc1 >= '" . $subpes . "' or r30_proc2 >= '" . $subpes . "')");

    $r44_where .= " and rh05_seqpes is null";
    $r44_where .= " and rh01_regist in (" . $sql_cadferia . ") ";
    
    include("libs/db_sql.php");
    $clsql = new cl_gera_sql_folha;
    $clsql->usar_pes = true;
    $clsql->usar_pad = true;
    $clsql->usar_cgm = true;
    $clsql->usar_fun = true;
    $clsql->usar_lot = true;
    $clsql->usar_exe = true;
    $clsql->usar_org = true;
    $clsql->usar_atv = true;
    $clsql->usar_res = true;
    $clsql->usar_fgt = true;
    $clsql->usar_cad = true;
    $clsql->usar_tra = true;
    $clsql->usar_car = true;
    $clsql->usar_afa = true;
    $campomatriculas = "";
    $virgumatriculas = "";
    $sql = $clsql->gerador_sql("", $anofolha, $mesfolha, null, null, "distinct rh01_regist as r30_regist, z01_numcgm", "rh01_regist", $r44_where);
    $result = $clsql->sql_record($sql);
    db_inicio_transacao();
    for($i=0; $i<$clsql->numrows_exec; $i++){
      db_fieldsmemory($result, $i);

      db_selectmax("cfpess", "select * from cfpess ".bb_condicaosubpes("r11_"));

      $condicaoaux  = " and r01_regist = ".db_sqlformat($r30_regist);
      
      $lExisteDadosPessoal = db_selectmax("pessoal", "select * from pessoal ".bb_condicaosubpes("r01_").$condicaoaux);
      
      $condicaoaux  = " and r30_regist = ".db_sqlformat($r30_regist);
      $condicaoaux .= " and (r30_proc1 >= '" . $subpes . "' or r30_proc2 >= '" . $subpes . "') ";
      db_selectmax("cadferia", "select * from cadferia ".bb_condicaosubpes("r30_").$condicaoaux." order by r30_perai desc");
      $periodo_aquisitivo = $cadferia[0]["r30_perai"];
      if($cadferia[0]["r30_proc2"] >= $subpes){

        $subpes = $cadferia[0]["r30_proc2"];
        $matriz1 = array();
        $matriz2 = array();
        $matriz1[1] = "r30_per2i";
        $matriz1[2] = "r30_per2f";
        $matriz1[3] = "r30_proc2";
        $matriz1[4] = "r30_dias2";
        $matriz1[5] = "r30_psal2";
        $matriz1[6] = "r30_abono";

        $matriz2[1] = db_nulldata("");
        $matriz2[2] = db_nulldata("");
        $matriz2[3] = bb_space(7);
        $matriz2[4] = 0;
        $matriz2[5] = "f";
        if($cadferia[0]["r30_tip2"] == "10"){
          $matriz2[6] = 0;
        }else{
          $matriz2[6] = $cadferia[0]["r30_abono"];
        }
        db_update("cadferia", $matriz1, $matriz2, bb_condicaosubpes("r30_").$condicaoaux);
      }else if($cadferia[0]["r30_proc1"] >= $subpes) {
        db_delete("cadferia", bb_condicaosubpes("r30_").$condicaoaux);
      }
      
      $condicaoaux  = " where r40_regist = ".db_sqlformat($r30_regist);
      $condicaoaux .= " and r40_proc = ".db_sqlformat($subpes);
      db_delete("fgtsfer", $condicaoaux);

      $condicaoaux = " and r29_regist = ".db_sqlformat($r30_regist);
      db_delete("pontofe", bb_condicaosubpes("r29_").$condicaoaux);
 
      $subpes = $subant;
      $condicaoaux = " and r31_regist = ".db_sqlformat($r30_regist);
      db_delete("gerffer", bb_condicaosubpes("r31_").$condicaoaux);

      $erro_msg = "Usuário, alguns procedimentos não podem ser feitos automaticamente \\n
                   pelo sistema, portanto proceda da seguinte maneira:\\n\\n
                   - Reinicialize o ponto de salário para funcionários desta seleção.";
      if(strtolower($cadferia[0]["r30_ponto"]) == "c"){
        $condicaoaux = " and r47_regist = ".db_sqlformat($r30_regist);
        db_delete("pontocom", bb_condicaosubpes("r47_").$condicaoaux);

        $condicaoaux = " and r48_regist = ".db_sqlformat($r30_regist);
        db_delete("gerfcom", bb_condicaosubpes("r48_").$condicaoaux);

      }else{
        $condicaoaux = " and r10_regist = ".db_sqlformat($r30_regist);
        if(db_selectmax("pontofs", "select * from pontofs ".bb_condicaosubpes("r10_").$condicaoaux)){
          $condicaoaux .= " and r10_rubric = ".db_sqlformat($cfpess[0]["r11_ferias"]);
          $condicaoaux .= " and r10_rubric = ".db_sqlformat($cfpess[0]["r11_fer13o"]);
          db_delete("pontofs", bb_condicaosubpes("r10_").$condicaoaux);
        }

        $condicaoaux = " and r14_regist = ".db_sqlformat($r30_regist);
        db_delete("gerfsal", bb_condicaosubpes("r14_").$condicaoaux);
      }
      
      $erro_msg.= "\\n- Recalcule folha de salário.";
      $erro_msg.= "\\n- Lance e recalcule folha complementar.";

      if ($lExisteDadosPessoal) {
        
        $condicaoaux  = " and r60_numcgm = ".$pessoal[0]["r01_numcgm"];
        $condicaoaux .= " and r60_tbprev = ".$pessoal[0]["r01_tbprev"];
        $condicaoaux .= " and r60_rubric = ".db_sqlformat("R977");
        $condicaoaux .= " and r60_regist = ".$pessoal[0]["r01_regist"];
        
        if(db_selectmax("previden", "select * from previden ".bb_condicaosubpes("r60_").$condicaoaux)){
          db_delete("previden", bb_condicaosubpes("r60_").$condicaoaux);
        }
      }
      
      $sWhere  = " where rh93_mesusu = {$mesfolha}  ";
      $sWhere .= "   and rh93_anousu = {$anofolha} ";    
      $sWhere .= "   and rh93_regist = {$r30_regist} ";
      db_delete('rhcadastroferiaslote', $sWhere);
    }
    
    db_fim_transacao();
  }

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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmexcferialote.php");
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
  db_msgbox("Processamento concluído.");
  if(trim($erro_msg) != ""){
    db_msgbox($erro_msg);
  }
  echo "<script>location.href = 'pes4_cadferialote003.php';</script>";
}
?>
<script>
js_tabulacaoforms("form1","r44_selec",true,1,"r44_selec",true);
</script>
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
include("classes/db_pontofs_classe.php");
include("classes/db_rhvisavale_classe.php");
include("classes/db_rhvisavalecad_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clpontofs = new cl_pontofs;
$clrhvisavale = new cl_rhvisavale;
$clrhvisavalecad = new cl_rhvisavalecad;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){

  $sqlerro = false;

  db_inicio_transacao();

  if(is_null($rh47_rubric) or trim($rh47_rubric)=="") {
    $sqlerro = true;
    $erromsg = "Rubrica não definida para Lançar Vale Alimentação no Ponto!";

    $clpontofs->erro_status = "0";
    $clpontofs->erro_msg    = $erromsg;
  } 
 
  if($sqlerro == false) {
    $clpontofs->excluir($rh49_anousu,$rh49_mesusu,null,$rh47_rubric);
    if($clpontofs->erro_status=="0") {
      $sqlerro = true;
      $erromsg = $clpontofs->erro_msg;
    }
  }

  if($sqlerro == false){
    $result_cad = $clrhvisavalecad->sql_record($clrhvisavalecad->sql_query(null,"(rh49_valormes * (rh49_perc/100)) as valor,rh01_regist,rh02_lota","rh49_codigo","rh49_anousu = $rh49_anousu and rh49_mesusu = $rh49_mesusu and rh49_valormes > 0 and rh49_instit = ".db_getsession("DB_instit")));
    
    if($clrhvisavalecad->erro_status=="0") {
      $sqlerro = true;
      $erromsg = $clrhvisavalecad->erro_msg;
    }

    if($sqlerro == false) {
      $numrows_cad = $clrhvisavalecad->numrows;
      $contador = 0;
      for($i=0; $i<$numrows_cad; $i++){
        db_fieldsmemory($result_cad, $i);
        $contador ++;
        
        $clpontofs->r10_anousu = $rh49_anousu;
        $clpontofs->r10_mesusu = $rh49_mesusu;
        $clpontofs->r10_regist = $rh01_regist;
        $clpontofs->r10_rubric = $rh47_rubric;
        $clpontofs->r10_valor  = "round(".$valor.",2)";
        $clpontofs->r10_quant  = "0";
        $clpontofs->r10_lotac  = $rh02_lota;
        $clpontofs->r10_datlim = "";
        $clpontofs->r10_instit = db_getsession("DB_instit");
        $clpontofs->incluir($rh49_anousu,$rh49_mesusu,$rh01_regist,$rh47_rubric);
        if($clpontofs->erro_status == "0"){
          $sqlerro = true;
          $erromsg = $clpontofs->erro_msg;
          break;
        }
      }
    }
  }

  db_fim_transacao($sqlerro);

}else{
  $result_dados = $clrhvisavale->sql_record($clrhvisavale->sql_query(db_getsession("DB_instit"),"rh47_rubric,rh27_descr,rh47_perc"));
  if($clrhvisavale->numrows > 0){
    db_fieldsmemory($result_dados, 0);
  } else {
    $rh47_rubric = null;
    $rh27_descr  = null;
    $rh47_perc   = null;
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
    include("forms/db_frmrhvisavalevalorlancaponto.php");
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
if(isset($incluir)){
  if($clpontofs->erro_status=="0"){
    db_msgbox($erromsg);
  }else{
    db_msgbox($contador." matrículas lançadas com sucesso.");
    echo "<script>location.href='".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."'</script>";
  }
}
?>
<script>
js_tabulacaoforms("form1","incluir",true,1,"incluir",true);
</script>
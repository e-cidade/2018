<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpessoalmov_classe.php");
include("classes/db_padroes_classe.php");
include("classes/db_pesdiver_classe.php");
include("dbforms/db_classesgenericas.php");

ini_set('error_reporting', E_ALL);

$clrhpessoal = new cl_rhpessoal;
$clrhpessoalmov = new cl_rhpessoalmov;
$clpadroes = new cl_padroes;
$clpesdiver = new cl_pesdiver;
$aux = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo;
db_postmemory($HTTP_POST_VARS);
if(isset($incluir)){
  db_inicio_transacao();
  $sqlerro = false;
  $dbwhere = " r02_anousu = ".$anofolha." and r02_mesusu = ".$mesfolha." and r02_instit = ".db_getsession("DB_instit")." " ;
  if(isset($matini) || isset($matfim)){
    if(trim($matini) != "" && trim($matfim) != ""){
      $dbwhere.= " and rh02_regist between ".$matini." and ".$matfim; 
    }else if(trim($matini) != ""){
      $dbwhere.= " and rh02_regist >= ".$matini; 
    }else if(trim($matfim) != ""){
      $dbwhere.= " and rh02_regist <= ".$matfim; 
    }
  }
  if(isset($selmatri) && count($selmatri) > 0){
    $campo_auxilio_regi = "";
    for($i=0; $i<count($selmatri); $i++){
      $campo_auxilio_regi.= ($i==0?"":",").$selmatri[$i];
    }
    if(isset($campo_auxilio_regi) && trim($campo_auxilio_regi) != ""){
      $dbwhere.= " and rh02_regist in (".$campo_auxilio_regi.") ";
    }
  }
  if(isset($lotini) || isset($lotfim)){
    if(trim($lotini) != "" && trim($lotfim) != ""){
      $dbwhere.= " and r70_estrut between '".$lotini."' and '".$lotfim."' "; 
    }else if(trim($lotini) != ""){
      $dbwhere.= " and r70_estrut >= '".$lotini."' ";
    }else if(trim($lotfim) != ""){
      $dbwhere.= " and r70_estrut <= '".$lotfim."' ";
    }
  }
  if(isset($sellotac) && count($sellotac) > 0){
    $campo_auxilio_lota = "";
    for($i=0; $i<count($sellotac); $i++){
      $campo_auxilio_lota.= ($i==0?"":",")."'".$sellotac[$i]."'";
    }
    if(isset($campo_auxilio_lota) && trim($campo_auxilio_lota) != ""){
      $dbwhere.= " and r70_estrut in (".$campo_auxilio_lota.") ";
    }
  }
  $contador = 0;
//echo "<br><br>". ($clpadroes->sql_query_cgmmovpad(null,null,null,null," distinct r02_codigo as pad, r02_regime as reg, r02_form, r02_valor","",$dbwhere));
  $result_padrao = $clpadroes->sql_record($clpadroes->sql_query_cgmmovpad(null,null,null,null," distinct r02_codigo as pad, r02_regime as reg, r02_form, r02_valor","",$dbwhere));
  $numrows_padrao = $clpadroes->numrows;
  if($numrows_padrao == 0){
    $erro_msg = "Nenhum registro encontrado.";
    $sqlerro = true;
  }else{
    for($i=0;$i<$numrows_padrao;$i++){
      db_fieldsmemory($result_padrao, $i);
      $alterar_padrao = false;
      if($lancar == "p" ){
        $r02_valor += ($r02_valor * ($rh02_salari / 100));
        $valorpadrao = "round($r02_valor, 2)";
        $alterar_padrao = true;
      }else if($lancar == "f" && trim($r02_form) != ""){
        $formpesdiver = explode("D",$r02_form);
        for($ii=1; $ii<count($formpesdiver); $ii++){
          $coddiver = "D".substr($formpesdiver[$ii],0,3);
          $result_diverso = $clpesdiver->sql_record($clpesdiver->sql_query_file(db_anofolha(),db_mesfolha(),$coddiver,db_getsession("DB_instit"),"r07_valor"));
          if($clpesdiver->numrows > 0){
            db_fieldsmemory($result_diverso,0);
            $r02_form = str_replace($coddiver, $r07_valor, $r02_form);
          }
        }
        ob_start();
        eval('$valorpadrao1 = round(('.$r02_form.'),2);');

        $saida = ob_get_contents();
        ob_end_clean();
        if(strpos($saida, "Parse error")>0) {
          $sqlerro = true;

           $erro_msg = "Erro na Formula : round((".$r02_form."),2) \\n\\n Padrao : ".$pad." \\n\\n Regime : ".$reg." \\n\\n Contate o Suporte !!";
//           db_msgbox("Erro na Formula : round((".$r02_form."),2) \\n\\n Padrao : ".$pad." \\n\\n Regime : ".$reg." \\n\\n Contate o Suporte !!");
           break;
        }else{
           $valorpadrao = "round((".$r02_form."),2)";
           $alterar_padrao = true;
        }
      }
      if($alterar_padrao == true){
        $clpadroes->r02_anousu = $anofolha;
        $clpadroes->r02_mesusu = $mesfolha;
        $clpadroes->r02_regime = $reg;
        $clpadroes->r02_codigo = $pad;
        $clpadroes->r02_valor  = $valorpadrao;
        //echo "<BR> valorpadrao --> $valorpadrao";
        $clpadroes->r02_instit = db_getsession('DB_instit');
        $clpadroes->alterar($anofolha, $mesfolha, $reg, $pad,db_getsession('DB_instit'));
        $erro_msg = $clpadroes->erro_msg;
        if($clpadroes->erro_status == "0"){
          $sqlerro = true;
        }
      }
    }
  }
  db_fim_transacao($sqlerro);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <tr>
    <td>
      <?
      include("forms/db_frmreajustepad.php");
      ?>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_setfocus(true);
</script>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
}
?>
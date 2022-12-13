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
include("dbforms/db_funcoes.php");
include("classes/db_arrecant_classe.php");
include("classes/db_arrehist_classe.php");
include("classes/db_cancdebitos_classe.php");
include("classes/db_cancdebitosreg_classe.php");
include("classes/db_cancdebitosproc_classe.php");
include("classes/db_cancdebitosprocreg_classe.php");
db_postmemory($HTTP_POST_VARS);
$clarrecant           = new cl_arrecant;
$clarrehist           = new cl_arrehist;
$clcancdebitos        = new cl_cancdebitos;
$clcancdebitosreg     = new cl_cancdebitosreg;
$clcancdebitosproc    = new cl_cancdebitosproc;
$clcancdebitosprocreg = new cl_cancdebitosprocreg;
$clarrecant->rotulo->label("k00_numpre");
$clarrecant->rotulo->label("k00_numpar");
$clarrecant->rotulo->label("k00_valor");
$instit = db_getsession("DB_instit");
$db_opcao = 3; 
if(isset($processar)){
  $sqlerro = false;
  $reg = str_replace("|",",",$linha);
  $clarrehist->k00_hist       = 502;
  $clarrehist->k00_dtoper     = date("Y-m-d",db_getsession("DB_datausu"));
  $clarrehist->k00_hora       = date("H:i");
  $clarrehist->k00_id_usuario = db_getsession("DB_id_usuario");
  $clarrehist->k00_histtxt    = $k23_obs;
  $result_reg = $clcancdebitos->sql_record($clcancdebitos->sql_query("","k21_sequencia,k21_codigo,k21_numpre,k21_numpar,k21_receit","","k21_sequencia in($reg) and k20_instit = $instit"));
  for($x=0;$x<$clcancdebitos->numrows;$x++){
    db_fieldsmemory($result_reg,$x); 
    $clarrehist->k00_numpre     = $k21_numpre;
    $clarrehist->k00_numpar     = $k21_numpar;
    $clarrehist->incluir(null);
    if($clarrehist->erro_status=="0"){
      $sqlerro = true;
    }
    $clarrecant->excluir_arrecant($k21_numpre,$k21_numpar,$k21_receit,true);
    $clcancdebitosprocreg->excluir("","k24_cancdebitosreg = ".$k21_sequencia);
    $clcancdebitosreg->excluir($k21_sequencia);
    if($clcancdebitosreg->erro_status=="0"){
      $sqlerro = true;
    }
  }
  $clcancdebitosproc->excluir($k21_codigo);
  $clcancdebitos->excluir($k21_codigo);
  if($sql_erro == false){
    db_msgbox("Operação Efetuada!");
  }else{
    db_msgbox("Operação Não Efetuada!");
  }
}

if($numpre != ""){
  $sql  = " select distinct ";
  $sql .= "        k21_sequencia, ";
  $sql .= "        k21_numpre, ";
  $sql .= "        k21_numpar, ";
  $sql .= "        k21_receit, ";
  $sql .= "        case when k24_vlrhis <> 0 then k24_vlrhis ";
  $sql .= "          else k00_valor ";
  $sql .= "        end as k00_valor ";
  $sql .= "   from cancdebitosreg ";
  $sql .= "        left join cancdebitosproc    on k21_sequencia = k23_codigo ";
  $sql .= "        left join cancdebitosprocreg on k23_codigo = k24_codigo ";
  $sql .= "        inner join arrecad           on k21_numpre = k00_numpre ";
  $sql .= "                                    and k21_numpar = k00_numpar ";
  $sql .= " where k21_numpre = $numpre";
  $result = $clcancdebitosreg->sql_record($sql);
  if($clcancdebitosreg->numrows > 0){
    @db_fieldsmemory($result,0);
    $db_botao = true;
    $db_opcao = 1;
  }else{
    db_msgbox("Numpre não encontrado");
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form_reg">
<table border='1' cellpadding="0" cellspacing='0' width="100%">
<tr><td colspan="5">Registros</td></tr>
<tr>
  <td><input type="button" name="marca" onclick="js_marcar(<?=$clcancdebitosreg->numrows?>,this)" value="M">
  <td align='center'><?=$Lk00_numpre?></td>
  <td align='center'><?=$Lk00_numpar?></td>
  <td align='center'><?=$Lk00_valor?></td>
</tr>
<?for($x = 0; $x < $clcancdebitosreg->numrows; $x++) {
    db_fieldsmemory($result,$x);
    echo "<tr><td><input type='checkbox' name='id' value='$k21_sequencia'></td><td>$k21_numpre</td><td>$k21_numpar</td><td>$k00_valor</td></tr>";
  }?>
<tr>
  <td colspan="4"><strong>Observações:</strong></td>
</tr>
<tr>
  <td colspan="4"><? db_textarea('k23_obs',2,50,$Ik23_obs,true,'text',$db_opcao,"","","#FFFFFF; text-transform:uppercase")?></td>
</tr>
</table>
</form>
<input name="processa" type="button" id="db_opcao" value="Processar" onclick="js_processar(<?=$clcancdebitosreg->numrows?>,this)" <?=($db_botao==false?"disabled":"")?> >
<script>
function js_processar(total,documento){
  linha = "";
  sep   = "";
  for(i=1;i <= total;i++){
    if(document.form_reg[i].checked == true){
      linha += sep+document.form_reg[i].value;
      sep = "|";
    }
  }
  if(linha == ""){
    alert("Informe as parcelas");
  }else{
    location.href = "cai4_retcancdebitos002.php?numpre=<?=$numpre?>&linha="+linha+"&k23_obs="+document.form_reg.k23_obs.value+"&processar";
  }
}
function js_marcar(tudo,documento){
  for(i=1;i<=tudo;i++){
    if(documento.value=="D"){
      document.form_reg[i].checked=false;
    }
    if(documento.value=="M"){
      document.form_reg[i].checked=true;
    }
  }
  if(document.form_reg.marca.value == "D"){
    document.form_reg.marca.value="M";
  } else {
    document.form_reg.marca.value="D";
  }
}
</script>
</body>
</html>
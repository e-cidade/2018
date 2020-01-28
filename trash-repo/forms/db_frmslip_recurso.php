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

require ("../libs/db_stdlib.php");
require ("../libs/db_conecta.php");
include ("../libs/db_sessoes.php");
include ("../libs/db_usuariosonline.php");
include ("../dbforms/db_funcoes.php");
include ("../classes/db_sliprecurso_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clsliprecurso = new cl_sliprecurso;
if(isset($valores)){
  $arr_valores = split(",", $valores);
  $sqlerro = false;
  db_inicio_transacao();
  $clsliprecurso->excluir(null, " k29_slip = $numslip ");
  if($clsliprecurso->erro_status == 0){
    $sqlerro = true;
    $erro_msg = $clsliprecurso->erro_msg;
  }
  if($sqlerro == false){
    for($i=0; $i<count($arr_valores); $i++){
      $recurso = $arr_valores[$i];
      $valor = "val_" . $recurso;
      $clsliprecurso->k29_slip = $numslip;
      $clsliprecurso->k29_recurso = $recurso;
      $clsliprecurso->k29_valor = $$valor;
      $clsliprecurso->incluir(null);
      $erro_msg = $clsliprecurso->erro_msg;
      if($clsliprecurso->erro_status == 0){
        $sqlerro = true;
      }
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
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<style>
.table_header{
  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #999999;
  font-size: 10px;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post">
<table border=0 width="100%" style="border:1px solid #7C7C7C" id="tabrecursos">
  <tr id="cabecalho">
    <td colspan=2><b>RECURSO</b></td>
    <td width=10% align=center><b>VALOR</b></td>
    <td width=10% align=center></td>
  </tr>
  <?
  if(isset($numslip) && trim($numslip) != ""){
    $res = $clsliprecurso->sql_record($clsliprecurso->sql_query(null,"o15_codigo as k29_recurso, o15_descr, k29_valor, k17_valor as valor_slip", "o15_codigo", " k29_slip = $numslip"));
    if($clsliprecurso->numrows > 0){
      for($i=0; $i<$clsliprecurso->numrows; $i++){
        db_fieldsmemory($res, $i);
        ?>
        <tr id="rec_<?=$k29_recurso?>">
          <td><?=$k29_recurso?></td>
          <td><?=$o15_descr?></td>
          <td align=right>
          <?
          $campovalor = "val_".$k29_recurso;
          $$campovalor = $k29_valor;
          db_input($campovalor, 8, 0, true, "text", ($k29_recurso == 1 ? 3 : 1), "onchange='js_AtualizaDeletaRow(null, \"val_$k29_recurso\", false)'");
          ?>
          </td>
          <td>
            <input type='button' value='E' onclick='js_AtualizaDeletaRow("rec_<?=$k29_recurso?>", "val_<?=$k29_recurso?>", true)' <?=($k29_recurso == 1 ? "disabled" : "")?>>
          </td>
        </tr>
        <?
      }
      db_input("numslip", 8, 0, true, "hidden", 3);
      db_input("valor_slip", 8, 0, true, "hidden", 3);
    }else{
    ?>
    <tr>
      <td id="semrecursos" colspan=2>Sem Recursos Lançados</td>
    </tr>
    <?
    }
  }
  ?>
</table>
</form>
</body>
</html>
<script>
function js_somatorioValores(){
  var form = document.form1;
  var valor = 0;
  for(i=1;i<form.length;i++){
    if(form.elements[i].type == "text" && form.elements[i].name != "val_1" && form.elements[i].name != "numslip" && form.elements[i].name != "valor_slip"){
      valorc = new Number(form.elements[i].value);
      valor += valorc;
    }
  }

  return valor;
}

function js_AtualizaDeletaRow(campoID, valorID, deleta){
  campo = document.getElementById(valorID);
  valort = new Number(document.getElementById("valor_slip").value);
  valorc = new Number(campo.value);

  if(deleta == true){
    var tab = document.getElementById("tabrecursos");
    for(i=1;i<tab.rows.length;i++){
      if(tab.rows[i].id == campoID){
        document.getElementById("tabrecursos").deleteRow(i);
        break;
      }
    }
  }

  valorsomatorio = js_somatorioValores();
  valors = new Number(valort - valorsomatorio);

  if(valors < 0){
    valors += valorc;
    alert("Valor informado não disponível! Verifique.");
    if(deleta == true){
      campo.value = "";
      campo.focus();
    }
  }

  document.getElementById("val_1").value = valors;
}

function js_selecionaRecursos(){
  var  tab = document.getElementById("tabrecursos");
  recursos = "";
  virgula  = "";
  for(i=1;i<tab.rows.length;i++){
    if(tab.rows[i].id.substr(0,4) == "rec_"){
      arrRec = tab.rows[i].id.split("_");
      recursos += virgula + arrRec[1];
      virgula   = ",";
    }
  }

  return recursos;
}

function js_submita(){
  form = document.form1;
  valores = "";
  virgula = "";
  for(i=0; i<form.length; i++){
    if(form.elements[i].type == "text" && form.elements[i].name != "numslip" && form.elements[i].name != "valor_slip"){
      arrValores = form.elements[i].name.split("_");
      valores += virgula + arrValores[1];
      virgula  = ",";
    }
  }

  obj=document.createElement('input');
  obj.setAttribute('name','valores');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value',valores);
  document.form1.appendChild(obj);

  document.form1.submit();
}
</script>
<?
if(isset($valores)){
  db_msgbox($erro_msg);
}
?>
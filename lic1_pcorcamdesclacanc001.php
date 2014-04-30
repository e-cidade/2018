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
include("classes/db_pcorcamdescla_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clpcorcamdescla = new cl_pcorcamdescla;
$db_botao        = false;

if (isset($coditens) && trim($coditens) != ""){
     $sqlerro     = false;
     $erro_msg    = "";

     db_inicio_transacao();

     $vetor_itens = split(",",$coditens);
     for($i = 0; $i < count($vetor_itens); $i++){
          $clpcorcamdescla->excluir($vetor_itens[$i]);  

          if ($clpcorcamdescla->erro_status == "0"){
               $sqlerro = true;
               break;
          }
     }

     $erro_msg = $clpcorcamdescla->erro_msg;
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
<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="">
<center>
  <br><br>
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td nowrap align="center" valign="top">
  <fieldset><Legend align="center"><b>&nbsp;&nbsp;Itens da Licitacao N<? echo chr(176)." ".$l20_codigo; ?>&nbsp;&nbsp;</b></Legend>
    <table  border="0" width="700">
         <tr class="bordas" nowrap>
           <td class="bordas" nowrap align="center"><a href="#" onClick="js_marcar_lote(0,'TODOS');">M</a></td>
           <td class="bordas" nowrap align="center"><b>Item</b></td>
           <td class="bordas" nowrap><b>Descricao do Material</b></td>
           <td class="bordas" nowrap><b>Lote</b></td>
         </tr>
<?
   $campos         = "distinct pc32_orcamitem,pc01_descrmater,l04_descricao";
   $sql            = $clpcorcamdescla->sql_query_descla_lote(null,null,$campos,"l04_descricao,pc32_orcamitem","orc.pc20_codorc = $pc20_codorc");
   $res_descla     = $clpcorcamdescla->sql_record($sql);
   $numrows_descla = $clpcorcamdescla->numrows;

   for($i = 0; $i < $numrows_descla; $i++){
        db_fieldsmemory($res_descla,$i);
?>
         <tr class="bordas_corp" nowrap>
           <td class="bordas_corp" nowrap align="center">
              <input name="chk_<?=$i?>" type="checkbox" value="<? echo $pc32_orcamitem."_".str_replace("_","",$l04_descricao); ?>" onClick="js_marcar_lote(<? echo $pc32_orcamitem.",'".str_replace("_","",$l04_descricao)."'"; ?>);">
           </td>
           <td class="bordas_corp" nowrap align="center"><?=$pc32_orcamitem?></td>
           <td class="bordas_corp" nowrap><?=$pc01_descrmater?></td>
           <td class="bordas_corp" nowrap><?=$l04_descricao?></td>
         </tr>
<?
   }

   if ($numrows_descla == 0){
        $db_botao = true;
   }
   db_input("coditens",500,"",true,"hidden",3);
?>
         </table>
       </fieldset>
         </td>
        </tr> 
        <tr>
           <td nowrap align="center" height="50"><input name="confirmar" type="submit" value="Confirmar" onClick="return js_confirmar();" <?=($db_botao==true?"disabled":"")?>>
           &nbsp;&nbsp;<input name="fechar"    type="button" value="Fechar" onClick="js_fechar();"></td>
        </tr>
       </table>
      </form>
<?
   if (trim(@$erro_msg) != ""){
        db_msgbox($erro_msg);
   }
?>
</center>
<script>
function js_confirmar(){
  var tam  = document.form1.elements.length; 
  var erro = false;

  for(i = 0; i < tam; i++){
       if (document.form1.elements[i].type == "checkbox"){
            if (document.form1.elements[i].checked == true){
                 erro = true;
                 break;
            }
       }
  }

  if (erro == false){
       alert("Selecione um item");
  }

  return erro;
}
function js_fechar(){
  parent.db_iframe_cancdescla.hide();
  if (document.form1.coditens.value.length > 0){
       parent.document.form1.submit();
  }
}
function js_marcar_lote(orcamitem,lote){
  var tam         = document.form1.elements.length; 
  var lista_itens = "";
  var virgula     = "";

  if (orcamitem > 0){
       for(i = 0; i < tam; i++){
            if (document.form1.elements[i].type == "checkbox"){
                 var str_lote   = new String(document.form1.elements[i].value);
                 var vetor_lote = str_lote.split("_");
                 if (lote == vetor_lote[1]){
                      document.form1.elements[i].checked = true;
                      lista_itens += virgula+vetor_lote[0];
                      virgula      = ", ";
                 }
            }
       }
  } else {
       for(i = 0; i < tam; i++){
            if (document.form1.elements[i].type == "checkbox"){
                 var str_lote   = new String(document.form1.elements[i].value);
                 var vetor_lote = str_lote.split("_");
                 if (orcamitem == 0){
                      if (document.form1.elements[i].checked == true){
                           document.form1.elements[i].checked = false;
                      } else {
                           document.form1.elements[i].checked = true;
                           lista_itens += virgula+vetor_lote[0];
                           virgula      = ", ";
                      }
                 }
            }
       } 
  }

  if (document.form1.coditens.value.length > 0 && orcamitem > 0){
       document.form1.coditens.value += virgula+lista_itens;
  } else {   
       document.form1.coditens.value = lista_itens;
  }
}
</script>
</body>
</html>
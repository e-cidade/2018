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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_liclicitemlote_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clliclicitemlote        = new cl_liclicitemlote;
$cliframe_seleciona_lote = new cl_iframe_seleciona;

$clliclicitemlote->rotulo->label();

$erro_msg = "";

if (isset($excluir)&&trim($excluir)!=""){
     $vetor_lotes = split(",",$l04_descricao);
     $sqlerro     = false;

     db_inicio_transacao();

     for($i = 0; $i < sizeof($vetor_lotes); $i++){
          $clliclicitemlote->excluir(null,"l04_descricao = '".trim($vetor_lotes[$i])."'");

          if ($clliclicitemlote->erro_status == 0){
               $erro_msg = $clliclicitemlote->erro_msg;
               $sqlerro  = true;
               break;
          }
     }

     if ($sqlerro == false){
          $erro_msg   = $clliclicitemlote->erro_msg;
     }

     db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<?
if (isset($licitacao)&&trim($licitacao)!=""){
   $sql_marca     = "";
   $campos        = "l04_descricao";
   $sql           = $clliclicitemlote->sql_query_licitacao(null,"distinct $campos","l04_descricao","l21_codliclicita = $licitacao and l04_codigo is not null");

   $res_itenslote = $clliclicitemlote->sql_record($sql);
   $numrows       = $clliclicitemlote->numrows;

   $cliframe_seleciona_lote->campos    = $campos;
   $cliframe_seleciona_lote->legenda   = "";
   $cliframe_seleciona_lote->sql       = $sql;
   $cliframe_seleciona_lote->sql_marca = $sql_marca;
   $cliframe_seleciona_lote->iframe_height = "300";
   $cliframe_seleciona_lote->iframe_width  = "600";
   $cliframe_seleciona_lote->iframe_nome   = "excluir_lote";
   $cliframe_seleciona_lote->chaves        = "l04_descricao";
   $cliframe_seleciona_lote->js_marcador   = "";
   $cliframe_seleciona_lote->dbscript      = "";
   $cliframe_seleciona_lote->iframe_seleciona(1);
?>    
<form name="form2" method="post" action="lic1_liclicitemlote003.php">
<table border="0" cellspacing="2" cellpadding="0" align="center">
<tr><td colspan="2">&nbsp;</td></tr>
<?
   db_input("licitacao", 10,"",true,"hidden",3);
   db_input("l04_descricao",500,"",true,"hidden",3);
?>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
  <td nowrap align="center" colspan="2">
    <input type="submit" name="excluir" value="Excluir" onClick="return js_confirma_dados();">
    <input type="button" name="fechar"  value="Fechar" onClick="js_fechar();"></td>
</tr>
</table>
<script>
   function js_confirma_dados(){
       var contador    = 0;
       var lista_itens = "";
       var separador   = ""; 
       var tam         = excluir_lote.document.form1.elements.length;

       for(i = 0; i < tam; i++){
            if (excluir_lote.document.form1.elements[i].type == "checkbox"){
                 if (excluir_lote.document.form1.elements[i].checked == true ){
                      lista_itens += separador+excluir_lote.document.form1.elements[i].value;   
                      separador    = ", ";
                      contador++;
                 }
            }
       }

       if (contador == 0){
            alert("Selecione um item");
            return false;
       } else {
            document.form2.l04_descricao.value = lista_itens;
       }
  
       if (confirm("Confirma exclusão dos lotes "+lista_itens+" ?") == true){
            document.form2.submit();
            return true;
       } else {
            return false;
       }
   }

   function js_fechar(){
       parent.db_iframe_loteexcluir.hide();     
       parent.itens_lote.location.href = 'lic1_liclicitemlote011.php?licitacao=<?=$licitacao?>';
   }
</script>
</center>
</form>
</body>
</html>
<?
}

if ($numrows == 0&&trim(@$excluir)==""){
      $erro_msg = "Nenhum lote cadastrado ou foram excluídos desta licitação.";
      echo "<script>
               document.form2.excluir.disabled = true;
            </script>";          
}

if ($numrows == 0&&trim(@$excluir)!=""){
      $erro_msg = "Todos os lotes desta licitação foram excluídos.";
      echo "<script>
               document.form2.excluir.disabled = true;
            </script>";          
}

if (isset($erro_msg)&&trim($erro_msg)!="") {
     db_msgbox($erro_msg);
}
?>
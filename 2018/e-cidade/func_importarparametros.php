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
include("dbforms/db_classesgenericas.php");
include("classes/db_orcparamelemento_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clorcparamelemento          = new cl_orcparamelemento;
$cliframe_importarparametros = new cl_iframe_seleciona;

$erro_msg = "";    
$sqlerro  = false;
?>
<script>
function js_retornar(){
  document.location.href="con2_conrelparametros.php?c83_codrel=<?=$o69_codparamrel?>";
}
</script>
<?

if (isset($codinst) && trim(@$codinst)!=""){
     db_inicio_transacao();

     $result  = $clorcparamelemento->sql_record($clorcparamelemento->sql_query_file(null,null,null,null,null,"o44_codele as codele",null,"o44_anousu = ".db_getsession("DB_anousu")." and o44_codparrel= $o69_codparamrel and o44_sequencia = $o69_codseq and o44_instit in ($codinst)"));     
     $numrows = $clorcparamelemento->numrows;
     if ($clorcparamelemento->numrows > 0){
          for($i = 0; $i < $numrows; $i++){
               db_fieldsmemory($result,$i);
               $clorcparamelemento->o44_codparrel = $o69_codparamrel;
               $clorcparamelemento->o44_sequencia = $o69_codseq;
               $clorcparamelemento->o44_codele    = $codele;
               $clorcparamelemento->o44_anousu    = db_getsession("DB_anousu");
               $clorcparamelemento->o44_instit    = $instituicao;
               $clorcparamelemento->o44_exclusao  = "false";

               $clorcparamelemento->incluir(db_getsession("DB_anousu"),$o69_codparamrel,$o69_codseq,$codele,$instituicao);
               $erro_msg = $clorcparamelemento->erro_msg;
               if ($clorcparamelemento->erro_status == "0"){
                    $sqlerro = true;
                    break;
               }
          }
     }
     
     db_fim_transacao($sqlerro);

     if ($sqlerro == false){
          db_msgbox($erro_msg);
	        echo "<script>
                  js_retornar();
                  parent.js_refresh(); 
	              </script>";
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
<script>
function js_selecao(){
  var obj       = instit.document.form1;
  var tam       = instit.document.form1.length;
  var contador  = 0;
  var virgula   = "";
  var codinst   = eval("document.form1.codinst");

  codinst.value = ""; 
  for(i=0; i < tam; i++){
       if (obj[i].type == "checkbox"){
            if (obj[i].checked == true){
                 contador++;
                 codinst.value += virgula+obj[i].value;
                 virgula        = ",";
            }
       }
  }

  if (contador == 0){
       alert("Selecione alguma instituicao.");
       return false;
  }

  document.form1.submit();
}
function js_voltar(){
  var query = "";

  query  = "o69_codparamrel=<?=$o69_codparamrel?>";
  query += "&o69_codseq=<?=$o69_codseq?>";
  query += "&grupo=<?=$grupo?>";
  query += "&flag_permissao=<?=$flag_permissao?>";

  document.location.href = "func_seleciona_plano.php?"+query;
}
</script>
</head>

<body>

<form name=form1 action=""  method="POST">
  <table align="center" border="0">
<?
   db_input("o69_codparamrel",10,0,true,"hidden");
   db_input("o69_codseq",10,0,true,"hidden");
   db_input("grupo",10,0,true,"hidden");
   db_input("flag_permissao",10,0,true,"hidden");
   db_input("instituicao",10,0,true,"hidden");

   db_input("codinst",10,0,true,"hidden");
?>
    <tr align="center">
      <td nowrap colspan="2">
   <?
         $sql          = "select distinct codigo,nomeinst 
                          from db_config 
                               inner join orcparamelemento on o44_anousu    = ".db_getsession("DB_anousu")." and
                                                              o44_codparrel = $o69_codparamrel and
                                                              o44_sequencia = $o69_codseq      and
                                                              o44_instit    = db_config.codigo
                          order by codigo";
         $sql_disabled = "select codigo,nomeinst from db_config where codigo = $instituicao order by codigo";  
         $campos       = "codigo,nomeinst";

         $cliframe_importarparametros->campos        = $campos;
         $cliframe_importarparametros->legenda       = "";
         $cliframe_importarparametros->sql           = $sql;
         $cliframe_importarparametros->sql_disabled  =  $sql_disabled;
         $cliframe_importarparametros->iframe_height = "400";
         $cliframe_importarparametros->iframe_width  = "400";
         $cliframe_importarparametros->iframe_nome   = "instit";
         $cliframe_importarparametros->chaves        = "codigo";
         $cliframe_importarparametros->js_marcador   = "";
         $cliframe_importarparametros->iframe_seleciona(1);
   ?>
      </td>
    </tr>
    <tr align="center">
      <td nowrap align="right"><input type="button" value="Importar" onClick="js_selecao();">&nbsp;&nbsp;</td>
      <td nowrap align="left"><input  type="button" value="Voltar"   onClick="js_voltar();"></td>
    </tr>
</table>
</form>
<?
   if (trim(@$erro_msg)!=""){
        db_msgbox($erro_msg);
   }
?>
</body>
</html>
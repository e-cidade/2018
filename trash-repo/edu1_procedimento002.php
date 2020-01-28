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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("model/educacao/ArredondamentoNota.model.php");
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oDaoProcedimento = db_utils::getdao("procedimento");
$db_opcao         = 22;
$db_opcao1        = 3;
$db_botao         = false;

if (isset($alterar)) {

  $db_opcao  = 2;
  $db_opcao1 = 3;
  $db_botao  = true;
  db_inicio_transacao();
  $oDaoProcedimento->alterar($ed40_i_codigo);
  db_fim_transacao();

} elseif (isset($chavepesquisa)) {

  $db_opcao         = 2;
  $db_opcao1        = 3;
  $sSqlProcedimento = $oDaoProcedimento->sql_query($chavepesquisa);
  $rsProcedimento   = $oDaoProcedimento->sql_record($sSqlProcedimento);
  db_fieldsmemory($rsProcedimento, 0);
  $db_botao  = true;
  ?>
  <script>
   parent.document.formaba.a2.disabled    = false;
   parent.document.formaba.a2.style.color = "black";
   parent.document.formaba.a3.disabled    = false;
   parent.document.formaba.a3.style.color = "black";
   top.corpo.iframe_a2.location.href      = 'edu1_avaliacoes.php?procedimento=<?=$ed40_i_codigo?>'+
                                            '&ed40_c_descr=<?=$ed40_c_descr?>&forma=<?=trim($ed37_c_tipo)?>';
   top.corpo.iframe_a3.location.href      = 'edu1_procescola001.php?ed86_i_procedimento=<?=$ed40_i_codigo?>'+
                                            '&ed40_c_descr=<?=$ed40_c_descr?>';
  </script>
  <?
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
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <br>
     <center>
      <fieldset style="width:95%"><legend><b>Alteração de Procedimento de Avaliação</b></legend>
       <?include("forms/db_frmprocedimento.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<script>
js_tabulacaoforms("form1","ed40_c_descr",true,1,"ed40_c_descr",true);
</script>
<?

if (isset($chavepesquisa)) {
  ?><script>iframe_aval.location.href = "edu1_procedimento004.php?codigo=<?=$ed40_i_formaavaliacao?>";</script><?
}

if (isset($alterar)) {

  if ($oDaoProcedimento->erro_status == "0") {

    $oDaoProcedimento->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoProcedimento->erro_campo != "") {

      echo "<script> document.form1.".$oDaoProcedimento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoProcedimento->erro_campo.".focus();</script>";

    }

  } else {

    $oDaoProcedimento->erro(true,false);
    ?>
    <script>
     parent.document.formaba.a2.disabled = false;
     parent.document.formaba.a3.disabled = false;
     top.corpo.iframe_a2.location.href   = 'edu1_avaliacoes.php?procedimento=<?=$ed40_i_codigo?>'+
                                           '&ed40_c_descr=<?=$ed40_c_descr?>&forma=<?=trim($ed37_c_tipo)?>';
     top.corpo.iframe_a3.location.href   = 'edu1_procescola001.php?ed86_i_procedimento=<?=$ed40_i_codigo?>'+
                                           '&ed40_c_descr=<?=$ed40_c_descr?>';
    </script>
    <?

  }

}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
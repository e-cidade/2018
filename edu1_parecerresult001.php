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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_parecerresult_classe.php");
include("classes/db_diarioresultado_classe.php");
include("classes/db_diario_classe.php");
include("classes/db_parecer_classe.php");
include("classes/db_parecerturma_classe.php");
include("classes/db_parecerlegenda_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clparecerresult = new cl_parecerresult;
$clparecerlegenda = new cl_parecerlegenda;
$cldiarioresultado = new cl_diarioresultado;
$cldiario = new cl_diario;
$clparecer = new cl_parecer;
$clparecerturma = new cl_parecerturma;
$db_opcao = 2;
$db_botao = true;
if(isset($alterar2)){
 db_inicio_transacao();
 $sql_reg = "SELECT ed95_i_regencia as codregatual
             FROM diarioresultado
              inner join diario on ed95_i_codigo = ed73_i_diario
             WHERE ed73_i_codigo = $ed63_i_diarioresultado
            ";
 $result_reg = pg_query($sql_reg);
 db_fieldsmemory($result_reg,0);
 $sql = "UPDATE diarioresultado SET ed73_t_parecer = '$ed73_t_parecer' WHERE ed73_i_codigo = $ed63_i_diarioresultado";
 $query = pg_query($sql);
 $tam = sizeof(@$reg_outras);
 if($tam>0){
  $regs = "";
  $sep = "";
  for($x=0;$x<$tam;$x++){
   $regs .= $sep.$reg_outras[$x];
   $sep = ",";
  }
  $result = $cldiarioresultado->sql_record($cldiarioresultado->sql_query("","ed73_i_codigo as ed63_i_diarioresultado",""," ed95_i_regencia in($regs) AND ed95_i_aluno = $codaluno AND ed73_i_procresultado = $ed43_i_codigo"));
  for($t=0;$t<$cldiarioresultado->numrows;$t++){
   db_fieldsmemory($result,$t);
   $result_jatem = $clparecerresult->sql_record($clparecerresult->sql_query("","ed63_i_codigo as cod_paraval_jatem,ed63_t_parecer as parecer_jatem",""," ed95_i_regencia = $codregatual AND ed95_i_aluno = $codaluno AND ed73_i_procresultado = $ed43_i_codigo"));
   $linhas_jatem = $clparecerresult->numrows;
   if($linhas_jatem==0){
    $clparecerresult->excluir(""," ed63_i_diarioresultado = $ed63_i_diarioresultado");
   }else{
    $clparecerresult->excluir(""," ed63_i_diarioresultado = $ed63_i_diarioresultado");
    for($s=0;$s<$linhas_jatem;$s++){
     db_fieldsmemory($result_jatem,$s);
     $result_jatem2 = $clparecerresult->sql_record($clparecerresult->sql_query("","ed63_i_codigo as confere",""," ed63_i_diarioresultado = $ed63_i_diarioresultado"));
     $linhas_jatem2 = $clparecerresult->numrows;
     if($linhas_jatem2==0){
      $clparecerresult->ed63_i_diarioresultado = $ed63_i_diarioresultado;
      $clparecerresult->ed63_t_parecer = $parecer_jatem;
      $clparecerresult->incluir(null);
     }else{
      db_fieldsmemory($result_jatem2,0);
      $clparecerresult->ed63_i_diarioresultado = $ed63_i_diarioresultado;
      $clparecerresult->ed63_t_parecer = $parecer_jatem;
      $clparecerresult->ed63_i_codigo = $confere;
      $clparecerresult->alterar($confere);
     }
    }
   }
   $sql = "UPDATE diarioresultado SET ed73_t_parecer = '$ed73_t_parecer' WHERE ed73_i_codigo = $ed63_i_diarioresultado";
   $query = pg_query($sql);
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
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 9;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td valign="top" bgcolor="#CCCCCC">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td class="titulo">
      Parecer para o aluno <?=$aluno?> no período <?=$periodo?>
     </td>
     <td align="right" class="titulo">
      <input type="button" id="voltar" name="voltar" value="Fechar" title="Fechar" onclick="parent.parent.iframe_R<?=$ed43_i_codigo?>.location.href = 'edu1_diarioresultado001.php?regencia=<?=$regencia?>&ed43_i_codigo=<?=$ed43_i_codigo?>';parent.db_iframe_parecer.hide();">
     </td>
    </tr>
    <tr>
     <td colspan="2">
      <center>
      <?include("forms/db_frmparecerresult001.php");?>
      </center>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($alterar2)){
 ?>
 <script>
  parent.db_iframe_parecer.hide();
  alert("Alteração efetuada com Sucesso");
  parent.parent.iframe_R<?=$ed43_i_codigo?>.location.href = "edu1_diarioresultado001.php?regencia=<?=$regencia?>&ed43_i_codigo=<?=$ed43_i_codigo?>";
  parent.document.form1.<?=$campo?>.value = "<?=$ed73_t_parecer?>";
 </script>
 <?
}
?>
<script>
 parent.db_iframe_parecer.liberarJanBTFechar('false');
 parent.db_iframe_parecer.liberarJanBTMinimizar('false');
 parent.db_iframe_parecer.liberarJanBTMaximizar('false');
</script>
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
include("classes/db_empagegera_classe.php");
include("classes/db_empageconfgera_classe.php");
include("classes/db_empagetipo_classe.php");
include("classes/db_empagedadosret_classe.php");
include("dbforms/db_classesgenericas.php");
$clempagegera = new cl_empagegera;
$clempageconfgera = new cl_empageconfgera;
$clempagetipo = new cl_empagetipo;
$clempagedadosret = new cl_empagedadosret;
$clrotulo = new rotulocampo;
$clempagegera->rotulo->label();
$clempagetipo->rotulo->label();
$clempagedadosret->rotulo->label();

db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.e83_codtipo.focus();" bgcolor="#cccccc">
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
	<td width="360" height="18">&nbsp;</td>
	<td width="263">&nbsp;</td>
	<td width="25">&nbsp;</td>
	<td width="140">&nbsp;</td>
      </tr>
    </table>
<center>
<form name="form1" method="post" action="cai2_arquivosenviados002.php">
<table border='0'>
  <tr height="20px">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td align="right" nowrap><strong>Data inicial:</strong></td>
    <td align="left" nowrap>
    <?
    $datai_dia = date("d",db_getsession("DB_datausu"));
    $datai_mes = date("m",db_getsession("DB_datausu"));
    $datai_ano = date("Y",db_getsession("DB_datausu"));
    ?>
    <?
    db_inputdata("datai",$datai_dia,$datai_mes,$datai_ano,true,"text",1);
    ?>
    </td>
  </tr>
  <tr> 
    <td align="right" nowrap><strong>Data final:</strong></td>
    <td align="left" nowrap>
    <?
    $dataf_dia = date("d",db_getsession("DB_datausu"));
    $dataf_mes = date("m",db_getsession("DB_datausu"));
    $dataf_ano = date("Y",db_getsession("DB_datausu"));
    ?>
    <?
    db_inputdata("dataf",$dataf_dia,$dataf_mes,$dataf_ano,true,"text",1);
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <?
      $aux = new cl_arquivo_auxiliar;
      $aux->cabecalho = "<strong>CONTAS SELECIONADAS</strong>";
      $aux->codigo = "e83_codtipo";
      $aux->descr  = "e83_descr";
      $aux->nomeobjeto = 'contas_selecionadas';
      $aux->funcao_js = 'js_mostra';
      $aux->funcao_js_hide = 'js_mostra1';
      $aux->func_arquivo = "func_empagetipo.php";
      $aux->nomeiframe = "db_iframe_empatetipo";
      $aux->executa_script_apos_incluir = "document.form1.e83_codtipo.focus();";
      $aux->localjan = "";
      $aux->db_opcao = 2;
      $aux->tipo = 2;
      $aux->top = 20;
      $aux->linhas = 20;
      $aux->vwidth = "360";
      $aux->funcao_gera_formulario();
      db_input('selecionadas',10,"",true,"hidden",1);
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap><strong>Opções:</strong></td>
    <td align="left" nowrap>
      <?
	  $arr_opcoes = array("S"=>"Somente Selecionados&nbsp;&nbsp;","N"=>"Menos os Selecionados&nbsp;&nbsp;");
	  db_select('opcoes',$arr_opcoes,true,2);
	  ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"><br>
      <input name="act" type="button" onclick='js_gerarel();'  value="Mostrar retorno">
    </td>
  </tr>
</table>
</form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_gerarel(){
  document.form1.selecionadas.value = ""; 
  if(document.form1.contas_selecionadas.length > 0){
    document.form1.selecionadas.value = js_campo_recebe_valores();
  }

  variavel = 1;
  jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  document.form1.target = 'safo' + variavel++;
  setTimeout("document.form1.submit()",1000);
}
</script>
</body>
</html>
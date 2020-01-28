<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo = new rotulocampo;
$clrotulo->label('c60_codcon');
$clrotulo->label('c60_descr');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){
  var_obj = document.getElementById('conta').length;
  cods = "";
  vir  = "";
  for(y=0;y<var_obj;y++){
    var_if = parseInt(document.getElementById('conta').options[y].value)
    cods += vir + var_if;
    vir = ",";
  }
  qry = 'codigos='+cods;
  qry+= '&conta='+document.form1.conta.value;  // conta (campo - variável)
  qry+= '&data='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value;
  qry+= '&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
  qry+= '&parametro='+document.form1.parametro.value;
  qry+= '&k144_numeroprocesso='+document.form1.k144_numeroprocesso.value;
  jan = window.open('cai2_relplacaixa002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table  align="center" border="0">
    <form name="form1">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3">
	  <center>
	  <table>
	    <tr>
	      <td><b>De:</b><?db_inputdata("data","","","","true","text",2);?></td>
              <td><b>Até:</b><?db_inputdata("data1","","","","true","text",2);?></td>
	    </tr>
	    
	    <tr>
	      <td nowrap="nowrap">
	        <strong>Processo Administrativo:</strong>
	      </td>
	    
	      <td>
	        <?php db_input('k144_numeroprocesso',10, null,true,'text',1, null,null,null,null,15);?>
	      </td>
	    
	    </tr>
	    
	  </table>
	  </center>
	</td>
      </tr>
      <tr>
        <td colspan="3" align = "center">
	  <table>
	  <?
	  $aux = new cl_arquivo_auxiliar;
	  $aux->cabecalho = "<strong>CONTAS</strong>";
	  $aux->codigo = "k13_conta";   //z01_numcgm";
	  $aux->descr  = "k13_descr";   // z01_nome
	  $aux->isfuncnome = true;
	  $aux->nomeobjeto = 'conta'; //'cgm';
	  $aux->funcao_js = 'js_mostra';
	  $aux->funcao_js_hide = 'js_mostra1';
	  $aux->sql_exec  = "";
	  $aux->func_arquivo = "func_saltes.php"; //"func_nome.php";
	  $aux->nomeiframe = "func_saltes";
	  $aux->localjan = "";
	  $aux->db_opcao = 2;
	  $aux->tipo = 2;
	  $aux->top = 0;
	  $aux->linhas = 10;
	  $aux->vwhidth = 400;
	  $aux->funcao_gera_formulario();
	  ?>
	  </table>
        </td>
      </tr>
      <tr>
        <td align="right"> <strong>Opção de Seleção :<strong></td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
          $xxx = array("S"=>"Somente Selecionados&nbsp;&nbsp;","N"=>"Menos os Selecionados&nbsp;&nbsp;");
          db_select('parametro',$xxx,true,2);
          ?>
        </td>
        <td align="center">
          <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
       </td>
      </tr>
    </form>
  </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
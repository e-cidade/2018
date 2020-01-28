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
$aux = new cl_arquivo_auxiliar;
$db_opcao = 1;
?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script>
  function js_abre(){
    variavel = 1;
    vir="";
    listagem="";
    for(i=0;i<document.form1.length;i++){      
      if(document.form1.elements[i].name == "ativtipo[]"){
	for(x=0;x< document.form1.elements[i].length;x++){
	  listagem+=vir+document.form1.elements[i].options[x].value;
	  vir=",";
	}
      }
    }
//    alert(document.form1.param_where.value);
    jan = window.open('iss2_ativtipo002.php?lista='+listagem+'&param_where='+document.form1.param_where.value+'&order='+document.form1.order.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);    
    }
  </script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
  <form name="form1" method="post" action="">
  <table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> <BR><BR><BR>
      <table>
        <tr>
	  <td colspan="2">
	    <table>
	      <tr>
	        <td>
	<?
	$aux = new cl_arquivo_auxiliar;
	$aux->cabecalho = "<strong>TIPOS DE C�LCULO</strong>";
	$aux->codigo = "q81_codigo";
	$aux->descr  = "q81_abrev";
	$aux->nomeobjeto = 'ativtipo';
	$aux->funcao_js = 'js_mostra';
	$aux->funcao_js_hide = 'js_mostra1';
	$aux->sql_exec  = "";
	$aux->func_arquivo = "func_tipcalc.php";
	$aux->nomeiframe = "db_iframe_tipcalc";
	$aux->localjan = "";
	$aux->db_opcao = 2;
	$aux->tipo = 2;
	$aux->top = 0;
	$aux->linhas = 10;
	$aux->vwhidth = 400;
	$aux->funcao_gera_formulario();
	?>
	        </td>
	      </tr>
	    </table>
	  </td>
        </tr>
        <tr>
          <td align="right"> <strong>Op��o de Sele��o :<strong></td>
	  <td align="left">&nbsp;&nbsp;&nbsp;
	    <?
	    $xxx = array("S"=>"Somente Selecionados","N"=>"Menos os Selecionados");
	    db_select('param_where',$xxx,true,2);
	    ?>
	  </td>
        </tr>
        <tr>
          <td align="right"> <strong>Ordem :<strong></td>
	  <td align="left">&nbsp;&nbsp;&nbsp;
	    <?
	    $yyy = array("alf"=>"Alfab�tica","num"=>"Num�rica");
	    db_select('order',$yyy,true,2);
	    ?>&nbsp;&nbsp;&nbsp;
	    <input name="relatorio" type="button" onclick='js_abre();'  value="Gerar relat�rio">
          </td>
        </tr>
      </table>
    </td>
  </tr> 
</table> 
</form>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
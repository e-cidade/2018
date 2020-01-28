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

$clrotulo = new rotulocampo;
$clrotulo->label("id_usuario");
$clrotulo->label("nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
  <table align="center" style="padding-top:23px">
    <tr> 
      <td>
        <fieldset>
         <legend align="center">
           <b>Execuções da Virada</b>
         </legend>
          <table>
			      <tr>
					    <td align="right" > 
					      <b> Período:</b>
					    </td>  
					    <td>
					     <?
					       db_inputdata('dtini',@$dia,@$mes,@$ano,true,'text',1,"");                 
					       echo " a ";
					       db_inputdata('dtfim',@$dia,@$mes,@$ano,true,'text',1,"");
					     ?>
					    </td>
			      </tr>
					  <tr>
					    <td nowrap title="<?=@$Tid_usuario?>" align="right">
					       <?
					       db_ancora("<b>Usuário:</b>","js_pesquisaUsuario(true);",1);
					       ?>
					    </td>
					    <td> 
								<?
								db_input('id_usuario',7,$Iid_usuario,true,'text',1," onchange='js_pesquisaUsuario(false);'");
								db_input('nome',40,$Inome,true,'text',3,'');
								?>
					    <td>
					  </tr>
					  <tr>
					    <td nowrap title="Situacão" align="right"><b>Situação:</b></td>
					    <td> 
					      <?
					        $situacao = 0;
					        $aSituacao = array("0"=>"Todos","1"=>"Processado","2"=>"Cancelado");
					        db_select('situacao',$aSituacao,true,1,"");
					      ?>
					    <td>
					  </tr>
          </table>  
        </fieldset>   
      </td>
    </tr>
    <tr>
      <td align="center">
        <input type="button" id="imprimir" value="Imprimir" onClick="js_imprimir()"/>
      </td>
    </tr>
  </table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_imprimir(){
  var obj       = document.form1;
  var usuario   = obj.id_usuario.value;
  var situacao  = obj.situacao.value;
  var dtini_dia = obj.dtini_dia.value;
  var dtini_mes = obj.dtini_mes.value;
  var dtini_ano = obj.dtini_ano.value;
  var dtfim_dia = obj.dtfim_dia.value;
  var dtfim_mes = obj.dtfim_mes.value;
  var dtfim_ano = obj.dtfim_ano.value;
  var sParam    = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
  var sQuery    = 'situacao='+situacao;

  if((dtini_dia != '') && (dtini_mes != '') && (dtini_ano != '')){
      sQuery   += "&dtini="+dtini_ano+"-"+dtini_mes+"-"+dtini_dia;
  }
	  
  if((dtfim_dia != '') && (dtfim_mes != '') && (dtfim_ano != '')){
      sQuery   += "&dtfim="+dtfim_ano+"-"+dtfim_mes+"-"+dtfim_dia;
  }

  if((dtini_dia > dtfim_dia) || (dtini_mes > dtfim_mes) || (dtini_ano > dtfim_ano)){
    alert('Data Incorreta. Verifique!');
    return false;
  }
  
  if(usuario != ''){
      sQuery   += '&usuario='+usuario;
  }

  var sUrl = 'con4_execucoesviradadeano003.php?'+sQuery;
  var jan  = window.open(sUrl,'',sParam);
    
}

function js_pesquisaUsuario(mostra) {
  var obj     = document.form1;
  var usuario = obj.id_usuario.value;
  var sUrl1   = 'func_db_usuarios.php?funcao_js=parent.js_mostrausu1|id_usuario|nome';
  var sUrl2   = 'func_db_usuarios.php?pesquisa_chave='+usuario+'&funcao_js=parent.js_mostrausu';
  
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuario',sUrl1,'Pesquisa',true);
  } else {
    if (usuario != "") {
      js_OpenJanelaIframe('top.corpo','db_iframe_db_usuario',sUrl2,'Pesquisa',false);
    } else {
      obj.nome.value = '';
    }
  }
}
function js_mostrausu1(chave1,chave2) {
  var obj              = document.form1;
  obj.id_usuario.value = chave1;
  obj.nome.value       = chave2;
  db_iframe_db_usuario.hide();
}
function js_mostrausu(chave,erro) {
  var obj        = document.form1;
  obj.nome.value = chave;
  if (erro == true) {
    obj.id_usuario.value = '';
    obj.id_usuario.focus();
  }
}
</script>
</html>
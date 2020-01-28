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
include("classes/db_atendimento_classe.php");
include("classes/db_db_usuarios_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$cldb_usuarios = new cl_db_usuarios;
$clrotulo = new rotulocampo;
$clrotulo->label('nome');
$clrotulo->label('at01_codcli');
$clrotulo->label('at02_codcli');
$clrotulo->label('at02_dataini');
$clrotulo->label('at02_datafim');

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
.tabcols {
  font-size:11px;
}
.tabcols1 {
  text-align: right;
  font-size:11px;
}
.btcols {
	height: 17px;
	font-size:10px;
}
.links {
	font-weight: bold;
	color: #0033FF;
	text-decoration: none;
	font-size:10px;
    cursor: hand;
}
a.links:hover {
    color:black;
	text-decoration: underline;
}
.links2 {
	font-weight: bold;
	color: #0587CD;
	text-decoration: none;
	font-size:10px;
}
a.links2:hover {
    color:black;
	text-decoration: underline;
}
.nome {
  color:black;  
}
a.nome:hover {
  color:blue;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<form method="" name="form1" target="">
<input type="hidden" name="tipo" value="<?=$tipo_cert?>">
<table width="60%">
    <tr>
   	   <td align="center"><br><br><font face="Arial, Helvetica, sans-serif"><strong>Relatório Estatístico de Atendimentos</strong></font></td>
    </tr>
    <tr>
      <td><table width="100%">
  <tr>
    <td nowrap title="<?=@$Tat01_codcli?>">
       <?
       db_ancora(@$Lat01_codcli,"js_pesquisaat02_codcli(true);",'1');
       ?>
    </td>
    <td> 
<?
db_input('at02_codcli',4,"",true,'text','1',"onchange='js_pesquisaat02_codcli(false);'")
?>
<?
db_input('at01_nomecli',40,"",true,'text',3,'')
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Tat02_dataini?>">
       <?=$Lat02_dataini?>
    </td>
    <td> 
<?
db_inputdata('at02_dataini',@$at02_dataini_dia,@$at02_dataini_mes,@$at02_dataini_ano,true,'text','1',"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Tat02_datafim?>">
       <?=$Lat02_datafim?>
    </td>
    <td> 
<?
db_inputdata('at02_datafim',@$at02_datafim_dia,@$at02_datafim_mes,@$at02_datafim_ano,true,'text','1',"")
?>
    </td>
  </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center"><input name="relatorio" type="button" value="Relatório" onClick="js_relatorio()">
      </td>
      </tr>
  </table>
</form>
<script>
function js_consulta(){
    db_iframe.jan.location.href = 'func_consultaatendimento.php?funcao_js=parent.js_relatorio|0&at02_codcli='+document.form1.at02_codcli.value+'&at03_id_usuario='+document.form1.at03_id_usuario.value+'&at02_dataini_dia='+document.form1.at02_dataini_dia.value+'&at02_dataini_mes='+document.form1.at02_dataini_mes.value+'&at02_dataini_ano='+document.form1.at02_dataini_ano.value+'&at02_datafim_dia='+document.form1.at02_datafim_dia.value+'&at02_datafim_mes='+document.form1.at02_datafim_mes.value+'&at02_datafim_ano='+document.form1.at02_datafim_ano.value;
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
}
function js_relatorio(chave1){
  window.open('ate2_reltarefaagenda002.php?&cliente='+document.form1.at02_codcli.value+'&at02_dataini_dia='+document.form1.at02_dataini_dia.value+'&at02_dataini_mes='+document.form1.at02_dataini_mes.value+'&at02_dataini_ano='+document.form1.at02_dataini_ano.value+'&at02_datafim_dia='+document.form1.at02_datafim_dia.value+'&at02_datafim_mes='+document.form1.at02_datafim_mes.value+'&at02_datafim_ano='+document.form1.at02_datafim_ano.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  db_iframe.hide();
}
function js_pesquisaat02_codcli(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_clientes.php?funcao_js=parent.js_mostraclientes1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_clientes.php?pesquisa_chave='+document.form1.at02_codcli.value+'&funcao_js=parent.js_mostraclientes';
  }
}
function js_mostraclientes1(chave1,chave2){
  document.form1.at02_codcli.value = chave1;
  document.form1.at01_nomecli.value = chave2;
  db_iframe.hide();
}
function js_mostraclientes(chave,erro){
  document.form1.at01_nomecli.value = chave; 
  if(erro==true){ 
    document.form1.at02_codcli.focus(); 
    document.form1.at02_codcli.value = ''; 
  }
}
function js_pesquisausuario(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_usuarios.php?funcao_js=parent.js_mostrausuario1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_usuarios.php?pesquisa_chave='+document.form1.at03_id_usuario.value+'&funcao_js=parent.js_mostrausuario';
  }
}
function js_mostrausuario1(chave1,chave2){
  document.form1.at03_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe.hide();
}
function js_mostrausuario(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.at03_id_usuario.focus(); 
    document.form1.at03_id_usuario.value = ''; 
  }
}
</script>
</center>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
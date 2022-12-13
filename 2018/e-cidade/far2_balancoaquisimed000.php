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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_stdlibwebseller.php");
include("dbforms/db_funcoes.php");
include("classes/db_far_modelolivro_classe.php");
include("classes/db_far_fechalivro_classe.php");
include("classes/db_far_farmacia_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfar_modelolivro = new cl_far_modelolivro;
$clfar_fechalivro  = new cl_far_fechalivro;
$clfar_farmacia    = new cl_far_farmacia;
$clrotulo          = new rotulocampo;
$clfar_fechalivro->rotulo->label();
$fa26_i_login = DB_getsession("DB_id_usuario");
$ano          = date("Y");

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <meta http-equiv="Expires" CONTENT="0">
      <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
      <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
    <center>
      <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
        <tr>
          <td width="360" height="18">&nbsp;</td>
          <td width="263">&nbsp;</td>
          <td width="25">&nbsp;</td>
          <td width="140">&nbsp;</td>
        </tr>
      </table align="center">
      <br><br>  
      <fieldset style="width:40%" align="center"><legend>Relatórios de Balanço Completo de Medicamentos</legend>
        <form name="form1" method="post" action="" >  
          <table  align="center"> 
	        <tr>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            <tr>
		      <td nowrap align='right'>
                <b>Período :</b>
              </td>
              <td nowrap>
	            <?
                  $x = array("0"=>"Escolha um Período","t"=>"Trimestre","a"=>"Anual");
                  db_select("escolha", $x, "", "","Onchange='js_escolha(this.value)'", "", "");
	            ?>
              </td>
            </tr>
            <tr>
              <td nowrap align='right'>
                <b> Mês :</b>
              </td>
              <td nowrap>
                <select name="periodo" id="periodo">   
	              <option value="0">Escolha um Trimestre </option>
                </select>
		      </td>
            </tr>
            <tr>
              <td nowrap align='right'>
                <b>Ano:</b>
              </td>
              <td>
                <input name="ano" id="ano" type="text" size="6" value="<?=date("Y")?>">
              </td>
            </tr>
            <tr>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
          </table>
        </form>  
      </fieldset>
	  <br>
	  <input  name="emite2" id="emite2" type="button" value="Imprimir" onclick="js_emite();" ></center>
    </center>  
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>
<script>
function js_escolha(escolha){
document.form1.periodo.length=0;
document.form1.periodo.disabled=false;
 if(escolha=="t"){
  document.form1.periodo.options[document.form1.periodo.length] = new Option('>>>Escolha um Trimestre','0');
  document.form1.periodo.options[document.form1.periodo.length] = new Option('Primeiro Trimestre','1T');
  document.form1.periodo.options[document.form1.periodo.length] = new Option('Segundo Trimestre','2T');
  document.form1.periodo.options[document.form1.periodo.length] = new Option('Terceiro Trimestre','3T');
  document.form1.periodo.options[document.form1.periodo.length] = new Option('Quarto Trimestre','4T');
 }
 if(escolha=="a"){
  document.form1.periodo.options[document.form1.periodo.length] = new Option('Todos','1A');
  document.form1.periodo.disabled=true;
 }
}
function js_emite(){
  if((document.form1.ano.value!='')&&(document.form1.ano.value>0)){ 
     if(document.form1.periodo.value!='0'){ 
        jan = window.open('far2_balancoaquisimed001.php?periodo='+document.form1.periodo.value+'&ano='+document.form1.ano.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);
     }else{
        alert('Selecione um periodo!');
     }
  }else{
     alert('Insira um ano valido!');
  }   
}
</script>
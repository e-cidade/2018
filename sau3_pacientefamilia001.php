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
include("classes/db_cgs_und_classe.php");
require("libs/db_app.utils.php");

$cl_cgs_und = new cl_cgs_und;
$clrotulo = new rotulocampo;

$clrotulo->label("sd03_i_codigo");
$clrotulo->label("sd03_c_nome");
$clrotulo->label("sd33_i_codigo");
$clrotulo->label("sd33_v_descricao");
$clrotulo->label("sd34_i_codigo");
$clrotulo->label("sd34_v_descricao");

$db_opcao = 1;
$db_botao = true;

$cl_cgs_und->rotulo->label();
$clrotulo->label("z01_i_cgs_und");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("prototype.js");
db_app::load("scripts.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("/widgets/dbautocomplete.widget.js");
db_app::load("webseller.js");
?>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height='18' border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
	    <br>
      <center>
      <fieldset style="width:50%"><legend><b>Consulta Prontuarios da familia</b></legend>
      <table border="0">
	        <tr>
	           <td nowrap title="$Tjs_pesquisaz01_i_cgsund">
                 <b><? db_ancora("$Lz01_i_cgsund","js_pesquisaz01_i_cgsund(true);",$db_opcao);?></b></td>
		         <td><? db_input('z01_i_cgsund',10,$Iz01_i_cgsund,true,'text',$db_opcao,"onchange='js_pesquisaz01_i_cgsund(false);'")?> </td>
		         <td><? db_input('z01_v_nome',40,$Iz01_v_nome,true,'text',3,$db_opcao,"")?></td>
	       </tr>
         <tr>
             <td><b>Micro:</b></td>
             <td>
                <? db_input("sd34_i_codigo",10,$Isd33_i_codigo ,true,"text",3,"");?>
             </td>
             <td>
                 <? db_input('sd34_v_descricao',40,$Isd33_v_descricao,true,'text',3,"")?>
                 <? db_input('z01_i_familiamicroarea',10,$Iz01_i_familiamicroarea,true,'hidden',1,"")?>
             </td>
         </tr>
         <tr>
             <td><b>Familia:</b></td>
             <td>
                 <? db_input("sd33_i_codigo",10,$Isd33_i_codigo ,true,"text",3,"");?>
             </td>
             <td>
                 <? db_input('sd33_v_descricao',40,$Isd33_v_descricao,true,'text',3,"")?>
             </td>
         </tr>
         <tr>
             <td colspan='6' align='center' >
                 <input name="limpar" id="limpar" value="Limpar" type="button" onclick="js_limpar()">
                 <input name='Processar' type='button' value='Processar' onclick="EnviaForm()">
             </td>
         </tr>
      </table>
      </fieldset>
      </center>
    </form>
    <iframe id="frame" name="frame" src="sau3_pacientefamilia002.php" width="60%" height="80%" scrolling="no"></iframe>
  </td>
 </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  sau_ajax = new ws_ajax('sau3_pacientefamiliaRPC.php');
 
 function limpar_campos(){
 	document.form1.z01_i_cgsund.value = '';
	document.form1.z01_v_nome.value = '';
 }
 
 //Funções da Ancora CGS           
 function js_pesquisaz01_i_cgsund(mostra){
   if(mostra==true){      
	  js_OpenJanelaIframe('','db_iframe_agendamento','func_cgs_und.php?funcao_js=parent.js_agendamento1|z01_i_cgsund|z01_v_nome','Pesquisa Pacientes',true);
   }else{
	 if(document.form1.z01_i_cgsund.value != ''){
		js_OpenJanelaIframe('','db_iframe_agendamento','func_cgs_und.php?pesquisa_chave='+document.form1.z01_i_cgsund.value+'&funcao_js=parent.js_agendamento','Pesquisa Pacientes',false);   
     }else{
        document.form1.z01_v_nome.value = '';
     }
   }
 }
 function js_agendamento(chave,erro){
   document.form1.z01_v_nome.value = chave;
   if(erro==true){
      document.form1.z01_i_cgsund.focus();
      document.form1.z01_i_cgsund.value = '';
   }else{
     //Executa requisição ajax para trazer velores da micro area e familia 
     sau_ajax.clear();
     sau_ajax.add('cgs',document.form1.z01_i_cgsund.value);
     sau_ajax.execute('consulta_microfamilia','js_retornocgs');
   }
 }
 function js_agendamento1(chave1,chave2){
   
   js_limpar();
   document.form1.z01_i_cgsund.value = chave1;
   document.form1.z01_v_nome.value = chave2;
   db_iframe_agendamento.hide();
   //executa requisição Ajax para trazer da familia e micro area
   sau_ajax.clear();
   sau_ajax.add('cgs',chave1);
   sau_ajax.execute('consulta_microfamilia','js_retornocgs');
   
 }
 function js_retornocgs(objAjax){      
      objretorno = sau_ajax.monta(objAjax);
      if(objretorno.status==0){
         message_ajax(objretorno.message);
      }else{
         F=document.form1;
         F.sd33_i_codigo.value=objretorno.campo.sd33_i_codigo;
         F.sd33_v_descricao.value=objretorno.campo.sd33_v_descricao;
         F.sd34_i_codigo.value=objretorno.campo.sd34_i_codigo;
         F.sd34_v_descricao.value=objretorno.campo.sd34_v_descricao;
         F.z01_i_familiamicroarea.value=objretorno.campo.z01_i_familiamicroarea;
         EnviaForm();
      }
 }
 function EnviaForm(){
        if(document.form1.z01_i_cgsund.value==""){
           alert("Preencha o Cgs");
           document.form1.z01_i_cgsund.focus();
           return false;
        }
        if(document.form1.z01_i_familiamicroarea.value==""){ 
           alert("CGS sem Familia e Micro area verifique o cadastro!");
           document.form1.z01_i_cgsund.focus();
           return false;
        }
        x  = "sau3_pacientefamilia002.php";
        x += "?Processar";
        x += "&z01_i_familiamicroarea="+document.form1.z01_i_familiamicroarea.value;
        this.frame.location.href=x;
 }
 function js_limpar(){
     F=document.form1;
     F.sd33_i_codigo.value='';
     F.sd33_v_descricao.value='';
     F.sd34_i_codigo.value='';
     F.sd34_v_descricao.value='';
     F.z01_i_familiamicroarea.value='';
     F.z01_i_cgsund.value='';
     F.z01_v_nome.value='';
 }
</script>
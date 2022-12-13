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
include("classes/db_medicos_classe.php");
include("classes/db_especmedico_classe.php");
$cl_medicos = new cl_medicos;
$cl_especmedicos = new cl_especmedico;
$clrotulo = new rotulocampo;
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("sd03_c_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
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
  <td align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <table>
    <tr>
    <td nowrap title="Médico">
     <?db_ancora("Medico","js_pesquisasd03_i_medico(true);",$db_opcao);?>
    </td>
    <td>
     <?db_input('medico',10,$Isd03_i_medico,true,'text',$db_opcao," onchange='js_pesquisasd03_i_medico(false);'")?>
     <?db_input('sd03_c_nome',50,$Isd03_c_nome,true,'text',3,'')?>
    </td>
     </tr>
     <tr>
      <td align='center' >
       <input name='Processar' type='button' value='Processar' onclick="EnviaForm()">
      </td>
     </tr>
    </table>
    </form>
<?
 if(isset($Processar)){
  $result = $cl_especmedicos -> sql_record($cl_especmedicos->sql_query($medico,"","sd05_i_codigo,sd05_c_descr"));
  if($cl_especmedicos->numrows>0){
   db_criatabela($result);
  }else{
   echo "<center>Nenhum Registro</center>";
  }
 }else{
  echo "<center><br><br>Informe o Médico e clique em <b>Processar</b>...</center>";
 }
?>
    <script>
   function js_pesquisasd03_i_medico(mostra){
     if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|sd03_c_nome','Pesquisa',true);
     }else{
     if(document.form1.medico.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.medico.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     }else{
       document.form1.sd03_c_nome.value = '';
     }
     }
   }
   function js_mostramedicos(chave,erro){
    document.form1.sd03_c_nome.value = chave;
    if(erro==true){
     document.form1.medico.focus();
     document.form1.medico.value = '';
    }
   }
   function js_mostramedicos1(chave1,chave2){
    document.form1.medico.value = chave1;
    document.form1.sd03_c_nome.value = chave2;
    db_iframe_medicos.hide();
   }

     function EnviaForm(){
      if(document.form1.medico.value==""){
       alert("Preencha o Médico");
       document.form1.medico.focus();
       return false;
      }
     location.href="sau3_especmedico001.php?Processar&medico="+document.form1.medico.value;
     }
    </script>
  </td>
 </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
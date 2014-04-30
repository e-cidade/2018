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
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");

$db_botao = true;
$db_opcao = 1;
?>
<script>

  function js_verifica(){
    obj= document.form1;
    
    jan = window.open('con2_relpermemp002.php?quebra='+obj.quebra.value+'&exercicio='+obj.exercicio.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    
    jan.moveTo(0,0);
  }
</script>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name='form1'>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
   	 <center>


 <table border="0"> 
  <tr>
    <td colspan=2> &nbsp;   <td>
  </tr>



  <tr>
    <td colspan=2>
     <h3> Relatório de Permissões de Empenho </h3>
    
    <td>
  </tr>
   <tr>
    <td><b> Exercício </b></td>
    <td> <select name="exercicio">  
          <option value="<?=db_getsession("DB_anousu")?>" selected > <?=db_getsession("DB_anousu")   ?> </option>
          <option value="<?=db_getsession("DB_anousu")-1?>"        > <?=db_getsession("DB_anousu")-1 ?> </option>   
         </select>
    </td>
  </tr>
 
  <tr>
    <td><b> Quebra </b></td>
    <td> <select name="quebra">  
          <option value='u'>Usuario</option>
	  <!--
          <option value='e'>Estrutura (Orgao/unidade...)</option>   
	  -->
         </select>
    </td>
  </tr>
  
  <tr>
    <td colspan=2> &nbsp;   <td>
  </tr>
  
  <tr>
    <td colspan='2' align='center'>
      <input type='button' onclick='return js_verifica();' name='entrar' value='Gerar relatório'>
    </td>
  </tr>
</table>  

    </center>
    </td>
  </tr>
  
</table>  
</form>
<script>
  function js_usu(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_usuario','func_db_usuarios.php?funcao_js=parent.js_mostrausu1|id_usuario|nome','Pesquisa',true);
    }else{
      usu= document.form1.id_usuario.value;
      if(usu!=""){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuario','func_db_usuarios.php?pesquisa_chave='+usu+'&funcao_js=parent.js_mostrausu','Pesquisa',false);
      }else{ 	
	document.form1.nome.value='';
      } 	
    }
  }
  function js_mostrausu1(chave1,chave2){
    document.form1.id_usuario.value = chave1;
    document.form1.nome.value = chave2;
    db_iframe_db_usuario.hide();
  }
  function js_mostrausu(chave,erro){
    document.form1.nome.value = chave; 
    if(erro==true){ 
      document.form1.id_usuario.focus(); 
      document.form1.id_usuario.value = ''; 
    }
  }
  function js_coddepto(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostracoddepto1|coddepto|descrdepto','Pesquisa',true);
    }else{
      coddepto = document.form1.coddepto.value;
      if(coddepto!=""){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+coddepto+'&funcao_js=parent.js_mostracoddepto','Pesquisa',false);
      }else{ 	
	document.form1.descrdepto.value='';
      } 	
    }
  }
  function js_mostracoddepto1(chave1,chave2){
    document.form1.coddepto.value = chave1;
    document.form1.descrdepto.value = chave2;
    db_iframe_db_depart.hide();
  }
  function js_mostracoddepto(chave,erro){
    document.form1.descrdepto.value = chave; 
    if(erro==true){ 
      document.form1.coddepto.focus(); 
      document.form1.coddepto.value = ''; 
    }
  }
  document.form1.id_usuario.focus();
</script>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
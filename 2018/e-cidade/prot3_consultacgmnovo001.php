<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cgm_classe.php");
$clcgm = new cl_cgm;
$clcgm->rotulo->label();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.z01_numcgm.focus();" bgcolor="#cccccc">
  <?php 
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
  	<form name="form1" method="post">
  		<fieldset style='margin-top:5px; margin-bottom:10px; width:500px;'>
  			<legend><strong>Consulta CGM</strong></legend>
        <table border='0'>
          <tr> 
            <td  align="right" nowrap title="<?=$Tz01_numcgm?>"> 
              <? 
                 //Clicando na ancora para buscar o cgm atraves do formulario de pesquisa.
                 db_ancora($Lz01_numcgm,"js_pesquisaz01_numcgm(true);",1);
              ?>
            </td> 
            <td align="left" nowrap>
              <?
                 //Digitando um numero de cgm para buscar
        	       db_input("z01_numcgm",10,$Iz01_numcgm,true,"text",1,"onchange='js_pesquisaz01_numcgm(false);'"); 
        	       db_input("z01_nome",45,$Iz01_nome,true,"text",3);
              ?>
            </td>
          </tr>
        </table>
  		</fieldset>
      <input name="pesquisar" type="button" onclick='js_abre();' value="Pesquisar">
		</form> 
	</center>
</body>
</html>

<script>
//--------------------------------------------------------------------------------------------------------------------------

//Função que passa como parametro o numero do cgm para mostrar as informações referentes ao mesmo.
//Caso não informe um NUMCGM retornará um alert pedindo para informar o NUMCGM.
function js_abre()
{
  if(document.form1.z01_numcgm.value!="")
  {
    js_OpenJanelaIframe('top.corpo','func_nome','prot3_consultacgmnovo002.php?numcgm='+document.form1.z01_numcgm.value,'Pesquisa',true);
    //alert('NUMCGM:'+document.form1.z01_numcgm.value+' - '+document.form1.z01_nome.value)
  }
  else
  {
    alert('Informe um número de cgm!');
  }
}

//Função que pesquisa caso seja TRUE a pesquisa foi feita atraves da ancora caso seja FALSE a pesquisa foi digitada um numero de CGM 
function js_pesquisaz01_numcgm(mostra)
{
  if(mostra==true)
  {
    js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?funcao_js=parent.js_mostranumcgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }
  else
  {
     if(document.form1.z01_numcgm.value != '')
     {
        js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostranumcgm','Pesquisa',false);
     }
     else
     {
       document.form1.z01_nome.value = "";
     }
  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME.
//Caso a função js_pesquisaz01_numcgm tenha sido FALSE.
//Se a função não encontrar um NUMCGM digitado retorna um erro para o formulario.
function js_mostranumcgm(erro,chave)
{
  document.form1.z01_nome.value = chave;
  if(erro==true)
  { 
    document.form1.z01_numcgm.value = ''; 
    document.form1.z01_numcgm.focus(); 
  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME
//Caso a função js_pesquisaz01_numcgm tenha sido TRUE.
function js_mostranumcgm1(chave1,chave2)
{
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  func_nome.hide();
}

//---------------------------------------------------------------------------------------------------------------------------

</script>
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_edu_relatmodel_classe.php");
require_once("libs/db_utils.php");
$iEscola          = db_getsession("DB_coddepto");
$clEduRelatmodel  = new cl_edu_relatmodel();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post">
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td valign="top">
   <br>
   <fieldset style="width:95%"><legend><b>Relatório Lista Auxiliar</b></legend>
   <table border="0" align="left">
    <tr>
    <td>
      <b>Selecione o Calendário:</b><br>
      <select name="grupo"  style="font-size:9px;width:250px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
       $sql       .= "       FROM calendario ";
       $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
       $sql       .= "       WHERE ed38_i_escola = $iEscola ";
       $sql       .= "       AND ed52_c_passivo = 'N' ";
       $sql       .= "       ORDER BY ed52_i_ano DESC";
       $sql_result = pg_query($sql);
       while ($row = pg_fetch_array($sql_result)) {
       	
         $calendario  = $row["ed52_i_codigo"];
         $desc_curso = $row["ed52_c_descr"];
         ?>
         <option value="<?=$calendario;?>" <?=$calendario==@$curso?"selected":""?>><?=$desc_curso;?></option>
         <?
         
       }
       #Popula o segundo combo de acordo com a escolha no primeiro
       ?>
      </select>
     </td>
     <td>
     </td>
     </tr>
     <tr>
     <td>
      <b>Data da Votação:</b>
      <?db_inputdata('datavotacao',@$data_votacao_dia,@$data_votacao_mes,@$data_votacao_ano,true,'text',1,"")?>      
     </td>
    </tr>  
     <tr>
     <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Processar" Onclick="js_pesquisa();"></td>
    </tr>
   </table>
   </fieldset>
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_pesquisa(curso) {

  if (document.form1.grupo.value == "") {
		  
	alert("Informe o Calendário!");
	return false;
			    
  }
	  
  if (document.form1.datavotacao.value == "") {
		  
	alert("Informe a data para votação!");
	return false;
		    
  }
  sCalendario = document.form1.grupo.value;
  sDataVotacao = document.form1.datavotacao.value;
    jan = window.open('edu2_listaauxvotacao002.php?calendario='+sCalendario+'&iData='+sDataVotacao,
                      '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                     );
    jan.moveTo(0,0);  

}


function js_botao(valor) {
	
  if (valor != "") {
    document.form1.pesquisar.disabled = false;
  } else {
    document.form1.pesquisar.disabled = true;
  }
}
</script>
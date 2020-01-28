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
include("classes/db_solicita_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcproc_classe.php");
include("classes/db_pcparam_classe.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clsolicita = new cl_solicita;
$clsolicitem = new cl_solicitem;
$clpcproc= new cl_pcproc;
$clpcparam = new cl_pcparam;
$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_data");
$clrotulo->label("pc10_resumo");
$clrotulo->label("pc80_codproc");
$clrotulo->label("pc80_resumo");
$clrotulo->label("descrdepto");
$clrotulo->label("nome");
$clrotulo->label("l20_codigo");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_submit(codsol){
	parent.itens.js_submit_form();
	parent.itens.document.form1.codsol.value=codsol;
	
	parent.itens.document.form1.submit();
	document.form1.submit();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">
<center>
<table border="0" cellspacing="1" cellpadding="0" height='10%'>
  <tr>
     <td><b>Solicitação:</b></td>
    <td>
    
    <?
    
    $result_solicita=$clsolicita->sql_record($clsolicita->sql_query_prot(null,"distinct pc10_numero","pc10_numero","pc49_solicitem is not null and p58_coddepto = ".db_getsession("DB_coddepto")." and p61_codandam is null and p63_codproc is null"));    
    if (isset($codsol)&&$codsol!=""){
      $couni="codsol";
	  $$couni=$codsol;
    }else{
    	$nome="";
    	$descrdepto="";
    	$pc10_resumo="";
    	$pc10_data_dia="";
        $pc10_data_mes="";
        $pc10_data_ano="";
    }
	echo"<select name='codsol' id='codsol' onchange='js_submit(this.value);'>";
	echo "<option value=''>Selecione uma solicitação</option>\n";
	for($y=0;$y<$clsolicita->numrows;$y++){
 	  db_fieldsmemory($result_solicita,$y);
 	  echo "<option value=$pc10_numero ".(isset($couni)?($$couni==$pc10_numero?"selected":""):"")." >$pc10_numero</option>\n";
   	}
    echo " </select>";
	    //  
    ?>
    </td>
    <?
    if (isset($codsol)&&$codsol!=""){
      $result_solicita=$clsolicita->sql_record($clsolicita->sql_query($codsol));
      db_fieldsmemory($result_solicita,0);
    }
    ?>
    <td align="right" nowrap title="<?=@$Tnome?>">
      <strong>Usuário:</strong>      
    </td>
    <td align="left" nowrap>
    <?
      db_input('nome',41,$Inome,true,'text',3);
    ?>
    </td>
    
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tpc10_data?>">
      <strong>Data: </strong>      
    </td>
    <td align="left" nowrap>
    <?
      db_input('pc10_data_dia',2,0,true,'text',3);
      db_input('pc10_data_mes',2,0,true,'text',3);
      db_input('pc10_data_ano',4,0,true,'text',3);
    ?>
    </td>
    <td align="right" nowrap title="<?=@$Tdescrdepto?>">
      <strong>Departamento: </strong>      
    </td>
    <td align="left" nowrap>
    <?
      db_input('descrdepto',41,$Idescrdepto,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tpc10_resumo?>">
      <strong>Resumo: </strong>
    </td>
    <td colspan="3" nowrap>
    <?
      db_textarea('pc10_resumo',2,73,$Ipc10_resumo,true,'text',3,"")     
    ?>
    </td>
  </tr>
</table>
</center>
</form>
</body>
</html>
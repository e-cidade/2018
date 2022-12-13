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
include("dbforms/db_funcoes.php");
include("classes/db_solicita_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcproc_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_solandam_classe.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clsolandam = new cl_solandam;
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
	parent.itens.document.form1.tipo.value=document.form1.tipo.value;	
	parent.itens.document.form1.submit();
	document.form1.submit();
}
function js_tipo(){	
	parent.itens.document.form1.codsol.value="";
	parent.itens.document.form1.submit();
	document.form1.codsol.value="";
	document.form1.submit();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post">
<center>
<table border="0" cellspacing="1" cellpadding="0" height='10%'>

  <tr>
     <td><b><?
     if (isset($tipo)&&$tipo=="P"){
     	?>
     	<select name="tipo" Onchange='js_tipo();' ><option value='P'>Processo de Compra</option>\n<option value='S'>Solicitação</option>\n</select>:</b></td>
     	<?     
     }else{
     ?>    
     <select name="tipo" Onchange='js_tipo();' ><option value='S'>Solicitação</option>\n<option value='P'>Processo de Compra</option>\n</select>:</b></td>
     <?
     }
     ?>
    <td>    
    <?  if (isset($tipo)&&$tipo=="P"){    	
    	$result_solicita=$clsolicita->sql_record($clsolicita->sql_query_andsol("distinct pc81_codproc","where p64_codtran is not null and y.pc47_pctipoandam = 1 or y.pc47_pctipoandam = 7 or y.pc47_pctipoandam = 3 or y.pc47_pctipoandam = 5 or y.pc47_pctipoandam = 4 and pc81_codproc is not null and y.pc43_depto=".db_getsession("DB_coddepto")." order by 1 desc"));
    	    
    if (isset($codsol)&&$codsol!=""){
      $couni="codsol";
	  $$couni=$codsol;
	  
    }else{
    	$nome="";
    	$descrdepto="";
    	$resumo="";
    	$data_dia="";
        $data_mes="";
        $data_ano="";
    }
	echo"<select name='codsol' id='codsol' onchange='js_submit(this.value);'>";
	echo "<option value=''>Selecione um Processo de Compra</option>\n";
	for($y=0;$y<$clsolicita->numrows;$y++){
 	  db_fieldsmemory($result_solicita,$y);
	  echo "<option value=$pc81_codproc ".(isset($couni)?($$couni==$pc81_codproc?"selected":""):"")." >$pc81_codproc</option>\n";
   	}
    echo " </select>";
	    //  
    ?>
    </td>
    <?
    if (isset($codsol)&&$codsol!=""){      
      $result_pcproc=$clpcproc->sql_record($clpcproc->sql_query($codsol));
      if ($clpcproc->numrows>0){      
      db_fieldsmemory($result_pcproc,0);
      $resumo=$pc80_resumo;
      $data=$pc80_data;
      $data_dia=$pc80_data_dia;
      $data_mes=$pc80_data_mes;
      $data_ano=$pc80_data_ano;
      }else{
      	$nome="";
    	$descrdepto="";
    	$resumo="";
    	$data_dia="";
        $data_mes="";
        $data_ano="";
      }
      
    }
    }else{  
    $result_solicita=$clsolicita->sql_record($clsolicita->sql_query_andsol("distinct pc10_numero","where p64_codtran is not null and y.pc47_pctipoandam in (1,2,3,5,4,6)and y.pc43_depto=".db_getsession("DB_coddepto")." order by 1 desc"));    
    if (isset($codsol)&&$codsol!=""){
      $couni="codsol";
	  $$couni=$codsol;
    }else{
    	$nome="";
    	$descrdepto="";
    	$resumo="";
    	$data_dia="";
        $data_mes="";
        $data_ano="";
    }
	echo"<select name='codsol' id='codsol' onchange='js_submit(this.value);'>";
	echo "<option value=''>Selecione uma Solicitação</option>\n";
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
      $resumo=$pc10_resumo;
      $data=$pc10_data;
      $data_dia=$pc10_data_dia;
      $data_mes=$pc10_data_mes;
      $data_ano=$pc10_data_ano;
    }
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
    <td align="right" nowrap title="<?=@$Tdata?>">
      <strong>Data: </strong>      
    </td>
    <td align="left" nowrap>
    <?
      db_input('data_dia',2,0,true,'text',3);
      db_input('data_mes',2,0,true,'text',3);
      db_input('data_ano',4,0,true,'text',3);
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
      db_textarea('resumo',2,73,$Ipc10_resumo,true,'text',3,"")     
    ?>
    </td>
  </tr>  
</table>
</center>
</form>
</body>
</html>
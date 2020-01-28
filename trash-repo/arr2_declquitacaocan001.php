<?php
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
include("dbforms/db_funcoes.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include ("libs/db_app.utils.php");

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 

  db_app::load('scripts.js');
  db_app::load('estilos.css');

?>
<script type="text/javascript">
function js_imprimir(){

  var dDataInicial = '';
  var dDataFinal   = '';
  var sOrigem      = document.form1.origem.value;
  var sOrdenar     = document.form1.ordenar.value;

  if(document.form1.datainicial.value == '') {
	  alert('Data Inicial não informada.');
	  return false;
  }else {
	  dDataInicial = document.form1.datainicial_ano.value+'-'+document.form1.datainicial_mes.value+'-'+document.form1.datainicial_dia.value;
  }  
  
  if(document.form1.datafinal.value == '') {
	  alert('Data Final não informada.')
	  return false;
  } else {
	  dDataFinal   = document.form1.datafinal_ano.value+'-'+document.form1.datafinal_mes.value+'-'+document.form1.datafinal_dia .value;
  }
  
  if(sOrigem    == '') {
    alert('Origem não informada.');
    return false;
	}
  if(sOrdenar == '') {
    alert('Ordenação do relatório não informada.');
    return false;
	}
	  
  jan = window.open('arr2_declquitacaocan002.php?origem='+sOrigem+'&datainicial='+dDataInicial+'&datafinal='+dDataFinal+'&ordenar='+sOrdenar, '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
</head>
<body bgcolor=#CCCCCC>
<form name="form1" method="post" style="margin-top: 40px;">

  <fieldset style="width: 500px; margin: 0 auto">
  
    <legend><strong>Declara&ccedil;&atilde;o Quita&ccedil;&atilde;o Geral</strong></legend>
    
    <table width="450" align="center">
    
			<tr>
			  
			  <td title="Data Inicial" >
          <strong>Data Inicial:</strong>
        </td>
        
			  <td>
			  <? 
          db_inputdata('datainicial', null, null, null, true, 'text', 1)
			  ?>
			  </td>
			  
			  <td title="Data Final">
          <strong>Data Final:</strong>
        </td>
        
			  <td>
			  <? 
          db_inputdata('datafinal', null, null, null, true, 'text', 1)
        ?>
        </td>
        
			</tr>
			
			<tr>
			
			  <td align="center" colspan="4" title="Origem da Decla&ccedil;&atilde;o">
          <strong>Origem:</strong>
				  <?
				    $aOrigens = array(''=>'Selecione', 'somentecgm'=>'Somente CGM', 'cgm'=>'CGM Geral', 'matric'=>'Matr&iacute;cula', 'inscr'=>'Inscri&ccedil;&atilde;o');
				    db_select('origem', $aOrigens, true, 1, 'style="width: 150px"');
				  ?>
			  </td>
			  
			</tr>
			
			<tr>
			
			  <td align="center" colspan="4" title="Ordenar por...">
          <strong>Ordenar</strong>
          <?
            $aOrdenar = array(''=>'Selecione', 'datacancelamento'=>'Data Cancelamento', 'declaracao'=>'Declara&ccedil;&atilde;o');
            db_select('ordenar', $aOrdenar, true, 1, 'style="width: 150px"');
          ?>  
			  </td>
			  
			</tr>
			
			<tr>
        <td colspan="4" align="center"><br/>
          <input type="submit" name="imprimir" value="Imprimir" onclick="return js_imprimir();"/>
        </td>
			</tr>
			
    </table>
    
  </fieldset>
  
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
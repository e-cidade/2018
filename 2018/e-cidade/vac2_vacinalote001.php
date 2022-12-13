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
require("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
$oDaoVacVacinaMaterial = db_utils::getdao('vac_vacinamaterial');
$db_opcao           = 1;
$db_botao           = true;
$iDepartamento      = db_getsession("DB_coddepto");
$dataini_dia        = date("d",db_getsession("DB_datausu"));
$dataini_mes        = date("m",db_getsession("DB_datausu"));
$dataini_ano        = date("Y",db_getsession("DB_datausu"));
$datafim_dia        = $dataini_dia;
$datafim_mes        = $dataini_mes;
$datafim_ano        = $dataini_ano;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("prototype.js, datagrid.widget.js, strings.js, webseller.js");
db_app::load("scripts.js, grid.style.css");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <br><br>
    <fieldset style='width: 70%;'> <legend><b>Relatório de Vacinas por Lote</b></legend>
    <form name="form1" method="post" action="">
    <center>
    <table border="0">
    <tr>
      <td><b>Período</b></td>
      <td nowrap >
      <? db_inputdata('dataini', @$dataini_dia, @$dataini_mes, @$dataini_ano, true, 'text', $db_opcao);?>
      Á
      <? db_inputdata('datafim', @$datafim_dia, @$datafim_mes, @$datafim_ano, true, 'text', $db_opcao);?></td>
    </tr>
    <tr>
      <td nowrap>
        <b>Lote</b>
      </td>
      <td>
        <?
          $x       = array();
          $sSql    = $oDaoVacVacinaMaterial->sql_query_vacina(null, 
                                                           "m77_sequencial,m77_lote||' - '||m60_descr as m77_lote",
                                                           'm77_sequencial desc',
                                                           "");
          $rsDados = $oDaoVacVacinaMaterial->sql_record($sSql);
          $x[0] = 'Todos';
          for ($iX = 0; $iX < $oDaoVacVacinaMaterial->numrows; $iX++) {

            $oDados                    = db_utils::fieldsmemory($rsDados,$iX);
            $x[$oDados->m77_sequencial] = $oDados->m77_lote;

          }
          db_select('lote',$x,true,$db_opcao,"");
       ?>
      </td>
    </tr>
    <tr>
      <td colspan="2"> <input type="checkbox" id="unidade" name="unidade" >Acrescentar dados das unidades </td>
    </tr>
</table>
</center>
<input name = "inprimir" type = "button" id = "inprimir" value = "Gerar Relatorio" onClick = "js_imprimir()">
<input name = "limpar" type = "button" id = "limpar" value = "Limpar" onClick = "js_limpar(1)">
</form>
</fieldset>
    </center>
  </td>
  </tr>
</table>
</center>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>
js_tabulacaoforms("form1", "dataini", true, 1, "dataini", true);
js_limpar(0)
function js_imprimir() {

	sErro = '';
  if ($F('dataini') == '') { 
    sErro = 'Datainicial não informada ';
  } else if ($F('datafim') == '') {
    sErro = 'Data final não informada ';
  }
  if (document.getElementById('unidade').checked == true) {
    iUnid = 1;
  } else {
	  iUnid = 2;
	}
  sStr = '?dDataini='+$F('dataini')+'&dDatafim='+$F('datafim')+'&iLote='+$F('lote')+'&iUnidades='+iUnid;
  aLote = $('lote').options[$('lote').selectedIndex].text.split(' - ');
  sStr+= '&sLote='+aLote[0];
  jan  = window.open('vac2_vacinalote002.php'+sStr,'',
		                 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}
function js_limpar(ini) {
	if (ini != 0) {
		
    $('dataini').value = '';
    $('datafim').value = '';
    
	}
  $('lote').selectedIndex = 0;
  
}
</script>
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<br><br><br>
<form name="form1" action="">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <fieldset style='width: 75%;'> <legend><b>Relatório CBO</b></legend>

        <center>
          <table border="0">
            <tr>
              <td nowrap title="<?=@$Ttf12_i_codigo?>">
                <b>Filtrar por:</b>
              </td>
              <td>
                <?
                $aX = array('1'=>'PAB', '2'=>'NÃO PAB', '3'=>'AMBOS');
                db_select('pab', $aX, true, 1, '');
                ?>
              </td>
              <td> 
                <b>Mostrar Profissionais:</b>
              </td>
              <td>
                <?
                $aX = array('1'=>'SIM', '2'=>'NÃO');
                db_select('mostrarProfissionais', $aX, true, 1, '');
                ?>
              </td>
            </tr>
            <tr>
              <td> 
                <b>Período:</b>
              </td>
              <td colspan="3">
                <?
                db_inputdata('dataIni', @$dataIni[0], @$dataIni[1], @$dataIni[2], true, 'text', 1, '');
                ?>
                <b> Até: </b>
                <?
                db_inputdata('dataFim', @$dataFim[0], @$dataFim[1], @$dataFim[2], true, 'text', 1, '');
                ?>
              </td>
            </tr>
          </table>
          <br>
          <input type="button" id="imprimir" value="Imprimir" onclick="js_mandaDados();">
        </center>

      </fieldset>
    </center>
	</td>
  </tr>
</table>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>

function js_mandaDados() {

  if (!js_validaData()) {
    return false;
  }

  sChave  = 'pab='+$F('pab')+'&mostrarProfissional='+$F('mostrarProfissionais');
  sChave += '&dataIni='+$F('dataIni')+'&dataFim='+$F('dataFim');

  oJan    = window.open('sau2_relatoriocbo002.php?'+sChave, '', 'width='+(screen.availWidth - 5)+',height='+
                     (screen.availHeight - 40)+',scrollbars=1,location=0 ');
  oJan.moveTo(0, 0);

}

function js_validaData() {


  if ($F('dataIni') == '' || $F('dataFim') == '') {

    alert('Preencha o período.');
    return false;

  }

	tInicio = new Date($F('dataIni').substring(6,10),
	                   $F('dataIni').substring(3,5),
	                   $F('dataIni').substring(0,2));
	tFim    = new Date($F('dataFim').substring(6,10),
	                   $F('dataFim').substring(3,5),
	                   $F('dataFim').substring(0,2));
	
	if(tInicio > tFim) {

		alert('A data de início não pode ser maior que a data final.');
		$('dataFim').value = '';
		$('dataFim').focus();
		return false;

	}

  return true;

}

</script>

</body>
</html>
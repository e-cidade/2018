<?
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
$lblRelatorio = "Processar";

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script src="scripts/prototype.js"></script>
<script src="scripts/scripts.js"></script>
<script src="scripts/strings.js"></script>
<?
db_app::load("estilos.css");
?>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
?>
<form id="form1" name="form1" method="get" action="pes3_relcomparativofolha002.php" target="Relatorio">
<center>

<table style="top: 60px; position: relative;">
  <tr>
    <td valign="middle" align="center">
    <fieldset>
      <legend><b>Filtros</b></legend>
      <table>
        <tr>
          <td><b>Ano/Mês Base:</b></td>
          <td>
            <? 
              db_input ("iAnoBase", "4", "", "", "text", 1, "");  
              echo "/";
              db_input ("iMesBase", "2", "", "", "text", 1, "");  
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Ano/Mês a Comparar:</b></td>
          <td>
            <? 
              db_input ("iAnoCompara", "4", "", "", "text", 1, "" );  
              echo "/";
              db_input ("iMesCompara", "2", "", "", "text", 1, "" );  
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Filtro:</b></td>
          <td>
            <? 
            
              $aFiltro = array( 
							                  ''           => 'Selecione',
							                  'todos'      => 'Todos',
							                  'diferentes' => 'Valores Diferentes'
							                );
							db_select("sFiltro", $aFiltro, "",1);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><b>Tipo de Folha</b></legend>
      <table>
        <tr>
	        <td>
	        
	        </td>
	        <td>
			          <? 
			            $aTiposFolha = array( 
			                                  'gerfsal' => 'Salário',
			                                  'gerffer' => 'Férias',
			                                  'gerfres' => 'Rescisão',
			                                  'gerfs13' => 'Saldo do 13º',
			                                  'gerfcom' => 'Complementar'
			                                );
                  db_multiploselect("valor","descr", "", "", $aTiposFolha, "", 6, 250, "", "", true, "");

			          ?>

          </td>
        </tr>
      </table>
    </fieldset>
   </td>
  </tr>
</table>
<p>&nbsp;
<p>&nbsp;
<p>&nbsp;

<?
	db_input ( "lblRelatorio", "20", "", "", "submit", 1, "onClick='return js_abrejanela();'" );
?>

</center>
</form>
</body>
</html>
<script>
/**
 */
function js_abrejanela(){
  
  $('iAnoBase').style.backgroundColor    = "#FFFFFF";
  $('iAnoCompara').style.backgroundColor = "#FFFFFF";
  $('iMesBase').style.backgroundColor    = "#FFFFFF";
  $('iMesCompara').style.backgroundColor = "#FFFFFF";
  $('sFiltro').style.backgroundColor     = "#FFFFFF";
  $('objeto2').style.border              = "1px solid #999999";
   
  if ($('iAnoBase').getValue() == "" || $('iAnoBase').getValue() == 0) { 
    $('iAnoBase').style.backgroundColor    = "#99A9AE";
    $('iAnoBase').focus();
    alert("Ano Base não Informado");
    return false;
  }
  if ($('iMesBase').getValue() == "") { 
    $('iMesBase').style.backgroundColor    = "#99A9AE";
    $('iMesBase').focus();
    alert("Mês Base não Informado");
    return false;
  }
  if ($('iAnoCompara').getValue() == "" || $('iAnoCompara').getValue() == 0) { 
    $('iAnoCompara').style.backgroundColor    = "#99A9AE";
    $('iAnoCompara').focus();
    alert("Ano a Comparar não Informado");
    return false;
  }
  if ($('iMesCompara').getValue() == "") { 
    $('iMesCompara').style.backgroundColor    = "#99A9AE";
    $('iMesCompara').focus();
    alert("Mês a Comparar não Informado");
    return false;
  }
  if ($('iMesBase').getValue() > 12 || $('iMesBase').getValue() < 1 ) { 
    $('iMesBase').style.backgroundColor    = "#99A9AE";
    $('iMesBase').focus();
    alert("Mês Base Inválido");
    return false;
  }
  if ($('iMesCompara').getValue() > 12 || $('iMesCompara').getValue() < 1) { 
    $('iMesCompara').style.backgroundColor    = "#99A9AE";
    $('iMesCompara').focus();
    alert("Mês a Comparar Inválido");
    return false;
  }
  if ($('sFiltro').getValue() == "") { 
    $('sFiltro').style.backgroundColor    = "#99A9AE";
    $('sFiltro').focus();
    alert("Nenhum Filtro Selecionado");
    return false;
  }
  if ($('objeto2').options.length == 0) { 
    $('objeto2').style.border             = "3px solid #99A9AE";
    $('objeto2').focus();
    alert("Selecione um tipo de Folha");
    return false;
  } else {
    for(iI in $('objeto2').options){
      $('objeto2').options[iI].selected = true;
    }
  }

     oJanela = window.open("","Relatorio",'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     oJanela.moveTo(0,0);
     this.form.submit();
     return false;
}
</script>
<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cidadaobeneficio_classe.php");

$oRotulo = new rotulocampo();
$oRotulo->label("as08_tipobeneficio");
$oRotulo->label("as08_sequencial");

$oDaoBeneficio = db_utils::getDao('cidadaobeneficio');
$sCampos       = "distinct upper(trim(as08_tipobeneficio)) as as08_tipobeneficio";
$sSqlBeneficio = $oDaoBeneficio->sql_query_file(null, $sCampos, 'as08_tipobeneficio');
$rsBeneficio   = $oDaoBeneficio->sql_record($sSqlBeneficio);

$aMesCompetencia = array(1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8, 9=>9, 10=>10, 11=>11, 12=>12);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, prototype.js, strings.js, dates.js");
db_app::load("estilos.css");
?>
</head>
<body style="margin-top: 25px; background-color: #CCCCCC;">
<div>
	<center>
    <form action="">
      <fieldset style="width: 400px;">
        <legend><b>Filtros do Relatório</b></legend>
        <table>
        	<tr>
        		<td nowrap="nowrap"><b>Benefício:</b></td>
            <td nowrap="nowrap" colspan="2">
            <?
             db_selectrecord('as08_tipobeneficio', $rsBeneficio, true, 1);
            ?>
            </td>
        	</tr>
        	<tr>
        		<td nowrap="nowrap"><b>Mês/Ano de Competência:</b></td>
        		<td nowrap="nowrap">
        			<?php 
              	db_select('iMesCompetencia', $aMesCompetencia, true, 1, "");
              ?> <b>/</b>
              <?php
        				db_input("anoCompetencia", 10, '', true, 'text', 1, '', '', '', '', 4);
        			?>
        		</td>
        	</tr>
        </table>
      </fieldset>
      <input type="button" value="Imprimir" name='imprimir' id='btnProcessar'>
    </form>
  </center>
</div>
</body>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

$('as08_tipobeneficiodescr').style.display = 'none';

$("btnProcessar").observe("click", function() {

  var sLocation  = "soc2_familiabeneficio002.php?";
  sLocation += "sBeneficio="+$F('as08_tipobeneficio');
  sLocation += "&iMesCompetencia="+$F('iMesCompetencia');
  sLocation += "&iAnoCompetencia="+$F('anoCompetencia');
  jan = window.open(sLocation,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);  
});

</script>
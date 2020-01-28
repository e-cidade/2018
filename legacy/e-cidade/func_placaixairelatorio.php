<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_placaixa_classe.php"));

$oGet   = db_utils::postmemory($_GET);
$sWhere = '';
if ($oGet->dataini != null  && $oGet->datafim == null) {

	$sWhere .= " and {$oGet->sFiltro} >= '".implode("-", array_reverse(explode("/",$oGet->dataini)))."'";
} else if ($oGet->dataini != null  && $oGet->datafim != null) {

	$sWhere .= " and {$oGet->sFiltro} between '".implode("-", array_reverse(explode("/",$oGet->dataini)))."'";
	$sWhere .= " and '".implode("-", array_reverse(explode("/",$oGet->datafim)))."'";

}
$funcao_js = "js_showReport|k80_codpla";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="estilos.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" >
	<table height="100%" border="0" align="center">
		<tr>
			<td align="center" valign="top">
				<?
					$where = "";
					$ano   = db_getsession("DB_anousu");
					if(isset($campos)==false){
        		if(file_exists("funcoes/db_func_placaixa.php")==true){
          		include(modification("funcoes/db_func_placaixa.php"));
        		}else{
        			$campos = "placaixa.*";
        		}
      		}

          $sSql = "select k80_codpla, k80_data, k80_dtaut, k81_valor                                                 ";
		      $sSql.= "  from (                                                                                          ";
		      $sSql.= "        select distinct k80_codpla, k80_data, k80_dtaut, sum(k81_valor) as k81_valor              ";
		      $sSql.= "          from placaixa                                                                           ";

		      if (isset($Modulo) and $Modulo == 'Pessoal') {

			      $sSql.= "             inner join placaixarec                   on k81_codpla        = k80_codpla         ";

	        } else {
			      $sSql .= "            left join placaixarec                    on k81_codpla        = k80_codpla         ";
					}

					$sSql.= "               inner join db_config                     on db_config.codigo  = placaixa.k80_instit";
		      $sSql.= "         where k80_instit = ".db_getsession("DB_instit")." {$sWhere }                             ";
          $sSql.= "  group by k80_codpla, k80_data, k80_dtaut                                                        ";
          $sSql.= "  order by k80_codpla desc) as x                                                                  ";

          db_lovrot($sSql,15,"()","",$funcao_js);
      ?>
			</td>
		</tr>
	</table>
</body>
</html>
<script>
function js_showReport(iPlanilha) {
  jan = window.open('cai2_emiteplanilha002.php?codpla='+iPlanilha,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
}
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>

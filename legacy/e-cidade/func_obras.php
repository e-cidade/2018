<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

require_once("classes/db_obras_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if (isset($chave_ob01_nomeobra)) {
  $chave_ob01_nomeobra = stripslashes($chave_ob01_nomeobra);
}

if (isset($ob06_setor)) {
  $ob06_setor = stripslashes($ob06_setor);
}

if (isset($ob06_quadra)) {
  $ob06_quadra = stripslashes($ob06_quadra);
}

if (isset($ob06_lote)) {
  $ob06_lote = stripslashes($ob06_lote);
}

$clobras = new cl_obras;
$clobras->rotulo->label("ob01_codobra");
$clobras->rotulo->label("ob01_nomeobra");

$clRotulo 		 = new rotulocampo;
$clRotulo->label("j01_matric");
$clRotulo->label("z01_nome");
$clRotulo->label("ob06_setor");
$clRotulo->label("ob06_quadra");
$clRotulo->label("ob06_lote");

$oGet    = db_utils::postMemory($_GET);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC">
<table align="center">
  <tr>
    <td>
        <table border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td nowrap title="<?=$Tob01_codobra?>">
              <?=$Lob01_codobra?>
            </td>
            <td nowrap>
              <?
		       db_input("ob01_codobra",10,$Iob01_codobra,true,"text",4,"","chave_ob01_codobra");
		       ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tob01_nomeobra?>">
              <?=$Lob01_nomeobra?>
            </td>
            <td nowrap>
              <?
		       db_input("ob01_nomeobra",55,$Iob01_nomeobra,true,"text",4,"","chave_ob01_nomeobra");
		       ?>
            </td>
          </tr>

          <tr>
          	<td title="<?=@$Tj01_matric?>">
          	  <?=$Lj01_matric?>
	          </td>
	          <td>
	          	<?
	          		db_input('j01_matric', 10, $Ij01_matric, true, 'text', 1)
	          	?>
          	</td>
          </tr>

          <tr>
          	<td title="<?=@$Tob06_setor?>">
          	  <strong>Setor/Quadra/Lote: </strong>
	          </td>
	          <td>
	          <?
	            db_input('ob06_setor',10,$Iob06_setor,true,'text',1,"")
	          ?>
	          /
	          <?
	            db_input('ob06_quadra',10,$Iob06_quadra,true,'text',1,"")
	          ?>
	          /
	          <?
	            db_input('ob06_lote',10,$Iob06_lote,true,'text',1,"")
	          ?>
          	</td>
          </tr>

          <tr>
          	<td title="Processo possui alvará.">
          	  <strong>Possui Alvará: </strong>
	          </td>
	          <td>
	          	<?
	          		db_select('lPossuiAlvara', array(''=>'Todos', 'S'=>'Sim', 'N'=>'Não'), true, 1, "style='width:92px;'");
	          	?>
          	</td>
          </tr>

          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onclick="return js_validar(arguments[0]);">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_obras.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      if (isset($chave_ob01_nomeobra)) {
        $chave_ob01_nomeobra = addslashes($chave_ob01_nomeobra);
      }

      if (isset($ob06_setor)) {
        $ob06_setor = addslashes($ob06_setor);
      }

      if (isset($ob06_quadra)) {
        $ob06_quadra = addslashes($ob06_quadra);
      }

      if (isset($ob06_lote)) {
        $ob06_lote = addslashes($ob06_lote);
      }

      if ( !isset($pesquisa_chave) ) {

      	if ( !isset($campos) ) {

      		if ( file_exists("funcoes/db_func_obras.php") ){
      			include("funcoes/db_func_obras.php");
      		}else{
      			$campos = "obras.*";
      		}
      	}

      	$aWherePesquisa  =  array();

      	if ( !empty($lPossuiAlvara) ) {

      		$aWherePesquisa[] = "obrasalvara.ob04_codobra is " . ($lPossuiAlvara == 'S' ? 'not null' : 'null') ;

      	}

      	if ( isset($oGet->lOrigemConsulta) ) {

      		$ob01_codobra    = trim($oGet->ob01_codobra);
      		$j01_matric      = trim($oGet->j01_matric);
      		$ob03_numcgm     = trim($oGet->ob03_numcgm);

      		if ( !empty($j01_matric) ) {
      			$aWherePesquisa[]   = "j01_matric  = $j01_matric";
      		}
      		if ( !empty($ob03_numcgm) ) {
      			$aWherePesquisa[]   = "ob03_numcgm = $ob03_numcgm";
      		}
      		if ( !empty($ob01_codobra) ) {
      			$aWherePesquisa     = array(" ob01_codobra = $ob01_codobra");
      		}


      		$sSqlPesquisa = $clobras->sql_query_consultaObras("",$campos,"ob01_nomeobra",implode(" and ", $aWherePesquisa) );

      	} else {


      		if ( isset($chave_ob01_codobra) && (trim($chave_ob01_codobra)!="") ) {

      			$aWherePesquisa [] = " ob01_codobra = '$chave_ob01_codobra' ";

      			$sSqlPesquisa = $clobras->sql_query_consultaObras("", $campos, "ob01_codobra", implode(" and ", $aWherePesquisa));

      		} elseif ( isset($chave_ob01_nomeobra) && (trim($chave_ob01_nomeobra) != "" ) ) {

      			$aWherePesquisa [] = " ob01_nomeobra like '$chave_ob01_nomeobra%' ";

      			$sSqlPesquisa = $clobras->sql_query_consultaObras("",$campos,"ob01_nomeobra", implode(" and ", $aWherePesquisa));

      		} elseif( !empty($j01_matric) ) {

      			$aWherePesquisa [] = "ob24_iptubase = {$j01_matric}";

      			$sSqlPesquisa = $clobras->sql_query_consultaObras("", $campos, "ob24_iptubase", implode(" and ", $aWherePesquisa));


      		} elseif( !empty($ob06_setor) || !empty($ob06_quadra) || !empty($ob06_quadra)){

      			if(!empty($ob06_setor)) {
      				$aWherePesquisa [] = "ob06_setor  = '{$ob06_setor}'";
      			}
      			if(!empty($ob06_quadra)) {
      				$aWherePesquisa [] = "ob06_quadra = '{$ob06_quadra}'";
      			}
      			if(!empty($ob06_lote)) {
      				$aWherePesquisa [] = "ob06_lote   = '{$ob06_lote}'";
      			}

      			$sSqlPesquisa = $clobras->sql_query_consultaObras("",$campos,"ob01_codobra",implode(" and ", $aWherePesquisa) );

      		} else {

      		  $sSqlPesquisa = $clobras->sql_query_consultaObras("",$campos,"ob01_codobra", implode(" and ", $aWherePesquisa));

      		}

      	}
      	$repassa = array();

      	if ( isset($chave_ob01_nomeobra) ) {

      		$repassa = array("chave_ob01_codobra"  => $chave_ob01_codobra,
      		                 "chave_ob01_nomeobra" => $chave_ob01_nomeobra);
      	}

      	db_lovrot($sSqlPesquisa, 15, "()", "", $funcao_js, "", "NoMe", $repassa);

      } else {

      	if ( !empty($pesquisa_chave) ) {

      		$result = $clobras->sql_record($clobras->sql_query($pesquisa_chave));

      		if ( $clobras->numrows != 0) {

      			db_fieldsmemory($result,0);
      			echo "<script>".$funcao_js."('$ob01_nomeobra',false);</script>";
      		} else {
      			echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
      		}

      	} else {
      		echo "<script>".$funcao_js."('',false);</script>";
      	}
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>

function js_validar(evt) {

  $('chave_ob01_codobra').onkeyup = evt;
  $('j01_matric').onkeyup         = evt;
  return true;
}

js_tabulacaoforms("form2","chave_ob01_nomeobra",true,1,"chave_ob01_nomeobra",true);
</script>
<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("classes/db_averbacao_classe.php");
require_once("classes/db_setorloc_classe.php");
require_once("libs/db_app.utils.php");

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$claverbacao = new cl_averbacao;
$claverbacao->rotulo->label("j75_codigo");
$claverbacao->rotulo->label("j75_codigo");

$clsetorloc = new cl_setorloc();
$rsSetorLoc = $clsetorloc->sql_record($clsetorloc->sql_query_file(null, 'j05_codigoproprio, j05_descr', 'j05_codigoproprio, j05_descr'));

$clrotulo = new rotulocampo;
$clrotulo->label("j06_setorloc");
$clrotulo->label("j06_quadraloc");
$clrotulo->label("j06_lote");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
	db_app::load('scripts.js, prototype.js, strings.js, dbcomboBox.widget.js, estilos.css');
?>
</head>
<body class="body-default">
<table height="100%" width="700" border="0"  align="center" cellspacing="0">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="90%" border="0" align="center" cellspacing="0">
	     <form name="form1" id="form1" method="post" action="" onsubmit="js_append()">
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tj75_codigo?>">
              <?=$Lj75_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
		            db_input("j75_codigo",6,$Ij75_codigo,true,"text",4,"","chave_j75_codigo");

			          $yy = array('1'=>'Não Processado','2'=>'Processado');
                db_select('j75_situacao',$yy,true,1,"onChange='form1.submit();'");
		          ?>
            </td>
          </tr>

          <tr>
          	<td width="34%" align="right" nowrap title="<?=$Tj06_setorloc?>"><?=$Lj06_setorloc?></td>
          	<td>
          	<?php
           		db_selectrecord('j05_codigoproprio', $rsSetorLoc, true, 4, '', 'j05_codigoproprio', '', 'todos', 'js_carregaQuadra(this.value)');
          	?>
          	</td>
          </tr>

          <tr>
          	<td width="34%" align="right" nowrap title="<?=$Tj06_quadraloc?>"><?=$Lj06_quadraloc?></td>
          	<td id="cboquadraloc" width="66%" >

          	</td>
          </tr>

          <tr>
          	<td width="34%" align="right" nowrap title="<?=$Tj06_lote?>"><?=$Lj06_lote?></td>
          	<td id="cboloteloc" width="66%" >

          	</td>
          </tr>

          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_averbacao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
  	  if(isset($j75_situacao)){
  	  	$db_where = " j75_situacao = $j75_situacao ";
  	  }else{
  	  	$db_where = " j75_situacao = 1 ";
  	  }

      if(!isset($pesquisa_chave)){

        if(isset($campos)==false){

           if(file_exists("funcoes/db_func_averbacao.php")==true){
             include("funcoes/db_func_averbacao.php");
           }else{
             $campos = "averbacao.*";
           }
        }
        if(isset($chave_j75_codigo) && (trim($chave_j75_codigo)!="") ){

	         $sql = $claverbacao->sql_query_loteloc($chave_j75_codigo,$campos,"j75_codigo","j75_codigo = $chave_j75_codigo and $db_where ");

        }else if((isset($j05_codigoproprio) && ($j05_codigoproprio != '' )) or
                 (isset($j06_quadraloc)     && ($j06_quadraloc != ''))      or
                 (isset($j06_lote)          && ($j06_lote != ''))){

					$sql2 = " 1 = 1";

          if(isset($j05_codigoproprio) && ($j05_codigoproprio != 'todos' )) {
          	$sql2 .= " and j05_codigoproprio = '$j05_codigoproprio' ";
          }
          if(isset($j06_quadraloc) && ($j06_quadraloc != '')) {
          	$sql2 .= " and j06_quadraloc = '" . $j06_quadraloc . "'";
          }
          if(isset($j06_lote) && ($j06_lote != '')) {
          	$sql2 .= " and j06_lote = '" . $j06_lote . "'";
          }
          $sql = $claverbacao->sql_query_loteloc(null,$campos,"j75_codigo", $sql2);
        }else{
           $sql = $claverbacao->sql_query_loteloc("",$campos,"j75_codigo",$db_where);
        }

        $repassa = array();
        if(isset($chave_j75_codigo)){
          $repassa = array("chave_j75_codigo"=>$chave_j75_codigo,"chave_j75_codigo"=>$chave_j75_codigo);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $claverbacao->sql_record($claverbacao->sql_query(null,"*",null,"j75_codigo = $pesquisa_chave and $db_where"));
          if($claverbacao->numrows!=0){

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j75_codigo',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>

<script type="text/javascript">
js_tabulacaoforms("form1","chave_j75_codigo",true,1,"chave_j75_codigo",true);

var aOptions     = new Array();
aOptions[''] = 'Todos...';

function js_append() {

	$('form1').appendChild($('j06_quadraloc'));
	$('form1').appendChild($('j06_lote'));
}

function js_mostraQuadra(){

	cboQuadras          = new DBComboBox('j06_quadraloc', 'j06_quadraloc', aOptions, '180');
	cboQuadras.onChange = 'js_carregaLote(this.value)';
	cboQuadras.show(document.getElementById('cboquadraloc'));
}

function js_mostraLotes(){

	cboLotes = new DBComboBox('j06_lote', 'j06_lote', aOptions, '180');
	cboLotes.show(document.getElementById('cboloteloc'));
}

js_mostraQuadra();
js_mostraLotes();

function js_carregaQuadra(iCodSetor) {

	js_mostraQuadra();
	js_mostraLotes();

	var oParametro       = new Object();
	oParametro.sExec     = 'getQuadraSetor';
	oParametro.iCodSetor = iCodSetor;

	var oAjax = new Ajax.Request('func_iptubase.RPC.php',
	                           {
	                            method: 'POST',
	 						               parameters: 'json='+Object.toJSON(oParametro),
							                 onComplete: js_retornaQuadra
	                           });
}

function js_retornaQuadra(oAjax) {

	var oRetorno = eval("("+oAjax.responseText+")");
	var aQuadras = new Array();

	if(oRetorno.status == 1) {
		for(var i = 0; i < oRetorno.oQuadras.length; i++) {
			with(oRetorno.oQuadras[i]) {
				cboQuadras.addItem(j06_quadraloc, j06_quadraloc);
		  }
		}
	}
	js_carregaLote($F('j06_quadraloc'));

	return false;
}

function js_carregaLote(sQuadra) {

	js_mostraLotes();
	var oParametro = new Object();

	oParametro.sExec     = 'getLote';
	oParametro.sQuadra   = sQuadra;
	oParametro.iSetor    = $F('j05_codigoproprio');

	var oAjax = new Ajax.Request('func_iptubase.RPC.php',
	                           {
	                            method: 'POST',
								              parameters: 'json='+Object.toJSON(oParametro),
								              onComplete: js_retornaLote });
}

function js_retornaLote(oAjax) {

	var oRetorno = eval("("+oAjax.responseText+")");
	var aLotes   = new Array();
	aLotes['']   = 'Todos...';

	if(oRetorno.status == 1) {
		for(var i = 0; i < oRetorno.oLotes.length; i++) {
			with(oRetorno.oLotes[i]) {
				cboLotes.addItem(j06_lote, j06_lote);
		  }
		}
	}

	return false;
}

js_carregaQuadra($F('j05_codigoproprio'));
</script>
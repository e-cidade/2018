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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_lote_classe.php");
include("classes/db_setorloc_classe.php");
include("classes/db_loteloc_classe.php");
include("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllote = new cl_lote;
$cllote->rotulo->label("j34_idbql");
$cllote->rotulo->label("j34_setor");
$cllote->rotulo->label("j34_quadra");
$cllote->rotulo->label("j34_lote");

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
<? 
	db_app::load('scripts.js, prototype.js, strings.js, dbcomboBox.widget.js, estilos.css');
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr width="100%" > 
    <td height="63" align="center" valign="top">
        <table width="100%" border="0" align="center" cellspacing="0">
	     <form name="form1" id="form1" method="post" action=""  onsubmit="js_append()">
          <tr width="100%" > 
            <td width="50%" align="right" nowrap title="<?=$Tj34_idbql?>">
              <?=$Lj34_idbql?>
            </td>
            <td width="50%" align="left" nowrap> 
              <?
		       db_input("j34_idbql",6,$Ij34_idbql,true,"text",4,"","chave_j34_idbql");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="">
              <?=$Lj34_setor?> / <?=$Lj34_quadra?> / <?=$Lj34_lote?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
			       db_input("j34_setor",4,$Ij34_setor,true,"text",4,"","chave_j34_setor");
			       ?>
			       /
			       <?
			       db_input("j34_quadra",4,$Ij34_quadra,true,"text",4,"","chave_j34_quadra");
			       ?>
			       /
			       <?
			       db_input("j34_lote",4,$Ij34_lote,true,"text",4,"","chave_j34_lote");
			       ?>
            </td>
          </tr>
          
          <tr>
          	<td width="34%" align="right" nowrap title="<?=$Tj06_setorloc?>"><?=$Lj06_setorloc?></td>
          	<td>
          	<?
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
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $wsetor="";
      $wquadra="";  
      $wlote="";              
      $xx=""; 
      $sSetorloc  = "";
      $sQuadraloc = "";
      $sLoteloc   = "";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "lote.*";
        }
        if(isset($chave_j34_idbql) && (trim($chave_j34_idbql)!="") ){
	         $sql = $cllote->sql_query_loteloc($chave_j34_idbql,$campos,"j34_idbql");
        }else{ 
          if(isset($chave_j34_setor) && (trim($chave_j34_setor)!="") ){
           $chave_j34_setor = str_pad($chave_j34_setor,4,"0",STR_PAD_LEFT);
           $wsetor = " j34_setor = '$chave_j34_setor'";
           $xx=" and ";
          }
          if(isset($chave_j34_quadra) && (trim($chave_j34_quadra)!="") ){
           $chave_j34_quadra = str_pad($chave_j34_quadra,4,"0",STR_PAD_LEFT);
           $wquadra = $xx." j34_quadra = '$chave_j34_quadra'"; 
           $xx=" and ";
          }
          if(isset($chave_j34_lote) && (trim($chave_j34_lote)!="") ){
          	$chave_j34_lote = str_pad($chave_j34_lote,4,"0",STR_PAD_LEFT);
            $wlote = $xx." j34_lote = '$chave_j34_lote'";
            $xx=" and ";
          }
          if((isset($j05_codigoproprio) && ($j05_codigoproprio != '' )) or 
            (isset($j06_quadraloc)      && ($j06_quadraloc != ''))      or 
            (isset($j06_lote)           && ($j06_lote != ''))){
                 	
	          if(isset($j05_codigoproprio) && ($j05_codigoproprio != 'todos' )) {
	          	$sSetorloc = $xx . " j05_codigoproprio = '$j05_codigoproprio' ";
	          	$xx        = " and ";
	          	
	          }
	          if(isset($j06_quadraloc) && ($j06_quadraloc != '')) {
	          	$sQuadraloc = $xx . " j06_quadraloc = '$j06_quadraloc' ";
						  $xx         = " and ";
	          }
	          if(isset($j06_lote) && ($j06_lote != '')) {
	          	$sLoteloc = $xx . " j06_lote = '$j06_lote' ";
	          }
          
       	 }
          
          if($xx == "" && isset($pesquisar) || isset($filtroquery)){   
              $sql = $cllote->sql_query_loteloc("",$campos,"j34_idbql");
          }else if($xx!=""){
            $sql = $cllote->sql_query_loteloc("",$campos,"j34_idbql",$wsetor.$wquadra.$wlote.$sSetorloc.$sQuadraloc.$sLoteloc);
          }
        }
        if(isset($sql) && $sql !=""){ 
         db_lovrot($sql,15,"()","",$funcao_js);
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cllote->sql_record($cllote->sql_query($pesquisa_chave));
          if($cllote->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j34_setor',false);</script>";
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
<script type="text/javascript">
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
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
document.form2.chave_j34_idbql.focus();
document.form2.chave_j34_idbql.select();
  </script>
  <?
}
?>
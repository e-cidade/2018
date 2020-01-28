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
include("classes/db_iptubaixa_classe.php");
include("classes/db_setorloc_classe.php");
include ("libs/db_app.utils.php");

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cliptubaixa = new cl_iptubaixa;
$cliptubaixa->rotulo->label("j02_matric");
$cliptubaixa->rotulo->label("j02_dtbaixa");

$clrotulo = new rotulocampo;
$clrotulo->label("j06_setorloc");
$clrotulo->label("j06_quadraloc");  
$clrotulo->label("j06_lote");

$clsetorloc = new cl_setorloc();
$rsSetorLoc = $clsetorloc->sql_record($clsetorloc->sql_query_file(null, 'j05_codigoproprio, j05_descr', 'j05_codigoproprio, j05_descr'));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<? 
	db_app::load('scripts.js, prototype.js, strings.js, dbcomboBox.widget.js, estilos.css');
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="600" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="100%" border="0" align="center" cellspacing="0">
	     <form name="form1" id="form1" method="post" action="" onsubmit="js_append()">
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj02_matric?>">
              <?=$Lj02_matric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j02_matric",10,$Ij02_matric,true,"text",4,"","chave_j02_matric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj02_dtbaixa?>">
              <?=$Lj02_dtbaixa?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j02_dtbaixa",10,$Ij02_dtbaixa,true,"text",4,"","chave_j02_dtbaixa");
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
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_iptubaixa.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_iptubaixa.php")==true){
             include("funcoes/db_func_iptubaixa.php");
           }else{
           $campos = "iptubaixa.*";
           }
        }
        if(isset($chave_j02_matric) && (trim($chave_j02_matric)!="") ){
	         $sql = $cliptubaixa->sql_query_loteloc($chave_j02_matric,$campos,"j02_matric");
        }else if(isset($chave_j02_dtbaixa) && (trim($chave_j02_dtbaixa)!="") ){
	         $sql = $cliptubaixa->sql_query_loteloc("",$campos,"j02_dtbaixa"," j02_dtbaixa like '$chave_j02_dtbaixa%' ");
        }else if((isset($j05_codigoproprio) && ($j05_codigoproprio  != '' )) or 
                (isset($j06_quadraloc)      && ($j06_quadraloc != ''))       or   
                (isset($j06_lote)           && ($j06_lote != ''))){
                  
          $sql2 = '1 = 1';        
                 	
          if(isset($j05_codigoproprio) && ($j05_codigoproprio != 'todos' )) {
          	$sql2 .= " and j05_codigoproprio = '$j05_codigoproprio' ";
          }
          if(isset($j06_quadraloc) && ($j06_quadraloc != '')) {
          	$sql2 .= " and j06_quadraloc = '" . $j06_quadraloc . "'";
          }
          if(isset($j06_lote) && ($j06_lote != '')) {
          	$sql2 .= " and j06_lote = '" . $j06_lote . "'";
          }
          $sql = $cliptubaixa->sql_query_loteloc('', $campos,  'j02_matric', $sql2);
          
        }
        else{
           $sql = $cliptubaixa->sql_query_loteloc("",$campos,"j02_matric","");
        }
        $repassa = array();
        if(isset($chave_j02_dtbaixa)){
          $repassa = array("chave_j02_matric"=>$chave_j02_matric,"chave_j02_dtbaixa"=>$chave_j02_dtbaixa);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cliptubaixa->sql_record($cliptubaixa->sql_query_loteloc($pesquisa_chave));
          if($cliptubaixa->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j02_dtbaixa',false);</script>";
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form1","chave_j02_dtbaixa",true,1,"chave_j02_dtbaixa",true);

var aOptions     = new Array();
aOptions[''] = 'Todos...';

function js_append() {

  $('form1').appendChild($('j06_quadraloc'));
  $('form1').appendChild($('j06_lote'));

}

function js_mostraQuadra(){

  cboQuadras      = new DBComboBox('j06_quadraloc', 'j06_quadraloc', aOptions, '180');
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

  var oParametro = new Object();
  
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
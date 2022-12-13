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
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

$clpostgresqlutils = new PostgreSQLUtils;
$clrotulo          = new rotulocampo;
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {
  
  db_msgbox(_M('tributario.notificacoes.not2_geratxtlayout001.problema_indices_debitos'));
  $db_botao = false; 
  $db_opcao = 3;
} else {
  
  $db_botao = true;
  $db_opcao = 4;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script>
function js_processa() {

	if (document.form1.k60_codigo.value == '') {
		alert(_M('tributario.notificacoes.not2_geratxtlayout001.selecione_lista'));
		return false;
	}
  
  var sQuery  = '?sOrdem='+document.form1.ordem.value;
      sQuery += '&sLista='+document.form1.k60_codigo.value;
      sQuery += '&iQtd='+document.form1.quantidade.value;
      sQuery += '&sDataVenc='+document.form1.dtvencimento.value; 
      sQuery += '&iUtilizacao='+document.form1.utilizacao.value; 
      sQuery += '&nValorIni='+document.form1.vlrini.value;
      sQuery += '&nValorFin='+document.form1.vlrfin.value;

  js_OpenJanelaIframe('','db_iframe_txt','not2_geratxtlayout002.php'+sQuery,'Gerando Arquivo TXT ...',true);
}

function js_imprimeLista(sLista) {
  db_iframe_txt.hide();
  js_montarlista(sLista,'form1');
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc">
<form class="container" name="form1" method="post" action="" >
  <fieldset>
	<legend>Emissão Geral TXT com Layout</legend>
    <table class="form-container">
      <tr>
        <td title="<?=@$Tk60_codigo?>" >
          <?
		    db_ancora("Lista:","js_pesquisalista(true);",$db_opcao);
          ?>
        </td>
        <td>
          <?
			db_input('k60_codigo',10,$Ik60_codigo,true,'text',$db_opcao,"onchange='js_pesquisalista(false);'");
	        db_input('k60_descr',40,$Ik60_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Ordem:
        </td>
        <td>
          <?
            $aOrdem = array('n'=>'Nome',
                       	    'c'=>'CGM',
                         	'm'=>'Matrícula',
                        	'i'=>'Inscrição',
                        	'p'=>'Parcelamento',
		                    'l'=>'Logradouro');				            
	        db_select('ordem',$aOrdem,true,$db_opcao,"");
	      ?>
        </td>
      </tr>
	  <tr>
		<td>
		  Quantidade de Registros a Processar:
		</td>
  		<td>
	      <?
            db_input('quantidade',10,'',true,'text',$db_opcao);
  	      ?>
	    </td>
	  </tr>
      <tr>
    	<td>
    	  Valor Inicial:
       	</td>
       	<td>
		  <?
            db_input('vlrini',10,'',true,'text',$db_opcao);
            echo '&nbsp;<b>Valor Final:</b>';
            db_input('vlrfin',10,'',true,'text',$db_opcao);
          ?>
        </td>
      </tr>						  
	  <tr>
		<td>
		  Data de Vencimento:
		</td>
		<td>
		  <?
		    db_inputdata("dtvencimento",null,null,null,true,'text',$db_opcao);
	      ?>
		</td>
	  </tr>
      <tr>
        <td>
          Utilização (se data preenchida):
        </td>
        <td>
          <?
        	$aUtilizacao = array('t'=>'Em todas as parcelas',
                                 'v'=>'Apenas nas vencidas');				            
		    db_select('utilizacao',$aUtilizacao,true,$db_opcao," style='width: 100%'");
		  ?>
        </td>
      </tr>
    </table>    
  </fieldset>
  <input name="processar" type="button" id="processar" value="Processar" onClick="js_processa();"
       <?=($db_botao ? '' : 'disabled')?>>                                                     
</form>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisalista(mostra){
  if ( mostra ) {
  
    var sUrl = 'func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr';
    js_OpenJanelaIframe('','db_iframe_lista',sUrl,'Pesquisa',true);
  } else {
  
    var sUrl = 'func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista';
    js_OpenJanelaIframe('','db_iframe_lista',sUrl,'Pesquisa',false);
  }
}

function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if ( erro ) {
    document.form1.k60_descr.focus();
    document.form1.k60_descr.value = '';
  }
}

function js_mostralista1(chave1,chave2){
  document.form1.k60_codigo.value = chave1;
  document.form1.k60_descr.value = chave2;
  db_iframe_lista.hide();
}
</script>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");
$("quantidade").addClassName("field-size2");
$("vlrini").addClassName("field-size2");
$("vlrfin").addClassName("field-size2");
$("dtvencimento").addClassName("field-size2");


</script>
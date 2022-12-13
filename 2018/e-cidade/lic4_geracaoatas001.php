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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitaata_classe.php");
require_once("classes/db_atatemplategeral_classe.php");

$oPost              = db_utils::postMemory($_POST);

$clrotulo           = new rotulocampo;
$clLiclicita        = new cl_liclicita;
$clliclicitaata     = new cl_liclicitaata;
$clAtaTemplateGeral = new cl_atatemplategeral;

$clrotulo->label("l20_codigo");
$clrotulo->label("l39_posicaoinicial");

$lListaModelos        = false;
$lGeraAta             = false;
$lInicialGerada       = false;
$lGerarPosicaoInicial = 'true';

if (isset($oPost->l20_codigo) && trim($oPost->l20_codigo) != '') {
               
  $lListaModelos = true;
  $lGeraAta      = true;
  	
  $sCamposModelos        = "db82_sequencial, ";
  $sCamposModelos       .= "db82_descricao   ";
  
  $rsTemplateModalidade  = $clLiclicita->sql_record($clLiclicita->sql_query_modelosatas($oPost->l20_codigo,$sCamposModelos));

  if ( $clLiclicita->numrows > 0 ) {
  	
  	$rsModelos = $rsTemplateModalidade; 
  	
  } else {

  	$rsTemplateGeral = $clAtaTemplateGeral->sql_record($clAtaTemplateGeral->sql_query(null,$sCamposModelos));
  	
  	if ( $clAtaTemplateGeral->numrows > 0 ) {
  		$rsModelos = $rsTemplateGeral; 
  	} else {
  		$lListaModelos = false;
  		$lGeraAta      = false;
  	}
  }
  
  $sWhere            = "l39_liclicita = {$oPost->l20_codigo} and l39_posicaoinicial is true";
  $sSqlLicLicitaAta  = $clliclicitaata->sql_query_file(null, "*", null, $sWhere);
  $rsSqlLicLicitaAta = $clliclicitaata->sql_record($sSqlLicLicitaAta);
  if ($clliclicitaata->numrows == 0) {
  	$lGerarPosicaoInicial = 'false';
  }
}

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_modeloposicao();">
<form name="form1" method="post" action="">
	<table align="center" style="padding-top:25px">
	  <tr> 
	    <td>
	      <fieldset>
	        <legend align="left">
	          <b>Geração de Atas</b>
	        </legend>
	        <table> 
					  <tr> 
					    <td nowrap title="<?=$Tl20_codigo?>">
						    <b>
						    <?
						      db_ancora('Licitação:',"js_pesquisa_liclicita(true);",1);
						    ?>
						    </b> 
					    </td>
					    <td nowrap>
					      <? 
					        db_input("l20_codigo",10,$Il20_codigo,true,"text",3,"onchange='js_pesquisa_liclicita(false);'");
			          ?>
					    </td>
					  </tr>
					  <? if ( $lListaModelos ) { ?>
					   <tr id="modelotemplate">
					     <td>
					       <b>Modelos:</b>
					     </td>
               <td>
                 <?
			              db_selectrecord('documentotemplateata',$rsModelos,true,1,'');
                 ?>
               </td>					     
					   </tr> 
					   <tr>
					     <td title="<?=@$Tl39_posicaoinicial?>">
					       <?=@$Ll39_posicaoinicial?>
					     </td>
					     <td>
					     <?
								 $sWhere            = "l39_liclicita = {$l20_codigo} and l39_posicaoinicial is true";
								 $sSqlLicLicitaAta  = $clliclicitaata->sql_query_file(null, "*", null, $sWhere);
								 $rsSqlLicLicitaAta = $clliclicitaata->sql_record($sSqlLicLicitaAta);
								 if ($clliclicitaata->numrows > 0) {
								   $lInicialGerada = true;
								 }
					       
                 $aPosicaoInicial = array("f" => "Atualizada",
                                          "t" => "Inicial");    
                 db_select("l39_posicaoinicial", $aPosicaoInicial, true, "text", " onchange='js_modeloposicao();'");
               ?>
					     </td>
					   </tr>
					  <? } ?>
	        </table>
	        <span id="mensagemposicao">*Posição inicial terá como modelo a opção escolhida na confirmação do julgamento.</span>
	      </fieldset>
	    </td>
	  </tr>
	  <tr align="center">
	    <td>
	      <input type="button" id="gerar" name="gerar" value="Gerar Ata" <?=(!$lGeraAta?"disabled":"")?> onClick="js_geraAta()"/>
	    </td>
	  </tr>
	</table>
</form>
<? 
  db_input("lInicialGerada", 10, null, true, "hidden", 3);
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  function js_modeloposicao() {
    
    if ($('l39_posicaoinicial') && $('gerar').disabled == false) {
     
	    if ($('l39_posicaoinicial').value == 't') {
	      $('modelotemplate').hide();
	    } else {
	      $('modelotemplate').show();
	    }
      
	    if ($('l39_posicaoinicial').value == 't') {
	          
		    var lGerarPosicaoInicial = '<? echo $lGerarPosicaoInicial; ?>';
		    if (lGerarPosicaoInicial == 'false') {
		      
		      var sMsg  = "Licitação "+$('l20_codigo').value+" julgada sem vinculo com modelo de ata, ";
		          sMsg += "para gerar ata escolher a opção posição ATUALIZADA.                        ";
		      alert(sMsg);
		    }
	    }
    }
  }

  function js_geraAta() {
  
    var sQuery  = '?iLicitacao='+$('l20_codigo').value;
    
		if ($F('l39_posicaoinicial') == 'f') {
      sQuery += '&iCodDocumento='+$('documentotemplateata').value;
		}
		 
    sQuery += '&lPosicaoInicial='+$('l39_posicaoinicial').value;
  
    jan = window.open('lic4_geracaoatas002.php'+sQuery, 
                      '', 
                      'width='+(screen.availWidth-5)+
                      ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    
  }

	function js_pesquisa_liclicita(mostra){
	
	  if( mostra ){
	  
	    var sUrl = 'func_liclicita.php?funcao_js=parent.js_mostraliclicita1|l20_codigo';
	    js_OpenJanelaIframe('top.corpo','db_iframe_liclicita', sUrl, 'Pesquisa', true);
	  } else {
	    if(document.form1.l20_codigo.value != ''){ 
	    
	      var sUrl = 'func_liclicita.php?pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita';
	      js_OpenJanelaIframe('top.corpo', 'db_iframe_liclicita', sUrl, 'Pesquisa', false);
	    } else {
        document.form1.l20_codigo.value = ''; 
	    }
	  }
	  
	}
	
	function js_mostraliclicita(chave,erro){
	  document.form1.l20_codigo.value = chave; 
	  if( erro ){ 
	    document.form1.l20_codigo.value = ''; 
	    document.form1.l20_codigo.focus(); 
	  } else {
	    document.form1.submit();
	  }
	}
	
	function js_mostraliclicita1(chave1){
	   document.form1.l20_codigo.value = chave1;  
	   db_iframe_liclicita.hide();
	   document.form1.submit();
	}
</script>
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

 //MODULO: projetos
 $clparprojetos->rotulo->label();
 $sNameButton   = $db_opcao == 1     ? "incluir"  : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir");
 $sLabelButton  = $db_opcao == 1     ? "Incluir"  : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir");
 $sStateButton  = $db_botao == false ? "disabled" : "";
 $ob21_anousu   = db_getsession('DB_anousu');
 $aTipoCarta    = array("0" => "Padrão do Sistema", "1"=> "Modelo do OpenOffice");
 $aTipoHabiteSe = array('1'=>'Manual', '2'=>'Contador por Exercício');
?>
<form class="container" name="form1" method="post" action="">
	<fieldset>
		<legend>Parâmetros do Módulo Projetos:</legend>
    <table class="form-container" >
      <tr>
        <td width="148px;" title="<?=@$Tob21_anousu?>">
          <?=@$Lob21_anousu?>
        </td>
        <td> 
          <?
            db_input('ob21_anousu',10,$Iob21_anousu,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
			    <fieldset class="separator">
				    <legend>Habite-se:</legend>
				    <table class="form-container">
				      <tr>
				        <td width="140px;" title="<?=@$Tob21_numeracaohabite?>">
				          <?=@$Lob21_numeracaohabite?>
				        </td>
				        <td> 
				          <?
				            db_select('ob21_numeracaohabite', $aTipoHabiteSe, true, $db_opcao, "onChange='js_mudaNumero(this.value);'");
				          ?>
				        </td>
				      </tr>
				      <tr>
				        <td title="<?=@$Tob21_ultnumerohabite?>">
				          <?=@$Lob21_ultnumerohabite?>
				        </td>
				        <td> 
				          <?
				            db_input('ob21_ultnumerohabite', 10, $Iob21_ultnumerohabite, true, 'text'  , $db_opcaoNumero, "");
				            db_input('ultnumerohabite'     , 10, ""                    , true, 'hidden', $db_opcao      , "");
				          ?>
				        </td>
				      </tr>
				    </table>
				  </fieldset>
				  <fieldset class="separator">
				    <legend>Grupos de Características: </legend>
				    <table class="form-container">
				      <tr title="<?=$Tob21_grupotipoocupacao;?>">
				        <td width="140px;">
		              <?
		                $sFuncaoJSAncora    = "js_pesquisaGrupoCaracteristica($('ob21_grupotipoocupacao'), $('descr_tipoocupacao'), true);";
		                $sFuncaoJSDigitacao = "js_pesquisaGrupoCaracteristica($('ob21_grupotipoocupacao'), $('descr_tipoocupacao'), false);";
		                db_ancora ( $Lob21_grupotipoocupacao, $sFuncaoJSAncora, 1 );
		              ?>
		            </td>
				        <td>
				          <? 
				            db_input('ob21_grupotipoocupacao'  , 10, $Iob21_grupotipoocupacao  , true,'text', $db_opcao, "onChange=\"{$sFuncaoJSDigitacao}\""); 
				            db_input('descr_tipoocupacao'      , 35, ""                        , true,'text', 3        , "");
				          ?>
		            </td>
				      </tr>
				      <tr title="<?=$Tob21_grupotipoconstrucao;?>">
				        <td>
		              <? 
		                $sFuncaoJSAncora    = "js_pesquisaGrupoCaracteristica($('ob21_grupotipoconstrucao'), $('descr_tipoconstrucao'), true)";
		                $sFuncaoJSDigitacao = "js_pesquisaGrupoCaracteristica($('ob21_grupotipoconstrucao'), $('descr_tipoconstrucao'), false)";
		                db_ancora ( $Lob21_grupotipoconstrucao, $sFuncaoJSAncora , 1 );
		              ?>
		            </td>
				        <td>
				          <? 
				            db_input('ob21_grupotipoconstrucao', 10, $Iob21_grupotipoconstrucao, true,'text', $db_opcao, "onChange=\"{$sFuncaoJSDigitacao}\""); 
				            db_input('descr_tipoconstrucao'    , 35, ""                        , true,'text', 3        , "");
				          ?>
		            </td>
				      </tr>
				      <tr title="<?=$Tob21_grupotipolancamento;?>">
				        <td>
		              <? 
		                $sFuncaoJSAncora    = "js_pesquisaGrupoCaracteristica($('ob21_grupotipolancamento'), $('descr_tipolancamento'), true)";
		                $sFuncaoJSDigitacao = "js_pesquisaGrupoCaracteristica($('ob21_grupotipolancamento'), $('descr_tipolancamento'), false)";
		                db_ancora ( $Lob21_grupotipolancamento, $sFuncaoJSAncora, 1 )
		              ?>
		            </td>
				        <td>
				          <? 
				            db_input('ob21_grupotipolancamento', 10, $Iob21_grupotipolancamento, true,'text', $db_opcao, "onChange=\"{$sFuncaoJSDigitacao}\"");
				            db_input('descr_tipolancamento'    , 35, ""                        , true,'text', 3        , "");
				          ?>
		            </td>
				      </tr>
				    </table>
				  </fieldset>
				  <fieldset class="separator">
				    <legend>Modelos Padrão de Carta:</legend>
		        <table class="form-container">
		          <tr title="<?=$Tob21_tipocartaalvara;?>">
		            <td width="140px;"><? echo $Lob21_tipocartaalvara; ?></td>
		            <td >
		              <? 
		                db_select('ob21_tipocartaalvara', $aTipoCarta, true, $db_opcao); 
		              ?>
		            </td>
		          </tr>
		          <tr title="<?=$Tob21_tipocartahabite;?>">
		            <td nowrap><? echo $Lob21_tipocartahabite; ?></td>
		            <td nowrap>
		              <? 
		                db_select('ob21_tipocartahabite', $aTipoCarta, true, $db_opcao); 
		              ?>
		            </td>
 		          </tr>
		        </table>
			    </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?= $sNameButton; ?>" type="submit" id="db_opcao" value="<?= $sLabelButton; ?>" <?=$sStateButton ?> >
</form>

<script>


	function js_mudaNumero(iOpcao){
    
		if (iOpcao == 1) {
			document.form1.ob21_ultnumerohabite.value            = 0;
			document.form1.ob21_ultnumerohabite.readOnly         = true;
			document.form1.ob21_ultnumerohabite.style.background = "#DEB887";
		} else {
			document.form1.ob21_ultnumerohabite.value            = document.form1.ultnumerohabite.value;
			document.form1.ob21_ultnumerohabite.readOnly         = false;
			document.form1.ob21_ultnumerohabite.style.background = "";
		}
	}
  /**
   * Objetos Inicializados nulos para manipulação das Funções Filhas
   */
	var oInputDigitacao = null;
  var oInputRetorno   = null;
  
  function js_pesquisaGrupoCaracteristica(oElementoDigitacao, oElementoRetorno, lMostra) {

    /**
     * Define qual elemento sera utilizado para digitação e retorno
     */
    oInputDigitacao = oElementoDigitacao;
    oInputRetorno   = oElementoRetorno;
    
    if (lMostra) {
       js_OpenJanelaIframe('', 
                           'db_iframe_cargrup', 
                           'func_cargrup_rel.php?grupo=O&funcao_js=parent.js_preenchePesquisa1|j32_descr|j32_grupo',
                           'Pesquisa Grupo de Caracteísticas',
                           true);
    } else {
      
      if(oInputDigitacao.value != ''){ 
         js_OpenJanelaIframe('',
                             'db_iframe_cargrup',
                             'func_cargrup_rel.php?grupo=O&pesquisa_chave=' + oInputDigitacao.value +
                             '&funcao_js=parent.js_preenchePesquisa',
                             'Pesquisa Grupo de Caracteísticas',
                             false);
      } else {
        oInputDigitacao.value = ''; 
      }
    }
  }

  function js_preenchePesquisa(sChave, lErro){

    if(lErro == true) { 
    
      oInputDigitacao.focus(); 
      oInputDigitacao.value = ''; 
    }
    oInputRetorno.value   = sChave; 
  }

  function js_preenchePesquisa1(sDescricaoGrupo, iCodigoGrupo) {
    
    oInputRetorno.value   = sDescricaoGrupo;
    oInputDigitacao.value = iCodigoGrupo;
    db_iframe_cargrup.hide();
  }

</script>
<script>

$("ob21_anousu").addClassName("field-size2");
$("ob21_ultnumerohabite").addClassName("field-size2");
$("ob21_grupotipoocupacao").addClassName("field-size2");
$("descr_tipoocupacao").addClassName("field-size7");
$("ob21_grupotipoconstrucao").addClassName("field-size2");
$("descr_tipoconstrucao").addClassName("field-size7");
$("ob21_grupotipolancamento").addClassName("field-size2");
$("descr_tipolancamento").addClassName("field-size7");
</script>
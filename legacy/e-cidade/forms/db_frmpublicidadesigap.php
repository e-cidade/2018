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

//MODULO: Contabilidade
$clpublicidadesigap->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("c49_sequencial");
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<fieldset>
	<legend><b>Publicidade</b></legend>
	<table border="0" align="left" width="100%"> 
	  <tr>
	    <td nowrap title="<?=@$Tc48_sequencial?>">
	       <?=@$Lc48_sequencial?>
	    </td>
	    <td> 
				<?
				  db_input('c48_sequencial', 10, $Ic48_sequencial, true, 'text', 3);
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="Ano / Mes">
	      <b>Ano / Mes:</b>
	    </td>
	    <td> 
	      <?
	        db_input('c48_ano', 4, $Ic48_ano, true, 'text', $db_opcao);
	        echo '&nbsp;/&nbsp;';
	        db_input('c48_mes', 2, $Ic48_mes, true, 'text', $db_opcao);
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tc48_descricao?>">
	       <?=@$Lc48_descricao?>
	    </td>
	    <td> 
	      <?
	        db_input('c48_descricao', 60, $Ic48_descricao, true, 'text', $db_opcao);
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tc48_datapublicacao?>">
	       <?=@$Lc48_datapublicacao?>
	    </td>
	    <td> 
	      <?
	        db_inputdata('c48_datapublicacao', @$c48_datapublicacao_dia, @$c48_datapublicacao_mes, @$c48_datapublicacao_ano, true, 'text', $db_opcao);
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tc48_meiocomunicacaosigap?>">
	       <?=@$Lc48_meiocomunicacaosigap?>
	    </td>
	    <td>
	      <?
	        $oDaoDbConfig              = db_utils::getDao("db_config");
	        $sWhere                    = "codigo = {$iInstit}";
	        $sSqlDbConfig              = $oDaoDbConfig->sql_query_file(null, "uf", null, $sWhere);
	        $rsSqlDbConfig             = $oDaoDbConfig->sql_record($sSqlDbConfig);
	        
	        $aMeioComunicacaoSigap[0]  = "Selecione";
	        if ($oDaoDbConfig->numrows > 0) {
	          
	          $oDbConfig                 = db_utils::fieldsMemory($rsSqlDbConfig, 0);
	          
	          $oDaoMeioComunicacaoSigap  = db_utils::getDao("meiocomunicacaosigap");
	          $sWhere                    = "c49_uf = '{$oDbConfig->uf}'";
	          $sSqlMeioComunicacaoSigap  = $oDaoMeioComunicacaoSigap->sql_query_file(null, "*", "c49_sequencial", $sWhere);
	          $rsSqlMeioComunicacaoSigap = $oDaoMeioComunicacaoSigap->sql_record($sSqlMeioComunicacaoSigap);
	          for($i = 0; $i < $oDaoMeioComunicacaoSigap->numrows; $i ++) {
	              
	            $oMeioComunicacaoSigap = db_utils::fieldsMemory($rsSqlMeioComunicacaoSigap, $i);
	            $aMeioComunicacaoSigap[$oMeioComunicacaoSigap->c49_sequencial] = $oMeioComunicacaoSigap->c49_descricao;
	          }
	        }
	        
	        db_select('c48_meiocomunicacaosigap', $aMeioComunicacaoSigap, true, $db_opcao, "onchange='js_desabilitaSelecionar();'");
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tc48_tiporelatoriofiscal?>">
	       <?=@$Lc48_tiporelatoriofiscal?>
	    </td>
	    <td> 
				<?
				  $aTipoRelatorio = array('01' => 'Relatório Resumido de Execução Orçamentária',
				                          '02' => 'Relatório de Gestão Fiscal');
				  db_select('c48_tiporelatoriofiscal', $aTipoRelatorio, true, $db_opcao);
				?>
	    </td>
	  </tr>
	</table>
</fieldset>
<table>
  <tr>
    <td>
			<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
			       type="submit" id="db_opcao" onclick="return js_validar();"
			       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
			       <?=($db_botao==false?"disabled":"")?> >
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </td>
  </tr>
</table>
</form>
<script>
function js_desabilitaSelecionar() {

  var iCodigoMeioComunicacaoSigap = $('c48_meiocomunicacaosigap').value;
  if (iCodigoMeioComunicacaoSigap != 0) {
    $('c48_meiocomunicacaosigap').options[0].disabled = true; 
  }
}

function js_validar() {

  var iAno = $('c48_ano').value;
  if (iAno == '') {
  
    alert('Informe um ano válido!');
    return false;
  }
  
  var iMes = $('c48_mes').value;
  if (iMes == '' || iMes < 1 || iMes > 12) {
  
    alert('Informe um mês válido!');
    
    $('c48_mes').value = '';
    return false;
  }
  
  var iCodigoMeioComunicacaoSigap = $('c48_meiocomunicacaosigap').value;
  if (iCodigoMeioComunicacaoSigap == 0) {
    
    alert('Selecione um meio de comunicação válido!');
    return false;
  }
  
}

function js_pesquisa() {

  var sUrl = 'func_publicidadesigap.php?funcao_js=parent.js_preenchepesquisa|c48_sequencial';
  js_OpenJanelaIframe('top.corpo', 'db_iframe_publicidadesigap', sUrl, 'Pesquisa', true);
}

function js_preenchepesquisa(chave) {

  db_iframe_publicidadesigap.hide();
  <?
	  if($db_opcao!=1){
	    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	  }
  ?>
}
</script>
<?php
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

/**
 * Rotulo dos campos
 */	 
$oIpe->rotulo->label();
$oDaoCfpess->rotulo->label();


$sCamposParametros = 'r11_codipe as r11_codipealt, r11_valor as r11_valoralt, r11_dtipe as r11_dtipealt, r11_recpatrafasta, r11_percentualipe';
$sSqlParametros    = $oDaoCfpess->sql_query_file( db_anofolha(), db_mesfolha(), db_getsession("DB_instit"), $sCamposParametros); 
$rsParametros      = $oDaoCfpess->sql_record($sSqlParametros);

if ( $oDaoCfpess->numrows > 0) {

	$oParametros = db_utils::fieldsMemory($rsParametros, 0);

	$r11_codipealt      = $oParametros->r11_codipealt;
	$r11_valoralt       = $oParametros->r11_valoralt;
  $r11_dtipealt       = $oParametros->r11_dtipealt;
  $r11_recpatrafasta  = $oParametros->r11_recpatrafasta;
  $r11_percentualipe  = $oParametros->r11_percentualipe;
}
?>
<form name="form1" method="post" action="">
<center>
  <table border="0">
    <tr>
      <td>
        <fieldset>
          <legend><strong>Parâmetros - IPE</strong></legend>
          <table border="0">

            <tr>
              <td nowrap title="<?php echo $Tr11_codipe; ?>">
                <?php echo $Lr11_codipe; ?>
              </td>
              <td> 
                <?php db_input('r11_codipe', 10, $Ir11_codipe, true, 'text', $db_opcao, "", "r11_codipealt"); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tr11_valor; ?>">
                <?php echo $Lr11_valor; ?>
              </td>
              <td> 
                <?php db_input('r11_valor', 10, $Ir11_valor, true, 'text', $db_opcao, "", "r11_valoralt"); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tr11_dtipe; ?>">
                <?php echo $Lr11_dtipe; ?>
              </td>
              <td> 
                <?php db_input('r11_dtipe', 10, $Ir11_dtipe, true,'text',$db_opcao, "", "r11_dtipealt"); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tr11_recpatrafasta?>">
                <?php echo $Lr11_recpatrafasta?>
              </td>
              <td> 
                <?php
                  $aRecPatrAfasta = Array("t" => "Sim", "f" => "Não");
                  db_select('r11_recpatrafasta', $aRecPatrAfasta, true, $db_opcao, "style='width:90px;'");
                ?>
              </td>
            </tr>            

            <tr>
              <td nowrap title="<?php echo $Tr11_percentualipe; ?>">
                <?php echo $Lr11_percentualipe; ?>
              </td>
              <td> 
                <?php db_input('r11_percentualipe', 10, $Ir11_percentualipe, true, 'text', $db_opcao, "onchange='js_validaPercentual(this);'", "r11_percentualipe"); ?>
              </td>
            </tr>            

          </table>
        </fieldset>
      </td>
    </tr>
  </table>
	<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</center>
</form>

<script type="text/javascript">

function js_validaPercentual(oElemento) {

	var sPercentualValor = oElemento.value;

	if ( sPercentualValor != '' && !js_isNumeric(sPercentualValor) ) {

		alert('Percentual IPE deve ser preenchido somente com números decimais!');
		oElemento.value = null;
	}
}

function js_isNumeric(nNumber) {

	var oRegExp = /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/;
	var lTeste  = oRegExp.test(nNumber);

	return lTeste;
}

</script>
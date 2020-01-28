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

$clrhpessoal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("r70_estrut");
$clrotulo->label("r70_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("rh02_salari");


?>
<form name="form1" method="post" action="" class="container">

  <fieldset>
    <Legend>Reajuste de Salários</Legend>
    <center>
    <table>
      <tr>
        <th nowrap style="padding: 0 15px"><b><?=$RLrh01_regist?></b></th>
        <th nowrap><b><?=$RLz01_nome?></b></th>
        <th nowrap style="padding: 0 15px"><b><?=$RLr70_estrut?></b></th>
        <th nowrap><b><?=$RLr70_descr?></b></th>
        <th nowrap style="padding: 0 15px"><b><?=$RLrh02_salari?></b></th>
        <th nowrap><b>Valor</b></th>
        <th nowrap><b>Percentual (%)</b></th>
      </tr>
      
    <?php

    $oDaoRhLota = db_utils::getDao('rhlota');

    foreach ($aServidores as $oServidor) {

      $iMovimentacao = $oServidor->getCodigoMovimentacao();
      $sqlRhLota = $oDaoRhLota->sql_query_file($oServidor->getCodigoLotacao(), 'r70_estrut, r70_descr');
      $rsRhLota  = db_query($sqlRhLota);
      $oLotacao  = db_utils::fieldsMemory($rsRhLota, 0);
    ?>      
        <tr>
          <td align='center' nowrap width="5%" ><?=$oServidor->getMatricula();?></td>
          <td align='left'   nowrap width="30%"><?=$oServidor->getCgm()->getNome(); ?></td>
          <td align='center' nowrap width="10%"><?=$oLotacao->r70_estrut; ?> </td>
          <td align='left'   nowrap width="30%"><?=$oLotacao->r70_descr; ?> </td>
          <td align='right'  nowrap width="5%" ><?=db_formatar($oServidor->getSalario(),"f")?></td>
          <td align='center' nowrap width="10%">
            <?
            db_input('rh02_salari',10, $Irh02_salari, true, 'text', 1, " movimentacao='$iMovimentacao' class='digitacao valor'", "valor_{$iMovimentacao}");
            ?>
          </td>
          <td align='center' nowrap width="10%">
            <?
            db_input('rh02_salari',10, $Irh02_salari, true, 'text', 1, "movimentacao='$iMovimentacao' class='digitacao percentual'", "perce_{$iMovimentacao}");
            ?>
          </td>
        </tr>

<?php
    }
?>
        </table>
      </fieldset>
   
      <fieldset>
        <Legend align="left">
          <b>Lançar valores</b>
        </Legend>

        <table style="margin: 0 auto;" cellspacing="8" cellpadding="0">
	        <tr>
            <td></td>
            <td align='right'><strong>Valor padrão:</strong></td>
            <td align='left'>
              <?
              db_input('rh02_salari',10, $Irh02_salari, true, 'text', 1, " movimentacao='GERAL' class='digitacao valor'", 'valor');
              ?>
            </td>
            <td align='right'><strong>Percentual padrão:</strong></td>
            <td align='left'>
              <?
              db_input('rh02_salari',10, $Irh02_salari, true, 'text', 1, " movimentacao='GERAL' class='digitacao percentual'", 'perce');
              ?>
            </td>
	        </tr>
	      </table>
      </fieldset>
      <input type="submit" name="processar" value="Processar" onclick="return js_ValidaDados();"/>
      <input type="button" name="limpar"    value="Limpar"    onclick="js_limparcampos('');"/>
      <input type="button" name="voltar"    value="Voltar"    onclick="parent.janelaReajuste.hide();"/>
    </form>
<script>

(function() {

  /**
   * O bloco a seguir, define regras para que a ação de 
   * OnDrop não seja aceita nos campos.
   */
  $$('.digitacao').each(function(oElemento){

    oElemento.ondrop = function() {
      return false;
    };
  });

})();

var mudarValor = function() {

  var lCampoPercentual = this.hasClassName("percentual");
  var mCodigo          = this.getAttribute("movimentacao");
  var aCampos          = document.querySelectorAll("input.digitacao[movimentacao='"+mCodigo+"']");
  var oCampoValor      = aCampos[0];
  var oCampoPercentual = aCampos[1];

  if (this.value != "" && !isNumeric(this.value)) {
    return false;
  }


  if (lCampoPercentual) { // Digitou no campo percentual

    if ( this.value == "" ) {

      oCampoValor.readOnly        = false;
      oCampoValor.removeClassName("readOnly");
    } else {
      oCampoValor.readOnly        = true;
      oCampoValor.addClassName("readOnly");
    } 
  } else {  //Campo Valor

    if ( this.value == "" ) {

      oCampoPercentual.readOnly   = false;
      oCampoPercentual.removeClassName("readOnly");
    } else {

      oCampoPercentual.readOnly   = true;
      oCampoPercentual.addClassName("readOnly");
    }
  }

  if ( mCodigo == "GERAL" ) {
    lancarValores( this.value, lCampoPercentual);
  }
};

function  lancarValores( nValor, lPercentual ) {

    if ( lPercentual ) {
      var sClasse = 'percentual';
    } else {
      var sClasse = 'valor';
    }

     if ( nValor ) {

       $$('input.'+sClasse+':not(.readOnly)').each( function(oElemento) {
        
         var fFuncao = mudarValor.bind(oElemento);//Aqui define o escopo da função com o elemento digitado
         if ( oElemento.value == "" ) {  

           oElemento.value = nValor;
           fFuncao();
         }
       });
     }
}

function js_limparcampos(){

  $$('input.digitacao').each( function(oElemento) {

    oElemento.value = '';
    oElemento.removeClassName('readOnly');
    oElemento.readOnly = false;
  });
}

$$('.digitacao').each(function(oElemento) {
  oElemento.observe('blur', mudarValor);
});

/**
 * Valida os dados dos campos Valor e percentual, não pode permitir reajustar 
 * salários se pelo menos um dos campos possui o valor 0 ou < 0
 * @return boolean
 */
function js_ValidaDados(){

  var aCampos   = $$('.digitacao:not(.readOnly)');
  var lTemValor = false;

  for (iCampos = 0; iCampos < aCampos.length; iCampos++) {
    
    var iValor = aCampos[iCampos].value;

    /**
     * Verifica se o numero informado é válido
     */
    if ( isNaN(parseFloat(iValor)) && iValor != '') {

        alert('Informe um valor válido para o campo escolhido.');
        return false;
    }

    if (parseFloat(iValor) == 0) {

      alert('Os campos valor e percentual não podem ser preenchidos com o valor \'0\'.');
      return false;
    }

    if (iValor < 0) {

      alert('Os campos valor e percentual não podem ser menores que \'0\'.');
      return false;
    }

    if (iValor != '') {
      lTemValor = true;
    }

  }

  if (!lTemValor) {

    alert('Preencha ao menos um dos campos disponíveis.');
    return false;
  }



  return true;
}

</script>

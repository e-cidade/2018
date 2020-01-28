<?php
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
?>

<div class="container">

<fieldset>
 <legend>Dados do registro de Baixa</legend>
  <table class="form-container">
   <form name="form1" action="" method="post">
    <input name="idret"  type="hidden" id="idret" value="<?php echo !empty($idret) ? $idret : ''?>">
    <input name="autent" type="hidden" id="codret" value="<?php echo !empty($autent) ? $autent : ''?>">
    <input name="conta"  type="hidden" id="conta" value="<?php echo !empty($conta) ? $conta : ''?>">
   <tr>
     <td width="25%"><label for="k15_codbco">Banco:</label></td>
     <td width="25%">
        <input name="k15_codbco" type="text" id="k15_codbco" <?=($oGet->opcao==5?"readonly":"")?> value="<?=($oGet->opcao!=5?$k15_codbco:$codbco)?>" size="15" maxlength="3">
     </td>
     <td width="25%">&nbsp;</td>
     <td width="25%"><label for="k00_numpre">Numpre:</label></td>
     <td width="25%">
       <?php

        $k00_numpre = $oGet->opcao!=5?$k00_numpre:0;
        db_input('k00_numpre', 15, $Ik00_numpre, true, 'text', 1, "");
       ?>
     </td>
   </tr>
   <tr>
     <td><label for="k15_codage">Agência:</label></td>
     <td>
       <input name="k15_codage" type="text" id="k15_codage" <?=($oGet->opcao==5?"readonly":"")?> value="<?=($oGet->opcao!=5?$k15_codage:$codage)?>" size="15" maxlength="5">
     </td>
     <td>&nbsp;</td>
     <td><label for="k00_numpar">Numpar:</label></td>
     <td>
        <?php
          $k00_numpar = $oGet->opcao!=5?$k00_numpar:0;
          db_input('k00_numpar', 15, $Ik00_numpar, true, 'text', 1, "");
         ?>
     </td>

   </tr>
   <tr>
     <td><label for="cedente22">Conv&ecirc;nio:</label></td>
     <td colspan="4"><input name="convenio" type="text" id="cedente22" value="<?=($oGet->opcao!=5?$convenio:0)?>" size="15" maxlength="10"></td>
   </tr>
   <tr>
     <td><label for="cedente3">Cedente</label></td>
     <td colspan="4"><input name="cedente" type="text" id="cedente3" value="<?=($oGet->opcao!=5?$cedente:0)?>" size="15" maxlength="10" ></td>
   </tr>
   <tr>
     <td><label for="k00_numbco">Número Banco:</label></td>
     <td colspan="4">
      <input name="k00_numbco" type="text" id="k00_numbco" value="<?=($oGet->opcao!=5?$k00_numbco:0)?>" size="20" maxlength="20">
      <input type="button" value="Atualizar" onclick="js_buscaArrebanco()">
     </td>
   </tr>
   <tr>
     <td><label for="dtarq">Data Arquivo:</label></td>
     <td colspan="4">
       <?
         if ($oGet->opcao == 5) {
            $diaarq = $dia;
            $mesarq = $mes;
            $anoarq = $ano;
         } else {
            $diaarq = substr($dtarq,-2);
            $mesarq = substr($dtarq,5,2);
            $anoarq = substr($dtarq,0,4);
         }
  		  db_inputdata("dtarq",$diaarq,$mesarq,$anoarq,true,'text',1);
  		 ?>
     </td>
   </tr>
   <tr>
     <td><label for="dtpago">Data Pagamento:</label></td>
     <td colspan="4">
      <?
        if ($oGet->opcao == 5 ) {
          $diapago = $dia;
          $mespago = $mes;
          $anopago = $ano;
        } else {
          $diapago = substr($dtpago,-2);
          $mespago = substr($dtpago,5,2);
          $anopago = substr($dtpago,0,4);
        }
  	    db_inputdata("dtpago",$diapago,$mespago,$anopago,true,'text',1);
      ?>
     </td>
   </tr>
   <tr>
     <td><label for="dtcredito">Data Crédito:</label></td>
     <td colspan="4">
      <?
        if ($oGet->opcao == 5 ) {
          $diacredito = $dia;
          $mescredito = $mes;
          $anocredito = $ano;
        } else {
          $diacredito = substr($dtcredito,-2);
          $mescredito = substr($dtcredito,5,2);
          $anocredito = substr($dtcredito,0,4);
        }
  	    db_inputdata("dtcredito",$diacredito,$mescredito,$anocredito,true,'text',1);
      ?>
     </td>
   </tr>
   <tr>
    <td align="left" nowrap title="Ordem Todas/Dívida Ativa/Parceladas">
      <label for="classi">Classifica:</label>
    </td>
    <td colspan="4">
      <?
       if ($oGet->opcao!=5) {
          if ($classi == 'f') {
             $classi = array("f"=>"Não","t"=>"Sim");
          } else {
             $classi = array("t"=>"Sim","f"=>"Não");
          }
       } else {
         $classi = array("f"=>"Não","t"=>"Sim");
       }

  		 if (isset($oGet->arquivocodret) && $oGet->arquivocodret != null && $oGet->arquivocodret != '' ) {
  			  $opcao_arquivo = "3";
  		 } else {
  			  $opcao_arquivo = 1;
  		 }
       db_select("classi",$classi,true,$opcao_arquivo," style='width: 115px;'");
      ?>
    </td>
   </tr>
   <tr>
    <td colspan="5">
     <fieldset>
     <legend>Valores</legend>
     <table width="100%">
     <tr>
      <td width="15%"><label for="vlrpago">Valor Pago:</label></td>
      <td width="35%">
        <?php
          $vlrpago = $oGet->opcao!=5?$vlrpago:0;
          db_input('vlrpago', 15, $Ivlrpago, true, 'text', 1, "onchange='js_valor_pago();'");
        ?>
      </td>
      <td width="15%"><label for="vlracres">Acréscimos:</label></td>
      <td width="35%">
        <?php
          $vlracres = $oGet->opcao!=5?$vlracres:0;
          db_input('vlracres', 15, $Ivlracres, true, 'text', 1, "")
        ?>
      </td>
     </tr>
     <tr>
      <td><label for="vlrjuros">Valor Juros:</label></td>
      <td>
        <?php
          $vlrjuros = $oGet->opcao!=5?$vlrjuros:0;
          db_input('vlrjuros', 15, $Ivlrjuros, true, 'text', 1, "")
        ?>
      </td>
      <td><label for="vlrdesco">Desconto:</label></td>
      <td>
        <?php
          $vlrdesco = $oGet->opcao!=5?$vlrdesco:0;
          db_input('vlrdesco', 15, $Ivlrdesco, true, 'text', 1, "")
        ?>
      </td>
     </tr>
     <tr>
      <td><label for="vlrmulta">Valor Multa:</label></td>
      <td>
        <?php
          $vlrmulta = $oGet->opcao!=5?$vlrmulta:0;
          db_input('vlrmulta', 15, $Ivlrmulta, true, 'text', 1, "")
        ?>
      </td>
      <td><label for="vlrtot">Total Pago:</label></td>
      <td>
        <?php
          $vlrtot = $oGet->opcao!=5?$vlrtot:0;
          db_input('vlrtot', 15, $Ivlrtot, true, 'text', 1, "")
        ?>
      </td>
     </tr>
     </table>
     </fieldset>
    </td>
   </tr>
   <tr>
    <td colspan="5">
     <fieldset>
     <legend>Protocolo</legend>
      <table>
        <tr>
		     <td nowrap title="Processos registrado no sistema?">
		     	<label for="lProcessoSistema">Processo do Sistema:</label>
		     </td>
		     <td nowrap>
		     	<?
		     	  db_select('lProcessoSistema', array(true=>'SIM', false=>'NÃO'), true, 1, "onchange='js_processoSistema(this.value)' style='width: 95px'")
		     	?>
		     </td>
	      </tr>
        <tr id="processoSistema">
		     <td nowrap title="<?=@$Tk141_protprocesso?>">
		     	<?
		     		db_ancora(@$Lk141_protprocesso, 'js_pesquisaProcesso(true)', 1);
		     	?>
		     </td>
		     <td nowrap>
		     	<?php
		     	  db_input('k141_protprocesso', 10, @$Ik141_protprocesso, true, 'text', 1, 'onchange="js_pesquisaProcesso(false)"') ;
	          db_input('p58_requer', 40, $Ip58_requer, true, 'text', 3);
		       ?>
		     </td>
	      </tr>

	      <tr id="processoExterno1" style="display: none;">
		      <td nowrap title="<?=@$Tk142_processo?>">
		       <label for="k142_processo"><?=@$Lk142_processo?></label>
		      </td>
		      <td nowrap>
		     	<?php
		     	 db_input('k142_processo', 10, @$Ik142_processo, true, 'text', 1) ;
		     	?>
		      </td>
	      </tr>

	      <tr id="processoExterno2" style="display: none;">
		      <td nowrap title="<?=@$Tk142_titular?>">
		     	 <label for="k142_titular"><?=@$Lk142_titular?></label>
		      </td>
		      <td nowrap>
		     	 <?php
		     		 db_input('k142_titular', 54, @$Ik142_titular, true, 'text', 1) ;
		     	 ?>
		      </td>
	     </tr>

       <tr id="processoExterno3" style="display: none;">
		      <td nowrap title="<?=@$Tk142_dataprocesso?>">
		        <label for="k142_dataprocesso"><?=@$Lk142_dataprocesso?></label>
		      </td>
		      <td nowrap>
		     	<?php
		     		db_inputdata('k142_dataprocesso', @$k142_dataprocesso_dia, @$k142_dataprocesso_mes, @$k142_dataprocesso_ano, true, 'text', 1);
		     	?>
		      </td>
	     </tr>

	     <tr>
          <td title="<?=@$Tk142_observacao?>" colspan="4" align="center">
	    	  	<fieldset class="separator">
	    	  		<legend>
	    	  			<?=@$Lk142_observacao?>
	    	  		</legend>
	    	  		<?
	    	  			db_textarea('k142_observacao', 3, 64, @$Ik142_observacao, true, 'text', 1);
	    	  		?>
	    	  	</fieldset>
          </td>
	     </tr>
	    </table>
	   </fieldset>
	  </td>
	 </tr>
  </table>
</fieldset>
  <?
    if ($oGet->opcao != 5 ) {
      echo "<input name=\"alterar\" type=\"submit\" id=\"alterar\" value=\"Confirma\">";

      if ($podeexcluir == 't' ) {
        echo "<input name=\"excluir\" type=\"submit\" id=\"excluir\" value=\"Excluir\">";
      }

    } else {
      echo "<input name=\"incluir\" type=\"submit\" id=\"incluir\" value=\"Incluir\">";
    }
  ?>
 </form>
</div>
<script>

function js_processoSistema(lProcessoSistema) {

	if (lProcessoSistema == 1) {

		document.getElementById('processoExterno1').style.display = 'none';
		document.getElementById('processoExterno2').style.display = 'none';
		document.getElementById('processoExterno3').style.display = 'none';
		document.getElementById('processoSistema').style.display  = '';
	}	else {

		document.getElementById('processoExterno1').style.display = '';
		document.getElementById('processoExterno2').style.display = '';
		document.getElementById('processoExterno3').style.display = '';
		document.getElementById('processoSistema').style.display  = 'none';
	}
}

js_processoSistema(document.form1.lProcessoSistema.value);

function js_pesquisaProcesso(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?funcao_js=parent.js_mostraProcesso|p58_codproc|z01_nome','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?pesquisa_chave='+document.form1.k141_protprocesso.value+'&funcao_js=parent.js_mostraProcessoHidden','Pesquisa',false);
  }
}

function js_mostraProcesso(iCodProcesso, sRequerente) {

  document.form1.k141_protprocesso.value = iCodProcesso;
  document.form1.p58_requer.value        = sRequerente;
  db_iframe_matric.hide();
}

function js_mostraProcessoHidden(iCodProcesso, sNome, lErro) {

  if(lErro == true) {
    document.form1.k141_protprocesso.value = "";
    document.form1.p58_requer.value  = sNome;
  } else {
    document.form1.p58_requer.value  = sNome;
  }
}

function js_valor_pago() {

  var iVlrPago = document.getElementById('vlrpago').value;
    document.getElementById('vlrtot').value = iVlrPago;
  if (iVlrPago == null || iVlrPago == ''){

    document.getElementById('vlrpago').value = '0';
    document.getElementById('vlrtot').value  = '0';
  } else {
    document.getElementById('vlrtot').value = iVlrPago;
  }
}

sUrl = 'cai4_arrenumbco.RPC.php';
function js_buscaArrebanco() {

  var oParam    = new Object();
	var iCodbco   = document.form1.k00_numbco.value;
	oParam.sExec   = 'getNumpre';
	oParam.sNumbco = iCodbco;
	var oAjax = new Ajax.Request(sUrl,
      												{
															 method: 'POST',
												       parameters: 'json='+Object.toJSON(oParam),
												       onComplete: js_retornaNumpre
      												});
}

function js_retornaNumpre(oAjax) {

	var oRetorno        = eval("("+oAjax.responseText+")");
	if (oRetorno.status == 1) {

		$('k00_numpre').value = oRetorno.iNumpre;
		$('k00_numpar').value = oRetorno.iNumpar;
	} else {

		alert(oRetorno.message);
	}
}
</script>
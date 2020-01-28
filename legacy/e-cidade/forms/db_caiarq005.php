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

$cldisbanco->rotulo->label();
$clrotulo = new rotulocampo;
?>
<form name="form1" action="" method="post">
  <table width="81%" border="0" cellspacing="0">
    <tr>
      <td width="25%">Banco:  <input type="hidden" id="cod_arquivo" value="<?=$arquivocodret ?>"></td>
      <td width="30%"><input name="k15_codbco" type="text" id="k15_codbco" <?=($opcao==5?"readonly":"")?> value="<?=($opcao!=5?$k15_codbco:$codbco)?>" size="4" maxlength="3">
        <input name="idret" type="hidden" id="idret" value="<?=$idret?>"></td>
        <input name="proximobanco" value="<?=@$proximobanco?>" type="hidden">

        <input name="autent" type="hidden" id="codret" value="<?=$autent?>"></td>
        <input name="conta"  type="hidden" id="conta"  value="<?=$conta?>"></td>
      <td width="15%">Numpre:</td>
      <td width="30%">
        <?
        $k00_numpre = $opcao!=5?$k00_numpre:0;
        db_input('k00_numpre', 15, $Ik00_numpre, true, 'text', 1, "");
        ?>
      </td>
    </tr>
    <tr>
      <td height="25">Agência:</td>
      <td><input name="k15_codage" type="text" id="k15_codage" <?=($opcao==5?"readonly":"")?> value="<?=($opcao!=5?$k15_codage:$codage)?>" size="6" maxlength="5"></td>
      <td>Numpar:</td>
      <td>
        <?
        $k00_numpar = $opcao!=5?$k00_numpar:0;
        db_input('k00_numpar', 15, $Ik00_numpar, true, 'text', 1, "");
        ?>
      </td>
    </tr>
    <tr>
      <td>Número Banco:</td>
      <td><input name="k00_numbco" type="text" id="k00_numbco" value="<?=($opcao!=5?$k00_numbco:0)?>" size="16" maxlength="15"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Data do Arquivo:</td>
      <td colspan="2">
        <?
          if ($opcao == 5) {

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
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Data do Pagamento:</td>
      <td colspan="2">
        <?
        if ($opcao == 5 ) {

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
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Valor Pago:</td>
      <td>
        <?
        $vlrpago = $opcao!=5?$vlrpago:0;
        db_input('vlrpago', 15, $Ivlrpago, true, 'text', 1, "onchange='js_valor_pago();'");
        ?>
       </td>
      <td>Acréscimos:</td>
      <td>
        <?
        $vlracres = $opcao!=5?$vlracres:0;
        db_input('vlracres', 15, $Ivlracres, true, 'text', 1, "")
        ?>
      </td>
    </tr>
    <tr>
      <td>Valor Juros:</td>
      <td>
        <?
        $vlrjuros = $opcao!=5?$vlrjuros:0;
        db_input('vlrjuros', 15, $Ivlrjuros, true, 'text', 1, "")
        ?>
      </td>
      <td>Desconto:</td>
      <td>
        <?
        $vlrdesco = $opcao!=5?$vlrdesco:0;
        db_input('vlrdesco', 15, $Ivlrdesco, true, 'text', 1, "")
        ?>
      </td>
    </tr>
    <tr>
      <td>Valor Multa:</td>
      <td>
        <?
        $vlrmulta = $opcao!=5?$vlrmulta:0;
        db_input('vlrmulta', 15, $Ivlrmulta, true, 'text', 1, "")
        ?>
      </td>
      <td>Total Pago:</td>
      <td>
        <?
        $vlrtot = $opcao!=5?$vlrtot:0;
        db_input('vlrtot', 15, $Ivlrtot, true, 'text', 1, "")
        ?>
      </td>
    </tr>
    <tr>
      <td>Cedente:</td>
      <td><input name="cedente" type="text" id="cedente3" value="<?=($opcao!=5?$cedente:0)?>" size="11" maxlength="10" ></td>
      <td>Conv&ecirc;nio:</td>
      <td><input name="convenio" type="text" id="cedente22" value="<?=($opcao!=5?$convenio:0)?>" size="11" maxlength="10"></td>
    </tr>

    <tr >
      <td align="left" nowrap title="Ordem Todas/Dívida Ativa/Parceladas" >
      <strong>Classi:&nbsp;&nbsp;</strong>
      </td>
      <td>
        <?
        if ($opcao!=5) {

          if ($classi == 'f') {
            $classi = array("f"=>"Não","t"=>"Sim");
          } else {
            $classi = array("t"=>"Sim","f"=>"Não");
          }
        } else {
          $classi = array("f"=>"Não","t"=>"Sim");
        }

        /*
         * T. 47777
         * se o arquivo foi selecionado o campo classi vem desabilitado com a opçãp 'não' selecionada
         *
         */
				if ($arquivocodret != null && $arquivocodret != '' ) {
					$opcao_arquivo = "disabled";
				} else {
					$opcao_arquivo = "";
				}
        db_select("classi",$classi,true,2,$opcao_arquivo);
        ?>
      </td>
    </tr>

      <?
      if ($opcao != 5 ) {
        ?>
        <td colspan="2" align="right"><input name="confirma" type="submit" id="confirma" value="Confirma">
        <?
        if ($podeexcluir == 't' ) {
          ?>
          <td align="left"><input name="exclui" type="submit" id="exclui" value="Excluir">
          <?
        }
      } else {
        ?>
        <td colspan="2" align="right"><input name="inclui" type="submit" id="inclui" value="Inclui">
        <?
      }
      ?>

      </td>
    </tr>
  </table>
</form>
<script type="text/javascript">
/*
 *  T. 47777
 *  função js_valor_pago() para preencher automaticamente o campo total pago, se o campo valor pago for preenchido,
 *  o total, será o mesmo valor pago.
*/
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
</script>
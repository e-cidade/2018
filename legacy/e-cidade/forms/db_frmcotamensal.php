<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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
  <form id="form1" name="form1" >
    <fieldset>
      <legend id="CotaMunicipio">Cota Mensal de Exames</legend>
      <table class="form-container">
        <tr id="prestador">
          <td nowrap title="<?=$Ts111_i_prestador?>">
            <label for="s111_i_prestador" id="lblPrestador"><?php echo $Ls111_i_prestador?></label>
          </td>
          <td nowrap>
            <?php
              db_input('s111_i_prestador',10,1,true,'text',3,'data="s111_i_prestador"');
              db_input('z01_nome',59,2,true,'text',3,'data="z01_nome" ');
            ?>
          </td>
        </tr>
        <tr>
          <td id="tipoLabel" nowrap title="Tipo de Cota">
            <label for="tipo">Tipo de Cota:</label>
          </td>
          <td>
            <?php
              $opcoes = array('Selecionar Tipo', '1' => 'Individual', '2' => 'Grupo' );
              db_select('tipo',$opcoes,true,$db_opcao,"");
            ?>
          </td>
        </tr>
        <tr id="individual">
          <td>
            <?php
              db_ancora( $Ls111_procedimento, "js_pesquisas111_i_exame(true);", $db_opcao );
            ?>
          </td>
          <td>
            <?php
            $sScript = " onchange='js_pesquisas111_i_exame(false);'";
            db_input( 's112_i_codigo',        10, $Is112_i_codigo,        true, 'hidden', $db_opcao );
            db_input( 's112_i_prestadorvinc', 10, $Is112_i_prestadorvinc, true, 'hidden', $db_opcao );
            db_input( 'sd63_i_codigo',        10, $Isd63_i_codigo,        true, 'hidden', $db_opcao );
            db_input( 'sd63_c_procedimento',  10, $Isd63_c_procedimento,  true, 'text',   $db_opcao, $sScript );
            db_input( 'sd63_c_nome',          59, $Isd63_c_nome,          true, 'text',   3 );
            ?>
          </td>
        </tr>
        <tr id="descricaoGrupo">
          <td nowrap title="<?=$Tage02_nome?>">
              <label for="age02_nome">Grupo:</label>
            </td>
          <td>
            <?php
              db_input('age02_nome',73,0,true,'text',1,'data="age02_nome"');
            ?>
          </td>
        </tr>
      </table>
      <div id="gridGrupoExames" style="display: none;">
        <div id="ctnLancador"></div>
      </div>
      <fieldset class="separator">
        <legend>Lançamento Mensal</legend>
        <table>
          <tr>
            <td nowrap title="<?=$Tage01_quantidade?>">
              <label for="quantidade"><?php echo $Lage01_quantidade?></label>
            </td>
            <td>
              <input title="Quantidade de Cota de Exames Municípais" name="age01_quantidade"
              id="quantidade" value="" size="6" maxlength="10" data="age01_quantidade" onblur="js_ValidaMaiusculo(this,'f',event);"
              oninput="js_ValidaCampos(this,1,'Quantidade','f','f',event);" onkeydown="return js_controla_tecla_enter(this,event);" autocomplete="off" type="text">
            </td>
          </tr>
          <tr>
            <td nowrap title="Competência da Cota">
              <label for="mes_inicial"><strong>Competência Inicial:</strong></label>
            </td>
            <td>
              <input title="Mês Inicial" name="mes_inicial" id="mes_inicial" value="" size="6" maxlength="2" onblur="js_ValidaMaiusculo(this,'',event);"
              oninput="js_ValidaCampos(this,1,'','','',event);" onkeydown="return js_controla_tecla_enter(this,event);" autocomplete="" type="text">
              /
              <input title="Ano Inicial" name="ano_inicial" id="ano_inicial" value="" size="6" maxlength="4" onblur="js_ValidaMaiusculo(this,'',event);" oninput="js_ValidaCampos(this,1,'','','',event);"
              onkeydown="return js_controla_tecla_enter(this,event);" autocomplete="" type="text">
            </td>
          </tr>
          <tr>
            <td nowrap title="Competência da Cota">
              <label for="mes_final"><strong>Competência Final:</strong></label>
            </td>
            <td>
              <input title="Mês Final" name="mes_final" id="mes_final" value="" size="6" maxlength="2" onblur="js_ValidaMaiusculo(this,'',event);"
              oninput="js_ValidaCampos(this,1,'','','',event);" onkeydown="return js_controla_tecla_enter(this,event);" autocomplete="" type="text">
              /
              <input title="Ano Final" name="ano_final" id="ano_final" value="" size="6" maxlength="4" onblur="js_ValidaMaiusculo(this,'',event);"
              oninput="js_ValidaCampos(this,1,'','','',event);" onkeydown="return js_controla_tecla_enter(this,event);" autocomplete="" type="text">
            </td>
          </tr>
        </table>
      </fieldset>
    </fieldset>

    <input type="button" name="btnSalvar" id="btnSalvar" value="Salvar" />
    <input type="button" name="btnLimpar" id="btnLimpar" value="Limpar" />
    <div >
      <fieldset style="width: 600px;">
        <legend>Grade Mensal</legend>

        <div id="gridMensal" />
      </fieldset>
    </div>

  </form>
</div>

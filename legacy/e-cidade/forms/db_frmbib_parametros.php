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

//MODULO: biblioteca
$clbib_parametros->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi17_nome");

$sBtnName  = "incluir";
$sBtnValue = "Incluir";
if ( $db_opcao == 2 ) {

  $sBtnName   = "alterar";
  $sBtnValue = "Alterar";
}
?>
<form class="container" name="form1" method="post" action="">

  <fieldset>
    <legend>Parâmetros</legend>
    <table class='form-container'>
      <tr>
        <td nowrap title="<?=$Tbi26_biblioteca?>">
          <label for="bi17_nome">
            <?php db_ancora($Lbi26_biblioteca, "", 3);?>
          </label>
        </td>
        <td>
          <?php
            db_input('bi26_codigo',     10, $Ibi26_codigo,     true, 'hidden', 3);
            db_input('bi26_biblioteca', 10, $Ibi26_biblioteca, true, 'hidden', 3);
            db_input('bi17_nome',       50, $Ibi17_nome,       true, 'text',   3);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Tbi26_leitorbarra?>">
          <label for="bi26_leitorbarra"><?=$Lbi26_leitorbarra?></label>
        </td>
        <td>
          <?php
            db_select('bi26_leitorbarra', array('N'=>'NÃO','S'=>'SIM'), true, $db_opcao);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Informe o tipo de impressora que será utilizado para impressão dos comprovantes.">
          <label for="bi26_impressora">Impressão de Comprovantes:</label>
        </td>
        <td>
          <?php
            $aImpressora = array(1 => 'Papel A4', 2 => 'Papel 80mm');
            db_select('bi26_impressora', $aImpressora, true, $db_opcao);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>

  <input name="<?=$sBtnName?>" type="submit" id="db_opcao" value="<?=$sBtnValue?>" <?=($db_botao==false?"disabled":"")?> />

</form>
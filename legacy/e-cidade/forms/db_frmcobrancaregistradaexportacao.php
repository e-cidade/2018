<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
<form action="" method="post" name="formularioExportacao" id="formularioExportacao">
  <fieldset>
    <legend>Geração de Remessa</legend>
    <table>
      <tr>
        <td>
          <label class="bold" id="labelConvenio" for="codigoConvenio"><a href="javascript:;">Convênio:</a></label>
        </td>
        <td>
          <?php
            db_input("ar11_sequencial", 1, 1, true, "text", 1);
            db_input("ar11_nome", 1, 1, true, "text", 3);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label class="bold" id="labelQuebraLinha" for="lQuebraLinha">Gerar última linha do arquivo com quebra de linha?</label>
        </td>
        <td>
          <?php
            $aOpcoes = array( 0 => 'NÃO', 1 => 'SIM');
            db_select('lQuebraLinha', $aOpcoes, true, 1, ""); 
          ?>
        </td>        
      </tr>
    </table>
  </fieldset>
  <input type="button" id="processar" value="Processar"/>
</form>
<script type="text/javascript">

  /**
   * Constante contendo o caminho para o RPC
   */
  const RPC = "arr4_cobrancaregistrada.RPC.php";

  var oLookUpConvenio = new DBLookUp($('labelConvenio'), $('ar11_sequencial'), $('ar11_nome'), {
    'sArquivo'      : 'func_cadconvenio.php',
    'sObjetoLookUp' : 'db_iframe_cadconvenio',
    'sLabel'        : 'Pesquisar Convênio'
  });

  /**
   * Capturamos o evento de click do botão processar para que possamos realizar
   * a emissão da remessa desejada
   */
  $('processar').observe('click', function(){

    if (empty( $('ar11_sequencial').value ) ) {
      return alert("O campo Convênio é de preenchimento obrigatório.");
    }
   
    js_OpenJanelaIframe(
      'CurrentWindow.corpo',
      'db_iframe_carne',
      'arr4_cobrancaregistradaexportacaogeracao.php?codigo_convenio=' + $('ar11_sequencial').value + '&lQuebraLinha=' + $('lQuebraLinha').value,
      'Processando Geração...',
      true
    );
  });
</script>

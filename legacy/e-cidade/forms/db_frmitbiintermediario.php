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

$clrotulo = new rotulocampo;
$clrotulo->label("it03_guia");

?>
<style type="text/css">
#linkCgm, #labelCgm{
  cursor: pointer;
}
#it35_cgm{
  width: 122px !important;
}
#it35_nome{
  width: 385px !important;
}
</style>
<fieldset>
  <legend>Intermediadores</legend>
  <table width="660px">
    <tr>
      <td width="136px"><strong>Número da guia de ITBI:</strong></td>
      <td align="left"><?php db_input('it03_guia', 14, $Iit03_guia, true, 'text', 3); ?></td>
    </tr>
    <tr>
      <td><a id="linkCgm" href="#"><label for="it35_cgm" id="labelCgm"><strong>CGM/Nome:</strong><label></a></td>
      <td>
        <?php
          $GLOBALS['Sit35_cgm']  = 'CGM/Nome';
          $GLOBALS['Git35_nome'] = 't';
          db_input('it35_cgm',  null, 1, true, 'text', 1);
          db_input('it35_nome', null, 0, true, 'text', 1, null, null, null, null, 60);
        ?>
      </td>
    </tr>
    <tr>
      <td><strong><label for="it35_cnpj_cpf">CPF/CNPJ:</label></strong></td>
      <td>
        <?php
          $GLOBALS['Sit35_cnpj_cpf'] = 'CPF/CNPJ';
          db_input('it35_cnpj_cpf', 14, 1, true, 'text', 1, null, null, null, null, 14);
        ?>
      </td>
    </tr>
    <tr>
      <td><strong><label for="it35_creci">CRECI:</label></strong></td>
      <td>
        <?php db_input('it35_creci', 14, 0, true, 'text', 1, null, null, null, null, 20); ?>
      </td>
    </tr>
    <tr>
      <td><strong><label for="it35_principal">Principal:</label></strong></td>
      <td>
        <select id="it35_principal">
          <option value="0">Não</option>
          <option value="1">Sim</option>
        </select>
      </td>
    </tr>
    <tr>
      <td align="center" colspan="2">
        <br/>
        <input id="bt_opcao" type="submit" name="bt_opcao" value="Salvar" onClick="js_salvar();" />
        <input id="novo" type="button" name="novo" value="Novo" onclick="js_novo();" />
        <input id="it35_sequencial" type="hidden" name="it35_sequencial" value="" />
        <input id="it03_guia" type="hidden" name="it03_guia" value="<?php echo !empty($it03_guia) ? $it03_guia : ''; ?>" />
      </td>
    </tr>
    <tr><td colspan="2"><td></tr>
    <tr>
      <td colspan="2">
        <div id="gridIntermediadores" width="700px"></div>
      </td>
    </tr>
  </table>
</fieldset>
<script type="text/javascript">

  const RPC      = 'itb1_itbiintermediadores.RPC.php';
  const MENSAGEM = 'tributario.itbi.itb1_intermediadores.';

  var oCgm        = $('it35_cgm'),
      oNome       = $('it35_nome'),
      oCnpjCpf    = $('it35_cnpj_cpf'),
      oCreci      = $('it35_creci'),
      oPrincipal  = $('it35_principal'),
      oSequencial = $('it35_sequencial'),
      oGuia       = $('it03_guia');

  gridIntermediadores = new DBGrid("gridIntermediadores");
  gridIntermediadores.nameInstance = "gridIntermediadores";

  gridIntermediadores.setCellAlign(new Array("left", "center", "center", "center", "center", "center"));
  gridIntermediadores.setHeader(new Array("Nome", "CGM", "CPF/CNPJ", "CRECI", "Principal", "Opções"));
  gridIntermediadores.setCellWidth(new Array("35%", "10%", "17%", "18%", "10%", "10%"));
  gridIntermediadores.setHeight(80);
  gridIntermediadores.clearAll(true);
  gridIntermediadores.show(document.getElementById('gridIntermediadores'));

  var oLookupCgm = new DBLookUp($('labelCgm'), oCgm, oNome, {
    'sArquivo'              : 'func_nome.php',
    'sObjetoLookUp'         : 'db_iframe_cgm',
    'sLabel'                : 'Pesquisa',
    'oBotaoParaDesabilitar' : $('excluir'),
    'aCamposAdicionais'     : ['z01_numcgm', 'z01_nome', 'z01_cgccpf']
  });

  oLookupCgm.callBackClick = function(sErro, sParam, iNumCgm, sNome, sCnpjCpf){

    oCgm.value     = iNumCgm;
    oNome.value    = sNome;
    oCnpjCpf.value = sCnpjCpf;

    oNome.readOnly    = true;
    oCnpjCpf.readOnly = true;

    oNome.classList.add("readOnly");
    oCnpjCpf.classList.add("readOnly");

    db_iframe_cgm.hide();
  };

  oNome.className = "field-size8";

  oLookupCgm.callBackChange = function(){

    if(arguments[0] == true){

      oCgm.value      = '';
      oNome.value     = '';
      oCnpjCpf.value  = '';

      oNome.readOnly    = false;
      oCnpjCpf.readOnly = false;

      oNome.classList.remove("readOnly");
      oCnpjCpf.classList.remove("readOnly");

      return false;
    }

    oNome.value    = arguments[1];
    oCnpjCpf.value = arguments[2];

    oNome.readOnly    = true;
    oCnpjCpf.readOnly = true;

    oNome.classList.add("readOnly");
    oCnpjCpf.classList.add("readOnly");

  };

  oCgm.onblur = function(){

    if(this.value == ''){

      oNome.value    = '';
      oCnpjCpf.value = '';
      oCreci.value   = '';

      oNome.readOnly    = false;
      oCnpjCpf.readOnly = false;

      oNome.classList.remove("readOnly");
      oCnpjCpf.classList.remove("readOnly");
    }
    return true;
  };

  function js_valida(){

    if(oNome.value == ''){

      alert(_M(MENSAGEM+'campo_obrigatorio_nome'));
      return false;
    }

    if(oCnpjCpf.value == ''){

      alert(_M(MENSAGEM+'campo_obrigatorio_cnpj_cpf'));
      return false;
    }

    return true;
  }

  function js_salvar(){

    if(js_valida()){

      var oParametros = {
        sExecucao   : 'salvarItbiIntermediadores',
        iCgm        : oCgm.value,
        sNome       : oNome.value,
        iCnpjCpf    : oCnpjCpf.value,
        sCreci      : oCreci.value,
        iPrincipal  : oPrincipal.value,
        iSequencial : oSequencial.value,
        iItbi       : oGuia.value
      };

      new AjaxRequest(RPC, oParametros, function(oRetorno, erro){

        alert(oRetorno.sMessage.urlDecode());

        if(erro){
          return false;
        }

        js_novo();
        js_carregaGridItbiIntermediadores();
      }).setMessage(_M(MENSAGEM+'itbi_intermediadores_salvar')).execute();
    }
  }

  function js_novo(){

    oCgm.value               = '';
    oNome.value              = '';
    oCnpjCpf.value           = '';
    oCreci.value             = '';
    oPrincipal.selectedIndex = 0;
    oSequencial.value        = '';

    oNome.readOnly      = false;
    oCnpjCpf.readOnly   = false;
    oPrincipal.disabled = false;

    oNome.classList.remove("readOnly");
    oCnpjCpf.classList.remove("readOnly");
    oPrincipal.classList.remove("readOnly");
  }

  function js_carregaGridItbiIntermediadores(){

    if ( oGuia.value.empty() ) {
      return false;
    }

    var oParametros = {
      sExecucao : 'getItbiIntermediadores',
      sGuia     : oGuia.value
    };

    new AjaxRequest(RPC, oParametros, function(oRetorno, lErro){

      if(lErro){

        alert(oRetorno.sMessage.urlDecode());
        return false;
      }

      gridIntermediadores.clearAll(true);

      oRetorno.aItbiIntermediador.each(function(oItbiIntermediador, iLinha){

        var sNome = oItbiIntermediador.it35_nome.urlDecode();

        if(sNome.length > 25){
          sNome = sNome.slice(0, 25) + "...";
        }

        var aLinha = new Array();
        aLinha[0] = sNome;
        aLinha[1] = oItbiIntermediador.it35_cgm.urlDecode();
        aLinha[2] = oItbiIntermediador.it35_cnpj_cpf.urlDecode();
        aLinha[3] = oItbiIntermediador.it35_creci.urlDecode();

        var sPrincipal = 'Não';
        if(oItbiIntermediador.it35_principal.urlDecode() == 't'){
          sPrincipal = 'Sim';
        }
        aLinha[4] = sPrincipal;

        var sAlterar = '<a href="#" onclick="js_alterar('+oItbiIntermediador.it35_sequencial.urlDecode()+')">A</a>';
        var sExcluir = '<a href="#" onclick="js_excluir('+oItbiIntermediador.it35_sequencial.urlDecode()+')">E</a>';

        aLinha[5] = sAlterar+'&nbsp;&nbsp;&nbsp;'+sExcluir;

        gridIntermediadores.addRow(aLinha, true, false, true);

      });

      gridIntermediadores.renderRows();

      gridIntermediadores.getRows().each(function(oLinha, iLinha){

        if(oRetorno.aItbiIntermediador[iLinha].it35_nome.urlDecode().length > 25){

          $(oLinha.getId()).setAttribute('rel', oRetorno.aItbiIntermediador[iLinha].it35_sequencial.urlDecode());
          gridIntermediadores.setHint(oLinha.getRowNumber(), 0, oRetorno.aItbiIntermediador[iLinha].it35_nome.urlDecode());
        }
      });

      oPrincipal.selectedIndex = 1;
      oPrincipal.disabled = true;
      oPrincipal.classList.add("readOnly");

      if(gridIntermediadores.getNumRows() > 0){
        oPrincipal.selectedIndex = 0;
        oPrincipal.disabled = false;
        oPrincipal.classList.remove("readOnly");
      }
    }).setMessage(_M(MENSAGEM+'carrega_dados_grid_intermediadores')).execute();
  }

  function js_alterar(iSequencial){

    js_novo();
    var oParametros = {
      sExecucao   : 'getIntermediador',
      iSequencial : iSequencial
    };

    new AjaxRequest(RPC, oParametros, function(oRetorno, lErro){

      if(lErro){
        alert(oRetorno.sMessage.urlDecode());
        return false;
      }

      oCgm.value        = oRetorno.oIntermediador.iCgm.urlDecode();
      oNome.value       = oRetorno.oIntermediador.sNome.urlDecode();
      oCnpjCpf.value    = oRetorno.oIntermediador.iCnpjCpf.urlDecode();
      oCreci.value      = oRetorno.oIntermediador.sCreci.urlDecode();
      oSequencial.value = oRetorno.oIntermediador.iSequencial.urlDecode();

      if(oRetorno.oIntermediador.lPrincipal.urlDecode() == 't'){
        oPrincipal.selectedIndex = 1;
        oPrincipal.disabled = true;
        oPrincipal.classList.add("readOnly");
      }

      if(oRetorno.oIntermediador.iCgm != ''){

        oNome.readOnly    = true;
        oCnpjCpf.readOnly = true;

        oNome.classList.add("readOnly");
        oCnpjCpf.classList.add("readOnly");
      }

    }).setMessage(_M(MENSAGEM+'consulta_intermediador_alteracao')).execute();
  }

  function js_excluir(iSequencial){

    if(confirm(_M(MENSAGEM+'excluir_intermediador'))){

      var oParametros = {
        sExecucao   : 'excluirItbiIntermediadores',
        iItbi       : oGuia.value,
        iSequencial : iSequencial
      };

      new AjaxRequest(RPC, oParametros, function(oRetorno, lErro){

        alert(oRetorno.sMessage.urlDecode());

        if(lErro){
          return false;
        }

        js_novo();
        js_carregaGridItbiIntermediadores();
      }).setMessage(_M(MENSAGEM+'carrega_excluir_intermediadores')).execute();
    }
  }

  oNome.readOnly = false;
  oNome.classList.remove("readOnly");

  js_novo();
  js_carregaGridItbiIntermediadores();
</script>

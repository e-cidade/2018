<?php
  /*
   *     E-cidade Software Publico para Gestao Municipal                
   *  Copyright (C) 2015  DBSeller Servicos de Informatica             
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
  
  require_once(modification("libs/db_stdlib.php"));
  require_once(modification("libs/db_conecta.php"));
  require_once(modification("libs/db_sessoes.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("dbforms/db_classesgenericas.php"));
  const MENSAGEM = "recursoshumanos.pessoal.pes4_configuracaofinanceirarra.";

  $oDaoTipoAsseFinanceiroRRA = new cl_tipoassefinanceirorra();
  $oDaoTipoAsseFinanceiroRRA->rotulo->label();
  db_postmemory($_POST);

  $db_opcao = 1;


  try {

    if (isset($incluir)) {

      $oRubricaProvento       = RubricaRepository::getInstanciaByCodigo($rh172_rubricaprovento);
      $oRubricaIRRF           = RubricaRepository::getInstanciaByCodigo($rh172_rubricairrf);
      $oRubricaPrevidencia    = RubricaRepository::getInstanciaByCodigo($rh172_rubricaprevidencia);
      $oRubricaPensao         = RubricaRepository::getInstanciaByCodigo($rh172_rubricapensao);
      $oRubricaParcelaDeducao = RubricaRepository::getInstanciaByCodigo($rh172_rubricaparceladeducao);
      $oRubricaMolestia       = RubricaRepository::getInstanciaByCodigo($rh172_rubricamolestia);
      $oTipoAssentamento      = TipoAssentamentoRepository::getInstanciaPorCodigo($rh165_tipoasse);

      $oInformacoesFinanceirasRRA = new InformacoesFinanceirasTipoAssentamentoRRA();
      $oInformacoesFinanceirasRRA->setSequencial(null);
      $oInformacoesFinanceirasRRA->setTipoAssentamento($oTipoAssentamento);
      $oInformacoesFinanceirasRRA->setRubricaProvento($oRubricaProvento);
      $oInformacoesFinanceirasRRA->setRubricaIrrf($oRubricaIRRF);
      $oInformacoesFinanceirasRRA->setRubricaPrevidencia($oRubricaPrevidencia);
      $oInformacoesFinanceirasRRA->setRubricaPensao($oRubricaPensao);
      $oInformacoesFinanceirasRRA->setRubricaParcelaDeducao($oRubricaParcelaDeducao);
      $oInformacoesFinanceirasRRA->setRubricaMolestia($oRubricaMolestia);

      InformacoesFinanceirasTipoAssentamentoRRARepository::persist($oInformacoesFinanceirasRRA);
      $sMensagemRetorno = "Inclusão efetuada com sucesso";
    }

    if (isset($alterar)) {

      $oRubricaProvento       = RubricaRepository::getInstanciaByCodigo($rh172_rubricaprovento);
      $oRubricaIRRF           = RubricaRepository::getInstanciaByCodigo($rh172_rubricairrf);
      $oRubricaPrevidencia    = RubricaRepository::getInstanciaByCodigo($rh172_rubricaprevidencia);
      $oRubricaPensao         = RubricaRepository::getInstanciaByCodigo($rh172_rubricapensao);
      $oRubricaParcelaDeducao = RubricaRepository::getInstanciaByCodigo($rh172_rubricaparceladeducao);
      $oRubricaMolestia       = RubricaRepository::getInstanciaByCodigo($rh172_rubricamolestia);

      $oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($rh165_tipoasse);
      $oInformacoesFinanceiras = InformacoesFinanceirasTipoAssentamentoRRARepository::getInstanciaByTipoAssentamento($oTipoAssentamento);
      $oInformacoesFinanceiras->setRubricaProvento($oRubricaProvento);
      $oInformacoesFinanceiras->setRubricaIrrf($oRubricaIRRF);
      $oInformacoesFinanceiras->setRubricaPrevidencia($oRubricaPrevidencia);
      $oInformacoesFinanceiras->setRubricaPensao($oRubricaPensao);
      $oInformacoesFinanceiras->setRubricaParcelaDeducao($oRubricaParcelaDeducao);
      $oInformacoesFinanceiras->setRubricaMolestia($oRubricaMolestia);

      InformacoesFinanceirasTipoAssentamentoRRARepository::persist($oInformacoesFinanceiras);
      $sMensagemRetorno = "Alteração efetuada com sucesso";
    }

    if (isset($excluir)) {

      $oDaoTipoAsseFinanceiroRRA->excluir($rh172_sequencial);

      if ($oDaoTipoAsseFinanceiroRRA->erro_status == 0) {
        throw new DBException($oDaoTipoAsseFinanceiroRRA->erro_msg);
      }

      $sMensagemRetorno = "Exclusão efetuada com sucesso";
    }

    if (isset($opcao)) {
      $db_opcao = ($opcao == 'alterar')? 2 : 3;
    }

    if (isset($rh172_sequencial)) {

      $sCamposTipoAsseFinanceiroRRA  = "rh172_tipoasse as rh165_tipoasse,";
      $sCamposTipoAsseFinanceiroRRA .= "h12_descr,";
      $sCamposTipoAsseFinanceiroRRA .= "rh172_rubricaprovento,";
      $sCamposTipoAsseFinanceiroRRA .= "rubricaprovento.rh27_descr as srubricaprovento,";
      $sCamposTipoAsseFinanceiroRRA .= "rh172_rubricairrf,";
      $sCamposTipoAsseFinanceiroRRA .= "rubricairrf.rh27_descr as srubricairrf,";
      $sCamposTipoAsseFinanceiroRRA .= "rh172_rubricaprevidencia,";
      $sCamposTipoAsseFinanceiroRRA .= "rubricaprevidencia.rh27_descr as srubricaprevidencia,";
      $sCamposTipoAsseFinanceiroRRA .= "rh172_rubricapensao,";
      $sCamposTipoAsseFinanceiroRRA .= "rubricapensao.rh27_descr as srubricapensao,";
      $sCamposTipoAsseFinanceiroRRA .= "rh172_rubricaparceladeducao, ";
      $sCamposTipoAsseFinanceiroRRA .= "rubricaparceladeducao.rh27_descr as srubricaparceladeducao,";
      $sCamposTipoAsseFinanceiroRRA .= "rh172_rubricamolestia, ";
      $sCamposTipoAsseFinanceiroRRA .= "rubricamolestia.rh27_descr as srubricamolestia";

      $sSqlTipoasseFinanceiroRRA = $oDaoTipoAsseFinanceiroRRA->sql_query($rh172_sequencial, $sCamposTipoAsseFinanceiroRRA);
      $rsTipoAsseFinanceiroRRA   = db_query($sSqlTipoasseFinanceiroRRA); 

      if (!$rsTipoAsseFinanceiroRRA) {
        throw new DBException("Ocorreu um erro ao buscar as configurações.");
      }

      if (pg_num_rows($rsTipoAsseFinanceiroRRA) == 1) {
        db_fieldsmemory($rsTipoAsseFinanceiroRRA, 0);
      }
    }

  } catch (Exception $oException) {
    $sMensagemRetorno = $oException->getMessage();
    db_redireciona('pes4_configuracaofinanceirarra001.php?sMensagemRetorno='.$sMensagemRetorno);
  }
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, prototype.js, estilos.css, widgets/DBLookUp.widget.js");
    ?>
  </head>
  <body>
    <form action="" method="POST" name="form1" class="container">
      <fieldset class="form-container" style="width: 600px; margin: 0 auto;">
        <?php db_input('rh172_sequencial', 40, 1, true, 'hidden', 3); ?>
        <legend>Configurações financeiras do RRA</legend>
        <table>
          <tr>
            <td><label><a id="sLabelTipoAssentamento" for="rh165_tipoasse"><?php echo $Lrh172_tipoasse ?></a></label></td>
            <td>
              <?php db_input('rh165_tipoasse', 4, 0, true, 'text', ($db_opcao == 2 ) ? 3 : $db_opcao, 'lang="h12_codigo"'); ?>
              <?php db_input('h12_descr', 40, 1, true, 'text', 3, 'lang="h12_descr"'); ?>
            </td>
          </tr>
        </table>
        <fieldset class="form-container" style="width: 600px; margin: 05px auto 0 auto;">
        <legend>Rubricas</legend>
          <table>
            <tr>
              <td><label><a id="sLabelProvento" for="rh172_rubricaprovento"><?php echo $Lrh172_rubricaprovento ?></a></label></td>
              <td>
                <?php db_input('rh172_rubricaprovento', 4, 0, true, 'text', $db_opcao, 'lang="rh27_rubric"'); ?>
                <?php db_input('srubricaprovento', 40, 1, true, 'text', 3, 'lang="rh27_descr"'); ?>
              </td>
            </tr>
            <tr>
              <td><label><a id="sLabelIRRF" for="rh172_rubricairrf"><?php echo $Lrh172_rubricairrf ?></a></label></td>
              <td>
                <?php db_input('rh172_rubricairrf', 4, 0, true, 'text', $db_opcao, 'lang="rh27_rubric"'); ?>
                <?php db_input('srubricairrf', 40, 1, true, 'text', 3, 'lang="rh27_descr"'); ?>
              </td>
            </tr>
            <tr>
              <td><label><a id="sLabelPrevidencia" for="rh172_rubricaprevidencia"><?php echo $Lrh172_rubricaprevidencia ?></a></label></td>
              <td>
                <?php db_input('rh172_rubricaprevidencia', 4, 0, true, 'text', $db_opcao, 'lang="rh27_rubric"'); ?>
                <?php db_input('srubricaprevidencia', 40, 1, true, 'text', 3, 'lang="rh27_descr"'); ?>
              </td>
            </tr>
            <tr>
              <td><label><a id="sLabelPensao" for="rh172_rubricapensao"><?php echo $Lrh172_rubricapensao ?></a></label></td>
              <td>
                <?php db_input('rh172_rubricapensao', 4, 0, true, 'text', $db_opcao, 'lang="rh27_rubric"'); ?>
                <?php db_input('srubricapensao', 40, 1, true, 'text', 3, 'lang="rh27_descr"'); ?>
              </td>
            </tr>
            <tr>
              <td><label><a id="sLabelParcelaDeducao" for="rh172_rubricaparceladeducao"><?php echo $Lrh172_rubricaparceladeducao ?></a></label></td>
              <td>
                <?php db_input('rh172_rubricaparceladeducao', 4, 0, true, 'text', $db_opcao, 'lang="rh27_rubric"'); ?>
                <?php db_input('srubricaparceladeducao', 40, 1, true, 'text', 3, 'lang="rh27_descr"'); ?>
              </td>
            </tr>
            <tr>
              <td><label><a id="sLabelMolestia" for="rh172_rubricamolestia"><?php echo $Lrh172_rubricamolestia ?></a></label></td>
              <td>
                <?php db_input('rh172_rubricamolestia', 4, 0, true, 'text', $db_opcao, 'lang="rh27_rubric"'); ?>
                <?php db_input('srubricamolestia', 40, 1, true, 'text', 3, 'lang="rh27_descr"'); ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>


      <?php

        $sLabel = "Incluir";

        if ($db_opcao == 2) {
          $sLabel = "Alterar";
        }

        if ($db_opcao == 3) {
          $sLabel = "Excluir";  
        }
      ?>

      <input type="submit" value="<?php echo $sLabel ?>" name="<?php echo strtolower($sLabel) ?>" onClick="return validaCampos()" />
      <input type="button" value="Novo" onClick="window.location.href = window.location.href;" <?php echo $db_opcao == 1 ? "disabled" : "" ?> />

      <div class="container">
        <?php

          $iInstituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
          $aChavePri    = array("rh172_sequencial" => isset($rh172_sequencial) ? $rh172_sequencial : '');

          $sCampos  = "rh172_sequencial,";
          $sCampos .= "h12_descr as rh172_tipoasse,     ";
          $sCampos .= "rh172_rubricaprovento || ' - ' || rubricaprovento.rh27_descr as rh172_rubricaprovento";

          $sWhere  = "     rh172_instit = {$iInstituicao}";

          if (isset($rh172_sequencial) && !empty($rh172_sequencial)) {
            $sWhere .= " and rh172_sequencial <> $rh172_sequencial ";
          } 

          $sWhere .= " group by rh172_sequencial, h12_descr, rubricaprovento.rh27_descr, rh172_rubricaprovento";

          $sSqlTipoasseFinanceiro   = $oDaoTipoAsseFinanceiroRRA->sql_query(null,$sCampos,null,$sWhere);
          $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
          $cliframe_alterar_excluir->chavepri= $aChavePri;
          $cliframe_alterar_excluir->sql     = $sSqlTipoasseFinanceiro;
          $cliframe_alterar_excluir->campos  = "rh172_sequencial, rh172_tipoasse, rh172_rubricaprovento";
          $cliframe_alterar_excluir->legenda = "Assentamentos Cadastrados";
          $cliframe_alterar_excluir->iframe_height = "160";
          $cliframe_alterar_excluir->iframe_width  = "850";
          $cliframe_alterar_excluir->iframe_alterar_excluir(1);
        ?>
      </div>
    </form>

    <?php
      if (isset($sMensagemRetorno)) {
        db_msgbox($sMensagemRetorno);
        db_redireciona('pes4_configuracaofinanceirarra001.php');
      }
    ?>

    <script>

      const MENSAGEM = "recursoshumanos.pessoal.pes4_configuracaofinanceirarra.";

      (function() {

        var oTipoAssentamento = new DBLookUp($('sLabelTipoAssentamento'), $('rh165_tipoasse'), $('h12_descr'), {
            'sArquivo'             : 'func_tipoasse.php',
            'sObjetoLookUp'        : 'db_iframe_rhrubricas',
            'sLabel'               : 'Pesquisar Rubrica de Provento',
            "aParametrosAdicionais": ["lPesquisaNatureza=true"]
        });

        oTipoAssentamento.setCamposAdicionais(['h12_natureza']);

        oTipoAssentamento.callBackClick = function() {

          var iNaturezaAssentamento = arguments[2];
          var iTipoAssentamento = arguments[0];
          var sTipoAssentamento = arguments[1];

          if (iNaturezaAssentamento != 3) {
            location.href = 'pes4_lancamentotiposassentamento001.php?rh165_tipoasse='+iTipoAssentamento+'&h12_descr='+sTipoAssentamento;
          }

          $('rh165_tipoasse').value = iTipoAssentamento;
          $('h12_descr').value      = sTipoAssentamento;
          var oObjetoLookUp         = eval(this.oParametros.sObjetoLookUp);
          oObjetoLookUp.hide();
        }

        oTipoAssentamento.callBackChange = function() {

          var iNaturezaAssentamento = arguments[3];
          var sDescricao            = arguments[2];

          if (arguments[1]) {

            sDescricao = arguments[0];
            $('rh165_tipoasse').value = '';
          }

          if (iNaturezaAssentamento != 3) {
            location.href = 'pes4_lancamentotiposassentamento001.php?rh165_tipoasse='+ $('rh165_tipoasse').value +'&h12_descr='+sDescricao;
          }

          $('h12_descr').value = sDescricao;
        }

        var oRubricaProvento = new DBLookUp($('sLabelProvento'), $('rh172_rubricaprovento'), $('srubricaprovento'), {
            'sArquivo'             : 'func_rhrubricas.php',
            'sObjetoLookUp'        : 'db_iframe_rhrubricas',
            'sLabel'               : 'Pesquisar Rubrica de Provento'
        });

        var oRubricaPrevidencia = new DBLookUp($('sLabelPrevidencia'), $('rh172_rubricaprevidencia'), $('srubricaprevidencia'), {
            'sArquivo'             : 'func_rhrubricas.php',
            'sObjetoLookUp'        : 'db_iframe_rhrubricas',
            'sLabel'               : 'Pesquisar Rubrica de Previdência'
        });

        var oRubricaIRRF = new DBLookUp($('sLabelIRRF'), $('rh172_rubricairrf'), $('srubricairrf'), {
            'sArquivo'             : 'func_rhrubricas.php',
            'sObjetoLookUp'        : 'db_iframe_rhrubricas',
            'sLabel'               : 'Pesquisar Rubrica de IRRF'
        });

        var oRubricaPensao = new DBLookUp($('sLabelPensao'), $('rh172_rubricapensao'), $('srubricapensao'), {
            'sArquivo'             : 'func_rhrubricas.php',
            'sObjetoLookUp'        : 'db_iframe_rhrubricas',
            'sLabel'               : 'Pesquisar Rubrica de Pensão'
        });

        var oRubricaParcelaDeducao = new DBLookUp($('sLabelParcelaDeducao'), $('rh172_rubricaparceladeducao'), $('srubricaparceladeducao'), {
            'sArquivo'             : 'func_rhrubricas.php',
            'sObjetoLookUp'        : 'db_iframe_rhrubricas',
            'sLabel'               : 'Pesquisar Rubrica de Parcela de Dedução'
        });

        var oRubricaMolestia = new DBLookUp($('sLabelMolestia'), $('rh172_rubricamolestia'), $('srubricamolestia'), {
            'sArquivo'             : 'func_rhrubricas.php',
            'sObjetoLookUp'        : 'db_iframe_rhrubricas',
            'sLabel'               : 'Pesquisar Rubrica Moléstia'
        });

      })();

      document.form1.onsubmit  = function() {
        
        if ( empty($F(rh165_tipoasse))) {

          alert(_M(MENSAGEM + 'tipo_asse_nao_informado'));
          return false;
        }
        if ( empty($F(rh172_rubricaprovento))) {

          alert(_M(MENSAGEM + 'rubrica_provendo_nao_informado'));
          return false;
        }
        if ( empty($F(rh172_rubricairrf))) {

          alert(_M(MENSAGEM + 'rubrica_irrf_nao_informado'));
          return false;
        }
        if ( empty($F(rh172_rubricaprevidencia))) {

          alert(_M(MENSAGEM + 'rubrica_previdencia_nao_informado'));
          return false;
        }
        if ( empty($F(rh172_rubricapensao))) {

          alert(_M(MENSAGEM + 'rubrica_pensao_nao_informado'));
          return false;
        }
        if ( empty( $F(rh172_rubricaparceladeducao))) {

          alert(_M(MENSAGEM + 'rubrica_parceladeducao_nao_informado'));
          return false;
        }
        if ( empty( $F(rh172_rubricamolestia))) {

          alert(_M(MENSAGEM + 'rubrica_molestia_nao_informado'));
          return false;
        }

        return true;
      }
    
    </script>

    <?php
      db_menu();
    ?>
  </body>
</html>

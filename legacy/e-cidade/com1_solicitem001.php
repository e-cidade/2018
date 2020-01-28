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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_solicitem_classe.php"));
require_once(modification("classes/db_solicitemvinculo_classe.php"));
require_once(modification("classes/db_solicitemunid_classe.php"));
require_once(modification("classes/db_solicitempcmater_classe.php"));
require_once(modification("classes/db_solicitemele_classe.php"));
require_once(modification("classes/db_solicita_classe.php"));
require_once(modification("classes/db_solicitatipo_classe.php"));
require_once(modification("classes/db_db_depart_classe.php"));
require_once(modification("classes/db_pcdotac_classe.php"));
require_once(modification("classes/db_pcmater_classe.php"));
require_once(modification("classes/db_pcmaterele_classe.php"));
require_once(modification("classes/db_pcparam_classe.php"));
require_once(modification("classes/db_matunid_classe.php"));
require_once(modification("classes/db_orcreserva_classe.php"));
require_once(modification("classes/db_orcreservasol_classe.php"));
require_once(modification("classes/db_orcelemento_classe.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_procandam_classe.php"));
require_once(modification("classes/db_solicitemprot_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("classes/db_solandpadrao_classe.php"));
require_once(modification("classes/db_solandpadraodepto_classe.php"));
require_once(modification("classes/db_solandam_classe.php"));
require_once(modification("classes/db_solandamand_classe.php"));
require_once(modification("classes/db_solordemtransf_classe.php"));
require_once(modification("classes/db_pcproc_classe.php"));
require_once(modification("classes/db_pcprocitem_classe.php"));
require_once(modification("classes/db_empautitempcprocitem_classe.php"));
require_once(modification("classes/db_liclicitem_classe.php"));
require_once(modification("classes/db_pcorcamitemlic_classe.php"));
require_once(modification("classes/db_pcorcamitemproc_classe.php"));
require_once(modification("classes/db_pcorcamitem_classe.php"));
require_once(modification("classes/db_pcorcam_classe.php"));
require_once(modification("classes/db_pcorcamjulg_classe.php"));
require_once(modification("classes/db_pcorcamdescla_classe.php"));
require_once(modification("classes/db_pcorcamtroca_classe.php"));
require_once(modification("classes/db_pcorcamval_classe.php"));
require_once(modification("classes/db_pcorcamforne_classe.php"));
require_once(modification("classes/db_pcorcamfornelic_classe.php"));
require_once(modification("classes/db_solicitalog_classe.php"));
require_once(modification("classes/db_liclicita_classe.php"));
require_once(modification("classes/db_liclicitemlote_classe.php"));
require_once(modification("classes/db_veiculos_classe.php"));
require_once(modification("classes/db_solicitemveic_classe.php"));
require_once(modification("classes/db_proctransferproc_classe.php"));
require_once(modification("model/itempacto.model.php"));
require_once(modification("classes/solicitacaocompras.model.php"));
require_once(modification("model/ItemEstimativa.model.php"));

$clsolicitem            = new cl_solicitem;
$clsolicitemunid        = new cl_solicitemunid;
$clsolicitempcmater     = new cl_solicitempcmater;
$clsolicitemele         = new cl_solicitemele;
$clsolicita             = new cl_solicita;
$clsolicitatipo         = new cl_solicitatipo;
$cldb_depart            = new cl_db_depart;
$clpcdotac              = new cl_pcdotac;
$clpcmater              = new cl_pcmater;
$clpcmaterele           = new cl_pcmaterele;
$clpcparam              = new cl_pcparam;
$clmatunid              = new cl_matunid;
$clorcreserva           = new cl_orcreserva;
$clorcreservasol        = new cl_orcreservasol;
$clorcelemento          = new cl_orcelemento;
$clprotprocesso         = new cl_protprocesso;
$clprocandam            = new cl_procandam;
$clsolicitemprot        = new cl_solicitemprot;
$cldb_config            = new cl_db_config;
$clsolandpadrao         = new cl_solandpadrao;
$clsolandpadraodepto    = new cl_solandpadraodepto;
$clsolandam             = new cl_solandam;
$clsolandamand          = new cl_solandamand;
$clsolordemtransf       = new cl_solordemtransf;
$clveiculos             = new cl_veiculos;
$clsolicitemveic        = new cl_solicitemveic;
$clproctransferproc     = new cl_proctransferproc;
$clempautitempcprocitem = new cl_empautitempcprocitem;

// Nova rotina inclui solicitação de compras em processo ou licitação
$clpcproc          = new cl_pcproc;
$clpcprocitem      = new cl_pcprocitem;
$clliclicitem      = new cl_liclicitem;
$clpcorcamitemlic  = new cl_pcorcamitemlic;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$clpcorcamitem     = new cl_pcorcamitem;
$clpcorcam         = new cl_pcorcam;
$clpcorcamjulg     = new cl_pcorcamjulg;
$clpcorcamdescla   = new cl_pcorcamdescla;
$clpcorcamtroca    = new cl_pcorcamtroca;
$clpcorcamval      = new cl_pcorcamval;
$clpcorcamforne    = new cl_pcorcamforne;
$clpcorcamfornelic = new cl_pcorcamfornelic;
$clsolicitalog     = new cl_solicitalog;
$clliclicita       = new cl_liclicita;
$clliclicitemlote  = new cl_liclicitemlote;
$iPactoPlano       = null;
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$aParametrosOrcamento = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));

$db_opcao = 1;
$db_botao = true;
$iframe   = true;

if (isset($verificado)) {
  if (isset($selecao)) {
    if ($selecao == "1" || $selecao == "2") {
      $db_opcao = 1;
      $db_botao = true;
      if (isset($opcao)) {
        if ($opcao == "alterar") {
          $db_opcao = 2;
        } else if ($opcao == "excluir") {
          $db_opcao = 3;
        }
      }
    } else if ($selecao == "3") {
      $db_opcao = 3;
      $db_botao = false;
      $iframe   = false;
    }
  }
}

$sqlerro = false;
$naodig  = false;

$msg_alertp = "";
$msg_alert  = "";
$msg_alert1 = "";
$msg_alert2 = "";

$result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "*"));
db_fieldsmemory($result_pcparam, 0);
/**
 * Verificamos se a solicitação é de um registro de preco(pc10_solicitacaotipo = 5);
 * devemos trazer na lookup dos itens somente os itens que fazem parte do registro de preco
 */
$oDaoSolicitaVInculo = db_utils::getDao("solicitavinculo");
$iRegistroPreco      = '';
$sWhere              = "pc53_solicitafilho = {$pc11_numero}";
$sSqlRegistroPreco   = $oDaoSolicitaVInculo->sql_query(null, "pc53_solicitapai", null, $sWhere);
$rsRegistroPreco     = $oDaoSolicitaVInculo->sql_record($sSqlRegistroPreco);
if ($oDaoSolicitaVInculo->numrows > 0) {
  $iRegistroPreco = db_utils::fieldsMemory($rsRegistroPreco, 0)->pc53_solicitapai;
}
if (isset($incluir) || isset($alterar)) {

  if (isset($incluir)) {
    $operacao = "Inclusão";
    if ($pc30_contrandsol == 't') {
      // db_msgbox("Controle do Andamento da Solicitação!!Em manutenção!!Não é nescessário dar Andamento Inicial!!");
    }
  } else {
    $operacao = "Alteração";
  }

  if ($pc30_obrigamat == 't') {
    if ((isset($pc16_codmater) && $pc16_codmater == "") || !isset($pc16_codmater)) {
      $naodig   = false;
      $sqlerro  = true;
      $erro_msg = "Usuário: \\n\\n$operacao abortada.\\nCódigo do material não informado. Campo obrigatório.\\n\\nAdministrador:";
    }
  }
  if ($pc30_obrigajust == 't') {
    if ((isset($pc11_just) && $pc11_just == "") || !isset($pc11_just)) {
      $naodig   = true;
      $sqlerro  = true;
      $erro_msg = "Usuário: \\n\\n$operacao abortada.\\nJustificativa para compra não informada.\\n\\nAdministrador:";
    } else {
      if (strlen(trim($pc11_just)) < $pc30_mincar) {
        $naodig   = true;
        $sqlerro  = true;
        $erro_msg = "Usuário: \\n\\n$operacao abortada.\\nJustificativa para compra deve ter no mínimo $pc30_mincar caracteres.\\n\\nAdministrador:";
      }
    }
  }
}

if (isset($incluir) && $sqlerro == false) {

  $sResumoRegistro = "";
  if ($digitouresumo == "false" || (isset($pc11_resum) && $pc11_resum == "")) {

    if (isset($iCodigoRegistro) && !empty($iCodigoRegistro)) {

      $oDaoSolicitem = new cl_solicitem();
      $sSqlSolicitem = $oDaoSolicitem->sql_query_file($iCodigoRegistro, "pc11_resum");
      $rsSolicitem   = @$oDaoSolicitem->sql_record($sSqlSolicitem);

      if ($oDaoSolicitem->numrows > 0) {
        $sResumoRegistro = db_utils::fieldsMemory($rsSolicitem, 0)->pc11_resum;
      }
    }
  }
  if ($sqlerro == false) {
    db_inicio_transacao();
    $pc11_vlrun = str_replace(",", ".", $pc11_vlrun);
    if (trim($pc11_vlrun) == "") {
      $pc11_vlrun = 0;
    }
    $clsolicitem->pc11_numero   = $pc11_numero;
    $clsolicitem->pc11_seq      = $pc11_seq;
    $clsolicitem->pc11_vlrun    = $pc11_vlrun;
    $clsolicitem->pc11_liberado = "f";
    $clsolicitem->pc11_prazo    = addslashes(stripslashes(trim($pc11_prazo)));
    $clsolicitem->pc11_pgto     = addslashes(stripslashes(trim($pc11_pgto)));
    $clsolicitem->pc11_resum    = addslashes(stripslashes(trim($sResumoRegistro)));
    $clsolicitem->pc11_just     = addslashes(stripslashes(trim($pc11_just)));
    $clsolicitem->incluir(empty($pc11_codigo) ? null : $pc11_codigo);
    $pc11_codigo = $clsolicitem->pc11_codigo;
    $erro_msg    = $clsolicitem->erro_msg;
    if ($clsolicitem->erro_status == 0) {
      $sqlerro = true;
    }
    if ($sqlerro == false) {
      if (isset($param) && trim($param) != "") {
        if (isset($codproc) && trim($codproc) != "") {
          // Esse teste e para o caso de materiais de licitacao excluidos, o processo é excluido qdo nao
          // tem mais itens e caso o usuario queira incluir mais itens usa novamente o processo (recria)
          $result_pcproc = $clpcproc->sql_record($clpcproc->sql_query_file($codproc));
          if ($clpcproc->numrows == 0) {
            $clpcproc->pc80_codproc = $codproc;
            $clpcproc->pc80_data    = date("Y-m-d", db_getsession("DB_datausu"));
            $clpcproc->pc80_usuario = db_getsession("DB_id_usuario");
            $clpcproc->pc80_depto   = db_getsession("DB_coddepto");
            $clpcproc->incluir($codproc);
            if ($clpcproc->erro_status == 0) {
              $sqlerro  = true;
              $erro_msg = $clpcproc->erro_msg;
            }
          }

          $clpcprocitem->pc81_codproc   = $codproc;
          $clpcprocitem->pc81_solicitem = $pc11_codigo;
          $clpcprocitem->incluir(null);
          if ($clpcprocitem->erro_status == 0) {
            $sqlerro  = true;
            $erro_msg = $clpcprocitem->erro_msg;
          }
        }

        if (isset($codliclicita) && trim($codliclicita) != "") {
          if (isset($codproc) && trim($codproc) == "") {
            // Cria o processo e insere na pcprocitem
            $clpcproc->pc80_data    = date("Y-m-d", db_getsession("DB_datausu"));
            $clpcproc->pc80_usuario = db_getsession("DB_id_usuario");
            $clpcproc->pc80_depto   = db_getsession("DB_coddepto");
            $clpcproc->incluir(null);
            if ($clpcproc->erro_status == 0) {
              $sqlerro  = true;
              $erro_msg = $clpcproc->erro_msg;
            }

            if ($sqlerro == false) {
              $codproc                      = $clpcproc->pc80_codproc;
              $clpcprocitem->pc81_codproc   = $codproc;
              $clpcprocitem->pc81_solicitem = $pc11_codigo;
              $clpcprocitem->incluir(null);
              if ($clpcprocitem->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcprocitem->erro_msg;
              }
            }
          }

          if ($sqlerro == false) {
            $clliclicitem->l21_codpcprocitem = $clpcprocitem->pc81_codprocitem;
            $clliclicitem->l21_codliclicita  = $codliclicita;
            $clliclicitem->l21_situacao      = "0";
            $clliclicitem->l21_ordem         = $pc11_seq;
            $clliclicitem->incluir(null);
            if ($clliclicitem->erro_status == 0) {
              $sqlerro  = true;
              $erro_msg = $clliclicitem->erro_msg;
            } else {
              $codliclicitem = $clliclicitem->l21_codigo;
            }

            // Rotina ficou faltando ser colocada apos alteracoes do modulo LICITACOES
            if ($sqlerro == false) {
              $res_liclicita = $clliclicita->sql_record($clliclicita->sql_query_pco(null,
                                                                                    "l20_tipojulg as tipojulg",
                                                                                    "l21_codigo desc",
                                                                                    "l20_codigo = $codliclicita"));
              if ($clliclicita->numrows > 0) {  // Existem itens no lote
                db_fieldsmemory($res_liclicita, 0);
              } else {
                $res_liclicita = $clliclicita->sql_record($clliclicita->sql_query_file($codliclicita,
                                                                                       "l20_tipojulg as tipojulg"));
                if ($clliclicita->numrows > 0) {
                  db_fieldsmemory($res_liclicita, 0);
                } else {
                  $sqlerro  = true;
                  $erro_msg = "Tipo de Julgamento não encontrado para Licitação $codliclicita. Verifique.";
                }
              }

              if ($sqlerro == false) {
                $clliclicitemlote->l04_liclicitem = $codliclicitem;

                if ($tipojulg == 1) {
                  // Julgamento por ITEM
                  $descr_lote = "LOTE_AUTOITEM_" . $pc11_codigo;
                }

                if ($tipojulg == 2) {
                  // GLOBAL
                  $descr_lote = "GLOBAL";
                }

                if ($tipojulg == 3) {
                  // por LOTE
                  $res_liclicitemlote = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_file(null,
                                                                                                        "l04_descricao as descr_lote",
                                                                                                        "l04_codigo desc",
                                                                                                        "l04_liclicitem=$codliclicitem"));
                  if ($clliclicitemlote->numrows > 0) {
                    db_fieldsmemory($res_liclicitemlote, 0);
                    if (substr(trim($descr_lote), 0, 9) == "AUTO_LOTE") {
                      $sequencial = intval(substr(trim($descr_lote), 11, 5)) + 1;
                      $descr_lote = "AUTO_LOTE_" . db_formatar($sequencial, "s", "0", 5, "e", 0);
                    }
                  } else {
                    $sequencial = 1;
                    $descr_lote = "AUTO_LOTE_" . db_formatar($sequencial, "s", "0", 5, "e", 0);
                  }
                }

                $clliclicitemlote->l04_descricao = $descr_lote;
                $clliclicitemlote->incluir(null);
                if ($clliclicitemlote->erro_status == 0) {
                  $sqlerro  = true;
                  $erro_msg = $clliclicitemlote->erro_msg;
                }
              }
            }

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // Orçamento da Licitação
            if ($sqlerro == false) {
              $codigo             = $clliclicitem->l21_codigo;
              $dbwhere_orcam      = "l21_codliclicita = $codliclicita and l21_codigo != $codigo limit 1";
              $result_pcorcamitem = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null,
                                                                                                    "pc22_codorc as codorc",
                                                                                                    null,
                                                                                                    "$dbwhere_orcam"));
              if ($clpcorcamitem->numrows > 0) {
                db_fieldsmemory($result_pcorcamitem, 0);
                $clpcorcamitem->pc22_codorc = $codorc;
                $clpcorcamitem->incluir(null);
                if ($clpcorcamitem->erro_status == 0) {
                  $sqlerro  = true;
                  $erro_msg = $clpcorcamitem->erro_msg;
                }

                if ($sqlerro == false) {
                  $clpcorcamitemlic->pc26_liclicitem = $codigo;
                  $clpcorcamitemlic->pc26_orcamitem  = $clpcorcamitem->pc22_orcamitem;
                  $clpcorcamitemlic->incluir(null);
                  if ($clpcorcamitemlic->erro_status == 0) {
                    $sqlerro  = true;
                    $erro_msg = $clpcorcamitemlic->erro_msg;
                  }
                }
              }
            }
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          }
        }

        if ($sqlerro == false) {
          $clsolicitalog->pc15_numsol = $pc11_numero;
          if (isset($codproc) && !empty($codproc)) {
            $clsolicitalog->pc15_codproc = $codproc;
          } else {
            $clsolicitalog->pc15_codproc = "0";
          }

          if (isset($codliclicita) && trim($codliclicita) != "") {
            $clsolicitalog->pc15_codliclicita = $codliclicita;
          } else {
            $clsolicitalog->pc15_codliclicita = "0";
          }

          $clsolicitalog->pc15_solicitem  = $pc11_codigo;
          $clsolicitalog->pc15_quant      = $pc11_quant;
          $clsolicitalog->pc15_vlrun      = $pc11_vlrun;
          $clsolicitalog->pc15_id_usuario = db_getsession("DB_id_usuario");
          $clsolicitalog->pc15_data       = date("Y-m-d", db_getsession("DB_datausu"));
          $clsolicitalog->pc15_hora       = db_hora();
          $clsolicitalog->pc15_opcao      = "1";
          // Inclusao
          $clsolicitalog->incluir(null);

          if ($clsolicitalog->erro_status == 0) {
            $sqlerro  = true;
            $erro_msg = $clsolicitalog->erro_msg;
          }
        }
      }
    }

    if ($sqlerro == false && isset($pc16_codmater) && $pc16_codmater != "") {

      $result_msgcodmater = $clsolicitempcmater->sql_record($clsolicitempcmater->sql_query_file(null,
                                                                                                null,
                                                                                                "pc16_codmater",
                                                                                                "",
                                                                                                " pc16_codmater=$pc16_codmater and pc16_solicitem in (select pc11_codigo from solicitem where pc11_numero in ($pc11_numero))"));
      if ($clsolicitempcmater->numrows > 0) {
        $msg_alert = "AVISO \\n\\nItem ja cadastrado nesta solicitação.";
      }
      $clsolicitempcmater->pc16_codmater  = $pc16_codmater;
      $clsolicitempcmater->pc16_solicitem = @ $pc11_codigo;
      $clsolicitempcmater->incluir(@ $pc16_codmater, @ $pc11_codigo);
      if ($clsolicitempcmater->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicitempcmater->erro_msg;
      }
      if (isset($o103_pactovalor) && $o103_pactovalor != "") {

        try {

          $oSolicitacao = new solicitacaoCompra($pc11_numero);
          $oSolicitacao->vincularItemPacto($pc11_codigo, $o103_pactovalor, $pc11_quant, @($pc11_quant * $pc11_vlrun));

        } catch (Exception $eErro) {

          $sqlerro  = true;
          $erro_msg = $eErro->getMessage();

        }

      }
      if ($sqlerro == false) {

        $clsolicitemele->incluir($pc11_codigo, $o56_codele);
        if ($clsolicitemele->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clsolicitemele->erro_msg;
        }
      }

      if ($sqlerro == false && $iRegistroPreco != "") {
        try {

          $oSolicitacao = new solicitacaoCompra($pc11_numero);
          $oSolicitacao->addItemRegistroPreco($pc11_codigo,
                                              $pc16_codmater,
                                              $iRegistroPreco,
                                              $pc11_quant,
                                              $registroprecoorigem,
                                              $pc11_vlrun);

        } catch (Exception $eErro) {

          $sqlerro  = true;
          $erro_msg = $eErro->getMessage();

        }
      }
    }
    //if ((!isset($pc01_servico) || (isset($pc01_servico) && ($pc01_servico == "f" || trim($pc01_servico) == ""))) && $sqlerro == false) {

    // echo "<br>" . $pc11_servicoquantidade; die();
    if ($pc11_servicoquantidade == 'true' || $pc01_servico == "f" && $sqlerro == false) {

      $clsolicitemunid->pc17_unid  = $pc17_unid;
      $clsolicitemunid->pc17_quant = $pc17_quant;
      $clsolicitemunid->incluir($pc11_codigo);
      if ($clsolicitemunid->erro_status == 0) {
        $erro_msg = $clsolicitemunid->erro_msg;
        $sqlerro  = true;
      }
    }

    if ($sqlerro == false && $pc30_seltipo == 't') {
      $result_vlsol = $clsolicitatipo->sql_record($clsolicitatipo->sql_query_file($pc11_numero, "pc12_vlrap"));
      if ($clsolicitatipo->numrows > 0) {
        db_fieldsmemory($result_vlsol, 0);
      } else {
        $pc12_vlrap = 0;
      }
      $clsolicitatipo->pc12_vlrap  = $pc12_vlrap + ($pc11_quant * $pc11_vlrun);
      $clsolicitatipo->pc12_numero = $pc11_numero;
      $clsolicitatipo->alterar($pc11_numero);
      if ($clsolicitatipo->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicitatipo->erro_msg;
      }
    }
    if ($sqlerro == false && $pc30_permsemdotac == "f") {
      $clsolicita->pc10_correto = "false";
      $clsolicita->pc10_numero  = $pc11_numero;
      $clsolicita->alterar($pc11_numero);
      if ($clsolicita->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicita->erro_msg;
      }
    }


    //---------------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------------
    //-----------------------CONTROLE DE ANDAMENTO DOS ITENS DA  SOLICITAÇÃO - ROGERIO BAUM --------------------------------
    /*
*CRIA UM PROCESSO PARA CADA ITEM DA SOLICITAÇÃO E GRAVA O CODIGO DA SOLICITAÇÃO E DO PROCESSO NA TABELA SOLICITEMPROT
*/
    /*
$result_pcparam1 = $clpcparam->sql_record($clpcparam->sql_query_file(null, "pc30_contrandsol,pc30_tipoprocsol"));
if ($clpcparam->numrows > 0) {
db_fieldsmemory($result_pcparam1, 0);
*/
    if ($pc30_contrandsol == 't') {
      if ($sqlerro == false) {
        $result_cgmpref = $cldb_config->sql_record($cldb_config->sql_query(null,
                                                                           "numcgm,z01_nome",
                                                                           null,
                                                                           "codigo=" . db_getsession("DB_instit")));
        db_fieldsmemory($result_cgmpref, 0);
        $clprotprocesso->p58_codigo     = $pc30_tipoprocsol;
        $clprotprocesso->p58_dtproc     = date('Y-m-d', db_getsession("DB_datausu"));
        $clprotprocesso->p58_id_usuario = db_getsession("DB_id_usuario");
        $clprotprocesso->p58_numcgm     = $numcgm;
        $clprotprocesso->p58_requer     = $z01_nome;
        $clprotprocesso->p58_coddepto   = db_getsession("DB_coddepto");
        $clprotprocesso->p58_codandam   = '0';
        $clprotprocesso->p58_obs        = "";
        $clprotprocesso->p58_despacho   = "";
        $clprotprocesso->p58_hora       = db_hora();
        $clprotprocesso->p58_interno    = 't';
        $clprotprocesso->p58_publico    = '0';
        $clprotprocesso->p58_numero     = $pc30_tipoprocsol;
        $clprotprocesso->p58_ano        = db_getsession("DB_anousu");
        $clprotprocesso->p58_instit     = db_getsession("DB_instit");

        $clprotprocesso->incluir(null);
        $codproc = $clprotprocesso->p58_codproc;
        if ($clprotprocesso->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clprotprocesso->erro_msg;
        }
      }
      if ($sqlerro == false) {
        $clsolicitemprot->pc49_protprocesso = $codproc;
        $clsolicitemprot->pc49_solicitem    = $pc11_codigo;
        $clsolicitemprot->incluir($pc11_codigo);
        if ($clsolicitemprot->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clsolicitem->erro_msg;
        }
      }
    }

    if (isset($pc14_veiculos) && $pc01_veiculo == "t") {
      if ($sqlerro == false) {
        $clsolicitemveic->pc14_veiculos  = $pc14_veiculos;
        $clsolicitemveic->pc14_solicitem = $pc11_codigo;
        $clsolicitemveic->incluir(null);

        if ($clsolicitemveic->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clsolicitemveic->erro_msg;
        }
      }
    }
    // }
    //---------------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------------

    db_fim_transacao($sqlerro);

    $pc11_numero = $pc11_numero;
    $pc11_codigo = $clsolicitem->pc11_codigo;
    if ($sqlerro == false) {
      $opcao    = 'alterar';
      $db_opcao = 2;
    } else {
      $pc11_codigo = '';
    }
  }
} else if (isset($alterar) && $sqlerro == false) {


  if (!isset($pc16_codmater) || (isset($pc16_codmater) && $pc16_codmater == "")) {

    if (($digitouresumo == "false" && trim($pc11_resum) == "") || (isset($pc11_resum) && $pc11_resum == "")) {

      $naodig                = true;
      $sqlerro               = true;
      $erro_msg              = "Usuário:\\n\\nAlteração abortada. \\nMaterial e resumo do item não informados.\\n\\nAdministrador:";
      $solicitem->erro_campo = "pc16_codmater";
    }
  }
  if ($sqlerro == false) {

    db_inicio_transacao();
    try {

      $oSolicitacao = new solicitacaoCompra($pc11_numero);
      $oSolicitacao->alterarItemRegistroPreco($pc11_codigo, $pc16_codmater, $pc11_quant);
    } catch (Exception $eErro) {

      $sqlerro  = true;
      $erro_msg = str_replace("\n", "\\n", $eErro->getMessage());
    }
    $clsolicitem->pc11_codigo = $pc11_codigo;
    $clsolicitem->pc11_quant  = $pc11_quant;
    if (trim($pc11_vlrun) == "") {
      $pc11_vlrun = 0;
    }
    $pc11_vlrun = str_replace(",", ".", $pc11_vlrun);
    if (strpos($pc11_vlrun, ".") == '') {
      $pc11_vlrun .= ".";
      $tam        = strlen($pc11_vlrun) + 2;
      $pc11_vlrun = str_pad($pc11_vlrun, $tam, '0', STR_PAD_RIGHT);
    }
    $clsolicitem->pc11_vlrun    = $pc11_vlrun;
    $clsolicitem->pc11_liberado = $pc11_liberado;
    $clsolicitem->pc11_prazo    = AddSlashes(chop($pc11_prazo));
    $clsolicitem->pc11_pgto     = AddSlashes(chop($pc11_pgto));
    $clsolicitem->pc11_resum    = AddSlashes(chop($pc11_resum));
    $clsolicitem->pc11_just     = AddSlashes(chop($pc11_just));

    // Alteracao para verificar se Item jah empenhado nao pode ser alterado
    if (isset($param) && trim($param) != "") {
      $result_pcproc = $clpcproc->sql_record($clpcproc->sql_query_autitem(null,
                                                                          "distinct pc81_codprocitem",
                                                                          null,
                                                                          "pc81_solicitem = $pc11_codigo and
e55_sequen is not null and e54_anulad is null"));
      if ($clpcproc->numrows > 0) {
        $sqlerro  = true;
        $erro_msg = "Item não pode ser alterado, pois já autorizado a empenho!";
      }

      if ($sqlerro == false) {
        $clsolicitalog->pc15_numsol = $pc11_numero;
        if (isset($codproc) && !empty($codproc)) {
          $clsolicitalog->pc15_codproc = $codproc;
        } else {
          $clsolicitalog->pc15_codproc = "0";
        }

        if (isset($codliclicita) && trim($codliclicita) != "") {
          $clsolicitalog->pc15_codliclicita = $codliclicita;
        } else {
          $clsolicitalog->pc15_codliclicita = "0";
        }

        $clsolicitalog->pc15_solicitem  = $pc11_codigo;
        $clsolicitalog->pc15_quant      = $pc11_quant;
        $clsolicitalog->pc15_vlrun      = $pc11_vlrun;
        $clsolicitalog->pc15_id_usuario = db_getsession("DB_id_usuario");
        $clsolicitalog->pc15_data       = date("Y-m-d", db_getsession("DB_datausu"));
        $clsolicitalog->pc15_hora       = db_hora();
        $clsolicitalog->pc15_opcao      = "2";
        // Alteracao
        $clsolicitalog->incluir(null);

        if ($clsolicitalog->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clsolicitalog->erro_msg;
        }
      }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if ($sqlerro == false) {
      $clsolicitem->alterar($pc11_codigo);
      if ($clsolicitem->erro_status == 0) {
        $sqlerro = true;
      }
      $erro_msg = $clsolicitem->erro_msg;
    }

    //if ((!isset($pc01_servico) || (isset($pc01_servico) && ($pc01_servico == "f" || trim($pc01_servico) == ""))) && $sqlerro == false) {
    if ($pc11_servicoquantidade == 'true' || $pc01_servico == "f" && $sqlerro == false) {
      $clsolicitemunid->pc17_unid   = $pc17_unid;
      $clsolicitemunid->pc17_quant  = $pc17_quant;
      $clsolicitemunid->pc17_codigo = $pc11_codigo;
      $result_solicitemunid         = $clsolicitemunid->sql_record($clsolicitemunid->sql_query_file($pc11_codigo));
      if ($clsolicitemunid->numrows > 0) {
        $clsolicitemunid->alterar($pc11_codigo);
      } else {
        $clsolicitemunid->incluir($pc11_codigo);
      }
      if ($clsolicitemunid->erro_status == 0) {
        $erro_msg = $clsolicitemunid->erro_msg;
        $sqlerro  = true;
      }
    }
    if ($sqlerro == false && isset($pc16_codmater) && $pc16_codmater != "") {
      $clsolicitempcmater->excluir(@ $pc16_codmater, @ $pc11_codigo);
      if ($clsolicitempcmater->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicitempcmater->erro_msg;
      }
      if ($sqlerro == false) {
        $clsolicitemele->excluir($pc11_codigo, null);
        if ($clsolicitemele->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clsolicitemele->erro_msg;
        }
      }
    }
    if ($sqlerro == false && isset($pc16_codmater) && $pc16_codmater != "") {
      $clsolicitempcmater->pc16_codmater  = $pc16_codmater;
      $clsolicitempcmater->pc16_solicitem = @ $pc11_codigo;
      $clsolicitempcmater->incluir(@ $pc16_codmater, @ $pc11_codigo);
      if ($clsolicitempcmater->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicitempcmater->erro_msg;
        $erro_msg = str_replace("Inclusao", "Alteracao", $erro_msg);
      }
      if ($sqlerro == false) {
        $clsolicitemele->incluir($pc11_codigo, $o56_codele);
        if ($clsolicitemele->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clsolicitemele->erro_msg;
        }
      }
    }
    if (isset($o103_pactovalor) && $o103_pactovalor != "" && !$sqlerro) {

      try {

        $oSolicitacao = new solicitacaoCompra($pc11_numero);
        $oSolicitacao->excluirVinculacaoItemPacto($pc11_codigo);
        $oSolicitacao->vincularItemPacto($pc11_codigo, $o103_pactovalor, $pc11_quant, @($pc11_quant * $pc11_vlrun));

      } catch (Exception $eErro) {

        $sqlerro  = true;
        $erro_msg = $eErro->getMessage();

      }
    }
    if ($sqlerro == false && $pc30_seltipo == "t") {
      $result_vlsol = $clsolicitatipo->sql_record($clsolicitatipo->sql_query_file($pc11_numero, "pc12_vlrap"));
      if ($clsolicitatipo->numrows > 0) {
        db_fieldsmemory($result_vlsol, 0);
      } else {
        $pc12_vlrap = 0;
      }
      $clsolicitatipo->pc12_vlrap  = ($pc12_vlrap - $pc11_quant_ant * $pc11_vlrun_ant) + $pc11_quant * $pc11_vlrun;
      $clsolicitatipo->pc12_numero = $pc11_numero;
      $clsolicitatipo->alterar($pc11_numero);
      if ($clsolicitatipo->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicitatipo->erro_msg;
      }
    }

    $quant_int = 0;
    $quant_dec = 0;
    $YN        = true;
    $passa     = false;
    $passa1    = false;
    if (($pc11_vlrun != $pc11_vlrun_ant || ($pc11_quant != $pc11_quant_ant && $pc11_quant < $pc11_quant_ant))
        && $sqlerro == false
        && $quant_rest >= 0
    ) {
      $result_vlrun    = $clpcdotac->sql_record($clpcdotac->sql_query_file($pc11_codigo,
                                                                           null,
                                                                           null,
                                                                           "pc13_quant,pc13_anousu,pc13_coddot,pc13_codigo,pc13_valor"));
      $numrows_pcdotac = $clpcdotac->numrows;
      if ($numrows_pcdotac > 0) {
        $soma        = 0;
        $arr_gerarel = array();
        for ($i = 0; $i < $numrows_pcdotac; $i++) {
          db_fieldsmemory($result_vlrun, $i);
          if ($pc30_gerareserva == "t") {
            //*******rotina que verifica se ainda existe saldo disponivel******************//
            //rotina para calcular o saldo final
            $result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$pc13_coddot", db_getsession("DB_anousu"));
            db_fieldsmemory($result, 0);

          }

          if ($pc11_vlrun != $pc11_vlrun_ant) {
            $passa = true;
            if (($pc01_servico == 'f' || !isset($pc01_servico)) && $sqlerro == false) {
              $pc13_valor = $pc11_vlrun * $pc13_quant;
            } else if (isset($pc01_servico) && $pc01_servico == 't') {
              $pc13_valor = ($pc13_valor / $pc11_vlrun_ant) * $pc11_vlrun;
            }
          }
          if ($pc11_quant != $pc11_quant_ant) {
            $passa = true;
            if ($i == 0) {
              $result_quant_rest = $clpcdotac->sql_record($clpcdotac->sql_query_file($pc11_codigo,
                                                                                     null,
                                                                                     null,
                                                                                     "round(sum(pc13_quant),2) as quant_solic"));
            }
            if ($clpcdotac->numrows > 0) {
              db_fieldsmemory($result_quant_rest, 0);
              if ($pc11_quant < $quant_solic) {
                $passa1     = true;
                $msg_alert2 = " - Quantidade de itens por dotação.\\n";
                $quant      = $pc11_quant * ($pc13_quant / $pc11_quant_ant);
                $soma += $quant;
                if (($i + 1) == $numrows_pcdotac) {
                  if ($soma < $pc11_quant) {
                    $quant += $pc11_quant - $soma;
                  } else if ($soma > $pc11_quant) {
                    $quant -= $soma - $pc11_quant;
                  }
                }
                $pc13_valor = $pc11_vlrun * $quant;
                $pc13_quant = $quant;
              }
            }
            if ($pc11_quant > $pc11_quant_ant) {
              $clsolicita->pc10_correto = "false";
              $clsolicita->pc10_numero  = $pc11_numero;
              $clsolicita->alterar($pc11_numero);
              if ($clsolicita->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clsolicita->erro_msg;
              }
            }
          }

          if ($pc30_gerareserva == "t") {
            $result_altext  = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,
                                                                                                  null,
                                                                                                  "o80_codres,o80_valor",
                                                                                                  "",
                                                                                                  "o80_coddot = {$pc13_coddot}
                                                           and pc13_codigo = {$pc13_codigo}"));
            $numrows_altres = $clorcreservasol->numrows;
            if ($numrows_altres > 0) {
              db_fieldsmemory($result_altext, 0);
              $tot = (0 + ($o80_valor + $atual_menos_reservado) - (0 + $pc13_valor));
            }
            $sqlerrosaldo = false;
            if ((isset($tot) && $tot < 0) && $sqlerro == false) {
              $sqlerrosaldo = true;
              $saldoreserva = $atual_menos_reservado + $o80_valor;
              if ($saldoreserva == 0) {
                db_msgbox("\\nATENÇÃO: \\nUma, ou mais dotações, está sem saldo disponível para reserva.\\nReserva será excluída.\\n");
              }
            }
          }

          if ($passa == true || $passa1 == true) {
            $msg_alert  = "ALERTA: \\n Ocorrerão, automaticamente, as seguintes alterações nas dotações com este item: \\n";
            $msg_alert1 = " - Valores das dotações.\\n";
          }

          if ($sqlerro == false) {
            $quant_int                  = 0;
            $clpcdotac->pc13_quant      = $pc13_quant;
            $clpcdotac->pc13_valor      = $pc13_valor;
            $clpcdotac->pc13_codigo     = $pc13_codigo;
            $clpcdotac->pc13_anousu     = $pc13_anousu;
            $clpcdotac->pc13_coddot     = $pc13_coddot;
            $rsPcDotac                  = $clpcdotac->sql_record($clpcdotac->sql_query_file($pc13_codigo,
                                                                                            $pc13_anousu,
                                                                                            $pc13_coddot,
                                                                                            "pc13_sequencial"));
            $oPcDotac                   = db_utils::fieldsMemory($rsPcDotac, 0);
            $clpcdotac->pc13_sequencial = $oPcDotac->pc13_sequencial;
            $clpcdotac->alterar($oPcDotac->pc13_sequencial);
            if ($clpcdotac->erro_status == 0) {
              $msg_alert = "Alteração nas dotações não efetuada.";
              $sqlerro   = true;
              break;
            }
            if ($pc30_gerareserva == "t") {
              $result_altext = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,
                                                                                                   null,
                                                                                                   "o80_codres,o80_valor",
                                                                                                   "",
                                                                                                   "o80_coddot     = {$pc13_coddot}
                                                            and pc13_codigo = {$pc13_codigo}"));
              if ($sqlerro == false && isset($sqlerrosaldo) && $sqlerrosaldo == false && isset($o80_codres)
                  && $o80_codres != ""
              ) {
                $clorcreserva->atualiza_valor($o80_codres, $pc13_valor);
                unset($o80_codres);
              } else if ($sqlerro == false && isset($sqlerrosaldo) && $sqlerrosaldo == true && isset($saldoreserva)
                         && $saldoreserva > 0
                         && isset($o80_codres)
                         && $o80_codres != ""
              ) {
                $clorcreserva->atualiza_valor($o80_codres, $saldoreserva);
                $msg_alert .= "Reserva de saldo gerada parcialmente.\\n";
                unset($o80_codres);
              } else if ($sqlerro == false && isset($sqlerrosaldo) && $sqlerrosaldo == true && isset($o80_codres)
                         && $o80_codres != ""
              ) {
                $clorcreservasol->excluir($oPcDotac->pc13_sequencial);
                if ($clorcreservasol->erro_status == 0) {
                  $erro_msg  = $clorcreservasol->erro_msg;
                  $msg_alert = "Alteração na reserva de saldo não efetuada.";
                  $sqlerro   = true;
                  break;
                }
                $clorcreserva->excluir($o80_codres);
                if ($clorcreserva->erro_status == 0) {
                  $erro_msg  = $clorcreserva->erro_msg;
                  $msg_alert = "Alteração na reserva de saldo não efetuada.";
                  $sqlerro   = true;
                  break;
                }
              }
            }
          }
        }
        if ($sqlerro == false) {
          $msg_alert .= $msg_alert1 . $msg_alert2 . $msg_alertp;
        }
      }
    }
    if ($sqlerro == false && $pc30_permsemdotac == "f") {
      $alterarr        = false;
      $result_altersol = $clpcdotac->sql_record($clpcdotac->sql_query_lefdotac(null,
                                                                               null,
                                                                               null,
                                                                               "pc11_codigo,pc11_quant,sum(pc13_quant) as pc13_quantalter",
                                                                               "",
                                                                               "pc11_numero=$pc11_numero group by pc11_codigo,pc11_quant"));
      if ($clpcdotac->numrows > 0) {
        for ($i = 0; $i < $clpcdotac->numrows; $i++) {
          db_fieldsmemory($result_altersol, $i);
          $result_servico  = $clsolicitem->sql_record($clsolicitem->sql_query_serv($pc11_codigo, "pc01_servico"));
          $numrows_servico = $clsolicitem->numrows;
          if ($numrows_servico > 0) {
            db_fieldsmemory($result_servico, 0);
          } else {
            $pc01_servico = 'f';
          }
          if ($pc01_servico == "f") {
            if ($pc11_quant == $pc13_quantalter) {
              $alterarr = true;
            } else {
              $alterarr = false;
              break;
            }
          } else {
            if ($pc13_quantalter > 0) {
              $alterarr = true;
            } else {
              $alterarr = false;
              break;
            }
          }
        }
      }
      if ($alterarr == true) {
        $clsolicita->pc10_correto = "true";
      } else {
        $clsolicita->pc10_correto = "false";
      }
      $clsolicita->pc10_numero = $pc11_numero;
      $clsolicita->alterar($pc11_numero);
      if ($clsolicita->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicita->erro_msg;
      }
    }

    if (isset($pc14_veiculos)) {
      if ($sqlerro == false) {
        $rsVeic = $clsolicitemveic->sql_record($clsolicitemveic->sql_query_file(null,
                                                                                "pc14_sequencial",
                                                                                "",
                                                                                "pc14_solicitem = $pc11_codigo"));
        if ($clsolicitemveic->numrows > 0) {
          db_fieldsmemory($rsVeic, 0);
          $clsolicitemveic->pc14_veiculos = $pc14_veiculos;
          $clsolicitemveic->alterar($pc14_sequencial);
          if ($clsolicitemveic->erro_status == 0) {
            $sqlerro  = true;
            $erro_msg = $clsolicitemveic->erro_msg;
          }
        }
      }
    }
    //$sqlerro = true;
    db_fim_transacao($sqlerro);
    if ($sqlerro == false) {
      $opcao    = 'alterar';
      $db_opcao = 2;
    }
  }
} else if (isset($excluir)) {
  db_inicio_transacao();

  // Para exclusao do item em processos e licitacoes nao autorizadas
  if (isset($param) && trim($param) != "") {

    $flag_pcproc    = false;
    $flag_liclicita = false;

    if (isset($codproc) && trim($codproc) != "") {
      // Testa se Item nao esta com autorizacao de empenho
      $result_pcproc = $clpcproc->sql_record($clpcproc->sql_query_autitem(null,
                                                                          "distinct pc81_codprocitem",
                                                                          null,
                                                                          "pc80_codproc = $codproc and pc81_solicitem = $pc11_codigo and
e55_sequen is not null and e54_anulad is null"));
      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      if ($clpcproc->numrows > 0) {
        // Caso esteja vai avisar que nao pode excluir
        $flag_pcproc = true;
      } else {
        // Exclusao de orçamento de processo de compras
        // Testa se item tem orcamento de processo de compras
        $result_item = $clpcproc->sql_record($clpcproc->sql_query_autitem(null,
                                                                          "distinct pc81_codprocitem as codprocitem",
                                                                          null,
                                                                          "pc80_codproc = $codproc and pc81_solicitem = $pc11_codigo"));
        IF ($clpcproc->numrows > 0) {
          db_fieldsmemory($result_item, 0);
          $result_pcorcamitem = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterproc(null,
                                                                                                 "pc22_orcamitem as codorcitem,
pc20_codorc    as codorc",
                                                                                                 null,
                                                                                                 "pc81_codprocitem = $codprocitem"));
          if ($clpcorcamitem->numrows > 0) {
            db_fieldsmemory($result_pcorcamitem, 0);
            $result_pcorcamjulg = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query(null,
                                                                                       null,
                                                                                       "pc21_orcamforne as codforne",
                                                                                       null,
                                                                                       "pc24_orcamitem = $codorcitem"));
            $numrows            = $clpcorcamjulg->numrows;

            $clpcorcamitemproc->excluir($codorcitem, $codprocitem);
            if ($clpcorcamitemproc->erro_status == 0) {
              $sqlerro  = true;
              $erro_msg = $clpcorcamitemproc->erro_msg;
            }

            if ($sqlerro == false) {
              $clpcorcamjulg->excluir($codorcitem);
              if ($clpcorcamjulg->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcorcamjulg->erro_msg;
              }
            }

            if ($sqlerro == false) {
              $clpcorcamval->excluir(null, $codorcitem);
              if ($clpcorcamval->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcorcamval->erro_msg;
              }
            }

            if ($sqlerro == false) {
              $clpcorcamdescla->excluir($codorcitem);
              if ($clpcorcamdescla->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcorcamdescla->erro_msg;
              }
            }

            if ($sqlerro == false) {
              $clpcorcamtroca->excluir(null, "pc25_orcamitem = $codorcitem");
              if ($clpcorcamtroca->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcorcamtroca->erro_msg;
              }
            }

            if ($sqlerro == false) {
              $clpcorcamitem->excluir($codorcitem);
              if ($clpcorcamitem->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcorcamitem->erro_msg;
              }
            }

            if ($sqlerro == false) {
              if ($numrows > 0) {
                for ($i = 0; $i < $numrows; $i++) {
                  db_fieldsmemory($result_pcorcamjulg, $i);
                  $res_pcorcamjulg = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query(null,
                                                                                          null,
                                                                                          "pc24_pontuacao",
                                                                                          null,
                                                                                          "pc24_orcamforne = $codforne"));
                  if ($clpcorcamjulg->numrows > 0) {
                    $numrows = 0;
                    break;
                  }
                }
                for ($i = 0; $i < $numrows; $i++) {
                  db_fieldsmemory($result_pcorcamjulg, $i);

                  $clpcorcamforne->excluir(null, "pc21_orcamforne = $codforne and pc21_codorc = $codorc");
                  if ($clpcorcamforne->erro_status == 0) {
                    $sqlerro  = true;
                    $erro_msg = $clpcorcamforne->erro_msg;
                  }

                  if ($sqlerro == true) {
                    break;
                  }
                }
              }
            }

            if ($sqlerro == false) {
              $sql_pcorcam    = "select *
from pcorcam
inner join pcorcamitem on pcorcamitem.pc22_codorc = pcorcam.pc20_codorc
where pc20_codorc = $codorc";
              $result_pcorcam = @db_query($sql_pcorcam);
              if (@pg_numrows($result_pcorcam) == 0) {
                $clpcorcam->excluir($codorc);
                if ($clpcorcam->erro_status == 0) {
                  $sqlerro  = true;
                  $erro_msg = $clpcorcam->erro_msg;
                }
              }
            }
          }
        }
      }
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      if ($flag_pcproc == true) {
        $sqlerro  = true;
        $erro_msg = "Item não pode ser excluido, pois já autorizado a empenho!";
      }
    }

    if (isset($codliclicita) && trim($codliclicita) != "") {

      $result_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query_inf(null,
                                                                                  "distinct l21_codigo",
                                                                                  null,
                                                                                  "l21_codliclicita = $codliclicita and pc81_solicitem = $pc11_codigo and
e55_sequen is not null and e54_anulad is null"));
      if ($clliclicitem->numrows > 0) {
        $flag_liclicita = true;
      } else {
        db_fieldsmemory($result_liclicitem, 0);
      }

      if ($flag_liclicita == true) {
        $sqlerro  = true;
        $erro_msg = "Item não pode ser excluido, pois já autorizado a empenho!";
      }
    }

    if ($sqlerro == false) {
      if (isset($codliclicita) && trim($codliclicita) != "") {
        // Testa se item tem orcamento em licitacao
        $result_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query_inf(null,
                                                                                    "distinct l21_codigo as codlic",
                                                                                    null,
                                                                                    "l21_codliclicita = $codliclicita and pc81_solicitem = $pc11_codigo"));
        if ($clliclicitem->numrows > 0) {
          db_fieldsmemory($result_liclicitem, 0);
        }

        if (isset($codlic) && trim($codlic) != "") {
          // Excluir orçamento de licitacao
          $result_pcorcamitem = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null,
                                                                                                "pc22_orcamitem as codorcitem, pc22_codorc as codorc",
                                                                                                null,
                                                                                                "l21_codigo = $codlic"));
          if ($clpcorcamitem->numrows > 0) {
            db_fieldsmemory($result_pcorcamitem, 0);
            $result_pcorcamjulg = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query(null,
                                                                                       null,
                                                                                       "pc21_orcamforne as codforne",
                                                                                       null,
                                                                                       "pc24_orcamitem = $codorcitem"));
            $numrows            = $clpcorcamjulg->numrows;

            $clpcorcamitemlic->excluir(null, "pc26_liclicitem = $codlic");
            if ($clpcorcamitemlic->erro_status == 0) {
              $sqlerro  = true;
              $erro_msg = $clpcorcamitemlic->erro_msg;
            }

            if ($sqlerro == false) {
              $clpcorcamjulg->excluir($codorcitem);
              if ($clpcorcamjulg->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcorcamjulg->erro_msg;
              }
            }

            if ($sqlerro == false) {
              $clpcorcamval->excluir(null, $codorcitem);
              if ($clpcorcamval->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcorcamval->erro_msg;
              }
            }

            if ($sqlerro == false) {
              $clpcorcamdescla->excluir($codorcitem);
              if ($clpcorcamdescla->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcorcamdescla->erro_msg;
              }
            }

            if ($sqlerro == false) {
              $clpcorcamtroca->excluir(null, "pc25_orcamitem = $codorcitem");
              if ($clpcorcamtroca->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcorcamtroca->erro_msg;
              }
            }

            if ($sqlerro == false) {
              $clpcorcamitem->excluir($codorcitem);
              if ($clpcorcamitem->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clpcorcamitem->erro_msg;
              }

              if ($sqlerro == false) {
                if ($numrows > 0) {
                  for ($i = 0; $i < $numrows; $i++) {
                    db_fieldsmemory($result_pcorcamjulg, $i);
                    $res_pcorcamjulg = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query(null,
                                                                                            null,
                                                                                            "pc24_pontuacao",
                                                                                            null,
                                                                                            "pc24_orcamforne = $codforne"));
                    if ($clpcorcamjulg->numrows > 0) {
                      $numrows = 0;
                      break;
                    }
                  }

                  for ($i = 0; $i < $numrows; $i++) {
                    db_fieldsmemory($result_pcorcamjulg, $i);

                    $clpcorcamforne->excluir(null, "pc21_orcamforne = $codforne and pc21_codorc = $codorc");
                    if ($clpcorcamforne->erro_status == 0) {
                      $sqlerro  = true;
                      $erro_msg = $clpcorcamforne->erro_msg;
                    }

                    if ($sqlerro == false) {
                      $clpcorcamfornelic->excluir($codforne);
                      if ($clpcorcamfornelic->erro_status == 0) {
                        $sqlerro  = true;
                        $erro_msg = $clpcorcamfornelic->erro_msg;
                      }
                    }

                    if ($sqlerro == true) {
                      break;
                    }
                  }
                }
              }

              if ($sqlerro == false) {
                $sql_pcorcam    = "select *
from pcorcam
inner join pcorcamitem on pcorcamitem.pc22_codorc = pcorcam.pc20_codorc
where pc20_codorc = $codorc";
                $result_pcorcam = @db_query($sql_pcorcam);
                if (@pg_numrows($result_pcorcam) == 0) {
                  $clpcorcam->excluir($codorc);
                  if ($clpcorcam->erro_status == 0) {
                    $sqlerro  = true;
                    $erro_msg = $clpcorcam->erro_msg;
                  }
                }
              }
            }
          }
          ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          if ($sqlerro == false) {
            $clliclicitemlote->excluir(null, "l04_liclicitem = $codlic");
            if ($clliclicitemlote->erro_status == 0) {
              $sqlerro  = true;
              $erro_msg = $clliclicitemlote->erro_msg;
            }

            if ($sqlerro == false) {
              $clliclicitem->excluir($codlic);
              if ($clliclicitem->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clliclicitem->erro_msg;
              }
            }
          }
        }
      }

      if ($sqlerro == false && isset($codprocitem)) {

        /*
         * Excluir tambem da empautitempcprocitem
         * que possui relacionamento
         */
        $clempautitempcprocitem->excluir("", "e73_pcprocitem = {$codprocitem}");
        if ($clempautitempcprocitem->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clempautitempcprocitem->erro_msg;
        }

        $clpcprocitem->excluir($codprocitem);
        if ($clpcprocitem->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clpcprocitem->erro_msg;
        }

        if ($sqlerro == false) {
          $sql_pcproc    = "select * from pcprocitem where pc81_codproc = $codproc";
          $result_pcproc = @db_query($sql_pcproc);
          if (@pg_numrows($result_pcproc) == 0) {
            $clpcproc->excluir($codproc);
            if ($clpcproc->erro_status == 0) {
              $sqlerro  = true;
              $erro_msg = $clpcproc->erro_msg;
            }
          }
        }
      }
    }

    if ($sqlerro == false) {

      $clsolicitalog->pc15_numsol = $pc11_numero;

      if (!isset($codproc) || $codproc == "") {
        $clsolicitalog->pc15_codproc = "0";
      } else {
        $clsolicitalog->pc15_codproc = $codproc;
      }

      if (isset($codliclicita) && trim($codliclicita) != "") {
        $clsolicitalog->pc15_codliclicita = $codliclicita;
      } else {
        $clsolicitalog->pc15_codliclicita = "0";
      }

      $clsolicitalog->pc15_solicitem  = $pc11_codigo;
      $clsolicitalog->pc15_quant      = $pc11_quant;
      $clsolicitalog->pc15_vlrun      = $pc11_vlrun;
      $clsolicitalog->pc15_id_usuario = db_getsession("DB_id_usuario");
      $clsolicitalog->pc15_data       = date("Y-m-d", db_getsession("DB_datausu"));
      $clsolicitalog->pc15_hora       = db_hora();
      $clsolicitalog->pc15_opcao      = "3";
      // Exclusao
      $clsolicitalog->incluir(null);

      if ($clsolicitalog->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicitalog->erro_msg;
      }
    }
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $result_vlsit = $clsolicitem->sql_record($clsolicitem->sql_query_file($pc11_codigo,
                                                                        "pc11_vlrun*pc11_quant as pc11_vlrun_sit"));
  if ($clsolicitem->numrows) {
    db_fieldsmemory($result_vlsit, 0);
  } else {
    $pc11_vlrun_sit = 0;
  }

  if ($sqlerro == false) {


    //------------------------------------------------------------------------------------------------------------------------
    //----------------------------------------------ROGERIO BAUM--------------------------------------------------------------------------
    //--------------------------TESTA SE EXISTE SE CONTROLA ANDAMENTO E SE EXISTIR ---------------------------------
    //---------------------ALGUM ANDAMENTO E PROCESSO EXCLUI TUDO REFERENTE AOS ANDAMENTOS---------------------------------
    $result_andpadrao  = $clsolandpadrao->sql_record($clsolandpadrao->sql_query_depto(null,
                                                                                      "*",
                                                                                      null,
                                                                                      "pc47_solicitem = $pc11_codigo"));
    $numrows_andpadrao = $clsolandpadrao->numrows;
    if ($numrows_andpadrao > 0) {
      for ($wy = 0; $wy < $numrows_andpadrao; $wy++) {
        db_fieldsmemory($result_andpadrao, $wy);
        if ($pc48_solandpadrao != "") {
          $clsolandpadraodepto->excluir($pc47_codigo);
          if ($clsolandpadraodepto->erro_status == 0) {
            $sqlerro  = true;
            $erro_msg = $clsolandpadraodepto->erro_msg;
          }
        }
      }
      $clsolandpadrao->excluir(null, "pc47_solicitem=$pc11_codigo");
      if ($clsolandpadrao->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolandpadrao->erro_msg;
      }
    }
  }

  if ($sqlerro == false) {
    $result_ordem = $clsolordemtransf->sql_record($clsolordemtransf->sql_query_file(null,
                                                                                    "*",
                                                                                    null,
                                                                                    "pc41_solicitem=$pc11_codigo"));
    if ($clsolordemtransf->numrows > 0) {
      $clsolordemtransf->excluir(null, "pc41_solicitem=$pc11_codigo");
      if ($clsolordemtransf->erro_status == 0) {
        $erro_msg = $clsolordemtransf->erro_msg;
        $sqlerro  = true;
      }
    }
  }

  if ($sqlerro == false) {
    $result_andam  = $clsolandam->sql_record($clsolandam->sql_query_and(null,
                                                                        "*",
                                                                        null,
                                                                        "pc43_solicitem = $pc11_codigo"));
    $numrows_andam = $clsolandam->numrows;
    if ($numrows_andam > 0) {
      for ($wy = 0; $wy < $numrows_andam; $wy++) {
        db_fieldsmemory($result_andam, $wy);
        if ($pc42_solandam != "") {
          $clsolandamand->excluir($pc43_codigo);
          if ($clsolandamand->erro_status == 0) {
            $sqlerro  = true;
            $erro_msg = $clsolandamand->erro_msg;
          }
        }
      }
      $clsolandam->excluir(null, "pc43_solicitem=$pc11_codigo");
      if ($clsolandam->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolandam->erro_msg;
      }
    }
  }

  if ($sqlerro == false) {
    $result_protprocesso = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($pc11_codigo));
    if ($clsolicitemprot->numrows > 0) {
      db_fieldsmemory($result_protprocesso, 0);

      $clsolicitemprot->excluir($pc11_codigo);
      if ($clsolicitemprot->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicitemprot->erro_msg;
      }

      $rsProcTransferProc = $clproctransferproc->sql_record($clproctransferproc->sql_query_file(null,
                                                                                                $pc49_protprocesso));
      if ($clproctransferproc->numrows > 0) {
        db_fieldsmemory($rsProcTransferProc, 0);
        $clproctransferproc->excluir(null, $p63_codproc);
        if ($clproctransferproc->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clproctransferproc->erro_msg;
        }
      }

      if ($sqlerro == false) {
        $clprocandam->excluir(null, "p61_codproc = $pc49_protprocesso");
        if ($clprocandam->erro_status == "0") {
          $sqlerro  = true;
          $erro_msg = $clprocandam->erro_msg;
        }
      }

      if ($sqlerro == false) {
        $clprotprocesso->excluir($pc49_protprocesso);
        if ($clprotprocesso->erro_status == 0) {
          $sqlerro  = true;
          $erro_msg = $clprotprocesso->erro_msg;
        }
      }

    }
  }
  //------------------------------------------------------------------------------------------------------------------------
  //------------------------------------------------------------------------------------------------------------------------

  if ($sqlerro == false) {
    $clsolicitempcmater->excluir(null, @ $pc11_codigo);
    if ($clsolicitempcmater->erro_status == 0) {
      $sqlerro  = true;
      $erro_msg = $clsolicitempcmater->erro_msg;
    }
    if ($sqlerro == false) {
      $clsolicitemele->excluir($pc11_codigo, null);
      if ($clsolicitemele->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicitemele->erro_msg;
      }
    }
  }
  if ($sqlerro == false) {
    $clsolicitemunid->excluir($pc11_codigo);
    if ($clsolicitemunid->erro_status == 0) {
      $erro_msg = $clsolicitemunid->erro_msg;
      $sqlerro  = true;
    }
  }
  if ($sqlerro == false) {

    //    if ($pc30_gerareserva == "t") {
    $result_altext  = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,
                                                                                          null,
                                                                                          "o82_sequencial,o82_codres",
                                                                                          "",
                                                                                          "pc13_codigo = {$pc11_codigo}"));
    $numrows_excres = $clorcreservasol->numrows;
    if ($numrows_excres > 0) {
      for ($i = 0; $i < $numrows_excres; $i++) {
        db_fieldsmemory($result_altext, $i);
        $clorcreservasol->excluir($o82_sequencial);
        $clorcreserva->excluir($o82_codres);
      }
    }
    //    }

    $clpcdotac->excluir(null, "pc13_codigo = {$pc11_codigo}");
    $erro_msg = $clpcdotac->erro_msg;
    if ($clpcdotac->erro_status == 0) {
      $sqlerro = true;
    }


  }

  if ($sqlerro == false) {
    $rsVeic = $clsolicitemveic->sql_record($clsolicitemveic->sql_query_file(null,
                                                                            "pc14_sequencial",
                                                                            "",
                                                                            "pc14_solicitem = $pc11_codigo"));
    if ($clsolicitemveic->numrows > 0) {
      db_fieldsmemory($rsVeic, 0);
      $clsolicitemveic->excluir($pc14_sequencial);
      if ($clsolicitemveic->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = $clsolicitemveic->erro_msg;
      }
    }
  }
  if (isset($o103_pactovalor) && $o103_pactovalor != "" && !$sqlerro) {

    $oSolicitacao = new solicitacaoCompra($pc11_numero);
    $oSolicitacao->excluirVinculacaoItemPacto($pc11_codigo);

  }
  if ($sqlerro == false && $iRegistroPreco != "") {

    try {

      $oSolicitacao = new solicitacaoCompra($pc11_numero);
      $oSolicitacao->removeItensRegitro($iRegistroPreco, $pc11_codigo);

    } catch (Exception $eErro) {

      $sqlerro  = true;
      $erro_msg = $eErro->getMessage();

    }
  }
  if ($sqlerro == false) {
    $clsolicitem->excluir($pc11_codigo);
    $erro_msg = $clsolicitem->erro_msg;
    if ($clsolicitem->erro_status == 0) {
      $sqlerro = true;
    }
  }
  if ($sqlerro == false && $pc30_seltipo == 't') {
    $result_vlsol = $clsolicitatipo->sql_record($clsolicitatipo->sql_query_file($pc11_numero, "pc12_vlrap"));
    if ($clsolicitatipo->numrows > 0) {
      db_fieldsmemory($result_vlsol, 0);
    } else {
      $pc12_vlrap = 0;
    }
    if ($pc12_vlrap == 0) {
      $pc11_vlrun_sit = 0;
    }
    $valexc = $pc12_vlrap - $pc11_vlrun_sit;
    if ($valexc == '') {
      $valexc = 0;
    }
    $clsolicitatipo->pc12_vlrap  = "'" . $valexc . "'";
    $clsolicitatipo->pc12_numero = $pc11_numero;
    $clsolicitatipo->alterar($pc11_numero);
    if ($clsolicitatipo->erro_status == 0) {
      $sqlerro  = true;
      $erro_msg = $clsolicitatipo->erro_msg;
    }
  }
  if ($sqlerro == false && $pc30_permsemdotac == "f") {
    $alterarr        = false;
    $result_altersol = $clpcdotac->sql_record($clpcdotac->sql_query_lefdotac(null,
                                                                             null,
                                                                             null,
                                                                             "pc11_codigo,pc11_quant,sum(pc13_quant) as pc13_quantalter",
                                                                             "",
                                                                             "pc11_numero=$pc11_numero group by pc11_codigo,pc11_quant"));
    if ($clpcdotac->numrows > 0) {
      for ($i = 0; $i < $clpcdotac->numrows; $i++) {
        db_fieldsmemory($result_altersol, $i);
        $result_servico  = $clsolicitem->sql_record($clsolicitem->sql_query_serv($pc11_codigo, "pc01_servico"));
        $numrows_servico = $clsolicitem->numrows;
        if ($numrows_servico > 0) {
          db_fieldsmemory($result_servico, 0);
        } else {
          $pc01_servico = 'f';
        }
        if (isset($pc13_quantalter) && trim($pc13_quantalter) != "") {
          if ($pc01_servico == "f") {
            if ($pc11_quant == $pc13_quantalter) {
              $alterarr = true;
            } else {
              $alterarr = false;
              break;
            }
          } else {
            if ($pc13_quantalter > 0) {
              $alterarr = true;
            } else {
              $alterarr = false;
              break;
            }
          }
        } else {
          $alterarr = false;
          break;
        }
      }
    }
    if ($alterarr == true) {
      $clsolicita->pc10_correto = "true";
    } else {
      $clsolicita->pc10_correto = "false";
    }
    $clsolicita->pc10_numero = $pc11_numero;
    $clsolicita->alterar($pc11_numero);
    if ($clsolicita->erro_status == 0) {
      $sqlerro  = true;
      $erro_msg = $clsolicita->erro_msg;
    }
  }

  //  $sqlerro = true;
  db_fim_transacao($sqlerro);
  // A quantidade de itens das dotações serão atualizadas automaticamente!
}

if ($sqlerro == false) {
  $pc11_codmater   = "";
  $quant_rest      = "";
  $pc11_quant      = "";
  $pc11_vlrun      = "";
  $pc11_prazo      = "";
  $pc11_pgto       = "";
  $pc11_resum      = "";
  $pc11_just       = "";
  $pc01_descrmater = "";
}

if (isset($opcao) && $opcao != "incluir") {

  $sCamposItem = "pc11_numero, pc11_codigo, pc11_quant, pc11_vlrun, pc11_seq, pc11_liberado, pc11_prazo, ";
  $sCamposItem .= "pc11_pgto,pc11_resum,pc11_just";
  $sWhere               = " pc11_codigo=" . @$pc11_codigo . " and pc11_numero=" . @$pc11_numero;
  $sSqlItem             = $clsolicitem->sql_query(null, $sCamposItem, '', $sWhere);
  $result_dad_solicitem = $clsolicitem->sql_record($sSqlItem);
  if ($clsolicitem->numrows > 0) {

    db_fieldsmemory($result_dad_solicitem, 0);

    $sSqlMaterial                = $clsolicitem->sql_query_pcmater(null,
                                                                   "pc16_codmater,pc01_descrmater",
                                                                   '',
                                                                   " pc16_solicitem=" . @ $pc11_codigo);
    $result_dad_solicitempcmater = $clsolicitem->sql_record($sSqlMaterial);

    if ($clsolicitem->numrows > 0) {
      db_fieldsmemory($result_dad_solicitempcmater, 0);
    } else {

      $pc16_codmater   = "";
      $pc01_descrmater = "";
    }
    if (!isset($pc16_codmater) || (isset($pc16_codmater) && trim($pc16_codmater) == "")) {

      if (isset($codigomaterial)) {
        $pc16_codmater = $codigomaterial;
      }
    }
    $sSqlUnidadeQuantidade    = $clsolicitemunid->sql_query_file($pc11_codigo, "pc17_unid,pc17_quant", "pc17_unid");
    $result_dad_solicitemunid = $clsolicitemunid->sql_record($sSqlUnidadeQuantidade);
    if ($clsolicitemunid->numrows > 0) {
      db_fieldsmemory($result_dad_solicitemunid, 0);
    }
  }
  /**
   * Consultamos o item da solicitacao para retorna o item do pacto.
   */
  $oDaoPactoSolicitaItem = db_utils::getDao("pactovalormovsolicitem");
  $sSqlItemPacto         = $oDaoPactoSolicitaItem->sql_query_item(null,
                                                                  'o87_sequencial as o103_pactovalor,
                                                                  o109_descricao',
                                                                  null,
                                                                  "o101_solicitem={$pc11_codigo}");

  $rsItemPacto = $oDaoPactoSolicitaItem->sql_record($sSqlItemPacto);
  if ($oDaoPactoSolicitaItem->numrows > 0) {
    db_fieldsmemory($rsItemPacto, 0);
  }
}
$lMostraItensPacto = false;
if (isset($pc11_numero)) {

  /**
   * Verificamos se o usuário vinculou algum plano no cadastro da
   * solicitacao, caso a a solicitação esteje vicnulado,
   * devemos disponibilizar a possibilidade de o usuario
   * vincular um item da solicitacao a um item do pacto.
   */
  if (isset($aParametrosOrcamento[0]->o50_utilizapacto) && $aParametrosOrcamento[0]->o50_utilizapacto == "t") {

    $oDaoPactoSolicita = db_utils::getDao("orctiporecconveniosolicita");
    $sSqlPacto         = $oDaoPactoSolicita->sql_query_file(null, "*", null, "o78_solicita={$pc11_numero}");
    $rsPacto           = $oDaoPactoSolicita->sql_record($sSqlPacto);
    if ($oDaoPactoSolicita->numrows > 0) {

      $oPlanoPacto       = db_utils::fieldsMemory($rsPacto, 0);
      $iPactoPlano       = $oPlanoPacto->o78_pactoplano;
      $lMostraItensPacto = true;
    }
  }
}
?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?

    db_app::load("scripts.js, prototype.js, datagrid.widget.js,windowAux.widget.js,messageboard.widget.js, strings.js, AjaxRequest.js");
    db_app::load("classes/ultimosOrcamentos.classe.js");
    db_app::load("estilos.css, grid.style.css");
    ?>
    <script>
      lItemPacto = <?=$lMostraItensPacto ? "true" : "false"?>;
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
        <center>
          <?


          require_once(modification("forms/db_frmsolicitem.php"));
          ?>
        </center>
      </td>
    </tr>
  </table>
  </body>
  </html>
  <script>
    function teste() {

      teste = new ultimosOrcamentos();
      teste.setItem($F('pc16_codmater'))
      teste.addUnidade($F('pc17_unid'))
      teste.getOrcamentos();
      teste.setCallBackMedia(function () {

                               $('pc11_vlrun').value = js_strToFloat($F('mediaprecosorcamento'));
                               teste.window.destroy();
                             }
      );
      teste.showUltimosOrcamentos();
    }

    function js_calculaMedia() {

      if ($('pc16_codmater') != "") {

        var oOrcamento = new ultimosOrcamentos();
        oOrcamento.setItem($F('pc16_codmater'))
        oOrcamento.addUnidade($F('pc17_unid'))
        oOrcamento.getOrcamentos();
        oOrcamento.onMediaCalculada = function () {

          $('pc11_vlrun').value = oOrcamento.getMediaPrecos();
          oOrcamento.window.destroy();
        }


      }
    }
    if ($('ultimosorcamentos')) {
      $('ultimosorcamentos').observe('click', teste);
    }
  </script>
<?php
if (isset($alterar) || isset($excluir) || isset($incluir)) {
  if (isset($param) && trim($param) != "") {
    $parametro = "&param=" . $param . "&codproc=" . $codproc . "&codliclicita=" . $codliclicita;
  } else {
    $parametro = "";
  }

  if ($msg_alert != "" && (@$passa == true || isset($incluir)) && $sqlerro == false) {
    db_msgbox($msg_alert);
  }
  if ($sqlerro == true) {
    $erro_msg = str_replace("\n", "\\n", $erro_msg);
    db_msgbox($erro_msg);
    if ($clsolicitem->erro_campo != "") {
      echo "<script> document.form1." . $clsolicitem->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clsolicitem->erro_campo . ".focus();</script>";
    };
    if ($naodig == true) {
      echo "<script> document.form1.info.click();</script>";
      if (isset($alterar)) {
        echo "<script> document.location.href = 'com1_solicitem001.php?pc11_numero=$pc11_numero&pc11_codigo=$pc11_codigo&opcao=alterar&selecao="
             . @$selecao . "$parametro'; </script>";
      }
    }
  } else {
    echo "<script>(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_solicita.location.href = 'com1_solicita005.php?chavepesquisa=$pc11_numero&ld=false$parametro'</script>";
    if ($pc30_sugforn == 't') {
      echo "<script> (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_sugforn.location.href='com1_sugforn001.php?pc40_solic=$pc11_numero$parametro';</script>";
    }
    if (isset($excluir)) {
      echo "<script> document.location.href = 'com1_solicitem001.php?pc11_numero=$pc11_numero&selecao=" . @$selecao
           . "$parametro'; </script>";
    }
  }
}
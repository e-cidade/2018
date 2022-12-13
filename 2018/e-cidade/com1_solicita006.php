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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_solicita_classe.php");
require_once ("classes/db_solicitatipo_classe.php");
require_once ("classes/db_solicitem_classe.php");
require_once ("classes/db_solicitemveic_classe.php");
require_once ("classes/db_solicitempcmater_classe.php");
require_once ("classes/db_solicitemele_classe.php");
require_once ("classes/db_solicitemvinculo_classe.php");
require_once ("classes/db_pcdotac_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_pctipocompra_classe.php");
require_once ("classes/db_db_depart_classe.php");
require_once ("classes/db_pcsugforn_classe.php");
require_once ("classes/db_pcparam_classe.php");
require_once ("classes/db_orcreservasol_classe.php");
require_once ("classes/db_orcreserva_classe.php");
require_once ("classes/db_solicitemunid_classe.php");
require_once ("classes/db_pcorcam_classe.php");
require_once ("classes/db_pcorcamforne_classe.php");
require_once ("classes/db_pcorcamitem_classe.php");
require_once ("classes/db_pcorcamitemsol_classe.php");
require_once ("classes/db_pcorcamval_classe.php");
require_once ("classes/db_pcorcamjulg_classe.php");
require_once ("classes/db_pcorcamtroca_classe.php");
require_once ("classes/db_protprocesso_classe.php");
require_once ("classes/db_solicitemprot_classe.php");
require_once ("classes/db_db_config_classe.php");
require_once ("classes/db_solandpadrao_classe.php");
require_once ("classes/db_solandpadraodepto_classe.php");
require_once ("classes/db_solandam_classe.php");
require_once ("classes/db_solandamand_classe.php");
require_once ("model/itempacto.model.php");
require_once ("classes/db_solordemtransf_classe.php");
require_once ("classes/db_proctransferproc_classe.php");
require_once ("classes/db_procandam_classe.php");
require_once ("classes/db_solicitaprotprocesso_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
//db_postmemory($HTTP_POST_VARS,2);db_postmemory($HTTP_GET_VARS,2);
$clsolicita           = new cl_solicita;
$clsolicitatipo       = new cl_solicitatipo;
$clsolicitem          = new cl_solicitem;
$clsolicitemveic      = new cl_solicitemveic;
$clsolicitempcmater   = new cl_solicitempcmater;
$clsolicitemele       = new cl_solicitemele;
$clsolicitemvinculo   = new cl_solicitemvinculo;
$clpcdotac            = new cl_pcdotac;
$clpctipocompra       = new cl_pctipocompra;
$cldb_depart          = new cl_db_depart;
$clpcsugforn          = new cl_pcsugforn;
$clpcparam            = new cl_pcparam;
$clorcreservasol      = new cl_orcreservasol;
$clorcreserva         = new cl_orcreserva;
$clsolicitemunid      = new cl_solicitemunid;
$clpcorcamforne       = new cl_pcorcamforne;
$clpcorcam            = new cl_pcorcam;
$clpcorcamitem        = new cl_pcorcamitem;
$clpcorcamitemsol     = new cl_pcorcamitemsol;
$clpcorcamval         = new cl_pcorcamval;
$clpcorcamjulg        = new cl_pcorcamjulg;
$clpcorcamtroca       = new cl_pcorcamtroca;
$clprotprocesso       = new cl_protprocesso;
$clsolicitemprot      = new cl_solicitemprot;
$cldb_config          = new cl_db_config;
$clsolandpadrao       = new cl_solandpadrao;
$clsolandpadraodepto  = new cl_solandpadraodepto;
$clsolandam           = new cl_solandam;
$clsolandamand        = new cl_solandamand;
$clsolordemtransf     = new cl_solordemtransf;
$oDaoProctransferproc = new cl_proctransferproc;
$oDaoProcandam        = new cl_procandam;
$oDaoProcessoAdministrativo  = new cl_solicitaprotprocesso();

$opselec              = 3;
$db_opcao             = 33;
$db_botao             = false;
$aParametrosOrcamento = db_stdClass::getParametro("orcparametro",array(db_getsession("DB_anousu")));
$lUtilizaPacto        = false;
if (count($aParametrosOrcamento) > 0) {

  if ($aParametrosOrcamento[0]->o50_utilizapacto == "t") {
    $lUtilizaPacto = true;
  }
}
$result_tipo = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_seltipo,pc30_sugforn"));
if ($clpcparam->numrows>0) {
  db_fieldsmemory($result_tipo,0);
}
if (isset($excluir)) {

  $sqlerro = false;
  db_inicio_transacao();

  $result_excreserva = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(
                                                    null,
                                                    null,
                                                    "o82_codres,o82_sequencial",
                                                    "","
                                                    pc13_codigo in(".$clsolicitem->sql_query_file(null,
                                                                     "pc11_codigo",
                                                                      "",
                                                                      " pc11_numero = $pc10_numero").")"));
  $numrows_excres = $clorcreservasol->numrows;
  if ($numrows_excres > 0) {

    for ($i=0;$i<$numrows_excres;$i++) {

      db_fieldsmemory($result_excreserva,$i);
      $clorcreservasol->excluir($o82_sequencial);
      $erro_msg = $clorcreservasol->erro_msg;
      if ($clorcreservasol->erro_status == 0) {
        $sqlerro=true;
      }
      if (!$sqlerro) {

        $clorcreserva->excluir($o82_codres);
        $erro_msg = $clorcreserva->erro_msg;
        if ($clorcreserva->erro_status == 0) {
          $sqlerro=true;
        }
      }
    }
  }

  /**
   * Verifica se a solicitação é de registro de preço e exclui o orçamento e o processo de compras.
   */
  $sSqlRegistroPreco = $clsolicita->sql_query_processo_orcamento("pc80_codproc, pc20_codorc, pc10_solicitacaotipo", null, " pc10_numero = {$pc10_numero} ");
  $rsRegistroPreco   = $clsolicita->sql_record($sSqlRegistroPreco);

  if ($clsolicita->numrows > 0) {

    $oDadosSolicitacao = db_utils::fieldsMemory($rsRegistroPreco, 0);

    if ($oDadosSolicitacao->pc10_solicitacaotipo == 5) {

      if (!empty($oDadosSolicitacao->pc20_codorc)) {
        $oOrcamento = new OrcamentoCompra($oDadosSolicitacao->pc20_codorc);
        $oOrcamento->remover();
      }

      if (!empty($oDadosSolicitacao->pc80_codproc)) {
        $oProcessoCompra = new ProcessoCompras($oDadosSolicitacao->pc80_codproc);
        $oProcessoCompra->remover();
      }
    }
  }

  $result_solicitem = $clsolicitem->sql_record($clsolicitem->sql_query_file(null,"pc11_codigo","pc11_codigo","pc11_numero=$pc10_numero"));
  $numrows_solicitem = $clsolicitem->numrows;
  for ($w = 0; $w < $numrows_solicitem; $w++) {

    db_fieldsmemory($result_solicitem,$w);
    if (!$sqlerro) {

      $result_ordem=$clsolordemtransf->sql_record($clsolordemtransf->sql_query_file(null,"*",null," pc41_solicitem=$pc11_codigo "));
      if ($clsolordemtransf->numrows > 0) {

        $clsolordemtransf->excluir(null,"pc41_solicitem=$pc11_codigo");
        if ($clsolordemtransf->erro_status == 0) {

          $erro_msg=$clsolordemtransf->erro_msg;
          $sqlerro=true;
        }
      }
    }
    if (!$sqlerro) {
      $result_andpadrao = $clsolandpadrao->sql_record($clsolandpadrao->sql_query_depto(null,"*",null,"pc47_solicitem = $pc11_codigo"));
      $numrows_andpadrao = $clsolandpadrao->numrows;
      if ($numrows_andpadrao>0) {

        for ($wy = 0; $wy < $numrows_andpadrao; $wy++) {

          db_fieldsmemory($result_andpadrao,$wy);
          if ($pc48_solandpadrao != "") {

            $clsolandpadraodepto->excluir($pc47_codigo);
            if ($clsolandpadraodepto->erro_status == 0) {

              $sqlerro=true;
              $erro_msg=$clsolandpadraodepto->erro_msg;
            }
          }
        }
        $clsolandpadrao->excluir(null, "pc47_solicitem={$pc11_codigo}");
        if ($clsolandpadrao->erro_status == 0) {

          $sqlerro=true;
          $erro_msg=$clsolandpadrao->erro_msg;
        }
      }
    }
    if (!$sqlerro) {

      $result_andam = $clsolandam->sql_record($clsolandam->sql_query_and(null,"*",null,"pc43_solicitem = $pc11_codigo"));
      $numrows_andam = $clsolandam->numrows;
      if ($numrows_andam > 0) {

        for($wy = 0; $wy < $numrows_andam; $wy++) {

          db_fieldsmemory($result_andam,$wy);
          if ($pc42_solandam != "") {

            $clsolandamand->excluir($pc43_codigo);
            if ($clsolandamand->erro_status == 0) {

              $sqlerro  = true;
              $erro_msg = $clsolandamand->erro_msg;
            }
          }
        }
        $clsolandam->excluir(null,"pc43_solicitem=$pc11_codigo");
        if ($clsolandam->erro_status == 0) {

          $sqlerro=true;
          $erro_msg=$clsolandam->erro_msg;
        }
      }
    }
    if (!$sqlerro) {

      $result_protprocesso = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($pc11_codigo));
      if ($clsolicitemprot->numrows>0) {

        db_fieldsmemory($result_protprocesso,0);
        $clsolicitemprot->excluir($pc11_codigo);
        if ($clsolicitemprot->erro_status == 0) {

          $sqlerro = true;
          $erro_msg = $clsolicitemprot->erro_msg;
        }

      if (!$sqlerro) {

      	$sSqlBuscaMovimentoProcesso        = $oDaoProctransferproc->sql_query(null, $pc49_protprocesso);
      	$rsSqlBuscaMovimentoProcesso       = $oDaoProctransferproc->sql_record($sSqlBuscaMovimentoProcesso);

      	if ($oDaoProctransferproc->numrows == 0) {

      	  $oDaoProctransferproc->excluir(null, $pc49_protprocesso);
         	if ($oDaoProctransferproc->erro_status == 0) {

         	  $sqlerro  = true;
            $erro_msg = $oDaoProctransferproc->erro_msg;
          }
      	}
      }
        if (!$sqlerro) {

          $sWhereDeleteProcandam = " p61_codproc = {$pc49_protprocesso} ";
          $oDaoProcandam->excluir(null, $sWhereDeleteProcandam);
          $oDaoProctransferproc->excluir(null, $pc49_protprocesso);
          if ($oDaoProcandam->erro_status == 0) {

            $sqlerro  = true;
            $erro_msg = $oDaoProcandam->erro_msg;
          }
        }
        if (!$sqlerro) {
          $clprotprocesso->excluir($pc49_protprocesso);
          if ($clprotprocesso->erro_status == 0) {
            $sqlerro = true;
            $erro_msg=$clprotprocesso->erro_msg;
          }
        }

      }
    }

    if (!$sqlerro) {
    	$result_solicitemitemvinculo = $clsolicitemvinculo->sql_record($clsolicitemvinculo->sql_query_file(null,"*",
    	                                                                      null,"pc55_solicitemfilho = $pc11_codigo"));
    	if ($clsolicitemvinculo->numrows > 0) {

    		db_fieldsmemory($result_solicitemitemvinculo,0);
    		$clsolicitemvinculo->excluir(null,"pc55_solicitemfilho = $pc11_codigo");
    		if ($clsolicitemvinculo->erro_status == 0) {

    			$sqlerro  = true;
    			$erro_msg = $clsolicitemvinculo->erro_msg;
    		}
    	}
    }
  }
  $result_solicitem  = $clsolicitem->sql_record($clsolicitem->sql_query_file(null,"pc11_codigo","pc11_codigo","pc11_numero=$pc10_numero"));
  $numrows_solicitem = $clsolicitem->numrows;
  $oDaoItemPacto     = db_utils::getDao("pactovalormovsolicitem");

  for ($i = 0; $i < $numrows_solicitem; $i++) {

    db_fieldsmemory($result_solicitem,$i);
    if (!$sqlerro) {

      $sSqlItemPacto = $oDaoItemPacto->sql_query(null,"*", null,"o101_solicitem = {$pc11_codigo}");
      $rsPacto       = $oDaoItemPacto->sql_record($sSqlItemPacto);
      if ($oDaoItemPacto->numrows > 0) {

        $oItemDoPacto = db_utils::fieldsMemory($rsPacto, 0);
        try {

          $oPactoItem = new itemPacto($oItemDoPacto->o87_pactoitem);
          $oPactoItem->excluirSaldoSolicitacao($pc11_codigo);
        } catch (Exception $eErro) {

          $sqlerro  = true;
          $erro_msg = $eErro->getMessage();
        }
      }
      $clpcdotac->excluir(null,"pc13_codigo = {$pc11_codigo}");
      if ($clpcdotac->erro_status == 0) {

        $erro_msg = $clpcdotac->erro_msg;
        $sqlerro=true;
      }
    }
    if (!$sqlerro) {

      $clsolicitempcmater->excluir(null,$pc11_codigo);
      if ($clsolicitempcmater->erro_status == 0) {

        $erro_msg = $clsolicitempcmater->erro_msg;
        $sqlerro  = true;
      }
      if (!$sqlerro) {

        $clsolicitemele->excluir($pc11_codigo,null);
        if ($clsolicitemele->erro_status == 0) {

          $erro_msg = $clsolicitemele->erro_msg;
          $sqlerro  = true;
        }
      }
    }
    if (!$sqlerro) {

      $clsolicitemunid->excluir($pc11_codigo);
      if ($clsolicitemunid->erro_status == 0) {

        $erro_msg = $clsolicitemunid->erro_msg;
        $sqlerro  = true;
      }
    }

    if (!$sqlerro) {

      $clsolicitemveic->excluir(null," pc14_solicitem = $pc11_codigo");
      if ($clsolicitemveic->erro_status == 0) {
        $erro_msg = $clsolicitemveic->erro_msg;
        $sqlerro  = true;
      }
    }
	}

  //  die($clpcorcamitemsol->sql_query(null,null,"pc22_orcamitem,pc20_codorc","pc20_codorc,pc22_orcamitem","pc10_numero=$pc10_numero "));
  $result_orcamitemsol  = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query(null,null,"pc20_codorc","pc20_codorc,pc22_orcamitem","pc10_numero=$pc10_numero "));
  $numrows_orcamitemsol = $clpcorcamitemsol->numrows;
  for ($i = 0; $i < $numrows_orcamitemsol; $i++) {

    db_fieldsmemory($result_orcamitemsol,$i);
    if (!$sqlerro) {

      $clpcorcamtroca->excluir(null,"pc25_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.")");
      if ($clpcorcamtroca->erro_status == 0) {

        $sqlerro  = true;
        $erro_msg = $clpcorcamtroca->erro_msg;
      }
    }
    if (!$sqlerro) {

      $clpcorcamjulg->excluir(null,null,"pc24_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.") and pc24_orcamforne in (select pc21_orcamforne from pcorcamforne where pc21_codorc=".$pc20_codorc.")");
      if ($clpcorcamjulg->erro_status == 0) {

        $sqlerro  = true;
        $erro_msg = $clpcorcamjulg->erro_msg;
      }
    }
    if(!$sqlerro) {

      $clpcorcamval->excluir(null,null,"pc23_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.") and pc23_orcamforne in (select distinct pc21_orcamforne from pcorcamforne where pc21_codorc=".$pc20_codorc.")");
      if ($clpcorcamval->erro_status == 0) {

        $sqlerro=true;
        $erro_msg = $clpcorcamval->erro_msg;
      }
    }
    if (!$sqlerro) {

      $clpcorcamitemsol->excluir(null,null,"pc29_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.")");
      if ($clpcorcamitemsol->erro_status == 0) {

        $sqlerro  = true;
        $erro_msg = $clpcorcamitemsol->erro_msg;
      }
    }
    if (!$sqlerro) {

      $clpcorcamitem->excluir(null," pc22_codorc=$pc20_codorc ");
      if ($clpcorcamitem->erro_status == 0) {

        $sqlerro=true;
        $erro_msg = $clpcorcamitem->erro_msg;
      }
    }
    if (!$sqlerro) {

      $clpcorcamforne->excluir(null,"pc21_codorc=$pc20_codorc ");
      if($clpcorcamforne->erro_status==0){
        $sqlerro=true;
        $erro_msg = $clpcorcamforne->erro_msg;
      }
    }
    if (!$sqlerro) {
      $clpcorcam->excluir($pc20_codorc);
      if($clpcorcam->erro_status==0){
        $erro_msg = $clpcorcam->erro_msg;
        $sqlerro=true;
      }
    }
  }

  if (!$sqlerro) {
    $clsolicitem->excluir(null," pc11_numero = $pc10_numero");
    if($clsolicitem->erro_status==0){
      $sqlerro=true;
      $erro_msg = $clsolicitem->erro_msg;
      //die($erro_msg);
    }
  }
  if (!$sqlerro) {
    $clpcsugforn->excluir($pc10_numero,null);
    if($clpcsugforn->erro_status==0){
      $sqlerro=true;
      $erro_msg = $clpcsugforn->erro_msg;
    }
  }
  if (!$sqlerro) {
    $clsolicitatipo->excluir($pc10_numero);
    if($clsolicitatipo->erro_status==0){
      $sqlerro=true;
      $erro_msg = $clsolicitatipo->erro_msg;
    }
  }
  if (!$sqlerro) {

    $oDaoOrctiporecConvenioPacto  = db_utils::getDao("orctiporecconveniosolicita");
    $sSqlPacto                    = $oDaoOrctiporecConvenioPacto->sql_query(null,
                                                                          "o74_sequencial, o74_descricao,o78_sequencial ",
                                                                         null,
                                                                         "o78_solicita = {$pc10_numero}");
    $rsPacto = $oDaoOrctiporecConvenioPacto->sql_record($sSqlPacto);
    if ($oDaoOrctiporecConvenioPacto->numrows > 0) {

      $oPactoSolicita   = db_utils::fieldsMemory($rsPacto, 0);
      $oDaoOrctiporecConvenioPacto->excluir($oPactoSolicita->o78_sequencial);
      if ($oDaoOrctiporecConvenioPacto->erro_status == 0) {

        $sqlerro  = true;
        $erro_msg = $oDaoOrctiporecConvenioPacto->erro_msg;
      }
    }
  }

  /**
   * Exclui vínculo de um registro de preço com a solicitação (solicitavinculo)
   */
  if (!$sqlerro) {

    $oDaoSolicitaVinculo = db_utils::getDao("solicitavinculo");
    $oDaoSolicitaVinculo->excluir(null, "pc53_solicitafilho = {$pc10_numero}");
    if ($oDaoSolicitaVinculo->erro_status == "0") {

      $sqlerro  = true;
      $erro_msg = $oDaoSolicitaVinculo->erro_msg;
    }
  }

  /**
   * Exclui vinculo do Protocolo Administrativo (solicitaprotprocesso)
   */
  if (!$sqlerro) {

    $sWhereProcessoAdministrativo = " pc90_solicita = {$pc10_numero}";
    $oDaoProcessoAdministrativo->excluir(null, $sWhereProcessoAdministrativo);

    if ($oDaoProcessoAdministrativo->erro_status == 0) {

      $sqlerro  = true;
      $erro_msg = $oDaoProcessoAdministrativo->erro_msg;
    }
  }

  /**
   * Exclui solicitação
   */
  if (!$sqlerro) {

    $clsolicita->excluir($pc10_numero);
    if($clsolicita->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clsolicita->erro_msg;
  }
  //$sqlerro = true;
  //db_criatabela(db_query("select * from cadtipo limit 1"));
  db_fim_transacao($sqlerro);
  $pc10_numero= $clsolicita->pc10_numero;
  //$sqlerro = false;
} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result   = $clsolicita->sql_record($clsolicita->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $result_libera = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_liberaitem,pc30_libdotac,pc30_contrandsol"));
  db_fieldsmemory($result_libera,0);

  if ($pc30_contrandsol == 't') {

    $result_andam=$clsolandam->sql_record($clsolandam->sql_query(null,"*","pc43_codigo desc","pc11_numero=$pc10_numero"));
    if ($clsolandam->numrows > 0) {

      db_fieldsmemory($result_andam,0);
      if ($pc43_depto != db_getsession("DB_coddepto")) {

        db_msgbox("Solicitação em outro departamento,em andamento!!");
        echo "<script>location.href='com1_solicita005.php';</script>";
        exit;
      } else {

        $result_transf = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_transf(null,"*",null,"pc49_solicitem = $pc11_codigo and p64_codtran is null"));
        if ($clsolicitemprot->numrows > 0) {

          db_msgbox("Solicitação em outro departamento,em andamento!!");
          echo "<script>location.href='com1_solicita005.php';</script>";
          exit;
        }
      }
    }
  }
  $oDaoOrctiporecConvenioPacto  = db_utils::getDao("orctiporecconveniosolicita");
  $sSqlPacto                    = $oDaoOrctiporecConvenioPacto->sql_query(null,
                                                                          "o74_sequencial, o74_descricao",
                                                                          null,
                                                                          "o78_solicita = {$pc10_numero}");
  $rsPacto = $oDaoOrctiporecConvenioPacto->sql_record($sSqlPacto);
  if ($oDaoOrctiporecConvenioPacto->numrows > 0) {
   db_fieldsmemory($rsPacto, 0);
  }
  /**
  * Busca os Dados do Processo administrativo
  */
  $sWhereProcessoAdministrativo = " pc90_solicita = {$pc10_numero}";
  $sSqlProcessoAdministrativo   = $oDaoProcessoAdministrativo->sql_query_file(null,
                                                                              "pc90_numeroprocesso",
                                                                              null,
                                                                              $sWhereProcessoAdministrativo);
  $rsProcessoAdministrativo     = $oDaoProcessoAdministrativo->sql_record($sSqlProcessoAdministrativo);

  if ($oDaoProcessoAdministrativo->numrows > 0) {
    $pc90_numeroprocesso = db_utils::fieldsMemory($rsProcessoAdministrativo, 0)->pc90_numeroprocesso;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="430" align="left" valign="top" bgcolor="#CCCCCC">
<center>
<?
include("forms/db_frmsolicita.php");
?>
</center>
</td>
</tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox(str_replace("\n","\\n",$erro_msg));
    if($clsolicita->erro_campo!=""){
      echo "<script> document.form1.".$clsolicita->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsolicita->erro_campo.".focus();</script>";
    };
  }else{
    echo "
    <script>
    function js_db_tranca(){
      parent.location.href='com1_solicita003.php';
    }\n
    js_db_tranca();
    </script>\n
    ";
    exit;
  }
}

if(isset($chavepesquisa)){
  echo "
  <script>
  function js_db_libera(){
    parent.document.formaba.solicitem.disabled=false;
    top.corpo.iframe_solicitem.location.href='com1_solicitem001.php?pc11_numero=".@$pc10_numero."&selecao=3';
    ";
    if($pc30_sugforn=='t'){
      echo "
      parent.document.formaba.sugforn.disabled=false;
      top.corpo.iframe_sugforn.location.href='com1_sugforn001.php?pc40_solic=".@$pc10_numero."&db_opcaoal=3';
      ";
    }
    if(isset($liberaaba)){
      echo "  parent.mo_camada('solicita');";
    }
  echo"}\n
  js_db_libera();
  </script>\n
  ";
}
if($db_opcao==22||$db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
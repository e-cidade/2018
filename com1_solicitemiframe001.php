<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once ("libs/db_liborcamento.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");
require_once ("classes/db_solicita_classe.php");
require_once ("classes/db_solicitem_classe.php");
require_once ("classes/db_solicitemele_classe.php");
require_once ("classes/db_solicitempcmater_classe.php");
require_once ("classes/db_pcdotac_classe.php");
require_once ("classes/db_pcdotaccontrapartida_classe.php");
require_once ("classes/db_pcparam_classe.php");
require_once ("classes/db_pcmaterele_classe.php");
require_once ("classes/db_orcorgao_classe.php");
require_once ("classes/db_orcdotacao_classe.php");
require_once ("classes/db_empautidot_classe.php");
require_once ("classes/db_db_depart_classe.php");
require_once ("classes/db_orcreserva_classe.php");
require_once ("classes/db_orcreservasol_classe.php");
require_once ("classes/db_orcelemento_classe.php");
require_once ("classes/db_pcproc_classe.php");
require_once("classes/solicitacaocompras.model.php");

db_postmemory($_GET);
db_postmemory($_POST);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clsolicitem = new cl_solicitem();
$clsolicitemele = new cl_solicitemele();
$clsolicita = new cl_solicita();
$clsolicitempcmater = new cl_solicitempcmater();
$clpcdotac = new cl_pcdotac();
$clpcparam = new cl_pcparam();
$clpcmaterele = new cl_pcmaterele();
$clorcorgao = new cl_orcorgao();
$clorcdotacao = new cl_orcdotacao();
$clempautidot = new cl_empautidot();
$cldb_depart = new cl_db_depart();
$clorcreserva = new cl_orcreserva();
$clorcreservasol = new cl_orcreservasol();
$clorcelemento = new cl_orcelemento();
$clpcproc = new cl_pcproc();

$db_opcao      = 1;
$db_botao      = false;
$altcoddot     = false;
$aParametrosOrcamento = db_stdClass::getParametro("orcparametro",array(db_getsession("DB_anousu")));
// Alteração feita para processo de compra e licitacao
if (isset($param) && trim($param) != "") {
  $parametro = "&param=" . $param;
  if (isset($codproc) && trim($codproc) != "") {
    $parametro .= "&codproc=" . $codproc;
  }

  if (isset($codliclicita) && trim($codliclicita) != "") {
    $parametro .= "&codliclicita=" . $codliclicita;
  }
} else {
  $parametro = "";
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$result_gerareserva = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_gerareserva,pc30_permsemdotac,pc30_passadepart,pc30_ultdotac"));
db_fieldsmemory($result_gerareserva, 0);
$gerareservaold = $pc30_gerareserva;

if (isset($iframe)) {

  if ($iframe == true && ($db_opcion == "incluir" || $db_opcion == "alterar")) {
    $db_opcao = 1;
    $db_botao = true;
  } else if (($iframe == false || $iframe == true) && $db_opcion == "excluir") {
    $db_opcao = 3;
    $db_botao = false;
  }

} else if (isset($opcao)) {

  $db_botao = true;
  $db_opcao = 1;

  if ($opcao == "alterar") {
    $db_opcao = 2;
  } else if ($opcao == "excluir") {
    $db_opcao = 3;
  }

}

$sqlerro = false;
if (isset($incluir) || isset($alterar) || isset($excluir)) {

  if (!isset($pc13_coddot) || (isset($pc13_coddot) && $pc13_coddot == "")) {

    $sqlerro = true;
    if (isset($incluir)) {
      $operacao = "Inclusão";
    } else if (isset($alterar)) {
      $operacao = "Alteração";
    } else if (isset($excluir)) {
      $operacao = "Exclusão";
    }
    $erro_msg = "Usuário: \\n\\n$operacao não efetuada.\\nCódigo da dotação não informado. \\n\\nAdministrador";
  }

  if ($sqlerro == false) {
    db_inicio_transacao();
    $clpcdotac->sql_record("update empparametro set e39_anousu = e39_anousu where e39_anousu =" . db_getsession("DB_anousu"));
  }
}

if ($pc30_gerareserva == 't' && isset($nreserva)) {
  if ((isset($alterar) || isset($excluir))) {
    $result_altext = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null, null, "o80_codres,o80_valor", "", "o80_coddot = $pc13_coddot and o82_solicitem = $pc13_codigo"));
    if ($clorcreservasol->numrows > 0) {
      db_fieldsmemory($result_altext, 0, true);
      if ($o80_valor < $pc13_valor) {
        $altcoddot = true;
      }
    }
  }
  if ((isset($pesquisa_dot) || isset($pc13_coddot) && $pc13_coddot != "") && $sqlerro == false) {
    //===================================================>>
    //*******rotina que verifica se ainda existe saldo disponivel******************//
    //rotina para calcular o saldo final
    $result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$pc13_coddot", db_getsession("DB_anousu"));
    db_fieldsmemory($result, 0, true);

    $tot = ((0 + $atual_menos_reservado) - (0 + $pc13_valor));
    if (isset($o80_valor)) {
      $tot += $o80_valor;
      $atual_menos_reservado += $o80_valor;
    }
  }
}

$erro_msg = "";
$sqlerrosaldo = false;
if (isset($incluir) && $sqlerro == false) {

  if ($pc13_quant <= 0 && $pc13_valor <= 0) {
    $sqlerro = true;
    $erro_msg = "Usuário: \\n\\nInformado corretamente a quantidade ou o valor da dotação. \\n\\nAdministrador:";
  }
  if ($pc13_valor <= 0) {
    $pc30_gerareserva = 'f';
  }
  if ($pc30_gerareserva == 't' && isset($nreserva)) {
    if (((isset($atual_menos_reservado) && $atual_menos_reservado < $pc13_valor) || (isset($tot) && $tot < 0)) && $sqlerro == false) {
      $sqlerrosaldo = true;
      $saldoreserva = $atual_menos_reservado;
    } else {
      $saldoreserva = $pc13_valor;
    }

    if ($saldoreserva <= 0) {
      $sqlerro = true;
      $erro_msg = "Usuário: \\n\\nDotação sem saldo disponivel. \\n\\nAdministrador:";
    }
  }
  /**
   * Verificamos o elemento do item, que deve ser igual ao da dotacao.
   * caso nao seje, avisamoso usuário, e cancelamos a operacao;
   */


  if (isset($pc13_coddot)) {
    $result_codele = $clorcdotacao->sql_record($clorcdotacao->sql_query($pc13_anousu, $pc13_coddot, "o58_codele as o56_codele,o56_elemento"));
    if ($clorcdotacao->numrows > 0) {
      $oElementoDotacao = db_utils::fieldsMemory($result_codele, 0);
    }
  }
  $rsElementoItem = $clsolicitemele->sql_record($clsolicitemele->sql_query($pc13_codigo,null,"o56_elemento"));
  if ($clsolicitemele->numrows > 0) {

    $oElementoItem = db_utils::fieldsMemory($rsElementoItem, 0);
    if (substr($oElementoItem->o56_elemento,0,7) != substr($oElementoDotacao->o56_elemento,0,7)) {

      $sqlerro  = true;
      $erro_msg = "Elemento da dotacao ($oElementoDotacao->o56_elemento) e do item ($oElementoItem->o56_elemento) não conferem.\\nDotação não Inclusa.";

    }
  }

  if ($sqlerro == false) {

    /**
     * Selecionamos a dotação para ver se já nao existe uma cadastra com o memso item
     */
    $sSqlDotacao  = "select pc13_sequencial";
    $sSqlDotacao .= "  from pcdotac ";
    $sSqlDotacao .= " where pc13_codigo = {$pc13_codigo} ";
    $sSqlDotacao .= "   and pc13_coddot = {$pc13_coddot}";
    $rsDotacoes   = $clpcdotac->sql_record($sSqlDotacao);

    if ($clpcdotac->numrows > 0) {

      $sqlerro  = true;
      $erro_msg = "Dotação/contrapartida já cadastrada";
    }
  }

  if ($sqlerro == false) {

      $clpcdotac->pc13_anousu = $pc13_anousu;
      $clpcdotac->pc13_coddot = $pc13_coddot;
      $clpcdotac->pc13_codigo = $pc13_codigo;
      $clpcdotac->pc13_depto = $pc13_depto;
      $clpcdotac->pc13_quant = $pc13_quant;
      $clpcdotac->pc13_valor = $pc13_valor;
      //select para buscar o código do elemento
      //die($clorcelemento->sql_query_file(null,"o56_codele",""," o56_elemento='$o56_elemento'"));
      if (isset($o56_codele) && $o56_codele != "") {
        $result_codele = $clorcelemento->sql_record($clorcelemento->sql_query_file(null, null, "o56_codele", "", " o56_anousu = $pc13_anousu and  o56_elemento='$o56_elemento'"));
        if ($clorcelemento->numrows > 0) {
          db_fieldsmemory($result_codele, 0);
        }
      }
      if ((! isset($o56_codele) || (isset($o56_codele) && $o56_codele == "")) && isset($pc13_coddot)) {
        $result_codele = $clorcdotacao->sql_record($clorcdotacao->sql_query_file($pc13_anousu, $pc13_coddot, "o58_codele as o56_codele"));
        if ($clorcdotacao->numrows > 0) {
          db_fieldsmemory($result_codele, 0);
        }
      }

      $clpcdotac->pc13_codele = @$o56_codele;
      $clpcdotac->incluir(null);
      if ($clpcdotac->erro_status == 0) {

        $sqlerro = true;
        $erro_msg = $clpcdotac->erro_msg."erro_status==erro inclusao";
      }


    //Incluimos as contrapartidas da dotacao.
      if (!$sqlerro) {

        try {

          $oSolicitacao = new solicitacaoCompra($pc11_numero);
          if (!$oSolicitacao->itemHasDotacoes($pc13_codigo, $pc13_coddot, @$pc19_orctiporec)) {
             $oSolicitacao->saveContrapartidas($clpcdotac->pc13_sequencial,@$pc19_orctiporec, $pc13_valor);
          } else {

            $sqlerro  = true;
            $erro_msg = "Dotação/contrapartida já cadastrada";

          }

        } catch (Exception $eSolic) {

          $sqlerro  = true;
          $erro_msg = $eSolic->getMessage();

        }
      }

      if ($pc30_ultdotac == "t") {
        if ($pc13_coddot != db_getsession("DB_coddot", false)) {
          db_putsession("DB_coddot", $pc13_coddot);
        }

        $res_pcdotac = @db_query("select pc13_depto, descrdepto
	                                               from pcdotac
		    		                            inner join db_depart on db_depart.coddepto = pcdotac.pc13_depto
			 	                       where pc13_anousu = " . db_getsession("DB_anousu") . " and
				                             pc13_depto  = " . db_getsession("DB_coddepto") . "
				                       order by pc13_codigo desc limit 1");
        if (@pg_numrows($res_pcdotac) > 0) {
          db_fieldsmemory($res_pcdotac, 0);
          $flag_dotac = true;
        }

        if (! isset($nreserva) && trim($nreserva) != "") {
          $nreserva = true;
        }
      }
  }

  if ($pc30_gerareserva == 't' && isset($nreserva)) {

    if ($sqlerro == false) {
      $clorcreserva->o80_anousu = db_getsession("DB_anousu");
      $clorcreserva->o80_coddot = $pc13_coddot;
      $clorcreserva->o80_dtfim = date('Y', db_getsession('DB_datausu')) . "-12-31";
      $clorcreserva->o80_dtini = date('Y-m-d', db_getsession('DB_datausu'));
      $clorcreserva->o80_dtlanc = date('Y-m-d', db_getsession('DB_datausu'));
      if (isset($sqlerrosaldo) && $sqlerrosaldo == false) {
        $clorcreserva->o80_valor = $pc13_valor;
        $saldoreserva = $pc13_valor;
      } else {
        $clorcreserva->o80_valor = $saldoreserva;
      }
      $clorcreserva->o80_descr = " ";
      if ($saldoreserva > 0) {
        $clorcreserva->incluir(null);
        $o80_codres = $clorcreserva->o80_codres;
        if ($clorcreserva->erro_status == 0) {
          $sqlerro = true;
          $erro_msg = $clorcreserva->erro_msg;
        }
        if ($sqlerro == false) {
          $clorcreservasol->o82_codres    = $o80_codres;
          $clorcreservasol->o82_pcdotac   = $clpcdotac->pc13_sequencial;
          $clorcreservasol->o82_solicitem = $pc13_codigo;
          $clorcreservasol->incluir(null);
          if ($clorcreservasol->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $clorcreservasol->erro_msg."erro soliuciut";
          }
        }
      }
    } else if ($sqlerro == false && isset($sqlerrosaldo) && $sqlerrosaldo == true) {
      if ($saldoreserva > 0) {
        db_msgbox("Atenção: \\nDotação sem saldo total disponível disponível.\\nReserva de saldo parcial da dotação efetuada.");
      } else {
        db_msgbox("Atenção: \\nDotação sem saldo disponível disponível.\\nReserva de saldo da dotação efetuada.");
      }
    }
  }
  
  //db_fim_transacao($sqlerro);
} else if (isset($alterar) && $sqlerro == false) {

  if (isset($param) && trim($param) != "") {
    $result_pcproc = $clpcproc->sql_record($clpcproc->sql_query_autitem(null, "distinct pc81_codprocitem", null, "pc81_solicitem = $pc13_codigo and
									              e55_sequen is not null and e54_anulad is null"
										      /* e55_sequen     = pc81_codprocitem"*/
								                    ));
    if ($clpcproc->numrows > 0) {
      $sqlerro = true;
      $erro_msg = "Dotação não pode ser alterada, item já autorizado a empenho ou empenhado!";
    }
  }

  if ($sqlerro == false) {
    $res_dotacao = $clpcdotac->sql_record($clpcdotac->sql_query_dotreserva(null, null, null, "distinct pc13_valor  as valor,
												  pc13_coddot as dotac", null, "pc11_numero=$pc11_numero"));
    $numrows_dotacao = $clpcdotac->numrows;

    $valor_totdot = 0;
    $valor_total = 0;
    for($i = 0; $i < $numrows_dotacao; $i ++) {
      db_fieldsmemory($res_dotacao, $i);
      if ($dotac == $pc13_coddot) {
        $valor_totdot += $valor;
        $valor_total += $valor;
      }
    }

    //                 $valor_total = $valor_unit * $quant_total;
    //                 echo $pc13_coddot." => ".$valor_totdot." => ".$valor_total;


    if ($valor_total > $valor_totdot) {
      $sqlerro = true;
      $erro_msg = "Usuário: \\n\\nInforme corretamente o valor da dotação. \\n\\nAdministrador:";
    }

    //                 exit;


    if ($pc13_quant <= 0 && $pc13_valor <= 0) {
      $sqlerro = true;
      $erro_msg = "Usuário: \\n\\nInforme corretamente a quantidade ou o valor da dotação. \\n\\nAdministrador:";
    }
  }
  if ($pc30_gerareserva == 't' && isset($nreserva)) {
    if ($altcoddot == true) {
      if (($o80_valor + $atual_menos_reservado) < $pc13_valor && $sqlerro == false) {
        $sqlerrosaldo = true;
        $saldoreserva = $atual_menos_reservado + $o80_valor;
      }
    }
    if (($atual_menos_reservado < $pc13_valor || $tot < 0) && $sqlerro == false) {
      $sqlerrosaldo = true;
      $saldoreserva = $atual_menos_reservado;
    } else {
      $saldoreserva = $pc13_valor;
    }
  } else {
    if (isset($itens) && trim($itens) != "") {
      $vet_itens = split("#", $itens);
      $vet_dotac_itens = split("#", $dotac_itens);
      $virgula = "";
      $lista_itens = "";
      $lista_dotac_itens = "";

      //print_r($vet_itens);


      for($i = 0; $i < sizeof($vet_itens); $i ++) {
        if (strlen($vet_itens [$i]) > 0) {
          $lista_itens .= $virgula . $vet_itens [$i];
          $virgula = ", ";
        }
      }

      $virgula = "";
      for($i = 0; $i < sizeof($vet_dotac_itens); $i ++) {
        if (strlen($vet_dotac_itens [$i]) > 0) {
          $lista_dotac_itens .= $virgula . $vet_dotac_itens [$i];
          $virgula = ", ";
        }
      }

      $res_orcreservasol = $clorcreservasol->sql_record($clorcreservasol->sql_query(null,"o80_codres as codres", "o82_solicitem", "o82_solicitem in ($lista_itens) and o80_coddot in ($lista_dotac_itens)"));
      if ($clorcreservasol->numrows > 0) {
        $numrows = $clorcreservasol->numrows;
        $lista_res = "";
        $virgula = "";
        for($i = 0; $i < $numrows; $i ++) {
          db_fieldsmemory($res_orcreservasol, $i);
          $lista_res .= $virgula . $codres;
          $virgula = ", ";
        }

        $clorcreservasol->excluir(null,"o82_codres in ($lista_res)");
        if ($clorcreservasol->erro_status == 0) {
          $erro_msg = $clorcreservasol->erro_msg;
          $sqlerro = true;
        }

        if ($sqlerro == false) {
          $clorcreserva->excluir(null, "o80_codres in ($lista_res)");
          if ($clorcreserva->erro_status == 0) {
            $erro_msg = $clorcreserva->erro_msg;
            $sqlerro = true;
          }
        }
      }
    }
  }

  if ($sqlerro == false) {

    //$rsPcDotac   = $clpcdotac->sql_record($clpcdotac->sql_query_file($pc13_codigo, $pc13_anousu, $pc13_coddot, "pc13_sequencial"));
    //$oPcDotac    = db_utils::fieldsMemory($rsPcDotac, 0);

    $clpcdotac->pc13_anousu     = $pc13_anousu;
    $clpcdotac->pc13_coddot     = $pc13_coddot;
    $clpcdotac->pc13_codigo     = $pc13_codigo;
    $clpcdotac->pc13_depto      = $pc13_depto;
    $clpcdotac->pc13_quant      = $pc13_quant;
    $clpcdotac->pc13_valor      = $pc13_valor;
    $clpcdotac->pc13_sequencial = $pc13_sequencial;

    //select para buscar o código do elemento
    $result_codele = $clorcelemento->sql_record($clorcelemento->sql_query_file(null, null, "o56_codele", "", " o56_anousu = $pc13_anousu and o56_elemento='$o56_elemento'"));
    if ($clorcelemento->numrows > 0) {
      db_fieldsmemory($result_codele, 0);
    }

    $clpcdotac->pc13_codele = $o56_codele;
    $clpcdotac->alterar($pc13_sequencial);
    if ($clpcdotac->erro_status == 0) {
      $sqlerro = true;
    }
    $erro_msg = $clpcdotac->erro_msg;
    try {

     $oSolicitacao = new solicitacaoCompra($pc11_numero);
     if (!$oSolicitacao->itemHasDotacoes($pc13_codigo, $pc13_coddot, $pc19_orctiporec,$pc13_sequencial)) {
           $oSolicitacao->saveContrapartidas($clpcdotac->pc13_sequencial,$pc19_orctiporec, $pc13_valor);
        } else {

          $sqlerro  = true;
          $erro_msg = "Dotação/contrapartida já cadastrada";
        }

    }
    catch (Exception $eSolic) {

      $sqlerro  = true;
      $erro_msg = $eSolic->getMessage()."erro alterar";

    }
    if ($pc30_gerareserva == 't' && isset($nreserva)) {
      if ($sqlerro == false) {
        if (isset($o80_codres) && $o80_codres != "") {
          if ((isset($pc13_valor) && $pc13_valor > 0) || (isset($saldoreserva) && $saldoreserva > 0)) {
            if (isset($sqlerrosaldo) && $sqlerrosaldo == false) {
              $clorcreserva->atualiza_valor($o80_codres, $pc13_valor);
            } else {
              $clorcreserva->atualiza_valor($o80_codres, $saldoreserva);
            }
          } else {
            if ($sqlerro == false) {
              $clorcreservasol->excluir(null,"o82_pcdotac = {$pc13_sequencial}");
              if ($clorcreservasol->erro_status == 0) {
                $sqlerro = true;
                $erro_msg = $clorcreservasol->erro_msg;
              }
            }
            if ($sqlerro == false) {
              $clorcreserva->excluir($o80_codres);
              if ($clorcreserva->erro_status == 0) {
                $sqlerro = true;
                $erro_msg = $clorcreserva->erro_msg;
              }
            }
          }
        } else {
          $clorcreserva->o80_anousu = db_getsession("DB_anousu");
          $clorcreserva->o80_coddot = $pc13_coddot;
          $clorcreserva->o80_dtfim = date('Y', db_getsession('DB_datausu')) . "-12-31";
          $clorcreserva->o80_dtini = date('Y-m-d', db_getsession('DB_datausu'));
          $clorcreserva->o80_dtlanc = date('Y-m-d', db_getsession('DB_datausu'));
          if (isset($sqlerrosaldo) && $sqlerrosaldo == false) {
            $clorcreserva->o80_valor = $pc13_valor;
            $saldoreserva = $pc13_valor;
          } else {
            $clorcreserva->o80_valor = $saldoreserva;
          }
          $clorcreserva->o80_descr = " ";
          if ($saldoreserva > 0) {
            $clorcreserva->incluir(null);
            $o80_codres = $clorcreserva->o80_codres;
            if ($clorcreserva->erro_status == 0) {
              $sqlerro = true;
              $erro_msg = $clorcreserva->erro_msg;
            }
            if ($sqlerro == false) {

              $clorcreservasol->o82_codres    = $o80_codres;
              $clorcreservasol->o82_pcdotac   = $pc13_sequencial;
              $clorcreservasol->o82_solicitem = $pc13_codigo;
              $clorcreservasol->incluir(null);
              if ($clorcreservasol->erro_status == 0) {
                $sqlerro = true;
                $erro_msg = $clorcreservasol->erro_msg;
              }
            }
          }
        }
        if ($sqlerro == false && $sqlerrosaldo == true) {
          if ($saldoreserva > 0) {
            $erro_msg .= "Atenção: \\nDotação sem saldo disponível disponível.\\nReserva de saldo parcial da dotação efetuada.";
          } else {
            $erro_msg .= "Atenção: \\nDotação sem saldo disponível disponível.\\nReserva de saldo da dotação efetuada.";
          }
        }
      }
    }
    // $sqlerro = true;
  // db_fim_transacao($sqlerro);
  }
} else if (isset($excluir) && $sqlerro == false) {
  $sqlerro = false;

  if (isset($param) && trim($param) != "") {
    $result_pcproc = $clpcproc->sql_record($clpcproc->sql_query_autitem(null, "distinct pc81_codprocitem", null, "pc81_solicitem = $pc13_codigo and
									          e55_sequen is not null and e54_anulad is null"
										  /*e55_sequen     = pc81_codprocitem"*/
								                ));
    if ($clpcproc->numrows > 0) {
      $sqlerro = true;
      $erro_msg = "Dotação não pode ser excluida, item já autorizado a empenho ou empenhado!";
    }
  }

  /*
   * T.45839
   * Bloco if comentado para nao depender do parametro para exclusao dos itens que tenham saldo
   */

//  if ($pc30_gerareserva == 't' && isset($nreserva)) {
    if (isset($o80_codres)) {
      if ($sqlerro == false) {
        $clorcreservasol->excluir(null,"o82_codres = {$o80_codres}");
        if ($clorcreservasol->erro_status == 0) {
          $sqlerro = true;
          $erro_msg = $clorcreservasol->erro_msg;
        }
      }
      if ($sqlerro == false) {
        $clorcreserva->excluir($o80_codres);
        if ($clorcreserva->erro_status == 0) {
          $sqlerro = true;
          $erro_msg = $clorcreserva->erro_msg;
        }
      }
    }
 // }
  if ($sqlerro == false) {

    $rsPcDotac = $clpcdotac->sql_record($clpcdotac->sql_query_file($pc13_codigo,$pc13_anousu, $pc13_coddot,"pc13_sequencial"));
    if ($clpcdotac->numrows > 0) {

      echo $clpcdotac->numrows ;
      $oDotac = db_utils::fieldsMemory($rsPcDotac, 0);
      $clpcdotaccontra = new cl_pcdotaccontrapartida();
      $clpcdotaccontra->excluir(null,"pc19_pcdotac = {$oDotac->pc13_sequencial}");
      if ($clpcdotaccontra->erro_status == 0) {
        $sqlerro  = true;
        $erro_msg = "Não foi possivel excluir a contrapartida";
      }
      $clpcdotac->excluir($oDotac->pc13_sequencial);
      if ($clpcdotac->erro_status == 0) {
       $sqlerro = true;
      }
      $erro_msg = $clpcdotac->erro_msg;
    }
  }
}

if (isset($incluir) || isset($alterar) || isset($excluir)) {

  if ($sqlerro == false && $pc30_permsemdotac == "f") {
    $alterarr = false;
    $result_altersol = $clpcdotac->sql_record($clpcdotac->sql_query_lefdotac(null, null, null, "pc11_codigo,pc11_quant,sum(pc13_quant) as pc13_quantalter", "", "pc11_numero=$pc11_numero group by pc11_codigo,pc11_quant"));
    if ($clpcdotac->numrows > 0) {
      for($i = 0; $i < $clpcdotac->numrows; $i ++) {
        db_fieldsmemory($result_altersol, $i);
        $result_servico = $clsolicitem->sql_record($clsolicitem->sql_query_serv($pc11_codigo, "pc01_servico"));
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
  } else if($sqlerro == false && $pc30_permsemdotac == "t") {
  	$clsolicita->pc10_correto = "true";
  }

  $clsolicita->pc10_numero = $pc11_numero;
  $clsolicita->alterar($pc11_numero);
  if ($clsolicita->erro_status == 0) {
    $sqlerro = true;
    $erro_msg = $clsolicita->erro_msg;
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
     echo "<script> top.corpo.iframe_solicitem.location.href = 'com1_solicitem001.php?pc11_numero=$pc11_numero&pc11_codigo=$pc13_codigo&pc13_codele=".@$pc13_codele."$parametro'; </script>";
  }
}

if (isset($opcao) && $opcao != "incluir") {
  $result_pcdotac = $clpcdotac->sql_record($clpcdotac->sql_query_depart(null,
                                                                        null,
                                                                        null,
                                                                        "pc13_coddot,
  		                                                                   pc13_quant,
  		                                                                   pc13_valor,
                                                                         pc13_depto,
  		                                                                   pc19_orctiporec,
                                                                         pc13_codele,
                                                                         descrdepto",
                                                                        null,
                                                                        "pc13_sequencial = $pc13_sequencial"
                                                                        ));
  if ($clpcdotac->numrows > 0) {
    db_fieldsmemory($result_pcdotac, 0);
  }
}
if (isset($pesquisa_dot)) {
  $result_descrdepto = $cldb_depart->sql_record($cldb_depart->sql_query_file($pc13_depto, "descrdepto"));
  if ($cldb_depart->numrows > 0) {
    db_fieldsmemory($result_descrdepto, 0);
  }
}
$pc30_gerareserva = $gerareservaold;

if ($pc30_ultdotac == "t") {
  if (! isset($pc13_coddot) && trim(@$pc13_coddot) == "") {
    if (db_getsession("DB_coddot", false) != null) {
      $res_pcdotac = @db_query("select pc13_codigo as coddotseq, pc13_coddot as coddot, pc13_anousu, pc13_depto, descrdepto
	                                 from pcdotac
		    		              inner join db_depart on db_depart.coddepto = pcdotac.pc13_depto
			 	         where pc13_anousu = " . db_getsession("DB_anousu") . " and
				               pc13_coddot = " . db_getsession("DB_coddot") . "
				         order by pc13_codigo desc limit 1");
      if (@pg_numrows($res_pcdotac) > 0) {
        db_fieldsmemory($res_pcdotac, 0);
        $pc13_coddot = $coddot;
      }
    }
  }
}
$iPactoPlano   = "";
$iRecursoPlano = "";
if (isset($aParametrosOrcamento[0]->o50_utilizapacto) && $aParametrosOrcamento[0]->o50_utilizapacto == "t") {

    $oDaoPactoSolicita = db_utils::getDao("orctiporecconveniosolicita");
    $sSqlPacto         = $oDaoPactoSolicita->sql_query(null,"*",null,"o78_solicita={$pc11_numero}");
    $rsPacto           = $oDaoPactoSolicita->sql_record($sSqlPacto);
    if ($oDaoPactoSolicita->numrows > 0) {

      $oPlanoPacto   = db_utils::fieldsMemory($rsPacto, 0);
      $iPactoPlano   = $oPlanoPacto->o78_pactoplano;
      $iRecursoPlano = $oPlanoPacto->o16_orctiporec;
      $lMostraItensPacto = true;
    }
 }

 if (isset($pc13_codigo) && $pc13_codigo != "") {

  $oDaoSolicitem           = new cl_solicitem();
  $sSqlValidaServicoQuantidade = "select pc01_servico,
                                         pc11_servicoquantidade
                                    from solicitem
                                         inner join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                                         inner join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater
                                   where pc11_numero = {$pc11_numero}
                                     and pc11_codigo = {$pc13_codigo}";
  $rsServicoQuantidade  = $oDaoSolicitem->sql_record($sSqlValidaServicoQuantidade);
  if ($oDaoSolicitem->numrows > 0) {

  	$oDadosServicoQuantidade = db_utils::fieldsMemory($rsServicoQuantidade, 0);
  	if ($oDadosServicoQuantidade->pc01_servico == 't') {

  		if ($oDadosServicoQuantidade->pc11_servicoquantidade == 't') {
  			$tquant = "false";
  		} else {
  			$tquant = "true";
  		}

  	} else {
  		$tquant = "false";
  	}

  }

 }

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table width="100%">
  <tr>
    <td width="100%">
      <?
      include ("forms/db_frmsolicitemiframe.php");
      ?>
    </td>
  </tr>
</table>
</center>
</body>
</html>
<?
if (isset($alterar) || isset($excluir) || isset($incluir)) {

  if ($sqlerro == true) {

    $erro_msg = str_replace("\n", "\\n", $erro_msg);
    db_msgbox($erro_msg);
    if ($clsolicitem->erro_campo != "") {

      echo "<script> document.form1." . $clpcdotac->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clpcdotac->erro_campo . ".focus();</script>";
    } else {
      //      echo "<script> top.corpo.iframe_solicitem.location.href = 'com1_solicitem001.php?pc11_numero=$pc11_numero&pc11_codigo=$pc13_codigo&opcao=alterar'; </script>";
    }
  }
}
?>
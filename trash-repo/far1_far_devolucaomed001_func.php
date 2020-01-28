<?
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

//includes da fun��o
require_once("libs/db_utils.php");
require_once("classes/requisicaoMaterial.model.php");
require_once("classes/materialestoque.model.php");

require_once "libs/db_app.utils.php";
db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");
/*
* Function Devolv_material
*
* @Efetua a devolu��o de um item especifico no estoque
*
* 1@ - Valores*    - String contendo informa��es do item a ser devolvido
* 2@ - m42_codigo - int Codigo de atendimento da requisi��o na qual o item pertence
* 3@ - m45_obs    - string com o motivo da devolu��o do produto
* @return boolean indica se houve ou n�o erro. True: houve erro. False: n�o houve erro
*
*/

  //////////////////////////////////////////////////////////////////////////////////////////////////
  //  String Valores                                                                              //
  //                                                                                              //
  //  As informa��es como quantidade,atendrequiitem,codmater...                                   //
  //  do produto a ser devolvido chgan aqui atravez da string valores                             //
  //  os dados foran concatnados nessa string com um padr�o separados por '_':                    //
  //                                                                                              //
  //  quant_[N� atendrequiitem]_[N� codmater]_[N� matrequiitem]_[Quant devolvida]_[iCodigoIniMei] //
  //                                                                                              //
  //  este � o padr�o de um item eles podem vir em sequencia na mesma string assim:               //
  //                                                                                              //
  //  quant_..._quant_...quant_...  [3 Itens]                                                     //
  //                                                                                              //
  //////////////////////////////////////////////////////////////////////////////////////////////////


  function devolveMaterial($valores, $m42_codigo, $m45_obs) {

  $oDaoAtendrequi           = db_utils::getdao('atendrequi');
  $oDaoAtendrequiitem       = db_utils::getdao('atendrequiitem');
  $oDaoAtendrequiitemmei    = db_utils::getdao('atendrequiitemmei');
  $oDaoMatrequi             = db_utils::getdao('matrequi');
  $oDaoMatrequiitem         = db_utils::getdao('matrequiitem');
  $oDaoMatestoque           = db_utils::getdao('matestoque');
  $oDaoMatestoqueini        = db_utils::getdao('matestoqueini');
  $oDaoMatestoqueinimei     = db_utils::getdao('matestoqueinimei');
  $oDaoMatestoqueinimeimdi  = db_utils::getdao('matestoqueinimeimdi');
  $oDaoMatestoqueitem       = db_utils::getdao('matestoqueitem');
  $oDaoMatestoquedev        = db_utils::getdao('matestoquedev');
  $oDaoMatestoquedevitem    = db_utils::getdao('matestoquedevitem');
  $oDaoMatestoquedevitemmei = db_utils::getdao('matestoquedevitemmei');
  $pesq                     = false;

  //Zerando Variaveis
  $devolver        = 0;
  $quant_devolvida = 0;
  $quant_altera    = 0;
  $valordev        = 0;
  $valor_uni       = 0;

  $aItens = array();
  if (trim($valores) != '') {

    $sql               = $oDaoAtendrequiitem->sql_query(null, 'm40_codigo', null, "m43_codatendrequi = $m42_codigo");
    $result_m40_codigo = $oDaoAtendrequiitem->sql_record($sql);
    $linhas_m40_codigo = $oDaoAtendrequiitem->numrows;
    if ($linhas_m40_codigo != 0) {
      $obj1 = db_utils::fieldsmemory($result_m40_codigo, 0);
    } else {

      $oDaoAtendrequiitem->erro(true, false);
      return true;

    }
    $iCodigoRequisicao                    = $obj1->m40_codigo;;
    $oDaoMatestoquedev->m45_depto         = db_getsession('DB_coddepto');
    $oDaoMatestoquedev->m45_login         = db_getsession('DB_id_usuario');
    $oDaoMatestoquedev->m45_hora          = db_hora();
    $oDaoMatestoquedev->m45_data          = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoMatestoquedev->m45_obs           = $m45_obs;
    $oDaoMatestoquedev->m45_codmatrequi   = $obj1->m40_codigo;
    $oDaoMatestoquedev->m45_codatendrequi = $m42_codigo;
    $oDaoMatestoquedev->incluir(null);
    $erro_msg                             = $oDaoMatestoquedev->erro_msg;
    if ($oDaoMatestoquedev->erro_status == '0') {

      $oDaoMatestoquedev->erro(true, false);
      return true;

    }
    $codigo = $oDaoMatestoquedev->m45_codigo;

  } else {

    echo "<script>alert('Nenhum item informado para devolu��o.');</script>";
    return true;

  }

  $oDaoMatestoqueini->m80_login    = db_getsession('DB_id_usuario');
  $oDaoMatestoqueini->m80_data     = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoMatestoqueini->m80_hora     = date('H:i:s');
  $oDaoMatestoqueini->m80_obs      = @$m45_obs;
  $oDaoMatestoqueini->m80_codtipo  = '18';
  $oDaoMatestoqueini->m80_coddepto = db_getsession('DB_coddepto');
  $oDaoMatestoqueini->incluir(@$m80_codigo);

  if ($oDaoMatestoqueini->erro_status == '0') {

    $oDaoMatestoqueini->erro(true, false);
    return true;

  }

  $m82_matestoqueini = $oDaoMatestoqueini->m80_codigo;
  $dados             = split("quant_","$valores");

  for ($y = 1; $y < count($dados); $y++) {

    $info            = split('_', $dados[$y]);
    $atendrequiitem  = $info[0];
    $codmatmater     = $info[1];
    $matrequiitem    = $info[2];
    $quant_devolvida = $info[3];
    $iCodigoIniMei   = $info[4];

    $oItem                     = new stdClass;
    $oItem->iCodigoMaterial    = $codmatmater;
    $oItem->nQuantidade        = $quant_devolvida;
    $oItem->iCodigoAtendimento = $m82_matestoqueini;
    $aItens[]                  = $oItem;

    $sSql            = $oDaoAtendrequiitem->sql_query_file($atendrequiitem);
    $result_atend    = $oDaoAtendrequiitem->sql_record($sSql);
    if ($oDaoAtendrequiitem->numrows != '0') {

      $obj4           = db_utils::fieldsmemory($result_atend,0);
    	$m43_quantatend = $obj4->m43_quantatend;
      $m43_codigo     = $obj4->m43_codigo;

    }

    /**
     * Consultamos se existe uma apropriacao de custo para esse atendimento;
     * caso exista, verificamos se � necess�rio atualizar os valores dessa apropria��o
     */
    $oDaoCustoAproria   = db_utils::getdao('custoapropria');
    $sSqlCustoAproriado = $oDaoCustoAproria->sql_query_file(null,
                                                            "*",
                                                            null,
                                                            "cc12_matestoqueinimei = {$iCodigoIniMei}"
                                                            );

    $rsCustoApropriado = $oDaoCustoAproria->sql_record($sSqlCustoAproriado);
    if ($oDaoCustoAproria->numrows > 0) {

      $aCustosApropriados = db_utils::getColectionByRecord($rsCustoApropriado);
      foreach ($aCustosApropriados as $oCustoApropriado) {

        $nNovaQuantidade = $oCustoApropriado->cc12_qtd - $quant_devolvida;
        $nNovoValor      = round((($nNovaQuantidade * $oCustoApropriado->cc12_valor) / $oCustoApropriado->cc12_qtd), 2);
        if ($nNovaQuantidade > 0) {

          $oDaoCustoAproria->cc12_sequencial = $oCustoApropriado->cc12_sequencial;
          $oDaoCustoAproria->cc12_qtd        = $nNovaQuantidade;
          $oDaoCustoAproria->cc12_valor      = $nNovoValor;
          $oDaoCustoAproria->alterar($oCustoApropriado->cc12_sequencial);

        } else {
          $oDaoCustoAproria->excluir(null,"cc12_matestoqueinimei = {$iCodigoIniMei}");
        }
        if ($oDaoCustoAproria->erro_status == '0') {

          $oDaoCustoAproria->erro(true, false);
          return true;

        }

      }

    }

    $oDaoMatestoquedevitem->m46_codmatestoquedev  = $codigo;
    $oDaoMatestoquedevitem->m46_codmatrequiitem   = $matrequiitem;
    $oDaoMatestoquedevitem->m46_codatendrequiitem = $atendrequiitem;
    $oDaoMatestoquedevitem->m46_codmatmater       = $codmatmater;
    $oDaoMatestoquedevitem->m46_quantdev          = $quant_devolvida;
    $oDaoMatestoquedevitem->m46_quantexistia      = $m43_quantatend;
    $oDaoMatestoquedevitem->incluir(null);
    if ($oDaoMatestoquedevitem->erro_status == '0') {

      $oDaoMatestoquedevitem->erro(true, false);
      return true;

    }

    $codigodevitem = $oDaoMatestoquedevitem->m46_codigo;
    $acaba         = false;
    $sSql          = $oDaoAtendrequiitemmei->sql_query_file(null, '*', null, "m44_codatendreqitem = $m43_codigo");
    $result_mei    = $oDaoAtendrequiitemmei->sql_record($sSql);
    $numrowsmei    = $oDaoAtendrequiitemmei->numrows;

    for ($w = 0; $w < $numrowsmei; $w++) {

      $obj2 = db_utils::fieldsmemory($result_mei, $w);

      $sSql                  = $oDaoMatestoqueitem->sql_query($obj2->m44_codmatestoqueitem);
      $result_matestoqueitem = $oDaoMatestoqueitem->sql_record($sSql);
      $obj3                  = db_utils::fieldsmemory($result_matestoqueitem, 0);

      if ($quant_devolvida >= $obj2->m44_quant) {

        if ($quant_devolvida == $obj2->m44_quant) {

          $quant_altera = abs($quant_devolvida - $obj3->m71_quantatend);
          $devolver     = $quant_devolvida;

        } else {

          $quant_altera    = $obj3->m71_quantatend - $obj2->m44_quant; // 0
          $devolver        = $obj2->m44_quant; // 20
          $quant_devolvida = $quant_devolvida - $obj2->m44_quant; // 20

        }
        $oDaoMatestoqueitem->m71_quantatend = "$quant_altera";

      } else {

        $quant_altera                       = $obj3->m71_quantatend-$quant_devolvida; // 10
        $oDaoMatestoqueitem->m71_quantatend = "$quant_altera";
        $devolver                           = $quant_devolvida; // 20
        $acaba                              = true;

      }

      $valor_uni                       = $obj3->m71_valor / $obj3->m71_quant;
      $valordev                        = $valor_uni * $devolver;
      $oDaoMatestoqueitem->m71_codlanc = $obj3->m71_codlanc;
      $oDaoMatestoqueitem->alterar($obj3->m71_codlanc);
      if ($oDaoMatestoqueitem->erro_status == '0') {

        $oDaoMatestoqueitem->erro(true, false);
        return true;

      }

      $oDaoAtendrequiitemmei->m44_quant  = "$quant_altera";
      $oDaoAtendrequiitemmei->m44_codigo = $obj2->m44_codigo;
      $oDaoAtendrequiitemmei->alterar($obj2->m44_codigo);
      if ($oDaoAtendrequiitemmei->erro_status == '0') {

        $oDaoAtendrequiitemmei->erro(true, false);
        return true;

      }

      $oDaoMatestoquedevitemmei->m47_quantdev             = $devolver;
      $oDaoMatestoquedevitemmei->m47_codmatestoqueitem    = $obj3->m71_codlanc;
      $oDaoMatestoquedevitemmei->m47_codmatestoquedevitem = $codigodevitem;
      $oDaoMatestoquedevitemmei->incluir(null);
      if ($oDaoMatestoquedevitemmei->erro_status == '0') {

        $oDaoMatestoquedevitemmei->erro(true, false);
        return true;

      }

      $oDaoMatestoqueinimei->m82_matestoqueitem = $obj3->m71_codlanc;
      $oDaoMatestoqueinimei->m82_matestoqueini  = $m82_matestoqueini;
      $oDaoMatestoqueinimei->m82_quant          = $devolver;
      $oDaoMatestoqueinimei->incluir(@$m82_codigo);
      if ($oDaoMatestoqueinimei->erro_status == '0') {

        $oDaoMatestoqueinimei->erro(true, false);
        return true;

      }
      $codigo_inimei = $oDaoMatestoqueinimei->m82_codigo;

      $oDaoMatestoqueinimeimdi->m50_codmatestoquedevitem = $codigodevitem;
      $oDaoMatestoqueinimeimdi->m50_codmatestoqueinimei  = $codigo_inimei;
      $oDaoMatestoqueinimeimdi->incluir(null);
      if ($oDaoMatestoqueinimeimdi->erro_status == '0') {

        $oDaoMatestoqueinimeimdi->erro(true, false);
        return true;

      }

      $v                          = $valordev+$obj3->m70_valor;
      $q                          = $devolver+$obj3->m70_quant;
      $oDaoMatestoque->m70_quant  = "$q";
      $oDaoMatestoque->m70_valor  = "$v";
      $oDaoMatestoque->m70_codigo = $obj3->m70_codigo;
      $oDaoMatestoque->alterar($obj3->m70_codigo);
      if ($oDaoMatestoque->erro_status == '0') {

        $oDaoMatestoque->erro(true, false);
        return true;

      }

      if ($acaba == true || $devolver == 0) {
        break;
      }

    } // fim for

  } // fim for
  /**
   * escrituramos a saida dos materiais
   */
  try {

    $oRequisicao = new RequisicaoMaterial($iCodigoRequisicao);
    foreach ($aItens as $oItem) {

      $oMaterial        = new MaterialEstoque($oItem->iCodigoMaterial);
      
      if (USE_PCASP) {
        
        $nValorPrecoMedio = $oMaterial->getPrecoMedio();
        $nValorLancamento = round($nValorPrecoMedio * $oItem->nQuantidade , 2);
        $oRequisicao->estornarLancamento($oMaterial, $m82_matestoqueini, $nValorLancamento);
      }
    }
  } catch (BusinessException $eException) {

    $erro_msg = str_replace("\n", "\\n", $eException->getMessage());
    db_msgbox($erro_msg);
    return true;
  }
  catch (Exception $eException) {

    $erro_msg = str_replace("\n", "\\n", $eException->getMessage());
    db_msgbox($erro_msg);
    return true;
  }
  return false;
}
?>
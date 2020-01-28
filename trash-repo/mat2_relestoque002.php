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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_utils.php");
require_once ("classes/db_matestoque_classe.php");
require_once ("classes/db_matestoqueitem_classe.php");
require_once ("classes/db_matmaterestoque_classe.php");
require_once "classes/materialestoque.model.php";
parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);

$clmatestoque = new cl_matestoque();
$clmatestoqueitem = new cl_matestoqueitem();
$clmatestoque->rotulo->label();
$clmatestoqueitem->rotulo->label();
$clmatmaterestoque = new cl_matmaterestoque;
$clrotulo = new rotulocampo();
$clrotulo->label('m60_descr');
$clrotulo->label('descrdepto');
$erro = 0;
$q_est = 0;
$v_est = 0;
if (isset($quebra) && $quebra == "S") {
  $ordem = 'm70_coddepto, m60_descr';
} else if ($ordem == 'a') {
  $ordem = 'm70_codmatmater';
} else if ($ordem == 'b') {
  $ordem = 'm70_coddepto, m60_descr';
} else if ($ordem == 'c') {
  $ordem = 'trim(m60_descr)';
}
$where = 'and  1=1';
$info = "";

/*
 * variavel selInstit validada e tratada por função js
 */
$txt_where = " db_depart.instit in ($selInstit)";

if ($listadepart != "") {
  if (isset($verdepart) and $verdepart == "com") {
    $txt_where = $txt_where . " and m70_coddepto in  ($listadepart)";
  } else {
    $txt_where = $txt_where . " and m70_coddepto not in  ($listadepart)";
  }
}

if ($listamat != "") {
  if (isset($vermat) and $vermat == "com") {
    $txt_where = $txt_where . " and m70_codmatmater in  ($listamat)";
  } else {
    $txt_where = $txt_where . " and m70_codmatmater not in  ($listamat)";
  }
}

if (($data != "--") && ($data1 != "--")) {

  $where = $where . " and m71_data  between '$data' and '$data1'  ";
  $data  = db_formatar($data, "d");
  $data1 = db_formatar($data1, "d");
  $info  = "De $data até $data1.";

} else if ($data != "--") {
  $where = $where . " and m71_data >= '$data'  ";
  $data = db_formatar($data, "d");
  $info = "Apartir de $data.";
} else if ($data1 != "--") {
  $where = $where . "and m71_data <= '$data1'   ";
  $data1 = db_formatar($data1, "d");
  $info = "Até $data1.";
}

/*if (isset($zera) && $zera == 'N') {
  $txt_where .= " and m70_quant<>0 ";
}*/

if ($opcao_material == "A") {

  $txt_where .= " and m60_ativo = 't' ";
  $info      .= " Materiais Ativos";

} else if ($opcao_material == "I") {

  $txt_where .= " and m60_ativo = 'f' ";
  $info      .= " Materiais Inativos";

}

$info_listar_serv = "";

if ($listar_serv == "M") {

  $txt_where           .= " and (pc01_servico is false or pc01_servico is null) ";
  $info_listar_serv    .= " Somente Materiais";

} else if ($listar_serv == "S") {

  $txt_where           .= " and pc01_servico is true ";
  $info_listar_serv    .= " Somente Serviços";

} else {
  $info_listar_serv = " Listar Todos";
}

if ($tipo == "A") {
  $info_tipo = " Análitico";
} else if ($tipo == "S") {
  $info_tipo = " Sintético";
} else {
  $info_tipo = " Conferência";
}

$head3 = "Relatório de Estoque";
$head4 = "$info_tipo";
$head5 = "$info";
$head6 = "$info_listar_serv";

$sCampos  = "distinct m70_codigo, ";
$sCampos .= "         m70_codmatmater, ";
$sCampos .= "         trim(m60_descr) as m60_descr, ";
$sCampos .= "         m61_usaquant, ";
$sCampos .= "         m61_usadec, ";
$sCampos .= "         m70_coddepto, ";
$sCampos .= "         descrdepto, ";
$sCampos .= "         m70_valor,";
$sCampos .= "         m70_quant,";
$sCampos .= "         m60_ativo";

if($listar_serv == "T") {
	$sSql = $clmatestoque->sql_query_pcmater(null, $sCampos, $ordem, "$txt_where");
} else {
	$sSql = $clmatestoque->sql_query_pcmater(null, $sCampos, $ordem, "$txt_where");
}
$iNumDec = pg_result(db_query("select coalesce(e30_numdec,2) from empparametro where e39_anousu = ".db_getsession("DB_anousu")),0,0);
$result  = $clmatestoque->sql_record($sSql);

if ($clmatestoque->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros  cadastrados.');
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);
$troca = 1;
/*
 * Variavel que acrescenta o tamanho de algumas celulas do relatório.
 * sera diminuida quando temos o relatorio emitido de
 * forma sintetica, qdo acrescentaremos a informação da localização do
 * material;
 */
$iAcrescimoCelula = 6;
$tam = 110;
if ($tipo == 'C') {

  $alt              = 10;
  $borda            = 1;
  $tam              = 80;

} else if ($tipo == "S") {

  $alt = 4;
  $borda = 0;
  $iAcrescimoCelula = 0;

} else {

  $alt = 4;
  $borda = 0;

}
$total = 0;
$p = 0;
$total_depto = 0;
$valor_depto = 0;
$quant_depto = 0;
$depto_ant = "";
$imp = 0;
$format = false;

if ($opcao_material == "T") {
  $br = 0;
} else {
  $br = 1;
}
$sLocalizacao = null;

$oDaoMatEstoqueTransferencia = db_utils::getDao("matestoquetransferencia");

for($x = 0; $x < $clmatestoque->numrows; $x ++) {

  $sLocalizacao = null;
  db_fieldsmemory($result, $x);

  if($m61_usadec == 'true'){
   $format = 'true';
  }

  $oMaterialEstoque         = new materialEstoque($m70_codmatmater);
  /**
   * Na classe MaterialEstoque, o código do departamento é setado no construtor
   * porém a classe pega o valor da sessão DB_coddepto
   */
  $oMaterialEstoque->setCodDepto($m70_coddepto);

  $nPrecoMedio              = $oMaterialEstoque->getPrecoMedio();
  $nQuantidadeTransferencia = $oMaterialEstoque->getSaldoTransferencia(true);

  if ($nQuantidadeTransferencia > 0) {

    $m70_valor += $nQuantidadeTransferencia * $nPrecoMedio;
    $m70_quant += $nQuantidadeTransferencia;
  } else {
    $m70_valor = $m70_quant * $nPrecoMedio;
  }

  if (isset($zera) && $zera == 'N' && $m70_quant <= 0) {
    continue;
  }

  $rsLocalizacao = $clmatmaterestoque->sql_record($clmatmaterestoque->sql_query(null,
                                                                                "m64_localizacao",
                                                                                null,
                                                                                "m64_matmater = {$m70_codmatmater}
                                                                                 and coddepto = {$m70_coddepto}"));


  if ($clmatmaterestoque->numrows > 0) {
    $sLocalizacao = db_utils::fieldsMemory($rsLocalizacao, 0)->m64_localizacao;
  }
  if (isset($quebra) && $quebra == "S") {
    if ($depto_ant != $m70_coddepto) {
      if ($depto_ant != "") {
        $pdf->setfont('arial', 'b', 8);
        $comp = 220;
        $bord = "T";
        $pdf->cell($comp, 6, 'TOTAL DE REGISTROS DO DEPART.:  ' . $total_depto, $bord, 0, "L", 0);
		if($m61_usadec == 'true'){
         $pdf->cell(25, 6,db_formatar($quant_depto, 'f'," ",0,'',$iNumDec), $bord, 0, "C", 0);
		}else{
         $pdf->cell(25, 6, $quant_depto, $bord, 0, "C", 0);
		}
        $pdf->cell(30, 6, db_formatar($valor_depto, 'f'), $bord, 1, "R", 0);
        $total_depto = 0;
        $valor_depto = 0;
        $quant_depto = 0;
        $imp = 1;
      }
      $depto_ant = $m70_coddepto;
    } else {
      if ($x == $num) {
      } else {
        if ($tipo == 'A') {
          $pdf->cell(275, $alt, "", "T", 1, "C", 0);
        }
      }
    }
  }
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 || $imp == 1) {
    if ($imp == 0) {
      $pdf->addpage('L');
    }
    $pdf->setfont('arial', 'b', 7);
    $pdf->cell(20, $alt, 'Codigo', 1, 0, "C", 1);
    $pdf->cell(20, $alt, 'Cod. Material', 1, 0, "C", 1);
    $pdf->cell($tam, $alt, $RLm60_descr, 1, 0, "C", 1);
    if ($tipo == "S") {
      $pdf->cell(18, $alt, "Localização", 1, 0, "C", 1);
    }
    $pdf->cell(14+$iAcrescimoCelula, $alt, 'Depart.', 1, 0, "C", 1);
    $pdf->cell(50, $alt, $RLdescrdepto, 1, 0, "C", 1);
    $pdf->cell(19+$iAcrescimoCelula, $alt, 'Quant. Est.', 1, 0, "C", 1);
    if ($tipo == 'C') {
      $pdf->cell(25, $alt, 'Contagem', 1, 0, "C", 1);
    }
    $pdf->cell(24+$iAcrescimoCelula, $alt, $RLm70_valor, 1, $br, "C", 1);
    if ($opcao_material == "T") {
      $pdf->cell(10, $alt, "Ativo", 1, 1, "C", 1);
    }
    if ($tipo == 'A') {

      $pdf->cell(20, $alt, '', 0, 0, "C", 0);
      $pdf->cell(20, $alt, 'Cod. Lanc.', 1, 0, "C", 1);
      $pdf->cell(20, $alt, 'Data', 1, 0, "C", 1);
      $pdf->cell(30, $alt, 'Lote', 1, 0, "C", 1);
      $pdf->cell(30, $alt, 'Validade', 1, 0, "C", 1);
      $pdf->cell(70, $alt, 'Fabricante', 1, 0, "C", 1);
      $pdf->cell(20, $alt, 'Valor Uni.', 1, 0, "C", 1);
      $pdf->cell(20, $alt, $RLm71_quant, 1, 0, "C", 1);
      $pdf->cell(20, $alt, 'Valor', 1, 0, "C", 1);
      $pdf->cell(25, $alt, $RLm71_quantatend, 1, 1, "C", 1);

    }
    $troca = 0;
    $imp = 0;
    $p = 0;
  }
  $result_itens = $clmatestoqueitem->sql_record($clmatestoqueitem->sql_query_lote(null, '*', null, "m71_codmatestoque=$m70_codigo $where "));
  if ($clmatestoqueitem->numrows == 0) {
    $erro ++;
  } else {
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(20, $alt,$m70_codigo, $borda, 0, "C", $p);
    $pdf->cell(20, $alt, $m70_codmatmater, $borda, 0, "C", $p);
    $pdf->cell($tam, $alt, substr($m60_descr, 0, 45), $borda, 0, "L", $p);
    if ($tipo == "S") {
      $pdf->cell(18, $alt, $sLocalizacao, $borda, 0, "C", $p);
    }
    $pdf->cell(14+$iAcrescimoCelula, $alt, $m70_coddepto, $borda, 0, "C", $p);
    $pdf->cell(50, $alt, substr($descrdepto, 0, 25), $borda, 0, "L", $p);

	if($m61_usadec == 'true'){
     $pdf->cell(19+$iAcrescimoCelula, $alt, db_formatar($m70_quant, 'f'," ",0,'',$iNumDec), $borda, 0, "C", $p);
	}else{
     $pdf->cell(19+$iAcrescimoCelula, $alt, $m70_quant, $borda, 0, "C", $p);
	}

    if ($tipo == 'C') {
      $pdf->cell(25, $alt, '', $borda, 0, "C", $p);
    }
    $pdf->cell(24+$iAcrescimoCelula, $alt, db_formatar($m70_valor, 'f'), $borda, $br, "R", $p);
    if ($opcao_material == "T") {
      if ($m60_ativo == 't') {
        $pdf->cell(10, $alt, "SIM", $borda, 1, "C", 0);
      } else {
        $pdf->cell(10, $alt, "NÃO", $borda, 1, "C", 0);
      }
    }
    $total ++;


    $q_est = $q_est + $m70_quant;
    $v_est = $v_est + $m70_valor;
    $total_depto ++;
    $valor_depto += $m70_valor;
    $quant_depto += $m70_quant;

    if ($tipo == 'A') {
      for($i = 0; $i < $clmatestoqueitem->numrows; $i ++) {
        db_fieldsmemory($result_itens, $i);
        if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {

          $pdf->addpage('L');
          $pdf->setfont('arial', 'b', 7);
          $pdf->cell(20, $alt, 'Codigo', 1, 0, "C", 1);
          $pdf->cell(20, $alt, "Cod. Material", 1, 0, "C", 1);
          $pdf->cell($tam, $alt, $RLm60_descr, 1, 0, "C", 1);
          $pdf->cell(20, $alt, 'Depart.', 1, 0, "C", 1);
          $pdf->cell(50, $alt, $RLdescrdepto, 1, 0, "C", 1);
          $pdf->cell(25, $alt, "Quant. Est.", 1, 0, "C", 1);
          if ($tipo == 'C') {
            $pdf->cell(25, $alt, '', 1, 0, "C", 1);
          }
          $pdf->cell(30, $alt, $RLm70_valor, 1, $br, "C", 1);
          if ($opcao_material == "T") {
            $pdf->cell(10, $alt, "Ativo", 1, 1, "C", 1);
          }
          if ($tipo == 'A') {

            $pdf->cell(20, $alt, '', 0, 0, "C", 0);
            $pdf->cell(20, $alt, 'Cod. Lanc.', 1, 0, "C", 1);
            $pdf->cell(20, $alt, 'Data', 1, 0, "C", 1);
            $pdf->cell(30, $alt, 'Lote', 1, 0, "C", 1);
            $pdf->cell(30, $alt, 'Validade', 1, 0, "C", 1);
            $pdf->cell(70, $alt, 'Fabricante', 1, 0, "C", 1);
            $pdf->cell(20, $alt, 'Valor Uni.', 1, 0, "C", 1);
            $pdf->cell(20, $alt, $RLm71_quant, 1, 0, "C", 1);
            $pdf->cell(20, $alt, 'Valor', 1, 0, "C", 1);
  		    $pdf->cell(25, $alt, $RLm71_quantatend, 1, 1, "C", 1);
          }
          $troca = 0;
          $p = 0;

        }
        if ($m71_quant != 0) {
          $valoruni = $m71_valor/$m71_quant;
        } else {
          $valoruni = $m71_valor;
        }

        $pdf->setfont('arial', '', 8);
        $pdf->cell(20, $alt, '', $borda, 0, "C", 0);
        $pdf->cell(20, $alt, $m71_codlanc, $borda, 0, "C", 0);
        $pdf->cell(20, $alt, db_formatar($m71_data, 'd'), $borda, 0, "C", 0);
        $pdf->cell(30, $alt, $m77_lote, $borda, 0, "C", 0);
        $pdf->cell(30, $alt, db_formatar($m77_dtvalidade, 'd'), $borda, 0, "C", 0);
        $pdf->cell(70, $alt, $m76_nome, $borda, 0, "C", 0);
        $pdf->cell(20, $alt, db_formatar($valoruni, 'f'), $borda, 0, "R", 0);
  	    if($m61_usadec == 'true'){
         $pdf->cell(20, $alt, db_formatar($m71_quant, 'f'," ",0,'',$iNumDec), $borda, 0, "C", 0);
		}else{
         $pdf->cell(20, $alt, $m71_quant, $borda, 0, "C", 0);
		}
        $pdf->cell(20, $alt, db_formatar($m71_valor, 'f'), $borda, 0, "R", 0);
  	    if($m61_usadec == 'true'){
         $pdf->cell(25, $alt, db_formatar($m71_quantatend, 'f'," ",0,'',$iNumDec), $borda, 1, "C", 0);
		}else{
         $pdf->cell(25, $alt, $m71_quantatend, $borda, 1, "C", 0);
		}
      }
    } else {
      if ($p == 0) {
        $p = 1;
      } else {
        $p = 0;
      }
    }

    $num = $clmatestoque->numrows - 1;
  }
}
if (isset($quebra) && $quebra == "S") {
  $pdf->setfont('arial', 'b', 8);
  $comp = 220;
  $bord = "T";
  $pdf->cell($comp, 6, 'TOTAL DE REGISTROS DO DEPART: ' . $total_depto, $bord, 0, "L", 0);
  if($m61_usadec == 'true'){
   $pdf->cell(25, 6, db_formatar($quant_depto, 'f'," ",0,'',$iNumDec), $bord, 0, "C", 0);
  }else{
   $pdf->cell(25, 6, $quant_depto, $bord, 0, "C", 0);
  }
  $pdf->cell(30, 6, db_formatar($valor_depto, 'f'), $bord, 1, "R", 0);
}
if ($erro == $clmatestoque->numrows) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros  cadastrados.');
}
$pdf->setfont('arial', 'b', 8);
$comp = 220;
$bord = 1;
if ($tipo == "C") {
  $pdf->setfont('arial', 'b', 9);
  $comp = 190;
  $bord = 1;
}
$pdf->cell($comp, 6, 'TOTAL DE REGISTROS :  ' . $total, $bord, 0, "L", 0);
 if($format == 'true'){
  $pdf->cell(25, 6, db_formatar($q_est, 'f'," ",0,'',$iNumDec), $bord, 0, "R", 0);
 }else{
  $pdf->cell(25, 6, db_formatar($q_est, "s", "0", strlen($q_est), "d", 0), $bord, 0, "R", 0);
 }
if ($tipo == "C") {
  $pdf->cell(25, 6, "", $bord, 0, "R", 0);
}
$pdf->cell(30, 6, db_formatar($v_est, 'f'), $bord, 0, "R", 0);
$pdf->Output();
exit();
?>
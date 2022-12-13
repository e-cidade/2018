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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once('libs/db_stdlibwebseller.php');
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$oDaoFarMater        = db_utils::getdao('far_matersaude');
$oDaoFechaLivro      = db_utils::getdao('far_fechalivro');
$iCodigoDepartamento = db_getsession("DB_coddepto");

/**
 * Emite na tela o erro.
 *
 * @param String   $sDescricao - Descrição do erro.
 *
 * @return
 */
function erro($sDescricao) {

  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b><br><br><?echo $sDescricao;?><br><br>
          <input type='button' value='Fechar' onclick='window.close()'></b>
        </font>
      </td>
    </tr>
 </table>
 <?
 exit;

}

/**
 * Calcula o Saldo inicial do produto na data informada.
 *
 * @param Object   $oDaoFarMater - DAO da classe far_matersaude.
 * @param Integer  $iCodigoDepartamento - Codigo do departamento que está logado.
 * @param String   $sData - A data da qual queremos saber o estoque.
 * @param Interger $iCodigoMaterial - Codigo do material na tabela far_matersaude.
 *
 * @return Integer - Saldo do medicamento na data informada.
 */
function getSaldoInicial ($iCodigoDepartamento, $dData, $iCodigoMaterial) {

  $oDaoFarMater = db_utils::getdao('far_matersaude');
  $sCampo       = " SUM(coalesce(case when m81_tipo = 1 and m81_entrada = 't' then round(m82_quant, 2) ";
  $sCampo      .= "     when m81_tipo = 2 and m81_entrada = 'f' then round(m82_quant, 2) * -1 end, 0)) AS saldoInicial";

  $sWhere       =  " m80_coddepto = ". $iCodigoDepartamento." and fa01_i_codmater = ".$iCodigoMaterial;
  $sWhere      .=  " and m80_data < '". $dData."'";

  $sSql         = $oDaoFarMater->sql_query_movimentacaoEstoqueSimples("", $sCampo, "", $sWhere);
  $rsSql        = $oDaoFarMater->sql_record($sSql);
  if ($oDaoFarMater->numrows > 0) {

  	$oEstoque = db_utils::fieldsmemory($rsSql, 0);
    return $oEstoque->saldoinicial;

  }
  return 0;

}

/**
 * Retorna o nome do livro conforme o código informado.
 *
 * @param Interger $iCodigo - Código do livro
 *
 * @return String - Nome do livro.
 */
function getNomeLivro($iCodigo) {

  $sLivro          = '';
  $oDaoModeloLivro = new cl_far_modelolivro();
  $sSqlModeloLivro = $oDaoModeloLivro->sql_query_file($iCodigo, 'fa16_c_livro', '', '');
  $rsModelosLivro  = db_query($sSqlModeloLivro);

  if ( pg_num_rows($rsModelosLivro) ) {
    $sLivro = db_utils::fieldsmemory($rsModelosLivro, 0)->fa16_c_livro;
  }

  return $sLivro;
}

/**
 * Converte uma data em formato normal para formato de base de dados.
 *
 * @param String $dDate - Data em formato normal('dd/mm/aaaa')
 *
 * @return String - Data formatada para consulta ('aaaa-mm-dd')
 */
function convertToDatabaseDate ($dDate) {

  $aData = explode("/", $dDate);
  return $aData[2]."-".$aData[1]."-".$aData[0];

}

/**
 * Função resposável por imprimir o cabeçalho do relatório.
 *
 * @param object $oPdf - Ponteiro do objeto da fpdf.
 * @param string $sDescriçãoMedicamento - Descrição do medicamento.
 *
 * @return void
 */
function imprimirCabecalho($oPdf, $sDescriçãoMedicamento) {

  $oPdf->addpage('L');
  $oPdf->setfillcolor(240);
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(280, 6,"$sDescriçãoMedicamento", 1, 1, "L", 1);
  $oPdf->setfont('arial','b',6);
  $oPdf->cell(30,10,"DATA",1,0,"C",0);
  $oPdf->cell(20,14,"NOTIFICAÇÃO DA ",1,0,"C",0);
  $oPdf->cell(80,10,"HISTÓRICO ",1,0,"C",0);
  $oPdf->cell(36,10,"MOVIMENTO",1,0,"C",0);
  $oPdf->cell(12,14,"ESTOQUE ",1,0,"C",0);
  $oPdf->cell(20,14,"USUÁRIO ",1,0,"C",0);
  $oPdf->cell(22,14,"ASS. DO ",1,0,"C",0);
  $oPdf->cell(60,14,"MÉDICO ",1,0,"C",0);
  //$oPdf->cell(10,14,"CRM ",1,0,"C",0);
  //$oPdf->cell(60,14,"OBSERVAÇÕES ",1,1,"C",0);
  $oPdf->setY(51);
  $oPdf->cell(10,4,"DIA",1,0,"C",0);
  $oPdf->cell(10,4,"MES",1,0,"C",0);
  $oPdf->cell(10,4,"ANO",1,0,"C",0);
  $oPdf->cell(20,4,"RECEITA ","BL",0,"C",0);
  $oPdf->cell(80,4,"",1,0,"C",0);
  $oPdf->cell(12,4,"ENTRADA",1,0,"C",0);
  $oPdf->cell(12,4,"SAÍDA",1,0,"C",0);
  $oPdf->cell(12,4,"PERDAS ",1,0,"C",0);
  $oPdf->cell(12,4," ","BL",0,"C",0);
  $oPdf->cell(20,4," ","BL",0,"C",0);
  $oPdf->cell(22,4,"RESP.TÉCNICO ","BL",0,"C",0);
  $oPdf->cell(50,4,"NOME ",1,0,"C",0);
  $oPdf->cell(10,4,"CRM ",1,0,"C",0);
  $oPdf->setY(55);

}

/**
 * Função resposável por imprimir a linha da retirada de um medicamento.
 *
 * @param object $oPdf - Ponteiro do objeto da fpdf.
 * @param string $oRetirada - Objeto com todas as informações de determinada retirada.
 * @param double $dSaldo - Saldo do medicamento antes da retirada.
 *
 * @return double - Saldo do medicamento após a retirada.
 */
function imprimirRetirada($oPdf, $oRetirada, $dSaldo) {

  $aData    = explode("-", $oRetirada->m80_data);
  $dEntrada = 0.0;
  $dSaida   = 0.0;
  $dPerda   = 0.0;
  $aDados   = Array();
  global $iNumRegPagina;

  if ($oRetirada->m81_tipo == 1) {
    //ENTRADA
    $dEntrada  = $oRetirada->m82_quant;
  	$dSaldo   += $dEntrada;

  } elseif ($oRetirada->m81_tipo == 2) {
  	//SAIDA
    if ($oRetirada->m81_codtipo == 11 || $oRetirada->m81_codtipo == 12) {
  	  $dPerda = $oRetirada->m82_quant;
    } else {
  	  $dSaida = $oRetirada->m82_quant;
    }
    $dSaldo -= $oRetirada->m82_quant;

  }

  $aDados[0] = $aData[2];
  $aDados[1] = $aData[1];
  $aDados[2] = $aData[0];
  $aDados[3] = (!empty($oRetirada->fa04_numeronotificacao))   ? $oRetirada->fa04_numeronotificacao   : '';
  if ($oRetirada->fa04_i_codigo != "") {

  	$sDescricaoRetirada  = "L: ".$oRetirada->m77_lote;
  	$sDescricaoRetirada .= " - P: ".$oRetirada->z01_v_nome;
    $dtValidade          = $oRetirada->s158_d_validade ;

    if ( $dtValidade != '') {

      $oDataValidade       = new DBDate( $oRetirada->s158_d_validade );
      $dtValidade          = $oDataValidade->getDate( DBDate::DATA_PTBR );
    }

  	$sDescricaoRetirada .= " - V: ".$dtValidade;
  	$sDescricaoRetirada .= " - R: ".$oRetirada->fa04_c_numeroreceita;

  } else  {

  	$sDescricaoRetirada  = $oRetirada->m81_descr." (".$oRetirada->m80_codigo.")\n{$oRetirada->m80_obs}";
  	$sDescricaoRetirada .= ($oRetirada->deptodestino != "" ? " PARA ".$oRetirada->deptodestino : "");

  }
  $aDados[4]  = $sDescricaoRetirada;
  $aDados[5]  = $dEntrada; //ENTRADA
  $aDados[6]  = $dSaida;  //SAIDA
  $aDados[7]  = $dPerda;  //PERDAS
  $aDados[8]  = $dSaldo;  //ESTOQUE
  $aDados[9]  = $oRetirada->login;  //USUARIO
  $aDados[10]  = ""; //ASSINATURA
  $aDados[11] = (!empty($oRetirada->z01_nome))                 ? $oRetirada->z01_nome                 : '';
  $aDados[12] = (!empty($oRetirada->sd03_i_crm))               ? $oRetirada->sd03_i_crm               : '';
  if ($oRetirada->fa04_i_codigo != "") {
    //$aDados[10] = "RETIRADA($oRetirada->fa04_i_codigo)";
  } else {
  	//$aDados[10] = $oRetirada->m80_obs;
  }
  $iLines = 0;

  for ($iConta = 0; $iConta < count($aDados); $iConta++) {

    if ($iLines <  $oPdf->NbLines($oPdf->widths[$iConta], $aDados[$iConta])) {
      $iLines = $oPdf->NbLines($oPdf->widths[$iConta], $aDados[$iConta]);
    }

  }

  $iNumRegPagina += $iLines;
  $iHeight        = $iLines * 4;

  $iAlturaRow     = $oPdf->h -32;
  $iAltura        = 4; //altura da linha
  $lBorda         = true;
  $lPreenchimento = false;
  $lNaoUsarEspaco = true;//Utilizar. Como false da erro!
  $lUsarQuebra    = true;
  $sCampoTestar   = null;
  $iLarguraFixa   = 0; //0=false, 1=true;

  $oPdf->Row_multicell($aDados,
                       $iAltura,
                       $lBorda,
                       $iHeight,
                       $lPreenchimento,
                       $lNaoUsarEspaco,
                       $lUsarQuebra,
                       $sCampoTestar,
                       $iAlturaRow,
                       $iLarguraFixa
                      );



  return $dSaldo;

}

/**
 * Função resposável por imprimir o rodapé do relatório.
 *
 * @param object $oPdf - Ponteiro do objeto da fpdf.
 *
 * @return void
 */
function imprimirRodape($oPdf) {

  $oPdf->cell(280, 5,"SIGLAS: (L)LOTE, (P) NOME DO PACIENTE, (V) DATA DE VALIDADE DA RECEITA e (R) NUMERO DA RECEITA. ",
              0, 1, "R", 0
             );

}

/**
 * Função resposável por criar string formatada com os dados do cabeçalho.
 *
 * @param string $oRetirada - Objeto com todas as informações de determinada retirada.
 *
 * @return string - String formatada com as informações do cabeçalho
 */
function getStringCabecalho($oRetirada) {

  return $oRetirada->m60_codmater." - ".$oRetirada->m60_descr;

}

$sCampos  = " m60_descr, m80_data, m80_hora, m80_codigo, m81_tipo, ";
$sCampos .= " m81_descr, fa28_c_numero, ";
$sCampos .= " m77_lote, z01_i_cgsund, z01_v_nome, s158_d_validade, fa04_c_numeroreceita, m80_obs, ";
$sCampos .= " fa04_i_codigo, login, m60_codmater, fa14_i_codigo, ";
$sCampos .= " far_retirada.fa04_numeronotificacao,cgm.z01_nome, medicos.sd03_i_crm, ";
$sGroup   = " GROUP BY ".$sCampos." m81_codtipo, deptodestino, deposito, m82_quant";
$sCampos .= " m81_codtipo, destino.descrdepto as deptodestino, db_depart.descrdepto as deposito, sum(m82_quant) as m82_quant";

$sOrdem   = " m60_descr, m80_data, m80_hora, m81_tipo ";
$sWhere   = " m80_data between '".convertToDatabaseDate($fa26_d_dataini)."' and '";
$sWhere  .= convertToDatabaseDate($fa26_d_datafim)."' ";
$sWhere  .= " and m70_coddepto       = ".$iCodigoDepartamento;
$sWhere  .= " and fa17_i_modelolivro = ".$livro;
$sWhere  .= $sGroup;

$sSql     = $oDaoFarMater->sql_query_movimentacaoEstoqueCompleta (null, $sCampos, $sOrdem, $sWhere);

$rsSql    = $oDaoFarMater->sql_record($sSql);
if ($oDaoFarMater->numrows == 0) {

  erro("Não foram encontrados registros para este modelo de livro.");

}
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(223);
$oPdf->SetWidths(array( 10,  10,  10,  20,  80,  12,  12,  12,  12,  20,  22,  50, 10));
$oPdf->SetAligns(array("C", "C", "C", "C", "L", "R", "R", "R", "C", "C", "C", "L", "C"));
$head1 = "LIVRO CONTROLADOS";
$head2 = "LIVRO:   ".getNomeLivro($livro);
$head3 = "PERIODO: ".$fa26_d_dataini. " ATÉ " .$fa26_d_datafim;
$iNumRegPagina       = 0;
$iCodigoMedicamentoAnterior = 0;
$dSaldo                     = 0.0;
for ($iI = 0; $iI < $oDaoFarMater->numrows; $iI++) {

  $oRetirada = db_utils::fieldsmemory($rsSql, $iI);
  if ($iNumRegPagina > 29 || $iCodigoMedicamentoAnterior != $oRetirada->m60_codmater || $iI == 0) {

    if ($iCodigoMedicamentoAnterior != $oRetirada->m60_codmater) {

      $dSaldo = getSaldoInicial($iCodigoDepartamento,
                                convertToDatabaseDate($fa26_d_dataini),
                                $oRetirada->m60_codmater
                               );

    }
  	imprimirRodape($oPdf);
    imprimirCabecalho($oPdf, getStringCabecalho($oRetirada));
    $iNumRegPagina = 0;

  }
  $dSaldo = imprimirRetirada($oPdf, $oRetirada, $dSaldo);
  $iCodigoMedicamentoAnterior = $oRetirada->m60_codmater;

}
imprimirRodape($oPdf);
$fa26_i_numpag = $oPdf->page;
$oPdf->Output("tmp/far4_livrocontrolado003.pdf", false, true);
db_inicio_transacao();
$oDaoFechaLivro->fa26_i_numpag  = $fa26_i_numpag;
$oidgrava                         = db_geraArquivoOidfarmacia("tmp/far4_livrocontrolado003.pdf","",1,$conn);
$oDaoFechaLivro->fa26_o_arquivo = $oidgrava;
$oDaoFechaLivro->fa26_c_nomearq = "tmp/far4_livrocontrolado003.pdf";
$oDaoFechaLivro->fa26_d_dataini = $fa26_d_dataini;
$oDaoFechaLivro->fa26_d_datafim = $fa26_d_datafim;
$oDaoFechaLivro->fa26_c_hora    = db_hora();
$oDaoFechaLivro->fa26_i_livro   = $livro;
$oDaoFechaLivro->fa26_i_login   = DB_getsession("DB_id_usuario");
$oDaoFechaLivro->fa26_d_data    = date("Y-m-d", db_getsession("DB_datausu"));
$oDaoFechaLivro->incluir(null);
db_fim_transacao();
db_redireciona("tmp/far4_livrocontrolado003.pdf");
?>
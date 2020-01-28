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

/**
 * Imprime todos os cidadões menores de 18 anos que recebem algum benefício
 * @author Andrio Costa  andrio.costa@dbseller.com.br
 * @version $Revision: 1.4 $
 */
require_once("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once ("std/DBDate.php");

$oDaoCadastroBaseMunicipal = new cl_cadastrounicobasemunicipal();

$sCampo      = " count(*) as cidadao ";
$sSqlCidadao = $oDaoCadastroBaseMunicipal->sql_query_base_cidadao(null, $sCampo);
$rsCidadao   = $oDaoCadastroBaseMunicipal->sql_record($sSqlCidadao);

$iCidadaoSemAvaliacao = db_utils::fieldsMemory($rsCidadao, 0)->cidadao;

if ($iCidadaoSemAvaliacao > 0) {
  
  $sMsg  = "Existem Avaliações Sócio Econômica para processar.<br> ";
  $sMsg .= "Acesse: Procedimentos > Cadastro Único > Processar Avaliação Sócio Econômica";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}

/**
 * Busca os cadastros únicos que atenden os requisitos do relatório
 * -> Sómente menores de 18 anos
 * -> Possuir ao menos um benefício ativo
 */
$aSituacoesValidas = array('EM PAGAMENTO', 'LIBERADO', 'CONCEDIDO');
$iDataAtual        = Date("Y-m-d", db_getsession("DB_datausu")); 

$sWhere  = "extract (year from AGE('{$iDataAtual}', ov02_datanascimento)) < 18 ";
$sWhere .= " and exists(select 1 ";
$sWhere .= "              from cidadaobeneficio ";
$sWhere .= "             where as02_nis = as08_nis ";
$sWhere .= "               and trim(as08_situacao) in ('EM PAGAMENTO', 'LIBERADO', 'CONCEDIDO') ";
$sWhere .= "           )";
$sOrder  = " to_ascii(trim(cidadao.ov02_nome)) "; 

$oDaoCidadaoCadUnico = new cl_cidadaocadastrounico();
$sSqlCidadaoCadUnico = $oDaoCidadaoCadUnico->sql_query(null, "as02_sequencial", $sOrder, $sWhere);  
$rsCidadaoCadUnico   = $oDaoCidadaoCadUnico->sql_record($sSqlCidadaoCadUnico);
$iLinhas             = $oDaoCidadaoCadUnico->numrows;

$aCadastroUnico = array();

if ($iLinhas > 0) {
  
  for ($i = 0; $i < $iLinhas; $i++) {
    
    $iCidadaoCadastroUnico = db_utils::fieldsMemory($rsCidadaoCadUnico, $i)->as02_sequencial;
    $oCadastroUnico        = new CadastroUnico($iCidadaoCadastroUnico);
    $aCadastroUnico[]      = $oCadastroUnico;
  }
} else {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros.');
}


$iHeigth       = 4;
$lPrimeiroLaco = true;
$oPdf          = new PDF("L");
$oPdf->SetMargins(8, 10);
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFont('arial', '', 7);

$head1 = "Crianças menores de 18 anos.";

$iTotalRegistros = 0;

/**
 * Imprime os dados do cadastro único
 */
foreach ($aCadastroUnico as $iPosicao => $oCadastroUnico) {

  /**
   * Buscamos a escola da avaliacao sócio economica do cidadão
   */
  $oAvaliacao = $oCadastroUnico->getAvaliacao();
  $sEscola    = $oAvaliacao->retornaValorRespostaMarcada('EscolaDoCidadaoFrequenta', 3000352);
  unset($oAvaliacao);
  
  /**
   * Buscamos os Beneficios
   */
  $aListaBeneficios = array();
  foreach ($oCadastroUnico->getBeneficios() as $oBeneficio) {
    
    if (in_array($oBeneficio->getSituacao(), $aSituacoesValidas)) {
      $aListaBeneficios[] = $oBeneficio->getTipoBeneficio();
    }
  }
  $aListaBeneficios = array_unique($aListaBeneficios);
  
  /**
   * Imprime cabeçalho
   */
  if ($oPdf->gety() > $oPdf->h - 20 || $lPrimeiroLaco ) {
  
    setHeader($oPdf, $iHeigth);
    $lPrimeiroLaco = false;
  }
  $oPdf->SetFont('arial', '', 7);
  
  /**
   * Como precisei utilizar multcell, 
   * imprimi as ultimas colunas por primeiro armazenando a posição X, Y do cursor em variáveis auxiliares 
   * para calcular a altura das celulas 
   */
  $iAlturaInicial = $oPdf->GetY();
  $oPdf->SetX(138);
  $oPdf->MultiCell(50, $iHeigth, $sEscola, "RL", "L");
  $iAlturaFinalEscola = $oPdf->GetY();
  
  $oPdf->SetY($iAlturaInicial);
  $oPdf->SetX(188);
  $oPdf->MultiCell(100, $iHeigth, implode(", ", $aListaBeneficios), "RL", "L");
  $iAlturaFinalBeneficio = $oPdf->GetY();
  
  
  $iAlturaFinal = $iAlturaFinalEscola;
  if ($iAlturaFinalEscola < $iAlturaFinalBeneficio) {
    $iAlturaFinal = $iAlturaFinalBeneficio;
  }
  $oPdf->Line(138, $iAlturaFinal, 288, $iAlturaFinal);   // linha horizontal
  $oPdf->Line(288, $iAlturaInicial, 288, $iAlturaFinal); // linha vertical
  
  /**
   * Retorno o eixo X para margem inicial
   */
  $oPdf->SetX(8);
  $oPdf->SetY($iAlturaInicial);
  $oPdf->Cell(25,  ($iAlturaFinal - $iAlturaInicial), $oCadastroUnico->getNis(),            1, 0, "C");
  $oPdf->Cell(70,  ($iAlturaFinal - $iAlturaInicial), $oCadastroUnico->getNome(),           1, 0, "L");
  $oPdf->Cell(10,  ($iAlturaFinal - $iAlturaInicial), $oCadastroUnico->getSexo(),           1, 0, "C");
  $oPdf->Cell(25,  ($iAlturaFinal - $iAlturaInicial), $oCadastroUnico->getDataNascimento(), 1, 0, "C");
  $oPdf->Ln();
  
  unset($aListaBeneficios);
  unset($aCadastroUnico[$iPosicao]);
  $iTotalRegistros++;
}

/**
 * Imprime o totalizador
 */
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(240, $iHeigth, "Total de Registros:", "TBR",  0, "R");
$oPdf->Cell(40,  $iHeigth, $iTotalRegistros,      "LTB",  1);


/**
 * Criamos o cabeçalho do relatorio
 * @param FPDF $oPdf
 * @param integer $iHeigth
 */
function setHeader($oPdf, $iHeigth) {

  $oPdf->setfillcolor(235);
  $oPdf->AddPage("L");
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(25,  $iHeigth, "NIS",             1, 0, "C", 1);
  $oPdf->Cell(70,  $iHeigth, "Nome",            1, 0, "C", 1);
  $oPdf->Cell(10,  $iHeigth, "Sexo",            1, 0, "C", 1);
  $oPdf->Cell(25,  $iHeigth, "Data Nascimento", 1, 0, "C", 1);
  $oPdf->Cell(50,  $iHeigth, "Escola",          1, 0, "C", 1);
  $oPdf->Cell(100, $iHeigth, "Benefícios",      1, 1, "C", 1);

}
$oPdf->Output();
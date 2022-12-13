<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
 * Model para processamento da DIRF
 *
 * @package Pessoal
 * @subpackage Dirf
 */
class Dirf2012 extends Dirf {


  /**
   * Array com as ordens de pagamentos inconsistentes
   * @var array
   * @access private
   */
  private $aOrdensInconsistentes = array();

  /**
   * Construtor da classe
   *
   * @param integer $iAno
   * @param string $sCnpj
   * @return void
   */
  public function __construct($iAno,  $sCnpj, $sCodigoArquivo = 198) {

    parent::__construct($iAno, $sCnpj);

    $this->setCodigoLayout($sCodigoArquivo);
  }

  /**
   * Adiciona uma ordem de pagamento como inconsistente
   *
   * @param stdClass $oOrdemPagamento - Objeto com os dados da ordem de pagamento
   * @access private
   * @return void
   */
  private function addInconsistenciasOrdemDePagamento($oOrdemPagamento) {

    $oInconsistente                  = new stdClass();
    $oInconsistente->iOrdemPagamento = $oOrdemPagamento->c80_codord;
    $oInconsistente->nValorPago      = $oOrdemPagamento->valor_pago;
    $oInconsistente->nValorEstornado = $oOrdemPagamento->valor_estornado;

    $this->aOrdensInconsistentes[]   = $oInconsistente;
  }

  /**
   * Gera arquivo de inconsistências
   * Chama o método da classe Dirf para os cgm's que não estão cadastrados certo
   * Após gera um arquivo com as ordens de pagamento inconsistentes
   *
   * @access public
   * @return void
   */
  public function geraArquivoInconsistencias() {

    /**
     * Array com arquivos de inconsistencias gerados
     */
    $aArquivos   = array();
    $aArquivos[] = parent::geraArquivoInconsistencias();

    global $head2;

    $sNomeArquivo = "tmp/inconsistencias_dirf_op".date('Ymdi').".pdf";

    $oPdf = new PDF();
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->SetFillColor(235);

    $iFonte    = 8;
    $iAlt      = 5;
    $iPreenche = 1;

    $head2 = "Relatório de Inconsistências";

    $oPdf->AddPage();
    $oPdf->SetFont('Arial','b',$iFonte);
    $oPdf->Cell(20, $iAlt, "O. P."          , 1, 0, 'C', 1);
    $oPdf->Cell(70, $iAlt, "Valor Pago"     , 1, 0, 'C', 1);
    $oPdf->Cell(70, $iAlt, "Valor Estornado", 1, 1, 'C', 1);

    foreach ($this->aOrdensInconsistentes as $oInconsistencia ) {

      if ($oPdf->gety() > $oPdf->h - 30) {

        $oPdf->AddPage();
        $oPdf->SetFont('Arial','b',$iFonte);
        $oPdf->Cell(20, $iAlt, "O. P."          , 1, 0, 'C', 1);
        $oPdf->Cell(70, $iAlt, "Valor Pago"     , 1, 0, 'C', 1);
        $oPdf->Cell(70, $iAlt, "Valor Estornado", 1, 1, 'C', 1);
      }

      if ($iPreenche == 1 ) {
        $iPreenche = 0;
      } else {
        $iPreenche = 1;
      }

      $oPdf->SetFont('Arial','',$iFonte);
      $oPdf->Cell(20, $iAlt, $oInconsistencia->iOrdemPagamento, 0, 0, 'C', $iPreenche);
      $oPdf->Cell(70, $iAlt, $oInconsistencia->nValorPago     , 0, 0, 'L', $iPreenche);
      $oPdf->Cell(70, $iAlt, $oInconsistencia->nValorEstornado, 0, 1, 'L', $iPreenche);
    }

    ob_start();
    $oPdf->Output($sNomeArquivo);
    ob_end_clean();

    $aArquivos[] = $sNomeArquivo;
    return $aArquivos;
  }

  /**
   * Processa dados da contablidade
   *
   * @access public
   * @return void
   */
  public function processarDadosContabilidade() {

    $sSqlDadosContabilidade  = "SELECT z01_numcgm, ";
    $sSqlDadosContabilidade .= "       trim(z01_cgccpf) as z01_cgccpf, ";
    $sSqlDadosContabilidade .= "       trim(z01_nome)   as z01_nome,   ";
    $sSqlDadosContabilidade .= "       coalesce(sum(case when c53_tipo = 30 then c70_valor else 0 end),0) as valor_pago, ";
    $sSqlDadosContabilidade .= "       coalesce(sum(case when c53_tipo = 31 then c70_valor else 0 end),0) as valor_estornado, ";
    $sSqlDadosContabilidade .= "       extract(month from c70_data) as mes, ";
    $sSqlDadosContabilidade .= "      case when (select e30_codigo";
    $sSqlDadosContabilidade .= "         from retencaopagordem";
    $sSqlDadosContabilidade .= "              inner join retencaoreceitas on e23_retencaopagordem = e20_sequencial";
    $sSqlDadosContabilidade .= "                                         and e23_recolhido is true";
    $sSqlDadosContabilidade .= "              inner join retencaotiporec  on e23_retencaotiporec = e21_sequencial";
    $sSqlDadosContabilidade .= "                                         and e21_retencaotipocalc in(1,2)";
    $sSqlDadosContabilidade .= "                                         and e21_retencaotiporecgrupo = 1";
    $sSqlDadosContabilidade .= "              inner  join  retencaonaturezatiporec on e31_retencaotiporec = e21_sequencial";
    $sSqlDadosContabilidade .= "              inner  join  retencaonatureza        on e31_retencaonatureza = e30_sequencial";
    $sSqlDadosContabilidade .= "                                                 and e31_retencaonatureza is not null";
    $sSqlDadosContabilidade .= "        where e20_pagordem = c80_codord limit 1";
    $sSqlDadosContabilidade .= "        )";
    $sSqlDadosContabilidade .= "        is not null then";
    $sSqlDadosContabilidade .= "        (select e30_codigo";
    $sSqlDadosContabilidade .= "         from retencaopagordem";
    $sSqlDadosContabilidade .= "              inner join retencaoreceitas on e23_retencaopagordem = e20_sequencial";
    $sSqlDadosContabilidade .= "                                         and e23_recolhido is true";
    $sSqlDadosContabilidade .= "              inner join retencaotiporec  on e23_retencaotiporec = e21_sequencial";
    $sSqlDadosContabilidade .= "                                         and e21_retencaotipocalc in(1,2)";
    $sSqlDadosContabilidade .= "                                         and e21_retencaotiporecgrupo = 1";
    $sSqlDadosContabilidade .= "              inner  join  retencaonaturezatiporec on e31_retencaotiporec = e21_sequencial";
    $sSqlDadosContabilidade .= "              inner  join  retencaonatureza        on e31_retencaonatureza = e30_sequencial";
    $sSqlDadosContabilidade .= "                                                 and e31_retencaonatureza is not null";
    $sSqlDadosContabilidade .= "        where e20_pagordem = c80_codord limit 1)";
    $sSqlDadosContabilidade .= "        else";
    $sSqlDadosContabilidade .= "           case when length(z01_cgccpf) = 14 then '1708' else '0588' end end as tipo,";
    $sSqlDadosContabilidade .= "       c80_codord ";
    $sSqlDadosContabilidade .= "  from conlancam ";
    $sSqlDadosContabilidade .= "       inner join conlancamdoc on c70_codlan = c71_codlan ";
    $sSqlDadosContabilidade .= "       inner join conlancamord on c70_codlan = c80_codlan ";
    $sSqlDadosContabilidade .= "       inner join conlancamemp on c75_codlan = c70_Codlan ";
    $sSqlDadosContabilidade .= "       inner join empempenho on e60_numemp = c75_numemp ";
    $sSqlDadosContabilidade .= "       inner join cgm on z01_numcgm = e60_numcgm ";
    $sSqlDadosContabilidade .= "       inner join conhistdoc on c71_coddoc = c53_coddoc ";
    $sSqlDadosContabilidade .= "       inner join orcdotacao       on e60_coddot           = o58_coddot  ";
    $sSqlDadosContabilidade .= "                                   and e60_anousu           = o58_anousu  ";
    $sSqlDadosContabilidade .= "       inner join orcunidade       on o41_unidade          = o58_unidade  ";
    $sSqlDadosContabilidade .= "                                   and o41_anousu           = o58_anousu  ";
    $sSqlDadosContabilidade .= "                                   and o41_orgao            = o58_orgao  ";
    $sSqlDadosContabilidade .= " where c70_data between '{$this->iAno}-01-01' and '{$this->iAno}-12-31'";
    $sSqlDadosContabilidade .= "   and o41_cnpj = '{$this->sCnpj}'";
    $sSqlDadosContabilidade .= "   and c53_tipo in (30,31)";
    $sSqlDadosContabilidade .= "   and not exists (select 1 ";
    $sSqlDadosContabilidade .= "                     from rhpessoal ";
    $sSqlDadosContabilidade .= "                          join rhpessoalmov on rh02_regist = rh01_regist ";
    $sSqlDadosContabilidade .= "                                           and rh02_anousu = {$this->iAno} ";
    $sSqlDadosContabilidade .= "                                           and rh02_mesusu = {$this->iMes} ";
    $sSqlDadosContabilidade .= "                                           and rh02_instit = ".db_getsession("DB_instit")." ";
    $sSqlDadosContabilidade .= "                          left join rhpesrescisao on rh05_seqpes = rh02_seqpes ";
    $sSqlDadosContabilidade .= "                    where rh01_numcgm = z01_numcgm ";
    $sSqlDadosContabilidade .= "                      and rh05_seqpes is null) ";
    $sSqlDadosContabilidade .= "   and e60_instit = ".db_getsession("DB_instit");

    /**
     * Essa cláusula vai retirar da contabilidade o desdobramento da
     * folha de pagamento que é o número 33190.
     *
     * EX.: Com esta condição não exibirá os empenhos que foram gerados na folha, na contabilidade.
     */
    $sSqlDadosContabilidade .= "   and o58_codele not in (select distinct o56_codele from orcelemento where o56_elemento like '33190%') ";

    $sSqlDadosContabilidade .= " group by z01_numcgm,z01_cgccpf,z01_nome,6,7,c80_codord";
    $sSqlDadosContabilidade .= " order by z01_numcgm, 6, c80_codord   ";

    $rsDadosContabilidade = db_query($sSqlDadosContabilidade);
    //$fp = fopen('/tmp/ordensErradas_dirf.txt','w');
    if ( !$rsDadosContabilidade ) {
      throw new Exception("Erro ao buscar informações na contabilidade.\n\n" . pg_last_error());
    }

    $aDadosDirf       = array();
    $aOrdensIndex     = array();

    /**
     * processa as anulações de empenho reduzindo os valores de pagamentos anteriores.
     */
    $iNumRowsPag = pg_num_rows($rsDadosContabilidade);
    for ($iPag = 0; $iPag < $iNumRowsPag; $iPag++) {

      $oPagamento = db_utils::fieldsMemory($rsDadosContabilidade, $iPag);

      if ($oPagamento->tipo == 0) {
        $oPagamento->tipo = '1708';
      }

      if ($oPagamento->valor_pago >= $oPagamento->valor_estornado) {

        $oPagamento->valor_pago     -= $oPagamento->valor_estornado;
        $oPagamento->valor_estornado = 0;

      } else {

        $nDeducao = $oPagamento->valor_pago;
        $oPagamento->valor_pago = 0;
        $oPagamento->valor_estornado -= $nDeducao;
        while ($oPagamento->valor_estornado > 0) {

          $iNumRowsPag2 = pg_num_rows($rsDadosContabilidade);
          for ($iPag2 = 0; $iPag2 < $iNumRowsPag2; $iPag2++) {

            $oPagamento2 = db_utils::fieldsMemory($rsDadosContabilidade, $iPag2);

            if ($oPagamento->c80_codord == $oPagamento2->c80_codord && $oPagamento->mes >= $oPagamento2->mes) {

              if ($oPagamento2->valor_pago >= $oPagamento->valor_estornado) {

                $oPagamento2->valor_pago     -= $oPagamento->valor_estornado;
                $oPagamento->valor_estornado  = 0;

              } else {

                $nDeducao2 = $oPagamento2->valor_pago;
                $oPagamento2->valor_pago      = 0;
                $oPagamento->valor_estornado -= $nDeducao;
              }

            }
            unset($oPagamento2);
            if ($oPagamento->valor_estornado > 0) {

              $this->addInconsistenciasOrdemDePagamento($oPagamento);
              //$texto = "a ordem $oPagamento->c80_codord está inconsistente.\n";
              //fputs($fp, $texto);
              $oPagamento->valor_estornado  = 0;
              break;
            }

          }

        }

      }
      unset($oPagamento);
    }

    $aDadosDirf    = array();

    $iNumRowsPag3 = pg_num_rows($rsDadosContabilidade);
    for ($iPag3 = 0; $iPag3 < $iNumRowsPag3; $iPag3++) {

        $oContribuinte = db_utils::fieldsMemory($rsDadosContabilidade, $iPag3);

      if ($oContribuinte->valor_pago == 0) {
        continue;
      }

      if (!isset($aDadosDirf[$oContribuinte->z01_numcgm])) {

        $oDeclaracaoDirf  = new stdClass();
        $oDeclaracaoDirf->cnpj         = $oContribuinte->z01_cgccpf;
        $oDeclaracaoDirf->nome         = $oContribuinte->z01_nome;
        $oDeclaracaoDirf->valores      = array();
        $oDeclaracaoDirf->retencaomes  = array();
        $aDadosDirf[$oContribuinte->z01_numcgm] = $oDeclaracaoDirf;
      }

      /**
       * Agrupamos os valores por tipo .
       * 1 - Valores de base de calculo (o valor pago total)
       */
      $oValorMesBase           = new stdClass();
      $oValorMesBase->valor    = $oContribuinte->valor_pago;
      $oValorMesBase->mes      = $oContribuinte->mes;
      $oValorMesBase->retencao = $oContribuinte->tipo;

      /**
       * total do valor retido para o mes.
       * calculamos apenas se o valor retido no mes ainda nao foi calculado
       */
      if (!in_array($oContribuinte->mes, $aDadosDirf[$oContribuinte->z01_numcgm]->retencaomes)) {

        $sSqlDadosIRRF  = " SELECT coalesce(sum(e23_valorretencao), 0) as retido ";
        $sSqlDadosIRRF .= "  from retencaotiporec ";
        $sSqlDadosIRRF .= "       inner join retencaoreceitas         on e21_sequencial  = e23_retencaotiporec  ";
        $sSqlDadosIRRF .= "       inner join retencaocorgrupocorrente on e23_sequencial  = e47_retencaoreceita  ";
        $sSqlDadosIRRF .= "       inner join corgrupocorrente         on k105_sequencial = e47_corgrupocorrente ";
        $sSqlDadosIRRF .= "       inner join retencaopagordem         on e20_sequencial  = e23_retencaopagordem ";
        $sSqlDadosIRRF .= "       inner join pagordem                 on e50_codord      = e20_pagordem ";
        $sSqlDadosIRRF .= "       inner join empempenho               on e50_numemp      = e60_numemp ";
        $sSqlDadosIRRF .= "       inner join orcdotacao               on e60_coddot      = o58_coddot ";
        $sSqlDadosIRRF .= "                                          and o58_anousu      = e60_anousu ";
        $sSqlDadosIRRF .= "       inner join orcunidade               on o41_unidade     = o58_unidade ";
        $sSqlDadosIRRF .= "                                          and o58_orgao       = o41_orgao   ";
        $sSqlDadosIRRF .= "                                          and o41_anousu = o58_anousu       ";
        $sSqlDadosIRRF .= "  where e21_retencaotiporecgrupo = 1  ";
        $sSqlDadosIRRF .= "    and e23_recolhido is true         ";
        $sSqlDadosIRRF .= "    and e23_ativo     is true         ";
        $sSqlDadosIRRF .= "    and e21_retencaotipocalc in(1, 2) ";
        $sSqlDadosIRRF .= "    and o41_cnpj = '{$this->sCnpj}'   ";
        $sSqlDadosIRRF .= "    and extract(month from k105_data) = {$oContribuinte->mes} ";
        $sSqlDadosIRRF .= "    and e60_numcgm                    = {$oContribuinte->z01_numcgm} ";
        $sSqlDadosIRRF .= "    and extract(year from k105_data)  = {$this->iAno} ";
        $sSqlDadosIRRF .= "    and e60_instit = ".db_getsession("DB_instit");

        $rsDadosIRRF    = db_query($sSqlDadosIRRF);

        /**
         * calculamos o valor total de inss para o mes, do cgm.
         */
        $sSqlDadosInss  = " SELECT coalesce(sum(e23_valorretencao), 0) as retido ";
        $sSqlDadosInss .= "  from retencaotiporec ";
        $sSqlDadosInss .= "       inner join retencaoreceitas         on e21_sequencial  = e23_retencaotiporec  ";
        $sSqlDadosInss .= "       inner join retencaocorgrupocorrente on e23_sequencial  = e47_retencaoreceita  ";
        $sSqlDadosInss .= "       inner join corgrupocorrente         on k105_sequencial = e47_corgrupocorrente ";
        $sSqlDadosInss .= "       inner join retencaopagordem         on e20_sequencial  = e23_retencaopagordem ";
        $sSqlDadosInss .= "       inner join pagordem                 on e50_codord      = e20_pagordem ";
        $sSqlDadosInss .= "       inner join empempenho               on e50_numemp      = e60_numemp ";
        $sSqlDadosInss .= "       inner join orcdotacao               on e60_coddot      = o58_coddot ";
        $sSqlDadosInss .= "                                          and o58_anousu      = e60_anousu ";
        $sSqlDadosInss .= "       inner join orcunidade               on o41_unidade     = o58_unidade ";
        $sSqlDadosInss .= "                                          and o58_orgao       = o41_orgao   ";
        $sSqlDadosInss .= "                                          and o41_anousu = o58_anousu       ";
        $sSqlDadosInss .= "  where e21_retencaotiporecgrupo = 1  ";
        $sSqlDadosInss .= "    and e23_recolhido is true         ";
        $sSqlDadosInss .= "    and e23_ativo     is true         ";
        $sSqlDadosInss .= "    and e21_retencaotipocalc in(3, 7) ";
        $sSqlDadosInss .= "    and o41_cnpj = '{$this->sCnpj}'";
        $sSqlDadosInss .= "    and extract(month from k105_data) = {$oContribuinte->mes} ";
        $sSqlDadosInss .= "    and e60_numcgm                    = {$oContribuinte->z01_numcgm} ";
        $sSqlDadosInss .= "    and extract(year from k105_data)  = {$this->iAno} ";
        $sSqlDadosInss .= "    and e60_instit = ".db_getsession("DB_instit");

        $rsDadosInss = db_query($sSqlDadosInss);
        $nValorInss  = db_utils::fieldsMemory($rsDadosInss, 0)->retido;

        if ($nValorInss > 0) {

          $oValorMesPrevidencia           = new stdClass();
          $oValorMesPrevidencia->valor    = $nValorInss;
          $oValorMesPrevidencia->mes      = $oContribuinte->mes;
          $oValorMesPrevidencia->retencao = $oContribuinte->tipo;
          $aDadosDirf[$oContribuinte->z01_numcgm]->valores[2][] = $oValorMesPrevidencia;
        }

        $nValorRetido = db_utils::fieldsMemory($rsDadosIRRF, 0)->retido;

        if ($nValorRetido > 0) {

          $oValorMesRetido           = new stdClass();
          $oValorMesRetido->valor    = $nValorRetido;
          $oValorMesRetido->mes      = $oContribuinte->mes;
          $oValorMesRetido->retencao = $oContribuinte->tipo;
          $aDadosDirf[$oContribuinte->z01_numcgm]->valores[6][] = $oValorMesRetido;
        }

      }

      $aDadosDirf[$oContribuinte->z01_numcgm]->valores[1][]  = $oValorMesBase;
      $aDadosDirf[$oContribuinte->z01_numcgm]->retencaomes[] = $oContribuinte->mes;
      unset($oContribuinte);
    }

    /**
     * realizamos a inclusão conforme do tipo.
     */
    foreach ($aDadosDirf as $iNumCgm => $oDirf) {

      if ( trim($oDirf->cnpj) == "" ) {

        $this->addInconsistente($iNumCgm,$oDirf->nome,'CPF Inválido');
        continue;
      }

      $oDaoRhDirfGeracaoPessoal               = db_utils::getDao("rhdirfgeracaodadospessoal");
      $oDaoRhDirfGeracaoPessoal->rh96_cpfcnpj = $oDirf->cnpj;
      $oDaoRhDirfGeracaoPessoal->rh96_numcgm  = $iNumCgm;
      $oDaoRhDirfGeracaoPessoal->rh96_regist  = '0';
      $oDaoRhDirfGeracaoPessoal->rh96_tipo    = 2;
      $oDaoRhDirfGeracaoPessoal->rh96_rhdirfgeracao = $this->iCodigoDirf;
      $oDaoRhDirfGeracaoPessoal->incluir(null);

      if ($oDaoRhDirfGeracaoPessoal->erro_status == 0) {

        $sMsg  = "Erro[10] -  Erro ao incluir valores(CGM: {$iNumCgm} com CPF/CNPJ Inválido) da DIRF.\n";
        $sMsg .= "{$oDaoRhDirfGeracaoPessoal->erro_msg}";
        throw new Exception($sMsg);
      }

      $oDirf->codigodirf = $oDaoRhDirfGeracaoPessoal->rh96_sequencial;
      $oDaoRhDirfGeracaoPessoalValor  = db_utils::getDao("rhdirfgeracaodadospessoalvalor");

      foreach ($oDirf->valores as $iTipo  => $aValor) {

        foreach ($aValor as $oValor) {

          $oDaoRhDirfGeracaoPessoalValor->rh98_mes                       = $oValor->mes;
          $oDaoRhDirfGeracaoPessoalValor->rh98_rhdirftipovalor           = $iTipo;
          $oDaoRhDirfGeracaoPessoalValor->rh98_tipoirrf                  = "$oValor->retencao";
          $oDaoRhDirfGeracaoPessoalValor->rh98_rhdirfgeracaodadospessoal = $oDirf->codigodirf;
          $oDaoRhDirfGeracaoPessoalValor->rh98_instit                    = db_getsession("DB_instit");
          $oDaoRhDirfGeracaoPessoalValor->rh98_valor                     = "{$oValor->valor}";
          $oDaoRhDirfGeracaoPessoalValor->incluir(null);

          if ($oDaoRhDirfGeracaoPessoalValor->erro_status == 0) {

            $sMsg  = "Erro[11] - Erro ao incluir valores bases da DIRF para .\n";
            $sMsg .= $oDaoRhDirfGeracaoPessoalValor->erro_msg;
            throw new Exception($sMsg);
          }
        }
      }
    }

    $this->processarPagamentosSemRetencao();
  }

  public function gerarArquivo($oDados, $lGerarContabil=true) {

    /**
     * @TODO Modificar para injeção de dependencia
     */
    $oGeracaoArquivo = new GeracaoArquivoTXTDirf($lGerarContabil, $oDados->aMatriculaSelecionadas, $oDados->sAcima6000);
    $oGeracaoArquivo->setAno($oDados->iAno);
    $oGeracaoArquivo->setValor($oDados->iValor);
    $oGeracaoArquivo->setCodigoArquivo($oDados->sCodigoArquivo);
    $oGeracaoArquivo->setTipoDeclaracao($oDados->TipoDeclaracao);
    $oGeracaoArquivo->setNumeroRecibo($oDados->iNumeroRecibo);
    $oGeracaoArquivo->setNomeResponsavel($oDados->sNomeResponsavel);
    $oGeracaoArquivo->setCpfResponsavelCNPJ($oDados->sCpfResponsavelCNPJ);
    $oGeracaoArquivo->setDDDResponsavel($oDados->sDDDResponsavel);
    $oGeracaoArquivo->setFoneResponsavel($oDados->sFoneResponsavel);
    $oGeracaoArquivo->setCpfResponsavel($oDados->sCpfResponsavel);
    $oGeracaoArquivo->setCcgmSaude($oDados->iCcgmSaude);
    $oGeracaoArquivo->setNumeroANS($oDados->iNumeroANS);
    $oGeracaoArquivo->setCcgmSaude2($oDados->iCcgmSaude2);
    $oGeracaoArquivo->setNumeroANS2($oDados->iNumeroANS2);
    $oGeracaoArquivo->setProcessaEmpenho($oDados->lProcessaEmpenho);
    $oGeracaoArquivo->setCnpj($oDados->sCnpj);
    $oGeracaoArquivo->setDirf($this);
    return $oGeracaoArquivo->processar();
  }

  /**
   * Busca pagamentos sem retencao e com valor >= 6000
   *
   * @access public
   * @return Array
   */
  public function processarPagamentosSemRetencao() {

    $sDesdobramentos = null;
    $aPagamentos     = array();

    $aDesdobramentos = $this->getDesdobramentos();

    if ( empty($aDesdobramentos) ) {
      return false;
    }

    $sDesdobramentos = implode(', ', $aDesdobramentos);

    /**
     * Pagamentos por desdobramento, que não tenha retencao e maior que 6000 por mes
     */
    $sSqlDesdobramento  = " select z01_cgccpf,                                                                            ";
    $sSqlDesdobramento .= "        z01_nome,                                                                              ";
    $sSqlDesdobramento .= "        z01_numcgm,                                                                            ";
    $sSqlDesdobramento .= "        mes,                                                                                   ";
    $sSqlDesdobramento .= "        sum(valor_pago)      as valor_pago,                                                    ";
    $sSqlDesdobramento .= "        sum(valor_estornado) as valor_estornado                                                ";
    $sSqlDesdobramento .= "                                                                                               ";
    $sSqlDesdobramento .= "   from (                                                                                      ";
    $sSqlDesdobramento .= "                                                                                               ";
    $sSqlDesdobramento .= "  select                                                                                       ";
    $sSqlDesdobramento .= "                                                                                               ";
    $sSqlDesdobramento .= "        z01_numcgm,                                                                            ";
    $sSqlDesdobramento .= "        trim(z01_cgccpf) as z01_cgccpf,                                                        ";
    $sSqlDesdobramento .= "        trim(z01_nome)   as z01_nome,                                                          ";
    $sSqlDesdobramento .= "        coalesce(sum(case when c53_tipo = 30 then c69_valor else 0 end),0) as valor_pago,      ";
    $sSqlDesdobramento .= "        coalesce(sum(case when c53_tipo = 31 then c69_valor else 0 end),0) as valor_estornado, ";
    $sSqlDesdobramento .= "        extract(month from c69_data) as mes,                                                   ";
    $sSqlDesdobramento .= "        c53_tipo                                                                               ";
    $sSqlDesdobramento .= "                                                                                               ";
    $sSqlDesdobramento .= "    from conlancamval                                                                          ";

    /**
     * documento
     */
    $sSqlDesdobramento .= "         inner join conlancamdoc on c69_codlan = c71_codlan                            ";
    $sSqlDesdobramento .= "         inner join conhistdoc   on c71_coddoc = c53_coddoc                            ";

    /**
     * Ordem de compra
     */
    $sSqlDesdobramento .= "         inner join conlancamord on c69_codlan = c80_codlan                            ";

    /**
     * inner join para saber elemento do conta
     */
    $sSqlDesdobramento .= "         inner join conlancamemp on c75_codlan = c69_codlan                            ";
    $sSqlDesdobramento .= "         inner join empelemento on e64_numemp = c75_numemp                             ";
    $sSqlDesdobramento .= "         inner join empempenho  on empempenho.e60_numemp = empelemento.e64_numemp      ";
    $sSqlDesdobramento .= "         inner join orcelemento on orcelemento.o56_codele = empelemento.e64_codele     ";
    $sSqlDesdobramento .= "                              and orcelemento.o56_anousu = empempenho.e60_anousu       ";

    $sSqlDesdobramento .= "         inner join cgm         on cgm.z01_numcgm = empempenho.e60_numcgm              ";
    $sSqlDesdobramento .= "         inner join db_config   on db_config.codigo = empempenho.e60_instit            ";
    $sSqlDesdobramento .= "         inner join emptipo     on emptipo.e41_codtipo = empempenho.e60_codtipo        ";

    /**
     * procura cnpj da unidade pelo cnpj passado no formulario, da instituicao
     */
    $sSqlDesdobramento .= "         inner join orcdotacao  on orcdotacao.o58_anousu = empempenho.e60_anousu       ";
    $sSqlDesdobramento .= "                              and orcdotacao.o58_coddot = empempenho.e60_coddot        ";
    $sSqlDesdobramento .= "         inner join orcunidade  on o41_unidade = o58_unidade                           ";
    $sSqlDesdobramento .= "                        and o41_anousu  = o58_anousu                                   ";
    $sSqlDesdobramento .= "                        and o41_orgao   = o58_orgao                                    ";

    /**
     * Lancamentos do ano escolhido no formulario
     */
    $sSqlDesdobramento .= " where c69_data between '{$this->iAno}-01-01' and '{$this->iAno}-12-31' ";

    /**
     * Busca elemento de pagamento do desdobramento informado no formulario
     */
    $sSqlDesdobramento .= "  and e64_codele in (                                          ";
    $sSqlDesdobramento .= "    select c61_codcon                                          ";
    $sSqlDesdobramento .= "      from conlancamval                                        ";
    $sSqlDesdobramento .= "           inner join conlancamdoc on c69_codlan = c71_codlan  ";
    $sSqlDesdobramento .= "           inner join conplanoreduz on c69_debito = c61_reduz  ";
    $sSqlDesdobramento .= "     where c69_data between '2012-01-01' and '2012-12-31'      ";
    $sSqlDesdobramento .= "       and c69_debito in ({$sDesdobramentos})                  ";
    $sSqlDesdobramento .= "       and conlancamdoc.c71_coddoc = 3                         ";
    $sSqlDesdobramento .= "  )                                                            ";

    /**
     * Pagamentos
     * Busca todas as contas de credito dos lancamentos de liquidacao, coddoc = 3
     * pela conta de debito dos lancamentos
     */
    $sSqlDesdobramento .= "  and (                                                        ";
    $sSqlDesdobramento .= "    c69_debito in(                                             ";
    $sSqlDesdobramento .= "    select distinct conlancamval.c69_credito                   ";
    $sSqlDesdobramento .= "      from conlancamval                                        ";
    $sSqlDesdobramento .= "           inner join conlancamdoc on c69_codlan = c71_codlan  ";
    $sSqlDesdobramento .= "     where c69_data between '2012-01-01' and '2012-12-31'      ";
    $sSqlDesdobramento .= "       and c69_debito in ({$sDesdobramentos})                  ";
    $sSqlDesdobramento .= "       and conlancamdoc.c71_coddoc = 3                         ";
    $sSqlDesdobramento .= "    )                                                          ";

    /**
     * Estorno
     * Busca todas as contas de credito dos lancamentos de liquidacao, coddoc = 3
     * pela conta de debito dos lancamentos
     */
    $sSqlDesdobramento .= "   or c69_credito in(                                          ";
    $sSqlDesdobramento .= "    select distinct conlancamval.c69_credito                   ";
    $sSqlDesdobramento .= "      from conlancamval                                        ";
    $sSqlDesdobramento .= "           inner join conlancamdoc on c69_codlan = c71_codlan  ";
    $sSqlDesdobramento .= "     where c69_data between '2012-01-01' and '2012-12-31'      ";
    $sSqlDesdobramento .= "       and c69_debito in ({$sDesdobramentos})                  ";
    $sSqlDesdobramento .= "       and conlancamdoc.c71_coddoc = 3                         ";
    $sSqlDesdobramento .= "   )                                                           ";
    $sSqlDesdobramento .= "  )                                                            ";

    /**
     * Retencao
     * Garante que pagamento nao tenha retencao, buscando pela ordem
     */
    $sSqlDesdobramento .= " and not exists(                                                                         ";
    $sSqlDesdobramento .= "   select 1                                                                              ";
    $sSqlDesdobramento .= "     from retencaopagordem                                                               ";
    $sSqlDesdobramento .= "          inner join retencaoreceitas on e23_retencaopagordem = e20_sequencial           ";
    $sSqlDesdobramento .= "                                     and e23_recolhido is true                           ";
    $sSqlDesdobramento .= "          inner join retencaotiporec  on e23_retencaotiporec = e21_sequencial            ";
    $sSqlDesdobramento .= "                                     and e21_retencaotipocalc in(1,2)                    ";
    $sSqlDesdobramento .= "                                     and e21_retencaotiporecgrupo = 1                    ";
    $sSqlDesdobramento .= "          inner  join  retencaonaturezatiporec on e31_retencaotiporec = e21_sequencial   ";
    $sSqlDesdobramento .= "          inner  join  retencaonatureza        on e31_retencaonatureza = e30_sequencial  ";
    $sSqlDesdobramento .= "                                              and e31_retencaonatureza is not null       ";
    $sSqlDesdobramento .= "    where e20_pagordem = c80_codord limit 1                                              ";
    $sSqlDesdobramento .= "  )                                                                                      ";

    /**
     * Filtros
     */
    $sSqlDesdobramento .= "   and not exists (select 1 ";
    $sSqlDesdobramento .= "                     from rhpessoal ";
    $sSqlDesdobramento .= "                          join rhpessoalmov on rh02_regist = rh01_regist ";
    $sSqlDesdobramento .= "                                           and rh02_anousu = {$this->iAno} ";
    $sSqlDesdobramento .= "                                           and rh02_mesusu = {$this->iMes} ";
    $sSqlDesdobramento .= "                                           and rh02_instit = ".db_getsession("DB_instit")." ";
    $sSqlDesdobramento .= "                          left join rhpesrescisao on rh05_seqpes = rh02_seqpes ";
    $sSqlDesdobramento .= "                    where rh01_numcgm = z01_numcgm ";
    $sSqlDesdobramento .= "                      and rh05_seqpes is null) ";


    $sSqlDesdobramento .= " and o41_cnpj = '{$this->sCnpj}' ";
    $sSqlDesdobramento .= " and c53_tipo in ( 30, 31 )      ";
    $sSqlDesdobramento .= " and e60_instit = ".db_getsession("DB_instit") . " ";

    /**
     * Agrupamento subquery dos pagamentos
     */
    $sSqlDesdobramento .= " group by c69_data,    ";
    $sSqlDesdobramento .= "       z01_numcgm,     ";
    $sSqlDesdobramento .= "       z01_cgccpf,     ";
    $sSqlDesdobramento .= "       z01_nome,       ";
    $sSqlDesdobramento .= "       c53_tipo        ";
    $sSqlDesdobramento .= "                       ";
    $sSqlDesdobramento .= " order by c69_data     ";
    $sSqlDesdobramento .= "                       ";
    $sSqlDesdobramento .= " ) as pagamentos       ";

    /**
     * Agrupamento
     */
    $sSqlDesdobramento .= " group by z01_numcgm,  ";
    $sSqlDesdobramento .= "          mes,         ";
    $sSqlDesdobramento .= "          z01_cgccpf,  ";
    $sSqlDesdobramento .= "          z01_nome,    ";
    $sSqlDesdobramento .= "          z01_numcgm   ";

    /**
     * Pagamentos com valor maior ou igual a 6000
     */
    $sSqlDesdobramento .= " having sum(valor_pago - valor_estornado) >= 6000 ";

    $rsDesdobramentos = db_query($sSqlDesdobramento);
    $iDesdobramentos  = pg_num_rows($rsDesdobramentos);

    if ( $iDesdobramentos == 0 ) {
      return false;
    }

    db_utils::getDao("rhdirfgeracaodadospessoalvalor", false);
    db_utils::getDao("rhdirfgeracaodadospessoal", false);

    for ($iIndice = 0; $iIndice < $iDesdobramentos; $iIndice++ ) {

      $oPagamento = db_utils::fieldsMemory($rsDesdobramentos, $iIndice);
      $iCnpj      =  trim($oPagamento->z01_cgccpf);

      if ( $iCnpj == "" ) {

        $this->addInconsistente($oPagamento->z01_numcgm, $oPagamento->z01_nome, 'CPF Inválido');
        continue;
      }

      $oDaoRhDirfGeracaoPessoal               = new cl_rhdirfgeracaodadospessoal();
      $oDaoRhDirfGeracaoPessoal->rh96_cpfcnpj = $oPagamento->z01_cgccpf;
      $oDaoRhDirfGeracaoPessoal->rh96_numcgm  = $oPagamento->z01_numcgm;
      $oDaoRhDirfGeracaoPessoal->rh96_regist  = '0';
      $oDaoRhDirfGeracaoPessoal->rh96_tipo    = 2;
      $oDaoRhDirfGeracaoPessoal->rh96_rhdirfgeracao = $this->iCodigoDirf;
      $oDaoRhDirfGeracaoPessoal->incluir(null);

      if ($oDaoRhDirfGeracaoPessoal->erro_status == '0') {

        $sMsg  = "Erro[10] -  Erro ao incluir valores(CGM: {$oPagamento->z01_numcgm} com CPF/CNPJ Inválido) da DIRF.\n";
        $sMsg .= "{$oDaoRhDirfGeracaoPessoal->erro_msg}";
        throw new Exception($sMsg);
      }

      $iCodigoDirf     = $oDaoRhDirfGeracaoPessoal->rh96_sequencial;
      $nValorPagamento = $oPagamento->valor_pago - $oPagamento->valor_estornado;
      $iTipoValor      = 16;

      /**
       * Pessoa fisica
       */
      $iCodigoImposto = "3208";

      /**
       * Pessoa juridica
       */
      if ( strlen($iCnpj) > 11 ) {
        $iCodigoImposto = '1708';
      }

      $oDaoRhDirfGeracaoPessoalValor = new cl_rhdirfgeracaodadospessoalvalor();

      $oDaoRhDirfGeracaoPessoalValor->rh98_mes                       = $oPagamento->mes;
      $oDaoRhDirfGeracaoPessoalValor->rh98_rhdirftipovalor           = $iTipoValor;
      $oDaoRhDirfGeracaoPessoalValor->rh98_tipoirrf                  = $iCodigoImposto;
      $oDaoRhDirfGeracaoPessoalValor->rh98_rhdirfgeracaodadospessoal = $iCodigoDirf;
      $oDaoRhDirfGeracaoPessoalValor->rh98_instit                    = db_getsession("DB_instit");
      $oDaoRhDirfGeracaoPessoalValor->rh98_valor                     = "{$nValorPagamento}";
      $oDaoRhDirfGeracaoPessoalValor->incluir(null);

      if ($oDaoRhDirfGeracaoPessoalValor->erro_status == '0') {

        $sMsg  = "Erro[11] - Erro ao incluir valores bases da DIRF para .\n";
        $sMsg .= $oDaoRhDirfGeracaoPessoalValor->erro_msg;
        throw new Exception($sMsg);
      }

    }

    return true;
  }

  public function calculaValoresMensaisTipo($iCodigoDirf, $oPessoa, $iTipoIRRF,$lSemRetencao=true, $naoCalcularTipos=array()) {

    $sSqlPagamentos  = " select rh98_rhdirftipovalor,                 ";
    $sSqlPagamentos .= "        sum(rh98_valor) as valor,             ";
    $sSqlPagamentos .= "        rh98_mes                              ";
    $sSqlPagamentos .= "  from  rhdirfgeracaodadospessoalvalor        ";
    $sSqlPagamentos .= "        inner join rhdirfgeracaodadospessoal on rh98_rhdirfgeracaodadospessoal = rh96_sequencial";
    $sSqlPagamentos .= "  where rh96_numcgm        = {$oPessoa->cgm}  ";
    $sSqlPagamentos .= "    and rh96_rhdirfgeracao = {$iCodigoDirf}   ";
    if (!empty($naoCalcularTipos)) {

      $sTipos = implode(",0", $naoCalcularTipos);
      $sSqlPagamentos .= "    and rh98_rhdirftipovalor not in({$sTipos})";
    }

    if ( $lSemRetencao ) {
      $sSqlPagamentos .= "  and rh98_tipoirrf in ('0','{$iTipoIRRF}') ";
    } else {
      $sSqlPagamentos .= "  and rh98_tipoirrf in ('{$iTipoIRRF}')     ";
    }

    $sSqlPagamentos .= "  group by rh98_rhdirftipovalor,              ";
    $sSqlPagamentos .= "        rh98_mes                              ";
    $sSqlPagamentos .= "        having sum(rh98_valor) <> 0           ";
    $sSqlPagamentos .= "  order by rh98_rhdirftipovalor,rh98_mes      ";

    $rsPagamentos = db_query($sSqlPagamentos);
    $aPagamentos  = db_utils::getCollectionByRecord($rsPagamentos);

    foreach ($aPagamentos as $oPagamento) {

      if ( $oPagamento->rh98_rhdirftipovalor == 16 && $iTipoIRRF == '1708' ) {
        continue;
      }

      /*
       * 13 é pagamento de plano de saude.
       */
      if (   $oPagamento->rh98_rhdirftipovalor != 13
          && $oPagamento->rh98_rhdirftipovalor != 14
          && $oPagamento->rh98_rhdirftipovalor != 15
         ) {

        if (!isset($oPessoa->pagamentos[$oPagamento->rh98_rhdirftipovalor])) {
          $oPessoa->pagamentos[$oPagamento->rh98_rhdirftipovalor] = array();
        }

        $oPessoa->pagamentos[$oPagamento->rh98_rhdirftipovalor][] = $oPagamento;

      } elseif($oPagamento->rh98_rhdirftipovalor == 13) {

        $oPessoa->totalsaude1 += $oPagamento->valor;

      } elseif($oPagamento->rh98_rhdirftipovalor == 14) {

        $oPessoa->totalsaude2 += $oPagamento->valor;
      } elseif($oPagamento->rh98_rhdirftipovalor == 15) {

        $oPessoa->totaloutros += $oPagamento->valor;
      }
    }

    unset($aPagamentos);
  }

  public function getInformacoesComplementares($iCgm) {

    /**
     * Busca total da pensao por pensionista do CGM informado
     */
    $ssqlPensoes  = "select sum(r52_valor + r52_valcom + r52_valfer + r52_val13 + r52_valres) as valor, ";
    $ssqlPensoes .= "       z01_nome ";
    $ssqlPensoes .= "  from pensao ";
    $ssqlPensoes .= "       inner join cgm on z01_numcgm = r52_numcgm ";
    $ssqlPensoes .= "       inner join rhpessoal on r52_regist = rh01_regist ";
    $ssqlPensoes .= " where r52_anousu = {$this->iAno} ";
    $ssqlPensoes .= " and rh01_numcgm  = {$iCgm}";
    $ssqlPensoes .= " group by z01_nome";

    $rsTotalPensoes = db_query($ssqlPensoes);
    $iTotalPensoes  = pg_num_rows($rsTotalPensoes);
    $sInformacaoComplementar = '';

    if ($iTotalPensoes > 0) {

      $sInformacaoComplementar = 'PENSAO ALIMENTICIA: ';
      for ($iPensao = 0; $iPensao < $iTotalPensoes; $iPensao++) {

        $oDadosPensao  = db_utils::fieldsMemory($rsTotalPensoes, $iPensao);
        $aPartesNome   = explode(" ", $oDadosPensao->z01_nome);
        $sInformacaoComplementar .= "{$aPartesNome[0]} (".trim(db_formatar($oDadosPensao->valor, 'f')).") ";
      }
    }

    /**
     * Buscas os dados da base B932 que contem as rubricas de desconto dos planos de saúde,
     * o valor de desconto total de cada uma delas é somada com base no ano base da geração da DIRF
     */
    $sWhereBaseRubricas  = " select distinct r09_rubric                       ";
    $sWhereBaseRubricas .= "  from basesr                                     ";
    $sWhereBaseRubricas .= " where r09_anousu  =    ".db_anofolha();
    $sWhereBaseRubricas .= "   and r09_mesusu  =    ".db_mesfolha();
    $sWhereBaseRubricas .= "   and r09_base    = 'B932'                       ";
    $sWhereBaseRubricas .= "   and r09_instit  = " . db_getsession("DB_instit");

    $aTabelasCalculo         = array('gerfsal' => 'r14', 'gerfcom' => 'r48', 'gerfres' => 'r20', 'gerffer' => 'r31');
    $aQueryCalculoFinanceiro = array();

    foreach ($aTabelasCalculo as $sTabela => $sSigla) {

      $sSqlCalculoFinanceiro     = " select sum({$sSigla}_valor) as valor,rh27_descr                                             ";
      $sSqlCalculoFinanceiro    .= "   from {$sTabela}                                                                           ";
      $sSqlCalculoFinanceiro    .= "  inner join rhrubricas on rh27_rubric = {$sSigla}_rubric and rh27_instit = {$sSigla}_instit ";
      $sSqlCalculoFinanceiro    .= "  inner join rhpessoal on rh01_regist  = {$sSigla}_regist                                    ";
      $sSqlCalculoFinanceiro    .= " where {$sSigla}_rubric in ( {$sWhereBaseRubricas} )                                         ";
      $sSqlCalculoFinanceiro    .= "   and {$sSigla}_anousu  = {$this->iAno}                                                     ";
      $sSqlCalculoFinanceiro    .= "   and rh01_numcgm = {$iCgm}                                                                 ";
      $sSqlCalculoFinanceiro    .= "   and {$sSigla}_instit  = " . db_getsession("DB_instit");
      $sSqlCalculoFinanceiro    .= " group by {$sSigla}_rubric, rh01_numcgm, rh27_descr                                          ";
      $aQueryCalculoFinanceiro[] = $sSqlCalculoFinanceiro;
    }

    $sSqlBaseInfPlanoSaude = implode($aQueryCalculoFinanceiro, 'union');
    $rsBaseInfPlanoSaude   = db_query($sSqlBaseInfPlanoSaude);

    if ($rsBaseInfPlanoSaude && pg_num_rows($rsBaseInfPlanoSaude) > 0) {

      for ($iRubricaPlano = 0; $iRubricaPlano < pg_num_rows($rsBaseInfPlanoSaude); $iRubricaPlano++) {

        $oRubricaPlanoSaude       = db_utils::fieldsMemory($rsBaseInfPlanoSaude, $iRubricaPlano);
        $aNomeRubrica             = explode(" ", $oRubricaPlanoSaude->rh27_descr);
        $sInformacaoComplementar .= "{$aNomeRubrica[0]}(".trim(db_formatar($oRubricaPlanoSaude->valor, 'f')).")";
      }
    }

    return $sInformacaoComplementar;
  }
}

<?php
/*
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Grm;


use ECidade\Tributario\Grm\Repository\Recibo as ReciboRepository;

class EmissaoDocumento {

  /**
   * @var \Recibo
   */
  private $recibo;

  /**
   * @var Recibo
   */
  private $reciboGrm;

  /**
   * @var \Instituicao
   */
  private $instituicao;

  /**
   * @var integer
   */
  private $anousu;

  /**
   * @var \regraEmissao
   */
  private $regraEmissao = null;

  /**
   * @var \convenio
   */
  private $convenio;

  private $historico;

  /**
   * EmissaoDocumento constructor.
   * @param \Recibo      $recibo
   * @param \Instituicao $instituicao
   *
   */
  public function __construct(\Recibo $recibo, \Instituicao $instituicao, Recibo $reciboGrm) {

    $this->recibo      = $recibo;
    $this->instituicao = $instituicao;
    $this->reciboGrm   = $reciboGrm;
  }

  public function setAnousu($iAnousu) {
    $this->anousu = $iAnousu;
  }

  /**
   * @return \regraEmissao
   */
  public function getRegraEmissao() {
    return $this->regraEmissao;
  }
  /**
   * @param \regraEmissao $regraEmissao
   */
  public function setRegraEmissao(\regraEmissao $regraEmissao) {
    $this->regraEmissao = $regraEmissao;
  }

  /**
   * @return \convenio
   */
  public function getConvenio() {

    return $this->convenio;
  }

  /**
   * @return mixed
   */
  public function getHistorico() {

    return $this->historico;
  }

  /**
   * @param mixed $historico
   */
  public function setHistorico($historico) {

    $this->historico = $historico;
  }



  /**
   * @param \convenio $convenio
   */
  public function setConvenio(\convenio $convenio) {
    $this->convenio = $convenio;
  }

  /**
   * Cria o documento do PDF
   * @return null
   * @throws \SoapFault
   */
  private function gerarRecibo() {

    try {

      $oRecibo          = $this->recibo;
      $oConvenio        = $this->convenio;
      $oRegraEmissao    = $this->regraEmissao;
      $iCodigoBarras    = $oConvenio->getCodigoBarra();
      $iLinhaDigitavel  = $oConvenio->getLinhaDigitavel();
      $dDataVencimento  = db_formatar($oRecibo->getDataVencimentoRecibo(), "d");
      $iNumpreFormatado = db_sqlformatar($oRecibo->getNumpreRecibo(),8,'0').'000999';
      $iNumpreFormatado = $iNumpreFormatado . db_CalculaDV($iNumpreFormatado,11);

      $oIdentificacao   = $this->reciboGrm->getCgm();
      $oInstituicao     = $this->instituicao;

      $oPdf                = $oRegraEmissao->getObjPdf();
      $oPdf->logo 	       = $oInstituicao->getImagemLogo();
      $oPdf->prefeitura    = $oInstituicao->getDescricao();
      $oPdf->tipo_convenio = $oConvenio->getTipoConvenio();
      $oPdf->uf_config     = $oInstituicao->getUf();
      $oPdf->enderpref	   = $oInstituicao->getLogradouro();
      $oPdf->municpref	   = $oInstituicao->getMunicipio();
      $oPdf->telefpref	   = $oInstituicao->getTelefone();
      $oPdf->emailpref	   = $oInstituicao->getEmail();
      $oPdf->cgcpref       = $oInstituicao->getCNPJ();

      $sLogradouro  = $oIdentificacao->getLogradouro();
      $sComplemento = $oIdentificacao->getComplemento();
      $sNumCgm      = $oIdentificacao->getCodigo();
      $sNumInscr    = $oIdentificacao->getInscricaoEstadual();
      $sMunicipio   = $oIdentificacao->getMunicipio();
      $sBairro      = $oIdentificacao->getBairro();
      $sCep         = $oIdentificacao->getCep();

      $cpfcnpj = $oIdentificacao->isJuridico() ? $oIdentificacao->getCnpj() : $oIdentificacao->getCpf();
      $oPdf->nome               = $oIdentificacao->getNome();
      $oPdf->ender              = $sLogradouro;
      $oPdf->munic              = $sMunicipio;
      $oPdf->bairrocontri       = $sBairro;
      $oPdf->cep                = $sCep;
      $oPdf->cgccpf             = $cpfcnpj;
      $oPdf->tipoinscr          = "Numcgm : {$sNumCgm}";
      $oPdf->nrinscr            = "Inscrição : {$sNumInscr}";
      $oPdf->tipolograd         = "Logradouro : {$sLogradouro}";
      $oPdf->tipocompl          = 'N'.chr(176)."/Compl : {$sComplemento}";
      $oPdf->tipobairro         = "Bairro : {$sBairro}";

      // Identificações recibo
      $rsDadosReceita           = $this->getDadosReceita();
      $oPdf->datacalc	      	  = date('d-m-Y');
      $oPdf->predatacalc	   	  = date('d-m-Y');
      $oPdf->linhasdadospagto   = pg_numrows($rsDadosReceita);
      $oPdf->recorddadospagto   = $rsDadosReceita;
      $oPdf->receita		        = 'k00_receit';
      $oPdf->receitared	        = 'codreduz';
      $oPdf->dreceita           = 'k02_drecei';
      $oPdf->ddreceita	        = 'k07_descr';
      $oPdf->valor 	   	        = 'valor';
      $oPdf->historico	        = $this->getHistorico();
      $oPdf->histparcel	        = "";
      $oPdf->totalrec           = $this->reciboGrm->getValor();
      $oPdf->totaldesc          = $this->reciboGrm->getValorDesconto();
      $oPdf->totalacres         = $this->reciboGrm->getValorOutrosAcrescimento();
      $oPdf->outras_deducoes    = $this->reciboGrm->getValorOutrasDeducoes();
      $oPdf->juros_encargos     = $this->reciboGrm->getValorJuros();
      $oPdf->multa_mora         = $this->reciboGrm->getValorMulta();
      $oPdf->valtotal           = $this->reciboGrm->getValorTotal();
      $oPdf->dtvenc  		        = $dDataVencimento;
      $oPdf->numpre	  	        = $iNumpreFormatado;
      $oPdf->valtotal		        = db_formatar($oRecibo->getTotalRecibo(),'f');
      $oPdf->linhadigitavel	    = $iLinhaDigitavel;
      $oPdf->codigobarras    	  = $iCodigoBarras;
      $oPdf->imprime();
      return $oPdf;

    } catch (\Exception $e) {
      throw new \SoapFault('Recibo GRM', $e->getMessage());
    }
  }

  /**
   * Cria o documento no diretorio local
   * @param $localArquivo
   * @throws \DBException
   * @return \File
   */
  public function gerarPdfNoLocal($localArquivo) {

    $sqlBuscaModelo = "select * from modcarnepadrao inner join modcarnepadraocadmodcarne on k48_sequencial = m01_modcarnepadrao where k48_cadtipomod = 26";
    $resultModelo   = db_query($sqlBuscaModelo);
    if (!$resultModelo) {
      throw new \DBException("Não foi possível localizar o modelo de impressão para o modelo 26 - GRM.");
    }

    if (pg_num_rows($resultModelo) > 0 && \db_utils::fieldsMemory($resultModelo, 0)->m01_cadmodcarne == GuiaDeRecolhimento::MODELO_GUIA) {

      /* @todo arrumar uma forma de passar a instituicao para o model - desculpe  :) */
      $recibo = new ReciboRepository();
      $recibo = $recibo->getById($this->reciboGrm->getCodigo());
      $recibo->setLinhaDigitavel($this->reciboGrm->getLinhaDigitavel());
      $recibo->setCodigoBarras($this->reciboGrm->getCodigoBarras());
      $recibo->setCidadao($this->reciboGrm->getCidadao());
      $guiaRecolhimento = new GuiaDeRecolhimento($recibo, \InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));
      $arquivo = $guiaRecolhimento->gerarArquivo();
      return $arquivo;

    } else {

      $oRecibo     = $this->gerarRecibo();
      $sCaminhoPDF = $localArquivo;
      $oRecibo->objpdf->output($sCaminhoPDF);
      return new \File($localArquivo);
    }
  }

  /**
   * Retorna resource com os dadso da receita do recibo.
   * Modelos de impressao usam um resource para montar a lista de receitas
   * @return bool|resource
   * @throws \DBException
   */
  private function getDadosReceita() {

    // Busca os dados da receita
    $sSql  = "   SELECT r.k00_numcgm,                                                                            ";
    $sSql .= "          r.k00_dtvenc,                                                                            ";
    $sSql .= "          r.k00_receit,                                                                            ";
    $sSql .= "          UPPER(t.k02_descr) AS k02_descr,                                                         ";
    $sSql .= "          UPPER(t.k02_drecei) AS k02_drecei,                                                       ";
    $sSql .= "          r.k00_dtoper AS k00_dtoper,                                                              ";
    $sSql .= "          k00_codsubrec,                                                                           ";
    $sSql .= "          COALESCE(UPPER(k07_descr),' ') AS k07_descr,                                             ";
    $sSql .= "          SUM(r.k00_valor) AS valor,                                                               ";
    $sSql .= "          CASE                                                                                     ";
    $sSql .= "            WHEN taborc.k02_codigo IS NULL                                                         ";
    $sSql .= "              THEN tabplan.k02_reduz                                                               ";
    $sSql .= "            ELSE                                                                                   ";
    $sSql .= "              taborc.k02_codrec                                                                    ";
    $sSql .= "          END AS codreduz,                                                                         ";
    $sSql .= "          k00_hist,                                                                                ";
    $sSql .= "          (SELECT (SELECT k02_codigo                                                               ";
    $sSql .= "                     FROM tabrec                                                                   ";
    $sSql .= "                    WHERE k02_recjur = k00_receit                                                  ";
    $sSql .= "                       OR k02_recmul = k00_receit LIMIT 1                                          ";
    $sSql .= "                   ) IS NOT NULL                                                                   ";
    $sSql .= "          ) AS codtipo                                                                             ";
    $sSql .= "     FROM recibo r                                                                                 ";
    $sSql .= "          INNER JOIN tabrec t 		 ON t.k02_codigo       = r.k00_receit                            ";
    $sSql .= "          INNER JOIN tabrecjm 		 ON tabrecjm.k02_codjm = t.k02_codjm                             ";
    $sSql .= "          LEFT OUTER JOIN tabdesc  ON codsubrec          = k00_codsubrec                           ";
    $sSql .= "                                  AND k07_instit         = {$this->instituicao->getCodigo()}       ";
    $sSql .= "          LEFT OUTER JOIN taborc   ON t.k02_codigo       = taborc.k02_codigo                       ";
    $sSql .= "                                  AND taborc.k02_anousu  = {$this->anousu}                         ";
    $sSql .= "          LEFT OUTER JOIN tabplan  ON t.k02_codigo       = tabplan.k02_codigo                      ";
    $sSql .= "                                  AND tabplan.k02_anousu = {$this->anousu}                         ";
    $sSql .= "    WHERE r.k00_numpre = {$this->recibo->getNumpreRecibo()}                                        ";
    $sSql .= " GROUP BY r.k00_dtoper,                                                                            ";
    $sSql .= "          r.k00_dtvenc,                                                                            ";
    $sSql .= "          r.k00_receit,                                                                            ";
    $sSql .= "          t.k02_descr,                                                                             ";
    $sSql .= "          t.k02_drecei,                                                                            ";
    $sSql .= "          r.k00_numcgm,                                                                            ";
    $sSql .= "          k00_codsubrec,                                                                           ";
    $sSql .= "          k07_descr,                                                                               ";
    $sSql .= "          codreduz,                                                                                ";
    $sSql .= "          r.k00_hist                                                                               ";

    $rsDadosPagamento = db_query($sSql);
    if (!$rsDadosPagamento) {
      throw new \DBException("Erro ao consultar dados da receita para geração da GRM");
    }
    return $rsDadosPagamento;
  }
}
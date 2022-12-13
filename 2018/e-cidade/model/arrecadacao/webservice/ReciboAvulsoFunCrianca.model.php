<?php

/**
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
 * Classe responsável pela geração de recibo avulso do projeto funcrianca
 *
 * @author Luiz Marcelo Schmitt <luiz.marcelo@dbseller.com.br>
 */
class ReciboAvulsoFunCrianca {

  /**
   * Cpf ou Cnpj do doador
   * @var integer
   */
  protected $iCnpjCpf;

  /**
   * Valor da doação
   * @var float
   */
  protected $fValorDoacao;

  /**
   * Nome do doador
   * @var string
   */
  protected $sNomeDoador;

  /**
   * Código da receita para gerar o recibo
   * @var integer
   */
  protected $iCodigoReceita;

  /**
   * Data do vencimento do Boleto
   * @var date
   */
  protected $dDataVencimento;

  /**
   * Dados da instituição configurada
   * @var object
   */
  protected $oInstituicao;

  /**
   * Nímero do CGM do doador ou da prefeitura
   * @var
   */
  protected $iNumCgm;

  /**
   * Metodo Construtor da Classe
   */
  public function __construct() {
    db_app::import('recibo');
  }

  /**
   * @return float
   */
  public function getValorDoacao() {

    return $this->fValorDoacao;
  }

  /**
   * @param float $fValorDoacao
   */
  public function setValorDoacao($fValorDoacao) {

    $this->fValorDoacao = $fValorDoacao;
  }

  /**
   * @return integer
   */
  public function getCnpjCpf() {

    return $this->iCnpjCpf;
  }

  /**
   * @param integer $iCnpjCpf
   */
  public function setCnpjCpf($iCnpjCpf) {

    $this->iCnpjCpf = $iCnpjCpf;
  }

  /**
   * @return integer
   */
  public function getCodigoReceita() {

    return $this->iCodigoReceita;
  }

  /**
   * @param integer $iCodigoReceita
   */
  public function setCodigoReceita($iCodigoReceita) {

    $this->iCodigoReceita = $iCodigoReceita;
  }

  /**
   * @return integer
   */
  public function getNumCgm() {

    return $this->iNumCgm;
  }

  /**
   * @param integer $iNumCgm
   */
  public function setNumCgm($iNumCgm) {

    $this->iNumCgm = $iNumCgm;
  }

  /**
   * @param date $dDataVencimento
   */
  public function setDataVencimento($dDataVencimento){
    $this->dDataVencimento = $dDataVencimento;
  }

  /**
   * @return date
   */
  public function getDataVencimento(){
    return $this->dDataVencimento;
  }

  /**
   * @return object
   */
  public function getInstituicao() {

    return $this->oInstituicao;
  }

  /**
   * @param object $oInstituicao
   */
  public function setInstituicao($oInstituicao) {

    $this->oInstituicao = $oInstituicao;
  }

  /**
   * @return string
   */
  public function getNomeDoador() {

    return $this->sNomeDoador;
  }

  /**
   * @param string $sNomeDoador
   */
  public function setNomeDoador($sNomeDoador) {

    $this->sNomeDoador = $sNomeDoador;
  }

  /**
   * Adiciona os dados da doação na recibo
   *
   * @return bool
   * @throws Exception
   */
  public function gerarRecibo() {

    db_inicio_transacao();

    try {

      $iAnoUsu = db_getsession("DB_anousu");

      $sSql  = "   SELECT c61_instit                                                                          ";
      $sSql .= "     FROM tabplan                                                                             ";
      $sSql .= "          INNER JOIN conplanoreduz     ON tabplan.k02_anousu     = conplanoreduz.c61_anousu   ";
      $sSql .= "                                       AND tabplan.k02_reduz     = conplanoreduz.c61_reduz    ";
      $sSql .= "     WHERE                             tabplan.k02_anousu        = {$iAnoUsu}                 ";
      $sSql .= "                                       AND tabplan.k02_codigo    = {$this->getCodigoReceita()}";

      // Seta instituicao default
      $iInstituicao = db_getsession('DB_instit');
      $rsInstituicao = db_query($sSql);

      if (pg_numrows($rsInstituicao)>0){
        $iInstituicao = db_utils:: fieldsMemory($rsInstituicao,0)->c61_instit;
      }

      $this->setInstituicao(new Instituicao($iInstituicao));

      $oCGM = CgmFactory::getInstanceByCnpjCpf($this->getCnpjCpf());

      // Verifica se existe cadastro se não pega CGM prefeitura
      if ($oCGM) {

        $this->setNumCgm($oCGM->getCodigo());
        $oDoador = CgmFactory::getInstanceByCgm($this->getNumCgm());
        $this->setNomeDoador($oDoador->getNome());
      } else {

        // Pega o CGM prefeitura
        $this->setNumCgm($this->getInstituicao()->getCgm()->getCodigo());
      }

      $iNumCgm = $this->getNumCgm();

      if (empty($iNumCgm)) {
        throw new Exception('Número do CGM não existe!');
      }

      // Adiciona um recibo avulso
      $oRecibo = new Recibo(1, $iNumCgm);
      $oRecibo->setDataVencimentoRecibo($this->getDataVencimento());
      $oRecibo->setVinculoCgm($iNumCgm);
      $oRecibo->adicionarReceita($this->getCodigoReceita(), $this->getValorDoacao(), 0);
      $oRecibo->setHistorico("Doação para o Portal Doações - {$this->getNomeDoador()}");
      $oRecibo->emiteRecibo();

      $sRetorno = $this->gerarBoleto($oRecibo, $iInstituicao);
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      db_fim_transacao(true);
      throw $eErro;
    }

    return $sRetorno;
  }


  /**
   * Gera o boleto do recibo
   *
   * @return null|string
   * @throws Exception
   */
  protected function gerarBoleto($oRecibo, $iInstituicao) {

    $sBoletoGerado = null;
    $iAnoUsu       = db_getsession("DB_anousu");
    $dDataUsu      = db_getsession("DB_datausu");
    $iIDUsuario    = db_getsession('DB_id_usuario');
    $sIp           = db_getsession('DB_ip');

    try {

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
      $sSql .= "                                  AND k07_instit         = {$iInstituicao}                         ";
      $sSql .= "          LEFT OUTER JOIN taborc   ON t.k02_codigo       = taborc.k02_codigo                       ";
      $sSql .= "                                  AND taborc.k02_anousu  = {$iAnoUsu}                              ";
      $sSql .= "          LEFT OUTER JOIN tabplan  ON t.k02_codigo       = tabplan.k02_codigo                      ";
      $sSql .= "                                  AND tabplan.k02_anousu = {$iAnoUsu}                              ";
      $sSql .= "    WHERE r.k00_numpre = {$oRecibo->getNumpreRecibo()}                                             ";
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

      // Gera o objeto PDF para a emissão
      $oRegraEmissao = new regraEmissao(null, 22, $iInstituicao, date("Y-m-d",$dDataUsu), $sIp);

      // Formata valor para gerar o código de barras
      $fValorBarra = str_replace('.','',str_pad(number_format($oRecibo->getTotalRecibo(),2,"","."),11,"0",STR_PAD_LEFT));

      // Gera os dados para o código de barras do convenio
      $oConvenio = new convenio($oRegraEmissao->getConvenio(),
                                $oRecibo->getNumpreRecibo(),
                                1,
                                $oRecibo->getTotalRecibo(),
                                db_formatar($fValorBarra, 's', '0', 11, 'e'),
                                $oRecibo->getDataVencimentoRecibo(),
                                6);

      $iCodigoBarras    = $oConvenio->getCodigoBarra();
      $iLinhaDigitavel  = $oConvenio->getLinhaDigitavel();
      $dDataVencimento  = db_formatar($oRecibo->getDataVencimentoRecibo(),"d");

      $iNumpreFormatado = db_sqlformatar($oRecibo->getNumpreRecibo(),8,'0').'000999';
      $iNumpreFormatado = $iNumpreFormatado . db_CalculaDV($iNumpreFormatado,11);

      $oIdentificacao   = CgmFactory::getInstanceByCgm($this->getNumCgm());
      $oInstituicao     = $this->getInstituicao();
      $oPdf             = $oRegraEmissao->getObjPdf();

      // Identificação do proprietário
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
      $sCnpjCpf     = $this->getCnpjCpf();

      if ($sNumCgm == $oInstituicao->getCgm()->getCodigo()) {

        $sLogradouro  = "XXXXXXXXXXXXXXXXXXXXXX";
        $sComplemento = "XXXX";
        $sBairro      = "XXXXX";
        $sCep         = "XXXXX";
        $sNumCgm      = "XXXX";
        $sMunicipio   = "XXXX";
        $sNumInscr    = "XXXXX";
      }

      // Identificação da origem
      $oPdf->nome               = $this->getNomeDoador();
      $oPdf->ender              = $sLogradouro;
      $oPdf->munic              = $sMunicipio;
      $oPdf->bairrocontri       = $sBairro;
      $oPdf->cep                = $sCep;
      $oPdf->cgccpf             = $sCnpjCpf;
      $oPdf->tipoinscr          = "Numcgm : {$sNumCgm}";
      $oPdf->nrinscr            = "Inscrição : {$sNumInscr}";
      $oPdf->tipolograd         = "Logradouro : {$sLogradouro}";
      $oPdf->tipocompl          = 'N'.chr(176)."/Compl : {$sComplemento}";
      $oPdf->tipobairro         = "Bairro : {$sBairro}";

      // Identificações recibo
      $oPdf->datacalc	      	  = date('d-m-Y',$dDataUsu);
      $oPdf->predatacalc	   	  = date('d-m-Y',$dDataUsu);
      $oPdf->linhasdadospagto   = pg_numrows($rsDadosPagamento);
      $oPdf->recorddadospagto   = $rsDadosPagamento;
      $oPdf->receita		        = 'k00_receit';
      $oPdf->receitared	        = 'codreduz';
      $oPdf->dreceita           = 'k02_drecei';
      $oPdf->ddreceita	        = 'k07_descr';
      $oPdf->valor 	   	        = 'valor';
      $oPdf->historico	        = "Doação para o Portal Doações";
      $oPdf->histparcel	        = "Histórico das parcelas";
      $oPdf->dtvenc  		        = $dDataVencimento;
      $oPdf->numpre	  	        = $iNumpreFormatado;
      $oPdf->valtotal		        = db_formatar($oRecibo->getTotalRecibo(),'f');
      $oPdf->linhadigitavel	    = $iLinhaDigitavel;
      $oPdf->codigobarras    	  = $iCodigoBarras;
      $oPdf->imprime();

      $sCaminhoPDF  = 'tmp/boleto_funcrianca_';
      $sCaminhoPDF .= $iIDUsuario . '_';
      $sCaminhoPDF .= date('d-m-Y_H:i:s');
      $sCaminhoPDF .= '.pdf';

      $oPdf->objpdf->output($sCaminhoPDF);

      // Retorna a stream do PDF gerado
      $sArquivoPDF   = file_get_contents($sCaminhoPDF);
      $sBoletoGerado = base64_encode($sArquivoPDF);
    } catch (Exception $eErro) {
      throw new Exception(print_r($eErro->getMessage(), true));
    }

    $resultado = array(
      'numpre'         => $oRecibo->getNumpreRecibo(),
      'codarrecadacao' => $iNumpreFormatado,
      'pdf'            => $sBoletoGerado
    );

    return $resultado;
  }
}
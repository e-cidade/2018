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

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

/**
 * Classe responsável pela geração de recibo avulso do Portal de Doações
 *set
 * @author Luiz Marcelo Schmitt <luiz.marcelo@dbseller.com.br>
 */
class ReciboAvulsoPortalDoacoes {

  /**
   * Cpf ou Cnpj do doador
   * @var integer
   */
  protected $iCnpjCpf;

  /**
   * Cnpj da Instituicao que irá receber a doação
   * @var integer
   */
  protected $iCnpjInstituicao;


  /**
   * Valor da doação
   * @var float
   */
  protected $fValorDoacao;

  /**
   * Nome do doador
   * @var string
   */
  // protected $sNomeDoador;

  /**
   * Nome da Instituicao
   * @var string
   */
  protected $sNomeInstituicao;

  /**
   * Endereco da Instituicao
   * @var string
   */
  protected $sEnderecoInstituicao;

  /**
   * E-mail da Instituicao
   * @var string
   */
  protected $sEmailInstituicao;

  /**
   * Telefone da Instituicao
   * @var string
   */
  protected $sTelefoneInstituicao;

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
  public function getCnpjInstituicao() {

    return $this->iCnpjInstituicao;
  }

  /**
   * @param integer $iCnpj
   */
  public function setCnpjInstituicao($iCnpjInstituicao) {

    $this->iCnpjInstituicao = $iCnpjInstituicao;
  }

  /**
   * @return string
   */
  public function getNomeInstituicao() {

    return $this->sNomeInstituicao;
  }

  /**
   * @param string $sNomeInstituicao
   */
  public function setNomeInstituicao($sNomeInstituicao) {

    $this->sNomeInstituicao = $sNomeInstituicao;
  }

  /**
   * @return string
   */
  public function getEnderecoInstituicao() {

    return $this->sEnderecoInstituicao;
  }

  /**
   * @param string $sEnderecoInstituicao
   */
  public function setEnderecoInstituicao($sEnderecoInstituicao) {

    $this->sEnderecoInstituicao = $sEnderecoInstituicao;
  }

  /**
   * @return string
   */
  public function getEmailInstituicao() {

    return $this->sEmailInstituicao;
  }

  /**
   * @param string $sEmailInstituicao
   */
  public function setEmailInstituicao($sEmailInstituicao) {

    $this->sEmailInstituicao = $sEmailInstituicao;
  }

  /**
   * @return string
   */
  public function getTelefoneInstituicao() {

    return $this->sTelefoneInstituicao;
  }

  /**
   * @param string $sTelefoneInstituicao
   */
  public function setTelefoneInstituicao($sTelefoneInstituicao) {

    $this->sTelefoneInstituicao = $sTelefoneInstituicao;
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

    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação ativa");
    }

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
        throw new Exception("Cadastro não localizado", 1);
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
    } catch (Exception $eErro) {
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
      $oRegraEmissao = new regraEmissao(null, 24, $iInstituicao, date("Y-m-d", $dDataUsu), $sIp);

      $lConvenioCobrancaRegistrada = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

      if ($lConvenioCobrancaRegistrada) {
        CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
      }

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

      if($this->getCnpjInstituicao()){

        $oPdf->prefeitura    = $this->getNomeInstituicao();
        $oPdf->enderpref     = $this->getEnderecoInstituicao();
        $oPdf->telefpref     = $this->getTelefoneInstituicao();
        $oPdf->emailpref     = $this->getEmailInstituicao();
        $oPdf->cgcpref       = $this->getCnpjInstituicao();

        $oPdf->municpref     = $oInstituicao->getMunicipio();
        $oPdf->tipo_convenio = $oConvenio->getTipoConvenio();
        $oPdf->uf_config     = $oInstituicao->getUf();
      }

      $sLogradouro  = utf8_decode($oIdentificacao->getLogradouro());
      $sComplemento = utf8_decode($oIdentificacao->getComplemento());
      $sNumCgm      = $oIdentificacao->getCodigo();
      $sNumInscr    = $oIdentificacao->getInscricaoEstadual();
      $sNumero      = $oIdentificacao->getNumero();
      $sMunicipio   = utf8_decode($oIdentificacao->getMunicipio());
      $sBairro      = utf8_decode($oIdentificacao->getBairro());
      $sCep         = $oIdentificacao->getCep();
      $sNome        = utf8_decode($oIdentificacao->getNome());
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
// ORIGINAL
      // Identificação da origem
      $oPdf->nome               = $sNome;
      $oPdf->ender              = $sLogradouro;
      $oPdf->munic              = $sMunicipio;
      $oPdf->bairrocontri       = $sBairro;
      $oPdf->cep                = $sCep;
      $oPdf->cgccpf             = $sCnpjCpf;
      $oPdf->tipoinscr          = "Numcgm : {$sNumCgm}";
      $oPdf->nrinscr            = "Inscrição : {$sNumInscr}";
      $oPdf->tipolograd         = "Logradouro : {$sLogradouro}";
      if(!empty($sComplemento)){
        $sNumero .= ' - ' . $sComplemento;
      }
      $oPdf->tipocompl          = 'N'.chr(176)."/Compl : ";
      $oPdf->tipobairro         = "Bairro : {$sBairro}";

      // Identificações recibo
      $oPdf->datacalc           = date('d-m-Y',$dDataUsu);
      $oPdf->predatacalc        = date('d-m-Y',$dDataUsu);
      $oPdf->linhasdadospagto   = pg_numrows($rsDadosPagamento);
      $oPdf->recorddadospagto   = $rsDadosPagamento;
      $oPdf->receita            = 'k00_receit';
      $oPdf->receitared         = 'codreduz';
      $oPdf->dreceita           = 'k02_drecei';
      $oPdf->ddreceita          = 'k07_descr';
      $oPdf->valor              = 'valor';
      // $oPdf->historico         = "Doação para o Portal Doações";
      $oPdf->historico          = "";
      $oPdf->histparcel         = "Histórico das parcelas";
      $oPdf->dtvenc             = $dDataVencimento;
      $oPdf->numpre             = $iNumpreFormatado;
      $oPdf->valtotal           = db_formatar($oRecibo->getTotalRecibo(),'f');
      $oPdf->linhadigitavel     = $iLinhaDigitavel;
      $oPdf->codigobarras       = $iCodigoBarras;
// FIM ORIGINAL

// Novo Modelo
  // $oConvenio      = new convenio($oRegraEmissao->getConvenio(),$oRecibo->getNumpreRecibo(),1,$oRecibo->getTotalRecibo(),$fValorBarra, $dDataVencimento,6);

  $codigobarras   = $oConvenio->getCodigoBarra();
  $linhadigitavel = $oConvenio->getLinhaDigitavel();
  $datavencimento = db_formatar($oRecibo->getDataVencimentoRecibo(),"d");


  if(strlen($oConvenio->getConvenioCobranca()) == 7) {

    $oPdf->nosso_numero = trim($oConvenio->getConvenioCobranca()) . str_pad($oRecibo->getNumpreRecibo(),8,"0",STR_PAD_LEFT) . "00";
  } else {

    $oPdf->nosso_numero = $oConvenio->getNossoNumero();
  }

  $oPdf->agencia_cedente  = $oConvenio->getAgenciaCedente();
  $oPdf->carteira         = $oConvenio->getCarteira();
  $oPdf->tipobairro       = 'Bairro :';

 $sql = "select r.k00_numcgm,
                r.k00_dtvenc,
                r.k00_receit,
                upper(t.k02_descr) as k02_descr,
                upper(t.k02_drecei) as k02_drecei,
                r.k00_dtoper as k00_dtoper,
                k00_codsubrec,
                coalesce(upper(k07_descr),' ') as k07_descr ,
                sum(r.k00_valor) as valor,
                case
                   when taborc.k02_codigo is null
                     then tabplan.k02_reduz
                   else
                     taborc.k02_codrec
                end as codreduz,
                k00_hist,
                (select (select k02_codigo from tabrec where k02_recjur = k00_receit or k02_recmul = k00_receit limit 1) is not null ) as codtipo           from recibo r
                inner join tabrec t      on t.k02_codigo       = r.k00_receit
                inner join tabrecjm      on tabrecjm.k02_codjm = t.k02_codjm
                 left outer join tabdesc on codsubrec          = k00_codsubrec
                                        and k07_instit         = ".$oInstituicao->getCodigo()."
                 left outer join taborc  on t.k02_codigo       = taborc.k02_codigo
                                        and taborc.k02_anousu  = ".$iAnoUsu."
                 left outer join tabplan on t.k02_codigo       = tabplan.k02_codigo
                                        and tabplan.k02_anousu = ".$iAnoUsu."
           where r.k00_numpre = ".$oRecibo->getNumpreRecibo()."
           group by r.k00_dtoper,r.k00_dtvenc,r.k00_receit,t.k02_descr,t.k02_drecei,r.k00_numcgm,k00_codsubrec,k07_descr,codreduz,r.k00_hist";
 $DadosPagamento = db_query($sql);

$total_recibo = 0;
for($i = 0;$i < pg_num_rows($DadosPagamento);$i++) {

  $oReceitas = db_utils::fieldsMemory($DadosPagamento,$i);

  $total_recibo           += $oReceitas->valor;
  $arraycodreceitas[$i]   =  $oReceitas->k00_receit;
  $arrayreduzreceitas[$i] =  $oReceitas->codreduz;
  $arraydescrreceitas[$i] =  $oReceitas->k02_descr;
  $arrayvalreceitas[$i]   =  $oReceitas->valor;
}


$oPdf->arraycodreceitas   = $arraycodreceitas;
$oPdf->arrayreduzreceitas = $arrayreduzreceitas;
$oPdf->arraydescrreceitas = $arraydescrreceitas;
$oPdf->arrayvalreceitas   = $arrayvalreceitas;

$oPdf->receita          = 'k00_receit';
$oPdf->receitared       = 'codreduz';
$oPdf->dreceita         = 'k02_drecei';
$oPdf->ddreceita        = 'k07_descr';
$oPdf->valor            = 'valor';
$oPdf->nrpri         = $sNumero;
$oPdf->nomepriimo    = $sLogradouro;
$oPdf->complpri      = $sComplemento;
$oPdf->bairropri     = $sBairro;

$oPdf->dtvenc = $dataVencimento;

$oPdf->linha_digitavel = $linhadigitavel;
$oPdf->codigo_barras   = $codigobarras;

$oPdf->descr6 = $datavencimento;  // Data de Vencimento
$oPdf->descr7 = db_formatar($oRecibo->getTotalRecibo(),'f');  // qtd de URM ou valor
$oPdf->descr9 = $oRecibo->getNumpreRecibo()."001"; //$numpre; // cod. de arrecadação

$oPdf->predescr6 = $datavencimento;  // Data de Vencimento
$oPdf->predescr7 = db_formatar($this->getValorDoacao(),'f');  // qtd de URM ou valor
$oPdf->predescr9 = $oRecibo->getNumpreRecibo()."001"; //$numpre; // cod. de arrecadação
/*************************************************************************************/
$oPdf->descr11_1           = $sNome;
$oPdf->descr11_2           = $sLogradouro .', ' . $sNumero; // Endereco
$oPdf->descr11_3           = $sMunicipio;
$oPdf->descr12_1           = ""; // Historico
$oPdf->descr14             = $datavencimento;
$oPdf->descr10             = "1 / 1";
$oPdf->data_processamento  = date('d/m/Y',db_getsession('DB_datausu'));
$oPdf->tipo_exerc          = "11 / ".date('Y',db_getsession('DB_datausu'));
$oPdf->especie             = "R$";
$oPdf->dtparapag           = $datavencimento; //date('d/m/Y',db_getsession('DB_datausu'));
$oPdf->ufcgm               = $oIdentificacao->sUF;


  $cldb_bancos = new cl_db_bancos;

  $rsConsultaBanco  = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
  $oBanco     = db_utils::fieldsMemory($rsConsultaBanco,0);
  $oPdf->numbanco   = $oBanco->db90_codban."-".$oBanco->db90_digban;
  $oPdf->banco      = $oBanco->db90_abrev;

  try {
    $oPdf->imagemlogo = $oConvenio->getImagemBanco();
  } catch (Exception $eExeption){
    db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
  }
  $pdf1->lUtilizaModeloDefault = false;
// FIM NOVO MODELO
      $oPdf->imprime();

      $sCaminhoPDF  = 'tmp/rp_boleto_portaldoacoes_';
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

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
 *
 * Classe para vнnculo das contas do plano de contas com o PCASP
 *
 * @author Trucolo trucolo@dbseller.com.br
 * @package contabilidade
 * @version $Revision: 1.2 $
 *
 */
class VinculoPlanoOrcamentoPcasp {

  /**
   * Ano de inнcio do processamento
   * @var integer
   */
  private $iAno = '';

  /**
   * Caminho do arquivo com a definiзгo dos vнnculos
   * @var string
   */
  private $sCaminhoArquivo = '';
  
  /**
   * instituicao que deverб ser feito o vinculo
   * @var Instituicao
   */
  protected $oInstituicao = null;

  /**
   * caminho do arquivo de mens
   * @var unknown
   */
  const CAMINHO_MENSAGEM = 'financeiro.contabilidade.VinculoPlanoOrcamentoPcasp.';

  /**
   * Cria uma nova instвncia da classe de vнnculo da conta PCASP.
   * @param integer $iAno ano de inнcio do processamento
   * @param string $sCaminhoArquivo caminho do arquivo para processamento
   * @param Instituicao $oInstituicao instituicao do processamento
   */
  public function __construct($iAno, $sCaminhoArquivo, Instituicao $oInstituicao) {

    if (!DBNumber::isInteger($iAno)) {
      throw new ParameterException(_M(VinculoPlanoOrcamentoPcasp::CAMINHO_MENSAGEM.'parametro_ano_invalido'));
    }

    if (!file_exists($sCaminhoArquivo)) {
      throw new ParameterException(_M(VinculoPlanoOrcamentoPcasp::CAMINHO_MENSAGEM.'arquivo_nao_encontrado'));
    }


    $this->iAno            = $iAno;
    $this->sCaminhoArquivo = $sCaminhoArquivo;
    $this->oInstituicao    = $oInstituicao;
    $this->validarArquivo();
  }

  /**
   * Retorna o ano do processamento
   * @return number
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Retorna o caminho do arquivo
   * @return string
   */
  public function getCaminhoArquivo() {
    return $this->sCaminhoArquivo;
  }

  /**
   * Valida se o arquivo й vбlido para o processamento
   * @throws FileException
   */
  protected function validarArquivo() {


    if (!is_readable($this->getCaminhoArquivo())) {
      throw new FileException(_M(VinculoPlanoOrcamentoPcasp::CAMINHO_MENSAGEM.'erro_abrir_arquivo'));
    }

    $rArquivo     = fopen($this->getCaminhoArquivo(), 'r');
    $aNomeArquivo = explode(".", $this->getCaminhoArquivo());
    $sExtensao    = $aNomeArquivo[count($aNomeArquivo) - 1];

    if (strtoupper($sExtensao) != 'TXT') {
      throw new FileException(_M(VinculoPlanoOrcamentoPcasp::CAMINHO_MENSAGEM.'extensao_arquivo_invalida'));
    }

    $sPrimeiraLinha = fgets($rArquivo);

    $aHeader = explode("|", $sPrimeiraLinha);

    if (count($aHeader) == 1) {

      $oParametrosMensagem = (object)array('caminho_arquivo' => $this->getCaminhoArquivo());
      throw new FileException(_M(VinculoPlanoOrcamentoPcasp::CAMINHO_MENSAGEM.'arquivo_vinculo_invalido',
                                 $oParametrosMensagem));
    }

    if (trim($aHeader[0]) != 'plano_orcamentario' || trim($aHeader[1]) != 'pcasp') {

      $oParametrosMensagem = (object)array('caminho_arquivo' => $this->getCaminhoArquivo());
      throw new FileException(_M(VinculoPlanoOrcamentoPcasp::CAMINHO_MENSAGEM.'arquivo_vinculo_invalido',
                                 $oParametrosMensagem));
    }
  }

  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Processa o arquivo
   */
  public function processar() {

    if (!$this->validarUsoPCASP()) {
      throw new BusinessException(_M(VinculoPlanoOrcamentoPcasp::CAMINHO_MENSAGEM.'pcasp_nao_ativo'));
    }
    $rArquivo = fopen($this->getCaminhoArquivo(), 'r');

    $iLinhas = 0;
    while (!feof($rArquivo)) {

      $sLinha   = fgets($rArquivo);
      $iLinhas += 1;
      if (trim($sLinha) == "" || $iLinhas == 1) {
        continue;
      }
      
      $aPartesArquivo            = explode("|", $sLinha);
      $sEstruturalContaOrcamento = trim($aPartesArquivo[0]);
      $sEstruturalContaPCasp     = trim($aPartesArquivo[1]);
      
      $oContaOrcamento = ContaOrcamentoRepository::getContaPorEstrutural(
                                                                         $sEstruturalContaOrcamento,
                                                                         $this->getAno(),
                                                                         $this->oInstituicao
                                                                        );

      /**
       * Caso nao tenha conta, ou a conta seja sintetica, nгo devemos vincular as contas;
       */
      if ($oContaOrcamento == false || count($oContaOrcamento->getContasReduzidas()) == 0) {
        continue;
      }
      
      $oContaVinculo = $oContaOrcamento->getPlanoContaPCASP();
      /**
       * Verificamos se a conta jб foi vinculada.
       */
      if (!empty($oContaVinculo)) {
        continue;
      }
      
      $oContaPCASP = ContaPlanoPCASPRepository::getContaPorEstrutural($sEstruturalContaPCasp,
                                                                      $this->getAno(),
                                                                      $this->oInstituicao
                                                                     );

      /**
       * conta do PCASP deve existir, e ser analitica
       */
      if (empty($oContaPCASP) || count($oContaPCASP->getContasReduzidas()) == 0) {
        continue;
      }

      $oContaOrcamento->setPlanoContaPCASP($oContaPCASP);
      $oContaOrcamento->salvar();
    }
  }
  
  /**
   * Valida se existe os cadastros bбsicos para a utilizaзгo do PCASp
   * para o uso do pcasp ser valido, a constatne USE_PCASP, deve ser true,
   * ter no minimo uma conta cadastrada no PCASP uma conta Cadastrada no plano Orcamentario
   */
  protected function validarUsoPCASP() {
    
    /**
     * Flag de controle desabilitada;
     */
    if (!USE_PCASP) {
      return false;
    }
    
    $sWhereAno    = "c60_anousu = {$this->getAno()}";
    $oDaoConplano = new cl_conplano();
    
    $sSqlContaPCASP = $oDaoConplano->sql_query_file(null, null, '*', 'c60_codcon limit 1', $sWhereAno);
    $rsPlanoPCASP   = $oDaoConplano->sql_record($sSqlContaPCASP);
    if (!$rsPlanoPCASP) {
      throw new BusinessException(_M(VinculoPlanoOrcamentoPcasp::CAMINHO_MENSAGEM.'nao_foi_possivel_verificar_pcasp'));
    }
    
    if ($oDaoConplano->numrows == 0) {
      return false;
    }
    
    $oDaoPlanoOrcamentario = new cl_conplanoorcamento();
    $sSqlPlanoOrcamento    = $oDaoPlanoOrcamentario->sql_query_file(null, null, '*', 'c60_codcon limit 1', $sWhereAno);
    $rsPlanoOrcamentario   = $oDaoPlanoOrcamentario->sql_record($sSqlPlanoOrcamento);
    if (!$rsPlanoOrcamentario) {
      
      throw new BusinessException(_M(VinculoPlanoOrcamentoPcasp::CAMINHO_MENSAGEM.
                                    'nao_foi_possivel_verificar_orcamento')
                                 );
    }
    if ($oDaoPlanoOrcamentario->numrows == 0) {
      return false;
    }
    return true;
  }
}
?>
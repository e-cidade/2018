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


namespace ECidade\Tributario\Integracao\JuntaComercial;


use ECidade\Tributario\Integracao\JuntaComercial\Model\Protocolo;
use ECidade\Tributario\Integracao\JuntaComercial\Model\Retorno;
use ECidade\Tributario\Integracao\JuntaComercial\Processamento\Cgm as ProcessaCgm;
use ECidade\Tributario\Integracao\JuntaComercial\Processamento\Empresa as ProcessaEmpresa;
use ECidade\Tributario\Integracao\JuntaComercial\Processamento\QSA as ProcessaQSA;

/**
 * Class InstituicaoService
 * @package ECidade\Tributario\Integracao\JuntaComercial
 */
class InstituicaoService
{
  /**
   * @var Retorno
   */
  private $oRetorno;

  /**
   * @var Repository $oRepository
   */
  private $oRepository;

  /**
   * @var Regin $oRegin
   */
  private $oRegin;

  public function recebeDados($servico, $funcao, $protocolo, $xml, $data, $cnpjInstituicaoEnvia, $cnpjInstituicaoRecebe, $cpfCnpjProcesso)
  {
    try {

      $this->oRetorno = new Retorno();
      $this->oRetorno->setProtocolo($protocolo);
      $this->oRetorno->setDataGeracao(new \DateTime($data));

      $oInstituicao = new \Instituicao(db_getsession("DB_instit"));

      $this->oRetorno->setCNPJInstituicao($oInstituicao->getCNPJ());

      $this->oRepository  = new Repository();
      $this->oRegin       = new Regin($xml);

      db_inicio_transacao();

      $this->salvaProtocolo($servico,$funcao,$protocolo,$xml,$data,$cnpjInstituicaoEnvia,$cnpjInstituicaoRecebe,$cpfCnpjProcesso);

      switch ($funcao) {
        default:
          $this->salvaCgm();
          break;
      }

      db_fim_transacao();

    } catch (\Exception $erro) {
      db_fim_transacao(true);
      $this->oRetorno->setDescricaoErro(utf8_encode($erro->getMessage()));
    }

    $this->oRetorno->organizaRetorno();
    return $this->oRetorno->getXmlRetorno()->asXML();
  }

  /**
   * Salva os dados do CGM de Sócios
   */
  private function salvaCgm()
  {
    $processaCgm = new ProcessaCgm($this->oRegin);
    $processaCgm->addGrupo(Regin::SOCIO);
    $processaCgm->addGrupo(Regin::ENDERECO_SOCIO);
    $processaCgm->setTipoEmpresa(null);
    $processaCgm->processar();

    $cgms = $processaCgm->getCgm();

    $processaQSA = new ProcessaQSA($this->oRegin);

    foreach ($cgms as $cgmEmpresa) {
      $processaQSA->addCgm($cgmEmpresa);
    }

    $processaQSA->addGrupo(Regin::SOCIO);
    $processaQSA->processar();

    $qsas = $processaQSA->getQsa();

    $codigos = array();

    $processaCgm = new ProcessaCgm($this->oRegin);
    $processaCgm->addGrupo(Regin::EMPRESA);
    $processaCgm->addGrupo(Regin::ENDERECO_CGM);
    $processaCgm->setTipoEmpresa(Regin::TIPO_EMPRESA_JURIDICA);
    $processaCgm->processar();

    $cgms = $processaCgm->getCgm();
    foreach ($cgms as $cgmEmpresa) {

      $codigos[] = $this->oRepository->persistCgm($cgmEmpresa);
      $processaEmpresa = new ProcessaEmpresa($this->oRegin);
      $processaEmpresa->setCgm($cgmEmpresa);
      $empresa = $processaEmpresa->getEmpresa();

      foreach ($qsas as $qsa) {
        $empresa->adicionarQsa($qsa);
      }

      $this->oRepository->persistEmpresa($empresa);

      $oAlvara = new \Alvara(null);
      $oAlvara->setEmpresa($empresa);
      $oAlvara->setDataInclusao(new \DBDate(date("Y-m-d")));
      $oAlvara->setTipoAlvara(1);
      $oAlvara->setSituacao(\Alvara::ATIVO);
      $oAlvara->setGeradoAltomatico(true);
      $oAlvara->setUsuario(new \UsuarioSistema(db_getsession("DB_id_usuario")));

      $aLicencas = $this->oRepository->persistAlvara($oAlvara);

      $this->oRetorno->setLicencas($aLicencas);
    }
  }

  /**
   * Salva os dados da requisição do servidor SOAP
   * @param $servico
   * @param $funcao
   * @param $protocolo
   * @param $xml
   * @param $data
   * @param $cnpjInstituicaoEnvia
   * @param $cnpjInstituicaoRecebe
   * @param $cpfCnpjProcesso
   */
  private function salvaProtocolo($servico, $funcao, $protocolo, $xml, $data, $cnpjInstituicaoEnvia, $cnpjInstituicaoRecebe, $cpfCnpjProcesso)
  {
    $oProtocolo = new Protocolo();
    $oProtocolo->setServico($servico);
    $oProtocolo->setFuncao($funcao);
    $oProtocolo->setProtocolo($protocolo);
    $oProtocolo->setXml($xml);
    $oProtocolo->setData(new \Datetime($data));
    $oProtocolo->setCNPJEmissor($cnpjInstituicaoEnvia);
    $oProtocolo->setCNPJReceptor($cnpjInstituicaoRecebe);
    $oProtocolo->setCPFCNPJProcesso($cpfCnpjProcesso);
    $oProtocolo->setEventos($this->oRegin->getEventos());

    $this->oRepository->persistCabecalho($oProtocolo);
  }

}

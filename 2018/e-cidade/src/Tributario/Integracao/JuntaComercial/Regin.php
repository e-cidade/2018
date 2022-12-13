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


use ECidade\Tributario\Integracao\JuntaComercial\Model\Evento;

class Regin implements Dicionario
{
  const TIPO_EMPRESA_FISICA = "31";
  const TIPO_EMPRESA_JURIDICA = "36";

  const EMPRESA = "RUC_GENERAL";
  const ENDERECO_CGM = "RUC_COMP";
  const ENDERECO_INSCRICAO = "RUC_ESTAB";
  const SOCIO = "RUC_RELAT_PROF";
  const ENDERECO_SOCIO = "RUC_PROF";
  const ATIVIDADE = "RUC_ACTV_ECON";
  const INFO_COMPLEMENTAR = "RUC_GEN_PROTOCOLO";
  const EVENTO = "PSC_PROT_EVENTO_RFB";
  const PROTOCOLO = "PSC_PROTOCOLO";

  private $pai = array(
    self::ENDERECO_SOCIO => "GROUPRUC_PROF",
    self::SOCIO => "GROUPRUC_RELAT_PROF",
    self::ATIVIDADE => "GROUPRUC_ACTV_ECON",
    self::INFO_COMPLEMENTAR => "GROUPRUC_GEN_PROTOCOLO",
    self::EVENTO => "GROUPPSC_PROT_EVENTO_RFB",
  );

  private $campos = array(
    self::EMPRESA =>  array( //empresa
      "cpfcnpj" => "RGE_CGC_CPF",
      "protocolo" => "RGE_PRA_PROTOCOLO",
      "inscricao_estadual" => "RGE_RUC",
      "tipo_empresa" => "RGE_TGE_VTIP_REG",
      "tipo_pessoa" => "RGE_TGE_VTIP_PERS",
      "porte" => "RGE_TGE_VTAMANHO",
      "data_cadastro" => "RGE_FEC_INI_ACT_EC",
      "data_junta" => "RGE_FEC_SIT_CAD",
      "origem_atualizacao" => "RGE_TGE_VORIG_ACTU",
      "razao_social" => "RGE_NOMB",
      "tipo_inscricao" => "RGE_TGE_VTIP_INSC",
      "codigo_municipio" => "RGE_CODG_MUN",
      "uf" => "RGE_TUF_COD_UF"
    ),
    self::ENDERECO_CGM =>  array( //endereço cgm
      "protocolo" => "RCO_RGE_PRA_PROTOCOLO",
      "nire" => "RCO_NUM_REG_MERC", //cadastro cgm e cadastro inscrição
      "numero" => "RCO_NUME",
      "codigo_pais" => "RCO_TGE_VPAIS",
      "complemento" => "RCO_IDENT_COMP",
      "tipo_logradouro" => "RCO_TTL_TIP_LOGRADORO",
      "logradouro" => "RCO_DIRECCION",
      "bairro" => "RCO_URBANIZACION",
      "uf" => "RCO_TES_COD_ESTADO",
      "cep" => "RCO_ZONA_POSTAL",
      "codigo_municipio" => "RCO_TMU_COD_MUN",
    ),
    self::ENDERECO_INSCRICAO =>  array( //endereço inscrição
      "protocolo" => "RES_RGE_PRA_PROTOCOLO",
      "tipo_estabelecimento" => "RES_TIP_ESTAB",
      "area" => "RES_AREA",
      "numero" => "RES_NUME",
      "complemento" => "RES_IDENT_COMP",
      "tipo_logradouro" => "RES_TTL_TIP_LOGRADORO",
      "logradouro" => "RES_DIRECCION",
      "bairro" => "RES_URBANIZACION",
      "uf" => "RES_TES_COD_ESTADO",
      "cep" => "RES_ZONA_POSTAL",
      "codigo_municipio" => "RES_TMU_COD_MUN",
    ),
    self::SOCIO =>  array(
      "protocolo" => "RRP_RGE_PRA_PROTOCOLO",
      "identificador" => "RRP_CGC_CPF_SECD",
      "cpfcnpj" => "RRP_CGC_CPF_SECD",
      "tipo_relacionamento" => "RRP_TGE_VTIP_RELAC",
      "data_atualizacao" => "RRP_FEC_ACTL",
      "valor_capital" => "RRP_PORC_PART"
    ),
    self::ENDERECO_SOCIO =>  array(
      "protocolo" => "RPR_RGE_PRA_PROTOCOLO",
      "identificador" => "RPR_CGC_CPF_SECD",
      "tipo_documento" => "RPR_TGE_VTIP_DOC",
      "numero_documento" => "RPR_NUM_DOC_IDENT",
      "numero" => "RPR_NUME",
      "codigo_pais" => "RPR_TGE_VPAIS",
      "complemento" => "RPR_IDENT_COMP",
      "tipo_logradouro" => "RPR_TTL_TIP_LOGRADORO",
      "logradouro" => "RPR_DIRECCION",
      "bairro" => "RPR_URBANIZACION",
      "uf" => "RPR_TES_COD_ESTADO",
      "codigo_municipio" => "RPR_TMU_COD_MUN",
      "cpfcnpj" => "RPR_CGC_CPF_SECD",
      "tipo_pessoa" => "RPR_TGE_VTIP_PERS",
      "razao_social" => "RPR_NOMB",
    ),
    self::ATIVIDADE =>  array( // CNAE
      "protocolo" => "RAE_RGE_PRA_PROTOCOLO",
      "codigo" => "RAE_TAE_COD_ACTVD",
      "tipo_atividade" => "RAE_CALIF_ACTV",
      "data_inicio" => "RAE_FEC_ACTL"
    ),
    self::INFO_COMPLEMENTAR =>  array( // mariana vai ver (possível matrícula)
      "protocolo" => "RGP_RGE_PRA_PROTOCOLO",
      "chave" => "RGP_TGE_COD_TIP_TAB",
      "valor" => "RGP_VALOR"
    ),
    self::PROTOCOLO =>  array(
      "nire" => "NIRE",
      "cpfcnpj" => "CNPJ",
      "inscricao_municipal" => "INCRICAOMUNICIPAL",
      "tipo_acao" => "PRO_TGE_VGACAO",
    ),
    self::EVENTO =>  array(
      "protocolo" => "PEV_PRO_PROTOCOLO",
      "codigo_evento" => "PEV_COD_EVENTO",
      "nome_evento" => "PEV_NOME_EVENTO",
    )
  );

  private $xml;

  private $grupoAtual;
  private $nomeGrupoAtual;
  private $posicaoAtual;

  public function __construct($xml)
  {

    try{
      $this->xml = simplexml_load_string($xml);
    } catch (\Exception $exception) {
      throw new \Exception("XML com formato inválido");
    }

    $this->grupoAtual = null;
    $this->posicaoAtual = null;
    $this->nomeGrupoAtual = null;
  }

  public function selecionarGrupo($grupo)
  {
    $this->grupoAtual = $this->getGrupo($grupo);
    $this->nomeGrupoAtual = $grupo;
    $this->posicaoAtual = null;
  }

  protected function getTamanhoGrupo()
  {
    if ( is_null($this->grupoAtual) ) {
      throw new \BusinessException("O Grupo ainda não foi selecionado para poder saber o seu tamanho.");
    }

    return count($this->grupoAtual);
  }

  public function next($debug = false)
  {
    if ( is_null($this->grupoAtual) ) {
      return false;
    }

    if ( is_null($this->posicaoAtual) ) {
      $this->posicaoAtual = -1;
    }

    $this->posicaoAtual++;

    if ($this->posicaoAtual >= $this->getTamanhoGrupo()) {
      return false;
    }

    if ( is_null($this->grupoAtual[$this->posicaoAtual])) {
      return false;
    }

    return true;
  }

  public function key()
  {
    return $this->posicaoAtual;
  }

  protected function getGrupo($grupo)
  {
    if ($this->temPai($grupo)) {
      $nomePai  = $this->pai[$grupo];
      $grupoPai = (array) $this->xml->ROWSET->$nomePai;

      if (isset($grupoPai[$grupo]) && !is_array($grupoPai[$grupo])) {
        return array($grupoPai[$grupo]);
      }

      return  $grupoPai[$grupo];
    }

    return isset($this->xml->ROWSET->$grupo) ? array($this->xml->ROWSET->$grupo) : array();
  }

  public function getCampo($campo)
  {
    if ( is_null($this->nomeGrupoAtual) ) {
      throw new \BusinessException("O Grupo ainda não foi selecionado para buscar um de seus campos.");
    }

    if ( !isset($this->campos[$this->nomeGrupoAtual][$campo]) || is_null($this->campos[$this->nomeGrupoAtual][$campo]) ) {
      return null;
    }

    $atributo = $this->campos[$this->nomeGrupoAtual][$campo];

    if ( ! isset($this->grupoAtual[$this->posicaoAtual]->$atributo) || is_null($this->grupoAtual[$this->posicaoAtual]->$atributo) ) {
      return null;
    }
    
    $valor = $this->grupoAtual[$this->posicaoAtual]->$atributo;

    return $valor->__toString();
  }

  public function getEventos()
  {
    $aEventos = array();
    $this->selecionarGrupo(Regin::EVENTO);

    while ($this->next()) {
      $iCodigoEvento    = $this->getCampo("codigo_evento");
      $sDescricaoEvento = $this->getCampo("nome_evento");

      $aEventos[] = new Evento($iCodigoEvento, utf8_decode($sDescricaoEvento));
    }

    return $aEventos;
  }

  public function temPai($grupo){
    return array_key_exists($grupo, $this->pai);
  }

  public function getDadosGrupo($sGrupo)
  {
    $aDados = array();
    $oDado  = new \stdClass();

    $this->selecionarGrupo($sGrupo);

    if($this->temPai($sGrupo)){

      while ($this->next($sGrupo)) {
        $oDado  = new \stdClass();
        foreach ($this->campos[$sGrupo] as $key => $campos) {
          $oDado->$key = trim($this->getCampo($key));
        }
        $aDados[] = $oDado;
      }
      return $aDados;
    }

    while ($this->next($sGrupo)) {
      foreach ($this->campos[$sGrupo] as $key => $campos) {
        $oDado->$key = trim($this->getCampo($key));
      }
    }

    return $oDado;
  }

}

<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
 * Classe repository para manutenção dos registros dos consignados
 */
abstract class RegistroConsignadoRepository {

  const ORDEM_MAIOR_VALOR_SERVIDOR = '1';

  public static $aRegistros = array();

  public static function add(RegistroConsignado $oRegistroConsignado) {
    self::$aRegistros[$oRegistroConsignado->getCodigo()] = $oRegistroConsignado;
  }

  public static function remove(RegistroConsignado $oRegistroConsignado) {
    unset(self::$aRegistros[$oRegistroConsignado->getCodigo()]);
  }

  /**
   * Salvas os dados do Registro Consignado na base de dados.
   * @param  RegistroConsignado $oRegistroConsignado
   * @throws \DBException
   */
  public static function persist(RegistroConsignado $oRegistroConsignado) {

    $oDaoConsignadoMovimentoServidor = new cl_rhconsignadomovimentoservidor();
    $oDaoConsignadoMovimentoServidor->rh152_sequencial          = $oRegistroConsignado->getCodigo();
    $oDaoConsignadoMovimentoServidor->rh152_consignadomovimento = $oRegistroConsignado->getArquivoConsignado()->getCodigo();
    $oDaoConsignadoMovimentoServidor->rh152_regist              = $oRegistroConsignado->getMatricula();
    $oDaoConsignadoMovimentoServidor->rh152_nome                = $oRegistroConsignado->getNome();
    $oDaoConsignadoMovimentoServidor->rh152_consignadomotivo    = ($oRegistroConsignado->getMotivo() === null) ? 'null' : $oRegistroConsignado->getMotivo();

    if ($oRegistroConsignado->getCodigo() === null) {

      $oDaoConsignadoMovimentoServidor->incluir(null);
      $oRegistroConsignado->setCodigo($oDaoConsignadoMovimentoServidor->rh152_sequencial);
    } else {
      $oDaoConsignadoMovimentoServidor->alterar($oRegistroConsignado->getCodigo());
    }

    if ($oDaoConsignadoMovimentoServidor->erro_status == '0') {
      throw new DBException("Ocorreu um erro ao persistir os dados.");
    }

    $oDaoConsignadoMovimentoServidorRubrica = new cl_rhconsignadomovimentoservidorrubrica();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_sequencial                  = $oRegistroConsignado->getCodigoMovimento();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_consignadomovimentoservidor = $oRegistroConsignado->getCodigo();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_rubrica                     = $oRegistroConsignado->getRubrica()->getCodigo();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_instit                      = $oRegistroConsignado->getInstituicao()->getCodigo();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_valordescontar              = $oRegistroConsignado->getValorDescontar();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_valordescontado             = $oRegistroConsignado->getValorDescontado();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_parcela                     = $oRegistroConsignado->getParcela();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_totalparcelas               = $oRegistroConsignado->getTotalParcelas();

    if ($oRegistroConsignado->getCodigoMovimento() === null) {

      $oDaoConsignadoMovimentoServidorRubrica->incluir(null);
      $oRegistroConsignado->setCodigoMovimento($oDaoConsignadoMovimentoServidorRubrica->rh153_sequencial);
    } else {
      $oDaoConsignadoMovimentoServidorRubrica->alterar($oRegistroConsignado->getCodigo());
    }

    if ($oDaoConsignadoMovimentoServidorRubrica->erro_status == '0') {
      throw new DBException("Ocorreu um erro ao persistir os dados.");
    }
  }

  /**
   * Retorna os Registros dos consignados pela matricula do servidor
   * @param  integer           $iMatricula Matricula do Servidor
   * @param  ArquivoConsignado $oConsignadoMovimento Arquivo que está sendo processado
   * @return \RegistroConsignado[] $aRegistrosConsignados Array com os registros,
   * @throws \BusinessException
   * @throws \DBException
   */
  public static function getRegistroByMatricula($iMatricula, ArquivoConsignado $oConsignadoMovimento) {

    $oDaoConsignadoMovimentoServidor = new cl_rhconsignadomovimentoservidor();

    $sWhereConsignadoMovimentoServidor  = "    rh152_regist = '$iMatricula' ";
    $sWhereConsignadoMovimentoServidor .= "and rh152_consignadomovimento = {$oConsignadoMovimento->getCodigo()}";
    $sWhereConsignadoMovimentoServidor .= "and rh151_tipoconsignado      = '".ArquivoConsignado::TIPO_ARQUIVO."'";

    $sSqlConsignadosServidor = $oDaoConsignadoMovimentoServidor->sql_query(null, 'rhconsignadomovimentoservidor.*, rhconsignadomovimentoservidorrubrica.*', null, $sWhereConsignadoMovimentoServidor);
    $rsConsignadoServidor    = db_query($sSqlConsignadosServidor);

    if (!$rsConsignadoServidor) {
      throw new DBException("Erro ao consultar registros.");
    }

    if (pg_num_rows($rsConsignadoServidor) == 0) {
      throw new BusinessException("Não foi encontrado o servidor para o arquivo de consignados.");
    }

    
    $aRegistrosConsignados = db_utils::makeCollectionFromRecord($rsConsignadoServidor, function($oDadosRegistroConsignado) {

      $oRegistroConsignado = RegistroConsignadoRepository::make($oDadosRegistroConsignado);
      RegistroConsignadoRepository::add($oRegistroConsignado);

      return $oRegistroConsignado;
    });
    
    return $aRegistrosConsignados;
  }

  /**
   * Retorna os dados do registro pelo sequencial da tabela
   * @param  integer $iSequencial sequencial da tabela rhconsignadomovimentoservidor
   * @return \RegistroConsignado $oRegistroConsignado.
   * @throws \BusinessException
   * @throws \DBException
   */
  public static function getRegistroByCodigo($iSequencial) {

    if (isset(self::$aRegistros[$iSequencial])) {
      return self::$aRegistros[$iSequencial];
    }

    $oDaoConsignadoMovimentoServidor    = new cl_rhconsignadomovimentoservidor();
    $sWhereConsignadoMovimentoServidor  = "rh152_sequencial = $iSequencial";

    $sSqlConsignadosServidor = $oDaoConsignadoMovimentoServidor->sql_query(null, 'rhconsignadomovimentoservidor.*, rhconsignadomovimentoservidorrubrica.*', null, $sWhereConsignadoMovimentoServidor);
    $rsConsignadoServidor    = db_query($sSqlConsignadosServidor);

    if (!$rsConsignadoServidor) {
      throw new DBException("Erro ao consultar registros.");
    }

    if (pg_num_rows($rsConsignadoServidor) == 0) {
      throw new BusinessException("Nenhum registro encontrado.");
    }

    $oRegistroConsignado = db_utils::makeFromRecord($rsConsignadoServidor, function($oDadosRegistroConsignado) {

      $oRegistroConsignado = RegistroConsignadoRepository::make($oDadosRegistroConsignado);
      RegistroConsignadoRepository::add($oRegistroConsignado);

      return $oRegistroConsignado;
    });
    
    return $oRegistroConsignado;
  }

  /**
   * Retorna uma instância do Registro do Consignado
   * @param   $oDadosRegistroConsignado 
   * @return RegistroConsignado $oRegistroConsignado
   */
  public static function make($oDadosRegistroConsignado) {

    $oRubrica      = RubricaRepository::getInstanciaByCodigo($oDadosRegistroConsignado->rh153_rubrica);
    $oInstituicao  = InstituicaoRepository::getInstituicaoByCodigo($oDadosRegistroConsignado->rh153_instit);

    $oRegistroConsignado = new RegistroConsignado();
    $oRegistroConsignado->setCodigo($oDadosRegistroConsignado->rh152_sequencial);
    $oRegistroConsignado->setCodigoMovimento($oDadosRegistroConsignado->rh153_sequencial);
    $oRegistroConsignado->setArquivoConsignado(ArquivoConsignadoRepository::getByCodigo($oDadosRegistroConsignado->rh152_consignadomovimento));
    $oRegistroConsignado->setMatricula($oDadosRegistroConsignado->rh152_regist);
    $oRegistroConsignado->setNome($oDadosRegistroConsignado->rh152_nome);
    $oRegistroConsignado->setMotivo($oDadosRegistroConsignado->rh152_consignadomotivo);
    $oRegistroConsignado->setRubrica($oRubrica);
    $oRegistroConsignado->setInstituicao($oInstituicao);
    $oRegistroConsignado->setValorDescontar($oDadosRegistroConsignado->rh153_valordescontar);
    $oRegistroConsignado->setValorDescontado($oDadosRegistroConsignado->rh153_valordescontado);
    $oRegistroConsignado->setParcela($oDadosRegistroConsignado->rh153_parcela);
    $oRegistroConsignado->setTotalParcelas($oDadosRegistroConsignado->rh153_totalparcelas);
    try {

      $oServidor     = ServidorRepository::getInstanciaByCodigo($oDadosRegistroConsignado->rh152_regist);
      $oRegistroConsignado->setServidor($oServidor);
    } catch (Exception $e) {

    }
    return $oRegistroConsignado;
  }

  /**
   * Retorna os dados do registro pelo sequencial da tabela
   * @param \ArquivoConsignado $oArquivo
   * @return \RegistroConsignado[] $oRegistroConsignado.
   * @throws \BusinessException
   * @throws \DBException
   */
  public static function getRegistrosDoArquivo(ArquivoConsignado $oArquivo, $sOrdenacao = null) {

    switch ($sOrdenacao) {
      case RegistroConsignadoRepository::ORDEM_MAIOR_VALOR_SERVIDOR:
        $sOrdem = 'rh152_regist , rh153_valordescontar::numeric desc';
      break;
      default:
        $sOrdem = 'rh152_sequencial asc';
      break;
    }
    $oDaoConsignadoMovimentoServidor   = new cl_rhconsignadomovimentoservidor();
    $sWhereConsignadoMovimentoServidor = "rh152_consignadomovimento = ".$oArquivo->getCodigo();
    $sWhereConsignadoMovimentoServidor .= " and rh151_tipoconsignado = '".ArquivoConsignadoManual::TIPO_ARQUIVO."'";
    if ($oArquivo->getBanco() != '') {
      $sWhereConsignadoMovimentoServidor .= " and rh151_banco = '{$oArquivo->getBanco()->getCodigo()}'";
    }

    $sSqlConsignadosServidor = $oDaoConsignadoMovimentoServidor->sql_query(null, 'rhconsignadomovimentoservidor.*, rhconsignadomovimentoservidorrubrica.*', $sOrdem, $sWhereConsignadoMovimentoServidor);
    $rsConsignadoServidor    = db_query($sSqlConsignadosServidor);

    if (!$rsConsignadoServidor) {
      throw new DBException("Erro ao consultar registros.");
    }

    if (pg_num_rows($rsConsignadoServidor) == 0) {
      throw new BusinessException("Nenhum registro encontrado.");
    }

    $aRegistroConsignado = db_utils::makeCollectionFromRecord($rsConsignadoServidor, function($oDadosRegistroConsignado) {

      $oRegistroConsignado = RegistroConsignadoRepository::make($oDadosRegistroConsignado);
      RegistroConsignadoRepository::add($oRegistroConsignado);
      return $oRegistroConsignado;
    });

    return $aRegistroConsignado;
  }

}
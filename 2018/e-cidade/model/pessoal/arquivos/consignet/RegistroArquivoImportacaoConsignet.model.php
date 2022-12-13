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
 * Classe que representa um registro do ponto na consignet
 *
 * @package folha
 * @author  Renan Silva <renan.silva@dbseller.com.br>
 */
class RegistroArquivoImportacaoConsignet extends RegistroArquivoImportacao {

  /**
   * Método que persiste um registro de consignação na base de dados
   */
  public function persist(RegistroArquivoImportacaoConsignet $oRegistro = null){

    if ( $oRegistro === null ){
      $oRegistro = $this;
    }

    if ( $oRegistro->getArquivo() == '' || $oRegistro->getArquivo() == null ){
      
      $oDaoConsignadoMovimento   = new cl_rhconsignadomovimento();
      $sWhereConsignadoMovimento = " rh151_sequencial = {$oRegistro->getCodigoArquivo()}";
      $sSqlConsignadoMovimento   = $oDaoConsignadoMovimento->sql_query_file(null, "*", null, $sWhereConsignadoMovimento);
      $rsConsignadoMovimento     = db_query($sSqlConsignadoMovimento);
  
      if( pg_numrows($rsConsignadoMovimento) > 0 ){

        $oConsignadoMovimento = db_utils::fieldsMemory($rsConsignadoMovimento, 0);
        
        $oArquivo = new ArquivoConsignet();
        $oArquivo->setCodigo($oConsignadoMovimento->rh151_sequencial);
        $oArquivo->setCompetencia(new DBCompetencia ($oConsignadoMovimento->rh151_ano, $oConsignadoMovimento->rh151_mes));
        $oArquivo->setInstituicao(new Instituicao($oConsignadoMovimento->rh151_instit));
        $oArquivo->setNome($oConsignadoMovimento->rh151_nomearquivo);
        $oArquivo->setRelatorio($oConsignadoMovimento->rh151_relatorio);
        $oArquivo->setProcessado($oConsignadoMovimento->rh151_processado);
        
        $oRegistro->setArquivo($oArquivo);

      } else {
        throw new DBException(_M(self::MENSAGEM ."erro_arquivo_inexistente"));
      }      
    }

    $oRegistro->setCodigoArquivo($oRegistro->getArquivo()->getCodigo());

    $oDaoConsignadoMovimentoServidor   = new cl_rhconsignadomovimentoservidor();

    $oDaoConsignadoMovimentoServidor->rh152_sequencial          = $oRegistro->getSequencialMovimentoServidor();
    $oDaoConsignadoMovimentoServidor->rh152_consignadomovimento = $oRegistro->getCodigoArquivo();
    $oDaoConsignadoMovimentoServidor->rh152_regist              = $oRegistro->getMatricula();
    $oDaoConsignadoMovimentoServidor->rh152_nome                = $oRegistro->getNome();
    $oDaoConsignadoMovimentoServidor->rh152_consignadomotivo    = $oRegistro->getMotivo();
    


    if ( $oDaoConsignadoMovimentoServidor->rh152_sequencial == null ) {
      $oDaoConsignadoMovimentoServidor->incluir(null);
    } else {

      $sWhereConsignadoMovimentoServidor = " rh152_sequencial = {$oDaoConsignadoMovimentoServidor->rh152_sequencial}";
      $sSqlConsignadoMovimentoServidor   = $oDaoConsignadoMovimentoServidor->sql_query_file(null, "*", null, $sWhereConsignadoMovimentoServidor);
      $rsConsignadoMovimentoServidor     = db_query($sSqlConsignadoMovimentoServidor);
      
      if( !$rsConsignadoMovimentoServidor || pg_num_rows($rsConsignadoMovimentoServidor) == 0 ){
        throw new DBException(_M(self::MENSAGEM."erro_incluir_servidor"));
      }
      
      $oConsignadoMovimentoServidor = db_utils::fieldsMemory($rsConsignadoMovimentoServidor, 0);
      $oDaoConsignadoMovimentoServidor->rh152_sequencial = $oConsignadoMovimentoServidor->rh152_sequencial;
      $oDaoConsignadoMovimentoServidor->alterar($oDaoConsignadoMovimentoServidor->rh152_sequencial);

    }
    
    if ($oDaoConsignadoMovimentoServidor->erro_status == "0") {
      throw new DBException(_M(self::MENSAGEM."erro_incluir_servidor"));
    }


    $oDaoConsignadoMovimentoServidorRubrica   = new cl_rhconsignadomovimentoservidorrubrica();

    $oDaoConsignadoMovimentoServidorRubrica->rh153_sequencial                  = $oRegistro->getSequencialMovimentoServidorRubrica();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_consignadomovimentoservidor = $oDaoConsignadoMovimentoServidor->rh152_sequencial;
    $oDaoConsignadoMovimentoServidorRubrica->rh153_rubrica                     = $oRegistro->getRubric();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_instit                      = $oRegistro->getArquivo()->getInstituicao()->getSequencial();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_valordescontar              = $oRegistro->getValorParcela();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_valordescontado             = $oRegistro->getValorDescontado();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_parcela                     = $oRegistro->getParcela();
    $oDaoConsignadoMovimentoServidorRubrica->rh153_totalparcelas               = $oRegistro->getTotalParcelas();
    

    if ( $oDaoConsignadoMovimentoServidorRubrica->rh153_sequencial == null ) {
      $oDaoConsignadoMovimentoServidorRubrica->incluir(null);
    } else {

      $sWhereConsignadoMovimentoServidorRubrica = " rh153_sequencial = {$oDaoConsignadoMovimentoServidorRubrica->rh153_sequencial}";
      $sSqlConsignadoMovimentoServidorRubrica   = $oDaoConsignadoMovimentoServidorRubrica->sql_query_file(null, "*", null, $sWhereConsignadoMovimentoServidorRubrica);
      $rsConsignadoMovimentoServidorRubrica     = db_query($sSqlConsignadoMovimentoServidorRubrica);

      if( !$rsConsignadoMovimentoServidorRubrica || pg_num_rows($rsConsignadoMovimentoServidorRubrica) == 0 ){
        throw new DBException(_M(self::MENSAGEM."erro_incluir_rubrica"));
      }

      $oConsignadoMovimentoServidorRubrica = db_utils::fieldsMemory($rsConsignadoMovimentoServidorRubrica, 0);
      $oDaoConsignadoMovimentoServidorRubrica->rh153_sequencial = $oConsignadoMovimentoServidorRubrica->rh153_sequencial;
      $oDaoConsignadoMovimentoServidorRubrica->alterar($oDaoConsignadoMovimentoServidorRubrica->rh153_sequencial);

    }

    if ($oDaoConsignadoMovimentoServidorRubrica->erro_status == "0") {
      throw new DBException(_M(self::MENSAGEM."erro_incluir_rubrica"));
    }

    $oRegistroSalvo = new RegistroArquivoImportacaoConsignet();

    $oRegistroSalvo->setCodigoArquivo($oRegistro->getCodigoArquivo());
    $oRegistroSalvo->setArquivo($oRegistro->getArquivo());
    $oRegistroSalvo->setMatricula($oDaoConsignadoMovimentoServidor->rh152_regist);
    $oRegistroSalvo->setNome($oDaoConsignadoMovimentoServidor->rh152_nome);
    $oRegistroSalvo->setMotivo($oDaoConsignadoMovimentoServidor->rh152_consignadomotivo);
    $oRegistroSalvo->setRubric($oDaoConsignadoMovimentoServidorRubrica->rh153_rubrica);
    $oRegistroSalvo->setValorParcela($oDaoConsignadoMovimentoServidorRubrica->rh153_valordescontar);
    $oRegistroSalvo->setValorDescontado($oDaoConsignadoMovimentoServidorRubrica->rh153_valordescontado);
    $oRegistroSalvo->setParcela($oDaoConsignadoMovimentoServidorRubrica->rh153_parcela);
    $oRegistroSalvo->setTotalParcelas($oDaoConsignadoMovimentoServidorRubrica->rh153_totalparcelas);

    try {
      $oServidor = ServidorRepository::getInstanciaByCodigo($oRegistroSalvo->getMatricula(),
                                                            $oRegistroSalvo->getArquivo()->getCompetencia()->getAno(),
                                                            $oRegistroSalvo->getArquivo()->getCompetencia()->getMes(),
                                                            $oRegistroSalvo->getArquivo()->getInstituicao()->getSequencial()
                                                            );

    } catch ( BusinessException $eException ) {

      $oServidor = new Servidor();
      $oServidor->setMatricula( $oRegistroSalvo->getMatricula() );
      $oServidor->setCodigoInstituicao($oRegistroSalvo->getArquivo()->getInstituicao()->getSequencial());
    }
    
    try { 
      $oRubrica = RubricaRepository::getInstanciaByCodigo($oRegistroSalvo->getRubric(), 
                                                          $oRegistroSalvo->getArquivo()->getInstituicao()->getSequencial());
    } catch ( BusinessException $eException ) {

      $oRubrica = new Rubrica();
      $oRubrica->setCodigo( $oRegistroSalvo->getRubric() );
      $oRubrica->setInstituicao( $oRegistroSalvo->getArquivo()->getInstituicao()->getSequencial() );
    }
    
    $oRegistroSalvo->setServidor($oServidor);
    $oRegistroSalvo->setRubrica($oRubrica);

    return $oRegistroSalvo;
  }

}

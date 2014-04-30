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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_libcontabilidade.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$iInstituicao        = db_getsession("DB_instit");
$iAno                = db_getsession("DB_anousu");
$sCaminhoMensagem    = "configuracao.configuracao.con4_processarvinculopcasp.";
$oInstituicao        = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));


try {

  if ( !USE_PCASP ) {
    throw new BusinessException( "Sem PCASP ativo." );
  }

  switch( $oParam->sExecucao ) {

    case 'processarVinculoPadrao' :

      db_inicio_transacao();

      $oVinculoPcasp = new VinculoPlanoOrcamentoPcasp(db_getsession("DB_anousu"),
                                                      $oParam->sArquivo,
                                                      $oInstituicao);
      $oVinculoPcasp->processar();
      $oRetorno->sMensagem = urlencode(_M("{$sCaminhoMensagem}processamento_efetuado_com_sucesso"));

      db_fim_transacao(false);

      break;

    /**
     * Retorna as contas analíticas, sem vínculo com PCASP
     */
  	case 'getContasOrcamentoSemVinculo':


  	  $oDaoConPlanoOrcamento     = new cl_conplanoorcamento();
  	  $sCamposConPlanoOrcamento  = "c60_codcon, c60_estrut, c60_descr, c61_reduz";
  	  $sWhereConPlanoOrcamento   = "     c61_instit = {$iInstituicao} AND c60_anousu = {$iAno} AND c61_reduz is not null";
  	  $sWhereConPlanoOrcamento  .= " AND c72_sequencial is null";
  	  $sSqlConPlanoOrcamento     = $oDaoConPlanoOrcamento->sql_query_inconsistencia_plano(
  	                                                                                       null,
  	                                                                                       $sCamposConPlanoOrcamento,
  	                                                                                       null,
  	                                                                                       $sWhereConPlanoOrcamento
                                                                                         );
  	  $rsConPlanoOrcamento     = $oDaoConPlanoOrcamento->sql_record($sSqlConPlanoOrcamento);
  	  $iTotalConPlanoOrcamento = $oDaoConPlanoOrcamento->numrows;

  	  $oRetorno->aContasOrcamentarias = array();
  	  if ( $iTotalConPlanoOrcamento > 0 ) {

  	    for ( $iContador = 0; $iContador < $iTotalConPlanoOrcamento; $iContador++ ) {

  	      $oDadosConPlanoOrcamento            = db_utils::fieldsMemory( $rsConPlanoOrcamento, $iContador, false, false, true );
  	      $oDadosConPlanoOrcamento->conta_pai = db_le_mae_conplano( $oDadosConPlanoOrcamento->c60_estrut );
  	      $iClasse                            = substr( $oDadosConPlanoOrcamento->c60_estrut, 0, 1 );

  	      /**
  	       * Verifica o primeiro dígito do estrutural para montar o array
  	       */
  	      switch ( $iClasse ) {

  	        /**
  	         * Ativo
  	         */
  	      	case 1:

  	      	  $oRetorno->aContasOrcamentarias[1][$oDadosConPlanoOrcamento->c60_estrut] = $oDadosConPlanoOrcamento;
  	      	  break;

  	      	/**
  	      	 * Passivo
  	      	 */
  	      	case 2:

  	      	  $oRetorno->aContasOrcamentarias[2][$oDadosConPlanoOrcamento->c60_estrut] = $oDadosConPlanoOrcamento;
  	      	  break;

  	      	/**
  	      	 * Despesa
  	      	 */
  	      	case 3:

  	      	  $oRetorno->aContasOrcamentarias[3][$oDadosConPlanoOrcamento->c60_estrut] = $oDadosConPlanoOrcamento;
  	      	  break;

  	      	/**
  	      	 * Receita
  	      	 */
  	      	case 4:

  	      	  $oRetorno->aContasOrcamentarias[4][$oDadosConPlanoOrcamento->c60_estrut] = $oDadosConPlanoOrcamento;
  	      	  break;

  	      	/**
  	      	 * Resultado Diminutivo do Exercicio
  	      	 */
  	      	case 5:

  	      	  $oRetorno->aContasOrcamentarias[5][$oDadosConPlanoOrcamento->c60_estrut] = $oDadosConPlanoOrcamento;
  	      	  break;

  	      	/**
  	      	 * Resultado Aumentativo do Exercicio
  	      	 */
  	      	case 6:

  	      	  $oRetorno->aContasOrcamentarias[6][$oDadosConPlanoOrcamento->c60_estrut] = $oDadosConPlanoOrcamento;
  	      	  break;

  	      	/**
  	      	 * Deduções da Receita
  	      	 */
  	      	case 9:

  	      	  $oRetorno->aContasOrcamentarias[9][$oDadosConPlanoOrcamento->c60_estrut] = $oDadosConPlanoOrcamento;
  	      	  break;
  	      }
  	    }

  	    /**
  	     * Percorre o array das contas, ordenando de acordo com o estrutural dentro de cada classe
  	     */
  	    foreach ( $oRetorno->aContasOrcamentarias as $sIndice => $aContas ) {

  	      ksort($aContas);
    	    $oRetorno->aContasOrcamentarias[$sIndice] = $aContas;
  	    }
  	  }

  	  break;

  	/**
  	 * Retorna as contas do PCASP por instituição e ano da sessão
  	 *
  	 * @return array $oRetorno->aContas
  	 */
  	case 'getContasPcasp':

  	  $oRetorno->aContas = array();

  	  $oDaoConplano  = new cl_conplano;
  	  $sCamposPlano  = "c60_codcon as codigo_conta, c60_estrut as estrutural, c60_descr as descricao";
  	  $sCamposPlano .= ", c61_reduz as reduzido, c60_anousu as ano";
  	  $sWherePlano   = "c60_anousu = {$iAno} AND (c61_instit is null or c61_instit = {$iInstituicao}) ";
  	  $sSqlPlano     = $oDaoConplano->sql_query_geral( null, null, $sCamposPlano, "c60_estrut", $sWherePlano );
  	  $rsPlano       = $oDaoConplano->sql_record( $sSqlPlano );
  	  $iTotalPlano   = $oDaoConplano->numrows;

  	  if ( !$rsPlano || $iTotalPlano == 0 ) {
  	    throw new DBException( _M( $sCaminhoMensagem.'erro_consulta_pcasp' ) );
  	  }

  	  /**
       * Abre o arquivo que se encontra salvo dentro de config/pcasp
  	   */
  	  $aVinculos = array();
  	  if ( file_exists( "config/pcasp/arquivo_vinculo_pcasp.txt" ) ) {

  	    $rArquivoVinculo = fopen( "config/pcasp/arquivo_vinculo_pcasp.txt", "r" );

    	  /**
    	   * Percorre o arquivo, criando um array indexado pelo estrutural do PCASP e por cada vínculo orçamentário com o
    	   * PCASP
    	   */
    	  while ( !feof($rArquivoVinculo) ) {

    	    $sLinha = fgets( $rArquivoVinculo );

    	    if ( trim($sLinha) == '' ) {
    	      continue;
    	    }

    	    $aLinha                        = explode( "|", $sLinha );
    	    $aVinculos[trim($aLinha[1])][] = trim($aLinha[0]);
    	  }
  	  }

  	  /**
  	   * Percorre as contas retornadas no SQL, montando e ordenando o array de stdClass com as seguintes propriedades
  	   *   .. integer codigo_conta
  	   *   .. string  estrutural
  	   *   .. string  descricao
  	   *   .. integer reduzido
  	   *   .. array   aVinculos
  	   *   .. string  conta_pai
  	   */
	    for ( $iContador = 0; $iContador < $iTotalPlano; $iContador++ ) {

	      $oDadosConta            = db_utils::fieldsMemory( $rsPlano, $iContador, false, false, true );
	      $oDadosConta->aVinculos = array();

	      if ( isset($aVinculos[$oDadosConta->estrutural]) ) {

	        sort($aVinculos[$oDadosConta->estrutural]);
	        $oDadosConta->aVinculos = $aVinculos[$oDadosConta->estrutural];
	      }

	      $oDadosConta->conta_pai = db_le_mae_conplano( $oDadosConta->estrutural );

	      if ( $oDadosConta->conta_pai == $oDadosConta->estrutural ) {
	        $oDadosConta->conta_pai = 0;
	      }

	      $oRetorno->aContas[] = $oDadosConta;
	    }

  	  break;

  	/**
  	 * Vincula contas orçamentárias com uma conta do PCASP
  	 */
  	case 'vincular':

  	  db_inicio_transacao();

  	  foreach ( $oParam->aContasOrcamentarias as $sEstrutural ) {

  	    $oContaOrcamentaria = ContaOrcamentoRepository::getContaPorEstrutural( $sEstrutural, $iAno );
  	    $oContaOrcamentaria->setPlanoContaPCASP( ContaPlanoPCASPRepository::getContaByCodigo( $oParam->iContaPcasp, $oParam->iAno) );
  	    $oContaOrcamentaria->salvar();
  	  }

  	  db_fim_transacao();

  	  $oRetorno->sMensagem = urlencode( _M( $sCaminhoMensagem.'vinculo_processados' ) );
  	  break;
  }
} catch ( ParameterException $oErro ) {

  db_fim_transacao( true );
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( $oErro->getMessage() );
} catch ( BusinessException $oErro ) {

  db_fim_transacao( true );
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( $oErro->getMessage() );
} catch ( DBException $oErro ) {

  db_fim_transacao( true );
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( $oErro->getMessage() );
}

echo $oJson->encode( $oRetorno );
?>
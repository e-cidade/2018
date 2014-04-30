<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Gera petições de inicias quitadas ou parceladas
 * 
 * @package  Juridico 
 * @author   alberto          <alberto@dbseller.com.br> 
 * @author   jeferson.belmiro <jeferson.belmiro@dbseller.com.br>
 * 
 * @revision $Author  : $
 * @version  $Revision: 1.2 $
 */
class PeticaoEmissao {
	
	/**
	 * Array de objetos das petições que serão geradas
	 * @var array
	 */
	protected $aPeticoes = array();		
	
	/**
	 * Tipo de peticao
	 * @var integer
	 */
	protected $iTipoPeticao;
	
	/**
	 * Construtor da classe 
	 * @param integer $iTipoPeticao - 1 parcelamento / 2 - inicial quitada
	 */
	public function __construct($iTipoPeticao) {
						
		$this->setTipoPeticao($iTipoPeticao);		
	}
	
	/**
	 * Emite uma ou varias peticoes
	 *
	 * @throws ParameterException - Petições não informadas 
	 * @throws BusinessException  - Nenhum parâmetro configurado ou modelo de arquivo não configurado 
	 */
	public function emitir() {
		
		db_app::import('documentoTemplate');
		
		if (!is_array($this->getPeticoes()) || count($this->getPeticoes()) == 0) {
			throw new ParameterException('Petições não informadas para emissão do arquivo.');
		}

		if ($this->getTipoPeticao() == 1) {
		
			$sArquivoAgt = "juridico/peticao_juridico_parcelamento.agt";
			$sCampo      = "v19_templateparcelamento";
			$iTipo       = 16;
		
		} else if ($this->getTipoPeticao() == 2){
		
			$sArquivoAgt = "juridico/peticao_juridico_inicial.agt";
			$sCampo      = "v19_templateinicialquitada";
			$iTipo       = 17;
		
		}
				
		foreach ($this->getPeticoes() as $oPeticao) {			
			$aCodigoPeticao[] = $oPeticao->getCodigoPeticao();			
		}
		
		$oDaoParjuridico = db_utils::getDao('parjuridico');
		$sSqlModelo      = $oDaoParjuridico->sql_query_file(db_getsession("DB_anousu"), db_getsession("DB_instit"), "{$sCampo} as modelo_impressao");
		$rsModelo        = $oDaoParjuridico->sql_record($sSqlModelo);
		
		if ($oDaoParjuridico->numrows == 0 || trim(db_utils::fieldsMemory($rsModelo, 0)->modelo_impressao) == '') {
			throw new BusinessException('Nenhum modelo configurado para o ano de ' . db_getsession('DB_anousu'));
		}
		
		$iCodigoModeloDocumento = db_utils::fieldsMemory($rsModelo, 0)->modelo_impressao;

		ini_set("error_reporting","E_ALL & ~NOTICE");
		
		$oAgata           = new cl_dbagata($sArquivoAgt);
		$oApiAgata        = $oAgata->api;
		$sCaminhoSalvoSxw = "tmp/peticao_" . date('YmdHis') . "_" . db_getsession("DB_id_usuario") . ".sxw";
		
		$oApiAgata->setOutputPath($sCaminhoSalvoSxw);
		
		$oApiAgata->setParameter('$iCodigoPeticao', implode("', '", $aCodigoPeticao));
		
		$oDocumentoTemplate = new documentoTemplate($iTipo, $iCodigoModeloDocumento);
		if ( $oApiAgata->parseOpenOffice( $oDocumentoTemplate->getArquivoTemplate() ) ) {
			
			$sNomeRelatorio   = "tmp/peticao_" . date('YmdHis') . "_" . db_getsession("DB_id_usuario") . ".pdf";
			$lConversao       = db_stdClass::ex_oo2pdf($sCaminhoSalvoSxw, $sNomeRelatorio);
				
			if ( !$lConversao ) {
				throw new BusinessException('Erro na conversão do arquivo.');
			}
			
		}
		
		return $sNomeRelatorio;		
	}
	
	/**
	 * Adiciona objeto peticao a lista que sera gerada
	 *
	 * @param Peticao $oPeticao
	 */
	public function adicionarPeticao(Peticao $oPeticao) {

		if ( $oPeticao->getTipoPeticao() <> $this->iTipoPeticao ){			
			throw new BusinessException('Tipo de petição adicionada é diferente do modelo de documento selecionado.');
		}
		
		$this->aPeticoes[] = $oPeticao;
	}
	
	/**
	 * Define o tipo de petição que será gerada
	 * 1 - Parcelamento / 2 - Inicial Quitada
	 * @param integer $iTipoPeticao
	 */
	public function setTipoPeticao($iTipoPeticao) {
		$this->iTipoPeticao = $iTipoPeticao;
	}
	
	/**
	 * Retorna o tipo de petição gerada
	 * 1 - Parcelamento / 2 - Inicial Quitada
	 * @return integer
	 */
	public function getTipoPeticao() {
		return $this->iTipoPeticao;
	}
	
	/**
	 * Retorna um array de objetos 'Peticao'
	 * @return array
	 */
	public function getPeticoes() {
		return $this->aPeticoes;
	}
	
}
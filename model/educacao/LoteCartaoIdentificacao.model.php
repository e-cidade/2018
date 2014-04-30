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


class LoteCartaoIdentificacao {
	
	private $iCodidoLote = null;
	private $sData       = null;
	private $sHora       = null;
	private $iUsuario    = null;
	private $aAlunos     = array();
	
	public function __construct($iCodigoLote = null) {
		
		if ($iCodigoLote != null) {
			
			$oDaoLoteCarteiraIdentificacao  = db_utils::getDao ( "loteimpressaocartaoidentificacao" );
			$oDaoCarteiraIdentificacaoAluno = db_utils::getDao ( "loteimpressaocartaoidentificacaoaluno" );
			$sSqlLoteCarteiraIdentificacao  = $oDaoLoteCarteiraIdentificacao->sql_query ( $iCodigoLote );
			$rsLoteCarteiraIdentificacao    = $oDaoLoteCarteiraIdentificacao->sql_record ( $sSqlLoteCarteiraIdentificacao );
			if ($rsLoteCarteiraIdentificacao && $oDaoLoteCarteiraIdentificacao->numrows > 0) {
				
				$oLoteCarteiraIdentificacao = db_utils::fieldsMemory ( $rsLoteCarteiraIdentificacao, 0 );
				
				$this->iCodidoLote = $oCarteiraLoteIdentificacao->ed305_sequencial;
			  $this->sData       = $oCarteiraLoteIdentificacao->ed305_data;
			  $this->sHora       = $oCarteiraLoteIdentificacao->ed305_hora;
			  $this->iUsuario    = $oCarteiraLoteIdentificacao->ed305_usuario;
			  
			  $sWhere = "ed306_loteimpressaocartaoidentificacao = {$this->iCodidoLote}";
        $sSqlCarteiraIdentificacaoAluno = $oDaoCarteiraIdentificacaoAluno->sql_query(null, 
                                                                                     "ed306_aluno",
                                                                                     "ed47_v_nome",
                                                                                      $sWhere 
                                                                                    );
        $rsCarteiraIdentificacaoAluno   = $oDaoCarteiraIdentificacaoAluno->sql_record ( $sSqlLoteCarteiraIdentificacao );
        $iNumRowsCarteiras              = $oDaoCarteiraIdentificacaoAluno->numrows;
        if ($rsCarteiraIdentificacaoAluno){
        	
				  for ($i = 0; $i < $iNumRowsCarteiras; $i++) {
				  	
				  	$oDadosCarteira = db_utils::fieldsMemory($rsCarteiraIdentificacaoAluno, $i);
				    $this->adicionarCarteira(new CarteiraIdentificacao( new Aluno($oDadosCarteira->ed306_aluno) ) );
				  }			
        }			  	
			} else {
				return false;
			}
		}
		return true;	
	}
	
	public function adicionarCarteira(CarteiraIdentificacao $oCarteira = null) {
		$this->aCarteiras[] = $oCarteira;
	}
	
	public function getCarteiras() {
		return $this->aCarteiras;
	}
	
	public function getCodigoLote() {
		return $this->iCodidoLote;
	}
	
	public function gerarArquivo() {
		
		$sArquivoCSV      = "";
		$sNomeArquivoCSV  = "tmp/lista_alunos_cartao_identificacao_lote_{$this->getCodigoLote()}.csv";
		$sDataAtual       = date('Ymd',db_getsession("DB_datausu"));
		$sHoraAtual       = str_replace(":","",db_hora());
		$sNomeArquivoZip  = "lote_cartao_identificacao_{$sDataAtual}_{$sHoraAtual}";
		$aArquivosZip     = array();
		$aCarteiras       = $this->getCarteiras();
		foreach ($aCarteiras as $oCarteira) {
			
			$oAluno           = $oCarteira->getAluno();
			$sPathArquivoFoto = str_replace("tmp/","",$oAluno->getFoto());
			
			if (!$sPathArquivoFoto ) {
				throw new Exception("Erro ao buscar foto do aluno {$oAluno->getCodigoAluno()}");
			}
			$aFotosGeradas[] = $sPathArquivoFoto;  
			$aArquivosZip[]  = str_replace("tmp/","",$sPathArquivoFoto);						
			$sDataNascimento = implode("/", array_reverse(explode("-",$oAluno->getDataNascimento())));
			$sArquivoCSV    .= "{$oAluno->getCodigoAluno()};{$oAluno->getNome()};{$sDataNascimento};{$oAluno->getNomeMae()};";
			$sArquivoCSV    .= "{$oAluno->getNomePai()};{$oAluno->getNomeResponsavelLegal()};{$sPathArquivoFoto}\n";
		}
		
		$lArquivoGerado = file_put_contents($sNomeArquivoCSV, $sArquivoCSV);
		if (! $lArquivoGerado) {
			throw new Exception("Erro ao gerar arquivo para o lote {$this->getCodigoLote()}");
		}
		$aArquivosZip[] = str_replace("tmp/","",$sNomeArquivoCSV);
		
		compactaArquivos($aArquivosZip, $sNomeArquivoZip);
		
		
		/**
		 * Limpamos os arquivos gerados
		 */
		unlink($sNomeArquivoCSV);
		foreach ($aFotosGeradas as $sFoto) {
		  unlink("tmp/{$sFoto}");
		}
		return "tmp/{$sNomeArquivoZip}.zip";		
	}
	
	public function salvar() {
		
		if (!db_utils::inTransaction()){
			throw new Exception("Sem transação ativa com o banco de dados !");
		}
	  $oDaoLoteCarteiraIdentificacao  = db_utils::getDao ( "loteimpressaocartaoidentificacao" );	  
		$oDaoLoteCarteiraIdentificacao->ed305_usuario = db_getsession("DB_id_usuario");
		$oDaoLoteCarteiraIdentificacao->ed305_data    = date('Y-m-d',db_getsession("DB_datausu"));
		$oDaoLoteCarteiraIdentificacao->ed305_hora    = db_hora();
		
		$oDaoLoteCarteiraIdentificacao->incluir(null);
		if ($oDaoLoteCarteiraIdentificacao->erro_status == 0) {			
			 throw new Exception("Erro ao salvar dados do lote : {$oDaoLoteCarteiraIdentificacao->erro_msg}");
		}
		
		$this->iCodidoLote = $oDaoLoteCarteiraIdentificacao->ed305_sequencial;
		$this->sData       = $oDaoLoteCarteiraIdentificacao->ed305_data;
		$this->sHora       = $oDaoLoteCarteiraIdentificacao->ed305_hora;
		$this->iUsuario    = $oDaoLoteCarteiraIdentificacao->ed305_usuario;
		
		foreach ($this->getCarteiras() as $oCarteira) {
			$oCarteira->salvar($oDaoLoteCarteiraIdentificacao->ed305_sequencial);
		}
		
		return true;	
	}

}
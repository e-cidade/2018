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
 * Classe para tratamento de Large Objects(LO) do Banco de Dados PostgreSQL 
 * 
 * @author Rafael Serpa Nery    rafael.nery@dbseller.com.br	
 * @author Rafael Pereira Lopes rafael.lopes@dbseller.com.br	
 * @package Caixa
 * @revision $Author: dbrafael.nery $
 * @version $Revision: 1.4 $
 */
abstract class DBLargeObject {

	/**
	 * Executa leitura de um arquivo LO apartir de seu OID, e o gera no caminho ($sCaminhoArquivo)
	 * @param integer $iOid
	 * @param string $sCaminhoArquivo
	 */
	public static function leitura($iOid, $sCaminhoArquivo) {

		global $conn;

		if( !db_utils::inTransaction($conn) ) {
			throw new Exception("Sem transaчуo Ativa.");
		}
		$lEscritaArquivo = pg_lo_export($iOid, $sCaminhoArquivo, $conn);

		return $lEscritaArquivo;
	}

	/**
	 * Escreve objeto oid com o arquivo informado
	 * @param integer $iOid
	 * @param string  $sCaminhoArquivo
	 * @return boolean
	 */
	public static function escrita($sCaminhoArquivo, $iOid = null) {

		global $conn;
		
		if( !db_utils::inTransaction($conn) ) {
			throw new Exception("Sem transaчуo Ativa.");
		}
		$rsLargeObject    = pg_lo_open($conn, $iOid, "w");
		
		$sConteudoArquivo = file_get_contents($sCaminhoArquivo);
		
		$escrita 				  = pg_lo_write($rsLargeObject, $sConteudoArquivo);
		
		pg_lo_close($rsLargeObject);
		
		return $escrita;
	}

	/**
	 * Exclui um objeto oid do Banco de dados
	 * @param integer $iOid
	 *
	 * Caso de Uso: Ao excluir um registro de uma tabela e eo campo for do tipo oid,
	 * Deve-se excluir o objeto do banco utilizando este mщtodo
	 */
	public static function exclusao($iOid) {

		$lTransacaoInterna = false;
			
		global $conn;

			if( !db_utils::inTransaction() ) {
			throw new Exception("Sem transaчуo Ativa.");
		}

		return pg_lo_unlink ($conn, $iOid);
	}
	
	/**
	 * Cria L.O. Vazio no Banco de dados e retorna se OID 
	 * @param integer $lTemTransacaoAtiva
	 */
	public static function criaOID($lTemTransacaoAtiva = false) {
		
		global $conn;
		
		if( ! $lTemTransacaoAtiva ) {
			db_inicio_transacao();
		}
		
		$iOidGerado = pg_lo_create($conn);
		
		if ( ! $lTemTransacaoAtiva ) {
			db_fim_transacao();
		}
		return $iOidGerado;
	}
}
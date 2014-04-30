<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

class PostgreSQLUtils {
	var $_conexao;
	var $_sql;
	var $_numrows;

  function query($sql) {
		$this->_sql = $sql;
		if($this->_conexao != null) {
			$result = pg_query($this->_conexao, $sql);
		} else {
			$result = pg_query($sql);
		}
		$this->_numrows = pg_num_rows($result);
		return $result;
	}

  function getNumRows() {
		return $this->_numrows;
	}

	function PostgreSQLUtils($conexao=null) {
		$this->_conexao = $conexao;
		$this->_numrows = 0;
		$this->_sql     = "";
	}

	function getVersion() {
		$sqlVersao = "select version();";
		
		$resultVersao = $this->query($sqlVersao);
		
		if($this->getNumRows() > 0) {
			return pg_result($resultVersao, 0, 0);
		}
		return '';
	}

	function getPid() {
		$versao = $this->getVersion($this->_conexao);

	  if( ($this->_conexao != null) ) {
			return pg_get_pid($this->_conexao);
		}

		if( strpos($versao, "8.1") ) {
			$sqlPid = "select pg_backend_pid();";
			$resultPid = $this->query($sqlPid);
			return pg_result($resultPid, 0, 0);
		}

		return 0;
	}

	function cancelQuery($pid=0) {
		$versao = $this->getVersion();

		if( strpos($versao, "8.1") ) {
      if(($pid != 0) || ($pid != '')) {
			  $sqlCancel = "select pg_cancel_backend($pid);";
			  $resultCancel = pg_query($sqlCancel);
			  return pg_result($resultCancel, 0, 0)=='t'?true:false;
			}
		}

		return;
	}
  
  function isTableExists ($sRelacao, $sType = 'tabela', $sEsquema = null) {

    $aTiposRelacao["tabela"]    = "r"; // relkind r = ordinary table
    $aTiposRelacao["indice"]    = "i"; // relkind i = indice
    $aTiposRelacao["visao"]     = "v"; // relkind v = view
    $aTiposRelacao["sequencia"] = "S"; // relkind S = sequence
    $aTiposRelacao["tipo"]      = "c"; // relkind c = composite type 
    $aTiposRelacao["especial"]  = "s"; // relkind s = special
    $aTiposRelacao["toast"]     = "t"; // relkind t = toast table
    if(empty($sRelacao)) {
      return false;
    }

    // Select para buscar Relação
    $sSqlRelacao  = "SELECT relname ";
    $sSqlRelacao .= "  FROM pg_catalog.pg_class c";
    $sSqlRelacao .= "       INNER JOIN pg_catalog.pg_namespace n on n.oid = c.relnamespace ";
    $sSqlRelacao .= " WHERE c.relname = '{$sRelacao}' ";
    $sSqlRelacao .= "   AND c.relkind = '{$aTiposRelacao[$sType]}' ";
    if ($sEsquema != null) {
      $sSqlRelacao .= " and n.nspname = '{$sEsquema}' ";
    }
    $rsRelacao = pg_query($sSqlRelacao);
    if (pg_num_rows($rsRelacao)== 1) {
      return true;
    } else {
      return false;
    }
  }
  
  /*
   * Verifica Indexes das Tabelas
   * Return @$aIndexes
   */
  function getTableIndexes($sNomeTabela) {
  	
  	$sSqlTableIndex  = "select indexname from pg_indexes where tablename = '{$sNomeTabela}'";
  	$rsSqlTableIndex = db_query($sSqlTableIndex);
  	$aIndexes        = array();
  	$iNumRows        = pg_num_rows($rsSqlTableIndex);
  	for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
  		
  		$oIndexName = db_utils::fieldsMemory($rsSqlTableIndex, $iInd);
  		$aIndexes[] = $oIndexName->indexname;
  	}
  	
  	return $aIndexes;
  }
}
?>
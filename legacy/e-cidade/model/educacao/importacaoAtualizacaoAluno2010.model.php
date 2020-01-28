<?
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

require_once("model/educacao/importacaoCenso.model.php");

class importacaoAtualizacaoAluno2010 extends importacaoCenso {
	
    /**
   * funзгo que valida o arquivo txt para importacao dos dados, verifica se o codigo inep й da escola 
   * onde o usuario esta logado, e verifica tambйm o ano atual
   * @override
   */
  function validaArquivo() {

    $sMsgErro      = "Importaзгo de Arquivo Censo abortada!\n";
    $oDaoEscola    = db_utils::getdao('escola');
    $pArquivoCenso = fopen($this->sCaminhoArquivo, "r");
    
    if (!$pArquivoCenso) {
      throw new Exception(" Nгo foi possнvel abrir o arquivo para importaзгo! ");   
    }    
    
    $sSqlEscola = $oDaoEscola->sql_query("", "ed18_c_codigoinep", "", "ed18_i_codigo = ".db_getsession("DB_coddepto"));      
    $rsEscola   = $oDaoEscola->sql_record($sSqlEscola);         
    if ($oDaoEscola->numrows > 0) {
      $iInepBanco = db_utils::fieldsMemory($rsEscola, 0)->ed18_c_codigoinep;   
    }
    
    $sLinha         = fgets($pArquivoCenso);
    $iAno           = substr($sLinha, 33, 4);
    $iTipoRegistro  = substr($sLinha, 0, 2);
    $iCodigoInepTxt = substr($sLinha, 12, 8);

    if ($iTipoRegistro != "00") {
      
      fclose($pArquivoCenso);
      throw new Exception(" Arquivo informado nгo й um arquivo de exportaзгo geral gerado pelo Educacenso! ");        
        
    } elseif ($iCodigoInepTxt != $iInepBanco) {
      
      fclose($pArquivoCenso);
      throw new Exception(" Arquivo nгo pertence a esta escola, cуdigo inep diferente do que informado no arquivo! ");  
                 
    } elseif ($this->iAnoEscolhido != $iAno) {
      
      fclose($pArquivoCenso);
      throw new Exception(" Arquivo informado nгo pertence ao ano de ".$this->iAnoEscolhido);
                
    }
    
    rewind($pArquivoCenso); 
    while (!feof($pArquivoCenso)) {
      
      $sLinha = fgets($pArquivoCenso);
      $iLinha = substr($sLinha, 0, 2);
      /**
       * se a linha esta em branco й ignorada
       */
      if (empty($iLinha)) {
        continue;
      }   
      
      if ($iLinha != "00" && $iLinha != "10" && $iLinha != "20" 
          && $iLinha != "30" && $iLinha != "40" && $iLinha != "50"
          && $iLinha != "51" && $iLinha != "60" && $iLinha != "70" 
          && $iLinha != "80") {
            
        fclose($pArquivoCenso);    
        throw new Exception(" Arquivo informado nгo й valido, existe registro de cуdigo ".
                            $iLinha." que й desconhecido"
                           );
        
      } //fecha o if que verifica os tipos de registros  
           
    }//fecha o while
    
    fclose($pArquivoCenso);
    
  } //fecha a funcao validaArquivo

  /**
   * 
   * Funcao que seleciona o codigo  do pais para utilizarmos na inclusao do aluno e do docente(RecHumano)
   * @param integer $iCodPaisCenso
   */
  function getPais($iCodPaisCenso) {
    
    $oDaoPais = db_utils::getdao('pais');
    $sWhere   = "ed228_i_codigo = ".$iCodPaisCenso;
    $sSqlPais = $oDaoPais->sql_query_file("", "ed228_i_codigo", "", $sWhere);  
    $rsPais   = $oDaoPais->sql_record($sSqlPais);
    
    if ($oDaoPais->numrows > 0) {
      return $oDados->pais = db_utils::fieldsmemory($rsPais, 0)->ed228_i_codigo;        
    } else {
      return $oDadosPais->ed47_i_pais = 0;  
    }//fecha o else 
                                 
  }//fecha a funcao getPais
  
  /**
   * Funcao que altera a formatacao da data 
   * @param date $dDataNascAluno
   * @override
   */
  function getNascimento($dDataNascAluno) {
  	return substr($dDataNascAluno, 4, 4)."-".substr($dDataNascAluno, 2, 2)."-".substr($dDataNascAluno, 0, 2);  	
  }
  
  
  /**
   * 
   * Funcao que seleciona o codigo  do cartorio para utilizarmos na inclusao do aluno e do docente(RecHumano)
   * @param varchar $sNomeCartorio
   */
  function getCartorio($sNomeCartorio) {
    
    $oDaoCensoCartorio = db_utils::getdao('censocartorio');
    $sWhere            = "ed291_c_nome = '".$sNomeCartorio. "'";
    $sSqlCartorio      = $oDaoCensoCartorio->sql_query_file("", "ed291_i_codigo", "", $sWhere);  
    $rsCartorio        = $oDaoCensoCartorio->sql_record($sSqlCartorio);
    //die($sSqlCartorio."sss");
    if ($oDaoCensoCartorio->numrows > 0) {
      return $oDadosCartorio = db_utils::fieldsmemory($rsCartorio, 0)->ed291_i_codigo;        
    } else {
      return $oDadosCartorio = null;  
    }//fecha o else 
                                 
  }//fecha a funcao getPais
  
}//fecha a classe
?>
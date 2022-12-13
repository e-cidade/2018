<?php
/*
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

define("URL_MENSAGEM_HISTORICOALUNOREPOSITORY", "educacao.escola.HistoricoAlunoRepository.");
/**
 * Repositoy para Histórico dos Alunos
 * @package   Educacao
 * @author    Trucolo - trucolo@dbseller.com.br
 * @version   $Revision: 1.9 $
 */

class HistoricoAlunoRepository {

  private $aHistoricoAluno = array();
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorna a instância do Repositório
   * @return HistoricoAlunoRepository
   */
  protected function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new HistoricoAlunoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se o historico aluno possui instância, se não instancia e retorna a instância de HistoricoAluno
   * @param integer $iCodigoHistoricoAluno
   * @return HistoricoAluno
   */
  public static function getHistoricoAlunoByCodigo($iCodigoHistoricoAluno) {

    if (!array_key_exists($iCodigoHistoricoAluno, HistoricoAlunoRepository::getInstance()->aHistoricoAluno)) {
      HistoricoAlunoRepository::getInstance()->aHistoricoAluno[$iCodigoHistoricoAluno] = new HistoricoAluno($iCodigoHistoricoAluno);
    }

    return HistoricoAlunoRepository::getInstance()->aHistoricoAluno[$iCodigoHistoricoAluno];
  }
  
  /**
   * 
   * @param Aluno $oAluno
   * @param Curso $oCurso
   * @throws DBException
   * @return HistoricoAluno
   */
  public static function getHistoricoAlunoByCurso(Aluno $oAluno, Curso $oCurso) {
  	
    $sWhere  = "     ed61_i_aluno = {$oAluno->getCodigoAluno()} ";
    $sWhere .= " and ed61_i_curso = {$oCurso->getCodigo()} ";
    
    $oDaoHistorico = new cl_historico();
    $sSqlHistorico = $oDaoHistorico->sql_query_file(null, "ed61_i_codigo", null, $sWhere);
    $rsHistorico   = db_query($sSqlHistorico);
    
    if (!$rsHistorico) {
    	throw new DBException(_M(URL_MENSAGEM_HISTORICOALUNOREPOSITORY."erro_ao_buscar_historico_aluno_por_curso"));
    }
    
    $iHistorico = db_utils::fieldsMemory($rsHistorico, 0)->ed61_i_codigo;
    
    return HistoricoAlunoRepository::getHistoricoAlunoByCodigo($iHistorico);
    
  } 

  /**
   * Adiciona um HistoricoAluno ao repositório
   * @param HistoricoAluno $oHistoricoAluno
   * @return boolean
   */
  public static function adicionarHistoricoAluno(HistoricoAluno $oHistoricoAluno) {

    if(!array_key_exists($oHistoricoAluno->getCodigoHistoricoAluno(), HistoricoAlunoRepository::getInstance()->aHistoricoAluno)) {
      HistoricoAlunoRepository::getInstance()->aHistoricoAluno[$oHistoricoAluno->getCodigoHistoricoAluno()] = $oHistoricoAluno;
    }
    return true;
  }

  /**
   * Remove um HistoricoAluno do repositório
   * @param HistoricoAluno $oHistoricoAluno
   * @return boolean
   */
  public static function removerHistoricoAluno(HistoricoAluno $oHistoricoAluno) {

    if (array_key_exists($oHistoricoAluno->getCodigoHistoricoAluno(), HistoricoAlunoRepository::getInstance()->aHistoricoAluno)) {
      unset(HistoricoAlunoRepository::getInstance()->aHistoricoAluno[$oHistoricoAluno->getCodigoHistoricoAluno()]);
    }
    return true;
  }

  /**
   * Retorna uma coleção de históricos do aluno
   * @param Aluno $oAluno
   * @return HistoricoAluno[] $aHistoricos
   */
  public static function getHistoricosPorAluno(Aluno $oAluno) {

    $sWhere        = " ed61_i_aluno = {$oAluno->getCodigoAluno()} ";
    $oDaoHistorico = new cl_historico();

    $sCampos  = " ed61_i_codigo, ed61_i_aluno, ";
    $sCampos .= " case  ";
    $sCampos .= "      when historicomps.ed62_i_historico is not null ";
    $sCampos .= "        then ed62_i_anoref ";
    $sCampos .= "      when historicompsfora.ed99_i_historico is not null ";
    $sCampos .= "        then ed99_i_anoref ";
    $sCampos .= "      else null ";
    $sCampos .= " end as ano ";

    $sSqlHistorico   = $oDaoHistorico->sql_query_historico(null, $sCampos ,"ano", $sWhere);
    $rsHistorico     = $oDaoHistorico->sql_record($sSqlHistorico);
    $iTotalHistorico = $oDaoHistorico->numrows;

    $aCodigoHistorico = array();
    if ($iTotalHistorico > 0) {

      for ($iCont = 0; $iCont < $iTotalHistorico; $iCont++) {

        $iCodigoHistoricoAluno                    = db_utils::fieldsMemory($rsHistorico, $iCont)->ed61_i_codigo;
        $aCodigoHistorico[$iCodigoHistoricoAluno] = $iCodigoHistoricoAluno;
      }
    }

    $aHistoricos = array();
    foreach ($aCodigoHistorico as $iHistorico) {
      $aHistoricos[] = HistoricoAlunoRepository::getHistoricoAlunoByCodigo($iHistorico);
    }
    
    return $aHistoricos;
  }
}
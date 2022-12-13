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

/**
 * Interface para os elementos de Exportação do Censo
 * @package educacao
 * @subpackage censo
 * @author André Mello <andre.mello@dbseller.com.br>
 */

interface IExportacaoCenso {

  /**
   * Retorna o Ano do Censo
   * @return integer
   */
  public function getAnoCenso();

  /**
   * Método para setar a data de pesquisa do Censo
   * @param date $dtBaseCenso
   */
  public function setDataCenso($dtBaseCenso);

  /**
   * Método para armazenar os erros em um arquivo de logs
   * @param string  $MensagemLog
   * @param integer $iTipo
   */
  public function logErro($MensagemLog, $iTipoLog);

  /**
   * Cria o arquivo txt.
   * Retorna o caminho do arquivo gerado.
   * @return string
   */
  public function escreverArquivo();

  /**
   * Retorna os dados processados referêntes a Escola
   * @return stdClass 
   */
  public function getDadosProcessadosEscola() ;

  /**
   * Retorna os dados processados referêntes a Turma
   * @return array
   */
  public function getDadosProcessadosTurma();

  /**
   * Retorna os dados processados referêntes ao Docente
   * @return array
   */
  public function getDadosProcessadosDocente();

  /**
   * Retorna os dados processados referêntes ao Aluno
   * @return array
   */
  public function getDadosProcessadosAluno();

  /**
   * Retorna o nome do arquivo de log
   * @return string
   */
  public function getNomeArquivoLog();

  /**
   * Método destrutor
   */
  public function __destruct();

}
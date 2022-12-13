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
   * Classe responsável pela geração dos Arquivos PIT
   * @author  Renan Melo <renan@dbseller.com.br>
   * @author  Tales Baz  <tales.baz@dbseller.com.br>
   * @package Cadastro
   */
  abstract class GeracaoArquivoPit{
    
    /**
     * Constante para quando o arquivos a ser Gerado For IPTU
     */
    const IPTU        = 1;

    /**
     * Constante para quando o arquivo a ser Gerado for ITBI URBANO
     */
    const ITBI_URBANO = 2;

    /**
     * Constante para quando o arquivo a ser gerado for ITBI RURAL
     */
    const ITBI_RURAL  = 3;

    /**
     * Constante para quando o arquivo a ser gerado for ITBI-PVR
     */
    const ITBI_PVR    = 4;

    /**
     * Constante para quando o arquivo a ser gerado  for ITBI-PVU
     */
    const ITBI_PVU    = 5;

    /**
     * Constante para quando o arquivo a ser gerado  for LOGRADOUROS
     */
    const LOGRADOUROS = 6;
    
    /**
     * Caminhos para o arquivo JSON contendo as mensagens utilizadas na função _M 
     */
    const MENSAGENS   = 'tributario.cadastro.geracaoarquivopit.';

    /**
     * Retorna uma instância da classe que é responsavel pelo tipo de arquivo que foi selecionado
     * @param integer $iTipoArquivo
     *        1 - IPTU
     *        2 - ITBI URBANO
     *        3 - ITBI RURAL
     *        4 - ITBI-PVR
     *        5 - ITBI-PVU
     *        6 - LOGRADOUROS
     * @param integer $iAno Ano selecionado para o arquivo ser gerado
     * @param integer $iSemestre Semestre em que o arquivo deve ser gerado. 
     *        1 - 1º Semestre 
     *        2 - 2º semestre
     */
    public static function getInstanceByArquivo($iTipoArquivo, $iAno, $iSemestre) {
      switch ($iTipoArquivo) {

        case GeracaoArquivoPit::IPTU:
          return new GeracaoArquivoPitIptu($iAno, $iSemestre);
          break;
        case GeracaoArquivoPit::ITBI_URBANO:
          return new GeracaoArquivoPitITBIUrbano($iAno, $iSemestre);
          break;
        case GeracaoArquivoPit::ITBI_RURAL:
          return new GeracaoArquivoPitITBIRural($iAno, $iSemestre);
          break;
        case GeracaoArquivoPit::ITBI_PVR:
          return new GeracaoArquivoPitITBIPVR($iAno, $iSemestre);
          break;
        case GeracaoArquivoPit::ITBI_PVU:
          return new GeracaoArquivoPitITBIPVU($iAno, $iSemestre);
          break;
        case GeracaoArquivoPit::LOGRADOUROS:
          return new GeracaoArquivoPitLogradouros($iAno, $iSemestre);
          break;
        default:
          throw new BusinessException(_M(GeracaoArquivoPit::MENSAGENS . 'tipo_nao_cadastrado'));
          break;
      }
    }

  }
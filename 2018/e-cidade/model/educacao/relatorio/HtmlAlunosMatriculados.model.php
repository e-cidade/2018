<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

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
 * Classe modelo para criar uma vis�o html das estatistica das matr�culas das turmas 
 * @package    Educacao
 * @subpackage relatorio
 * @author     Andr� Mello - andre.mello@dbseller.com.br
 * @version    $Revision: 1.1 $
 */
class HtmlAlunosMatriculados extends EstatisticaAlunosMatriculados {
  
  /**
   * valida se devemos exibir o percentual
   * @var boolean
   */
  private $lPercentual = false;
  
  const COR_ENSINO = '#999999';
  const COR_ETAPA  = '#dbdbdb';
  const COR_TURMA  = '#f3f3f3';
  
  private $sHtml;
  
  public function __construct( Calendario $oCalendario, $aEtapa, Escola $oEscola ) {
    
    parent::__construct($oCalendario, $aEtapa, $oEscola);
    $this->getEstatisticaAlunosMatriculados();
    $this->getPercentual();
  }
  
  /**
   * Seta se � necess�rio listar na tela as porcentagens totais por etapa
   * @param boolean $lPercentual
   */
  public function setExibePercentual( $lPercentual = false ) {
    
    $this->lPercentual = $lPercentual;
  } 
  
  /**
   * M�todo respons�vel por montar a tabela que exibe os c�lculos e porcentagens referentes aos totais
   * de matriculas de aluno por etapa e ensnino
   * @return String $sHtml
   */
  public function exibir() {
    
    $this->sHtml = "<table style='border-collapse: collapse; width:100%; font-size:8pt; border: 1px solid; '>";
    
    foreach ( $this->aEnsino as $oEnsino ) {
      
      /**
       * Monta a linha que exibe os ensinos
       */
      $sCor     = self::COR_ENSINO;
      $sStyle   = "style = 'background-color : {$sCor};  border-bottom: 1px solid; border-top: 1px solid; ' ";
      
      $this->sHtml .= "<tr {$sStyle} class='bold'> \n";
      $this->sHtml .= "  <td colspan = '11'> {$oEnsino->sNome} </td> \n";
      $this->sHtml .= "</tr>";

      foreach ( $oEnsino->aEtapa as $oEtapa ) {
        
        /**
         * Lista o cabe�alho por etapa
         * @var [type]
         */
        $sCor     = self::COR_ETAPA;
        $sStyleTR = "style = 'background-color : {$sCor}; border-bottom: 2px solid; border-top: 2px solid; ' ";
        $sStyleTD = "border-left: 1px solid; border-rigth: 1px solid;";
        
        $this->sHtml .= "<tr {$sStyleTR} class='bold'> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' colspan = '2'>Etapa: {$oEtapa->sNome}     </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�cula Inicial'>Matr.Inicial  </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�culas Evadidas'>EVAD. 	     </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�culas Canceladas'>CANC. 	   </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�culas Transferidas'>TRANS.  </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�culas Progredidas'>PROGR. 	 </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Alunos Falec�dos'>�BITO 	       </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�culas Efetiva'>Matr.Efetiva </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Vagas da Turma'>Vagas 	         </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Vagas Dispon�veis'>Vagas Disp.   </td> \n";
        $this->sHtml .= "</tr> \n";
        
        foreach ( $oEtapa->aTurmas as $oTurma ) {

          /**
           * Lista as linhas contendo os dados de matriculas dos alunos por turma
           * @var [type]
           */
          $sCor   = self::COR_TURMA;
          $sStyle = "style = 'background-color : {$sCor}; border: 1px solid;' ";
          $sTitle = 'Veja os alunos matriculados nesta turma';
          
          $sBorderLeftRigth = "border-left: 1px solid; border-rigth: 1px solid;";
          
          $sFunction  = " js_OpenJanelaIframe('','db_iframe_matriculas', ";
          $sFunction .= "                     'edu3_alunomatriculado002.php?turma={$oTurma->iCodigo} ";
          $sFunction .= "                                                   &etapaturma={$oEtapa->iCodigo}', ";
          $sFunction .= "                     'Alunos Matriculados na Turma {$oTurma->sTurma}', true);";
          
          $this->sHtml .= "<tr {$sStyle} style = 'border : 1px solid;'> \n";
          $this->sHtml .= "  <td >Turma: <a href='#' onclick=\"{$sFunction}\" title='{$sTitle}'>{$oTurma->sTurma}</a> </td> \n";
          $this->sHtml .= "  <td style='' >Turno: {$oTurma->sTurno}                </td> \n";
          $this->sHtml .= "  <td style='{$sBorderLeftRigth}' class='text-center' > {$oTurma->matricula_inicial      }     </td> \n";
          $this->sHtml .= "  <td style='{$sBorderLeftRigth}' class='text-center' > {$oTurma->matriculas_evadidas    }     </td> \n";
          $this->sHtml .= "  <td style='{$sBorderLeftRigth}' class='text-center' > {$oTurma->matriculas_canceladas  }     </td> \n";
          $this->sHtml .= "  <td style='{$sBorderLeftRigth}' class='text-center' > {$oTurma->matriculas_transferidas}     </td> \n";
          $this->sHtml .= "  <td style='{$sBorderLeftRigth}' class='text-center' > {$oTurma->matriculas_progredidas }     </td> \n";
          $this->sHtml .= "  <td style='{$sBorderLeftRigth}' class='text-center' > {$oTurma->matriculas_falecidas   }     </td> \n";
          $this->sHtml .= "  <td style='{$sBorderLeftRigth}' class='text-center' > <b>{$oTurma->matriculas_efetivas }</b> </td> \n";
          $this->sHtml .= "  <td style='{$sBorderLeftRigth}' class='text-center' > {$oTurma->total_vagas            }     </td> \n";
          $this->sHtml .= "  <td style='{$sBorderLeftRigth} color:green;' class='text-center' > {$oTurma->total_disponiveis      }     </td> \n";
          $this->sHtml .= "</tr> \n";
        }
        
        /**
         * Mostra a linha total por etapa
         */
        $this->sHtml .= "<tr {$sStyleTR} > \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-right' colspan = '2'>Total da Etapa: {$oEtapa->sNome} </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iTotalMatriculaInicial}           </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iTotalEvadidos        }           </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iTotalCancelados      }           </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iTotalTransferidos    }           </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iTotalProgredidos     }           </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iTotalObitos          }           </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iTotalMatriculaEfetiva}           </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iTotalVagas           }           </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD} color:green;'  class='text-center' > {$oEtapa->iTotalVagasDisponiveis}           </td> \n";
        $this->sHtml .= "</tr> \n";

        /**
         * Verifica se � necess�rio listar os percentuais por etapa e os lista
         */
        if ($this->lPercentual) {
          
          $this->sHtml .= "<tr {$sStyleTR} > \n";
          $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-right' colspan = '2'>Percentuais: </td> \n";
          $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > </td> \n";
          $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iPercentualEvadidos        }%           </td> \n";
          $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iPercentualCancelados      }%           </td> \n";
          $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iPercentualTransferidos    }%           </td> \n";
          $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iPercentualProgredidos     }%           </td> \n";
          $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iPercentualObitos          }%           </td> \n";
          $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iPercentualMatriculaEfetiva}%           </td> \n";
          $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > </td> \n";
          $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oEtapa->iPercentualVagasDisponiveis}%          </td> \n";
          $this->sHtml .= "</tr> \n";
        }
      }
    } 

    /**
     * Monta as linhas para mostrar os Totais Gerais 
     */
    $oTotalGeral  = $this->getTotalGeral();
    
    $sStyleTR     = "border-bottom: 1px solid; border-top: 1px solid;";
    $sCor         = self::COR_ENSINO;
    $this->sHtml .= "<tr>";
    $this->sHtml .= "  <td colspan='11'> <b>TOTAL GERAL</b>  </td>";
    $this->sHtml .= "</tr>";
    $this->sHtml .= "<tr style = '{$sStyleTR} background-color: {$sCor}' class='bold'> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' colspan = '2'></td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�cula Inicial'>Matr.Inicial  </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�culas Evadidas'>EVAD.       </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�culas Canceladas'>CANC.     </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�culas Transferidas'>TRANS.  </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�culas Progredidas'>PROGR.   </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Alunos Falec�dos'>�BITO          </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matr�culas Efetiva'>Matr.Efetiva </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Vagas da Turma'>Vagas            </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD} ' class='text-center' title = 'Vagas Dispon�veis'>Vagas Disp.   </td> \n";
    $this->sHtml .= "</tr> \n";


    
    $sCor         = self::COR_TURMA;

    $this->sHtml .= "<tr style='{$sStyleTR} background-color : {$sCor}' > \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-right bold' colspan = '2'>Somas: </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iTotalMatriculaInicial} </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iTotalEvadidos}         </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iTotalCancelados}       </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iTotalTransferidos}     </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iTotalProgredidos}      </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iTotalObitos}           </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center bold' > {$oTotalGeral->iTotalMatriculaEfetiva} </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iTotalVagas}            </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD} color:green;' class='text-center' > {$oTotalGeral->iTotalVagasDisponiveis} </td> \n";
    $this->sHtml .= "</tr> \n";


    $this->sHtml .= "<tr style='{$sStyleTR} background-color : {$sCor}' > \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-right bold' colspan = '2'>Percentuais: </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iPercentualEvadidos        }%           </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iPercentualCancelados      }%           </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iPercentualTransferidos    }%           </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iPercentualProgredidos     }%           </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iPercentualObitos          }%           </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center bold' > {$oTotalGeral->iPercentualMatriculaEfetiva}%           </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' > {$oTotalGeral->iPercentualVagasDisponiveis}%          </td> \n";
    $this->sHtml .= "</tr> \n";

    $this->sHtml .= "</table>";
    
    return $this->sHtml;
  }
  
}
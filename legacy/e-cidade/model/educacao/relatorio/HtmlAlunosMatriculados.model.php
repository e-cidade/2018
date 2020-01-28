<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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
 * Classe modelo para criar uma visão html das estatistica das matrículas das turmas 
 * @package    Educacao
 * @subpackage relatorio
 * @author     André Mello - andre.mello@dbseller.com.br
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
   * Seta se é necessário listar na tela as porcentagens totais por etapa
   * @param boolean $lPercentual
   */
  public function setExibePercentual( $lPercentual = false ) {
    
    $this->lPercentual = $lPercentual;
  } 
  
  /**
   * Método responsável por montar a tabela que exibe os cálculos e porcentagens referentes aos totais
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
         * Lista o cabeçalho por etapa
         * @var [type]
         */
        $sCor     = self::COR_ETAPA;
        $sStyleTR = "style = 'background-color : {$sCor}; border-bottom: 2px solid; border-top: 2px solid; ' ";
        $sStyleTD = "border-left: 1px solid; border-rigth: 1px solid;";
        
        $this->sHtml .= "<tr {$sStyleTR} class='bold'> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' colspan = '2'>Etapa: {$oEtapa->sNome}     </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrícula Inicial'>Matr.Inicial  </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrículas Evadidas'>EVAD. 	     </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrículas Canceladas'>CANC. 	   </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrículas Transferidas'>TRANS.  </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrículas Progredidas'>PROGR. 	 </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Alunos Falecídos'>ÓBITO 	       </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrículas Efetiva'>Matr.Efetiva </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Vagas da Turma'>Vagas 	         </td> \n";
        $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Vagas Disponíveis'>Vagas Disp.   </td> \n";
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
         * Verifica se é necessário listar os percentuais por etapa e os lista
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
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrícula Inicial'>Matr.Inicial  </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrículas Evadidas'>EVAD.       </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrículas Canceladas'>CANC.     </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrículas Transferidas'>TRANS.  </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrículas Progredidas'>PROGR.   </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Alunos Falecídos'>ÓBITO          </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Matrículas Efetiva'>Matr.Efetiva </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD}' class='text-center' title = 'Vagas da Turma'>Vagas            </td> \n";
    $this->sHtml .= "  <td style = '{$sStyleTD} ' class='text-center' title = 'Vagas Disponíveis'>Vagas Disp.   </td> \n";
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
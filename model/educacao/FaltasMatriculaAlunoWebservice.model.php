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


class FaltasMatriculaAlunoWebservice {
  
  protected $oMatricula;
  
  public function __construct($iCodigoMatricula) {

    $this->oMatricula = MatriculaRepository::getMatriculaByCodigo($iCodigoMatricula);
  }
  
  public function getFaltas() {

    $aFaltas                     = array();
    $oDaoDiarioClasseAlunoFalta  = new cl_diarioclassealunofalta();
    $sCamposFalta  = " count(*) as total_faltas, ed300_datalancamento";
    $sCamposFalta .= ", ed232_c_descr as disciplina";
    $sWhereFalta   = " ed60_i_codigo = {$this->oMatricula->getCodigo()} group by ed300_datalancamento,ed232_c_descr ";
    $sWhereFalta  .= "             order by ed300_datalancamento DESC, ed232_c_descr asc";
    
    
    $sSqlDiarioClasseAlunoFalta     = $oDaoDiarioClasseAlunoFalta->sql_query_falta_matricula(null,
                                                                                          $sCamposFalta,
                                                                                          null,
                                                                                          $sWhereFalta
    );
    $rsDiarioClasseAlunoFalta      = $oDaoDiarioClasseAlunoFalta->sql_record($sSqlDiarioClasseAlunoFalta);
    $iTotalDiarioClasseAlunoFalta  = $oDaoDiarioClasseAlunoFalta->numrows;
    
    if ($iTotalDiarioClasseAlunoFalta > 0) {
    
      for ($iContadorFalta = 0; $iContadorFalta < $iTotalDiarioClasseAlunoFalta; $iContadorFalta++) {
    
        $oFalta                    = db_utils::fieldsMemory($rsDiarioClasseAlunoFalta, $iContadorFalta);
        $oDadosFalta               = new stdClass();
        $oDadosFalta->dtFalta      = $oFalta->ed300_datalancamento;
        $oDadosFalta->iTotalFaltas = $oFalta->total_faltas;
        $oDadosFalta->sDisciplinas = utf8_encode($oFalta->disciplina);
        $aFaltas[]                 = $oDadosFalta;
      }
      unset($oDadosFalta);
      unset($dtFaltaAluno);
    }
    return $aFaltas;
  }
}
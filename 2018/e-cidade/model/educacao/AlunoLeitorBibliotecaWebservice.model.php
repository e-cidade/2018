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

/**
 * Classe para webservices de aluno
 * Atua como um facade para a classe de Aluno.
 * as informa��es Retornadas s�o oo codigo do aluno, nome
 * @author dbseller
 *
 */
class AlunoLeitorBibliotecaWebservice {

  private $iCodigoAluno = null;
  private $oAluno       = null;
  public function __construct($iCodigoAluno) {
    
    $this->oAluno  = new Aluno($iCodigoAluno);
    if ($this->oAluno->getCodigoAluno() == null) {
      
      $this->oAluno = null;
      throw new ParameterException('Aluno n�o Encontrado.');
    }
    $this->iCodigoAluno = $this->oAluno->getCodigoAluno();
  }
  
  /**
   * Retorna os emprestimos Realizados pelo aluno
   * @throws ParameterException
   * @return boolean|string
   */
  public function getDados($sDataInicial = '', $sDataFinal = '') {
    

    if ($this->oAluno == null) {
      throw new ParameterException('Aluno n�o existe no cadastro.');
    }
    $oDadosLeitorAluno              = new stdClass();
    $oDadosLeitorAluno->emprestimos = array();
    
    $oDaoLeitor      = new cl_leitor();
    $sSqlDadosLeitor = $oDaoLeitor->sql_query(null, "bi10_codigo", null, "bi11_aluno = {$this->iCodigoAluno}");
    $rsDadosLeitor   = $oDaoLeitor->sql_record($sSqlDadosLeitor);
    
    
    if (!$rsDadosLeitor && $oDaoLeitor->numrows == 0) {
      return false;
    }
    
    $iCodigoLeitor =  db_Utils::fieldsMemory($rsDadosLeitor, 0)->bi10_codigo;
    
    $aWhere   = array();
    $aWhere[] = "bi16_leitor = {$iCodigoLeitor}";
    
    if (trim($sDataInicial) != "") {
      $aWhere[] = " bi18_retirada >= '{$sDataInicial}'";
    }
    
    if (trim($sDataFinal) != "") {
      $aWhere[] = " bi18_retirada <= '{$sDataFinal}'";
    }
    
    $oDadosBliblioteca = new stdClass();
    
    $sGroupBy  = " group by bi23_codigo, bi06_titulo, bi17_nome, bi18_retirada, bi18_devolucao, bi21_entrega";
    $sOrdem    = 'bi18_retirada desc';
    $sCampos   = "bi23_codigo, trim(bi06_titulo) as bi06_titulo, trim(bi17_nome) as bi17_nome, bi18_retirada, bi18_devolucao,";
    $sCampos  .= "array_to_string(array_accum(trim(bi01_nome)), ' / ') as autores, bi21_entrega";
    
    $sWhere          = implode(" and", $aWhere);
    $sWhereAtraso    = $sWhere . " and bi18_devolucao < current_date and bi21_entrega IS NULL";
    $sWhereAberto    = $sWhere . " and bi18_devolucao > current_date and bi21_entrega IS NULL";
    $sWhereDevolvido = $sWhere . " and bi21_entrega  is not null";
    
    $oDaoEmprestivoAcervo = new cl_emprestimoacervo();
    
    $sSqlAcervosAtrasado = $oDaoEmprestivoAcervo->sql_query_emprestimos_acervo_com_autor(null,
                                                                                         $sCampos. ", 1 as situacao",
                                                                                         $sOrdem,
                                                                                         $sWhereAtraso.$sGroupBy
                                                                                        );
    $sSqlAcervosAberto = $oDaoEmprestivoAcervo->sql_query_emprestimos_acervo_com_autor(null,
                                                                                       $sCampos . ", 2 as situacao",
                                                                                       $sOrdem,
                                                                                       $sWhereAberto.$sGroupBy
                                                                                      );
    
    $sSqlAcervosDevolvido = $oDaoEmprestivoAcervo->sql_query_emprestimos_acervo_com_autor(null,
                                                                                          $sCampos. ", 3 as situacao",
                                                                                          $sOrdem,
                                                                                          $sWhereDevolvido.$sGroupBy
                                                                                         );
    
    $sSqlAcervosRetirados  = " select * from ({$sSqlAcervosAtrasado}) as a ";
    $sSqlAcervosRetirados .= " union all ";
    $sSqlAcervosRetirados .= " select * from ({$sSqlAcervosAberto}) as b";
    $sSqlAcervosRetirados .= " union all ";
    $sSqlAcervosRetirados .= " select * from ({$sSqlAcervosDevolvido}) as c";
    
    $rsAcervosRetirados = $oDaoEmprestivoAcervo->sql_record($sSqlAcervosRetirados);
    $iTotalEmprestimos  = $oDaoEmprestivoAcervo->numrows;
    
    $aSituacao = array(1 => "ATRASADO", 2 => 'ABERTO', 3 => 'DEVOLVIDOS');
    
    if ($rsAcervosRetirados && $iTotalEmprestimos > 0) {
      
      for ($iExemplar = 0; $iExemplar < $iTotalEmprestimos; $iExemplar++) {
        
        $oDadosEmprestimo = db_utils::fieldsMemory($rsAcervosRetirados, $iExemplar);
        
        $oEmprestimo                          = new stdClass();
        $oEmprestimo->nome_exemplar           = utf8_encode($oDadosEmprestimo->bi06_titulo);
        $oEmprestimo->codigo_exemplar         = utf8_encode($oDadosEmprestimo->bi23_codigo);
        $oEmprestimo->autores                 = utf8_encode($oDadosEmprestimo->autores);
        $oEmprestimo->biblicoteca             = utf8_encode($oDadosEmprestimo->bi17_nome);
        $oEmprestimo->data_retirada           = utf8_encode($oDadosEmprestimo->bi18_retirada);
        $oEmprestimo->data_devolucao_prevista = utf8_encode($oDadosEmprestimo->bi18_devolucao);
        $oEmprestimo->data_devolucao          = utf8_encode($oDadosEmprestimo->bi21_entrega);
        $oEmprestimo->situacao                = utf8_encode($aSituacao[$oDadosEmprestimo->situacao]);
        
        $oDadosLeitorAluno->emprestimos[] = $oEmprestimo;
      }
    }
    return $oDadosLeitorAluno;
  }
  
}
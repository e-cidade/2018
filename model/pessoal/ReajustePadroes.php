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


class ReajustePadroes{

  private $oPdf;
  private $iAnoUsu;
  private $iMesUsu;
  private $iTipoReajuste;
  private $oDaoPadroes;

  /**
   * Construtor da classe, 
   * instancia os atributos da classe.
   * 
   * @param integer $iAnoUsu       Ano da Competência
   * @param integer $iMesUsu       Mes da Competência
   * @param integer $iTipoReajuste Tipo de Reajuste
   */
  public function ReajustePadroes($iAnoUsu, $iMesUsu, $iTipoReajuste){

    $this->iAnoUsu       = $iAnoUsu;
    $this->iMesUsu       = $iMesUsu;
    $this->iTipoReajuste = $iTipoReajuste;
    $this->oDaoPadroes   = db_utils::getDao('padroes');
  }

  /**
   * Realiza validação de todos os padrões. Um padrão só é válido quando todas 
   * as matriculas contidas neste padrão possuem o mesmo tipo de reajuste.
   * 
   * @return mixed - Caso de possuir dados inconsistentes com a regra, 
   *                 retorna o caminho do relatorio de inconsistências
   *               - Caso todos os dados sejam válidos retorna true
   */
  public function validaPadroes() {

    $rsPadroesInvalidos     = $this->getPadroesInvalidos();
    $iTotalPadroesInvalidos = pg_num_rows($rsPadroesInvalidos);

    if ($iTotalPadroesInvalidos  > 0 ) {
      
      $aInconsistencias = array();
      for ($iPadrao = 0; $iPadrao < $iTotalPadroesInvalidos; $iPadrao++){

        $oInconsistencias = new stdClass();
        $oPadrao          = db_utils::fieldsMemory($rsPadroesInvalidos,$iPadrao);

        $oInconsistencias->r02_codigo = $oPadrao->r02_codigo . ' - ' . $oPadrao->r02_descr;
        $oInconsistencias->motivo = 'Servidor com Tipo de Reajuste Salarial divergente do selecionado.';

        $rsServidoresInvalidos = $this->getServidoresInvalidos($oPadrao);  

        $oInconsistencias->aServidores = db_utils::getColectionByRecord($rsServidoresInvalidos);
        $aInconsistencias[] = $oInconsistencias;
      }

      return $this->geraRelatorioInconsistencias($aInconsistencias);
    }

    return true;
  }


  /**
   * Retorna os padrões inválidos. Um padrão inválido é 
   * aquele que possui servidores com diferentes tipos de reajuste.
   * 
   * @return resource rsPadroesInvalidos. 
   */
  private function getPadroesInvalidos() {

    $sSqlPadroesInvalidos = $this->oDaoPadroes->sql_padroesInvalidos($this->iTipoReajuste, $this->iAnoUsu, $this->iMesUsu, db_getsession("DB_instit"));
    $rsPadroesInvalidos   = db_query($sSqlPadroesInvalidos);

    return $rsPadroesInvalidos;
  }

  /**
   * Retorna os servidores inválidos no padrão informado como parametro.
   * 
   * @param  object $oPadrao Objeto do padrão
   * @return resource rsServidoresInvalidos
   */
  private function getServidoresInvalidos($oPadrao) {

    $sSqlServidoreInvalidos = $this->oDaoPadroes->sql_ServidoresInvalidos($oPadrao->r02_codigo, $oPadrao->r02_regime, $oPadrao->r02_anousu, $oPadrao->r02_mesusu, $this->iTipoReajuste);
    $rsServidoresInvalidos  = db_query($sSqlServidoreInvalidos);
    return $rsServidoresInvalidos;
  }

  public function geraRelatorioInconsistencias( $aInconsistencias = array()) {

    /**
     * Variavel global utilizada para montar o cabeçalho do Relatório
     */
    global $head1, $head2;
    $head1 = "Relatório de Inconsistências de Padrões";
    $head2 = "Competência: {$this->iAnoUsu} / {$this->iMesUsu}";
    $this->oPdf = new PDFNovo();
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->AddPage("P");
    $this->oPdf->SetFillColor(235);

    $this->escrevePdf($aInconsistencias);

    $this->sCaminho = "tmp/Inconsistencias_Padroes.pdf";
    $this->oPdf->Output($this->sCaminho, false, true);

    return $this->sCaminho;
  }

  private function escrevePdf($aInconsistencias) {

    foreach ($aInconsistencias as $oPadrao) {
      
      $this->oPdf->setFont("", "B");
      $this->oPdf->Cell(97.5, 4, "Padrão: ". $oPadrao->r02_codigo, true, false, 'L', true);
      $this->oPdf->Cell(97.5, 4, "Motivo: ". $oPadrao->motivo, true, true, 'L', true);
      $this->oPdf->Cell(30, 4, "Matrícula", true, false, "C", true);
      $this->oPdf->Cell(165, 4, "Nome", true, true, "C", true);
      $this->oPdf->setFont("", "");
      
      foreach ($oPadrao->aServidores as $iIndex => $oServidor) {

        $lFill = $iIndex % 2 != 0;

        $this->oPdf->Cell(30, 4, $oServidor->matricula, true, false, "C", $lFill);
        $this->oPdf->Cell(165, 4, $oServidor->nome, true, true, "L", $lFill);
      }

      $this->oPdf->Ln();
   }

  }
}

?>
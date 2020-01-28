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

require_once("fpdf151/pdf.php");

class GeracaoRelatorioInconsistenciasPit {

  /**
   * Caminho onde foi salvo o Arquivo
   * @var String
   */
  private $sCaminho;

  /**
   * Nome do Arquivo selecionado
   * @var String
   */
  private $sTipoArquivo;

  /**
   * Instância de FPDF
   * @var FPDF
   */
  private $oPdf;

  /**
   * Gera o arquivo de inconsistências, com os erros informados no array aErros.
   * Para quando for do tipo IPTU e ITBI.
   * @param  Array   $aErros array com os erros.
   * @param  Integer $iTipo  Tipo de arquivo.
   * @return Mixed
   *         -Boolean retorna false se o arquivo não for IPTU ou ITBI.
   *         -String  $sCaminho   Caminho do arquivo gerado.
   *         -Integer $iAno       Ano da competência.
   *         -Array   $aPeriodo   Periodo da competência.
   */
  public function gerar($aErros, $iTipo, $iAno, $aPeriodo) {

    /**
     * Se não houverem erros retorna false
     */
    if( count($aErros) == 0 ){
      return false;
    }

    /**
     * Se não houverem erros retorna false
     */
    if( count($aErros) == 0 ){
      return false;
    }

    /**
     * Se não for IPTU ou ITBI retorna false
     */
    if( ! $this->setTipo($iTipo) ){
      return false;
    }

    /**
     * Variavel global utilizada para montar o cabeçalho do Relatório
     */
    global $head1, $head2, $head3;
    $head1 = "Relatório de Inconsistências {$this->sTipoArquivo}";
    $head2 = "Exercício: {$iAno}";
    if ($iTipo != GeracaoArquivoPit::IPTU) {

      $sSemestres = implode($aPeriodo, '/');
      $head3 = "Semestres: {$sSemestres}";
    }

    $this->oPdf = new PDF();
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->AddPage("P");
    $this->oPdf->SetFillColor(235);

    /**
     * Percorre todos os erros, adicionando seu conteudo no PDF.
     */
    foreach ($aErros as $iErro => $sErro) {
      $this->escrevePDF($sErro, $iErro);
    }

    $this->sCaminho = "tmp/Inconsistencias_{$this->sTipoArquivo}-".date('ymdhis').".pdf";
    $this->oPdf->Output($this->sCaminho, false, true);

    return $this->sCaminho;
  }

  /**
   * Verifica o tipo de arquivo escolhido, se não for IPTU ou ITBI return false.
   * @param  $iTipo
   * @return Boolean retorna false se o arquivo não for IPTU ou ITBI.
   */
  private function setTipo($iTipo) {

    switch ($iTipo) {
      case GeracaoArquivoPit::IPTU:
        $this->sTipoArquivo = "IPTU";
      break;
      case GeracaoArquivoPit::ITBI_URBANO:
        $this->sTipoArquivo = "ITBI-URBANO";
      break;
      case GeracaoArquivoPit::ITBI_RURAL:
        $this->sTipoArquivo = "ITBI-RURAL";
      break;
      default:
        return false;
      break;
    }

    return true;
  }

  /**
   * Escreve o erro no PDF
   * @param String $sErro
   * @param Integer $iIndice
   */
  private function escrevePDF($sErro, $iIndice){

    $lFundo = true;

    if ($iIndice % 2 == 0) {
      $lFundo = false;
    }

    $this->oPdf->Cell(195, 4, $sErro, true, true, 'L', $lFundo);
  }
}
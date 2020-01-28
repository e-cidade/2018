<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Classe para Consistencia Cont�bil
 *
 * @package Contabilidade
 * @subpackage Relatorios
 * @version    $Revision: 1.6 $
 * @author  Iuri Guntchnigg iuri@dbseller.com.br
 * @author  Vinicius Martins vinicius@dbseller.com.br
 */
class ConsistenciaContabil extends RelatoriosLegaisBase {

  /**
   * Retorna os dados da consistencia processados, em forma de uma array
   * @return array
   */
  public function getDados() {

    $this->aLinhasConsistencia = $this->getLinhasRelatorio();
    $this->executarBalancetesNecessarios();
    $this->processaTotalizadores($this->aLinhasConsistencia);
    $this->limparDadosLinha();
    return $this->aLinhasConsistencia;
  }


  /**
   * Deixa objeto linha apenas com os dados necess�rios para a emiss�o da consist�ncia
   */
  protected  function limparDadosLinha() {

    foreach ($this->aLinhasConsistencia as $oLinha) {

      $aColunas      = $oLinha->colunas;
      $aLinhaColunas = array();
      foreach($aColunas as $oColuna) {

        $oColunaNova            = new stdClass();
        $oColunaNova->descricao = $oColuna->o115_descricao;
        $oColunaNova->nome      = $oColuna->o115_nomecoluna;
        $oColunaNova->valor     = $oLinha->{$oColuna->o115_nomecoluna};
        $aLinhaColunas[]        = $oColunaNova;
      }

      $oLinha->colunas = $aLinhaColunas;
      unset($oLinha->oLinhaRelatorio);
      unset($oLinha->parametros);
    }
  }

  /**
   * Gera um arquivo CSV com os resultados da consist�ncia
   * @return string caminho do arquivo gerado
   */
  public function gerarCSV() {

    $sNomeArquivo  = "tmp/relatorio_consistencia_{$this->iCodigoRelatorio}.csv";
    $aLinhas       = $this->aLinhasConsistencia;

    if (count($this->aLinhasConsistencia) == 0){
      $aLinhas = $this->getDados();
    }
    $aHeader                = array("Linha");
    $aColunasPrimeiraLinhas = $aLinhas[1]->colunas;

    foreach ($aColunasPrimeiraLinhas as $aColuna) {
      array_push($aHeader, $aColuna->descricao);
    }

    $rsArquivo = fopen($sNomeArquivo, 'w');
    fputs($rsArquivo, implode(';', $aHeader)."\n");
    foreach ($aLinhas as $oLinha) {

      $aLinhaCSV = array($oLinha->descricao);
      foreach ($oLinha->colunas as $oColuna) {
        array_push($aLinhaCSV, round($oColuna->valor, 2));
      }
      fputs($rsArquivo, implode(';', $aLinhaCSV)."\n");
    }
    fclose($rsArquivo);
    return $sNomeArquivo;
  }
}

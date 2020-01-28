<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Classe para Consistencia Contábil
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
   * Deixa objeto linha apenas com os dados necessários para a emissão da consistência
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
   * Gera um arquivo CSV com os resultados da consistência
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

<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Layout;

/**
 * Constroi uma instancia de um layout
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @author André Mello  <andre.mello@dbseller.com.br>
 * @version $Revision: 1.2 $
 */
abstract class Layout {

  protected $sNomeArquivo = null;

  /**
   * Instância do layout txt
   * @var db_layouttxt
   */
  protected $oLayout = null;

  function __construct($iLayout) {

    $this->sNomeArquivo = "tmp/situacao_aluno_censo_" . time() . ".txt";
    $this->oLayout      = new \db_layouttxt($iLayout, $this->sNomeArquivo, "", 1);
  }

  public function getNomeArquivo() {
    return $this->sNomeArquivo;
  }

  /**
   * Escreve uma linha no arquivo
   * @param  stdClass $oDados              possui as colunas e os valores cadastrado nos campos do layout
   * @param  integer  $iTipoLinha          Tipo da linha cadastrada no layout
   * @param  string   $sIdentificadorLinha Identificador da linha
   */
  public function escreverLinha($oDados, $iTipoLinha, $sIdentificadorLinha) {

    $this->oLayout->setByLineOfDBUtils($oDados, $iTipoLinha, $sIdentificadorLinha);
  }
}

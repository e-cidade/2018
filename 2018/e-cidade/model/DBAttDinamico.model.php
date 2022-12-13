<?php
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
class DBAttDinamico {

  protected $iCodigo;

  protected $sDescricao;

  protected $aAtributos = array();

  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoCadAttDinamico         = new cl_db_cadattdinamico();
      $oDaoCadAttDinamicoAtributo = new cl_db_cadattdinamicoatributos();

      $rsDadosAttDinamico = $oDaoCadAttDinamico->sql_record($oDaoCadAttDinamico->sql_query_file($iCodigo));

      if ($oDaoCadAttDinamico->numrows > 0) {

        $oDadosAttDinamico = db_utils::fieldsMemory($rsDadosAttDinamico,0);

        $this->setCodigo   ($oDadosAttDinamico->db118_sequencial);
        $this->setDescricao($oDadosAttDinamico->db118_descricao);

        $sWhereAtributos  = "db109_db_cadattdinamico = {$iCodigo}";
        $sSqlAtributos    = $oDaoCadAttDinamicoAtributo->sql_query_file(null,"*",null,$sWhereAtributos);
        $rsDadosAtributos = $oDaoCadAttDinamicoAtributo->sql_record($sSqlAtributos);

        if ($oDaoCadAttDinamicoAtributo->numrows > 0 ) {

          for ($iInd=0; $iInd < $oDaoCadAttDinamicoAtributo->numrows; $iInd++ ) {

            $oAtributo = db_utils::fieldsMemory($rsDadosAtributos,$iInd);
            $this->adicionarAtributo(new DBAttDinamicoAtributo($oAtributo->db109_sequencial));
          }
        }
      }
    }
  }

  public function getCodigo() {
    return $this->iCodigo;
  }

  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  public function getDescricao() {
    return $this->sDescricao;
  }

  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  public function adicionarAtributo(DBAttDinamicoAtributo $oAttDinamicoAtt) {

    if ($oAttDinamicoAtt->getCodigo() != '') {
      $this->aAtributos[$oAttDinamicoAtt->getCodigo()] = $oAttDinamicoAtt;
    } else {
      $this->aAtributos[] = $oAttDinamicoAtt;
    }

  }

  public function alterarAtributo($iInd,DBAttDinamicoAtributo $oAttDinamicoAtt) {
    $this->aAtributos[$iInd] = $oAttDinamicoAtt;
  }

  public function removerAtributo($iInd) {

    $aAtributos = $this->getAtributos();

    $this->aAtributos = array();

    foreach ($aAtributos as $oAtributo) {

      if($oAtributo->getCodigo() == $iInd) {
        continue;
      }
      $this->adicionarAtributo($oAtributo);
    }
  }

  /**
   * @return \DBAttDinamicoAtributo[]
   */
  public function getAtributos(){
    return $this->aAtributos;
  }

  /**
   * Retorna somente os atributos dinâmicos ATIVOS
   * @return \DBAttDinamicoAtributo[]
   */
  public function getAtributosAtivos() {

    $atributosAtivos = array();
    foreach ($this->aAtributos as $oAtributo) {

      if ($oAtributo->ativo()) {
        $atributosAtivos[] = $oAtributo;
      }
    }
    return $atributosAtivos;
  }


  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta!\\n\\nInclusão cancelada.");
    }

    $oDaoCadAttDinamico         = new cl_db_cadattdinamico();
    $oDaoCadAttDinamicoAtributo = new cl_db_cadattdinamicoatributos();

    $oDaoCadAttDinamico->db118_sequencial = $this->getCodigo() ;
    $oDaoCadAttDinamico->db118_descricao  = $this->getDescricao();

    if (trim($this->getCodigo()) == '') {

      $oDaoCadAttDinamico->incluir(null);
      $this->setCodigo($oDaoCadAttDinamico->db118_sequencial);
    } else {
      $oDaoCadAttDinamico->alterar($this->getCodigo());
    }

    if ($oDaoCadAttDinamico->erro_status == 0) {
      throw new Exception($oDaoCadAttDinamico->erro_msg);
    }

    $oDaoCadAttDinamicoAtributo = new cl_db_cadattdinamicoatributos();

    $sWhereAtributos  = " db109_db_cadattdinamico = {$this->getCodigo()}";
    $sSqlAtributos    = $oDaoCadAttDinamicoAtributo->sql_query_file(null,"*",null,$sWhereAtributos);
    $rsDadosAtributos = $oDaoCadAttDinamicoAtributo->sql_record($sSqlAtributos);

    if ( $oDaoCadAttDinamicoAtributo->numrows > 0 ) {

      for ( $iInd=0; $iInd < $oDaoCadAttDinamicoAtributo->numrows; $iInd++ ) {

        $iCodAtributo = db_utils::fieldsMemory($rsDadosAtributos,$iInd)->db109_sequencial;

        $lExcluir = true;

        foreach ($this->getAtributos() as $oAtributo ) {
          if ( $oAtributo->getCodigo() == $iCodAtributo ) {
            $lExcluir = false;
          }
        }

        if ($lExcluir) {
          $oDBAttDinamicoAtributo = new DBAttDinamicoAtributo($iCodAtributo);
          $oDBAttDinamicoAtributo->excluir();
        }
      }
    }

    foreach ($this->getAtributos() as $oAtributo ) {
      $oAtributo->setGrupoAtributo($oDaoCadAttDinamico->db118_sequencial);
      $oAtributo->salvar();
    }

  }

  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta!\\n\\nExclusão cancelada.");
    }

    $oDaoCadAttDinamico = new cl_db_cadattdinamico();

    foreach ($this->getAtributos() as $oAtributo ) {
      $oAtributo->excluir();
    }

    $oDaoSysarquivo = new cl_db_cadattdinamicosysarquivo();
    $oDaoSysarquivo->excluir(null, "db17_cadattdinamico = {$this->getCodigo()}");

    if ($oDaoSysarquivo->erro_status == 0) {
      throw new Exception($oDaoSysarquivo->erro_msg);
    }

    $oDaoCadAttDinamico->excluir($this->getCodigo());

    if ($oDaoCadAttDinamico->erro_status == 0) {
      throw new Exception($oDaoCadAttDinamico->erro_msg);
    }
  }

  /**
   * Retorna o código do grupo de atributos através do código do arquivo no dicionário de dados
   *
   * @param  integer $iCodigoArquivo
   * @return integer|null
   */
  public static function getCodigoPorArquivo($iCodigoArquivo) {

    $oDaoAtibutoArquivo  = new cl_db_cadattdinamicosysarquivo;
    $sWhere              = "db17_sysarquivo = {$iCodigoArquivo}";
    $sSqlAtributoArquivo = $oDaoAtibutoArquivo->sql_query_file(null, 'db17_cadattdinamico', null, $sWhere);
    $rsCodigoArquivo     = db_query($sSqlAtributoArquivo);

    if ($rsCodigoArquivo && pg_num_rows($rsCodigoArquivo) > 0) {
      return db_utils::fieldsMemory($rsCodigoArquivo, 0)->db17_cadattdinamico;
    }

    return null;
  }
}

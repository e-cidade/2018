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

/**
 * Class cl_pontoeletronicoevento
 */
class cl_pontoeletronicoevento extends DAOBasica {

  public function __construct() {
    parent::__construct('recursoshumanos.pontoeletronicoevento');
  }

  public function salvar() {
    empty($this->rh207_sequencial) ? $this->incluir($this->rh207_sequencial) : $this->alterar($this->rh207_sequencial);
  }

  public function sql_query_matricula($codigo, $campos = "*", $order = null, $where = null) {

    $sql  = " select {$campos}";
    $sql .= "   from pontoeletronicoevento";
    $sql .= "        inner join pontoeletronicoeventomatricula on pontoeletronicoeventomatricula.rh208_pontoeletronicoevento = pontoeletronicoevento.rh207_sequencial";

    if (!empty($codigo)) {
      $sql = " where pontoeletronicoevento.rh207_sequencial = {$codigo} ";
    } else if (!empty($where)) {
      $sql .= " where {$where} ";
    }

    if (!empty($order)) {
      $sql .= " order by {$order} ";
    }
    return $sql;
  }

  public function sql_query_join_configuracoesdatasefetividade($key = null, $fields = '*', $order = 'rh207_sequencial asc', $where = null) {

    if(empty($where)) {
      if(empty($key)) {
        throw new Exception("Informe o código do evento à pesquisar.");
      }
      $where = "rh207_sequencial = {$key}";
    }

    $sql = "SELECT {$fields}
              FROM configuracoesdatasefetividade 
        INNER JOIN pontoeletronicoevento ON rh207_instit = rh186_instituicao
             WHERE {$where}
          ORDER BY {$order}
    ";
    return $sql;
  }
}
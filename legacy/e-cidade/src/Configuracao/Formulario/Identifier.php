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
namespace ECidade\Configuracao\Formulario;

/**
 * @author Andrio Costa     <andrio.costa@gmail.com>
 * @author Jeferson Belmiro <jeferson.belmiro@gmail.com>
 */
class Identifier
{
  /**
   * @var Array
   */
  private $tables = array(
    'avaliacao' => 'db101',
    'avaliacaogrupopergunta' => 'db102',
    'avaliacaopergunta' => 'db103',
    'avaliacaoperguntaopcao' => 'db104',
  );

  /**
   * Instancia da dao atual
   * @var mixed
   */
  private $dao;

  /**
   * table name
   * @var string
   */
  private $table;

  /**
   * @var integer
   */
  private $id;

  /**
   * @param string $table
   */
  public function __construct($table, $id = null)
  {
    $className = "\cl_$table";
    $this->dao = new $className;
    $this->table = $table;
    $this->id = $id;
  }

  /**
   * @param string $string
   * @return boolean
   */
  public function validate($string)
  {
    $prefix = $this->tables[$this->table];
    $identifierField = $prefix . '_identificador';
    $idField = $prefix . '_sequencial';
    $where = " $identifierField = '$string'";

    if ($this->id) {
      $where .= " and $idField != $this->id";
    }

    $sql = $this->dao->sql_query_file(null, '1', null, $where);
    $rs  = db_query($sql);

    if ( !$rs ) {
      throw new \Exception("Erro ao buscar identificador.");
    }

    if ( pg_num_rows($rs) == 0 ) {
      return true;
    }

    return false;
  }

  /**
   * @param string $string
   * @return string
   */
  public function slugify($string)
  {
    // gera slugify limitando tamanho
    $slug = mb_strimwidth(\DBString::slugify($string), 0, 50);

    // string valida, nao existe no banco
    if ($this->validate($slug)) {
      return $slug;
    }

    // string nao valida, substitui os ultimos 13 caractes por uniqid
    $slug = mb_strimwidth($string, 0, 37);
    $slug = $slug . uniqid();

    return $this->slugify($slug);
  }

}

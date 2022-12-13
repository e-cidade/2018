<?php
namespace ECidade\Package\Desktop\Model;

use \ECidade\V3\Extension\Model;
use \Exception;

class Window extends Model {

  /**
   * @param integer $iUsuario
   * @param integer $iInstituicao
   * @param string $sData
   * @param integer $iLimit
   * @return array
   */
  public function getDepartamentos($iUsuario, $iInstituicao, $sData, $iLimit = null) {

    $sSql  = "  select distinct d.coddepto, d.descrdepto, u.db17_ordem   ";
    $sSql .= "    from db_depusu u                                       ";
    $sSql .= "         inner join db_depart d on u.coddepto = d.coddepto ";
    $sSql .= "   where instit       = $iInstituicao ";
    $sSql .= "     and u.id_usuario = $iUsuario";
    $sSql .= "     and (d.limite is null or d.limite >= '$sData')";
    $sSql .= "order by u.db17_ordem ";

    if (!empty($iLimit)) {
      $sSql .= " limit $iLimit ";
    }

    $rsDepartamentos = $this->db->execute($sSql);

    if (pg_num_rows($rsDepartamentos) == 0) {
      return array();
    }

    return $this->db->getCollectionByRecord($rsDepartamentos);
  }

  /**
   * @param integer $iUsuario
   * @param integer $iInstituicao
   * @param string $sData
   * @param integer $iLimit
   * @return stdClass
   */
  public function getDepartamento($iDepartamento, $iUsuario, $iInstituicao, $sData, $iLimit = null) {

    $sSql  = "  select distinct d.coddepto, d.descrdepto, u.db17_ordem   ";
    $sSql .= "    from db_depusu u                                       ";
    $sSql .= "         inner join db_depart d on u.coddepto = d.coddepto ";
    $sSql .= "   where d.coddepto = $iDepartamento                       ";
    $sSql .= "     and instit       = $iInstituicao ";
    $sSql .= "     and u.id_usuario = $iUsuario";
    $sSql .= "     and (d.limite is null or d.limite >= '$sData')";

    $rsDepartamentos = $this->db->execute($sSql);

    if (pg_num_rows($rsDepartamentos) == 0) {
      return false;
    }

    return $this->db->fetchRow($rsDepartamentos, 0);
  }

  /**
   * @param integer $codigo
   * @return string
   */
  public function getNomeInstituicao($codigo) {

    $sSql = "select nomeinst from db_config where codigo = $codigo";
    $rsInstiuicao = $this->db->execute($sSql);

    if (pg_num_rows($rsInstiuicao) == 0) {
      return null;
    }

    return $this->db->fetchRow($rsInstiuicao, 0)->nomeinst;
  }

  /**
   * @param integer $codigo
   * @return string
   */
  public function getNomeDepartamento($codigo) {

    $sSql  = "select descrdepto from db_depart where coddepto = $codigo";
    $result = $this->db->execute($sSql);

    if (pg_num_rows($result) == 0) {
      return null;
    }

    return $this->db->fetchRow($result, 0)->descrdepto;
  }

  /**
   * @param integer $idUsuario
   * @param array
   */
  public function getExercicios($idUsuario) {

    if ($idUsuario == 1) {

      $sSql  = "select anousu                  ";
      $sSql .= "  from db_permissao            ";
      $sSql .= " where id_usuario = $idUsuario ";
      $sSql .= "group by id_usuario, anousu    ";
      $sSql .= "order by anousu desc           ";

    } else {

      $sSql  = " select distinct on (anousu) anousu                                                 ";
      $sSql .= "   from (select id_usuario, anousu                                                  ";
      $sSql .= "           from db_permissao                                                        ";
      $sSql .= "          where id_usuario = $idUsuario                                             ";
      $sSql .= "       group by id_usuario, anousu                                                  ";
      $sSql .= "       union all                                                                    ";
      $sSql .= "         select db_permissao.id_usuario, anousu                                     ";
      $sSql .= "           from db_permissao                                                        ";
      $sSql .= "                inner join db_permherda h on h.id_perfil  = db_permissao.id_usuario ";
      $sSql .= "                inner join db_usuarios  u on u.id_usuario = h.id_perfil             ";
      $sSql .= "                                         and u.usuarioativo = '1'                   ";
      $sSql .= "          where h.id_usuario = $idUsuario                                           ";
      $sSql .= "         group by db_permissao.id_usuario, anousu                                   ";
      $sSql .= "         ) as x                                                                     ";
      $sSql .= "order by anousu desc                                                                ";
    }

    $result = $this->db->execute($sSql);

    if (pg_num_rows($result) == 0) {
      throw new Exception("Você não tem permissão de acesso para exercício.");
    }

    $exercicios = array();
    foreach ($this->db->getCollectionByRecord($result) as $data) {
      $exercicios[] = $data['anousu'];
    }

    return $exercicios;
  }

  /**
   * @param integer $usuario
   * @return string
   */
  public function getDataUsuario($usuario) {

    $sql = "select data from db_datausuarios where id_usuario = $usuario";
    $result = $this->db->execute($sql);

    if (pg_num_rows($result) == 0) {
      return false;
    }

    return $this->db->fetchRow($result, 0)->data;
  }

  /**
   * @param integer $idUsuario
   * @param string $data - formato Y-m-d
   * @return void
   */
  public function salvarDataUsuario($idUsuario, $data) {

    $this->db->begin();
    $this->excluirDataUsuario($idUsuario);

    if ($data != date('Y-m-d')) {
      $this->db->execute("INSERT INTO db_datausuarios( id_usuario, data ) VALUES ({$idUsuario}, '{$data}')");
    }

    $this->db->commit();
  }

  /**
   * @param integer $idUsuario
   */
  public function excluirDataUsuario($idUsuario) {

    $this->db->begin();
    $this->db->execute("DELETE FROM db_datausuarios WHERE id_usuario = $idUsuario");
    $this->db->commit();
  }

}

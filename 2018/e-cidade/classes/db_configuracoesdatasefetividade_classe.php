<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

class cl_configuracoesdatasefetividade extends DAOBasica {

  function __construct() {
    parent::__construct("recursoshumanos.configuracoesdatasefetividade");
  }

  function sql_query_file ( $codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from recursoshumanos.configuracoesdatasefetividade ";
    $sql2 = "";
    if($dbwhere==""){
      if($codigo!=null ){
        $sql2 .= " where configuracoesdatasefetividade.rh186_exercicio = $codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  /**
   * re-implementado metodo alterar
   */
  public function alterar2( $iExercicio, $sCompetencia, $iInstituicao ) {

    $sCamposUpdate = "";
    $sVirgula      = "";
    foreach ( $this->getDados() as $sChave => $sValor ) {

      $sCamposUpdate .= "{$sVirgula} {$sChave} = " . $this->formatarAtributo ( $sChave, $sValor );
      $sVirgula       = ",";
    }

    $sWhere  = " where rh186_exercicio   = {$iExercicio} ";
    $sWhere .= "   and rh186_competencia = '{$sCompetencia}' ";
    $sWhere .= "   and rh186_instituicao = {$iInstituicao} ";
    $sSql    = "UPDATE recursoshumanos.configuracoesdatasefetividade SET {$sCamposUpdate}  {$sWhere}";

    $rsUpdate = db_query ( $sSql );
    if ($rsUpdate == false) {

      $this->erro_banco      = str_replace ( "\n", "", @pg_last_error () );
      $this->erro_sql        = "{$this->description} não Alterado. Alteração Abortada.\n";
      $this->erro_sql       .= "Valores : " . $this->getStringCamposChave ();
      $this->erro_msg        = "Usuário: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg       .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
      $this->erro_status     = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {

      if (pg_affected_rows ( $rsUpdate ) == 0) {

        $this->erro_banco      = "";
        $this->erro_sql        = "{$this->descricao} não foi Alterado. Alteraçã Executada.\n";
        $this->erro_sql       .= "Valores : " . $this->getStringCamposChave ();
        $this->erro_msg        = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg       .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
        $this->erro_status     = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {

        $this->erro_banco      = "";
        $this->erro_sql        = "Alteração efetuada com Sucesso\n";
        $this->erro_sql       .= "Valores : " . $this->getStringCamposChave ();
        $this->erro_msg        = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg       .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
        $this->erro_status     = "1";
        $this->numrows_alterar = pg_affected_rows ( $rsUpdate );
        return true;
      }
    }

    return true;
  }
}
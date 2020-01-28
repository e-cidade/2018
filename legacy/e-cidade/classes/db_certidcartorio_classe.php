<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

//MODULO: divida
//CLASSE DA ENTIDADE certidcartorio
class cl_certidcartorio {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $v31_sequencial = 0;
   var $v31_certid = 0;
   var $v31_cartorio = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v31_sequencial = int4 = Código Certidão Cartório
                 v31_certid = int4 = Certidão
                 v31_cartorio = int4 = Cartório
                 ";
   //funcao construtor da classe
   function cl_certidcartorio() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidcartorio");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->v31_sequencial = ($this->v31_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v31_sequencial"]:$this->v31_sequencial);
       $this->v31_certid = ($this->v31_certid == ""?@$GLOBALS["HTTP_POST_VARS"]["v31_certid"]:$this->v31_certid);
       $this->v31_cartorio = ($this->v31_cartorio == ""?@$GLOBALS["HTTP_POST_VARS"]["v31_cartorio"]:$this->v31_cartorio);
     }else{
       $this->v31_sequencial = ($this->v31_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v31_sequencial"]:$this->v31_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($v31_sequencial){
      $this->atualizacampos();
     if($this->v31_certid == null ){
       $this->erro_sql = " Campo Certidão não informado.";
       $this->erro_campo = "v31_certid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v31_cartorio == null ){
       $this->erro_sql = " Campo Cartório não informado.";
       $this->erro_campo = "v31_cartorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v31_sequencial == "" || $v31_sequencial == null ){
       $result = db_query("select nextval('certidcartorio_v31_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidcartorio_v31_sequencial_seq do campo: v31_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v31_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from certidcartorio_v31_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v31_sequencial)){
         $this->erro_sql = " Campo v31_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v31_sequencial = $v31_sequencial;
       }
     }
     if(($this->v31_sequencial == null) || ($this->v31_sequencial == "") ){
       $this->erro_sql = " Campo v31_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidcartorio(
                                       v31_sequencial
                                      ,v31_certid
                                      ,v31_cartorio
                       )
                values (
                                $this->v31_sequencial
                               ,$this->v31_certid
                               ,$this->v31_cartorio
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Certidão Cartório ($this->v31_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Certidão Cartório já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Certidão Cartório ($this->v31_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v31_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v31_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21229,'$this->v31_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3822,21229,'','".AddSlashes(pg_result($resaco,0,'v31_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3822,21230,'','".AddSlashes(pg_result($resaco,0,'v31_certid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3822,21231,'','".AddSlashes(pg_result($resaco,0,'v31_cartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($v31_sequencial=null) {
      $this->atualizacampos();
     $sql = " update certidcartorio set ";
     $virgula = "";
     if(trim($this->v31_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v31_sequencial"])){
       $sql  .= $virgula." v31_sequencial = $this->v31_sequencial ";
       $virgula = ",";
       if(trim($this->v31_sequencial) == null ){
         $this->erro_sql = " Campo Código Certidão Cartório não informado.";
         $this->erro_campo = "v31_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v31_certid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v31_certid"])){
       $sql  .= $virgula." v31_certid = $this->v31_certid ";
       $virgula = ",";
       if(trim($this->v31_certid) == null ){
         $this->erro_sql = " Campo Certidão não informado.";
         $this->erro_campo = "v31_certid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v31_cartorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v31_cartorio"])){
       $sql  .= $virgula." v31_cartorio = $this->v31_cartorio ";
       $virgula = ",";
       if(trim($this->v31_cartorio) == null ){
         $this->erro_sql = " Campo Cartório não informado.";
         $this->erro_campo = "v31_cartorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v31_sequencial!=null){
       $sql .= " v31_sequencial = $this->v31_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v31_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21229,'$this->v31_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v31_sequencial"]) || $this->v31_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3822,21229,'".AddSlashes(pg_result($resaco,$conresaco,'v31_sequencial'))."','$this->v31_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v31_certid"]) || $this->v31_certid != "")
             $resac = db_query("insert into db_acount values($acount,3822,21230,'".AddSlashes(pg_result($resaco,$conresaco,'v31_certid'))."','$this->v31_certid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v31_cartorio"]) || $this->v31_cartorio != "")
             $resac = db_query("insert into db_acount values($acount,3822,21231,'".AddSlashes(pg_result($resaco,$conresaco,'v31_cartorio'))."','$this->v31_cartorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Certidão Cartório não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v31_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Certidão Cartório não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($v31_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($v31_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21229,'$v31_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3822,21229,'','".AddSlashes(pg_result($resaco,$iresaco,'v31_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3822,21230,'','".AddSlashes(pg_result($resaco,$iresaco,'v31_certid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3822,21231,'','".AddSlashes(pg_result($resaco,$iresaco,'v31_cartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from certidcartorio
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($v31_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " v31_sequencial = $v31_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Certidão Cartório não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v31_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Certidão Cartório não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:certidcartorio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($v31_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from certidcartorio ";
     $sql .= "      inner join certid  on  certid.v13_certid = certidcartorio.v31_certid";
     $sql .= "      inner join cartorio  on  cartorio.v82_sequencial = certidcartorio.v31_cartorio";
     $sql .= "      inner join db_config  on  db_config.codigo = certid.v13_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = certid.v13_login";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cartorio.v82_numcgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v31_sequencial)) {
         $sql2 .= " where certidcartorio.v31_sequencial = $v31_sequencial ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql
   public function sql_query_file ($v31_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from certidcartorio ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v31_sequencial)){
         $sql2 .= " where certidcartorio.v31_sequencial = $v31_sequencial ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

  /**
   * Cria query para buscar o recibo da cda
   *
   * @param  string  $sCertidaoCartorio
   * @param  string  $sCampos
   * @return string  query pronta
   */
  public function sql_query_recibo($sCertidaoCartorio, $sCampos = "*") {

    $sSql  = " select {$sCampos}                                                                 ";
    $sSql .= "   from certidcartorio                                                             ";
    $sSql .= "        inner join certidmovimentacao       on v31_sequencial = v32_certidcartorio ";
    $sSql .= "        inner join certidcartoriorecibopaga on v31_sequencial = v33_certidcartorio ";
    $sSql .= "        inner join recibopaga               on v33_numnov     = k00_numnov         ";
    $sSql .= "  where v31_sequencial in ($sCertidaoCartorio)                                     ";
    $sSql .= "  order by certidmovimentacao.v32_sequencial desc                                  ";

    return $sSql;
  }
}

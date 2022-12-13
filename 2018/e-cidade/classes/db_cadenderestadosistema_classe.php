<?php
//MODULO: configuracoes
//CLASSE DA ENTIDADE cadenderestadosistema
class cl_cadenderestadosistema {
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
   var $db300_sequencial = 0;
   var $db300_db_sistemaexterno = 0;
   var $db300_cadenderestado = 0;
   var $db300_codigo = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 db300_sequencial = int4 = Código
                 db300_db_sistemaexterno = int4 = Tipo Sistema
                 db300_cadenderestado = int4 = Estado
                 db300_codigo = varchar(50) = Código no sistema externo
                 ";
   //funcao construtor da classe
   function cl_cadenderestadosistema() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadenderestadosistema");
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
       $this->db300_sequencial = ($this->db300_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db300_sequencial"]:$this->db300_sequencial);
       $this->db300_db_sistemaexterno = ($this->db300_db_sistemaexterno == ""?@$GLOBALS["HTTP_POST_VARS"]["db300_db_sistemaexterno"]:$this->db300_db_sistemaexterno);
       $this->db300_cadenderestado = ($this->db300_cadenderestado == ""?@$GLOBALS["HTTP_POST_VARS"]["db300_cadenderestado"]:$this->db300_cadenderestado);
       $this->db300_codigo = ($this->db300_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db300_codigo"]:$this->db300_codigo);
     }else{
       $this->db300_sequencial = ($this->db300_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db300_sequencial"]:$this->db300_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db300_sequencial){
      $this->atualizacampos();
     if($this->db300_db_sistemaexterno == null ){
       $this->erro_sql = " Campo Tipo Sistema não informado.";
       $this->erro_campo = "db300_db_sistemaexterno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db300_cadenderestado == null ){
       $this->erro_sql = " Campo Estado não informado.";
       $this->erro_campo = "db300_cadenderestado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db300_codigo == null ){
       $this->erro_sql = " Campo Código no sistema externo não informado.";
       $this->erro_campo = "db300_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->db300_sequencial = $db300_sequencial;
     if(($this->db300_sequencial == null) || ($this->db300_sequencial == "") ){
       $this->erro_sql = " Campo db300_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadenderestadosistema(
                                       db300_sequencial
                                      ,db300_db_sistemaexterno
                                      ,db300_cadenderestado
                                      ,db300_codigo
                       )
                values (
                                $this->db300_sequencial
                               ,$this->db300_db_sistemaexterno
                               ,$this->db300_cadenderestado
                               ,'$this->db300_codigo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Codigo do Estado Sistema Externo ($this->db300_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Codigo do Estado Sistema Externo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Codigo do Estado Sistema Externo ($this->db300_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db300_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db300_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21691,'$this->db300_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3899,21691,'','".AddSlashes(pg_result($resaco,0,'db300_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3899,21692,'','".AddSlashes(pg_result($resaco,0,'db300_db_sistemaexterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3899,21693,'','".AddSlashes(pg_result($resaco,0,'db300_cadenderestado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3899,21694,'','".AddSlashes(pg_result($resaco,0,'db300_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($db300_sequencial=null) {
      $this->atualizacampos();
     $sql = " update cadenderestadosistema set ";
     $virgula = "";
     if(trim($this->db300_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db300_sequencial"])){
       $sql  .= $virgula." db300_sequencial = $this->db300_sequencial ";
       $virgula = ",";
       if(trim($this->db300_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "db300_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db300_db_sistemaexterno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db300_db_sistemaexterno"])){
       $sql  .= $virgula." db300_db_sistemaexterno = $this->db300_db_sistemaexterno ";
       $virgula = ",";
       if(trim($this->db300_db_sistemaexterno) == null ){
         $this->erro_sql = " Campo Tipo Sistema não informado.";
         $this->erro_campo = "db300_db_sistemaexterno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db300_cadenderestado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db300_cadenderestado"])){
       $sql  .= $virgula." db300_cadenderestado = $this->db300_cadenderestado ";
       $virgula = ",";
       if(trim($this->db300_cadenderestado) == null ){
         $this->erro_sql = " Campo Estado não informado.";
         $this->erro_campo = "db300_cadenderestado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db300_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db300_codigo"])){
       $sql  .= $virgula." db300_codigo = '$this->db300_codigo' ";
       $virgula = ",";
       if(trim($this->db300_codigo) == null ){
         $this->erro_sql = " Campo Código no sistema externo não informado.";
         $this->erro_campo = "db300_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db300_sequencial!=null){
       $sql .= " db300_sequencial = $this->db300_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db300_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21691,'$this->db300_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db300_sequencial"]) || $this->db300_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3899,21691,'".AddSlashes(pg_result($resaco,$conresaco,'db300_sequencial'))."','$this->db300_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db300_db_sistemaexterno"]) || $this->db300_db_sistemaexterno != "")
             $resac = db_query("insert into db_acount values($acount,3899,21692,'".AddSlashes(pg_result($resaco,$conresaco,'db300_db_sistemaexterno'))."','$this->db300_db_sistemaexterno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db300_cadenderestado"]) || $this->db300_cadenderestado != "")
             $resac = db_query("insert into db_acount values($acount,3899,21693,'".AddSlashes(pg_result($resaco,$conresaco,'db300_cadenderestado'))."','$this->db300_cadenderestado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db300_codigo"]) || $this->db300_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3899,21694,'".AddSlashes(pg_result($resaco,$conresaco,'db300_codigo'))."','$this->db300_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Codigo do Estado Sistema Externo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db300_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Codigo do Estado Sistema Externo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db300_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db300_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($db300_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db300_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21691,'$db300_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3899,21691,'','".AddSlashes(pg_result($resaco,$iresaco,'db300_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3899,21692,'','".AddSlashes(pg_result($resaco,$iresaco,'db300_db_sistemaexterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3899,21693,'','".AddSlashes(pg_result($resaco,$iresaco,'db300_cadenderestado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3899,21694,'','".AddSlashes(pg_result($resaco,$iresaco,'db300_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cadenderestadosistema
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db300_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db300_sequencial = $db300_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Codigo do Estado Sistema Externo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db300_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Codigo do Estado Sistema Externo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db300_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db300_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadenderestadosistema";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($db300_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from cadenderestadosistema ";
     $sql .= "      inner join cadenderestado  on  cadenderestado.db71_sequencial = cadenderestadosistema.db300_cadenderestado";
     $sql .= "      inner join db_sistemaexterno  on  db_sistemaexterno.db124_sequencial = cadenderestadosistema.db300_db_sistemaexterno";
     $sql .= "      inner join cadenderpais  on  cadenderpais.db70_sequencial = cadenderestado.db71_cadenderpais";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db300_sequencial)) {
         $sql2 .= " where cadenderestadosistema.db300_sequencial = $db300_sequencial ";
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
   public function sql_query_file ($db300_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cadenderestadosistema ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db300_sequencial)){
         $sql2 .= " where cadenderestadosistema.db300_sequencial = $db300_sequencial ";
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


  public function sql_buscaEstadoMunicipio($db300_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from cadenderestadosistema ";
    $sql .= "  join cadenderestado    on  cadenderestado.db71_sequencial = cadenderestadosistema.db300_cadenderestado";
    $sql .= "  join db_sistemaexterno on  db_sistemaexterno.db124_sequencial = cadenderestadosistema.db300_db_sistemaexterno";
    $sql .= "  join cadenderpais      on  cadenderpais.db70_sequencial = cadenderestado.db71_cadenderpais ";
    $sql .= "  join cadendermunicipio on cadendermunicipio.db72_cadenderestado = cadenderestado.db71_sequencial ";
    $sql .= "  join cadendermunicipiosistema on cadendermunicipiosistema.db125_cadendermunicipio = cadendermunicipio.db72_sequencial ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($db300_sequencial)) {
        $sql2 .= " where cadenderestadosistema.db300_sequencial = $db300_sequencial ";
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
}
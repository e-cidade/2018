<?
//MODULO: recursoshumanos
//CLASSE DA ENTIDADE tipoassedb_cadattdinamico
class cl_tipoassedb_cadattdinamico { 
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
   var $h79_db_cadattdinamico = 0; 
   var $h79_tipoasse = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h79_db_cadattdinamico = int4 = Cadastro de Atributo dinamico 
                 h79_tipoasse = int4 = Sequencial do Tipo assentamento 
                 ";
   //funcao construtor da classe 
   function cl_tipoassedb_cadattdinamico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipoassedb_cadattdinamico"); 
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
       $this->h79_db_cadattdinamico = ($this->h79_db_cadattdinamico == ""?@$GLOBALS["HTTP_POST_VARS"]["h79_db_cadattdinamico"]:$this->h79_db_cadattdinamico);
       $this->h79_tipoasse = ($this->h79_tipoasse == ""?@$GLOBALS["HTTP_POST_VARS"]["h79_tipoasse"]:$this->h79_tipoasse);
     }else{
       $this->h79_db_cadattdinamico = ($this->h79_db_cadattdinamico == ""?@$GLOBALS["HTTP_POST_VARS"]["h79_db_cadattdinamico"]:$this->h79_db_cadattdinamico);
       $this->h79_tipoasse = ($this->h79_tipoasse == ""?@$GLOBALS["HTTP_POST_VARS"]["h79_tipoasse"]:$this->h79_tipoasse);
     }
   }
   // funcao para Inclusão
   function incluir ($h79_db_cadattdinamico,$h79_tipoasse){ 
      $this->atualizacampos();
       $this->h79_db_cadattdinamico = $h79_db_cadattdinamico; 
       $this->h79_tipoasse = $h79_tipoasse; 
     if(($this->h79_db_cadattdinamico == null) || ($this->h79_db_cadattdinamico == "") ){ 
       $this->erro_sql = " Campo h79_db_cadattdinamico não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->h79_tipoasse == null) || ($this->h79_tipoasse == "") ){ 
       $this->erro_sql = " Campo h79_tipoasse não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipoassedb_cadattdinamico(
                                       h79_db_cadattdinamico 
                                      ,h79_tipoasse 
                       )
                values (
                                $this->h79_db_cadattdinamico 
                               ,$this->h79_tipoasse 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tipoassedb_cadattdinamico ($this->h79_db_cadattdinamico."-".$this->h79_tipoasse) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tipoassedb_cadattdinamico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tipoassedb_cadattdinamico ($this->h79_db_cadattdinamico."-".$this->h79_tipoasse) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h79_db_cadattdinamico."-".$this->h79_tipoasse;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h79_db_cadattdinamico,$this->h79_tipoasse  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21204,'$this->h79_db_cadattdinamico','I')");
         $resac = db_query("insert into db_acountkey values($acount,21205,'$this->h79_tipoasse','I')");
         $resac = db_query("insert into db_acount values($acount,3818,21204,'','".AddSlashes(pg_result($resaco,0,'h79_db_cadattdinamico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3818,21205,'','".AddSlashes(pg_result($resaco,0,'h79_tipoasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($h79_db_cadattdinamico=null,$h79_tipoasse=null) { 
      $this->atualizacampos();
     $sql = " update tipoassedb_cadattdinamico set ";
     $virgula = "";
     if(trim($this->h79_db_cadattdinamico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h79_db_cadattdinamico"])){ 
       $sql  .= $virgula." h79_db_cadattdinamico = $this->h79_db_cadattdinamico ";
       $virgula = ",";
       if(trim($this->h79_db_cadattdinamico) == null ){ 
         $this->erro_sql = " Campo Cadastro de Atributo dinamico não informado.";
         $this->erro_campo = "h79_db_cadattdinamico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h79_tipoasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h79_tipoasse"])){ 
       $sql  .= $virgula." h79_tipoasse = $this->h79_tipoasse ";
       $virgula = ",";
       if(trim($this->h79_tipoasse) == null ){ 
         $this->erro_sql = " Campo Sequencial do Tipo assentamento não informado.";
         $this->erro_campo = "h79_tipoasse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h79_db_cadattdinamico!=null){
       $sql .= " h79_db_cadattdinamico = $this->h79_db_cadattdinamico";
     }
     if($h79_tipoasse!=null){
       $sql .= " and  h79_tipoasse = $this->h79_tipoasse";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h79_db_cadattdinamico,$this->h79_tipoasse));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21204,'$this->h79_db_cadattdinamico','A')");
           $resac = db_query("insert into db_acountkey values($acount,21205,'$this->h79_tipoasse','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h79_db_cadattdinamico"]) || $this->h79_db_cadattdinamico != "")
             $resac = db_query("insert into db_acount values($acount,3818,21204,'".AddSlashes(pg_result($resaco,$conresaco,'h79_db_cadattdinamico'))."','$this->h79_db_cadattdinamico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h79_tipoasse"]) || $this->h79_tipoasse != "")
             $resac = db_query("insert into db_acount values($acount,3818,21205,'".AddSlashes(pg_result($resaco,$conresaco,'h79_tipoasse'))."','$this->h79_tipoasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tipoassedb_cadattdinamico não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h79_db_cadattdinamico."-".$this->h79_tipoasse;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "tipoassedb_cadattdinamico não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h79_db_cadattdinamico."-".$this->h79_tipoasse;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h79_db_cadattdinamico."-".$this->h79_tipoasse;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($h79_db_cadattdinamico=null,$h79_tipoasse=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($h79_db_cadattdinamico,$h79_tipoasse));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21204,'$h79_db_cadattdinamico','E')");
           $resac  = db_query("insert into db_acountkey values($acount,21205,'$h79_tipoasse','E')");
           $resac  = db_query("insert into db_acount values($acount,3818,21204,'','".AddSlashes(pg_result($resaco,$iresaco,'h79_db_cadattdinamico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3818,21205,'','".AddSlashes(pg_result($resaco,$iresaco,'h79_tipoasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tipoassedb_cadattdinamico
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($h79_db_cadattdinamico)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " h79_db_cadattdinamico = $h79_db_cadattdinamico ";
        }
        if (!empty($h79_tipoasse)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " h79_tipoasse = $h79_tipoasse ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tipoassedb_cadattdinamico não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h79_db_cadattdinamico."-".$h79_tipoasse;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "tipoassedb_cadattdinamico não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h79_db_cadattdinamico."-".$h79_tipoasse;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h79_db_cadattdinamico."-".$h79_tipoasse;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipoassedb_cadattdinamico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($h79_db_cadattdinamico = null,$h79_tipoasse = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from tipoassedb_cadattdinamico ";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = tipoassedb_cadattdinamico.h79_tipoasse";
     $sql .= "      inner join db_cadattdinamico  on  db_cadattdinamico.db118_sequencial = tipoassedb_cadattdinamico.h79_db_cadattdinamico";
     $sql .= "      inner join naturezatipoassentamento  on  naturezatipoassentamento.rh159_sequencial = tipoasse.h12_natureza";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h79_db_cadattdinamico)) {
         $sql2 .= " where tipoassedb_cadattdinamico.h79_db_cadattdinamico = $h79_db_cadattdinamico "; 
       } 
       if (!empty($h79_tipoasse)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " tipoassedb_cadattdinamico.h79_tipoasse = $h79_tipoasse "; 
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
   public function sql_query_file ($h79_db_cadattdinamico = null,$h79_tipoasse = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from tipoassedb_cadattdinamico ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h79_db_cadattdinamico)){
         $sql2 .= " where tipoassedb_cadattdinamico.h79_db_cadattdinamico = $h79_db_cadattdinamico "; 
       } 
       if (!empty($h79_tipoasse)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " tipoassedb_cadattdinamico.h79_tipoasse = $h79_tipoasse "; 
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

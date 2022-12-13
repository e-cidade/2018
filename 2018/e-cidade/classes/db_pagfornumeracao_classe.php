<?
//MODULO: caixa
//CLASSE DA ENTIDADE pagfornumeracao
class cl_pagfornumeracao { 
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
   var $o152_sequencial = 0; 
   var $o152_instit = 0; 
   var $o152_numero = 0; 
   var $o152_empagegera = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o152_sequencial = int4 = Código 
                 o152_instit = int4 = Código da Instituição 
                 o152_numero = int4 = Numeração Atual 
                 o152_empagegera = int4 = Código da Remessa 
                 ";
   //funcao construtor da classe 
   function cl_pagfornumeracao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pagfornumeracao"); 
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
       $this->o152_sequencial = ($this->o152_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o152_sequencial"]:$this->o152_sequencial);
       $this->o152_instit = ($this->o152_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o152_instit"]:$this->o152_instit);
       $this->o152_numero = ($this->o152_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["o152_numero"]:$this->o152_numero);
       $this->o152_empagegera = ($this->o152_empagegera == ""?@$GLOBALS["HTTP_POST_VARS"]["o152_empagegera"]:$this->o152_empagegera);
     }else{
       $this->o152_sequencial = ($this->o152_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o152_sequencial"]:$this->o152_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($o152_sequencial){ 
      $this->atualizacampos();
     if($this->o152_instit == null ){ 
       $this->erro_sql = " Campo Código da Instituição não informado.";
       $this->erro_campo = "o152_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o152_numero == null ){ 
       $this->erro_sql = " Campo Numeração Atual não informado.";
       $this->erro_campo = "o152_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o152_empagegera == null ){ 
       $this->erro_sql = " Campo Código da Remessa não informado.";
       $this->erro_campo = "o152_empagegera";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o152_sequencial == "" || $o152_sequencial == null ){
       $result = db_query("select nextval('pagfornumeracao_o152_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pagfornumeracao_o152_sequencial_seq do campo: o152_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o152_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pagfornumeracao_o152_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o152_sequencial)){
         $this->erro_sql = " Campo o152_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o152_sequencial = $o152_sequencial; 
       }
     }
     if(($this->o152_sequencial == null) || ($this->o152_sequencial == "") ){ 
       $this->erro_sql = " Campo o152_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pagfornumeracao(
                                       o152_sequencial 
                                      ,o152_instit 
                                      ,o152_numero 
                                      ,o152_empagegera 
                       )
                values (
                                $this->o152_sequencial 
                               ,$this->o152_instit 
                               ,$this->o152_numero 
                               ,$this->o152_empagegera 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pagfornumeracao ($this->o152_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pagfornumeracao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pagfornumeracao ($this->o152_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o152_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o152_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21911,'$this->o152_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3945,21911,'','".AddSlashes(pg_result($resaco,0,'o152_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3945,21912,'','".AddSlashes(pg_result($resaco,0,'o152_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3945,21913,'','".AddSlashes(pg_result($resaco,0,'o152_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3945,21922,'','".AddSlashes(pg_result($resaco,0,'o152_empagegera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($o152_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pagfornumeracao set ";
     $virgula = "";
     if(trim($this->o152_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o152_sequencial"])){ 
       $sql  .= $virgula." o152_sequencial = $this->o152_sequencial ";
       $virgula = ",";
       if(trim($this->o152_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "o152_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o152_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o152_instit"])){ 
       $sql  .= $virgula." o152_instit = $this->o152_instit ";
       $virgula = ",";
       if(trim($this->o152_instit) == null ){ 
         $this->erro_sql = " Campo Código da Instituição não informado.";
         $this->erro_campo = "o152_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o152_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o152_numero"])){ 
       $sql  .= $virgula." o152_numero = $this->o152_numero ";
       $virgula = ",";
       if(trim($this->o152_numero) == null ){ 
         $this->erro_sql = " Campo Numeração Atual não informado.";
         $this->erro_campo = "o152_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o152_empagegera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o152_empagegera"])){ 
       $sql  .= $virgula." o152_empagegera = $this->o152_empagegera ";
       $virgula = ",";
       if(trim($this->o152_empagegera) == null ){ 
         $this->erro_sql = " Campo Código da Remessa não informado.";
         $this->erro_campo = "o152_empagegera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o152_sequencial!=null){
       $sql .= " o152_sequencial = $this->o152_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o152_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21911,'$this->o152_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o152_sequencial"]) || $this->o152_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3945,21911,'".AddSlashes(pg_result($resaco,$conresaco,'o152_sequencial'))."','$this->o152_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o152_instit"]) || $this->o152_instit != "")
             $resac = db_query("insert into db_acount values($acount,3945,21912,'".AddSlashes(pg_result($resaco,$conresaco,'o152_instit'))."','$this->o152_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o152_numero"]) || $this->o152_numero != "")
             $resac = db_query("insert into db_acount values($acount,3945,21913,'".AddSlashes(pg_result($resaco,$conresaco,'o152_numero'))."','$this->o152_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o152_empagegera"]) || $this->o152_empagegera != "")
             $resac = db_query("insert into db_acount values($acount,3945,21922,'".AddSlashes(pg_result($resaco,$conresaco,'o152_empagegera'))."','$this->o152_empagegera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pagfornumeracao não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o152_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "pagfornumeracao não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o152_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o152_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($o152_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($o152_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21911,'$o152_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3945,21911,'','".AddSlashes(pg_result($resaco,$iresaco,'o152_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3945,21912,'','".AddSlashes(pg_result($resaco,$iresaco,'o152_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3945,21913,'','".AddSlashes(pg_result($resaco,$iresaco,'o152_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3945,21922,'','".AddSlashes(pg_result($resaco,$iresaco,'o152_empagegera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pagfornumeracao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($o152_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " o152_sequencial = $o152_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pagfornumeracao não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o152_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "pagfornumeracao não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o152_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o152_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pagfornumeracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($o152_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from pagfornumeracao ";
     $sql .= "      inner join db_config  on  db_config.codigo = pagfornumeracao.o152_instit";
     $sql .= "      inner join empagegera  on  empagegera.e87_codgera = pagfornumeracao.o152_empagegera";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o152_sequencial)) {
         $sql2 .= " where pagfornumeracao.o152_sequencial = $o152_sequencial "; 
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
   public function sql_query_file ($o152_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pagfornumeracao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o152_sequencial)){
         $sql2 .= " where pagfornumeracao.o152_sequencial = $o152_sequencial "; 
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

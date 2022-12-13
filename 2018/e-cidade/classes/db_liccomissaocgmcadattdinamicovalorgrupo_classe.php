<?
//MODULO: licitacao
//CLASSE DA ENTIDADE liccomissaocgmcadattdinamicovalorgrupo
class cl_liccomissaocgmcadattdinamicovalorgrupo { 
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
   var $l15_sequencial = 0; 
   var $l15_liccomissaocgm = 0; 
   var $l15_cadattdinamicovalorgrupo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l15_sequencial = int4 = Código 
                 l15_liccomissaocgm = int4 = Menbro da Comissão 
                 l15_cadattdinamicovalorgrupo = int4 = Grupo dos Valores 
                 ";
   //funcao construtor da classe 
   function cl_liccomissaocgmcadattdinamicovalorgrupo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liccomissaocgmcadattdinamicovalorgrupo"); 
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
       $this->l15_sequencial = ($this->l15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l15_sequencial"]:$this->l15_sequencial);
       $this->l15_liccomissaocgm = ($this->l15_liccomissaocgm == ""?@$GLOBALS["HTTP_POST_VARS"]["l15_liccomissaocgm"]:$this->l15_liccomissaocgm);
       $this->l15_cadattdinamicovalorgrupo = ($this->l15_cadattdinamicovalorgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["l15_cadattdinamicovalorgrupo"]:$this->l15_cadattdinamicovalorgrupo);
     }else{
       $this->l15_sequencial = ($this->l15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l15_sequencial"]:$this->l15_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($l15_sequencial){ 
      $this->atualizacampos();
     if($this->l15_liccomissaocgm == null ){ 
       $this->erro_sql = " Campo Menbro da Comissão não informado.";
       $this->erro_campo = "l15_liccomissaocgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l15_cadattdinamicovalorgrupo == null ){ 
       $this->erro_sql = " Campo Grupo dos Valores não informado.";
       $this->erro_campo = "l15_cadattdinamicovalorgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l15_sequencial == "" || $l15_sequencial == null ){
       $result = db_query("select nextval('liccomissaocgmcadattdinamicovalorgrupo_l15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liccomissaocgmcadattdinamicovalorgrupo_l15_sequencial_seq do campo: l15_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liccomissaocgmcadattdinamicovalorgrupo_l15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l15_sequencial)){
         $this->erro_sql = " Campo l15_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l15_sequencial = $l15_sequencial; 
       }
     }
     if(($this->l15_sequencial == null) || ($this->l15_sequencial == "") ){ 
       $this->erro_sql = " Campo l15_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liccomissaocgmcadattdinamicovalorgrupo(
                                       l15_sequencial 
                                      ,l15_liccomissaocgm 
                                      ,l15_cadattdinamicovalorgrupo 
                       )
                values (
                                $this->l15_sequencial 
                               ,$this->l15_liccomissaocgm 
                               ,$this->l15_cadattdinamicovalorgrupo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores dos Atributos Dinâmicos ($this->l15_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores dos Atributos Dinâmicos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores dos Atributos Dinâmicos ($this->l15_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l15_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l15_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21710,'$this->l15_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3901,21710,'','".AddSlashes(pg_result($resaco,0,'l15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3901,21711,'','".AddSlashes(pg_result($resaco,0,'l15_liccomissaocgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3901,21712,'','".AddSlashes(pg_result($resaco,0,'l15_cadattdinamicovalorgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($l15_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update liccomissaocgmcadattdinamicovalorgrupo set ";
     $virgula = "";
     if(trim($this->l15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l15_sequencial"])){ 
       $sql  .= $virgula." l15_sequencial = $this->l15_sequencial ";
       $virgula = ",";
       if(trim($this->l15_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "l15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l15_liccomissaocgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l15_liccomissaocgm"])){ 
       $sql  .= $virgula." l15_liccomissaocgm = $this->l15_liccomissaocgm ";
       $virgula = ",";
       if(trim($this->l15_liccomissaocgm) == null ){ 
         $this->erro_sql = " Campo Menbro da Comissão não informado.";
         $this->erro_campo = "l15_liccomissaocgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l15_cadattdinamicovalorgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l15_cadattdinamicovalorgrupo"])){ 
       $sql  .= $virgula." l15_cadattdinamicovalorgrupo = $this->l15_cadattdinamicovalorgrupo ";
       $virgula = ",";
       if(trim($this->l15_cadattdinamicovalorgrupo) == null ){ 
         $this->erro_sql = " Campo Grupo dos Valores não informado.";
         $this->erro_campo = "l15_cadattdinamicovalorgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l15_sequencial!=null){
       $sql .= " l15_sequencial = $this->l15_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l15_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21710,'$this->l15_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l15_sequencial"]) || $this->l15_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3901,21710,'".AddSlashes(pg_result($resaco,$conresaco,'l15_sequencial'))."','$this->l15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l15_liccomissaocgm"]) || $this->l15_liccomissaocgm != "")
             $resac = db_query("insert into db_acount values($acount,3901,21711,'".AddSlashes(pg_result($resaco,$conresaco,'l15_liccomissaocgm'))."','$this->l15_liccomissaocgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l15_cadattdinamicovalorgrupo"]) || $this->l15_cadattdinamicovalorgrupo != "")
             $resac = db_query("insert into db_acount values($acount,3901,21712,'".AddSlashes(pg_result($resaco,$conresaco,'l15_cadattdinamicovalorgrupo'))."','$this->l15_cadattdinamicovalorgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores dos Atributos Dinâmicos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Valores dos Atributos Dinâmicos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($l15_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($l15_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21710,'$l15_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3901,21710,'','".AddSlashes(pg_result($resaco,$iresaco,'l15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3901,21711,'','".AddSlashes(pg_result($resaco,$iresaco,'l15_liccomissaocgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3901,21712,'','".AddSlashes(pg_result($resaco,$iresaco,'l15_cadattdinamicovalorgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from liccomissaocgmcadattdinamicovalorgrupo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($l15_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " l15_sequencial = $l15_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores dos Atributos Dinâmicos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Valores dos Atributos Dinâmicos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:liccomissaocgmcadattdinamicovalorgrupo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($l15_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from liccomissaocgmcadattdinamicovalorgrupo ";
     $sql .= "      inner join liccomissaocgm  on  liccomissaocgm.l31_codigo = liccomissaocgmcadattdinamicovalorgrupo.l15_liccomissaocgm";
     $sql .= "      inner join db_cadattdinamicovalorgrupo  on  db_cadattdinamicovalorgrupo.db120_sequencial = liccomissaocgmcadattdinamicovalorgrupo.l15_cadattdinamicovalorgrupo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = liccomissaocgm.l31_numcgm";
     $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liccomissaocgm.l31_liccomissao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l15_sequencial)) {
         $sql2 .= " where liccomissaocgmcadattdinamicovalorgrupo.l15_sequencial = $l15_sequencial "; 
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
   public function sql_query_file ($l15_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from liccomissaocgmcadattdinamicovalorgrupo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l15_sequencial)){
         $sql2 .= " where liccomissaocgmcadattdinamicovalorgrupo.l15_sequencial = $l15_sequencial "; 
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

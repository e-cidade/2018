<?
//MODULO: material
//CLASSE DA ENTIDADE planilhadistribuicaomaterial
class cl_planilhadistribuicaomaterial { 
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
   var $pd03_sequencial = 0; 
   var $pd03_planilhadistribuicao = 0; 
   var $pd03_material = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pd03_sequencial = int4 = Código 
                 pd03_planilhadistribuicao = int4 = Planilha de Distribuição 
                 pd03_material = int4 = Material 
                 ";
   //funcao construtor da classe 
   function cl_planilhadistribuicaomaterial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("planilhadistribuicaomaterial"); 
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
       $this->pd03_sequencial = ($this->pd03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pd03_sequencial"]:$this->pd03_sequencial);
       $this->pd03_planilhadistribuicao = ($this->pd03_planilhadistribuicao == ""?@$GLOBALS["HTTP_POST_VARS"]["pd03_planilhadistribuicao"]:$this->pd03_planilhadistribuicao);
       $this->pd03_material = ($this->pd03_material == ""?@$GLOBALS["HTTP_POST_VARS"]["pd03_material"]:$this->pd03_material);
     }else{
       $this->pd03_sequencial = ($this->pd03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pd03_sequencial"]:$this->pd03_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($pd03_sequencial){ 
      $this->atualizacampos();
     if($this->pd03_planilhadistribuicao == null ){ 
       $this->erro_sql = " Campo Planilha de Distribuição não informado.";
       $this->erro_campo = "pd03_planilhadistribuicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pd03_material == null ){ 
       $this->erro_sql = " Campo Material não informado.";
       $this->erro_campo = "pd03_material";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pd03_sequencial == "" || $pd03_sequencial == null ){
       $result = db_query("select nextval('planilhadistribuicaomaterial_pd03_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: planilhadistribuicaomaterial_pd03_sequencial_seq do campo: pd03_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pd03_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from planilhadistribuicaomaterial_pd03_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pd03_sequencial)){
         $this->erro_sql = " Campo pd03_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pd03_sequencial = $pd03_sequencial; 
       }
     }
     if(($this->pd03_sequencial == null) || ($this->pd03_sequencial == "") ){ 
       $this->erro_sql = " Campo pd03_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into planilhadistribuicaomaterial(
                                       pd03_sequencial 
                                      ,pd03_planilhadistribuicao 
                                      ,pd03_material 
                       )
                values (
                                $this->pd03_sequencial 
                               ,$this->pd03_planilhadistribuicao 
                               ,$this->pd03_material 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Material da Planilha de Distribuição ($this->pd03_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Material da Planilha de Distribuição já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Material da Planilha de Distribuição ($this->pd03_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pd03_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pd03_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21588,'$this->pd03_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3876,21588,'','".AddSlashes(pg_result($resaco,0,'pd03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3876,21589,'','".AddSlashes(pg_result($resaco,0,'pd03_planilhadistribuicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3876,21590,'','".AddSlashes(pg_result($resaco,0,'pd03_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($pd03_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update planilhadistribuicaomaterial set ";
     $virgula = "";
     if(trim($this->pd03_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pd03_sequencial"])){ 
       $sql  .= $virgula." pd03_sequencial = $this->pd03_sequencial ";
       $virgula = ",";
       if(trim($this->pd03_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "pd03_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pd03_planilhadistribuicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pd03_planilhadistribuicao"])){ 
       $sql  .= $virgula." pd03_planilhadistribuicao = $this->pd03_planilhadistribuicao ";
       $virgula = ",";
       if(trim($this->pd03_planilhadistribuicao) == null ){ 
         $this->erro_sql = " Campo Planilha de Distribuição não informado.";
         $this->erro_campo = "pd03_planilhadistribuicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pd03_material)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pd03_material"])){ 
       $sql  .= $virgula." pd03_material = $this->pd03_material ";
       $virgula = ",";
       if(trim($this->pd03_material) == null ){ 
         $this->erro_sql = " Campo Material não informado.";
         $this->erro_campo = "pd03_material";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pd03_sequencial!=null){
       $sql .= " pd03_sequencial = $this->pd03_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pd03_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21588,'$this->pd03_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pd03_sequencial"]) || $this->pd03_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3876,21588,'".AddSlashes(pg_result($resaco,$conresaco,'pd03_sequencial'))."','$this->pd03_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pd03_planilhadistribuicao"]) || $this->pd03_planilhadistribuicao != "")
             $resac = db_query("insert into db_acount values($acount,3876,21589,'".AddSlashes(pg_result($resaco,$conresaco,'pd03_planilhadistribuicao'))."','$this->pd03_planilhadistribuicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pd03_material"]) || $this->pd03_material != "")
             $resac = db_query("insert into db_acount values($acount,3876,21590,'".AddSlashes(pg_result($resaco,$conresaco,'pd03_material'))."','$this->pd03_material',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Material da Planilha de Distribuição não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pd03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Material da Planilha de Distribuição não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pd03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pd03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($pd03_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($pd03_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21588,'$pd03_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3876,21588,'','".AddSlashes(pg_result($resaco,$iresaco,'pd03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3876,21589,'','".AddSlashes(pg_result($resaco,$iresaco,'pd03_planilhadistribuicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3876,21590,'','".AddSlashes(pg_result($resaco,$iresaco,'pd03_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from planilhadistribuicaomaterial
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($pd03_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " pd03_sequencial = $pd03_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Material da Planilha de Distribuição não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pd03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Material da Planilha de Distribuição não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pd03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pd03_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:planilhadistribuicaomaterial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($pd03_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from planilhadistribuicaomaterial ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = planilhadistribuicaomaterial.pd03_material";
     $sql .= "      inner join planilhadistribuicao  on  planilhadistribuicao.pd01_sequencial = planilhadistribuicaomaterial.pd03_planilhadistribuicao";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pd03_sequencial)) {
         $sql2 .= " where planilhadistribuicaomaterial.pd03_sequencial = $pd03_sequencial "; 
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
   public function sql_query_file ($pd03_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from planilhadistribuicaomaterial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pd03_sequencial)){
         $sql2 .= " where planilhadistribuicaomaterial.pd03_sequencial = $pd03_sequencial "; 
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

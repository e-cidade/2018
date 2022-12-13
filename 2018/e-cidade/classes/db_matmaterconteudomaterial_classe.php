<?
//MODULO: material
//CLASSE DA ENTIDADE matmaterconteudomaterial
class cl_matmaterconteudomaterial { 
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
   var $m08_codigo = 0; 
   var $m08_matmater = 0; 
   var $m08_unidade = 0; 
   var $m08_quantidade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m08_codigo = int4 = Código 
                 m08_matmater = int4 = Material 
                 m08_unidade = int4 = Unidade 
                 m08_quantidade = float8 = Quantidade 
                 ";
   //funcao construtor da classe 
   function cl_matmaterconteudomaterial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matmaterconteudomaterial"); 
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
       $this->m08_codigo = ($this->m08_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m08_codigo"]:$this->m08_codigo);
       $this->m08_matmater = ($this->m08_matmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m08_matmater"]:$this->m08_matmater);
       $this->m08_unidade = ($this->m08_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["m08_unidade"]:$this->m08_unidade);
       $this->m08_quantidade = ($this->m08_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["m08_quantidade"]:$this->m08_quantidade);
     }else{
       $this->m08_codigo = ($this->m08_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m08_codigo"]:$this->m08_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($m08_codigo){ 
      $this->atualizacampos();
     if($this->m08_matmater == null ){ 
       $this->erro_sql = " Campo Material não informado.";
       $this->erro_campo = "m08_matmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m08_unidade == null ){ 
       $this->erro_sql = " Campo Unidade não informado.";
       $this->erro_campo = "m08_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m08_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "m08_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m08_codigo == "" || $m08_codigo == null ){
       $result = db_query("select nextval('matmaterconteudomaterial_m08_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matmaterconteudomaterial_m08_codigo_seq do campo: m08_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m08_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matmaterconteudomaterial_m08_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m08_codigo)){
         $this->erro_sql = " Campo m08_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m08_codigo = $m08_codigo; 
       }
     }
     if(($this->m08_codigo == null) || ($this->m08_codigo == "") ){ 
       $this->erro_sql = " Campo m08_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matmaterconteudomaterial(
                                       m08_codigo 
                                      ,m08_matmater 
                                      ,m08_unidade 
                                      ,m08_quantidade 
                       )
                values (
                                $this->m08_codigo 
                               ,$this->m08_matmater 
                               ,$this->m08_unidade 
                               ,$this->m08_quantidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Controle Fracionamento ($this->m08_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Controle Fracionamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Controle Fracionamento ($this->m08_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m08_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m08_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21324,'$this->m08_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3840,21324,'','".AddSlashes(pg_result($resaco,0,'m08_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3840,21325,'','".AddSlashes(pg_result($resaco,0,'m08_matmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3840,21326,'','".AddSlashes(pg_result($resaco,0,'m08_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3840,21327,'','".AddSlashes(pg_result($resaco,0,'m08_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($m08_codigo=null) { 
      $this->atualizacampos();
     $sql = " update matmaterconteudomaterial set ";
     $virgula = "";
     if(trim($this->m08_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m08_codigo"])){ 
       $sql  .= $virgula." m08_codigo = $this->m08_codigo ";
       $virgula = ",";
       if(trim($this->m08_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "m08_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m08_matmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m08_matmater"])){ 
       $sql  .= $virgula." m08_matmater = $this->m08_matmater ";
       $virgula = ",";
       if(trim($this->m08_matmater) == null ){ 
         $this->erro_sql = " Campo Material não informado.";
         $this->erro_campo = "m08_matmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m08_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m08_unidade"])){ 
       $sql  .= $virgula." m08_unidade = $this->m08_unidade ";
       $virgula = ",";
       if(trim($this->m08_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade não informado.";
         $this->erro_campo = "m08_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m08_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m08_quantidade"])){ 
       $sql  .= $virgula." m08_quantidade = $this->m08_quantidade ";
       $virgula = ",";
       if(trim($this->m08_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "m08_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m08_codigo!=null){
       $sql .= " m08_codigo = $this->m08_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m08_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21324,'$this->m08_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m08_codigo"]) || $this->m08_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3840,21324,'".AddSlashes(pg_result($resaco,$conresaco,'m08_codigo'))."','$this->m08_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m08_matmater"]) || $this->m08_matmater != "")
             $resac = db_query("insert into db_acount values($acount,3840,21325,'".AddSlashes(pg_result($resaco,$conresaco,'m08_matmater'))."','$this->m08_matmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m08_unidade"]) || $this->m08_unidade != "")
             $resac = db_query("insert into db_acount values($acount,3840,21326,'".AddSlashes(pg_result($resaco,$conresaco,'m08_unidade'))."','$this->m08_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m08_quantidade"]) || $this->m08_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3840,21327,'".AddSlashes(pg_result($resaco,$conresaco,'m08_quantidade'))."','$this->m08_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle Fracionamento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m08_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Controle Fracionamento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($m08_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($m08_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21324,'$m08_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3840,21324,'','".AddSlashes(pg_result($resaco,$iresaco,'m08_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3840,21325,'','".AddSlashes(pg_result($resaco,$iresaco,'m08_matmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3840,21326,'','".AddSlashes(pg_result($resaco,$iresaco,'m08_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3840,21327,'','".AddSlashes(pg_result($resaco,$iresaco,'m08_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from matmaterconteudomaterial
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($m08_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " m08_codigo = $m08_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle Fracionamento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m08_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Controle Fracionamento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m08_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matmaterconteudomaterial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($m08_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from matmaterconteudomaterial ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matmaterconteudomaterial.m08_matmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmaterconteudomaterial.m08_unidade";
     $sql .= "      inner join matunid  as a on   a.m61_codmatunid = matmater.m60_codmatunid";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($m08_codigo)) {
         $sql2 .= " where matmaterconteudomaterial.m08_codigo = $m08_codigo "; 
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
   public function sql_query_file ($m08_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from matmaterconteudomaterial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($m08_codigo)){
         $sql2 .= " where matmaterconteudomaterial.m08_codigo = $m08_codigo "; 
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

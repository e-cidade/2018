<?php
//MODULO: biblioteca
//CLASSE DA ENTIDADE bib_parametros
class cl_bib_parametros { 
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
   var $bi26_codigo = 0; 
   var $bi26_biblioteca = 0; 
   var $bi26_leitorbarra = null; 
   var $bi26_impressora = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bi26_codigo = int8 = Código 
                 bi26_biblioteca = int8 = Biblioteca 
                 bi26_leitorbarra = char(1) = Usar Leitor de Código de Barras 
                 bi26_impressora = int4 = Impressão de Comprovantes 
                 ";
   //funcao construtor da classe 
   function cl_bib_parametros() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bib_parametros"); 
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
       $this->bi26_codigo = ($this->bi26_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi26_codigo"]:$this->bi26_codigo);
       $this->bi26_biblioteca = ($this->bi26_biblioteca == ""?@$GLOBALS["HTTP_POST_VARS"]["bi26_biblioteca"]:$this->bi26_biblioteca);
       $this->bi26_leitorbarra = ($this->bi26_leitorbarra == ""?@$GLOBALS["HTTP_POST_VARS"]["bi26_leitorbarra"]:$this->bi26_leitorbarra);
       $this->bi26_impressora = ($this->bi26_impressora == ""?@$GLOBALS["HTTP_POST_VARS"]["bi26_impressora"]:$this->bi26_impressora);
     }else{
       $this->bi26_codigo = ($this->bi26_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi26_codigo"]:$this->bi26_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($bi26_codigo){ 
      $this->atualizacampos();
     if($this->bi26_biblioteca == null ){ 
       $this->erro_sql = " Campo Biblioteca não informado.";
       $this->erro_campo = "bi26_biblioteca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi26_leitorbarra == null ){ 
       $this->erro_sql = " Campo Usar Leitor de Código de Barras não informado.";
       $this->erro_campo = "bi26_leitorbarra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi26_impressora == null ){ 
       $this->bi26_impressora = "1";
     }
     if($bi26_codigo == "" || $bi26_codigo == null ){
       $result = db_query("select nextval('bib_parametros_bi26_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bib_parametros_bi26_codigo_seq do campo: bi26_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bi26_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bib_parametros_bi26_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi26_codigo)){
         $this->erro_sql = " Campo bi26_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi26_codigo = $bi26_codigo; 
       }
     }
     if(($this->bi26_codigo == null) || ($this->bi26_codigo == "") ){ 
       $this->erro_sql = " Campo bi26_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bib_parametros(
                                       bi26_codigo 
                                      ,bi26_biblioteca 
                                      ,bi26_leitorbarra 
                                      ,bi26_impressora 
                       )
                values (
                                $this->bi26_codigo 
                               ,$this->bi26_biblioteca 
                               ,'$this->bi26_leitorbarra' 
                               ,$this->bi26_impressora 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parâmetros Módulo Biblioteca ($this->bi26_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parâmetros Módulo Biblioteca já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parâmetros Módulo Biblioteca ($this->bi26_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi26_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->bi26_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12285,'$this->bi26_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2138,12285,'','".AddSlashes(pg_result($resaco,0,'bi26_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2138,12286,'','".AddSlashes(pg_result($resaco,0,'bi26_biblioteca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2138,12287,'','".AddSlashes(pg_result($resaco,0,'bi26_leitorbarra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2138,21910,'','".AddSlashes(pg_result($resaco,0,'bi26_impressora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($bi26_codigo=null) { 
      $this->atualizacampos();
     $sql = " update bib_parametros set ";
     $virgula = "";
     if(trim($this->bi26_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi26_codigo"])){ 
       $sql  .= $virgula." bi26_codigo = $this->bi26_codigo ";
       $virgula = ",";
       if(trim($this->bi26_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "bi26_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi26_biblioteca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi26_biblioteca"])){ 
       $sql  .= $virgula." bi26_biblioteca = $this->bi26_biblioteca ";
       $virgula = ",";
       if(trim($this->bi26_biblioteca) == null ){ 
         $this->erro_sql = " Campo Biblioteca não informado.";
         $this->erro_campo = "bi26_biblioteca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi26_leitorbarra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi26_leitorbarra"])){ 
       $sql  .= $virgula." bi26_leitorbarra = '$this->bi26_leitorbarra' ";
       $virgula = ",";
       if(trim($this->bi26_leitorbarra) == null ){ 
         $this->erro_sql = " Campo Usar Leitor de Código de Barras não informado.";
         $this->erro_campo = "bi26_leitorbarra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi26_impressora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi26_impressora"])){ 
        if(trim($this->bi26_impressora)=="" && isset($GLOBALS["HTTP_POST_VARS"]["bi26_impressora"])){ 
           $this->bi26_impressora = "1" ; 
        } 
       $sql  .= $virgula." bi26_impressora = $this->bi26_impressora ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($bi26_codigo!=null){
       $sql .= " bi26_codigo = $this->bi26_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->bi26_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,12285,'$this->bi26_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi26_codigo"]) || $this->bi26_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2138,12285,'".AddSlashes(pg_result($resaco,$conresaco,'bi26_codigo'))."','$this->bi26_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi26_biblioteca"]) || $this->bi26_biblioteca != "")
             $resac = db_query("insert into db_acount values($acount,2138,12286,'".AddSlashes(pg_result($resaco,$conresaco,'bi26_biblioteca'))."','$this->bi26_biblioteca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi26_leitorbarra"]) || $this->bi26_leitorbarra != "")
             $resac = db_query("insert into db_acount values($acount,2138,12287,'".AddSlashes(pg_result($resaco,$conresaco,'bi26_leitorbarra'))."','$this->bi26_leitorbarra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi26_impressora"]) || $this->bi26_impressora != "")
             $resac = db_query("insert into db_acount values($acount,2138,21910,'".AddSlashes(pg_result($resaco,$conresaco,'bi26_impressora'))."','$this->bi26_impressora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros Módulo Biblioteca não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi26_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros Módulo Biblioteca não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi26_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi26_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($bi26_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($bi26_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,12285,'$bi26_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2138,12285,'','".AddSlashes(pg_result($resaco,$iresaco,'bi26_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2138,12286,'','".AddSlashes(pg_result($resaco,$iresaco,'bi26_biblioteca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2138,12287,'','".AddSlashes(pg_result($resaco,$iresaco,'bi26_leitorbarra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2138,21910,'','".AddSlashes(pg_result($resaco,$iresaco,'bi26_impressora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from bib_parametros
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($bi26_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " bi26_codigo = $bi26_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros Módulo Biblioteca não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi26_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros Módulo Biblioteca não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi26_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi26_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:bib_parametros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($bi26_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from bib_parametros ";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = bib_parametros.bi26_biblioteca";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = biblioteca.bi17_coddepto";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($bi26_codigo)) {
         $sql2 .= " where bib_parametros.bi26_codigo = $bi26_codigo "; 
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
   public function sql_query_file ($bi26_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from bib_parametros ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($bi26_codigo)){
         $sql2 .= " where bib_parametros.bi26_codigo = $bi26_codigo "; 
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


<?
//MODULO: orcamento
//CLASSE DA ENTIDADE cronogramaperspectivaacompanhamento
class cl_cronogramaperspectivaacompanhamento { 
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
   var $o151_sequencial = 0; 
   var $o151_cronogramaperspectivaorigem = 0; 
   var $o151_cronogramaperspectiva = 0; 
   var $o151_mes = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o151_sequencial = int4 = Código Sequencial 
                 o151_cronogramaperspectivaorigem = int4 = Perspesctiva Origem 
                 o151_cronogramaperspectiva = int4 = Perspesctiva 
                 o151_mes = int4 = Mês 
                 ";
   //funcao construtor da classe 
   function cl_cronogramaperspectivaacompanhamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cronogramaperspectivaacompanhamento"); 
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
       $this->o151_sequencial = ($this->o151_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o151_sequencial"]:$this->o151_sequencial);
       $this->o151_cronogramaperspectivaorigem = ($this->o151_cronogramaperspectivaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["o151_cronogramaperspectivaorigem"]:$this->o151_cronogramaperspectivaorigem);
       $this->o151_cronogramaperspectiva = ($this->o151_cronogramaperspectiva == ""?@$GLOBALS["HTTP_POST_VARS"]["o151_cronogramaperspectiva"]:$this->o151_cronogramaperspectiva);
       $this->o151_mes = ($this->o151_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o151_mes"]:$this->o151_mes);
     }else{
       $this->o151_sequencial = ($this->o151_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o151_sequencial"]:$this->o151_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($o151_sequencial){ 
      $this->atualizacampos();
     if($this->o151_cronogramaperspectivaorigem == null ){ 
       $this->erro_sql = " Campo Perspesctiva Origem não informado.";
       $this->erro_campo = "o151_cronogramaperspectivaorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o151_cronogramaperspectiva == null ){ 
       $this->erro_sql = " Campo Perspesctiva não informado.";
       $this->erro_campo = "o151_cronogramaperspectiva";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o151_mes == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "o151_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o151_sequencial == "" || $o151_sequencial == null ){
       $result = db_query("select nextval('cronogramaperspectivaacompanhamento_o151_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cronogramaperspectivaacompanhamento_o151_sequencial_seq do campo: o151_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o151_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cronogramaperspectivaacompanhamento_o151_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o151_sequencial)){
         $this->erro_sql = " Campo o151_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o151_sequencial = $o151_sequencial; 
       }
     }
     if(($this->o151_sequencial == null) || ($this->o151_sequencial == "") ){ 
       $this->erro_sql = " Campo o151_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cronogramaperspectivaacompanhamento(
                                       o151_sequencial 
                                      ,o151_cronogramaperspectivaorigem 
                                      ,o151_cronogramaperspectiva 
                                      ,o151_mes 
                       )
                values (
                                $this->o151_sequencial 
                               ,$this->o151_cronogramaperspectivaorigem 
                               ,$this->o151_cronogramaperspectiva 
                               ,$this->o151_mes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acompanhamento do Cronograma ($this->o151_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acompanhamento do Cronograma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acompanhamento do Cronograma ($this->o151_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o151_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o151_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21176,'$this->o151_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3814,21176,'','".AddSlashes(pg_result($resaco,0,'o151_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3814,21177,'','".AddSlashes(pg_result($resaco,0,'o151_cronogramaperspectivaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3814,21178,'','".AddSlashes(pg_result($resaco,0,'o151_cronogramaperspectiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3814,21179,'','".AddSlashes(pg_result($resaco,0,'o151_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($o151_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cronogramaperspectivaacompanhamento set ";
     $virgula = "";
     if(trim($this->o151_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o151_sequencial"])){ 
       $sql  .= $virgula." o151_sequencial = $this->o151_sequencial ";
       $virgula = ",";
       if(trim($this->o151_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "o151_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o151_cronogramaperspectivaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o151_cronogramaperspectivaorigem"])){ 
       $sql  .= $virgula." o151_cronogramaperspectivaorigem = $this->o151_cronogramaperspectivaorigem ";
       $virgula = ",";
       if(trim($this->o151_cronogramaperspectivaorigem) == null ){ 
         $this->erro_sql = " Campo Perspesctiva Origem não informado.";
         $this->erro_campo = "o151_cronogramaperspectivaorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o151_cronogramaperspectiva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o151_cronogramaperspectiva"])){ 
       $sql  .= $virgula." o151_cronogramaperspectiva = $this->o151_cronogramaperspectiva ";
       $virgula = ",";
       if(trim($this->o151_cronogramaperspectiva) == null ){ 
         $this->erro_sql = " Campo Perspesctiva não informado.";
         $this->erro_campo = "o151_cronogramaperspectiva";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o151_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o151_mes"])){ 
       $sql  .= $virgula." o151_mes = $this->o151_mes ";
       $virgula = ",";
       if(trim($this->o151_mes) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "o151_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o151_sequencial!=null){
       $sql .= " o151_sequencial = $this->o151_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o151_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21176,'$this->o151_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o151_sequencial"]) || $this->o151_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3814,21176,'".AddSlashes(pg_result($resaco,$conresaco,'o151_sequencial'))."','$this->o151_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o151_cronogramaperspectivaorigem"]) || $this->o151_cronogramaperspectivaorigem != "")
             $resac = db_query("insert into db_acount values($acount,3814,21177,'".AddSlashes(pg_result($resaco,$conresaco,'o151_cronogramaperspectivaorigem'))."','$this->o151_cronogramaperspectivaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o151_cronogramaperspectiva"]) || $this->o151_cronogramaperspectiva != "")
             $resac = db_query("insert into db_acount values($acount,3814,21178,'".AddSlashes(pg_result($resaco,$conresaco,'o151_cronogramaperspectiva'))."','$this->o151_cronogramaperspectiva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o151_mes"]) || $this->o151_mes != "")
             $resac = db_query("insert into db_acount values($acount,3814,21179,'".AddSlashes(pg_result($resaco,$conresaco,'o151_mes'))."','$this->o151_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acompanhamento do Cronograma não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o151_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Acompanhamento do Cronograma não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o151_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o151_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($o151_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($o151_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21176,'$o151_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3814,21176,'','".AddSlashes(pg_result($resaco,$iresaco,'o151_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3814,21177,'','".AddSlashes(pg_result($resaco,$iresaco,'o151_cronogramaperspectivaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3814,21178,'','".AddSlashes(pg_result($resaco,$iresaco,'o151_cronogramaperspectiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3814,21179,'','".AddSlashes(pg_result($resaco,$iresaco,'o151_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cronogramaperspectivaacompanhamento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($o151_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " o151_sequencial = $o151_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acompanhamento do Cronograma não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o151_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Acompanhamento do Cronograma não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o151_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o151_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cronogramaperspectivaacompanhamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($o151_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from cronogramaperspectivaacompanhamento ";
     $sql .= "      inner join cronogramaperspectiva  on  cronogramaperspectiva.o124_sequencial = cronogramaperspectivaacompanhamento.o151_cronogramaperspectivaorigem";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cronogramaperspectiva.o124_idusuario";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = cronogramaperspectiva.o124_ppaversao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o151_sequencial)) {
         $sql2 .= " where cronogramaperspectivaacompanhamento.o151_sequencial = $o151_sequencial "; 
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
   public function sql_query_file ($o151_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cronogramaperspectivaacompanhamento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o151_sequencial)){
         $sql2 .= " where cronogramaperspectivaacompanhamento.o151_sequencial = $o151_sequencial "; 
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

  public function sql_query_acompanhamento ($o151_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from cronogramaperspectivaacompanhamento ";
    $sql .= "      inner join cronogramaperspectiva  on  cronogramaperspectiva.o124_sequencial = cronogramaperspectivaacompanhamento.o151_cronogramaperspectiva";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cronogramaperspectiva.o124_idusuario";
    $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = cronogramaperspectiva.o124_ppaversao";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($o151_sequencial)) {
        $sql2 .= " where cronogramaperspectivaacompanhamento.o151_sequencial = $o151_sequencial ";
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

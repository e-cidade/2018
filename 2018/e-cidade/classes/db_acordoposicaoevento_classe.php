<?
//MODULO: acordos
//CLASSE DA ENTIDADE acordoposicaoevento
class cl_acordoposicaoevento { 
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
   var $ac56_sequencial = 0; 
   var $ac56_acordoevento = 0; 
   var $ac56_acordoposicao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac56_sequencial = int4 = Sequencial 
                 ac56_acordoevento = int4 = Evento 
                 ac56_acordoposicao = int4 = Posiçao do Acordo 
                 ";
   //funcao construtor da classe 
   function cl_acordoposicaoevento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoposicaoevento"); 
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
       $this->ac56_sequencial = ($this->ac56_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac56_sequencial"]:$this->ac56_sequencial);
       $this->ac56_acordoevento = ($this->ac56_acordoevento == ""?@$GLOBALS["HTTP_POST_VARS"]["ac56_acordoevento"]:$this->ac56_acordoevento);
       $this->ac56_acordoposicao = ($this->ac56_acordoposicao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac56_acordoposicao"]:$this->ac56_acordoposicao);
     }else{
       $this->ac56_sequencial = ($this->ac56_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac56_sequencial"]:$this->ac56_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ac56_sequencial){ 
      $this->atualizacampos();
     if($this->ac56_acordoevento == null ){ 
       $this->erro_sql = " Campo Evento não informado.";
       $this->erro_campo = "ac56_acordoevento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac56_acordoposicao == null ){ 
       $this->erro_sql = " Campo Posiçao do Acordo não informado.";
       $this->erro_campo = "ac56_acordoposicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac56_sequencial == "" || $ac56_sequencial == null ){
       $result = db_query("select nextval('acordoposicaoevento_ac56_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoposicaoevento_ac56_sequencial_seq do campo: ac56_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac56_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoposicaoevento_ac56_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac56_sequencial)){
         $this->erro_sql = " Campo ac56_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac56_sequencial = $ac56_sequencial; 
       }
     }
     if(($this->ac56_sequencial == null) || ($this->ac56_sequencial == "") ){ 
       $this->erro_sql = " Campo ac56_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoposicaoevento(
                                       ac56_sequencial 
                                      ,ac56_acordoevento 
                                      ,ac56_acordoposicao 
                       )
                values (
                                $this->ac56_sequencial 
                               ,$this->ac56_acordoevento 
                               ,$this->ac56_acordoposicao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Posição do acordo/evento ($this->ac56_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Posição do acordo/evento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Posição do acordo/evento ($this->ac56_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac56_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac56_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21829,'$this->ac56_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3928,21829,'','".AddSlashes(pg_result($resaco,0,'ac56_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3928,21830,'','".AddSlashes(pg_result($resaco,0,'ac56_acordoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3928,21831,'','".AddSlashes(pg_result($resaco,0,'ac56_acordoposicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ac56_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoposicaoevento set ";
     $virgula = "";
     if(trim($this->ac56_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac56_sequencial"])){ 
       $sql  .= $virgula." ac56_sequencial = $this->ac56_sequencial ";
       $virgula = ",";
       if(trim($this->ac56_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "ac56_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac56_acordoevento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac56_acordoevento"])){ 
       $sql  .= $virgula." ac56_acordoevento = $this->ac56_acordoevento ";
       $virgula = ",";
       if(trim($this->ac56_acordoevento) == null ){ 
         $this->erro_sql = " Campo Evento não informado.";
         $this->erro_campo = "ac56_acordoevento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac56_acordoposicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac56_acordoposicao"])){ 
       $sql  .= $virgula." ac56_acordoposicao = $this->ac56_acordoposicao ";
       $virgula = ",";
       if(trim($this->ac56_acordoposicao) == null ){ 
         $this->erro_sql = " Campo Posiçao do Acordo não informado.";
         $this->erro_campo = "ac56_acordoposicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac56_sequencial!=null){
       $sql .= " ac56_sequencial = $this->ac56_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac56_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21829,'$this->ac56_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac56_sequencial"]) || $this->ac56_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3928,21829,'".AddSlashes(pg_result($resaco,$conresaco,'ac56_sequencial'))."','$this->ac56_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac56_acordoevento"]) || $this->ac56_acordoevento != "")
             $resac = db_query("insert into db_acount values($acount,3928,21830,'".AddSlashes(pg_result($resaco,$conresaco,'ac56_acordoevento'))."','$this->ac56_acordoevento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac56_acordoposicao"]) || $this->ac56_acordoposicao != "")
             $resac = db_query("insert into db_acount values($acount,3928,21831,'".AddSlashes(pg_result($resaco,$conresaco,'ac56_acordoposicao'))."','$this->ac56_acordoposicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Posição do acordo/evento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac56_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Posição do acordo/evento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ac56_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ac56_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21829,'$ac56_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3928,21829,'','".AddSlashes(pg_result($resaco,$iresaco,'ac56_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3928,21830,'','".AddSlashes(pg_result($resaco,$iresaco,'ac56_acordoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3928,21831,'','".AddSlashes(pg_result($resaco,$iresaco,'ac56_acordoposicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acordoposicaoevento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ac56_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ac56_sequencial = $ac56_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Posição do acordo/evento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac56_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Posição do acordo/evento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac56_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoposicaoevento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ac56_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from acordoposicaoevento ";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoposicaoevento.ac56_acordoposicao";
     $sql .= "      inner join acordoevento  on  acordoevento.ac55_sequencial = acordoposicaoevento.ac56_acordoevento";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoposicao.ac26_acordo";
     $sql .= "      inner join acordoposicaotipo  on  acordoposicaotipo.ac27_sequencial = acordoposicao.ac26_acordoposicaotipo";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoevento.ac55_acordo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac56_sequencial)) {
         $sql2 .= " where acordoposicaoevento.ac56_sequencial = $ac56_sequencial "; 
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
   public function sql_query_file ($ac56_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from acordoposicaoevento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac56_sequencial)){
         $sql2 .= " where acordoposicaoevento.ac56_sequencial = $ac56_sequencial "; 
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

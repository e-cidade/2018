<?
//MODULO: esocial
//CLASSE DA ENTIDADE avaliacaoresposta_cgm
class cl_avaliacaoresposta_cgm { 
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
   var $eso02_sequencial = 0; 
   var $eso02_avaliacaoresposta = 0; 
   var $eso02_cgm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 eso02_sequencial = int4 = Código 
                 eso02_avaliacaoresposta = int4 = Resposta 
                 eso02_cgm = int4 = CGM 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaoresposta_cgm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaoresposta_cgm"); 
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
       $this->eso02_sequencial = ($this->eso02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso02_sequencial"]:$this->eso02_sequencial);
       $this->eso02_avaliacaoresposta = ($this->eso02_avaliacaoresposta == ""?@$GLOBALS["HTTP_POST_VARS"]["eso02_avaliacaoresposta"]:$this->eso02_avaliacaoresposta);
       $this->eso02_cgm = ($this->eso02_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["eso02_cgm"]:$this->eso02_cgm);
     }else{
       $this->eso02_sequencial = ($this->eso02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso02_sequencial"]:$this->eso02_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($eso02_sequencial){ 
      $this->atualizacampos();
     if($this->eso02_avaliacaoresposta == null ){ 
       $this->erro_sql = " Campo Resposta não informado.";
       $this->erro_campo = "eso02_avaliacaoresposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso02_cgm == null ){ 
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "eso02_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($eso02_sequencial == "" || $eso02_sequencial == null ){
       $result = db_query("select nextval('avaliacaoresposta_cgm_eso02_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaoresposta_cgm_eso02_sequencial_seq do campo: eso02_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->eso02_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaoresposta_cgm_eso02_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $eso02_sequencial)){
         $this->erro_sql = " Campo eso02_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->eso02_sequencial = $eso02_sequencial; 
       }
     }
     if(($this->eso02_sequencial == null) || ($this->eso02_sequencial == "") ){ 
       $this->erro_sql = " Campo eso02_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaoresposta_cgm(
                                       eso02_sequencial 
                                      ,eso02_avaliacaoresposta 
                                      ,eso02_cgm 
                       )
                values (
                                $this->eso02_sequencial 
                               ,$this->eso02_avaliacaoresposta 
                               ,$this->eso02_cgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vincula uma resposta de avaliação a um servidor ($this->eso02_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vincula uma resposta de avaliação a um servidor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vincula uma resposta de avaliação a um servidor ($this->eso02_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->eso02_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso02_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21792,'$this->eso02_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3924,21792,'','".AddSlashes(pg_result($resaco,0,'eso02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3924,21793,'','".AddSlashes(pg_result($resaco,0,'eso02_avaliacaoresposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3924,21794,'','".AddSlashes(pg_result($resaco,0,'eso02_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($eso02_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaoresposta_cgm set ";
     $virgula = "";
     if(trim($this->eso02_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso02_sequencial"])){ 
       $sql  .= $virgula." eso02_sequencial = $this->eso02_sequencial ";
       $virgula = ",";
       if(trim($this->eso02_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "eso02_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso02_avaliacaoresposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso02_avaliacaoresposta"])){ 
       $sql  .= $virgula." eso02_avaliacaoresposta = $this->eso02_avaliacaoresposta ";
       $virgula = ",";
       if(trim($this->eso02_avaliacaoresposta) == null ){ 
         $this->erro_sql = " Campo Resposta não informado.";
         $this->erro_campo = "eso02_avaliacaoresposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso02_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso02_cgm"])){ 
       $sql  .= $virgula." eso02_cgm = $this->eso02_cgm ";
       $virgula = ",";
       if(trim($this->eso02_cgm) == null ){ 
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "eso02_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($eso02_sequencial!=null){
       $sql .= " eso02_sequencial = $this->eso02_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso02_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21792,'$this->eso02_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso02_sequencial"]) || $this->eso02_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3924,21792,'".AddSlashes(pg_result($resaco,$conresaco,'eso02_sequencial'))."','$this->eso02_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso02_avaliacaoresposta"]) || $this->eso02_avaliacaoresposta != "")
             $resac = db_query("insert into db_acount values($acount,3924,21793,'".AddSlashes(pg_result($resaco,$conresaco,'eso02_avaliacaoresposta'))."','$this->eso02_avaliacaoresposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso02_cgm"]) || $this->eso02_cgm != "")
             $resac = db_query("insert into db_acount values($acount,3924,21794,'".AddSlashes(pg_result($resaco,$conresaco,'eso02_cgm'))."','$this->eso02_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vincula uma resposta de avaliação a um servidor não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vincula uma resposta de avaliação a um servidor não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->eso02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($eso02_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($eso02_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21792,'$eso02_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3924,21792,'','".AddSlashes(pg_result($resaco,$iresaco,'eso02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3924,21793,'','".AddSlashes(pg_result($resaco,$iresaco,'eso02_avaliacaoresposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3924,21794,'','".AddSlashes(pg_result($resaco,$iresaco,'eso02_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaoresposta_cgm
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($eso02_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " eso02_sequencial = $eso02_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vincula uma resposta de avaliação a um servidor não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$eso02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vincula uma resposta de avaliação a um servidor não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$eso02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$eso02_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaoresposta_cgm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($eso02_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaoresposta_cgm ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = avaliacaoresposta_cgm.eso02_cgm";
     $sql .= "      inner join avaliacaoresposta  on  avaliacaoresposta.db106_sequencial = avaliacaoresposta_cgm.eso02_avaliacaoresposta";
     $sql .= "      inner join avaliacaoperguntaopcao  on  avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso02_sequencial)) {
         $sql2 .= " where avaliacaoresposta_cgm.eso02_sequencial = $eso02_sequencial "; 
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
   public function sql_query_file ($eso02_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaoresposta_cgm ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso02_sequencial)){
         $sql2 .= " where avaliacaoresposta_cgm.eso02_sequencial = $eso02_sequencial "; 
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

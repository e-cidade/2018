<?
//MODULO: matriculaonline
//CLASSE DA ENTIDADE criteriosdesignacaoensino
class cl_criteriosdesignacaoensino { 
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
   var $mo17_sequencial = 0; 
   var $mo17_criteriosdesignacao = 0; 
   var $mo17_ensino = 0; 
   var $mo17_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 mo17_sequencial = int4 = Código 
                 mo17_criteriosdesignacao = int4 = Critérios de Designação 
                 mo17_ensino = int4 = Ensino 
                 mo17_ordem = int4 = Ordem 
                 ";
   //funcao construtor da classe 
   function cl_criteriosdesignacaoensino() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("criteriosdesignacaoensino"); 
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
       $this->mo17_sequencial = ($this->mo17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["mo17_sequencial"]:$this->mo17_sequencial);
       $this->mo17_criteriosdesignacao = ($this->mo17_criteriosdesignacao == ""?@$GLOBALS["HTTP_POST_VARS"]["mo17_criteriosdesignacao"]:$this->mo17_criteriosdesignacao);
       $this->mo17_ensino = ($this->mo17_ensino == ""?@$GLOBALS["HTTP_POST_VARS"]["mo17_ensino"]:$this->mo17_ensino);
       $this->mo17_ordem = ($this->mo17_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["mo17_ordem"]:$this->mo17_ordem);
     }else{
       $this->mo17_sequencial = ($this->mo17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["mo17_sequencial"]:$this->mo17_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($mo17_sequencial){ 
      $this->atualizacampos();
     if($this->mo17_criteriosdesignacao == null ){ 
       $this->erro_sql = " Campo Critérios de Designação não informado.";
       $this->erro_campo = "mo17_criteriosdesignacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mo17_ensino == null ){ 
       $this->erro_sql = " Campo Ensino não informado.";
       $this->erro_campo = "mo17_ensino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mo17_ordem == null ){ 
       $this->erro_sql = " Campo Ordem não informado.";
       $this->erro_campo = "mo17_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($mo17_sequencial == "" || $mo17_sequencial == null ){
       $result = db_query("select nextval('criteriosdesignacaoensino_mo17_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: criteriosdesignacaoensino_mo17_sequencial_seq do campo: mo17_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->mo17_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from criteriosdesignacaoensino_mo17_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $mo17_sequencial)){
         $this->erro_sql = " Campo mo17_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->mo17_sequencial = $mo17_sequencial; 
       }
     }
     if(($this->mo17_sequencial == null) || ($this->mo17_sequencial == "") ){ 
       $this->erro_sql = " Campo mo17_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into criteriosdesignacaoensino(
                                       mo17_sequencial 
                                      ,mo17_criteriosdesignacao 
                                      ,mo17_ensino 
                                      ,mo17_ordem 
                       )
                values (
                                $this->mo17_sequencial 
                               ,$this->mo17_criteriosdesignacao 
                               ,$this->mo17_ensino 
                               ,$this->mo17_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Criterios de Designacao para Ensino ($this->mo17_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Criterios de Designacao para Ensino já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Criterios de Designacao para Ensino ($this->mo17_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->mo17_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->mo17_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21504,'$this->mo17_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3861,21504,'','".AddSlashes(pg_result($resaco,0,'mo17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3861,21506,'','".AddSlashes(pg_result($resaco,0,'mo17_criteriosdesignacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3861,21505,'','".AddSlashes(pg_result($resaco,0,'mo17_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3861,21507,'','".AddSlashes(pg_result($resaco,0,'mo17_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($mo17_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update criteriosdesignacaoensino set ";
     $virgula = "";
     if(trim($this->mo17_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo17_sequencial"])){ 
       $sql  .= $virgula." mo17_sequencial = $this->mo17_sequencial ";
       $virgula = ",";
       if(trim($this->mo17_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "mo17_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo17_criteriosdesignacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo17_criteriosdesignacao"])){ 
       $sql  .= $virgula." mo17_criteriosdesignacao = $this->mo17_criteriosdesignacao ";
       $virgula = ",";
       if(trim($this->mo17_criteriosdesignacao) == null ){ 
         $this->erro_sql = " Campo Critérios de Designação não informado.";
         $this->erro_campo = "mo17_criteriosdesignacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo17_ensino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo17_ensino"])){ 
       $sql  .= $virgula." mo17_ensino = $this->mo17_ensino ";
       $virgula = ",";
       if(trim($this->mo17_ensino) == null ){ 
         $this->erro_sql = " Campo Ensino não informado.";
         $this->erro_campo = "mo17_ensino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo17_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo17_ordem"])){ 
       $sql  .= $virgula." mo17_ordem = $this->mo17_ordem ";
       $virgula = ",";
       if(trim($this->mo17_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem não informado.";
         $this->erro_campo = "mo17_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($mo17_sequencial!=null){
       $sql .= " mo17_sequencial = $this->mo17_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->mo17_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21504,'$this->mo17_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo17_sequencial"]) || $this->mo17_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3861,21504,'".AddSlashes(pg_result($resaco,$conresaco,'mo17_sequencial'))."','$this->mo17_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo17_criteriosdesignacao"]) || $this->mo17_criteriosdesignacao != "")
             $resac = db_query("insert into db_acount values($acount,3861,21506,'".AddSlashes(pg_result($resaco,$conresaco,'mo17_criteriosdesignacao'))."','$this->mo17_criteriosdesignacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo17_ensino"]) || $this->mo17_ensino != "")
             $resac = db_query("insert into db_acount values($acount,3861,21505,'".AddSlashes(pg_result($resaco,$conresaco,'mo17_ensino'))."','$this->mo17_ensino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo17_ordem"]) || $this->mo17_ordem != "")
             $resac = db_query("insert into db_acount values($acount,3861,21507,'".AddSlashes(pg_result($resaco,$conresaco,'mo17_ordem'))."','$this->mo17_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Criterios de Designacao para Ensino não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->mo17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Criterios de Designacao para Ensino não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->mo17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->mo17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($mo17_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($mo17_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21504,'$mo17_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3861,21504,'','".AddSlashes(pg_result($resaco,$iresaco,'mo17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3861,21506,'','".AddSlashes(pg_result($resaco,$iresaco,'mo17_criteriosdesignacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3861,21505,'','".AddSlashes(pg_result($resaco,$iresaco,'mo17_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3861,21507,'','".AddSlashes(pg_result($resaco,$iresaco,'mo17_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from criteriosdesignacaoensino
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($mo17_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " mo17_sequencial = $mo17_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Criterios de Designacao para Ensino não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$mo17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Criterios de Designacao para Ensino não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$mo17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$mo17_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:criteriosdesignacaoensino";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($mo17_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from criteriosdesignacaoensino ";
     $sql .= "      inner join criteriosdesignacao  on  criteriosdesignacao.mo16_sequencial = criteriosdesignacaoensino.mo17_criteriosdesignacao";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = criteriosdesignacaoensino.mo17_ensino";
     $sql .= "      inner join mediacaodidaticopedagogica  on  mediacaodidaticopedagogica.ed130_codigo = ensino.ed10_mediacaodidaticopedagogica";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($mo17_sequencial)) {
         $sql2 .= " where criteriosdesignacaoensino.mo17_sequencial = $mo17_sequencial "; 
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
   public function sql_query_file ($mo17_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from criteriosdesignacaoensino ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($mo17_sequencial)){
         $sql2 .= " where criteriosdesignacaoensino.mo17_sequencial = $mo17_sequencial "; 
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

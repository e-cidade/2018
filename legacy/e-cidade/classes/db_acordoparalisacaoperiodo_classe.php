<?
//MODULO: acordos
//CLASSE DA ENTIDADE acordoparalisacaoperiodo
class cl_acordoparalisacaoperiodo { 
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
   var $ac49_sequencial = 0; 
   var $ac49_acordoparalisacao = 0; 
   var $ac49_acordoposicaoperiodo = 0; 
   var $ac49_tipoperiodo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac49_sequencial = int4 = Sequencial Periodo da Paralisacao 
                 ac49_acordoparalisacao = int4 = Sequencial da Paralisação 
                 ac49_acordoposicaoperiodo = int4 = Codigo Sequencial 
                 ac49_tipoperiodo = int4 = Tipo de Periodo 
                 ";
   //funcao construtor da classe 
   function cl_acordoparalisacaoperiodo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoparalisacaoperiodo"); 
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
       $this->ac49_sequencial = ($this->ac49_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac49_sequencial"]:$this->ac49_sequencial);
       $this->ac49_acordoparalisacao = ($this->ac49_acordoparalisacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac49_acordoparalisacao"]:$this->ac49_acordoparalisacao);
       $this->ac49_acordoposicaoperiodo = ($this->ac49_acordoposicaoperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac49_acordoposicaoperiodo"]:$this->ac49_acordoposicaoperiodo);
       $this->ac49_tipoperiodo = ($this->ac49_tipoperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac49_tipoperiodo"]:$this->ac49_tipoperiodo);
     }else{
       $this->ac49_sequencial = ($this->ac49_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac49_sequencial"]:$this->ac49_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac49_sequencial){ 
      $this->atualizacampos();
     if($this->ac49_acordoparalisacao == null ){ 
       $this->erro_sql = " Campo Sequencial da Paralisação não informado.";
       $this->erro_campo = "ac49_acordoparalisacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac49_acordoposicaoperiodo == null ){ 
       $this->erro_sql = " Campo Codigo Sequencial não informado.";
       $this->erro_campo = "ac49_acordoposicaoperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac49_tipoperiodo == null ){ 
       $this->erro_sql = " Campo Tipo de Periodo não informado.";
       $this->erro_campo = "ac49_tipoperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac49_sequencial == "" || $ac49_sequencial == null ){
       $result = db_query("select nextval('acordoparalisacaoperiodo_ac49_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoparalisacaoperiodo_ac49_sequencial_seq do campo: ac49_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac49_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoparalisacaoperiodo_ac49_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac49_sequencial)){
         $this->erro_sql = " Campo ac49_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac49_sequencial = $ac49_sequencial; 
       }
     }
     if(($this->ac49_sequencial == null) || ($this->ac49_sequencial == "") ){ 
       $this->erro_sql = " Campo ac49_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoparalisacaoperiodo(
                                       ac49_sequencial 
                                      ,ac49_acordoparalisacao 
                                      ,ac49_acordoposicaoperiodo 
                                      ,ac49_tipoperiodo 
                       )
                values (
                                $this->ac49_sequencial 
                               ,$this->ac49_acordoparalisacao 
                               ,$this->ac49_acordoposicaoperiodo 
                               ,$this->ac49_tipoperiodo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Periodo da Paralisação ($this->ac49_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Periodo da Paralisação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Periodo da Paralisação ($this->ac49_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac49_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac49_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20525,'$this->ac49_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3694,20525,'','".AddSlashes(pg_result($resaco,0,'ac49_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3694,20526,'','".AddSlashes(pg_result($resaco,0,'ac49_acordoparalisacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3694,20527,'','".AddSlashes(pg_result($resaco,0,'ac49_acordoposicaoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3694,20528,'','".AddSlashes(pg_result($resaco,0,'ac49_tipoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac49_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoparalisacaoperiodo set ";
     $virgula = "";
     if(trim($this->ac49_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac49_sequencial"])){ 
       $sql  .= $virgula." ac49_sequencial = $this->ac49_sequencial ";
       $virgula = ",";
       if(trim($this->ac49_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial Periodo da Paralisacao não informado.";
         $this->erro_campo = "ac49_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac49_acordoparalisacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac49_acordoparalisacao"])){ 
       $sql  .= $virgula." ac49_acordoparalisacao = $this->ac49_acordoparalisacao ";
       $virgula = ",";
       if(trim($this->ac49_acordoparalisacao) == null ){ 
         $this->erro_sql = " Campo Sequencial da Paralisação não informado.";
         $this->erro_campo = "ac49_acordoparalisacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac49_acordoposicaoperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac49_acordoposicaoperiodo"])){ 
       $sql  .= $virgula." ac49_acordoposicaoperiodo = $this->ac49_acordoposicaoperiodo ";
       $virgula = ",";
       if(trim($this->ac49_acordoposicaoperiodo) == null ){ 
         $this->erro_sql = " Campo Codigo Sequencial não informado.";
         $this->erro_campo = "ac49_acordoposicaoperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac49_tipoperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac49_tipoperiodo"])){ 
       $sql  .= $virgula." ac49_tipoperiodo = $this->ac49_tipoperiodo ";
       $virgula = ",";
       if(trim($this->ac49_tipoperiodo) == null ){ 
         $this->erro_sql = " Campo Tipo de Periodo não informado.";
         $this->erro_campo = "ac49_tipoperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac49_sequencial!=null){
       $sql .= " ac49_sequencial = $this->ac49_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac49_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20525,'$this->ac49_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac49_sequencial"]) || $this->ac49_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3694,20525,'".AddSlashes(pg_result($resaco,$conresaco,'ac49_sequencial'))."','$this->ac49_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac49_acordoparalisacao"]) || $this->ac49_acordoparalisacao != "")
             $resac = db_query("insert into db_acount values($acount,3694,20526,'".AddSlashes(pg_result($resaco,$conresaco,'ac49_acordoparalisacao'))."','$this->ac49_acordoparalisacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac49_acordoposicaoperiodo"]) || $this->ac49_acordoposicaoperiodo != "")
             $resac = db_query("insert into db_acount values($acount,3694,20527,'".AddSlashes(pg_result($resaco,$conresaco,'ac49_acordoposicaoperiodo'))."','$this->ac49_acordoposicaoperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac49_tipoperiodo"]) || $this->ac49_tipoperiodo != "")
             $resac = db_query("insert into db_acount values($acount,3694,20528,'".AddSlashes(pg_result($resaco,$conresaco,'ac49_tipoperiodo'))."','$this->ac49_tipoperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Periodo da Paralisação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac49_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Periodo da Paralisação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac49_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac49_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac49_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ac49_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20525,'$ac49_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3694,20525,'','".AddSlashes(pg_result($resaco,$iresaco,'ac49_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3694,20526,'','".AddSlashes(pg_result($resaco,$iresaco,'ac49_acordoparalisacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3694,20527,'','".AddSlashes(pg_result($resaco,$iresaco,'ac49_acordoposicaoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3694,20528,'','".AddSlashes(pg_result($resaco,$iresaco,'ac49_tipoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acordoparalisacaoperiodo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac49_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac49_sequencial = $ac49_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Periodo da Paralisação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac49_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Periodo da Paralisação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac49_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac49_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:acordoparalisacaoperiodo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac49_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acordoparalisacaoperiodo ";
     $sql .= "      inner join acordoposicaoperiodo  on  acordoposicaoperiodo.ac36_sequencial = acordoparalisacaoperiodo.ac49_acordoposicaoperiodo";
     $sql .= "      inner join acordoparalisacao  on  acordoparalisacao.ac47_sequencial = acordoparalisacaoperiodo.ac49_acordoparalisacao";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoposicaoperiodo.ac36_acordoposicao";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoparalisacao.ac47_acordo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac49_sequencial!=null ){
         $sql2 .= " where acordoparalisacaoperiodo.ac49_sequencial = $ac49_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $ac49_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acordoparalisacaoperiodo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac49_sequencial!=null ){
         $sql2 .= " where acordoparalisacaoperiodo.ac49_sequencial = $ac49_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>

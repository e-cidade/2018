<?
//MODULO: diversos
//CLASSE DA ENTIDADE diversoslancamentotaxa
class cl_diversoslancamentotaxa { 
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
   var $dv14_sequencial = 0; 
   var $dv14_diversos = 0; 
   var $dv14_lancamentotaxadiversos = 0; 
   var $dv14_data_calculo_dia = null; 
   var $dv14_data_calculo_mes = null; 
   var $dv14_data_calculo_ano = null; 
   var $dv14_data_calculo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 dv14_sequencial = int4 = Código 
                 dv14_diversos = int4 = Código do Diverso 
                 dv14_lancamentotaxadiversos = int4 = Código do Lançamento 
                 dv14_data_calculo = date = Data do Cálculo 
                 ";
   //funcao construtor da classe 
   function cl_diversoslancamentotaxa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diversoslancamentotaxa"); 
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
       $this->dv14_sequencial = ($this->dv14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["dv14_sequencial"]:$this->dv14_sequencial);
       $this->dv14_diversos = ($this->dv14_diversos == ""?@$GLOBALS["HTTP_POST_VARS"]["dv14_diversos"]:$this->dv14_diversos);
       $this->dv14_lancamentotaxadiversos = ($this->dv14_lancamentotaxadiversos == ""?@$GLOBALS["HTTP_POST_VARS"]["dv14_lancamentotaxadiversos"]:$this->dv14_lancamentotaxadiversos);
       if($this->dv14_data_calculo == ""){
         $this->dv14_data_calculo_dia = ($this->dv14_data_calculo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dv14_data_calculo_dia"]:$this->dv14_data_calculo_dia);
         $this->dv14_data_calculo_mes = ($this->dv14_data_calculo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dv14_data_calculo_mes"]:$this->dv14_data_calculo_mes);
         $this->dv14_data_calculo_ano = ($this->dv14_data_calculo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dv14_data_calculo_ano"]:$this->dv14_data_calculo_ano);
         if($this->dv14_data_calculo_dia != ""){
            $this->dv14_data_calculo = $this->dv14_data_calculo_ano."-".$this->dv14_data_calculo_mes."-".$this->dv14_data_calculo_dia;
         }
       }
     }else{
       $this->dv14_sequencial = ($this->dv14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["dv14_sequencial"]:$this->dv14_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($dv14_sequencial){ 
      $this->atualizacampos();
     if($this->dv14_diversos == null ){ 
       $this->erro_sql = " Campo Código do Diverso não informado.";
       $this->erro_campo = "dv14_diversos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv14_lancamentotaxadiversos == null ){ 
       $this->erro_sql = " Campo Código do Lançamento não informado.";
       $this->erro_campo = "dv14_lancamentotaxadiversos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv14_data_calculo == null ){ 
       $this->erro_sql = " Campo Data do Cálculo não informado.";
       $this->erro_campo = "dv14_data_calculo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($dv14_sequencial == "" || $dv14_sequencial == null ){
       $result = db_query("select nextval('diversoslancamentotaxa_dv14_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diversoslancamentotaxa_dv14_sequencial_seq do campo: dv14_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->dv14_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from diversoslancamentotaxa_dv14_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $dv14_sequencial)){
         $this->erro_sql = " Campo dv14_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->dv14_sequencial = $dv14_sequencial; 
       }
     }
     if(($this->dv14_sequencial == null) || ($this->dv14_sequencial == "") ){ 
       $this->erro_sql = " Campo dv14_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diversoslancamentotaxa(
                                       dv14_sequencial 
                                      ,dv14_diversos 
                                      ,dv14_lancamentotaxadiversos 
                                      ,dv14_data_calculo 
                       )
                values (
                                $this->dv14_sequencial 
                               ,$this->dv14_diversos 
                               ,$this->dv14_lancamentotaxadiversos 
                               ,".($this->dv14_data_calculo == "null" || $this->dv14_data_calculo == ""?"null":"'".$this->dv14_data_calculo."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Diversos Lançados ($this->dv14_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Diversos Lançados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Diversos Lançados ($this->dv14_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv14_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->dv14_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22087,'$this->dv14_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3978,22087,'','".AddSlashes(pg_result($resaco,0,'dv14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3978,22088,'','".AddSlashes(pg_result($resaco,0,'dv14_diversos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3978,22089,'','".AddSlashes(pg_result($resaco,0,'dv14_lancamentotaxadiversos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3978,22094,'','".AddSlashes(pg_result($resaco,0,'dv14_data_calculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($dv14_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update diversoslancamentotaxa set ";
     $virgula = "";
     if(trim($this->dv14_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv14_sequencial"])){ 
       $sql  .= $virgula." dv14_sequencial = $this->dv14_sequencial ";
       $virgula = ",";
       if(trim($this->dv14_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "dv14_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv14_diversos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv14_diversos"])){ 
       $sql  .= $virgula." dv14_diversos = $this->dv14_diversos ";
       $virgula = ",";
       if(trim($this->dv14_diversos) == null ){ 
         $this->erro_sql = " Campo Código do Diverso não informado.";
         $this->erro_campo = "dv14_diversos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv14_lancamentotaxadiversos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv14_lancamentotaxadiversos"])){ 
       $sql  .= $virgula." dv14_lancamentotaxadiversos = $this->dv14_lancamentotaxadiversos ";
       $virgula = ",";
       if(trim($this->dv14_lancamentotaxadiversos) == null ){ 
         $this->erro_sql = " Campo Código do Lançamento não informado.";
         $this->erro_campo = "dv14_lancamentotaxadiversos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv14_data_calculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv14_data_calculo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dv14_data_calculo_dia"] !="") ){ 
       $sql  .= $virgula." dv14_data_calculo = '$this->dv14_data_calculo' ";
       $virgula = ",";
       if(trim($this->dv14_data_calculo) == null ){ 
         $this->erro_sql = " Campo Data do Cálculo não informado.";
         $this->erro_campo = "dv14_data_calculo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dv14_data_calculo_dia"])){ 
         $sql  .= $virgula." dv14_data_calculo = null ";
         $virgula = ",";
         if(trim($this->dv14_data_calculo) == null ){ 
           $this->erro_sql = " Campo Data do Cálculo não informado.";
           $this->erro_campo = "dv14_data_calculo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($dv14_sequencial!=null){
       $sql .= " dv14_sequencial = $this->dv14_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->dv14_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22087,'$this->dv14_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["dv14_sequencial"]) || $this->dv14_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3978,22087,'".AddSlashes(pg_result($resaco,$conresaco,'dv14_sequencial'))."','$this->dv14_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["dv14_diversos"]) || $this->dv14_diversos != "")
             $resac = db_query("insert into db_acount values($acount,3978,22088,'".AddSlashes(pg_result($resaco,$conresaco,'dv14_diversos'))."','$this->dv14_diversos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["dv14_lancamentotaxadiversos"]) || $this->dv14_lancamentotaxadiversos != "")
             $resac = db_query("insert into db_acount values($acount,3978,22089,'".AddSlashes(pg_result($resaco,$conresaco,'dv14_lancamentotaxadiversos'))."','$this->dv14_lancamentotaxadiversos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["dv14_data_calculo"]) || $this->dv14_data_calculo != "")
             $resac = db_query("insert into db_acount values($acount,3978,22094,'".AddSlashes(pg_result($resaco,$conresaco,'dv14_data_calculo'))."','$this->dv14_data_calculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diversos Lançados não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Diversos Lançados não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($dv14_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($dv14_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22087,'$dv14_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3978,22087,'','".AddSlashes(pg_result($resaco,$iresaco,'dv14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3978,22088,'','".AddSlashes(pg_result($resaco,$iresaco,'dv14_diversos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3978,22089,'','".AddSlashes(pg_result($resaco,$iresaco,'dv14_lancamentotaxadiversos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3978,22094,'','".AddSlashes(pg_result($resaco,$iresaco,'dv14_data_calculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diversoslancamentotaxa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($dv14_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " dv14_sequencial = $dv14_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diversos Lançados não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$dv14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Diversos Lançados não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$dv14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$dv14_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:diversoslancamentotaxa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($dv14_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from diversoslancamentotaxa ";
     $sql .= "      inner join diversos  on  diversos.dv05_coddiver = diversoslancamentotaxa.dv14_diversos";
     $sql .= "      inner join lancamentotaxadiversos  on  lancamentotaxadiversos.y120_sequencial = diversoslancamentotaxa.dv14_lancamentotaxadiversos";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = diversos.dv05_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = diversos.dv05_instit";
     $sql .= "      inner join procdiver  on  procdiver.dv09_procdiver = diversos.dv05_procdiver";
     $sql .= "      left  join issbase  on  issbase.q02_inscr = lancamentotaxadiversos.y120_issbase";
     $sql .= "      left  join cgm  as a on   a.z01_numcgm = lancamentotaxadiversos.y120_cgm";
     $sql .= "      inner join taxadiversos  on  taxadiversos.y119_sequencial = lancamentotaxadiversos.y120_taxadiversos";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($dv14_sequencial)) {
         $sql2 .= " where diversoslancamentotaxa.dv14_sequencial = $dv14_sequencial "; 
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
   public function sql_query_file ($dv14_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from diversoslancamentotaxa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($dv14_sequencial)){
         $sql2 .= " where diversoslancamentotaxa.dv14_sequencial = $dv14_sequencial "; 
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

  public function sql_query_observacoes_taxa($dv14_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from diversoslancamentotaxa ";
    $sql .= "       inner join diversos               on diversos.dv05_coddiver                 = diversoslancamentotaxa.dv14_diversos";
    $sql .= "       inner join lancamentotaxadiversos on lancamentotaxadiversos.y120_sequencial = diversoslancamentotaxa.dv14_lancamentotaxadiversos";
    $sql .= "       inner join cgm                    on cgm.z01_numcgm                         = diversos.dv05_numcgm";
    $sql .= "       inner join taxadiversos           on taxadiversos.y119_sequencial           = lancamentotaxadiversos.y120_taxadiversos";
    $sql .= "       inner join grupotaxadiversos      on grupotaxadiversos.y118_sequencial      = taxadiversos.y119_grupotaxadiversos";
    $sql .= "       inner join recibopaga             on recibopaga.k00_numcgm                  = diversos.dv05_numcgm";
    $sql .= "                                        and recibopaga.k00_numpre                  = diversos.dv05_numpre";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($dv14_sequencial)) {
        $sql2 .= " where diversoslancamentotaxa.dv14_sequencial = $dv14_sequencial ";
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

<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_triagemavulsaagravo
class cl_sau_triagemavulsaagravo { 
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
   var $s167_sequencial = 0; 
   var $s167_sau_triagemavulsa = 0; 
   var $s167_sau_cid = 0; 
   var $s167_datasintoma_dia = null; 
   var $s167_datasintoma_mes = null; 
   var $s167_datasintoma_ano = null; 
   var $s167_datasintoma = null; 
   var $s167_gestante = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s167_sequencial = int4 = Sequencial da tabela 
                 s167_sau_triagemavulsa = int4 = Triagem Avulsa 
                 s167_sau_cid = int4 = Agravo 
                 s167_datasintoma = date = Data do primeiro sintoma 
                 s167_gestante = bool = Gestante 
                 ";
   //funcao construtor da classe 
   function cl_sau_triagemavulsaagravo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_triagemavulsaagravo"); 
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
       $this->s167_sequencial = ($this->s167_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["s167_sequencial"]:$this->s167_sequencial);
       $this->s167_sau_triagemavulsa = ($this->s167_sau_triagemavulsa == ""?@$GLOBALS["HTTP_POST_VARS"]["s167_sau_triagemavulsa"]:$this->s167_sau_triagemavulsa);
       $this->s167_sau_cid = ($this->s167_sau_cid == ""?@$GLOBALS["HTTP_POST_VARS"]["s167_sau_cid"]:$this->s167_sau_cid);
       if($this->s167_datasintoma == ""){
         $this->s167_datasintoma_dia = ($this->s167_datasintoma_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s167_datasintoma_dia"]:$this->s167_datasintoma_dia);
         $this->s167_datasintoma_mes = ($this->s167_datasintoma_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s167_datasintoma_mes"]:$this->s167_datasintoma_mes);
         $this->s167_datasintoma_ano = ($this->s167_datasintoma_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s167_datasintoma_ano"]:$this->s167_datasintoma_ano);
         if($this->s167_datasintoma_dia != ""){
            $this->s167_datasintoma = $this->s167_datasintoma_ano."-".$this->s167_datasintoma_mes."-".$this->s167_datasintoma_dia;
         }
       }
       $this->s167_gestante = ($this->s167_gestante == "f"?@$GLOBALS["HTTP_POST_VARS"]["s167_gestante"]:$this->s167_gestante);
     }else{
       $this->s167_sequencial = ($this->s167_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["s167_sequencial"]:$this->s167_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($s167_sequencial){ 
      $this->atualizacampos();
     if($this->s167_sau_triagemavulsa == null ){ 
       $this->erro_sql = " Campo Triagem Avulsa não informado.";
       $this->erro_campo = "s167_sau_triagemavulsa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s167_sau_cid == null ){ 
       $this->erro_sql = " Campo Agravo não informado.";
       $this->erro_campo = "s167_sau_cid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s167_datasintoma == null ){ 
       $this->erro_sql = " Campo Data do primeiro sintoma não informado.";
       $this->erro_campo = "s167_datasintoma_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s167_gestante == null ){ 
       $this->erro_sql = " Campo Gestante não informado.";
       $this->erro_campo = "s167_gestante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s167_sequencial == "" || $s167_sequencial == null ){
       $result = db_query("select nextval('sau_triagemavulsaagravo_s167_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_triagemavulsaagravo_s167_sequencial_seq do campo: s167_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s167_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_triagemavulsaagravo_s167_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $s167_sequencial)){
         $this->erro_sql = " Campo s167_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s167_sequencial = $s167_sequencial; 
       }
     }
     if(($this->s167_sequencial == null) || ($this->s167_sequencial == "") ){ 
       $this->erro_sql = " Campo s167_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_triagemavulsaagravo(
                                       s167_sequencial 
                                      ,s167_sau_triagemavulsa 
                                      ,s167_sau_cid 
                                      ,s167_datasintoma 
                                      ,s167_gestante 
                       )
                values (
                                $this->s167_sequencial 
                               ,$this->s167_sau_triagemavulsa 
                               ,$this->s167_sau_cid 
                               ,".($this->s167_datasintoma == "null" || $this->s167_datasintoma == ""?"null":"'".$this->s167_datasintoma."'")." 
                               ,'$this->s167_gestante' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Triagem Avulsa com Agravo ($this->s167_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Triagem Avulsa com Agravo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Triagem Avulsa com Agravo ($this->s167_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s167_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s167_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20678,'$this->s167_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3722,20678,'','".AddSlashes(pg_result($resaco,0,'s167_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3722,20679,'','".AddSlashes(pg_result($resaco,0,'s167_sau_triagemavulsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3722,20680,'','".AddSlashes(pg_result($resaco,0,'s167_sau_cid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3722,20681,'','".AddSlashes(pg_result($resaco,0,'s167_datasintoma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3722,20682,'','".AddSlashes(pg_result($resaco,0,'s167_gestante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s167_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update sau_triagemavulsaagravo set ";
     $virgula = "";
     if(trim($this->s167_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s167_sequencial"])){ 
       $sql  .= $virgula." s167_sequencial = $this->s167_sequencial ";
       $virgula = ",";
       if(trim($this->s167_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela não informado.";
         $this->erro_campo = "s167_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s167_sau_triagemavulsa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s167_sau_triagemavulsa"])){ 
       $sql  .= $virgula." s167_sau_triagemavulsa = $this->s167_sau_triagemavulsa ";
       $virgula = ",";
       if(trim($this->s167_sau_triagemavulsa) == null ){ 
         $this->erro_sql = " Campo Triagem Avulsa não informado.";
         $this->erro_campo = "s167_sau_triagemavulsa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s167_sau_cid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s167_sau_cid"])){ 
       $sql  .= $virgula." s167_sau_cid = $this->s167_sau_cid ";
       $virgula = ",";
       if(trim($this->s167_sau_cid) == null ){ 
         $this->erro_sql = " Campo Agravo não informado.";
         $this->erro_campo = "s167_sau_cid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s167_datasintoma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s167_datasintoma_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s167_datasintoma_dia"] !="") ){ 
       $sql  .= $virgula." s167_datasintoma = '$this->s167_datasintoma' ";
       $virgula = ",";
       if(trim($this->s167_datasintoma) == null ){ 
         $this->erro_sql = " Campo Data do primeiro sintoma não informado.";
         $this->erro_campo = "s167_datasintoma_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s167_datasintoma_dia"])){ 
         $sql  .= $virgula." s167_datasintoma = null ";
         $virgula = ",";
         if(trim($this->s167_datasintoma) == null ){ 
           $this->erro_sql = " Campo Data do primeiro sintoma não informado.";
           $this->erro_campo = "s167_datasintoma_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s167_gestante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s167_gestante"])){ 
       $sql  .= $virgula." s167_gestante = '$this->s167_gestante' ";
       $virgula = ",";
       if(trim($this->s167_gestante) == null ){ 
         $this->erro_sql = " Campo Gestante não informado.";
         $this->erro_campo = "s167_gestante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s167_sequencial!=null){
       $sql .= " s167_sequencial = $this->s167_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s167_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20678,'$this->s167_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s167_sequencial"]) || $this->s167_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3722,20678,'".AddSlashes(pg_result($resaco,$conresaco,'s167_sequencial'))."','$this->s167_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s167_sau_triagemavulsa"]) || $this->s167_sau_triagemavulsa != "")
             $resac = db_query("insert into db_acount values($acount,3722,20679,'".AddSlashes(pg_result($resaco,$conresaco,'s167_sau_triagemavulsa'))."','$this->s167_sau_triagemavulsa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s167_sau_cid"]) || $this->s167_sau_cid != "")
             $resac = db_query("insert into db_acount values($acount,3722,20680,'".AddSlashes(pg_result($resaco,$conresaco,'s167_sau_cid'))."','$this->s167_sau_cid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s167_datasintoma"]) || $this->s167_datasintoma != "")
             $resac = db_query("insert into db_acount values($acount,3722,20681,'".AddSlashes(pg_result($resaco,$conresaco,'s167_datasintoma'))."','$this->s167_datasintoma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s167_gestante"]) || $this->s167_gestante != "")
             $resac = db_query("insert into db_acount values($acount,3722,20682,'".AddSlashes(pg_result($resaco,$conresaco,'s167_gestante'))."','$this->s167_gestante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Triagem Avulsa com Agravo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s167_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Triagem Avulsa com Agravo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s167_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s167_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s167_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($s167_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20678,'$s167_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3722,20678,'','".AddSlashes(pg_result($resaco,$iresaco,'s167_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3722,20679,'','".AddSlashes(pg_result($resaco,$iresaco,'s167_sau_triagemavulsa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3722,20680,'','".AddSlashes(pg_result($resaco,$iresaco,'s167_sau_cid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3722,20681,'','".AddSlashes(pg_result($resaco,$iresaco,'s167_datasintoma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3722,20682,'','".AddSlashes(pg_result($resaco,$iresaco,'s167_gestante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sau_triagemavulsaagravo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s167_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s167_sequencial = $s167_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Triagem Avulsa com Agravo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s167_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Triagem Avulsa com Agravo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s167_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s167_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_triagemavulsaagravo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s167_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_triagemavulsaagravo ";
     $sql .= "      inner join sau_cid  on  sau_cid.sd70_i_codigo = sau_triagemavulsaagravo.s167_sau_cid";
     $sql .= "      inner join sau_triagemavulsa  on  sau_triagemavulsa.s152_i_codigo = sau_triagemavulsaagravo.s167_sau_triagemavulsa";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_triagemavulsa.s152_i_login";
     $sql .= "      inner join far_cbosprofissional  on  far_cbosprofissional.fa54_i_codigo = sau_triagemavulsa.s152_i_cbosprofissional";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = sau_triagemavulsa.s152_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($s167_sequencial!=null ){
         $sql2 .= " where sau_triagemavulsaagravo.s167_sequencial = $s167_sequencial "; 
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
   function sql_query_file ( $s167_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_triagemavulsaagravo ";
     $sql2 = "";
     if($dbwhere==""){
       if($s167_sequencial!=null ){
         $sql2 .= " where sau_triagemavulsaagravo.s167_sequencial = $s167_sequencial "; 
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

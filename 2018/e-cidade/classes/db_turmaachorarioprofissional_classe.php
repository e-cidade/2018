<?
//MODULO: escola
//CLASSE DA ENTIDADE turmaachorarioprofissional
class cl_turmaachorarioprofissional { 
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
   var $ed346_sequencial = 0; 
   var $ed346_turmaac = 0; 
   var $ed346_funcaoatividade = 0; 
   var $ed346_rechumano = 0; 
   var $ed346_diasemana = 0; 
   var $ed346_horainicial = null; 
   var $ed346_horafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed346_sequencial = int4 = Código 
                 ed346_turmaac = int4 = Turma AC / AEE 
                 ed346_funcaoatividade = int4 = Função 
                 ed346_rechumano = int4 = Profissional 
                 ed346_diasemana = int4 = Dia da Semana 
                 ed346_horainicial = char(5) = Hora Inicial 
                 ed346_horafinal = char(5) = Hora Final 
                 ";
   //funcao construtor da classe 
   function cl_turmaachorarioprofissional() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmaachorarioprofissional"); 
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
       $this->ed346_sequencial = ($this->ed346_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed346_sequencial"]:$this->ed346_sequencial);
       $this->ed346_turmaac = ($this->ed346_turmaac == ""?@$GLOBALS["HTTP_POST_VARS"]["ed346_turmaac"]:$this->ed346_turmaac);
       $this->ed346_funcaoatividade = ($this->ed346_funcaoatividade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed346_funcaoatividade"]:$this->ed346_funcaoatividade);
       $this->ed346_rechumano = ($this->ed346_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed346_rechumano"]:$this->ed346_rechumano);
       $this->ed346_diasemana = ($this->ed346_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["ed346_diasemana"]:$this->ed346_diasemana);
       $this->ed346_horainicial = ($this->ed346_horainicial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed346_horainicial"]:$this->ed346_horainicial);
       $this->ed346_horafinal = ($this->ed346_horafinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed346_horafinal"]:$this->ed346_horafinal);
     }else{
       $this->ed346_sequencial = ($this->ed346_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed346_sequencial"]:$this->ed346_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed346_sequencial){ 
      $this->atualizacampos();
     if($this->ed346_turmaac == null ){ 
       $this->erro_sql = " Campo Turma AC / AEE não informado.";
       $this->erro_campo = "ed346_turmaac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed346_funcaoatividade == null ){ 
       $this->erro_sql = " Campo Função não informado.";
       $this->erro_campo = "ed346_funcaoatividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed346_rechumano == null ){ 
       $this->erro_sql = " Campo Profissional não informado.";
       $this->erro_campo = "ed346_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed346_diasemana == null ){ 
       $this->erro_sql = " Campo Dia da Semana não informado.";
       $this->erro_campo = "ed346_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed346_horainicial == null ){ 
       $this->erro_sql = " Campo Hora Inicial não informado.";
       $this->erro_campo = "ed346_horainicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed346_horafinal == null ){ 
       $this->erro_sql = " Campo Hora Final não informado.";
       $this->erro_campo = "ed346_horafinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed346_sequencial == "" || $ed346_sequencial == null ){
       $result = db_query("select nextval('turmaachorarioprofissional_ed346_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmaachorarioprofissional_ed346_sequencial_seq do campo: ed346_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed346_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from turmaachorarioprofissional_ed346_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed346_sequencial)){
         $this->erro_sql = " Campo ed346_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed346_sequencial = $ed346_sequencial; 
       }
     }
     if(($this->ed346_sequencial == null) || ($this->ed346_sequencial == "") ){ 
       $this->erro_sql = " Campo ed346_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmaachorarioprofissional(
                                       ed346_sequencial 
                                      ,ed346_turmaac 
                                      ,ed346_funcaoatividade 
                                      ,ed346_rechumano 
                                      ,ed346_diasemana 
                                      ,ed346_horainicial 
                                      ,ed346_horafinal 
                       )
                values (
                                $this->ed346_sequencial 
                               ,$this->ed346_turmaac 
                               ,$this->ed346_funcaoatividade 
                               ,$this->ed346_rechumano 
                               ,$this->ed346_diasemana 
                               ,'$this->ed346_horainicial' 
                               ,'$this->ed346_horafinal' 
                      )";

     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "turmaachorarioprofissional ($this->ed346_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "turmaachorarioprofissional já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "turmaachorarioprofissional ($this->ed346_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed346_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed346_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20603,'$this->ed346_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3709,20603,'','".AddSlashes(pg_result($resaco,0,'ed346_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3709,20604,'','".AddSlashes(pg_result($resaco,0,'ed346_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3709,20605,'','".AddSlashes(pg_result($resaco,0,'ed346_funcaoatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3709,20606,'','".AddSlashes(pg_result($resaco,0,'ed346_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3709,20607,'','".AddSlashes(pg_result($resaco,0,'ed346_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3709,20608,'','".AddSlashes(pg_result($resaco,0,'ed346_horainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3709,20609,'','".AddSlashes(pg_result($resaco,0,'ed346_horafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed346_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update turmaachorarioprofissional set ";
     $virgula = "";
     if(trim($this->ed346_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed346_sequencial"])){ 
       $sql  .= $virgula." ed346_sequencial = $this->ed346_sequencial ";
       $virgula = ",";
       if(trim($this->ed346_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed346_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed346_turmaac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed346_turmaac"])){ 
       $sql  .= $virgula." ed346_turmaac = $this->ed346_turmaac ";
       $virgula = ",";
       if(trim($this->ed346_turmaac) == null ){ 
         $this->erro_sql = " Campo Turma AC / AEE não informado.";
         $this->erro_campo = "ed346_turmaac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed346_funcaoatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed346_funcaoatividade"])){ 
       $sql  .= $virgula." ed346_funcaoatividade = $this->ed346_funcaoatividade ";
       $virgula = ",";
       if(trim($this->ed346_funcaoatividade) == null ){ 
         $this->erro_sql = " Campo Função não informado.";
         $this->erro_campo = "ed346_funcaoatividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed346_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed346_rechumano"])){ 
       $sql  .= $virgula." ed346_rechumano = $this->ed346_rechumano ";
       $virgula = ",";
       if(trim($this->ed346_rechumano) == null ){ 
         $this->erro_sql = " Campo Profissional não informado.";
         $this->erro_campo = "ed346_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed346_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed346_diasemana"])){ 
       $sql  .= $virgula." ed346_diasemana = $this->ed346_diasemana ";
       $virgula = ",";
       if(trim($this->ed346_diasemana) == null ){ 
         $this->erro_sql = " Campo Dia da Semana não informado.";
         $this->erro_campo = "ed346_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed346_horainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed346_horainicial"])){ 
       $sql  .= $virgula." ed346_horainicial = '$this->ed346_horainicial' ";
       $virgula = ",";
       if(trim($this->ed346_horainicial) == null ){ 
         $this->erro_sql = " Campo Hora Inicial não informado.";
         $this->erro_campo = "ed346_horainicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed346_horafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed346_horafinal"])){ 
       $sql  .= $virgula." ed346_horafinal = '$this->ed346_horafinal' ";
       $virgula = ",";
       if(trim($this->ed346_horafinal) == null ){ 
         $this->erro_sql = " Campo Hora Final não informado.";
         $this->erro_campo = "ed346_horafinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed346_sequencial!=null){
       $sql .= " ed346_sequencial = $this->ed346_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed346_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20603,'$this->ed346_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed346_sequencial"]) || $this->ed346_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3709,20603,'".AddSlashes(pg_result($resaco,$conresaco,'ed346_sequencial'))."','$this->ed346_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed346_turmaac"]) || $this->ed346_turmaac != "")
             $resac = db_query("insert into db_acount values($acount,3709,20604,'".AddSlashes(pg_result($resaco,$conresaco,'ed346_turmaac'))."','$this->ed346_turmaac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed346_funcaoatividade"]) || $this->ed346_funcaoatividade != "")
             $resac = db_query("insert into db_acount values($acount,3709,20605,'".AddSlashes(pg_result($resaco,$conresaco,'ed346_funcaoatividade'))."','$this->ed346_funcaoatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed346_rechumano"]) || $this->ed346_rechumano != "")
             $resac = db_query("insert into db_acount values($acount,3709,20606,'".AddSlashes(pg_result($resaco,$conresaco,'ed346_rechumano'))."','$this->ed346_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed346_diasemana"]) || $this->ed346_diasemana != "")
             $resac = db_query("insert into db_acount values($acount,3709,20607,'".AddSlashes(pg_result($resaco,$conresaco,'ed346_diasemana'))."','$this->ed346_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed346_horainicial"]) || $this->ed346_horainicial != "")
             $resac = db_query("insert into db_acount values($acount,3709,20608,'".AddSlashes(pg_result($resaco,$conresaco,'ed346_horainicial'))."','$this->ed346_horainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed346_horafinal"]) || $this->ed346_horafinal != "")
             $resac = db_query("insert into db_acount values($acount,3709,20609,'".AddSlashes(pg_result($resaco,$conresaco,'ed346_horafinal'))."','$this->ed346_horafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmaachorarioprofissional nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed346_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turmaachorarioprofissional nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed346_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed346_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed346_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed346_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20603,'$ed346_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3709,20603,'','".AddSlashes(pg_result($resaco,$iresaco,'ed346_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3709,20604,'','".AddSlashes(pg_result($resaco,$iresaco,'ed346_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3709,20605,'','".AddSlashes(pg_result($resaco,$iresaco,'ed346_funcaoatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3709,20606,'','".AddSlashes(pg_result($resaco,$iresaco,'ed346_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3709,20607,'','".AddSlashes(pg_result($resaco,$iresaco,'ed346_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3709,20608,'','".AddSlashes(pg_result($resaco,$iresaco,'ed346_horainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3709,20609,'','".AddSlashes(pg_result($resaco,$iresaco,'ed346_horafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from turmaachorarioprofissional
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed346_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed346_sequencial = $ed346_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmaachorarioprofissional nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed346_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turmaachorarioprofissional nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed346_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed346_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmaachorarioprofissional";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed346_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaachorarioprofissional ";
     $sql .= "      inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorarioprofissional.ed346_turmaac";
     $sql .= "      inner join funcaoatividade  on  funcaoatividade.ed119_sequencial = turmaachorarioprofissional.ed346_funcaoatividade";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = turmaachorarioprofissional.ed346_rechumano";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = turmaachorarioprofissional.ed346_diasemana";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turmaac.ed268_i_turno";
     $sql .= "      left  join sala  on  sala.ed16_i_codigo = turmaac.ed268_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
     $sql .= "      left  join rhregime  on  rhregime.rh30_codreg = rechumano.ed20_i_rhregime";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = rechumano.ed20_i_censoufcert and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufender and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufnat and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = rechumano.ed20_i_censomunicnat ";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio";
     $sql .= "      left  join rechumano  as a on   a.ed20_i_codigo = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed346_sequencial!=null ){
         $sql2 .= " where turmaachorarioprofissional.ed346_sequencial = $ed346_sequencial "; 
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
   function sql_query_file ( $ed346_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaachorarioprofissional ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed346_sequencial!=null ){
         $sql2 .= " where turmaachorarioprofissional.ed346_sequencial = $ed346_sequencial "; 
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

  function sql_query_vinculo_profissional ( $ed346_sequencial = null, $sCampos = "*",$sOrdem = null,$sWhere = "") {

    $sSql = "select ";
    if ($sCampos != "*" ) {

      $campos_sql = split("#",$sCampos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql   .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from turmaachorarioprofissional ";
    $sSql .= "      inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorarioprofissional.ed346_turmaac";
    $sSql .= "      inner join funcaoatividade  on  funcaoatividade.ed119_sequencial = turmaachorarioprofissional.ed346_funcaoatividade";
    $sSql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = turmaachorarioprofissional.ed346_diasemana";
    $sSql .= "      inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
    $sSql .= "      inner join turno  on  turno.ed15_i_codigo = turmaac.ed268_i_turno";
    $sSql .= "      left  join sala  on  sala.ed16_i_codigo = turmaac.ed268_i_sala";
    $sSql .= "      inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
    $sSql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = turmaachorarioprofissional.ed346_rechumano";
    $sSql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
    $sSql .= "       left join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo            ";
    $sSql .= "       left join rhpessoal        on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal ";
    $sSql .= "       left join cgm as cgmrh     on cgmrh.z01_numcgm                   = rhpessoal.rh01_numcgm              ";
    $sSql .= "       left join rechumanocgm     on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo            ";
    $sSql .= "       left join cgm as cgmcgm    on cgmcgm.z01_numcgm                  = rechumanocgm.ed285_i_cgm           ";
    $sSql2 = "";

    if ($sWhere == "") {
      if ($ed346_sequencial!=null) {
        $sSql2 .= " where turmaachorarioprofissional.ed346_sequencial = $ed346_sequencial ";
      }
    } else if($sWhere != "") {
      $sSql2 = " where $sWhere";
    }
    $sSql .= $sSql2;
    if ($sOrdem != null) {

      $sSql      .= " order by ";
      $campos_sql = split("#",$sOrdem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sSql   .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sSql;
  }
}
?>

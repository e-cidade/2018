<?
//MODULO: escola
//CLASSE DA ENTIDADE agendaatividade
class cl_agendaatividade { 
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
   var $ed129_codigo = 0; 
   var $ed129_tipohoratrabalho = 0; 
   var $ed129_diasemana = 0; 
   var $ed129_turno = 0; 
   var $ed129_rechumanoativ = 0; 
   var $ed129_horainicio = null; 
   var $ed129_horafim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed129_codigo = int4 = Código 
                 ed129_tipohoratrabalho = int4 = Tipo de Hora de Trabalho 
                 ed129_diasemana = int4 = Dia da Semana 
                 ed129_turno = int4 = Turno 
                 ed129_rechumanoativ = int4 = Atividade 
                 ed129_horainicio = varchar(5) = Hora de Inicio 
                 ed129_horafim = varchar(5) = Hora Final 
                 ";
   //funcao construtor da classe 
   function cl_agendaatividade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agendaatividade"); 
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
       $this->ed129_codigo = ($this->ed129_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_codigo"]:$this->ed129_codigo);
       $this->ed129_tipohoratrabalho = ($this->ed129_tipohoratrabalho == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_tipohoratrabalho"]:$this->ed129_tipohoratrabalho);
       $this->ed129_diasemana = ($this->ed129_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_diasemana"]:$this->ed129_diasemana);
       $this->ed129_turno = ($this->ed129_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_turno"]:$this->ed129_turno);
       $this->ed129_rechumanoativ = ($this->ed129_rechumanoativ == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_rechumanoativ"]:$this->ed129_rechumanoativ);
       $this->ed129_horainicio = ($this->ed129_horainicio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_horainicio"]:$this->ed129_horainicio);
       $this->ed129_horafim = ($this->ed129_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_horafim"]:$this->ed129_horafim);
     }else{
       $this->ed129_codigo = ($this->ed129_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed129_codigo"]:$this->ed129_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed129_codigo){ 
      $this->atualizacampos();
     if($this->ed129_tipohoratrabalho == null ){ 
       $this->erro_sql = " Campo Tipo de Hora de Trabalho não informado.";
       $this->erro_campo = "ed129_tipohoratrabalho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_diasemana == null ){ 
       $this->erro_sql = " Campo Dia da Semana não informado.";
       $this->erro_campo = "ed129_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_turno == null ){ 
       $this->erro_sql = " Campo Turno não informado.";
       $this->erro_campo = "ed129_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_rechumanoativ == null ){ 
       $this->erro_sql = " Campo Atividade não informado.";
       $this->erro_campo = "ed129_rechumanoativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_horainicio == null ){ 
       $this->erro_sql = " Campo Hora de Inicio não informado.";
       $this->erro_campo = "ed129_horainicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed129_horafim == null ){ 
       $this->erro_sql = " Campo Hora Final não informado.";
       $this->erro_campo = "ed129_horafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed129_codigo == "" || $ed129_codigo == null ){
       $result = db_query("select nextval('agendaatividade_ed129_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agendaatividade_ed129_codigo_seq do campo: ed129_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed129_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from agendaatividade_ed129_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed129_codigo)){
         $this->erro_sql = " Campo ed129_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed129_codigo = $ed129_codigo; 
       }
     }
     if(($this->ed129_codigo == null) || ($this->ed129_codigo == "") ){ 
       $this->erro_sql = " Campo ed129_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agendaatividade(
                                       ed129_codigo 
                                      ,ed129_tipohoratrabalho 
                                      ,ed129_diasemana 
                                      ,ed129_turno 
                                      ,ed129_rechumanoativ 
                                      ,ed129_horainicio 
                                      ,ed129_horafim 
                       )
                values (
                                $this->ed129_codigo 
                               ,$this->ed129_tipohoratrabalho 
                               ,$this->ed129_diasemana 
                               ,$this->ed129_turno 
                               ,$this->ed129_rechumanoativ 
                               ,'$this->ed129_horainicio' 
                               ,'$this->ed129_horafim' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agenda de atividade ($this->ed129_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agenda de atividade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agenda de atividade ($this->ed129_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed129_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed129_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21033,'$this->ed129_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3790,21033,'','".AddSlashes(pg_result($resaco,0,'ed129_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3790,21034,'','".AddSlashes(pg_result($resaco,0,'ed129_tipohoratrabalho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3790,21035,'','".AddSlashes(pg_result($resaco,0,'ed129_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3790,21036,'','".AddSlashes(pg_result($resaco,0,'ed129_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3790,21037,'','".AddSlashes(pg_result($resaco,0,'ed129_rechumanoativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3790,21038,'','".AddSlashes(pg_result($resaco,0,'ed129_horainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3790,21039,'','".AddSlashes(pg_result($resaco,0,'ed129_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed129_codigo=null) { 
      $this->atualizacampos();
     $sql = " update agendaatividade set ";
     $virgula = "";
     if(trim($this->ed129_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_codigo"])){ 
       $sql  .= $virgula." ed129_codigo = $this->ed129_codigo ";
       $virgula = ",";
       if(trim($this->ed129_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed129_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_tipohoratrabalho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_tipohoratrabalho"])){ 
       $sql  .= $virgula." ed129_tipohoratrabalho = $this->ed129_tipohoratrabalho ";
       $virgula = ",";
       if(trim($this->ed129_tipohoratrabalho) == null ){ 
         $this->erro_sql = " Campo Tipo de Hora de Trabalho não informado.";
         $this->erro_campo = "ed129_tipohoratrabalho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_diasemana"])){ 
       $sql  .= $virgula." ed129_diasemana = $this->ed129_diasemana ";
       $virgula = ",";
       if(trim($this->ed129_diasemana) == null ){ 
         $this->erro_sql = " Campo Dia da Semana não informado.";
         $this->erro_campo = "ed129_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_turno"])){ 
       $sql  .= $virgula." ed129_turno = $this->ed129_turno ";
       $virgula = ",";
       if(trim($this->ed129_turno) == null ){ 
         $this->erro_sql = " Campo Turno não informado.";
         $this->erro_campo = "ed129_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_rechumanoativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_rechumanoativ"])){ 
       $sql  .= $virgula." ed129_rechumanoativ = $this->ed129_rechumanoativ ";
       $virgula = ",";
       if(trim($this->ed129_rechumanoativ) == null ){ 
         $this->erro_sql = " Campo Atividade não informado.";
         $this->erro_campo = "ed129_rechumanoativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_horainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_horainicio"])){ 
       $sql  .= $virgula." ed129_horainicio = '$this->ed129_horainicio' ";
       $virgula = ",";
       if(trim($this->ed129_horainicio) == null ){ 
         $this->erro_sql = " Campo Hora de Inicio não informado.";
         $this->erro_campo = "ed129_horainicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed129_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed129_horafim"])){ 
       $sql  .= $virgula." ed129_horafim = '$this->ed129_horafim' ";
       $virgula = ",";
       if(trim($this->ed129_horafim) == null ){ 
         $this->erro_sql = " Campo Hora Final não informado.";
         $this->erro_campo = "ed129_horafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed129_codigo!=null){
       $sql .= " ed129_codigo = $this->ed129_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed129_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21033,'$this->ed129_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed129_codigo"]) || $this->ed129_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3790,21033,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_codigo'))."','$this->ed129_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed129_tipohoratrabalho"]) || $this->ed129_tipohoratrabalho != "")
             $resac = db_query("insert into db_acount values($acount,3790,21034,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_tipohoratrabalho'))."','$this->ed129_tipohoratrabalho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed129_diasemana"]) || $this->ed129_diasemana != "")
             $resac = db_query("insert into db_acount values($acount,3790,21035,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_diasemana'))."','$this->ed129_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed129_turno"]) || $this->ed129_turno != "")
             $resac = db_query("insert into db_acount values($acount,3790,21036,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_turno'))."','$this->ed129_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed129_rechumanoativ"]) || $this->ed129_rechumanoativ != "")
             $resac = db_query("insert into db_acount values($acount,3790,21037,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_rechumanoativ'))."','$this->ed129_rechumanoativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed129_horainicio"]) || $this->ed129_horainicio != "")
             $resac = db_query("insert into db_acount values($acount,3790,21038,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_horainicio'))."','$this->ed129_horainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed129_horafim"]) || $this->ed129_horafim != "")
             $resac = db_query("insert into db_acount values($acount,3790,21039,'".AddSlashes(pg_result($resaco,$conresaco,'ed129_horafim'))."','$this->ed129_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda de atividade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed129_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Agenda de atividade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed129_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed129_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed129_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed129_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21033,'$ed129_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3790,21033,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3790,21034,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_tipohoratrabalho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3790,21035,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3790,21036,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3790,21037,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_rechumanoativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3790,21038,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_horainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3790,21039,'','".AddSlashes(pg_result($resaco,$iresaco,'ed129_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from agendaatividade
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed129_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed129_codigo = $ed129_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda de atividade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed129_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Agenda de atividade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed129_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed129_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:agendaatividade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed129_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from agendaatividade ";
     $sql .= "      inner join tipohoratrabalho  on  tipohoratrabalho.ed128_codigo = agendaatividade.ed129_tipohoratrabalho";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = agendaatividade.ed129_diasemana";
     $sql .= "      inner join rechumanoativ  on  rechumanoativ.ed22_i_codigo = agendaatividade.ed129_rechumanoativ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = tipohoratrabalho.ed128_escola";
     $sql .= "      left  join atolegal  on  atolegal.ed05_i_codigo = rechumanoativ.ed22_i_atolegal";
     $sql .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_codigo = rechumanoativ.ed22_i_rechumanoescola";
     $sql .= "      inner join atividaderh  on  atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed129_codigo)) {
         $sql2 .= " where agendaatividade.ed129_codigo = $ed129_codigo "; 
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
   public function sql_query_file ($ed129_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from agendaatividade ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed129_codigo)){
         $sql2 .= " where agendaatividade.ed129_codigo = $ed129_codigo "; 
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

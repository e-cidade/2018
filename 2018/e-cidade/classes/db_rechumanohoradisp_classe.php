<?php
//MODULO: escola
//CLASSE DA ENTIDADE rechumanohoradisp
class cl_rechumanohoradisp { 
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
   var $ed33_i_codigo = 0; 
   var $ed33_rechumanoescola = 0; 
   var $ed33_i_diasemana = 0; 
   var $ed33_i_periodo = 0; 
   var $ed33_ativo = 'f'; 
   var $ed33_tipohoratrabalho = 0; 
   var $ed33_horaatividade = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed33_i_codigo = int8 = Código 
                 ed33_rechumanoescola = int4 = Vinculo escola 
                 ed33_i_diasemana = int8 = Dia da Semana 
                 ed33_i_periodo = int8 = Período 
                 ed33_ativo = bool = Ativo 
                 ed33_tipohoratrabalho = int4 = Tipo de hora de trabalho 
                 ed33_horaatividade = bool = Hora de Atividade 
                 ";
   //funcao construtor da classe 
   function cl_rechumanohoradisp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rechumanohoradisp"); 
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
       $this->ed33_i_codigo = ($this->ed33_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed33_i_codigo"]:$this->ed33_i_codigo);
       $this->ed33_rechumanoescola = ($this->ed33_rechumanoescola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed33_rechumanoescola"]:$this->ed33_rechumanoescola);
       $this->ed33_i_diasemana = ($this->ed33_i_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["ed33_i_diasemana"]:$this->ed33_i_diasemana);
       $this->ed33_i_periodo = ($this->ed33_i_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed33_i_periodo"]:$this->ed33_i_periodo);
       $this->ed33_ativo = ($this->ed33_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed33_ativo"]:$this->ed33_ativo);
       $this->ed33_tipohoratrabalho = ($this->ed33_tipohoratrabalho == ""?@$GLOBALS["HTTP_POST_VARS"]["ed33_tipohoratrabalho"]:$this->ed33_tipohoratrabalho);
       $this->ed33_horaatividade = ($this->ed33_horaatividade == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed33_horaatividade"]:$this->ed33_horaatividade);
     }else{
       $this->ed33_i_codigo = ($this->ed33_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed33_i_codigo"]:$this->ed33_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed33_i_codigo){ 
      $this->atualizacampos();
     if($this->ed33_rechumanoescola == null ){ 
       $this->erro_sql = " Campo Vinculo escola não informado.";
       $this->erro_campo = "ed33_rechumanoescola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed33_i_diasemana == null ){ 
       $this->erro_sql = " Campo Dia da Semana não informado.";
       $this->erro_campo = "ed33_i_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed33_i_periodo == null ){ 
       $this->erro_sql = " Campo Período não informado.";
       $this->erro_campo = "ed33_i_periodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed33_ativo == null ){ 
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "ed33_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed33_tipohoratrabalho == null ){ 
       $this->erro_sql = " Campo Tipo de hora de trabalho não informado.";
       $this->erro_campo = "ed33_tipohoratrabalho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed33_horaatividade == null ){ 
       $this->erro_sql = " Campo Hora de Atividade não informado.";
       $this->erro_campo = "ed33_horaatividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed33_i_codigo == "" || $ed33_i_codigo == null ){
       $result = db_query("select nextval('rechumanohoradisp_ed33_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rechumanohoradisp_ed33_i_codigo_seq do campo: ed33_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed33_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rechumanohoradisp_ed33_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed33_i_codigo)){
         $this->erro_sql = " Campo ed33_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed33_i_codigo = $ed33_i_codigo; 
       }
     }
     if(($this->ed33_i_codigo == null) || ($this->ed33_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed33_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rechumanohoradisp(
                                       ed33_i_codigo 
                                      ,ed33_rechumanoescola 
                                      ,ed33_i_diasemana 
                                      ,ed33_i_periodo 
                                      ,ed33_ativo 
                                      ,ed33_tipohoratrabalho 
                                      ,ed33_horaatividade 
                       )
                values (
                                $this->ed33_i_codigo 
                               ,$this->ed33_rechumanoescola 
                               ,$this->ed33_i_diasemana 
                               ,$this->ed33_i_periodo 
                               ,'$this->ed33_ativo' 
                               ,$this->ed33_tipohoratrabalho 
                               ,'$this->ed33_horaatividade' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Horário Disponível do Rec Humano ($this->ed33_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Horário Disponível do Rec Humano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Horário Disponível do Rec Humano ($this->ed33_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed33_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed33_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008528,'$this->ed33_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010091,1008528,'','".AddSlashes(pg_result($resaco,0,'ed33_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010091,20568,'','".AddSlashes(pg_result($resaco,0,'ed33_rechumanoescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010091,1008530,'','".AddSlashes(pg_result($resaco,0,'ed33_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010091,1008531,'','".AddSlashes(pg_result($resaco,0,'ed33_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010091,20567,'','".AddSlashes(pg_result($resaco,0,'ed33_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010091,21040,'','".AddSlashes(pg_result($resaco,0,'ed33_tipohoratrabalho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010091,21140,'','".AddSlashes(pg_result($resaco,0,'ed33_horaatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed33_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rechumanohoradisp set ";
     $virgula = "";
     if(trim($this->ed33_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed33_i_codigo"])){ 
       $sql  .= $virgula." ed33_i_codigo = $this->ed33_i_codigo ";
       $virgula = ",";
       if(trim($this->ed33_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed33_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed33_rechumanoescola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed33_rechumanoescola"])){ 
       $sql  .= $virgula." ed33_rechumanoescola = $this->ed33_rechumanoescola ";
       $virgula = ",";
       if(trim($this->ed33_rechumanoescola) == null ){ 
         $this->erro_sql = " Campo Vinculo escola não informado.";
         $this->erro_campo = "ed33_rechumanoescola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed33_i_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed33_i_diasemana"])){ 
       $sql  .= $virgula." ed33_i_diasemana = $this->ed33_i_diasemana ";
       $virgula = ",";
       if(trim($this->ed33_i_diasemana) == null ){ 
         $this->erro_sql = " Campo Dia da Semana não informado.";
         $this->erro_campo = "ed33_i_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed33_i_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed33_i_periodo"])){ 
       $sql  .= $virgula." ed33_i_periodo = $this->ed33_i_periodo ";
       $virgula = ",";
       if(trim($this->ed33_i_periodo) == null ){ 
         $this->erro_sql = " Campo Período não informado.";
         $this->erro_campo = "ed33_i_periodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed33_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed33_ativo"])){ 
       $sql  .= $virgula." ed33_ativo = '$this->ed33_ativo' ";
       $virgula = ",";
       if(trim($this->ed33_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "ed33_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed33_tipohoratrabalho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed33_tipohoratrabalho"])){ 
       $sql  .= $virgula." ed33_tipohoratrabalho = $this->ed33_tipohoratrabalho ";
       $virgula = ",";
       if(trim($this->ed33_tipohoratrabalho) == null ){ 
         $this->erro_sql = " Campo Tipo de hora de trabalho não informado.";
         $this->erro_campo = "ed33_tipohoratrabalho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed33_horaatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed33_horaatividade"])){ 
       $sql  .= $virgula." ed33_horaatividade = '$this->ed33_horaatividade' ";
       $virgula = ",";
       if(trim($this->ed33_horaatividade) == null ){ 
         $this->erro_sql = " Campo Hora de Atividade não informado.";
         $this->erro_campo = "ed33_horaatividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed33_i_codigo!=null){
       $sql .= " ed33_i_codigo = $this->ed33_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed33_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008528,'$this->ed33_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed33_i_codigo"]) || $this->ed33_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010091,1008528,'".AddSlashes(pg_result($resaco,$conresaco,'ed33_i_codigo'))."','$this->ed33_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed33_rechumanoescola"]) || $this->ed33_rechumanoescola != "")
             $resac = db_query("insert into db_acount values($acount,1010091,20568,'".AddSlashes(pg_result($resaco,$conresaco,'ed33_rechumanoescola'))."','$this->ed33_rechumanoescola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed33_i_diasemana"]) || $this->ed33_i_diasemana != "")
             $resac = db_query("insert into db_acount values($acount,1010091,1008530,'".AddSlashes(pg_result($resaco,$conresaco,'ed33_i_diasemana'))."','$this->ed33_i_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed33_i_periodo"]) || $this->ed33_i_periodo != "")
             $resac = db_query("insert into db_acount values($acount,1010091,1008531,'".AddSlashes(pg_result($resaco,$conresaco,'ed33_i_periodo'))."','$this->ed33_i_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed33_ativo"]) || $this->ed33_ativo != "")
             $resac = db_query("insert into db_acount values($acount,1010091,20567,'".AddSlashes(pg_result($resaco,$conresaco,'ed33_ativo'))."','$this->ed33_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed33_tipohoratrabalho"]) || $this->ed33_tipohoratrabalho != "")
             $resac = db_query("insert into db_acount values($acount,1010091,21040,'".AddSlashes(pg_result($resaco,$conresaco,'ed33_tipohoratrabalho'))."','$this->ed33_tipohoratrabalho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed33_horaatividade"]) || $this->ed33_horaatividade != "")
             $resac = db_query("insert into db_acount values($acount,1010091,21140,'".AddSlashes(pg_result($resaco,$conresaco,'ed33_horaatividade'))."','$this->ed33_horaatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Horário Disponível do Rec Humano não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed33_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Horário Disponível do Rec Humano não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed33_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed33_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed33_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed33_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008528,'$ed33_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010091,1008528,'','".AddSlashes(pg_result($resaco,$iresaco,'ed33_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010091,20568,'','".AddSlashes(pg_result($resaco,$iresaco,'ed33_rechumanoescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010091,1008530,'','".AddSlashes(pg_result($resaco,$iresaco,'ed33_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010091,1008531,'','".AddSlashes(pg_result($resaco,$iresaco,'ed33_i_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010091,20567,'','".AddSlashes(pg_result($resaco,$iresaco,'ed33_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010091,21040,'','".AddSlashes(pg_result($resaco,$iresaco,'ed33_tipohoratrabalho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010091,21140,'','".AddSlashes(pg_result($resaco,$iresaco,'ed33_horaatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rechumanohoradisp
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed33_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed33_i_codigo = $ed33_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Horário Disponível do Rec Humano não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed33_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Horário Disponível do Rec Humano não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed33_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed33_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rechumanohoradisp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed33_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rechumanohoradisp ";
     $sql .= "      inner join periodoescola  on  periodoescola.ed17_i_codigo = rechumanohoradisp.ed33_i_periodo";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = rechumanohoradisp.ed33_i_diasemana";
     $sql .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_codigo = rechumanohoradisp.ed33_rechumanoescola";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
     $sql .= "      inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = periodoescola.ed17_i_turno";
     $sql .= "      inner join escola  as a on   a.ed18_i_codigo = rechumanoescola.ed75_i_escola";
     $sql .= "      inner join rechumano  as b on   b.ed20_i_codigo = rechumanoescola.ed75_i_rechumano";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed33_i_codigo)) {
         $sql2 .= " where rechumanohoradisp.ed33_i_codigo = $ed33_i_codigo "; 
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
   public function sql_query_file ($ed33_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rechumanohoradisp ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed33_i_codigo)){
         $sql2 .= " where rechumanohoradisp.ed33_i_codigo = $ed33_i_codigo "; 
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

   function sql_query_disponivel_periodo ($ed33_i_codigo = null, $campos="*", $ordem = null, $dbwhere = "") {
  	
  	$sql = "select ";
  	if ($campos != "*" ) {
  		
  		$campos_sql = split("#",$campos);
  		$virgula    = "";
  		
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {
  			
  			$sql    .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	}else {
  		$sql .= $campos;
  	}
  	$sql .= "  from rechumanohoradisp ";
  	$sql .= " inner join periodoescola    on periodoescola.ed17_i_codigo            = rechumanohoradisp.ed33_i_periodo   ";
    $sql .= "       inner join rechumanoescola  on rechumanoescola.ed75_i_codigo          = rechumanohoradisp.ed33_rechumanoescola ";
    $sql .= "       inner join rechumano        on rechumano.ed20_i_codigo                = rechumanoescola.ed75_i_rechumano       ";
  	$sql .= " inner join relacaotrabalho  on relacaotrabalho.ed23_i_rechumanoescola = rechumanoescola.ed75_i_codigo      ";
  	$sql .= " inner join rechumanoativ    on rechumanoativ.ed22_i_rechumanoescola   = rechumanoescola.ed75_i_codigo      ";
  	$sql .= " inner join atividaderh      on atividaderh.ed01_i_codigo              = rechumanoativ.ed22_i_atividade     ";
  	$sql .= " inner join areatrabalho     on areatrabalho.ed25_i_codigo             = relacaotrabalho.ed23_i_areatrabalho";
  	
  	$sql2 = "";
  	if ($dbwhere == "") {
  		
  		if ($ed33_i_codigo != null) {
  			$sql2 .= " where rechumanohoradisp.ed33_i_codigo = $ed33_i_codigo ";
  		}
  	} else if($dbwhere != "") {
  		$sql2 = " where $dbwhere";
  	}
  	$sql .= $sql2;
  	if ($ordem != null ) {
  		
  		$sql       .= " order by ";
  		$campos_sql = split("#",$ordem);
  		$virgula    = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {
  			
  			$sql    .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	}
  	return $sql;
  }

  function sql_query_disponibilidade ( $ed33_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql = "select ";

    if( $campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from rechumanohoradisp ";
    $sql .= "      inner join periodoescola     on periodoescola.ed17_i_codigo        = rechumanohoradisp.ed33_i_periodo";
    $sql .= "      inner join diasemana         on diasemana.ed32_i_codigo            = rechumanohoradisp.ed33_i_diasemana";
    $sql .= "      inner join rechumanoescola   on rechumanoescola.ed75_i_codigo      = rechumanohoradisp.ed33_rechumanoescola";
    $sql .= "      inner join escola            on escola.ed18_i_codigo               = rechumanoescola.ed75_i_escola";
    $sql .= "      inner join periodoaula       on periodoaula.ed08_i_codigo          = periodoescola.ed17_i_periodoaula";
    $sql .= "      inner join turno             on turno.ed15_i_codigo                = periodoescola.ed17_i_turno";
    $sql .= "      inner join rechumano         on rechumano.ed20_i_codigo            = rechumanoescola.ed75_i_rechumano";
    $sql .= "      left  join rechumanopessoal  on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sql .= "      left  join rhpessoal         on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal";
    $sql .= "      left  join cgm as cgmrh      on cgmrh.z01_numcgm                   = rhpessoal.rh01_numcgm";
    $sql .= "      left  join rechumanocgm      on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo";
    $sql .= "      left  join cgm as cgmcgm     on cgmcgm.z01_numcgm                  = rechumanocgm.ed285_i_cgm";
    $sql2 = "";

    if( $dbwhere == "" ) {

      if( $ed33_i_codigo != null ) {
        $sql2 .= " where rechumanohoradisp.ed33_i_codigo = $ed33_i_codigo ";
      }
    } else if( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;

    if( $ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split( "#", $ordem );
      $virgula     = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }

    return $sql;
  }

  function sql_query_tipohoratrabalho ( $ed33_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql = "select ";

    if( $campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from rechumanohoradisp ";
    $sql .= "      inner join periodoescola     on periodoescola.ed17_i_codigo        = rechumanohoradisp.ed33_i_periodo";
    $sql .= "      inner join diasemana         on diasemana.ed32_i_codigo            = rechumanohoradisp.ed33_i_diasemana";
    $sql .= "      inner join rechumanoescola   on rechumanoescola.ed75_i_codigo      = rechumanohoradisp.ed33_rechumanoescola";
    $sql .= "      inner join escola            on escola.ed18_i_codigo               = rechumanoescola.ed75_i_escola";
    $sql .= "      inner join periodoaula       on periodoaula.ed08_i_codigo          = periodoescola.ed17_i_periodoaula";
    $sql .= "      inner join turno             on turno.ed15_i_codigo                = periodoescola.ed17_i_turno";
    $sql .= "      inner join rechumano         on rechumano.ed20_i_codigo            = rechumanoescola.ed75_i_rechumano";
    $sql .= "      inner join tipohoratrabalho  on tipohoratrabalho.ed128_codigo      = rechumanohoradisp.ed33_tipohoratrabalho";
    $sql .= "      left  join rechumanopessoal  on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sql .= "      left  join rhpessoal         on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal";
    $sql .= "      left  join cgm as cgmrh      on cgmrh.z01_numcgm                   = rhpessoal.rh01_numcgm";
    $sql .= "      left  join rechumanocgm      on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo";
    $sql .= "      left  join cgm as cgmcgm     on cgmcgm.z01_numcgm                  = rechumanocgm.ed285_i_cgm";
    $sql2 = "";

    if( $dbwhere == "" ) {

      if( $ed33_i_codigo != null ) {
        $sql2 .= " where rechumanohoradisp.ed33_i_codigo = $ed33_i_codigo ";
      }
    } else if( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;

    if( $ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split( "#", $ordem );
      $virgula     = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
  	return $sql;
  }
}

<?
//MODULO: arrecadacao
//CLASSE DA ENTIDADE abatimentocorrecao
class cl_abatimentocorrecao { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $k167_sequencial = 0; 
   var $k167_valorantigo = 0; 
   var $k167_valorcorrigido = 0; 
   var $k167_data_dia = null; 
   var $k167_data_mes = null; 
   var $k167_data_ano = null; 
   var $k167_data = null; 
   var $k167_abatimento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k167_sequencial = int4 = Sequencial 
                 k167_valorantigo = float8 = Valor Antigo 
                 k167_valorcorrigido = float8 = Valor Corrigido 
                 k167_data = date = Data da Correção 
                 k167_abatimento = int4 = Código do Abatimento 
                 ";
   //funcao construtor da classe 
   function cl_abatimentocorrecao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("abatimentocorrecao"); 
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
       $this->k167_sequencial = ($this->k167_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k167_sequencial"]:$this->k167_sequencial);
       $this->k167_valorantigo = ($this->k167_valorantigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k167_valorantigo"]:$this->k167_valorantigo);
       $this->k167_valorcorrigido = ($this->k167_valorcorrigido == ""?@$GLOBALS["HTTP_POST_VARS"]["k167_valorcorrigido"]:$this->k167_valorcorrigido);
       if($this->k167_data == ""){
         $this->k167_data_dia = @$GLOBALS["HTTP_POST_VARS"]["k167_data_dia"];
         $this->k167_data_mes = @$GLOBALS["HTTP_POST_VARS"]["k167_data_mes"];
         $this->k167_data_ano = @$GLOBALS["HTTP_POST_VARS"]["k167_data_ano"];
         if($this->k167_data_dia != ""){
            $this->k167_data = $this->k167_data_ano."-".$this->k167_data_mes."-".$this->k167_data_dia;
         }
       }
       $this->k167_abatimento = ($this->k167_abatimento == ""?@$GLOBALS["HTTP_POST_VARS"]["k167_abatimento"]:$this->k167_abatimento);
     }else{
       $this->k167_sequencial = ($this->k167_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k167_sequencial"]:$this->k167_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k167_sequencial){ 
      $this->atualizacampos();
     if($this->k167_valorantigo == null ){ 
       $this->erro_sql = " Campo Valor Antigo nao Informado.";
       $this->erro_campo = "k167_valorantigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k167_valorcorrigido == null ){ 
       $this->erro_sql = " Campo Valor Corrigido nao Informado.";
       $this->erro_campo = "k167_valorcorrigido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k167_data == null ){ 
       $this->erro_sql = " Campo Data da Correção nao Informado.";
       $this->erro_campo = "k167_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k167_abatimento == null ){ 
       $this->erro_sql = " Campo Código do Abatimento nao Informado.";
       $this->erro_campo = "k167_abatimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k167_sequencial == "" || $k167_sequencial == null ){
       $result = @db_query("select nextval('abatimentocorrecao_k167_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: abatimentocorrecao_k167_sequencial_seq do campo: k167_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k167_sequencial = pg_result($result,0,0); 
     }else{
       $result = @db_query("select last_value from abatimentocorrecao_k167_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k167_sequencial)){
         $this->erro_sql = " Campo k167_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k167_sequencial = $k167_sequencial; 
       }
     }
     if(($this->k167_sequencial == null) || ($this->k167_sequencial == "") ){ 
       $this->erro_sql = " Campo k167_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @db_query("insert into abatimentocorrecao(
                                       k167_sequencial 
                                      ,k167_valorantigo 
                                      ,k167_valorcorrigido 
                                      ,k167_data 
                                      ,k167_abatimento 
                       )
                values (
                                $this->k167_sequencial 
                               ,$this->k167_valorantigo 
                               ,$this->k167_valorcorrigido 
                               ,".($this->k167_data == "null" || $this->k167_data == ""?"null":"'".$this->k167_data."'")." 
                               ,$this->k167_abatimento 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Abatimento Correção ($this->k167_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Abatimento Correção já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Abatimento Correção ($this->k167_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k167_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->k167_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,21928,'$this->k167_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3949,21928,'','".pg_result($resaco,0,'k167_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3949,21929,'','".pg_result($resaco,0,'k167_valorantigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3949,21930,'','".pg_result($resaco,0,'k167_valorcorrigido')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3949,21931,'','".pg_result($resaco,0,'k167_data')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3949,21932,'','".pg_result($resaco,0,'k167_abatimento')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k167_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update abatimentocorrecao set ";
     $virgula = "";
     if(trim($this->k167_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k167_sequencial"])){ 
        if(trim($this->k167_sequencial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k167_sequencial"])){ 
           $this->k167_sequencial = "0" ; 
        } 
       $sql  .= $virgula." k167_sequencial = $this->k167_sequencial ";
       $virgula = ",";
       if(trim($this->k167_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k167_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k167_valorantigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k167_valorantigo"])){ 
        if(trim($this->k167_valorantigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k167_valorantigo"])){ 
           $this->k167_valorantigo = "0" ; 
        } 
       $sql  .= $virgula." k167_valorantigo = $this->k167_valorantigo ";
       $virgula = ",";
       if(trim($this->k167_valorantigo) == null ){ 
         $this->erro_sql = " Campo Valor Antigo nao Informado.";
         $this->erro_campo = "k167_valorantigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k167_valorcorrigido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k167_valorcorrigido"])){ 
        if(trim($this->k167_valorcorrigido)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k167_valorcorrigido"])){ 
           $this->k167_valorcorrigido = "0" ; 
        } 
       $sql  .= $virgula." k167_valorcorrigido = $this->k167_valorcorrigido ";
       $virgula = ",";
       if(trim($this->k167_valorcorrigido) == null ){ 
         $this->erro_sql = " Campo Valor Corrigido nao Informado.";
         $this->erro_campo = "k167_valorcorrigido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k167_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k167_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k167_data_dia"] !="") ){ 
       $sql  .= $virgula." k167_data = '$this->k167_data' ";
       $virgula = ",";
       if(trim($this->k167_data) == null ){ 
         $this->erro_sql = " Campo Data da Correção nao Informado.";
         $this->erro_campo = "k167_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k167_data_dia"])){ 
         $sql  .= $virgula." k167_data = null ";
         $virgula = ",";
         if(trim($this->k167_data) == null ){ 
           $this->erro_sql = " Campo Data da Correção nao Informado.";
           $this->erro_campo = "k167_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k167_abatimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k167_abatimento"])){ 
        if(trim($this->k167_abatimento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k167_abatimento"])){ 
           $this->k167_abatimento = "0" ; 
        } 
       $sql  .= $virgula." k167_abatimento = $this->k167_abatimento ";
       $virgula = ",";
       if(trim($this->k167_abatimento) == null ){ 
         $this->erro_sql = " Campo Código do Abatimento nao Informado.";
         $this->erro_campo = "k167_abatimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  k167_sequencial = $this->k167_sequencial
";
     $resaco = $this->sql_record($this->sql_query_file($this->k167_sequencial));
     if($this->numrows>0){       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,21928,'$this->k167_sequencial','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k167_sequencial"]))
         $resac = db_query("insert into db_acount values($acount,3949,21928,'".pg_result($resaco,0,'k167_sequencial')."','$this->k167_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k167_valorantigo"]))
         $resac = db_query("insert into db_acount values($acount,3949,21929,'".pg_result($resaco,0,'k167_valorantigo')."','$this->k167_valorantigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k167_valorcorrigido"]))
         $resac = db_query("insert into db_acount values($acount,3949,21930,'".pg_result($resaco,0,'k167_valorcorrigido')."','$this->k167_valorcorrigido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k167_data"]))
         $resac = db_query("insert into db_acount values($acount,3949,21931,'".pg_result($resaco,0,'k167_data')."','$this->k167_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["k167_abatimento"]))
         $resac = db_query("insert into db_acount values($acount,3949,21932,'".pg_result($resaco,0,'k167_abatimento')."','$this->k167_abatimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Abatimento Correção nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k167_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Abatimento Correção nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k167_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k167_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k167_sequencial=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->k167_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,21928,'$this->k167_sequencial','E')");
       $resac = db_query("insert into db_acount values($acount,3949,21928,'','".pg_result($resaco,0,'k167_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3949,21929,'','".pg_result($resaco,0,'k167_valorantigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3949,21930,'','".pg_result($resaco,0,'k167_valorcorrigido')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3949,21931,'','".pg_result($resaco,0,'k167_data')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3949,21932,'','".pg_result($resaco,0,'k167_abatimento')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from abatimentocorrecao
                    where ";
     $sql2 = "";
      if($this->k167_sequencial != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " k167_sequencial = $this->k167_sequencial ";
}
     $result = @db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Abatimento Correção nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->k167_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Abatimento Correção nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->k167_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k167_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @db_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k167_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentocorrecao ";
     $sql .= "      inner join abatimento  on  abatimento.k125_sequencial = abatimentocorrecao.k167_abatimento";
     $sql .= "      inner join db_config  on  db_config.codigo = abatimento.k125_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = abatimento.k125_usuario";
     $sql .= "      inner join tipoabatimento  on  tipoabatimento.k126_sequencial = abatimento.k125_tipoabatimento";
     $sql .= "      inner join abatimentosituacao  on  abatimentosituacao.k165_sequencial = abatimento.k125_abatimentosituacao";
     $sql2 = "";
     if($dbwhere==""){
       if($k167_sequencial!=null ){
         $sql2 .= " where abatimentocorrecao.k167_sequencial = $k167_sequencial "; 
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
   function sql_query_file ( $k167_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentocorrecao ";
     $sql2 = "";
     if($dbwhere==""){
       if($k167_sequencial!=null ){
         $sql2 .= " where abatimentocorrecao.k167_sequencial = $k167_sequencial "; 
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

<?
//MODULO: recursoshumanos
//CLASSE DA ENTIDADE assentamentorra
class cl_assentamentorra { 
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
   var $h83_assenta = 0; 
   var $h83_valor = 0; 
   var $h83_meses = 0; 
   var $h83_encargos = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h83_assenta = int4 = Código do Assentamento 
                 h83_valor = float8 = Valor 
                 h83_meses = int4 = Quantidade de Meses 
                 h83_encargos = float8 = Valor dos Encargos 
                 ";
   //funcao construtor da classe 
   function cl_assentamentorra() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("assentamentorra"); 
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
       $this->h83_assenta = ($this->h83_assenta == ""?@$GLOBALS["HTTP_POST_VARS"]["h83_assenta"]:$this->h83_assenta);
       $this->h83_valor = ($this->h83_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["h83_valor"]:$this->h83_valor);
       $this->h83_meses = ($this->h83_meses == ""?@$GLOBALS["HTTP_POST_VARS"]["h83_meses"]:$this->h83_meses);
       $this->h83_encargos = ($this->h83_encargos == ""?@$GLOBALS["HTTP_POST_VARS"]["h83_encargos"]:$this->h83_encargos);
     }else{
       $this->h83_assenta = ($this->h83_assenta == ""?@$GLOBALS["HTTP_POST_VARS"]["h83_assenta"]:$this->h83_assenta);
     }
   }
   // funcao para Inclusão
   function incluir ($h83_assenta){ 
      $this->atualizacampos();
     if($this->h83_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "h83_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h83_meses == null ){ 
       $this->erro_sql = " Campo Quantidade de Meses não informado.";
       $this->erro_campo = "h83_meses";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h83_encargos == null ){ 
       $this->erro_sql = " Campo Valor dos Encargos não informado.";
       $this->erro_campo = "h83_encargos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->h83_assenta = $h83_assenta; 
     if(($this->h83_assenta == null) || ($this->h83_assenta == "") ){ 
       $this->erro_sql = " Campo h83_assenta não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into assentamentorra(
                                       h83_assenta 
                                      ,h83_valor 
                                      ,h83_meses 
                                      ,h83_encargos 
                       )
                values (
                                $this->h83_assenta 
                               ,$this->h83_valor 
                               ,$this->h83_meses 
                               ,$this->h83_encargos 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Assentamentos do RRA ($this->h83_assenta) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Assentamentos do RRA já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Assentamentos do RRA ($this->h83_assenta) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h83_assenta;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h83_assenta  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21655,'$this->h83_assenta','I')");
         $resac = db_query("insert into db_acount values($acount,3890,21655,'','".AddSlashes(pg_result($resaco,0,'h83_assenta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3890,21656,'','".AddSlashes(pg_result($resaco,0,'h83_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3890,21657,'','".AddSlashes(pg_result($resaco,0,'h83_meses'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3890,21665,'','".AddSlashes(pg_result($resaco,0,'h83_encargos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($h83_assenta=null) { 
      $this->atualizacampos();
     $sql = " update assentamentorra set ";
     $virgula = "";
     if(trim($this->h83_assenta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h83_assenta"])){ 
       $sql  .= $virgula." h83_assenta = $this->h83_assenta ";
       $virgula = ",";
       if(trim($this->h83_assenta) == null ){ 
         $this->erro_sql = " Campo Código do Assentamento não informado.";
         $this->erro_campo = "h83_assenta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h83_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h83_valor"])){ 
       $sql  .= $virgula." h83_valor = $this->h83_valor ";
       $virgula = ",";
       if(trim($this->h83_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "h83_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h83_meses)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h83_meses"])){ 
       $sql  .= $virgula." h83_meses = $this->h83_meses ";
       $virgula = ",";
       if(trim($this->h83_meses) == null ){ 
         $this->erro_sql = " Campo Quantidade de Meses não informado.";
         $this->erro_campo = "h83_meses";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h83_encargos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h83_encargos"])){ 
       $sql  .= $virgula." h83_encargos = $this->h83_encargos ";
       $virgula = ",";
       if(trim($this->h83_encargos) == null ){ 
         $this->erro_sql = " Campo Valor dos Encargos não informado.";
         $this->erro_campo = "h83_encargos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h83_assenta!=null){
       $sql .= " h83_assenta = $this->h83_assenta";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h83_assenta));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21655,'$this->h83_assenta','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h83_assenta"]) || $this->h83_assenta != "")
             $resac = db_query("insert into db_acount values($acount,3890,21655,'".AddSlashes(pg_result($resaco,$conresaco,'h83_assenta'))."','$this->h83_assenta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h83_valor"]) || $this->h83_valor != "")
             $resac = db_query("insert into db_acount values($acount,3890,21656,'".AddSlashes(pg_result($resaco,$conresaco,'h83_valor'))."','$this->h83_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h83_meses"]) || $this->h83_meses != "")
             $resac = db_query("insert into db_acount values($acount,3890,21657,'".AddSlashes(pg_result($resaco,$conresaco,'h83_meses'))."','$this->h83_meses',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h83_encargos"]) || $this->h83_encargos != "")
             $resac = db_query("insert into db_acount values($acount,3890,21665,'".AddSlashes(pg_result($resaco,$conresaco,'h83_encargos'))."','$this->h83_encargos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assentamentos do RRA não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h83_assenta;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Assentamentos do RRA não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h83_assenta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h83_assenta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($h83_assenta=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($h83_assenta));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21655,'$h83_assenta','E')");
           $resac  = db_query("insert into db_acount values($acount,3890,21655,'','".AddSlashes(pg_result($resaco,$iresaco,'h83_assenta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3890,21656,'','".AddSlashes(pg_result($resaco,$iresaco,'h83_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3890,21657,'','".AddSlashes(pg_result($resaco,$iresaco,'h83_meses'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3890,21665,'','".AddSlashes(pg_result($resaco,$iresaco,'h83_encargos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from assentamentorra
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($h83_assenta)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " h83_assenta = $h83_assenta ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assentamentos do RRA não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h83_assenta;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Assentamentos do RRA não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h83_assenta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h83_assenta;
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
        $this->erro_sql   = "Record Vazio na Tabela:assentamentorra";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($h83_assenta = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from assentamentorra ";
     $sql .= "      inner join assenta  on  assenta.h16_codigo = assentamentorra.h83_assenta";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = assenta.h16_login";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = assenta.h16_assent";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = assenta.h16_regist";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h83_assenta)) {
         $sql2 .= " where assentamentorra.h83_assenta = $h83_assenta "; 
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
   public function sql_query_file ($h83_assenta = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from assentamentorra ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h83_assenta)){
         $sql2 .= " where assentamentorra.h83_assenta = $h83_assenta "; 
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

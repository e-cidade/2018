<?
//MODULO: escola
//CLASSE DA ENTIDADE diarioregracalculo
class cl_diarioregracalculo { 
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
   var $ed125_codigo = 0; 
   var $ed125_ordemperiodo = 0; 
   var $ed125_diario = 0; 
   var $ed125_regracalculo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed125_codigo = int4 = Código 
                 ed125_ordemperiodo = int4 = Ordem do Período 
                 ed125_diario = int4 = Código do Diário 
                 ed125_regracalculo = int4 = Regra de Cálculo 
                 ";
   //funcao construtor da classe 
   function cl_diarioregracalculo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diarioregracalculo"); 
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
       $this->ed125_codigo = ($this->ed125_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed125_codigo"]:$this->ed125_codigo);
       $this->ed125_ordemperiodo = ($this->ed125_ordemperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed125_ordemperiodo"]:$this->ed125_ordemperiodo);
       $this->ed125_diario = ($this->ed125_diario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed125_diario"]:$this->ed125_diario);
       $this->ed125_regracalculo = ($this->ed125_regracalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed125_regracalculo"]:$this->ed125_regracalculo);
     }else{
       $this->ed125_codigo = ($this->ed125_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed125_codigo"]:$this->ed125_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed125_codigo){ 
      $this->atualizacampos();
     if($this->ed125_ordemperiodo == null ){ 
       $this->erro_sql = " Campo Ordem do Período não informado.";
       $this->erro_campo = "ed125_ordemperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed125_diario == null ){ 
       $this->erro_sql = " Campo Código do Diário não informado.";
       $this->erro_campo = "ed125_diario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed125_regracalculo == null ){ 
       $this->erro_sql = " Campo Regra de Cálculo não informado.";
       $this->erro_campo = "ed125_regracalculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed125_codigo == "" || $ed125_codigo == null ){
       $result = db_query("select nextval('diarioregracalculo_ed125_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diarioregracalculo_ed125_codigo_seq do campo: ed125_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed125_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from diarioregracalculo_ed125_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed125_codigo)){
         $this->erro_sql = " Campo ed125_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed125_codigo = $ed125_codigo; 
       }
     }
     if(($this->ed125_codigo == null) || ($this->ed125_codigo == "") ){ 
       $this->erro_sql = " Campo ed125_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diarioregracalculo(
                                       ed125_codigo 
                                      ,ed125_ordemperiodo 
                                      ,ed125_diario 
                                      ,ed125_regracalculo 
                       )
                values (
                                $this->ed125_codigo 
                               ,$this->ed125_ordemperiodo 
                               ,$this->ed125_diario 
                               ,$this->ed125_regracalculo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Diario Regra Cálculo ($this->ed125_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Diario Regra Cálculo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Diario Regra Cálculo ($this->ed125_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed125_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed125_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20892,'$this->ed125_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3759,20892,'','".AddSlashes(pg_result($resaco,0,'ed125_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3759,20893,'','".AddSlashes(pg_result($resaco,0,'ed125_ordemperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3759,20894,'','".AddSlashes(pg_result($resaco,0,'ed125_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3759,20895,'','".AddSlashes(pg_result($resaco,0,'ed125_regracalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed125_codigo=null) { 
      $this->atualizacampos();
     $sql = " update diarioregracalculo set ";
     $virgula = "";
     if(trim($this->ed125_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed125_codigo"])){ 
       $sql  .= $virgula." ed125_codigo = $this->ed125_codigo ";
       $virgula = ",";
       if(trim($this->ed125_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed125_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed125_ordemperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed125_ordemperiodo"])){ 
       $sql  .= $virgula." ed125_ordemperiodo = $this->ed125_ordemperiodo ";
       $virgula = ",";
       if(trim($this->ed125_ordemperiodo) == null ){ 
         $this->erro_sql = " Campo Ordem do Período não informado.";
         $this->erro_campo = "ed125_ordemperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed125_diario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed125_diario"])){ 
       $sql  .= $virgula." ed125_diario = $this->ed125_diario ";
       $virgula = ",";
       if(trim($this->ed125_diario) == null ){ 
         $this->erro_sql = " Campo Código do Diário não informado.";
         $this->erro_campo = "ed125_diario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed125_regracalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed125_regracalculo"])){ 
       $sql  .= $virgula." ed125_regracalculo = $this->ed125_regracalculo ";
       $virgula = ",";
       if(trim($this->ed125_regracalculo) == null ){ 
         $this->erro_sql = " Campo Regra de Cálculo não informado.";
         $this->erro_campo = "ed125_regracalculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed125_codigo!=null){
       $sql .= " ed125_codigo = $this->ed125_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed125_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20892,'$this->ed125_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed125_codigo"]) || $this->ed125_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3759,20892,'".AddSlashes(pg_result($resaco,$conresaco,'ed125_codigo'))."','$this->ed125_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed125_ordemperiodo"]) || $this->ed125_ordemperiodo != "")
             $resac = db_query("insert into db_acount values($acount,3759,20893,'".AddSlashes(pg_result($resaco,$conresaco,'ed125_ordemperiodo'))."','$this->ed125_ordemperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed125_diario"]) || $this->ed125_diario != "")
             $resac = db_query("insert into db_acount values($acount,3759,20894,'".AddSlashes(pg_result($resaco,$conresaco,'ed125_diario'))."','$this->ed125_diario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed125_regracalculo"]) || $this->ed125_regracalculo != "")
             $resac = db_query("insert into db_acount values($acount,3759,20895,'".AddSlashes(pg_result($resaco,$conresaco,'ed125_regracalculo'))."','$this->ed125_regracalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diario Regra Cálculo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed125_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Diario Regra Cálculo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed125_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed125_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed125_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed125_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20892,'$ed125_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3759,20892,'','".AddSlashes(pg_result($resaco,$iresaco,'ed125_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3759,20893,'','".AddSlashes(pg_result($resaco,$iresaco,'ed125_ordemperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3759,20894,'','".AddSlashes(pg_result($resaco,$iresaco,'ed125_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3759,20895,'','".AddSlashes(pg_result($resaco,$iresaco,'ed125_regracalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diarioregracalculo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed125_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed125_codigo = $ed125_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diario Regra Cálculo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed125_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Diario Regra Cálculo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed125_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed125_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:diarioregracalculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed125_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from diarioregracalculo ";
     $sql .= "      inner join regracalculo  on  regracalculo.ed126_codigo = diarioregracalculo.ed125_regracalculo";
     $sql .= "      inner join diario  on  diario.ed95_i_codigo = diarioregracalculo.ed125_diario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = diario.ed95_i_escola";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = diario.ed95_i_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diario.ed95_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = diario.ed95_i_calendario";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario.ed95_i_regencia";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed125_codigo)) {
         $sql2 .= " where diarioregracalculo.ed125_codigo = $ed125_codigo "; 
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
   public function sql_query_file ($ed125_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from diarioregracalculo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed125_codigo)){
         $sql2 .= " where diarioregracalculo.ed125_codigo = $ed125_codigo "; 
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

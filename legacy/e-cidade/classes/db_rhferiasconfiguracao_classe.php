<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhferiasconfiguracao
class cl_rhferiasconfiguracao { 
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
   var $rh168_sequencial = 0; 
   var $rh168_tipoassentamentoferias = 0; 
   var $rh168_tipoassentamentoabono = 0; 
   var $rh168_ultimoperiodoaquisitivo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh168_sequencial = int4 = Código Sequencial 
                 rh168_tipoassentamentoferias = int4 = Assentamento paraFérias 
                 rh168_tipoassentamentoabono = int4 = Assentamento para Abono 
                 rh168_ultimoperiodoaquisitivo = bool = Último Período Aquisitivo 
                 ";
   //funcao construtor da classe 
   function cl_rhferiasconfiguracao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhferiasconfiguracao"); 
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
       $this->rh168_sequencial = ($this->rh168_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh168_sequencial"]:$this->rh168_sequencial);
       $this->rh168_tipoassentamentoferias = ($this->rh168_tipoassentamentoferias == ""?@$GLOBALS["HTTP_POST_VARS"]["rh168_tipoassentamentoferias"]:$this->rh168_tipoassentamentoferias);
       $this->rh168_tipoassentamentoabono = ($this->rh168_tipoassentamentoabono == ""?@$GLOBALS["HTTP_POST_VARS"]["rh168_tipoassentamentoabono"]:$this->rh168_tipoassentamentoabono);
       $this->rh168_ultimoperiodoaquisitivo = ($this->rh168_ultimoperiodoaquisitivo == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh168_ultimoperiodoaquisitivo"]:$this->rh168_ultimoperiodoaquisitivo);
     }else{
       $this->rh168_sequencial = ($this->rh168_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh168_sequencial"]:$this->rh168_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh168_sequencial){ 
      $this->atualizacampos();
     if($this->rh168_tipoassentamentoferias == null ){ 
       $this->rh168_tipoassentamentoferias = "0";
     }
     if($this->rh168_tipoassentamentoabono == null ){ 
       $this->rh168_tipoassentamentoabono = "0";
     }
     if($this->rh168_ultimoperiodoaquisitivo == null ){ 
       $this->erro_sql = " Campo Último Período Aquisitivo não informado.";
       $this->erro_campo = "rh168_ultimoperiodoaquisitivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh168_sequencial == "" || $rh168_sequencial == null ){
       $result = db_query("select nextval('tipoassentamentoferias_rh168_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipoassentamentoferias_rh168_sequencial_seq do campo: rh168_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh168_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipoassentamentoferias_rh168_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh168_sequencial)){
         $this->erro_sql = " Campo rh168_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh168_sequencial = $rh168_sequencial; 
       }
     }
     if(($this->rh168_sequencial == null) || ($this->rh168_sequencial == "") ){ 
       $this->erro_sql = " Campo rh168_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhferiasconfiguracao(
                                       rh168_sequencial 
                                      ,rh168_tipoassentamentoferias 
                                      ,rh168_tipoassentamentoabono 
                                      ,rh168_ultimoperiodoaquisitivo 
                       )
                values (
                                $this->rh168_sequencial 
                               ,$this->rh168_tipoassentamentoferias 
                               ,$this->rh168_tipoassentamentoabono 
                               ,'$this->rh168_ultimoperiodoaquisitivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de configuração para Férias ($this->rh168_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de configuração para Férias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de configuração para Férias ($this->rh168_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh168_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh168_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21573,'$this->rh168_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3872,21573,'','".AddSlashes(pg_result($resaco,0,'rh168_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3872,21574,'','".AddSlashes(pg_result($resaco,0,'rh168_tipoassentamentoferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3872,21575,'','".AddSlashes(pg_result($resaco,0,'rh168_tipoassentamentoabono'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3872,22179,'','".AddSlashes(pg_result($resaco,0,'rh168_ultimoperiodoaquisitivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh168_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhferiasconfiguracao set ";
     $virgula = "";
     if(trim($this->rh168_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh168_sequencial"])){ 
       $sql  .= $virgula." rh168_sequencial = $this->rh168_sequencial ";
       $virgula = ",";
       if(trim($this->rh168_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "rh168_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh168_tipoassentamentoferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh168_tipoassentamentoferias"])){ 
        if(trim($this->rh168_tipoassentamentoferias)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh168_tipoassentamentoferias"])){ 
           $this->rh168_tipoassentamentoferias = "0" ; 
        } 
       $sql  .= $virgula." rh168_tipoassentamentoferias = $this->rh168_tipoassentamentoferias ";
       $virgula = ",";
     }
     if(trim($this->rh168_tipoassentamentoabono)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh168_tipoassentamentoabono"])){ 
        if(trim($this->rh168_tipoassentamentoabono)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh168_tipoassentamentoabono"])){ 
           $this->rh168_tipoassentamentoabono = "0" ; 
        } 
       $sql  .= $virgula." rh168_tipoassentamentoabono = $this->rh168_tipoassentamentoabono ";
       $virgula = ",";
     }
     if(trim($this->rh168_ultimoperiodoaquisitivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh168_ultimoperiodoaquisitivo"])){ 
       $sql  .= $virgula." rh168_ultimoperiodoaquisitivo = '$this->rh168_ultimoperiodoaquisitivo' ";
       $virgula = ",";
       if(trim($this->rh168_ultimoperiodoaquisitivo) == null ){ 
         $this->erro_sql = " Campo Último Período Aquisitivo não informado.";
         $this->erro_campo = "rh168_ultimoperiodoaquisitivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh168_sequencial!=null){
       $sql .= " rh168_sequencial = $this->rh168_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh168_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21573,'$this->rh168_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh168_sequencial"]) || $this->rh168_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3872,21573,'".AddSlashes(pg_result($resaco,$conresaco,'rh168_sequencial'))."','$this->rh168_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh168_tipoassentamentoferias"]) || $this->rh168_tipoassentamentoferias != "")
             $resac = db_query("insert into db_acount values($acount,3872,21574,'".AddSlashes(pg_result($resaco,$conresaco,'rh168_tipoassentamentoferias'))."','$this->rh168_tipoassentamentoferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh168_tipoassentamentoabono"]) || $this->rh168_tipoassentamentoabono != "")
             $resac = db_query("insert into db_acount values($acount,3872,21575,'".AddSlashes(pg_result($resaco,$conresaco,'rh168_tipoassentamentoabono'))."','$this->rh168_tipoassentamentoabono',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh168_ultimoperiodoaquisitivo"]) || $this->rh168_ultimoperiodoaquisitivo != "")
             $resac = db_query("insert into db_acount values($acount,3872,22179,'".AddSlashes(pg_result($resaco,$conresaco,'rh168_ultimoperiodoaquisitivo'))."','$this->rh168_ultimoperiodoaquisitivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de configuração para Férias não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh168_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de configuração para Férias não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh168_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh168_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh168_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh168_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21573,'$rh168_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3872,21573,'','".AddSlashes(pg_result($resaco,$iresaco,'rh168_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3872,21574,'','".AddSlashes(pg_result($resaco,$iresaco,'rh168_tipoassentamentoferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3872,21575,'','".AddSlashes(pg_result($resaco,$iresaco,'rh168_tipoassentamentoabono'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhferiasconfiguracao";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh168_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh168_sequencial = $rh168_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     if (!empty($sql2)) {
       $sql .= " where ";
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Assentamentos para Férias não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh168_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Assentamentos para Férias não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh168_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh168_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhferiasconfiguracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh168_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhferiasconfiguracao ";
     $sql .= "      left  join tipoasse  on  tipoasse.h12_codigo = rhferiasconfiguracao.rh168_tipoassentamentoferias";
     $sql .= "      inner join naturezatipoassentamento  on  naturezatipoassentamento.rh159_sequencial = tipoasse.h12_natureza";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh168_sequencial)) {
         $sql2 .= " where rhferiasconfiguracao.rh168_sequencial = $rh168_sequencial "; 
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
   public function sql_query_file ($rh168_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhferiasconfiguracao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh168_sequencial)){
         $sql2 .= " where rhferiasconfiguracao.rh168_sequencial = $rh168_sequencial "; 
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

  public function sql_query_tipos ($rh168_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from rhferiasconfiguracao ";
    $sql .= "      left  join tipoasse  as assentamento_ferias on  assentamento_ferias.h12_codigo = rhferiasconfiguracao.rh168_tipoassentamentoferias";
    $sql .= "      left  join tipoasse  as assentamento_abono  on  assentamento_abono.h12_codigo  = rhferiasconfiguracao.rh168_tipoassentamentoabono";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($rh168_sequencial)) {
        $sql2 .= " where rhferiasconfiguracao.rh168_sequencial = $rh168_sequencial ";
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

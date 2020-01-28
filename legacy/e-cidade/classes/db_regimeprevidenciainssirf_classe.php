<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE regimeprevidenciainssirf
class cl_regimeprevidenciainssirf { 
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
   var $rh129_sequencial = 0; 
   var $rh129_regimeprevidencia = 0; 
   var $rh129_codigo = 0; 
   var $rh129_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh129_sequencial = int4 = Código 
                 rh129_regimeprevidencia = int4 = Código Regime Previdência 
                 rh129_codigo = int8 = Código INSSIRF 
                 rh129_instit = int4 = Código Instituição 
                 ";
   //funcao construtor da classe 
   function cl_regimeprevidenciainssirf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("regimeprevidenciainssirf"); 
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
       $this->rh129_sequencial = ($this->rh129_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh129_sequencial"]:$this->rh129_sequencial);
       $this->rh129_regimeprevidencia = ($this->rh129_regimeprevidencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh129_regimeprevidencia"]:$this->rh129_regimeprevidencia);
       $this->rh129_codigo = ($this->rh129_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh129_codigo"]:$this->rh129_codigo);
       $this->rh129_instit = ($this->rh129_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh129_instit"]:$this->rh129_instit);
     }else{
       $this->rh129_sequencial = ($this->rh129_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh129_sequencial"]:$this->rh129_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh129_sequencial){ 
      $this->atualizacampos();
     if($this->rh129_regimeprevidencia == null ){ 
       $this->erro_sql = " Campo Código Regime Previdência não informado.";
       $this->erro_campo = "rh129_regimeprevidencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh129_codigo == null ){ 
       $this->erro_sql = " Campo Código INSSIRF não informado.";
       $this->erro_campo = "rh129_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh129_instit == null ){ 
       $this->erro_sql = " Campo Código Instituição não informado.";
       $this->erro_campo = "rh129_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh129_sequencial == "" || $rh129_sequencial == null ){
       $result = db_query("select nextval('regimeprevidenciainssirf_rh129_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: regimeprevidenciainssirf_rh129_sequencial_seq do campo: rh129_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh129_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from regimeprevidenciainssirf_rh129_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh129_sequencial)){
         $this->erro_sql = " Campo rh129_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh129_sequencial = $rh129_sequencial; 
       }
     }
     if(($this->rh129_sequencial == null) || ($this->rh129_sequencial == "") ){ 
       $this->erro_sql = " Campo rh129_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into regimeprevidenciainssirf(
                                       rh129_sequencial 
                                      ,rh129_regimeprevidencia 
                                      ,rh129_codigo 
                                      ,rh129_instit 
                       )
                values (
                                $this->rh129_sequencial 
                               ,$this->rh129_regimeprevidencia 
                               ,$this->rh129_codigo 
                               ,$this->rh129_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Regime Previdência INSSIRF ($this->rh129_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Regime Previdência INSSIRF já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Regime Previdência INSSIRF ($this->rh129_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh129_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh129_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20373,'$this->rh129_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3660,20373,'','".AddSlashes(pg_result($resaco,0,'rh129_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3660,20374,'','".AddSlashes(pg_result($resaco,0,'rh129_regimeprevidencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3660,20375,'','".AddSlashes(pg_result($resaco,0,'rh129_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3660,20376,'','".AddSlashes(pg_result($resaco,0,'rh129_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh129_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update regimeprevidenciainssirf set ";
     $virgula = "";
     if(trim($this->rh129_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh129_sequencial"])){ 
       $sql  .= $virgula." rh129_sequencial = $this->rh129_sequencial ";
       $virgula = ",";
       if(trim($this->rh129_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh129_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh129_regimeprevidencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh129_regimeprevidencia"])){ 
       $sql  .= $virgula." rh129_regimeprevidencia = $this->rh129_regimeprevidencia ";
       $virgula = ",";
       if(trim($this->rh129_regimeprevidencia) == null ){ 
         $this->erro_sql = " Campo Código Regime Previdência não informado.";
         $this->erro_campo = "rh129_regimeprevidencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh129_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh129_codigo"])){ 
       $sql  .= $virgula." rh129_codigo = $this->rh129_codigo ";
       $virgula = ",";
       if(trim($this->rh129_codigo) == null ){ 
         $this->erro_sql = " Campo Código INSSIRF não informado.";
         $this->erro_campo = "rh129_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh129_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh129_instit"])){ 
       $sql  .= $virgula." rh129_instit = $this->rh129_instit ";
       $virgula = ",";
       if(trim($this->rh129_instit) == null ){ 
         $this->erro_sql = " Campo Código Instituição não informado.";
         $this->erro_campo = "rh129_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh129_sequencial!=null){
       $sql .= " rh129_sequencial = $this->rh129_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh129_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20373,'$this->rh129_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh129_sequencial"]) || $this->rh129_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3660,20373,'".AddSlashes(pg_result($resaco,$conresaco,'rh129_sequencial'))."','$this->rh129_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh129_regimeprevidencia"]) || $this->rh129_regimeprevidencia != "")
             $resac = db_query("insert into db_acount values($acount,3660,20374,'".AddSlashes(pg_result($resaco,$conresaco,'rh129_regimeprevidencia'))."','$this->rh129_regimeprevidencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh129_codigo"]) || $this->rh129_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3660,20375,'".AddSlashes(pg_result($resaco,$conresaco,'rh129_codigo'))."','$this->rh129_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh129_instit"]) || $this->rh129_instit != "")
             $resac = db_query("insert into db_acount values($acount,3660,20376,'".AddSlashes(pg_result($resaco,$conresaco,'rh129_instit'))."','$this->rh129_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regime Previdência INSSIRF nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh129_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regime Previdência INSSIRF nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh129_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh129_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh129_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh129_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20373,'$rh129_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3660,20373,'','".AddSlashes(pg_result($resaco,$iresaco,'rh129_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3660,20374,'','".AddSlashes(pg_result($resaco,$iresaco,'rh129_regimeprevidencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3660,20375,'','".AddSlashes(pg_result($resaco,$iresaco,'rh129_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3660,20376,'','".AddSlashes(pg_result($resaco,$iresaco,'rh129_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from regimeprevidenciainssirf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh129_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh129_sequencial = $rh129_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regime Previdência INSSIRF nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh129_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regime Previdência INSSIRF nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh129_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh129_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:regimeprevidenciainssirf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh129_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from regimeprevidenciainssirf ";
     $sql .= "      inner join inssirf  on  inssirf.r33_codigo = regimeprevidenciainssirf.rh129_codigo and  inssirf.r33_instit = regimeprevidenciainssirf.rh129_instit";
     $sql .= "      inner join regimeprevidencia  on  regimeprevidencia.rh127_sequencial = regimeprevidenciainssirf.rh129_regimeprevidencia";
     $sql .= "      inner join db_config  on  db_config.codigo = inssirf.r33_instit";
     $sql .= "      left  join orcelemento  on  orcelemento.o56_codele = inssirf.r33_codele and  orcelemento.o56_anousu = inssirf.r33_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($rh129_sequencial!=null ){
         $sql2 .= " where regimeprevidenciainssirf.rh129_sequencial = $rh129_sequencial "; 
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
   function sql_query_file ( $rh129_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from regimeprevidenciainssirf ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh129_sequencial!=null ){
         $sql2 .= " where regimeprevidenciainssirf.rh129_sequencial = $rh129_sequencial "; 
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
<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE rhpessoalmovcontabancaria
class cl_rhpessoalmovcontabancaria { 
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
   var $rh138_sequencial = 0; 
   var $rh138_rhpessoalmov = 0; 
   var $rh138_contabancaria = 0; 
   var $rh138_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh138_sequencial = int4 = Sequencial 
                 rh138_rhpessoalmov = int4 = Pessoalmov 
                 rh138_contabancaria = int4 = Conta Bancaria 
                 rh138_instit = int4 = Insituição 
                 ";
   //funcao construtor da classe 
   function cl_rhpessoalmovcontabancaria() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpessoalmovcontabancaria"); 
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
       $this->rh138_sequencial = ($this->rh138_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh138_sequencial"]:$this->rh138_sequencial);
       $this->rh138_rhpessoalmov = ($this->rh138_rhpessoalmov == ""?@$GLOBALS["HTTP_POST_VARS"]["rh138_rhpessoalmov"]:$this->rh138_rhpessoalmov);
       $this->rh138_contabancaria = ($this->rh138_contabancaria == ""?@$GLOBALS["HTTP_POST_VARS"]["rh138_contabancaria"]:$this->rh138_contabancaria);
       $this->rh138_instit = ($this->rh138_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh138_instit"]:$this->rh138_instit);
     }else{
       $this->rh138_sequencial = ($this->rh138_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh138_sequencial"]:$this->rh138_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh138_sequencial){ 
      $this->atualizacampos();
     if($this->rh138_rhpessoalmov == null ){ 
       $this->erro_sql = " Campo Pessoalmov não informado.";
       $this->erro_campo = "rh138_rhpessoalmov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh138_contabancaria == null ){ 
       $this->erro_sql = " Campo Conta Bancaria não informado.";
       $this->erro_campo = "rh138_contabancaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh138_instit == null ){ 
       $this->erro_sql = " Campo Insituição não informado.";
       $this->erro_campo = "rh138_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh138_sequencial == "" || $rh138_sequencial == null ){
       $result = db_query("select nextval('rhpessoalmovcontabancaria_rh138_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalmovcontabancaria_rh138_sequencial_seq do campo: rh138_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh138_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalmovcontabancaria_rh138_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh138_sequencial)){
         $this->erro_sql = " Campo rh138_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh138_sequencial = $rh138_sequencial; 
       }
     }
     if(($this->rh138_sequencial == null) || ($this->rh138_sequencial == "") ){ 
       $this->erro_sql = " Campo rh138_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalmovcontabancaria(
                                       rh138_sequencial 
                                      ,rh138_rhpessoalmov 
                                      ,rh138_contabancaria 
                                      ,rh138_instit 
                       )
                values (
                                $this->rh138_sequencial 
                               ,$this->rh138_rhpessoalmov 
                               ,$this->rh138_contabancaria 
                               ,$this->rh138_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de conta bancaria pessoal ($this->rh138_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de conta bancaria pessoal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de conta bancaria pessoal ($this->rh138_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh138_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh138_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20644,'$this->rh138_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3719,20644,'','".AddSlashes(pg_result($resaco,0,'rh138_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3719,20645,'','".AddSlashes(pg_result($resaco,0,'rh138_rhpessoalmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3719,20646,'','".AddSlashes(pg_result($resaco,0,'rh138_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3719,20647,'','".AddSlashes(pg_result($resaco,0,'rh138_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh138_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhpessoalmovcontabancaria set ";
     $virgula = "";
     if(trim($this->rh138_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh138_sequencial"])){ 
       $sql  .= $virgula." rh138_sequencial = $this->rh138_sequencial ";
       $virgula = ",";
       if(trim($this->rh138_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh138_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh138_rhpessoalmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh138_rhpessoalmov"])){ 
       $sql  .= $virgula." rh138_rhpessoalmov = $this->rh138_rhpessoalmov ";
       $virgula = ",";
       if(trim($this->rh138_rhpessoalmov) == null ){ 
         $this->erro_sql = " Campo Pessoalmov não informado.";
         $this->erro_campo = "rh138_rhpessoalmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh138_contabancaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh138_contabancaria"])){ 
       $sql  .= $virgula." rh138_contabancaria = $this->rh138_contabancaria ";
       $virgula = ",";
       if(trim($this->rh138_contabancaria) == null ){ 
         $this->erro_sql = " Campo Conta Bancaria não informado.";
         $this->erro_campo = "rh138_contabancaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh138_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh138_instit"])){ 
       $sql  .= $virgula." rh138_instit = $this->rh138_instit ";
       $virgula = ",";
       if(trim($this->rh138_instit) == null ){ 
         $this->erro_sql = " Campo Insituição não informado.";
         $this->erro_campo = "rh138_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh138_sequencial!=null){
       $sql .= " rh138_sequencial = $this->rh138_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh138_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20644,'$this->rh138_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh138_sequencial"]) || $this->rh138_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3719,20644,'".AddSlashes(pg_result($resaco,$conresaco,'rh138_sequencial'))."','$this->rh138_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh138_rhpessoalmov"]) || $this->rh138_rhpessoalmov != "")
             $resac = db_query("insert into db_acount values($acount,3719,20645,'".AddSlashes(pg_result($resaco,$conresaco,'rh138_rhpessoalmov'))."','$this->rh138_rhpessoalmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh138_contabancaria"]) || $this->rh138_contabancaria != "")
             $resac = db_query("insert into db_acount values($acount,3719,20646,'".AddSlashes(pg_result($resaco,$conresaco,'rh138_contabancaria'))."','$this->rh138_contabancaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh138_instit"]) || $this->rh138_instit != "")
             $resac = db_query("insert into db_acount values($acount,3719,20647,'".AddSlashes(pg_result($resaco,$conresaco,'rh138_instit'))."','$this->rh138_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de conta bancaria pessoal nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh138_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de conta bancaria pessoal nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh138_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh138_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20644,'$rh138_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3719,20644,'','".AddSlashes(pg_result($resaco,$iresaco,'rh138_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3719,20645,'','".AddSlashes(pg_result($resaco,$iresaco,'rh138_rhpessoalmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3719,20646,'','".AddSlashes(pg_result($resaco,$iresaco,'rh138_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3719,20647,'','".AddSlashes(pg_result($resaco,$iresaco,'rh138_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalmovcontabancaria
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh138_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh138_sequencial = $rh138_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de conta bancaria pessoal nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh138_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de conta bancaria pessoal nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh138_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalmovcontabancaria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh138_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpessoalmovcontabancaria ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_seqpes = rhpessoalmovcontabancaria.rh138_rhpessoalmov and  rhpessoalmov.rh02_instit = rhpessoalmovcontabancaria.rh138_instit";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial = rhpessoalmovcontabancaria.rh138_contabancaria";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoalmov.rh02_instit";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpessoalmov.rh02_lota";
     $sql .= "      inner join rhregime  on  rhregime.rh30_codreg = rhpessoalmov.rh02_codreg";
     $sql .= "      left  join rhtipoapos  on  rhtipoapos.rh88_sequencial = rhpessoalmov.rh02_rhtipoapos";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
     $sql2 = "";
     if($dbwhere==""){
       if($rh138_sequencial!=null ){
         $sql2 .= " where rhpessoalmovcontabancaria.rh138_sequencial = $rh138_sequencial "; 
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
   function sql_query_file ( $rh138_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpessoalmovcontabancaria ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh138_sequencial!=null ){
         $sql2 .= " where rhpessoalmovcontabancaria.rh138_sequencial = $rh138_sequencial "; 
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

  function sql_query_retorno ( $rh138_rhpessoalmov=null,$campos="*",$ordem=null,$dbwhere="",$anonovo="",$mesnovo=""){ 
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
     $sql .= " from rhpessoalmovcontabancaria ";
     $sql .= " inner join rhpessoalmov on rh138_rhpessoalmov = rh02_seqpes ";
     $sql .= " left  join rhpessoal on rh01_regist=rh02_regist ";
     $sql .= " left  join rhpessoalmov a on a.rh02_regist=rh01_regist 
                                        and a.rh02_anousu=".$anonovo."
                                        and a.rh02_mesusu=".$mesnovo."
                                        and a.rh02_instit=".db_getsession("DB_instit")." ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh44_seqpes!=null ){
         $sql2 .= " where rhpessoalmovcontabancaria.rh138_rhpessoalmov = $rh44_seqpes "; 
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
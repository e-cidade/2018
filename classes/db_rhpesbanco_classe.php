<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE rhpesbanco
class cl_rhpesbanco { 
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
   var $rh44_seqpes = 0; 
   var $rh44_codban = null; 
   var $rh44_agencia = null; 
   var $rh44_dvagencia = null; 
   var $rh44_conta = null; 
   var $rh44_dvconta = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh44_seqpes = int4 = Sequência 
                 rh44_codban = varchar(10) = Código do banco 
                 rh44_agencia = varchar(10) = Agência 
                 rh44_dvagencia = varchar(2) = DV agência 
                 rh44_conta = varchar(50) = Conta 
                 rh44_dvconta = varchar(2) = DV conta 
                 ";
   //funcao construtor da classe 
   function cl_rhpesbanco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpesbanco"); 
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
       $this->rh44_seqpes = ($this->rh44_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh44_seqpes"]:$this->rh44_seqpes);
       $this->rh44_codban = ($this->rh44_codban == ""?@$GLOBALS["HTTP_POST_VARS"]["rh44_codban"]:$this->rh44_codban);
       $this->rh44_agencia = ($this->rh44_agencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh44_agencia"]:$this->rh44_agencia);
       $this->rh44_dvagencia = ($this->rh44_dvagencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh44_dvagencia"]:$this->rh44_dvagencia);
       $this->rh44_conta = ($this->rh44_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["rh44_conta"]:$this->rh44_conta);
       $this->rh44_dvconta = ($this->rh44_dvconta == ""?@$GLOBALS["HTTP_POST_VARS"]["rh44_dvconta"]:$this->rh44_dvconta);
     }else{
       $this->rh44_seqpes = ($this->rh44_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh44_seqpes"]:$this->rh44_seqpes);
     }
   }
   function atualiza_incluir (){
  	 $this->incluir($this->rh44_seqpes);
   }
   // funcao para inclusao
   function incluir ($rh44_seqpes){ 
      $this->atualizacampos();
     if($this->rh44_codban == null ){ 
       $this->erro_sql = " Campo Código do banco nao Informado.";
       $this->erro_campo = "rh44_codban";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh44_agencia == null ){ 
       $this->erro_sql = " Campo Agência nao Informado.";
       $this->erro_campo = "rh44_agencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh44_dvagencia == null ){ 
       $this->erro_sql = " Campo DV agência nao Informado.";
       $this->erro_campo = "rh44_dvagencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh44_conta == null ){ 
       $this->erro_sql = " Campo Conta nao Informado.";
       $this->erro_campo = "rh44_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh44_dvconta == null ){ 
       $this->erro_sql = " Campo DV conta nao Informado.";
       $this->erro_campo = "rh44_dvconta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh44_seqpes = $rh44_seqpes; 
     if(($this->rh44_seqpes == null) || ($this->rh44_seqpes == "") ){ 
       $this->erro_sql = " Campo rh44_seqpes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpesbanco(
                                       rh44_seqpes 
                                      ,rh44_codban 
                                      ,rh44_agencia 
                                      ,rh44_dvagencia 
                                      ,rh44_conta 
                                      ,rh44_dvconta 
                       )
                values (
                                $this->rh44_seqpes 
                               ,'$this->rh44_codban' 
                               ,'$this->rh44_agencia' 
                               ,'$this->rh44_dvagencia' 
                               ,'$this->rh44_conta' 
                               ,'$this->rh44_dvconta' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Rh Pessoal Banco ($this->rh44_seqpes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Rh Pessoal Banco já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Rh Pessoal Banco ($this->rh44_seqpes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh44_seqpes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh44_seqpes));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7474,'$this->rh44_seqpes','I')");
       $resac = db_query("insert into db_acount values($acount,1238,7474,'','".AddSlashes(pg_result($resaco,0,'rh44_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1238,7475,'','".AddSlashes(pg_result($resaco,0,'rh44_codban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1238,7476,'','".AddSlashes(pg_result($resaco,0,'rh44_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1238,7477,'','".AddSlashes(pg_result($resaco,0,'rh44_dvagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1238,7478,'','".AddSlashes(pg_result($resaco,0,'rh44_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1238,7479,'','".AddSlashes(pg_result($resaco,0,'rh44_dvconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh44_seqpes=null) { 
      $this->atualizacampos();
     $sql = " update rhpesbanco set ";
     $virgula = "";
     if(trim($this->rh44_seqpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh44_seqpes"])){ 
       $sql  .= $virgula." rh44_seqpes = $this->rh44_seqpes ";
       $virgula = ",";
       if(trim($this->rh44_seqpes) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "rh44_seqpes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh44_codban)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh44_codban"])){ 
       $sql  .= $virgula." rh44_codban = '$this->rh44_codban' ";
       $virgula = ",";
       if(trim($this->rh44_codban) == null ){ 
         $this->erro_sql = " Campo Código do banco nao Informado.";
         $this->erro_campo = "rh44_codban";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh44_agencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh44_agencia"])){ 
       $sql  .= $virgula." rh44_agencia = '$this->rh44_agencia' ";
       $virgula = ",";
       if(trim($this->rh44_agencia) == null ){ 
         $this->erro_sql = " Campo Agência nao Informado.";
         $this->erro_campo = "rh44_agencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh44_dvagencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh44_dvagencia"])){ 
       $sql  .= $virgula." rh44_dvagencia = '$this->rh44_dvagencia' ";
       $virgula = ",";
       if(trim($this->rh44_dvagencia) == null ){ 
         $this->erro_sql = " Campo DV agência nao Informado.";
         $this->erro_campo = "rh44_dvagencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh44_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh44_conta"])){ 
       $sql  .= $virgula." rh44_conta = '$this->rh44_conta' ";
       $virgula = ",";
       if(trim($this->rh44_conta) == null ){ 
         $this->erro_sql = " Campo Conta nao Informado.";
         $this->erro_campo = "rh44_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh44_dvconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh44_dvconta"])){ 
       $sql  .= $virgula." rh44_dvconta = '$this->rh44_dvconta' ";
       $virgula = ",";
       if(trim($this->rh44_dvconta) == null ){ 
         $this->erro_sql = " Campo DV conta nao Informado.";
         $this->erro_campo = "rh44_dvconta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh44_seqpes!=null){
       $sql .= " rh44_seqpes = $this->rh44_seqpes";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh44_seqpes));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7474,'$this->rh44_seqpes','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh44_seqpes"]))
           $resac = db_query("insert into db_acount values($acount,1238,7474,'".AddSlashes(pg_result($resaco,$conresaco,'rh44_seqpes'))."','$this->rh44_seqpes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh44_codban"]))
           $resac = db_query("insert into db_acount values($acount,1238,7475,'".AddSlashes(pg_result($resaco,$conresaco,'rh44_codban'))."','$this->rh44_codban',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh44_agencia"]))
           $resac = db_query("insert into db_acount values($acount,1238,7476,'".AddSlashes(pg_result($resaco,$conresaco,'rh44_agencia'))."','$this->rh44_agencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh44_dvagencia"]))
           $resac = db_query("insert into db_acount values($acount,1238,7477,'".AddSlashes(pg_result($resaco,$conresaco,'rh44_dvagencia'))."','$this->rh44_dvagencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh44_conta"]))
           $resac = db_query("insert into db_acount values($acount,1238,7478,'".AddSlashes(pg_result($resaco,$conresaco,'rh44_conta'))."','$this->rh44_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh44_dvconta"]))
           $resac = db_query("insert into db_acount values($acount,1238,7479,'".AddSlashes(pg_result($resaco,$conresaco,'rh44_dvconta'))."','$this->rh44_dvconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rh Pessoal Banco nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh44_seqpes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Rh Pessoal Banco nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh44_seqpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh44_seqpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh44_seqpes=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh44_seqpes));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7474,'$rh44_seqpes','E')");
         $resac = db_query("insert into db_acount values($acount,1238,7474,'','".AddSlashes(pg_result($resaco,$iresaco,'rh44_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1238,7475,'','".AddSlashes(pg_result($resaco,$iresaco,'rh44_codban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1238,7476,'','".AddSlashes(pg_result($resaco,$iresaco,'rh44_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1238,7477,'','".AddSlashes(pg_result($resaco,$iresaco,'rh44_dvagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1238,7478,'','".AddSlashes(pg_result($resaco,$iresaco,'rh44_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1238,7479,'','".AddSlashes(pg_result($resaco,$iresaco,'rh44_dvconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhpesbanco
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh44_seqpes != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh44_seqpes = $rh44_seqpes ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rh Pessoal Banco nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh44_seqpes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Rh Pessoal Banco nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh44_seqpes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh44_seqpes;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpesbanco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   
   function sql_query ( $rh44_seqpes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpesbanco ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_seqpes = rhpesbanco.rh44_seqpes";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = rhpesbanco.rh44_codban";
     $sql .= "      inner join tpcontra  on  tpcontra.h13_codigo = rhpessoalmov.rh02_tpcont";
     $sql .= "      inner join rhregime  on  rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
		                                    and  rhregime.rh30_instit = rhpessoalmov.rh02_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh44_seqpes!=null ){
         $sql2 .= " where rhpesbanco.rh44_seqpes = $rh44_seqpes "; 
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
   function sql_query_file ( $rh44_seqpes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpesbanco ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh44_seqpes!=null ){
         $sql2 .= " where rhpesbanco.rh44_seqpes = $rh44_seqpes "; 
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
   function sql_query_retorno ( $rh44_seqpes=null,$campos="*",$ordem=null,$dbwhere="",$anonovo="",$mesnovo=""){ 
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
     $sql .= " from rhpesbanco ";
     $sql .= " inner join rhpessoalmov on rh44_seqpes=rh02_seqpes ";
     $sql .= " left  join rhpessoal on rh01_regist=rh02_regist ";
     $sql .= " left  join rhpessoalmov a on a.rh02_regist=rh01_regist 
		                                    and a.rh02_anousu=".$anonovo."
                                        and a.rh02_mesusu=".$mesnovo."
																				and a.rh02_instit=".db_getsession("DB_instit")." ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh44_seqpes!=null ){
         $sql2 .= " where rhpesbanco.rh44_seqpes = $rh44_seqpes "; 
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
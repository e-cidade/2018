<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: caixa
//CLASSE DA ENTIDADE disbancoprocesso
class cl_disbancoprocesso { 
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
   var $k142_sequencial = 0; 
   var $k142_idret = 0; 
   var $k142_processo = null; 
   var $k142_dataprocesso_dia = null; 
   var $k142_dataprocesso_mes = null; 
   var $k142_dataprocesso_ano = null; 
   var $k142_dataprocesso = null; 
   var $k142_titular = null; 
   var $k142_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k142_sequencial = int4 = Sequencial 
                 k142_idret = int4 = Id. Ret 
                 k142_processo = varchar(15) = Processo de Protocolo 
                 k142_dataprocesso = date = Data do Processo 
                 k142_titular = varchar(40) = Titular do Processo 
                 k142_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_disbancoprocesso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("disbancoprocesso"); 
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
       $this->k142_sequencial = ($this->k142_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k142_sequencial"]:$this->k142_sequencial);
       $this->k142_idret = ($this->k142_idret == ""?@$GLOBALS["HTTP_POST_VARS"]["k142_idret"]:$this->k142_idret);
       $this->k142_processo = ($this->k142_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["k142_processo"]:$this->k142_processo);
       if($this->k142_dataprocesso == ""){
         $this->k142_dataprocesso_dia = ($this->k142_dataprocesso_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k142_dataprocesso_dia"]:$this->k142_dataprocesso_dia);
         $this->k142_dataprocesso_mes = ($this->k142_dataprocesso_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k142_dataprocesso_mes"]:$this->k142_dataprocesso_mes);
         $this->k142_dataprocesso_ano = ($this->k142_dataprocesso_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k142_dataprocesso_ano"]:$this->k142_dataprocesso_ano);
         if($this->k142_dataprocesso_dia != ""){
            $this->k142_dataprocesso = $this->k142_dataprocesso_ano."-".$this->k142_dataprocesso_mes."-".$this->k142_dataprocesso_dia;
         }
       }
       $this->k142_titular = ($this->k142_titular == ""?@$GLOBALS["HTTP_POST_VARS"]["k142_titular"]:$this->k142_titular);
       $this->k142_observacao = ($this->k142_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k142_observacao"]:$this->k142_observacao);
     }else{
       $this->k142_sequencial = ($this->k142_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k142_sequencial"]:$this->k142_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k142_sequencial){ 
      $this->atualizacampos();
     if($this->k142_idret == null ){ 
       $this->erro_sql = " Campo Id. Ret nao Informado.";
       $this->erro_campo = "k142_idret";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k142_dataprocesso == null ){ 
       $this->k142_dataprocesso = "null";
     }
     if($k142_sequencial == "" || $k142_sequencial == null ){
       $result = db_query("select nextval('disbancoprocesso_k142_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: disbancoprocesso_k142_sequencial_seq do campo: k142_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k142_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from disbancoprocesso_k142_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k142_sequencial)){
         $this->erro_sql = " Campo k142_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k142_sequencial = $k142_sequencial; 
       }
     }
     if(($this->k142_sequencial == null) || ($this->k142_sequencial == "") ){ 
       $this->erro_sql = " Campo k142_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into disbancoprocesso(
                                       k142_sequencial 
                                      ,k142_idret 
                                      ,k142_processo 
                                      ,k142_dataprocesso 
                                      ,k142_titular 
                                      ,k142_observacao 
                       )
                values (
                                $this->k142_sequencial 
                               ,$this->k142_idret 
                               ,'$this->k142_processo' 
                               ,".($this->k142_dataprocesso == "null" || $this->k142_dataprocesso == ""?"null":"'".$this->k142_dataprocesso."'")." 
                               ,'$this->k142_titular' 
                               ,'$this->k142_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Processo de Protocolo da Disbanco ($this->k142_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Processo de Protocolo da Disbanco já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Processo de Protocolo da Disbanco ($this->k142_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k142_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k142_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19189,'$this->k142_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3409,19189,'','".AddSlashes(pg_result($resaco,0,'k142_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3409,19190,'','".AddSlashes(pg_result($resaco,0,'k142_idret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3409,19191,'','".AddSlashes(pg_result($resaco,0,'k142_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3409,19192,'','".AddSlashes(pg_result($resaco,0,'k142_dataprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3409,19193,'','".AddSlashes(pg_result($resaco,0,'k142_titular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3409,19194,'','".AddSlashes(pg_result($resaco,0,'k142_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k142_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update disbancoprocesso set ";
     $virgula = "";
     if(trim($this->k142_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k142_sequencial"])){ 
       $sql  .= $virgula." k142_sequencial = $this->k142_sequencial ";
       $virgula = ",";
       if(trim($this->k142_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k142_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k142_idret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k142_idret"])){ 
       $sql  .= $virgula." k142_idret = $this->k142_idret ";
       $virgula = ",";
       if(trim($this->k142_idret) == null ){ 
         $this->erro_sql = " Campo Id. Ret nao Informado.";
         $this->erro_campo = "k142_idret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k142_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k142_processo"])){ 
       $sql  .= $virgula." k142_processo = '$this->k142_processo' ";
       $virgula = ",";
     }
     if(trim($this->k142_dataprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k142_dataprocesso_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k142_dataprocesso_dia"] !="") ){ 
       $sql  .= $virgula." k142_dataprocesso = '$this->k142_dataprocesso' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k142_dataprocesso_dia"])){ 
         $sql  .= $virgula." k142_dataprocesso = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k142_titular)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k142_titular"])){ 
       $sql  .= $virgula." k142_titular = '$this->k142_titular' ";
       $virgula = ",";
     }
     if(trim($this->k142_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k142_observacao"])){ 
       $sql  .= $virgula." k142_observacao = '$this->k142_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k142_sequencial!=null){
       $sql .= " k142_sequencial = $this->k142_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k142_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19189,'$this->k142_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k142_sequencial"]) || $this->k142_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3409,19189,'".AddSlashes(pg_result($resaco,$conresaco,'k142_sequencial'))."','$this->k142_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k142_idret"]) || $this->k142_idret != "")
           $resac = db_query("insert into db_acount values($acount,3409,19190,'".AddSlashes(pg_result($resaco,$conresaco,'k142_idret'))."','$this->k142_idret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k142_processo"]) || $this->k142_processo != "")
           $resac = db_query("insert into db_acount values($acount,3409,19191,'".AddSlashes(pg_result($resaco,$conresaco,'k142_processo'))."','$this->k142_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k142_dataprocesso"]) || $this->k142_dataprocesso != "")
           $resac = db_query("insert into db_acount values($acount,3409,19192,'".AddSlashes(pg_result($resaco,$conresaco,'k142_dataprocesso'))."','$this->k142_dataprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k142_titular"]) || $this->k142_titular != "")
           $resac = db_query("insert into db_acount values($acount,3409,19193,'".AddSlashes(pg_result($resaco,$conresaco,'k142_titular'))."','$this->k142_titular',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k142_observacao"]) || $this->k142_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3409,19194,'".AddSlashes(pg_result($resaco,$conresaco,'k142_observacao'))."','$this->k142_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo de Protocolo da Disbanco nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k142_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Processo de Protocolo da Disbanco nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k142_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k142_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k142_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k142_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19189,'$k142_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3409,19189,'','".AddSlashes(pg_result($resaco,$iresaco,'k142_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3409,19190,'','".AddSlashes(pg_result($resaco,$iresaco,'k142_idret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3409,19191,'','".AddSlashes(pg_result($resaco,$iresaco,'k142_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3409,19192,'','".AddSlashes(pg_result($resaco,$iresaco,'k142_dataprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3409,19193,'','".AddSlashes(pg_result($resaco,$iresaco,'k142_titular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3409,19194,'','".AddSlashes(pg_result($resaco,$iresaco,'k142_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from disbancoprocesso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k142_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k142_sequencial = $k142_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo de Protocolo da Disbanco nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k142_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Processo de Protocolo da Disbanco nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k142_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k142_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:disbancoprocesso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k142_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from disbancoprocesso ";
     $sql .= "      inner join disbanco  on  disbanco.idret = disbancoprocesso.k142_idret";
     $sql .= "      inner join db_config  on  db_config.codigo = disbanco.instit";
     $sql .= "      inner join disarq  on  disarq.codret = disbanco.codret";
     $sql2 = "";
     if($dbwhere==""){
       if($k142_sequencial!=null ){
         $sql2 .= " where disbancoprocesso.k142_sequencial = $k142_sequencial "; 
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
   function sql_query_file ( $k142_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from disbancoprocesso ";
     $sql2 = "";
     if($dbwhere==""){
       if($k142_sequencial!=null ){
         $sql2 .= " where disbancoprocesso.k142_sequencial = $k142_sequencial "; 
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
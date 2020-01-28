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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_versaoclientes
class cl_db_versaoclientes { 
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
   var $db19_sequen = 0; 
   var $db19_codver = 0; 
   var $db19_codcli = 0; 
   var $db19_data_dia = null; 
   var $db19_data_mes = null; 
   var $db19_data_ano = null; 
   var $db19_data = null; 
   var $db19_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db19_sequen = int4 = Sequencial 
                 db19_codver = int4 = Código da Versão 
                 db19_codcli = int4 = Código do cliente 
                 db19_data = date = Data 
                 db19_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_db_versaoclientes() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_versaoclientes"); 
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
       $this->db19_sequen = ($this->db19_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["db19_sequen"]:$this->db19_sequen);
       $this->db19_codver = ($this->db19_codver == ""?@$GLOBALS["HTTP_POST_VARS"]["db19_codver"]:$this->db19_codver);
       $this->db19_codcli = ($this->db19_codcli == ""?@$GLOBALS["HTTP_POST_VARS"]["db19_codcli"]:$this->db19_codcli);
       if($this->db19_data == ""){
         $this->db19_data_dia = ($this->db19_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db19_data_dia"]:$this->db19_data_dia);
         $this->db19_data_mes = ($this->db19_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db19_data_mes"]:$this->db19_data_mes);
         $this->db19_data_ano = ($this->db19_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db19_data_ano"]:$this->db19_data_ano);
         if($this->db19_data_dia != ""){
            $this->db19_data = $this->db19_data_ano."-".$this->db19_data_mes."-".$this->db19_data_dia;
         }
       }
       $this->db19_obs = ($this->db19_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db19_obs"]:$this->db19_obs);
     }else{
       $this->db19_sequen = ($this->db19_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["db19_sequen"]:$this->db19_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($db19_sequen){ 
      $this->atualizacampos();
     if($this->db19_codver == null ){ 
       $this->erro_sql = " Campo Código da Versão nao Informado.";
       $this->erro_campo = "db19_codver";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db19_codcli == null ){ 
       $this->erro_sql = " Campo Código do cliente nao Informado.";
       $this->erro_campo = "db19_codcli";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db19_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "db19_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db19_sequen == "" || $db19_sequen == null ){
       $result = db_query("select nextval('db_versaoclientes_db19_sequen_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_versaoclientes_db19_sequen_seq do campo: db19_sequen"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db19_sequen = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_versaoclientes_db19_sequen_seq");
       if(($result != false) && (pg_result($result,0,0) < $db19_sequen)){
         $this->erro_sql = " Campo db19_sequen maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db19_sequen = $db19_sequen; 
       }
     }
     if(($this->db19_sequen == null) || ($this->db19_sequen == "") ){ 
       $this->erro_sql = " Campo db19_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_versaoclientes(
                                       db19_sequen 
                                      ,db19_codver 
                                      ,db19_codcli 
                                      ,db19_data 
                                      ,db19_obs 
                       )
                values (
                                $this->db19_sequen 
                               ,$this->db19_codver 
                               ,$this->db19_codcli 
                               ,".($this->db19_data == "null" || $this->db19_data == ""?"null":"'".$this->db19_data."'")." 
                               ,'$this->db19_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Clientes e Versões ($this->db19_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Clientes e Versões já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Clientes e Versões ($this->db19_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db19_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db19_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12234,'$this->db19_sequen','I')");
       $resac = db_query("insert into db_acount values($acount,2127,12234,'','".AddSlashes(pg_result($resaco,0,'db19_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2127,12235,'','".AddSlashes(pg_result($resaco,0,'db19_codver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2127,12236,'','".AddSlashes(pg_result($resaco,0,'db19_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2127,12238,'','".AddSlashes(pg_result($resaco,0,'db19_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2127,12237,'','".AddSlashes(pg_result($resaco,0,'db19_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db19_sequen=null) { 
      $this->atualizacampos();
     $sql = " update db_versaoclientes set ";
     $virgula = "";
     if(trim($this->db19_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db19_sequen"])){ 
       $sql  .= $virgula." db19_sequen = $this->db19_sequen ";
       $virgula = ",";
       if(trim($this->db19_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db19_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db19_codver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db19_codver"])){ 
       $sql  .= $virgula." db19_codver = $this->db19_codver ";
       $virgula = ",";
       if(trim($this->db19_codver) == null ){ 
         $this->erro_sql = " Campo Código da Versão nao Informado.";
         $this->erro_campo = "db19_codver";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db19_codcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db19_codcli"])){ 
       $sql  .= $virgula." db19_codcli = $this->db19_codcli ";
       $virgula = ",";
       if(trim($this->db19_codcli) == null ){ 
         $this->erro_sql = " Campo Código do cliente nao Informado.";
         $this->erro_campo = "db19_codcli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db19_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db19_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db19_data_dia"] !="") ){ 
       $sql  .= $virgula." db19_data = '$this->db19_data' ";
       $virgula = ",";
       if(trim($this->db19_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "db19_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db19_data_dia"])){ 
         $sql  .= $virgula." db19_data = null ";
         $virgula = ",";
         if(trim($this->db19_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "db19_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db19_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db19_obs"])){ 
       $sql  .= $virgula." db19_obs = '$this->db19_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db19_sequen!=null){
       $sql .= " db19_sequen = $this->db19_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db19_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12234,'$this->db19_sequen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db19_sequen"]))
           $resac = db_query("insert into db_acount values($acount,2127,12234,'".AddSlashes(pg_result($resaco,$conresaco,'db19_sequen'))."','$this->db19_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db19_codver"]))
           $resac = db_query("insert into db_acount values($acount,2127,12235,'".AddSlashes(pg_result($resaco,$conresaco,'db19_codver'))."','$this->db19_codver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db19_codcli"]))
           $resac = db_query("insert into db_acount values($acount,2127,12236,'".AddSlashes(pg_result($resaco,$conresaco,'db19_codcli'))."','$this->db19_codcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db19_data"]))
           $resac = db_query("insert into db_acount values($acount,2127,12238,'".AddSlashes(pg_result($resaco,$conresaco,'db19_data'))."','$this->db19_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db19_obs"]))
           $resac = db_query("insert into db_acount values($acount,2127,12237,'".AddSlashes(pg_result($resaco,$conresaco,'db19_obs'))."','$this->db19_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Clientes e Versões nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db19_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Clientes e Versões nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db19_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db19_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db19_sequen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db19_sequen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12234,'$db19_sequen','E')");
         $resac = db_query("insert into db_acount values($acount,2127,12234,'','".AddSlashes(pg_result($resaco,$iresaco,'db19_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2127,12235,'','".AddSlashes(pg_result($resaco,$iresaco,'db19_codver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2127,12236,'','".AddSlashes(pg_result($resaco,$iresaco,'db19_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2127,12238,'','".AddSlashes(pg_result($resaco,$iresaco,'db19_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2127,12237,'','".AddSlashes(pg_result($resaco,$iresaco,'db19_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_versaoclientes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db19_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db19_sequen = $db19_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Clientes e Versões nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db19_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Clientes e Versões nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db19_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db19_sequen;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_versaoclientes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   // funcao do sql 
   function sql_query ( $db19_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_versaoclientes ";
     $sql .= "      inner join clientes  on  clientes.at01_codcli = db_versaoclientes.db19_codcli";
     $sql .= "      inner join db_versao  on  db_versao.db30_codver = db_versaoclientes.db19_codver";
     $sql2 = "";
     if($dbwhere==""){
       if($db19_sequen!=null ){
         $sql2 .= " where db_versaoclientes.db19_sequen = $db19_sequen "; 
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
   function sql_query_file ( $db19_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_versaoclientes ";
     $sql2 = "";
     if($dbwhere==""){
       if($db19_sequen!=null ){
         $sql2 .= " where db_versaoclientes.db19_sequen = $db19_sequen "; 
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
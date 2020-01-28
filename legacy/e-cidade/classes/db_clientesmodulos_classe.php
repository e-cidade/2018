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

//MODULO: atendimento
//CLASSE DA ENTIDADE clientesmodulos
class cl_clientesmodulos { 
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
   var $at74_sequencial = 0; 
   var $at74_codcli = 0; 
   var $at74_id_item = 0; 
   var $at74_data_dia = null; 
   var $at74_data_mes = null; 
   var $at74_data_ano = null; 
   var $at74_data = null; 
   var $at74_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at74_sequencial = int4 = Sequencial 
                 at74_codcli = int4 = Código do cliente 
                 at74_id_item = int4 = Código do ítem 
                 at74_data = date = Data Liberação 
                 at74_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_clientesmodulos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("clientesmodulos"); 
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
       $this->at74_sequencial = ($this->at74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at74_sequencial"]:$this->at74_sequencial);
       $this->at74_codcli = ($this->at74_codcli == ""?@$GLOBALS["HTTP_POST_VARS"]["at74_codcli"]:$this->at74_codcli);
       $this->at74_id_item = ($this->at74_id_item == ""?@$GLOBALS["HTTP_POST_VARS"]["at74_id_item"]:$this->at74_id_item);
       if($this->at74_data == ""){
         $this->at74_data_dia = ($this->at74_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at74_data_dia"]:$this->at74_data_dia);
         $this->at74_data_mes = ($this->at74_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at74_data_mes"]:$this->at74_data_mes);
         $this->at74_data_ano = ($this->at74_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at74_data_ano"]:$this->at74_data_ano);
         if($this->at74_data_dia != ""){
            $this->at74_data = $this->at74_data_ano."-".$this->at74_data_mes."-".$this->at74_data_dia;
         }
       }
       $this->at74_obs = ($this->at74_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["at74_obs"]:$this->at74_obs);
     }else{
       $this->at74_sequencial = ($this->at74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at74_sequencial"]:$this->at74_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at74_sequencial){ 
      $this->atualizacampos();
     if($this->at74_codcli == null ){ 
       $this->erro_sql = " Campo Código do cliente nao Informado.";
       $this->erro_campo = "at74_codcli";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at74_id_item == null ){ 
       $this->erro_sql = " Campo Código do ítem nao Informado.";
       $this->erro_campo = "at74_id_item";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at74_data == null ){ 
       $this->at74_data = "null";
     }
     if($at74_sequencial == "" || $at74_sequencial == null ){
       $result = db_query("select nextval('clientesmodulos_at74_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: clientesmodulos_at74_sequencial_seq do campo: at74_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at74_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from clientesmodulos_at74_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at74_sequencial)){
         $this->erro_sql = " Campo at74_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at74_sequencial = $at74_sequencial; 
       }
     }
     if(($this->at74_sequencial == null) || ($this->at74_sequencial == "") ){ 
       $this->erro_sql = " Campo at74_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into clientesmodulos(
                                       at74_sequencial 
                                      ,at74_codcli 
                                      ,at74_id_item 
                                      ,at74_data 
                                      ,at74_obs 
                       )
                values (
                                $this->at74_sequencial 
                               ,$this->at74_codcli 
                               ,$this->at74_id_item 
                               ,".($this->at74_data == "null" || $this->at74_data == ""?"null":"'".$this->at74_data."'")." 
                               ,'$this->at74_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Clientes e Módulos ($this->at74_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Clientes e Módulos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Clientes e Módulos ($this->at74_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at74_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at74_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12100,'$this->at74_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2099,12100,'','".AddSlashes(pg_result($resaco,0,'at74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2099,12101,'','".AddSlashes(pg_result($resaco,0,'at74_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2099,12102,'','".AddSlashes(pg_result($resaco,0,'at74_id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2099,12103,'','".AddSlashes(pg_result($resaco,0,'at74_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2099,12104,'','".AddSlashes(pg_result($resaco,0,'at74_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at74_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update clientesmodulos set ";
     $virgula = "";
     if(trim($this->at74_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at74_sequencial"])){ 
       $sql  .= $virgula." at74_sequencial = $this->at74_sequencial ";
       $virgula = ",";
       if(trim($this->at74_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at74_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at74_codcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at74_codcli"])){ 
       $sql  .= $virgula." at74_codcli = $this->at74_codcli ";
       $virgula = ",";
       if(trim($this->at74_codcli) == null ){ 
         $this->erro_sql = " Campo Código do cliente nao Informado.";
         $this->erro_campo = "at74_codcli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at74_id_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at74_id_item"])){ 
       $sql  .= $virgula." at74_id_item = $this->at74_id_item ";
       $virgula = ",";
       if(trim($this->at74_id_item) == null ){ 
         $this->erro_sql = " Campo Código do ítem nao Informado.";
         $this->erro_campo = "at74_id_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at74_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at74_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at74_data_dia"] !="") ){ 
       $sql  .= $virgula." at74_data = '$this->at74_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at74_data_dia"])){ 
         $sql  .= $virgula." at74_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->at74_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at74_obs"])){ 
       $sql  .= $virgula." at74_obs = '$this->at74_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($at74_sequencial!=null){
       $sql .= " at74_sequencial = $this->at74_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at74_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12100,'$this->at74_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at74_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2099,12100,'".AddSlashes(pg_result($resaco,$conresaco,'at74_sequencial'))."','$this->at74_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at74_codcli"]))
           $resac = db_query("insert into db_acount values($acount,2099,12101,'".AddSlashes(pg_result($resaco,$conresaco,'at74_codcli'))."','$this->at74_codcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at74_id_item"]))
           $resac = db_query("insert into db_acount values($acount,2099,12102,'".AddSlashes(pg_result($resaco,$conresaco,'at74_id_item'))."','$this->at74_id_item',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at74_data"]))
           $resac = db_query("insert into db_acount values($acount,2099,12103,'".AddSlashes(pg_result($resaco,$conresaco,'at74_data'))."','$this->at74_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at74_obs"]))
           $resac = db_query("insert into db_acount values($acount,2099,12104,'".AddSlashes(pg_result($resaco,$conresaco,'at74_obs'))."','$this->at74_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Clientes e Módulos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at74_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Clientes e Módulos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at74_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at74_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12100,'$at74_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2099,12100,'','".AddSlashes(pg_result($resaco,$iresaco,'at74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2099,12101,'','".AddSlashes(pg_result($resaco,$iresaco,'at74_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2099,12102,'','".AddSlashes(pg_result($resaco,$iresaco,'at74_id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2099,12103,'','".AddSlashes(pg_result($resaco,$iresaco,'at74_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2099,12104,'','".AddSlashes(pg_result($resaco,$iresaco,'at74_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from clientesmodulos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at74_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at74_sequencial = $at74_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Clientes e Módulos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at74_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Clientes e Módulos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at74_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:clientesmodulos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clientesmodulos ";
     $sql .= "      inner join db_modulos  on  db_modulos.id_item = clientesmodulos.at74_id_item";
     $sql .= "      inner join clientes  on  clientes.at01_codcli = clientesmodulos.at74_codcli";
     $sql2 = "";
     if($dbwhere==""){
       if($at74_sequencial!=null ){
         $sql2 .= " where clientesmodulos.at74_sequencial = $at74_sequencial "; 
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
   function sql_query_areas ( $at74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clientesmodulos ";
     $sql .= "      inner join db_modulos  on  db_modulos.id_item = clientesmodulos.at74_id_item";     
     $sql .= "      inner join atendcadareamod  on  db_modulos.id_item = atendcadareamod.at26_id_item";
     $sql .= "      inner join atendcadarea  on  atendcadarea.at26_sequencial = atendcadareamod.at26_codarea";
     $sql .= "      inner join clientes  on  clientes.at01_codcli = clientesmodulos.at74_codcli";
     $sql2 = "";
     if($dbwhere==""){
       if($at74_sequencial!=null ){
         $sql2 .= " where clientesmodulos.at74_sequencial = $at74_sequencial "; 
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
   function sql_query_file ( $at74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clientesmodulos ";
     $sql2 = "";
     if($dbwhere==""){
       if($at74_sequencial!=null ){
         $sql2 .= " where clientesmodulos.at74_sequencial = $at74_sequencial "; 
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
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

//MODULO: divida
//CLASSE DA ENTIDADE prescricaoanula
class cl_prescricaoanula { 
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
   var $k120_sequencial = 0; 
   var $k120_id_usuario = 0; 
   var $k120_instit = 0; 
   var $k120_obs = null; 
   var $k120_data_dia = null; 
   var $k120_data_mes = null; 
   var $k120_data_ano = null; 
   var $k120_data = null; 
   var $k120_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k120_sequencial = int4 = Sequencial 
                 k120_id_usuario = int4 = Id Usuário 
                 k120_instit = int4 = Instituição 
                 k120_obs = text = Observação 
                 k120_data = date = Data 
                 k120_hora = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_prescricaoanula() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prescricaoanula"); 
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
       $this->k120_sequencial = ($this->k120_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k120_sequencial"]:$this->k120_sequencial);
       $this->k120_id_usuario = ($this->k120_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k120_id_usuario"]:$this->k120_id_usuario);
       $this->k120_instit = ($this->k120_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k120_instit"]:$this->k120_instit);
       $this->k120_obs = ($this->k120_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k120_obs"]:$this->k120_obs);
       if($this->k120_data == ""){
         $this->k120_data_dia = ($this->k120_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k120_data_dia"]:$this->k120_data_dia);
         $this->k120_data_mes = ($this->k120_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k120_data_mes"]:$this->k120_data_mes);
         $this->k120_data_ano = ($this->k120_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k120_data_ano"]:$this->k120_data_ano);
         if($this->k120_data_dia != ""){
            $this->k120_data = $this->k120_data_ano."-".$this->k120_data_mes."-".$this->k120_data_dia;
         }
       }
       $this->k120_hora = ($this->k120_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k120_hora"]:$this->k120_hora);
     }else{
       $this->k120_sequencial = ($this->k120_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k120_sequencial"]:$this->k120_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k120_sequencial){ 
      $this->atualizacampos();
     if($this->k120_id_usuario == null ){ 
       $this->erro_sql = " Campo Id Usuário nao Informado.";
       $this->erro_campo = "k120_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k120_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "k120_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k120_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "k120_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k120_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k120_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k120_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k120_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k120_sequencial == "" || $k120_sequencial == null ){
       $result = db_query("select nextval('prescricaoanula_k120_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prescricaoanula_k120_sequencial_seq do campo: k120_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k120_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prescricaoanula_k120_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k120_sequencial)){
         $this->erro_sql = " Campo k120_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k120_sequencial = $k120_sequencial; 
       }
     }
     if(($this->k120_sequencial == null) || ($this->k120_sequencial == "") ){ 
       $this->erro_sql = " Campo k120_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prescricaoanula(
                                       k120_sequencial 
                                      ,k120_id_usuario 
                                      ,k120_instit 
                                      ,k120_obs 
                                      ,k120_data 
                                      ,k120_hora 
                       )
                values (
                                $this->k120_sequencial 
                               ,$this->k120_id_usuario 
                               ,$this->k120_instit 
                               ,'$this->k120_obs' 
                               ,".($this->k120_data == "null" || $this->k120_data == ""?"null":"'".$this->k120_data."'")." 
                               ,'$this->k120_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Anulação de Prescrição ($this->k120_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Anulação de Prescrição já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Anulação de Prescrição ($this->k120_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k120_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k120_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17624,'$this->k120_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3113,17624,'','".AddSlashes(pg_result($resaco,0,'k120_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3113,17625,'','".AddSlashes(pg_result($resaco,0,'k120_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3113,17626,'','".AddSlashes(pg_result($resaco,0,'k120_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3113,17627,'','".AddSlashes(pg_result($resaco,0,'k120_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3113,17628,'','".AddSlashes(pg_result($resaco,0,'k120_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3113,17629,'','".AddSlashes(pg_result($resaco,0,'k120_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k120_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update prescricaoanula set ";
     $virgula = "";
     if(trim($this->k120_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k120_sequencial"])){ 
       $sql  .= $virgula." k120_sequencial = $this->k120_sequencial ";
       $virgula = ",";
       if(trim($this->k120_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k120_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k120_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k120_id_usuario"])){ 
       $sql  .= $virgula." k120_id_usuario = $this->k120_id_usuario ";
       $virgula = ",";
       if(trim($this->k120_id_usuario) == null ){ 
         $this->erro_sql = " Campo Id Usuário nao Informado.";
         $this->erro_campo = "k120_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k120_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k120_instit"])){ 
       $sql  .= $virgula." k120_instit = $this->k120_instit ";
       $virgula = ",";
       if(trim($this->k120_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "k120_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k120_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k120_obs"])){ 
       $sql  .= $virgula." k120_obs = '$this->k120_obs' ";
       $virgula = ",";
       if(trim($this->k120_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "k120_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k120_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k120_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k120_data_dia"] !="") ){ 
       $sql  .= $virgula." k120_data = '$this->k120_data' ";
       $virgula = ",";
       if(trim($this->k120_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k120_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k120_data_dia"])){ 
         $sql  .= $virgula." k120_data = null ";
         $virgula = ",";
         if(trim($this->k120_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k120_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k120_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k120_hora"])){ 
       $sql  .= $virgula." k120_hora = '$this->k120_hora' ";
       $virgula = ",";
       if(trim($this->k120_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k120_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k120_sequencial!=null){
       $sql .= " k120_sequencial = $this->k120_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k120_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17624,'$this->k120_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k120_sequencial"]) || $this->k120_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3113,17624,'".AddSlashes(pg_result($resaco,$conresaco,'k120_sequencial'))."','$this->k120_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k120_id_usuario"]) || $this->k120_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3113,17625,'".AddSlashes(pg_result($resaco,$conresaco,'k120_id_usuario'))."','$this->k120_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k120_instit"]) || $this->k120_instit != "")
           $resac = db_query("insert into db_acount values($acount,3113,17626,'".AddSlashes(pg_result($resaco,$conresaco,'k120_instit'))."','$this->k120_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k120_obs"]) || $this->k120_obs != "")
           $resac = db_query("insert into db_acount values($acount,3113,17627,'".AddSlashes(pg_result($resaco,$conresaco,'k120_obs'))."','$this->k120_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k120_data"]) || $this->k120_data != "")
           $resac = db_query("insert into db_acount values($acount,3113,17628,'".AddSlashes(pg_result($resaco,$conresaco,'k120_data'))."','$this->k120_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k120_hora"]) || $this->k120_hora != "")
           $resac = db_query("insert into db_acount values($acount,3113,17629,'".AddSlashes(pg_result($resaco,$conresaco,'k120_hora'))."','$this->k120_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anulação de Prescrição nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k120_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anulação de Prescrição nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k120_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k120_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k120_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k120_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17624,'$k120_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3113,17624,'','".AddSlashes(pg_result($resaco,$iresaco,'k120_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3113,17625,'','".AddSlashes(pg_result($resaco,$iresaco,'k120_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3113,17626,'','".AddSlashes(pg_result($resaco,$iresaco,'k120_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3113,17627,'','".AddSlashes(pg_result($resaco,$iresaco,'k120_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3113,17628,'','".AddSlashes(pg_result($resaco,$iresaco,'k120_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3113,17629,'','".AddSlashes(pg_result($resaco,$iresaco,'k120_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from prescricaoanula
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k120_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k120_sequencial = $k120_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anulação de Prescrição nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k120_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anulação de Prescrição nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k120_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k120_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:prescricaoanula";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k120_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prescricaoanula ";
     $sql .= "      inner join db_config  on  db_config.codigo = prescricaoanula.k120_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prescricaoanula.k120_id_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($k120_sequencial!=null ){
         $sql2 .= " where prescricaoanula.k120_sequencial = $k120_sequencial "; 
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
   function sql_query_file ( $k120_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prescricaoanula ";
     $sql2 = "";
     if($dbwhere==""){
       if($k120_sequencial!=null ){
         $sql2 .= " where prescricaoanula.k120_sequencial = $k120_sequencial "; 
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
<?
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

//MODULO: caixa
//CLASSE DA ENTIDADE recibopagaboleto
class cl_recibopagaboleto { 
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
   var $k138_sequencial = 0; 
   var $k138_numnov = 0; 
   var $k138_data_dia = null; 
   var $k138_data_mes = null; 
   var $k138_data_ano = null; 
   var $k138_data = null; 
   var $k138_hora = null; 
   var $k138_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k138_sequencial = int4 = Sequencial 
                 k138_numnov = int4 = Numpre novo 
                 k138_data = date = Data emissao 
                 k138_hora = char(8) = Hora 
                 k138_usuario = int4 = Usuario 
                 ";
   //funcao construtor da classe 
   function cl_recibopagaboleto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("recibopagaboleto"); 
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
       $this->k138_sequencial = ($this->k138_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k138_sequencial"]:$this->k138_sequencial);
       $this->k138_numnov = ($this->k138_numnov == ""?@$GLOBALS["HTTP_POST_VARS"]["k138_numnov"]:$this->k138_numnov);
       if($this->k138_data == ""){
         $this->k138_data_dia = ($this->k138_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k138_data_dia"]:$this->k138_data_dia);
         $this->k138_data_mes = ($this->k138_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k138_data_mes"]:$this->k138_data_mes);
         $this->k138_data_ano = ($this->k138_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k138_data_ano"]:$this->k138_data_ano);
         if($this->k138_data_dia != ""){
            $this->k138_data = $this->k138_data_ano."-".$this->k138_data_mes."-".$this->k138_data_dia;
         }
       }
       $this->k138_hora = ($this->k138_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k138_hora"]:$this->k138_hora);
       $this->k138_usuario = ($this->k138_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k138_usuario"]:$this->k138_usuario);
     }else{
       $this->k138_sequencial = ($this->k138_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k138_sequencial"]:$this->k138_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k138_sequencial){ 
      $this->atualizacampos();
     if($this->k138_numnov == null ){ 
       $this->erro_sql = " Campo Numpre novo nao Informado.";
       $this->erro_campo = "k138_numnov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k138_data == null ){ 
       $this->erro_sql = " Campo Data emissao nao Informado.";
       $this->erro_campo = "k138_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k138_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k138_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k138_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "k138_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k138_sequencial == "" || $k138_sequencial == null ){
       $result = db_query("select nextval('recibopagaboleto_k138_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: recibopagaboleto_k138_sequencial_seq do campo: k138_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k138_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from recibopagaboleto_k138_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k138_sequencial)){
         $this->erro_sql = " Campo k138_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k138_sequencial = $k138_sequencial; 
       }
     }
     if(($this->k138_sequencial == null) || ($this->k138_sequencial == "") ){ 
       $this->erro_sql = " Campo k138_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into recibopagaboleto(
                                       k138_sequencial 
                                      ,k138_numnov 
                                      ,k138_data 
                                      ,k138_hora 
                                      ,k138_usuario 
                       )
                values (
                                $this->k138_sequencial 
                               ,$this->k138_numnov 
                               ,".($this->k138_data == "null" || $this->k138_data == ""?"null":"'".$this->k138_data."'")." 
                               ,'$this->k138_hora' 
                               ,$this->k138_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "recibopagaboleto ($this->k138_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "recibopagaboleto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "recibopagaboleto ($this->k138_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k138_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k138_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18783,'$this->k138_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3331,18783,'','".AddSlashes(pg_result($resaco,0,'k138_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3331,18784,'','".AddSlashes(pg_result($resaco,0,'k138_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3331,18785,'','".AddSlashes(pg_result($resaco,0,'k138_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3331,18786,'','".AddSlashes(pg_result($resaco,0,'k138_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3331,18787,'','".AddSlashes(pg_result($resaco,0,'k138_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k138_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update recibopagaboleto set ";
     $virgula = "";
     if(trim($this->k138_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k138_sequencial"])){ 
       $sql  .= $virgula." k138_sequencial = $this->k138_sequencial ";
       $virgula = ",";
       if(trim($this->k138_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k138_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k138_numnov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k138_numnov"])){ 
       $sql  .= $virgula." k138_numnov = $this->k138_numnov ";
       $virgula = ",";
       if(trim($this->k138_numnov) == null ){ 
         $this->erro_sql = " Campo Numpre novo nao Informado.";
         $this->erro_campo = "k138_numnov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k138_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k138_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k138_data_dia"] !="") ){ 
       $sql  .= $virgula." k138_data = '$this->k138_data' ";
       $virgula = ",";
       if(trim($this->k138_data) == null ){ 
         $this->erro_sql = " Campo Data emissao nao Informado.";
         $this->erro_campo = "k138_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k138_data_dia"])){ 
         $sql  .= $virgula." k138_data = null ";
         $virgula = ",";
         if(trim($this->k138_data) == null ){ 
           $this->erro_sql = " Campo Data emissao nao Informado.";
           $this->erro_campo = "k138_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k138_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k138_hora"])){ 
       $sql  .= $virgula." k138_hora = '$this->k138_hora' ";
       $virgula = ",";
       if(trim($this->k138_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k138_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k138_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k138_usuario"])){ 
       $sql  .= $virgula." k138_usuario = $this->k138_usuario ";
       $virgula = ",";
       if(trim($this->k138_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "k138_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k138_sequencial!=null){
       $sql .= " k138_sequencial = $this->k138_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k138_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18783,'$this->k138_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k138_sequencial"]) || $this->k138_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3331,18783,'".AddSlashes(pg_result($resaco,$conresaco,'k138_sequencial'))."','$this->k138_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k138_numnov"]) || $this->k138_numnov != "")
           $resac = db_query("insert into db_acount values($acount,3331,18784,'".AddSlashes(pg_result($resaco,$conresaco,'k138_numnov'))."','$this->k138_numnov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k138_data"]) || $this->k138_data != "")
           $resac = db_query("insert into db_acount values($acount,3331,18785,'".AddSlashes(pg_result($resaco,$conresaco,'k138_data'))."','$this->k138_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k138_hora"]) || $this->k138_hora != "")
           $resac = db_query("insert into db_acount values($acount,3331,18786,'".AddSlashes(pg_result($resaco,$conresaco,'k138_hora'))."','$this->k138_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k138_usuario"]) || $this->k138_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3331,18787,'".AddSlashes(pg_result($resaco,$conresaco,'k138_usuario'))."','$this->k138_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "recibopagaboleto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k138_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "recibopagaboleto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k138_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k138_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18783,'$k138_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3331,18783,'','".AddSlashes(pg_result($resaco,$iresaco,'k138_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3331,18784,'','".AddSlashes(pg_result($resaco,$iresaco,'k138_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3331,18785,'','".AddSlashes(pg_result($resaco,$iresaco,'k138_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3331,18786,'','".AddSlashes(pg_result($resaco,$iresaco,'k138_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3331,18787,'','".AddSlashes(pg_result($resaco,$iresaco,'k138_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from recibopagaboleto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k138_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k138_sequencial = $k138_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "recibopagaboleto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k138_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "recibopagaboleto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k138_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:recibopagaboleto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k138_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from recibopagaboleto ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = recibopagaboleto.k138_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k138_sequencial!=null ){
         $sql2 .= " where recibopagaboleto.k138_sequencial = $k138_sequencial "; 
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
   function sql_query_file ( $k138_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from recibopagaboleto ";
     $sql2 = "";
     if($dbwhere==""){
       if($k138_sequencial!=null ){
         $sql2 .= " where recibopagaboleto.k138_sequencial = $k138_sequencial "; 
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

   /**
    * Busca informacoes de dados de recibo 
    * 
    * @param integer $iNumpreBoleto 
    * @access public
    * @return string
    */
   public function sql_queryDadosRecibo($iNumpreBoleto) {

     $sSqlBoleto  = " select                                                                          \n";
     $sSqlBoleto .= " recibopaga.k00_numnov           as numpre_boleto,                               \n";
     $sSqlBoleto .= " recibopaga.k00_numpre           as numpre_debito,                               \n";
     $sSqlBoleto .= " recibopaga.k00_numpar           as numpar_debito,                               \n";
     $sSqlBoleto .= " recibopaga.k00_valor            as valor,                                       \n";
     $sSqlBoleto .= " arrecad.k00_valor               as valor_historico,                             \n";
     $sSqlBoleto .= " arrecad.k00_tipo                as tipo_debito,                                 \n";
     $sSqlBoleto .= " tabrec.k02_tabrectipo           as tipo_receita,                                \n";
     $sSqlBoleto .= " recibocodbar.k00_codbar         as codigo_barras,                               \n";
     $sSqlBoleto .= " recibopaga.k00_dtpaga           as data_pagamento,                              \n";
     $sSqlBoleto .= " recibocodbar.k00_linhadigitavel as linha_digitavel,                             \n";
     $sSqlBoleto .= " ( select k00_inscr                                                              \n";
     $sSqlBoleto .= "     from arreinscr                                                              \n";
     $sSqlBoleto .= "    where arreinscr.k00_numpre = recibopaga.k00_numpre                           \n";   
     $sSqlBoleto .= "    limit 1 )                    as inscricao,                                   \n";
     $sSqlBoleto .= " ( select k00_matric                                                             \n";
     $sSqlBoleto .= "     from arrematric                                                             \n";
     $sSqlBoleto .= "    where arrematric.k00_numpre = recibopaga.k00_numpre                          \n";    
     $sSqlBoleto .= "    limit 1 )                    as matricula,                                   \n";
     $sSqlBoleto .= " (select k00_numcgm                                                              \n";
     $sSqlBoleto .= "    from arrenumcgm                                                              \n";
     $sSqlBoleto .= "   where arrenumcgm.k00_numpre = recibopaga.k00_numpre                           \n";
     $sSqlBoleto .= "   limit 1  )                    as cgm                                          \n";
     $sSqlBoleto .= " from recibopaga                                                                 \n";
     $sSqlBoleto .= "      inner join arrecad      on arrecad.k00_numpre = recibopaga.k00_numpre      \n";
     $sSqlBoleto .= "                             and arrecad.k00_numpar = recibopaga.k00_numpar      \n";
     $sSqlBoleto .= "      inner join tabrec       on tabrec.k02_codigo = recibopaga.k00_receit       \n";
     $sSqlBoleto .= "      left  join recibocodbar on recibocodbar.k00_numpre = recibopaga.k00_numnov \n";
     $sSqlBoleto .= " where recibopaga.k00_numnov = {$iNumpreBoleto}                                  \n";

     return $sSqlBoleto;
   }

  /**
   * Busca informacoes do debito
   *
   * @param int $iNumpreDebito
   * @param int $iNumparDebito
   *
   * @return string
   */
   public function sql_queryDadosDebito($iNumpreDebito, $iNumparDebito) {

     $sSqlDebito  = " select distinct                                     \n";
     $sSqlDebito .= " arrecad.k00_numpre              as numpre_debito,   \n";
     $sSqlDebito .= " arrecad.k00_numpar              as numpar_debito,   \n";
     $sSqlDebito .= " ( select k00_inscr                                  \n";
     $sSqlDebito .= "     from arreinscr                                  \n";
     $sSqlDebito .= "    where arreinscr.k00_numpre = arrecad.k00_numpre  \n";   
     $sSqlDebito .= "    limit 1 )                    as inscricao,       \n";
     $sSqlDebito .= " ( select k00_matric                                 \n";
     $sSqlDebito .= "     from arrematric                                 \n";
     $sSqlDebito .= "    where arrematric.k00_numpre = arrecad.k00_numpre \n";    
     $sSqlDebito .= "    limit 1 )                    as matricula,       \n";
     $sSqlDebito .= " (select k00_numcgm                                  \n";
     $sSqlDebito .= "    from arrenumcgm                                  \n";
     $sSqlDebito .= "   where arrenumcgm.k00_numpre = arrecad.k00_numpre  \n";
     $sSqlDebito .= "   limit 1  )                    as cgm              \n";
     $sSqlDebito .= "  from arrecad                                       \n";
     $sSqlDebito .= " where k00_numpre = {$iNumpreDebito}                 \n";
     $sSqlDebito .= "   and k00_numpar = {$iNumparDebito}                 \n";

     return $sSqlDebito;
   }
}
?>
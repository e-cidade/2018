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

//MODULO: caixa
//CLASSE DA ENTIDADE arrejust
class cl_arrejust { 
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
   var $k27_sequencia = 0; 
   var $k27_usuario = 0; 
   var $k27_obs = null; 
   var $k27_dias = 0; 
   var $k27_hora = null; 
   var $k27_data_dia = null; 
   var $k27_data_mes = null; 
   var $k27_data_ano = null; 
   var $k27_data = null; 
   var $k27_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k27_sequencia = int4 = Código 
                 k27_usuario = int4 = Cod. Usuário 
                 k27_obs = text = Observação 
                 k27_dias = int4 = Dias 
                 k27_hora = char(5) = Hora 
                 k27_data = date = Data 
                 k27_instit = int4 = Cód. Instituição 
                 ";
   //funcao construtor da classe 
   function cl_arrejust() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arrejust"); 
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
       $this->k27_sequencia = ($this->k27_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["k27_sequencia"]:$this->k27_sequencia);
       $this->k27_usuario = ($this->k27_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k27_usuario"]:$this->k27_usuario);
       $this->k27_obs = ($this->k27_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k27_obs"]:$this->k27_obs);
       $this->k27_dias = ($this->k27_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["k27_dias"]:$this->k27_dias);
       $this->k27_hora = ($this->k27_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k27_hora"]:$this->k27_hora);
       if($this->k27_data == ""){
         $this->k27_data_dia = ($this->k27_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k27_data_dia"]:$this->k27_data_dia);
         $this->k27_data_mes = ($this->k27_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k27_data_mes"]:$this->k27_data_mes);
         $this->k27_data_ano = ($this->k27_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k27_data_ano"]:$this->k27_data_ano);
         if($this->k27_data_dia != ""){
            $this->k27_data = $this->k27_data_ano."-".$this->k27_data_mes."-".$this->k27_data_dia;
         }
       }
       $this->k27_instit = ($this->k27_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k27_instit"]:$this->k27_instit);
     }else{
       $this->k27_sequencia = ($this->k27_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["k27_sequencia"]:$this->k27_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($k27_sequencia){ 
      $this->atualizacampos();
     if($this->k27_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "k27_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k27_dias == null ){ 
       $this->erro_sql = " Campo Dias nao Informado.";
       $this->erro_campo = "k27_dias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k27_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k27_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k27_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k27_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k27_instit == null ){ 
       $this->erro_sql = " Campo Cód. Instituição nao Informado.";
       $this->erro_campo = "k27_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k27_sequencia == "" || $k27_sequencia == null ){
       $result = db_query("select nextval('arrejust_k27_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arrejust_k27_sequencia_seq do campo: k27_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k27_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from arrejust_k27_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $k27_sequencia)){
         $this->erro_sql = " Campo k27_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k27_sequencia = $k27_sequencia; 
       }
     }
     if(($this->k27_sequencia == null) || ($this->k27_sequencia == "") ){ 
       $this->erro_sql = " Campo k27_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arrejust(
                                       k27_sequencia 
                                      ,k27_usuario 
                                      ,k27_obs 
                                      ,k27_dias 
                                      ,k27_hora 
                                      ,k27_data 
                                      ,k27_instit 
                       )
                values (
                                $this->k27_sequencia 
                               ,$this->k27_usuario 
                               ,'$this->k27_obs' 
                               ,$this->k27_dias 
                               ,'$this->k27_hora' 
                               ,".($this->k27_data == "null" || $this->k27_data == ""?"null":"'".$this->k27_data."'")." 
                               ,$this->k27_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "arrejust ($this->k27_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "arrejust já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "arrejust ($this->k27_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k27_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k27_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8726,'$this->k27_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1487,8726,'','".AddSlashes(pg_result($resaco,0,'k27_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1487,8729,'','".AddSlashes(pg_result($resaco,0,'k27_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1487,8727,'','".AddSlashes(pg_result($resaco,0,'k27_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1487,8728,'','".AddSlashes(pg_result($resaco,0,'k27_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1487,8731,'','".AddSlashes(pg_result($resaco,0,'k27_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1487,8730,'','".AddSlashes(pg_result($resaco,0,'k27_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1487,10679,'','".AddSlashes(pg_result($resaco,0,'k27_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k27_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update arrejust set ";
     $virgula = "";
     if(trim($this->k27_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k27_sequencia"])){ 
       $sql  .= $virgula." k27_sequencia = $this->k27_sequencia ";
       $virgula = ",";
       if(trim($this->k27_sequencia) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k27_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k27_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k27_usuario"])){ 
       $sql  .= $virgula." k27_usuario = $this->k27_usuario ";
       $virgula = ",";
       if(trim($this->k27_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "k27_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k27_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k27_obs"])){ 
       $sql  .= $virgula." k27_obs = '$this->k27_obs' ";
       $virgula = ",";
     }
     if(trim($this->k27_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k27_dias"])){ 
       $sql  .= $virgula." k27_dias = $this->k27_dias ";
       $virgula = ",";
       if(trim($this->k27_dias) == null ){ 
         $this->erro_sql = " Campo Dias nao Informado.";
         $this->erro_campo = "k27_dias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k27_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k27_hora"])){ 
       $sql  .= $virgula." k27_hora = '$this->k27_hora' ";
       $virgula = ",";
       if(trim($this->k27_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k27_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k27_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k27_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k27_data_dia"] !="") ){ 
       $sql  .= $virgula." k27_data = '$this->k27_data' ";
       $virgula = ",";
       if(trim($this->k27_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k27_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k27_data_dia"])){ 
         $sql  .= $virgula." k27_data = null ";
         $virgula = ",";
         if(trim($this->k27_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k27_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k27_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k27_instit"])){ 
       $sql  .= $virgula." k27_instit = $this->k27_instit ";
       $virgula = ",";
       if(trim($this->k27_instit) == null ){ 
         $this->erro_sql = " Campo Cód. Instituição nao Informado.";
         $this->erro_campo = "k27_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k27_sequencia!=null){
       $sql .= " k27_sequencia = $this->k27_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k27_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8726,'$this->k27_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k27_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1487,8726,'".AddSlashes(pg_result($resaco,$conresaco,'k27_sequencia'))."','$this->k27_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k27_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1487,8729,'".AddSlashes(pg_result($resaco,$conresaco,'k27_usuario'))."','$this->k27_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k27_obs"]))
           $resac = db_query("insert into db_acount values($acount,1487,8727,'".AddSlashes(pg_result($resaco,$conresaco,'k27_obs'))."','$this->k27_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k27_dias"]))
           $resac = db_query("insert into db_acount values($acount,1487,8728,'".AddSlashes(pg_result($resaco,$conresaco,'k27_dias'))."','$this->k27_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k27_hora"]))
           $resac = db_query("insert into db_acount values($acount,1487,8731,'".AddSlashes(pg_result($resaco,$conresaco,'k27_hora'))."','$this->k27_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k27_data"]))
           $resac = db_query("insert into db_acount values($acount,1487,8730,'".AddSlashes(pg_result($resaco,$conresaco,'k27_data'))."','$this->k27_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k27_instit"]))
           $resac = db_query("insert into db_acount values($acount,1487,10679,'".AddSlashes(pg_result($resaco,$conresaco,'k27_instit'))."','$this->k27_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arrejust nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k27_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arrejust nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k27_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k27_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k27_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k27_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8726,'$k27_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1487,8726,'','".AddSlashes(pg_result($resaco,$iresaco,'k27_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1487,8729,'','".AddSlashes(pg_result($resaco,$iresaco,'k27_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1487,8727,'','".AddSlashes(pg_result($resaco,$iresaco,'k27_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1487,8728,'','".AddSlashes(pg_result($resaco,$iresaco,'k27_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1487,8731,'','".AddSlashes(pg_result($resaco,$iresaco,'k27_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1487,8730,'','".AddSlashes(pg_result($resaco,$iresaco,'k27_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1487,10679,'','".AddSlashes(pg_result($resaco,$iresaco,'k27_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arrejust
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k27_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k27_sequencia = $k27_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arrejust nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k27_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arrejust nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k27_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k27_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:arrejust";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k27_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrejust ";
     $sql .= "      inner join db_config  on  db_config.codigo = arrejust.k27_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = arrejust.k27_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($k27_sequencia!=null ){
         $sql2 .= " where arrejust.k27_sequencia = $k27_sequencia "; 
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
   function sql_query_file ( $k27_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrejust ";
     $sql2 = "";
     if($dbwhere==""){
       if($k27_sequencia!=null ){
         $sql2 .= " where arrejust.k27_sequencia = $k27_sequencia "; 
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
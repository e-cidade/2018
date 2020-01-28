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
//CLASSE DA ENTIDADE noticonf
class cl_noticonf { 
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
   var $k54_notifica = 0; 
   var $k54_codigo = 0; 
   var $k54_data_dia = null; 
   var $k54_data_mes = null; 
   var $k54_data_ano = null; 
   var $k54_data = null; 
   var $k54_hora = null; 
   var $k54_assinante = null; 
   var $k54_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k54_notifica = int4 = Notificação 
                 k54_codigo = int4 = Situação 
                 k54_data = date = Data Confirmação 
                 k54_hora = varchar(5) = Hora Confirmação 
                 k54_assinante = varchar(60) = Nome Assinatura 
                 k54_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_noticonf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("noticonf"); 
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
       $this->k54_notifica = ($this->k54_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k54_notifica"]:$this->k54_notifica);
       $this->k54_codigo = ($this->k54_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k54_codigo"]:$this->k54_codigo);
       if($this->k54_data == ""){
         $this->k54_data_dia = ($this->k54_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k54_data_dia"]:$this->k54_data_dia);
         $this->k54_data_mes = ($this->k54_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k54_data_mes"]:$this->k54_data_mes);
         $this->k54_data_ano = ($this->k54_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k54_data_ano"]:$this->k54_data_ano);
         if($this->k54_data_dia != ""){
            $this->k54_data = $this->k54_data_ano."-".$this->k54_data_mes."-".$this->k54_data_dia;
         }
       }
       $this->k54_hora = ($this->k54_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k54_hora"]:$this->k54_hora);
       $this->k54_assinante = ($this->k54_assinante == ""?@$GLOBALS["HTTP_POST_VARS"]["k54_assinante"]:$this->k54_assinante);
       $this->k54_obs = ($this->k54_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k54_obs"]:$this->k54_obs);
     }else{
       $this->k54_notifica = ($this->k54_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k54_notifica"]:$this->k54_notifica);
     }
   }
   // funcao para inclusao
   function incluir ($k54_notifica){ 
      $this->atualizacampos();
     if($this->k54_codigo == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "k54_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k54_data == null ){ 
       $this->erro_sql = " Campo Data Confirmação nao Informado.";
       $this->erro_campo = "k54_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k54_hora == null ){ 
       $this->erro_sql = " Campo Hora Confirmação nao Informado.";
       $this->erro_campo = "k54_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k54_notifica = $k54_notifica; 
     if(($this->k54_notifica == null) || ($this->k54_notifica == "") ){ 
       $this->erro_sql = " Campo k54_notifica nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into noticonf(
                                       k54_notifica 
                                      ,k54_codigo 
                                      ,k54_data 
                                      ,k54_hora 
                                      ,k54_assinante 
                                      ,k54_obs 
                       )
                values (
                                $this->k54_notifica 
                               ,$this->k54_codigo 
                               ,".($this->k54_data == "null" || $this->k54_data == ""?"null":"'".$this->k54_data."'")." 
                               ,'$this->k54_hora' 
                               ,'$this->k54_assinante' 
                               ,'$this->k54_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Notificacao Confirmada ($this->k54_notifica) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Notificacao Confirmada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Notificacao Confirmada ($this->k54_notifica) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k54_notifica;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k54_notifica));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4714,'$this->k54_notifica','I')");
       $resac = db_query("insert into db_acount values($acount,624,4714,'','".AddSlashes(pg_result($resaco,0,'k54_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,624,4742,'','".AddSlashes(pg_result($resaco,0,'k54_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,624,4715,'','".AddSlashes(pg_result($resaco,0,'k54_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,624,4716,'','".AddSlashes(pg_result($resaco,0,'k54_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,624,4717,'','".AddSlashes(pg_result($resaco,0,'k54_assinante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,624,4718,'','".AddSlashes(pg_result($resaco,0,'k54_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k54_notifica=null) { 
      $this->atualizacampos();
     $sql = " update noticonf set ";
     $virgula = "";
     if(trim($this->k54_notifica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k54_notifica"])){ 
        if(trim($this->k54_notifica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k54_notifica"])){ 
           $this->k54_notifica = "0" ; 
        } 
       $sql  .= $virgula." k54_notifica = $this->k54_notifica ";
       $virgula = ",";
       if(trim($this->k54_notifica) == null ){ 
         $this->erro_sql = " Campo Notificação nao Informado.";
         $this->erro_campo = "k54_notifica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k54_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k54_codigo"])){ 
        if(trim($this->k54_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k54_codigo"])){ 
           $this->k54_codigo = "0" ; 
        } 
       $sql  .= $virgula." k54_codigo = $this->k54_codigo ";
       $virgula = ",";
       if(trim($this->k54_codigo) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "k54_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k54_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k54_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k54_data_dia"] !="") ){ 
       $sql  .= $virgula." k54_data = '$this->k54_data' ";
       $virgula = ",";
       if(trim($this->k54_data) == null ){ 
         $this->erro_sql = " Campo Data Confirmação nao Informado.";
         $this->erro_campo = "k54_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k54_data_dia"])){ 
         $sql  .= $virgula." k54_data = null ";
         $virgula = ",";
         if(trim($this->k54_data) == null ){ 
           $this->erro_sql = " Campo Data Confirmação nao Informado.";
           $this->erro_campo = "k54_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k54_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k54_hora"])){ 
       $sql  .= $virgula." k54_hora = '$this->k54_hora' ";
       $virgula = ",";
       if(trim($this->k54_hora) == null ){ 
         $this->erro_sql = " Campo Hora Confirmação nao Informado.";
         $this->erro_campo = "k54_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k54_assinante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k54_assinante"])){ 
       $sql  .= $virgula." k54_assinante = '$this->k54_assinante' ";
       $virgula = ",";
     }
     if(trim($this->k54_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k54_obs"])){ 
       $sql  .= $virgula." k54_obs = '$this->k54_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k54_notifica!=null){
       $sql .= " k54_notifica = $this->k54_notifica";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k54_notifica));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4714,'$this->k54_notifica','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k54_notifica"]))
           $resac = db_query("insert into db_acount values($acount,624,4714,'".AddSlashes(pg_result($resaco,$conresaco,'k54_notifica'))."','$this->k54_notifica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k54_codigo"]))
           $resac = db_query("insert into db_acount values($acount,624,4742,'".AddSlashes(pg_result($resaco,$conresaco,'k54_codigo'))."','$this->k54_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k54_data"]))
           $resac = db_query("insert into db_acount values($acount,624,4715,'".AddSlashes(pg_result($resaco,$conresaco,'k54_data'))."','$this->k54_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k54_hora"]))
           $resac = db_query("insert into db_acount values($acount,624,4716,'".AddSlashes(pg_result($resaco,$conresaco,'k54_hora'))."','$this->k54_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k54_assinante"]))
           $resac = db_query("insert into db_acount values($acount,624,4717,'".AddSlashes(pg_result($resaco,$conresaco,'k54_assinante'))."','$this->k54_assinante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k54_obs"]))
           $resac = db_query("insert into db_acount values($acount,624,4718,'".AddSlashes(pg_result($resaco,$conresaco,'k54_obs'))."','$this->k54_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notificacao Confirmada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k54_notifica;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notificacao Confirmada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k54_notifica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k54_notifica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k54_notifica=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k54_notifica));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4714,'$k54_notifica','E')");
         $resac = db_query("insert into db_acount values($acount,624,4714,'','".AddSlashes(pg_result($resaco,$iresaco,'k54_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,624,4742,'','".AddSlashes(pg_result($resaco,$iresaco,'k54_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,624,4715,'','".AddSlashes(pg_result($resaco,$iresaco,'k54_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,624,4716,'','".AddSlashes(pg_result($resaco,$iresaco,'k54_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,624,4717,'','".AddSlashes(pg_result($resaco,$iresaco,'k54_assinante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,624,4718,'','".AddSlashes(pg_result($resaco,$iresaco,'k54_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from noticonf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k54_notifica != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k54_notifica = $k54_notifica ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notificacao Confirmada nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k54_notifica;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notificacao Confirmada nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k54_notifica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k54_notifica;
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
        $this->erro_sql   = "Record Vazio na Tabela:noticonf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k54_notifica=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from noticonf ";
     $sql .= "      inner join notificacao  on  notificacao.k50_notifica = noticonf.k54_notifica";
     $sql .= "      inner join notisitu  on  notisitu.k59_codigo = noticonf.k54_codigo";
     $sql .= "      inner join notitipo  on  notitipo.k51_procede = notificacao.k50_procede";
     $sql2 = "";
     if($dbwhere==""){
       if($k54_notifica!=null ){
         $sql2 .= " where noticonf.k54_notifica = $k54_notifica "; 
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
   function sql_query_file ( $k54_notifica=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from noticonf ";
     $sql2 = "";
     if($dbwhere==""){
       if($k54_notifica!=null ){
         $sql2 .= " where noticonf.k54_notifica = $k54_notifica "; 
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
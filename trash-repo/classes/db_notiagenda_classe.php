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
//CLASSE DA ENTIDADE notiagenda
class cl_notiagenda { 
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
   var $k58_codage = 0; 
   var $k58_notifica = 0; 
   var $k58_data_dia = null; 
   var $k58_data_mes = null; 
   var $k58_data_ano = null; 
   var $k58_data = null; 
   var $k58_hora = null; 
   var $k58_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k58_codage = int4 = Código 
                 k58_notifica = int4 = Notificação 
                 k58_data = date = Data Agenda 
                 k58_hora = char(5) = Hora Agenda 
                 k58_id_usuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_notiagenda() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notiagenda"); 
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
       $this->k58_codage = ($this->k58_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k58_codage"]:$this->k58_codage);
       $this->k58_notifica = ($this->k58_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k58_notifica"]:$this->k58_notifica);
       if($this->k58_data == ""){
         $this->k58_data_dia = ($this->k58_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k58_data_dia"]:$this->k58_data_dia);
         $this->k58_data_mes = ($this->k58_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k58_data_mes"]:$this->k58_data_mes);
         $this->k58_data_ano = ($this->k58_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k58_data_ano"]:$this->k58_data_ano);
         if($this->k58_data_dia != ""){
            $this->k58_data = $this->k58_data_ano."-".$this->k58_data_mes."-".$this->k58_data_dia;
         }
       }
       $this->k58_hora = ($this->k58_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k58_hora"]:$this->k58_hora);
       $this->k58_id_usuario = ($this->k58_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k58_id_usuario"]:$this->k58_id_usuario);
     }else{
       $this->k58_codage = ($this->k58_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k58_codage"]:$this->k58_codage);
     }
   }
   // funcao para inclusao
   function incluir ($k58_codage){ 
      $this->atualizacampos();
     if($this->k58_notifica == null ){ 
       $this->erro_sql = " Campo Notificação nao Informado.";
       $this->erro_campo = "k58_notifica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k58_data == null ){ 
       $this->erro_sql = " Campo Data Agenda nao Informado.";
       $this->erro_campo = "k58_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k58_hora == null ){ 
       $this->erro_sql = " Campo Hora Agenda nao Informado.";
       $this->erro_campo = "k58_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k58_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "k58_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k58_codage == "" || $k58_codage == null ){
       $result = db_query("select nextval('notiagenda_k58_codage_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: notiagenda_k58_codage_seq do campo: k58_codage"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k58_codage = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from notiagenda_k58_codage_seq");
       if(($result != false) && (pg_result($result,0,0) < $k58_codage)){
         $this->erro_sql = " Campo k58_codage maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k58_codage = $k58_codage; 
       }
     }
     if(($this->k58_codage == null) || ($this->k58_codage == "") ){ 
       $this->erro_sql = " Campo k58_codage nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notiagenda(
                                       k58_codage 
                                      ,k58_notifica 
                                      ,k58_data 
                                      ,k58_hora 
                                      ,k58_id_usuario 
                       )
                values (
                                $this->k58_codage 
                               ,$this->k58_notifica 
                               ,".($this->k58_data == "null" || $this->k58_data == ""?"null":"'".$this->k58_data."'")." 
                               ,'$this->k58_hora' 
                               ,$this->k58_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "notificacoes agendadas ($this->k58_codage) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "notificacoes agendadas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "notificacoes agendadas ($this->k58_codage) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k58_codage;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k58_codage));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4730,'$this->k58_codage','I')");
       $resac = db_query("insert into db_acount values($acount,630,4730,'','".AddSlashes(pg_result($resaco,0,'k58_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,630,4729,'','".AddSlashes(pg_result($resaco,0,'k58_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,630,4731,'','".AddSlashes(pg_result($resaco,0,'k58_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,630,4732,'','".AddSlashes(pg_result($resaco,0,'k58_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,630,4733,'','".AddSlashes(pg_result($resaco,0,'k58_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k58_codage=null) { 
      $this->atualizacampos();
     $sql = " update notiagenda set ";
     $virgula = "";
     if(trim($this->k58_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k58_codage"])){ 
        if(trim($this->k58_codage)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k58_codage"])){ 
           $this->k58_codage = "0" ; 
        } 
       $sql  .= $virgula." k58_codage = $this->k58_codage ";
       $virgula = ",";
       if(trim($this->k58_codage) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k58_codage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k58_notifica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k58_notifica"])){ 
        if(trim($this->k58_notifica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k58_notifica"])){ 
           $this->k58_notifica = "0" ; 
        } 
       $sql  .= $virgula." k58_notifica = $this->k58_notifica ";
       $virgula = ",";
       if(trim($this->k58_notifica) == null ){ 
         $this->erro_sql = " Campo Notificação nao Informado.";
         $this->erro_campo = "k58_notifica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k58_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k58_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k58_data_dia"] !="") ){ 
       $sql  .= $virgula." k58_data = '$this->k58_data' ";
       $virgula = ",";
       if(trim($this->k58_data) == null ){ 
         $this->erro_sql = " Campo Data Agenda nao Informado.";
         $this->erro_campo = "k58_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k58_data_dia"])){ 
         $sql  .= $virgula." k58_data = null ";
         $virgula = ",";
         if(trim($this->k58_data) == null ){ 
           $this->erro_sql = " Campo Data Agenda nao Informado.";
           $this->erro_campo = "k58_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k58_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k58_hora"])){ 
       $sql  .= $virgula." k58_hora = '$this->k58_hora' ";
       $virgula = ",";
       if(trim($this->k58_hora) == null ){ 
         $this->erro_sql = " Campo Hora Agenda nao Informado.";
         $this->erro_campo = "k58_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k58_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k58_id_usuario"])){ 
        if(trim($this->k58_id_usuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k58_id_usuario"])){ 
           $this->k58_id_usuario = "0" ; 
        } 
       $sql  .= $virgula." k58_id_usuario = $this->k58_id_usuario ";
       $virgula = ",";
       if(trim($this->k58_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "k58_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k58_codage!=null){
       $sql .= " k58_codage = $this->k58_codage";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k58_codage));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4730,'$this->k58_codage','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k58_codage"]))
           $resac = db_query("insert into db_acount values($acount,630,4730,'".AddSlashes(pg_result($resaco,$conresaco,'k58_codage'))."','$this->k58_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k58_notifica"]))
           $resac = db_query("insert into db_acount values($acount,630,4729,'".AddSlashes(pg_result($resaco,$conresaco,'k58_notifica'))."','$this->k58_notifica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k58_data"]))
           $resac = db_query("insert into db_acount values($acount,630,4731,'".AddSlashes(pg_result($resaco,$conresaco,'k58_data'))."','$this->k58_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k58_hora"]))
           $resac = db_query("insert into db_acount values($acount,630,4732,'".AddSlashes(pg_result($resaco,$conresaco,'k58_hora'))."','$this->k58_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k58_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,630,4733,'".AddSlashes(pg_result($resaco,$conresaco,'k58_id_usuario'))."','$this->k58_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notificacoes agendadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k58_codage;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notificacoes agendadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k58_codage;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k58_codage;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k58_codage=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k58_codage));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4730,'$k58_codage','E')");
         $resac = db_query("insert into db_acount values($acount,630,4730,'','".AddSlashes(pg_result($resaco,$iresaco,'k58_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,630,4729,'','".AddSlashes(pg_result($resaco,$iresaco,'k58_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,630,4731,'','".AddSlashes(pg_result($resaco,$iresaco,'k58_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,630,4732,'','".AddSlashes(pg_result($resaco,$iresaco,'k58_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,630,4733,'','".AddSlashes(pg_result($resaco,$iresaco,'k58_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notiagenda
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k58_codage != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k58_codage = $k58_codage ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notificacoes agendadas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k58_codage;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notificacoes agendadas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k58_codage;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k58_codage;
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
        $this->erro_sql   = "Record Vazio na Tabela:notiagenda";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k58_codage=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notiagenda ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = notiagenda.k58_id_usuario";
     $sql .= "      inner join notificacao  on  notificacao.k50_notifica = notiagenda.k58_notifica";
     $sql .= "      inner join notitipo  on  notitipo.k51_procede = notificacao.k50_procede";
     $sql2 = "";
     if($dbwhere==""){
       if($k58_codage!=null ){
         $sql2 .= " where notiagenda.k58_codage = $k58_codage "; 
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
   function sql_query_file ( $k58_codage=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notiagenda ";
     $sql2 = "";
     if($dbwhere==""){
       if($k58_codage!=null ){
         $sql2 .= " where notiagenda.k58_codage = $k58_codage "; 
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
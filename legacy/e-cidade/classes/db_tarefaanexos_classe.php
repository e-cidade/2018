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
//CLASSE DA ENTIDADE tarefaanexos
class cl_tarefaanexos { 
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
   var $at25_sequencial = 0; 
   var $at25_tarefa = 0; 
   var $at25_anexo = 0; 
   var $at25_nomearq = null; 
   var $at25_data_dia = null; 
   var $at25_data_mes = null; 
   var $at25_data_ano = null; 
   var $at25_data = null; 
   var $at25_hora = null; 
   var $at25_usuario = 0; 
   var $at25_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at25_sequencial = int4 = Sequencial 
                 at25_tarefa = int4 = Codigo da Tarefa 
                 at25_anexo = oid = Anexo 
                 at25_nomearq = varchar(50) = Nome do Arquivo 
                 at25_data = date = Data 
                 at25_hora = char(5) = Hora 
                 at25_usuario = int4 = Cod. Usuário 
                 at25_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_tarefaanexos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefaanexos"); 
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
       $this->at25_sequencial = ($this->at25_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_sequencial"]:$this->at25_sequencial);
       $this->at25_tarefa = ($this->at25_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_tarefa"]:$this->at25_tarefa);
       $this->at25_anexo = ($this->at25_anexo == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_anexo"]:$this->at25_anexo);
       $this->at25_nomearq = ($this->at25_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_nomearq"]:$this->at25_nomearq);
       if($this->at25_data == ""){
         $this->at25_data_dia = ($this->at25_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_data_dia"]:$this->at25_data_dia);
         $this->at25_data_mes = ($this->at25_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_data_mes"]:$this->at25_data_mes);
         $this->at25_data_ano = ($this->at25_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_data_ano"]:$this->at25_data_ano);
         if($this->at25_data_dia != ""){
            $this->at25_data = $this->at25_data_ano."-".$this->at25_data_mes."-".$this->at25_data_dia;
         }
       }
       $this->at25_hora = ($this->at25_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_hora"]:$this->at25_hora);
       $this->at25_usuario = ($this->at25_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_usuario"]:$this->at25_usuario);
       $this->at25_obs = ($this->at25_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_obs"]:$this->at25_obs);
     }else{
       $this->at25_sequencial = ($this->at25_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at25_sequencial"]:$this->at25_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at25_sequencial){ 
      $this->atualizacampos();
     if($this->at25_tarefa == null ){ 
       $this->erro_sql = " Campo Codigo da Tarefa nao Informado.";
       $this->erro_campo = "at25_tarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at25_anexo == null ){ 
       $this->erro_sql = " Campo Anexo nao Informado.";
       $this->erro_campo = "at25_anexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at25_nomearq == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
       $this->erro_campo = "at25_nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at25_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "at25_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at25_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "at25_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at25_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "at25_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at25_sequencial == "" || $at25_sequencial == null ){
       $result = db_query("select nextval('tarefaanexos_at25_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefaanexos_at25_sequencial_seq do campo: at25_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at25_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefaanexos_at25_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at25_sequencial)){
         $this->erro_sql = " Campo at25_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at25_sequencial = $at25_sequencial; 
       }
     }
     if(($this->at25_sequencial == null) || ($this->at25_sequencial == "") ){ 
       $this->erro_sql = " Campo at25_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefaanexos(
                                       at25_sequencial 
                                      ,at25_tarefa 
                                      ,at25_anexo 
                                      ,at25_nomearq 
                                      ,at25_data 
                                      ,at25_hora 
                                      ,at25_usuario 
                                      ,at25_obs 
                       )
                values (
                                $this->at25_sequencial 
                               ,$this->at25_tarefa 
                               ,'$this->at25_anexo' 
                               ,'$this->at25_nomearq' 
                               ,".($this->at25_data == "null" || $this->at25_data == ""?"null":"'".$this->at25_data."'")." 
                               ,'$this->at25_hora' 
                               ,$this->at25_usuario 
                               ,'$this->at25_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Anexos das tarefas ($this->at25_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Anexos das tarefas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Anexos das tarefas ($this->at25_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at25_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at25_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9510,'$this->at25_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1633,9510,'','".AddSlashes(pg_result($resaco,0,'at25_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1633,9509,'','".AddSlashes(pg_result($resaco,0,'at25_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1633,9512,'','".AddSlashes(pg_result($resaco,0,'at25_anexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1633,9513,'','".AddSlashes(pg_result($resaco,0,'at25_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1633,9514,'','".AddSlashes(pg_result($resaco,0,'at25_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1633,9515,'','".AddSlashes(pg_result($resaco,0,'at25_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1633,9516,'','".AddSlashes(pg_result($resaco,0,'at25_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1633,9522,'','".AddSlashes(pg_result($resaco,0,'at25_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at25_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tarefaanexos set ";
     $virgula = "";
     if(trim($this->at25_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at25_sequencial"])){ 
       $sql  .= $virgula." at25_sequencial = $this->at25_sequencial ";
       $virgula = ",";
       if(trim($this->at25_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at25_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at25_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at25_tarefa"])){ 
       $sql  .= $virgula." at25_tarefa = $this->at25_tarefa ";
       $virgula = ",";
       if(trim($this->at25_tarefa) == null ){ 
         $this->erro_sql = " Campo Codigo da Tarefa nao Informado.";
         $this->erro_campo = "at25_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at25_anexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at25_anexo"])){ 
       $sql  .= $virgula." at25_anexo = '$this->at25_anexo' ";
       $virgula = ",";
       if(trim($this->at25_anexo) == null ){ 
         $this->erro_sql = " Campo Anexo nao Informado.";
         $this->erro_campo = "at25_anexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at25_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at25_nomearq"])){ 
       $sql  .= $virgula." at25_nomearq = '$this->at25_nomearq' ";
       $virgula = ",";
       if(trim($this->at25_nomearq) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
         $this->erro_campo = "at25_nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at25_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at25_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at25_data_dia"] !="") ){ 
       $sql  .= $virgula." at25_data = '$this->at25_data' ";
       $virgula = ",";
       if(trim($this->at25_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "at25_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at25_data_dia"])){ 
         $sql  .= $virgula." at25_data = null ";
         $virgula = ",";
         if(trim($this->at25_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "at25_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at25_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at25_hora"])){ 
       $sql  .= $virgula." at25_hora = '$this->at25_hora' ";
       $virgula = ",";
       if(trim($this->at25_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "at25_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at25_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at25_usuario"])){ 
       $sql  .= $virgula." at25_usuario = $this->at25_usuario ";
       $virgula = ",";
       if(trim($this->at25_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "at25_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at25_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at25_obs"])){ 
       $sql  .= $virgula." at25_obs = '$this->at25_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($at25_sequencial!=null){
       $sql .= " at25_sequencial = $this->at25_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at25_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9510,'$this->at25_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at25_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1633,9510,'".AddSlashes(pg_result($resaco,$conresaco,'at25_sequencial'))."','$this->at25_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at25_tarefa"]))
           $resac = db_query("insert into db_acount values($acount,1633,9509,'".AddSlashes(pg_result($resaco,$conresaco,'at25_tarefa'))."','$this->at25_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at25_anexo"]))
           $resac = db_query("insert into db_acount values($acount,1633,9512,'".AddSlashes(pg_result($resaco,$conresaco,'at25_anexo'))."','$this->at25_anexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at25_nomearq"]))
           $resac = db_query("insert into db_acount values($acount,1633,9513,'".AddSlashes(pg_result($resaco,$conresaco,'at25_nomearq'))."','$this->at25_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at25_data"]))
           $resac = db_query("insert into db_acount values($acount,1633,9514,'".AddSlashes(pg_result($resaco,$conresaco,'at25_data'))."','$this->at25_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at25_hora"]))
           $resac = db_query("insert into db_acount values($acount,1633,9515,'".AddSlashes(pg_result($resaco,$conresaco,'at25_hora'))."','$this->at25_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at25_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1633,9516,'".AddSlashes(pg_result($resaco,$conresaco,'at25_usuario'))."','$this->at25_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at25_obs"]))
           $resac = db_query("insert into db_acount values($acount,1633,9522,'".AddSlashes(pg_result($resaco,$conresaco,'at25_obs'))."','$this->at25_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anexos das tarefas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at25_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anexos das tarefas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at25_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at25_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9510,'$at25_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1633,9510,'','".AddSlashes(pg_result($resaco,$iresaco,'at25_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1633,9509,'','".AddSlashes(pg_result($resaco,$iresaco,'at25_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1633,9512,'','".AddSlashes(pg_result($resaco,$iresaco,'at25_anexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1633,9513,'','".AddSlashes(pg_result($resaco,$iresaco,'at25_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1633,9514,'','".AddSlashes(pg_result($resaco,$iresaco,'at25_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1633,9515,'','".AddSlashes(pg_result($resaco,$iresaco,'at25_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1633,9516,'','".AddSlashes(pg_result($resaco,$iresaco,'at25_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1633,9522,'','".AddSlashes(pg_result($resaco,$iresaco,'at25_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefaanexos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at25_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at25_sequencial = $at25_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anexos das tarefas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at25_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anexos das tarefas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at25_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefaanexos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at25_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefaanexos ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tarefaanexos.at25_usuario";
     $sql .= "      inner join tarefa  on  tarefa.at40_sequencial = tarefaanexos.at25_tarefa";
     $sql .= "      inner join db_usuarios as a  on  a.id_usuario = tarefa.at40_responsavel";
     $sql2 = "";
     if($dbwhere==""){
       if($at25_sequencial!=null ){
         $sql2 .= " where tarefaanexos.at25_sequencial = $at25_sequencial "; 
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
   function sql_query_file ( $at25_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefaanexos ";
     $sql2 = "";
     if($dbwhere==""){
       if($at25_sequencial!=null ){
         $sql2 .= " where tarefaanexos.at25_sequencial = $at25_sequencial "; 
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
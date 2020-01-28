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
//CLASSE DA ENTIDADE atendusucliitem
class cl_atendusucliitem { 
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
   var $at81_seq = 0; 
   var $at81_codatendcli = 0; 
   var $at81_descr = null; 
   var $at81_codtipo = 0; 
   var $at81_data_dia = null; 
   var $at81_data_mes = null; 
   var $at81_data_ano = null; 
   var $at81_data = null; 
   var $at81_hora = null; 
   var $at81_prioridade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at81_seq = int4 = Sequencial da tarefa 
                 at81_codatendcli = int4 = Código do atendimento 
                 at81_descr = text = Descrição da tarefa 
                 at81_codtipo = int4 = Tipo de atendimento 
                 at81_data = date = Data de criação 
                 at81_hora = varchar(5) = Hora de criação 
                 at81_prioridade = int4 = Prioridade 
                 ";
   //funcao construtor da classe 
   function cl_atendusucliitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendusucliitem"); 
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
       $this->at81_seq = ($this->at81_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["at81_seq"]:$this->at81_seq);
       $this->at81_codatendcli = ($this->at81_codatendcli == ""?@$GLOBALS["HTTP_POST_VARS"]["at81_codatendcli"]:$this->at81_codatendcli);
       $this->at81_descr = ($this->at81_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["at81_descr"]:$this->at81_descr);
       $this->at81_codtipo = ($this->at81_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["at81_codtipo"]:$this->at81_codtipo);
       if($this->at81_data == ""){
         $this->at81_data_dia = ($this->at81_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at81_data_dia"]:$this->at81_data_dia);
         $this->at81_data_mes = ($this->at81_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at81_data_mes"]:$this->at81_data_mes);
         $this->at81_data_ano = ($this->at81_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at81_data_ano"]:$this->at81_data_ano);
         if($this->at81_data_dia != ""){
            $this->at81_data = $this->at81_data_ano."-".$this->at81_data_mes."-".$this->at81_data_dia;
         }
       }
       $this->at81_hora = ($this->at81_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["at81_hora"]:$this->at81_hora);
       $this->at81_prioridade = ($this->at81_prioridade == ""?@$GLOBALS["HTTP_POST_VARS"]["at81_prioridade"]:$this->at81_prioridade);
     }else{
       $this->at81_seq = ($this->at81_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["at81_seq"]:$this->at81_seq);
     }
   }
   // funcao para inclusao
   function incluir ($at81_seq){ 
      $this->atualizacampos();
     if($this->at81_codatendcli == null ){ 
       $this->erro_sql = " Campo Código do atendimento nao Informado.";
       $this->erro_campo = "at81_codatendcli";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at81_descr == null ){ 
       $this->erro_sql = " Campo Descrição da tarefa nao Informado.";
       $this->erro_campo = "at81_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at81_codtipo == null ){ 
       $this->erro_sql = " Campo Tipo de atendimento nao Informado.";
       $this->erro_campo = "at81_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at81_data == null ){ 
       $this->erro_sql = " Campo Data de criação nao Informado.";
       $this->erro_campo = "at81_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at81_hora == null ){ 
       $this->erro_sql = " Campo Hora de criação nao Informado.";
       $this->erro_campo = "at81_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at81_prioridade == null ){ 
       $this->erro_sql = " Campo Prioridade nao Informado.";
       $this->erro_campo = "at81_prioridade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at81_seq == "" || $at81_seq == null ){
       $result = @pg_query("select nextval('atendusucliitem_at81_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atendusucliitem_at81_seq_seq do campo: at81_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at81_seq = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from atendusucliitem_at81_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $at81_seq)){
         $this->erro_sql = " Campo at81_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at81_seq = $at81_seq; 
       }
     }
     if(($this->at81_seq == null) || ($this->at81_seq == "") ){ 
       $this->erro_sql = " Campo at81_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendusucliitem(
                                       at81_seq 
                                      ,at81_codatendcli 
                                      ,at81_descr 
                                      ,at81_codtipo 
                                      ,at81_data 
                                      ,at81_hora 
                                      ,at81_prioridade 
                       )
                values (
                                $this->at81_seq 
                               ,$this->at81_codatendcli 
                               ,'$this->at81_descr' 
                               ,$this->at81_codtipo 
                               ,".($this->at81_data == "null" || $this->at81_data == ""?"null":"'".$this->at81_data."'")." 
                               ,'$this->at81_hora' 
                               ,$this->at81_prioridade 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tarefas de atendimento de usuários das prefeituras ($this->at81_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tarefas de atendimento de usuários das prefeituras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tarefas de atendimento de usuários das prefeituras ($this->at81_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at81_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at81_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,9194,'$this->at81_seq','I')");
       $resac = pg_query("insert into db_acount values($acount,1573,9194,'','".AddSlashes(pg_result($resaco,0,'at81_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1573,9195,'','".AddSlashes(pg_result($resaco,0,'at81_codatendcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1573,9196,'','".AddSlashes(pg_result($resaco,0,'at81_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1573,9211,'','".AddSlashes(pg_result($resaco,0,'at81_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1573,9197,'','".AddSlashes(pg_result($resaco,0,'at81_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1573,9198,'','".AddSlashes(pg_result($resaco,0,'at81_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1573,9214,'','".AddSlashes(pg_result($resaco,0,'at81_prioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at81_seq=null) { 
      $this->atualizacampos();
     $sql = " update atendusucliitem set ";
     $virgula = "";
     if(trim($this->at81_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at81_seq"])){ 
       $sql  .= $virgula." at81_seq = $this->at81_seq ";
       $virgula = ",";
       if(trim($this->at81_seq) == null ){ 
         $this->erro_sql = " Campo Sequencial da tarefa nao Informado.";
         $this->erro_campo = "at81_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at81_codatendcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at81_codatendcli"])){ 
       $sql  .= $virgula." at81_codatendcli = $this->at81_codatendcli ";
       $virgula = ",";
       if(trim($this->at81_codatendcli) == null ){ 
         $this->erro_sql = " Campo Código do atendimento nao Informado.";
         $this->erro_campo = "at81_codatendcli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at81_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at81_descr"])){ 
       $sql  .= $virgula." at81_descr = '$this->at81_descr' ";
       $virgula = ",";
       if(trim($this->at81_descr) == null ){ 
         $this->erro_sql = " Campo Descrição da tarefa nao Informado.";
         $this->erro_campo = "at81_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at81_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at81_codtipo"])){ 
       $sql  .= $virgula." at81_codtipo = $this->at81_codtipo ";
       $virgula = ",";
       if(trim($this->at81_codtipo) == null ){ 
         $this->erro_sql = " Campo Tipo de atendimento nao Informado.";
         $this->erro_campo = "at81_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at81_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at81_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at81_data_dia"] !="") ){ 
       $sql  .= $virgula." at81_data = '$this->at81_data' ";
       $virgula = ",";
       if(trim($this->at81_data) == null ){ 
         $this->erro_sql = " Campo Data de criação nao Informado.";
         $this->erro_campo = "at81_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at81_data_dia"])){ 
         $sql  .= $virgula." at81_data = null ";
         $virgula = ",";
         if(trim($this->at81_data) == null ){ 
           $this->erro_sql = " Campo Data de criação nao Informado.";
           $this->erro_campo = "at81_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at81_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at81_hora"])){ 
       $sql  .= $virgula." at81_hora = '$this->at81_hora' ";
       $virgula = ",";
       if(trim($this->at81_hora) == null ){ 
         $this->erro_sql = " Campo Hora de criação nao Informado.";
         $this->erro_campo = "at81_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at81_prioridade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at81_prioridade"])){ 
       $sql  .= $virgula." at81_prioridade = $this->at81_prioridade ";
       $virgula = ",";
       if(trim($this->at81_prioridade) == null ){ 
         $this->erro_sql = " Campo Prioridade nao Informado.";
         $this->erro_campo = "at81_prioridade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at81_seq!=null){
       $sql .= " at81_seq = $this->at81_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at81_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9194,'$this->at81_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at81_seq"]))
           $resac = pg_query("insert into db_acount values($acount,1573,9194,'".AddSlashes(pg_result($resaco,$conresaco,'at81_seq'))."','$this->at81_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at81_codatendcli"]))
           $resac = pg_query("insert into db_acount values($acount,1573,9195,'".AddSlashes(pg_result($resaco,$conresaco,'at81_codatendcli'))."','$this->at81_codatendcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at81_descr"]))
           $resac = pg_query("insert into db_acount values($acount,1573,9196,'".AddSlashes(pg_result($resaco,$conresaco,'at81_descr'))."','$this->at81_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at81_codtipo"]))
           $resac = pg_query("insert into db_acount values($acount,1573,9211,'".AddSlashes(pg_result($resaco,$conresaco,'at81_codtipo'))."','$this->at81_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at81_data"]))
           $resac = pg_query("insert into db_acount values($acount,1573,9197,'".AddSlashes(pg_result($resaco,$conresaco,'at81_data'))."','$this->at81_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at81_hora"]))
           $resac = pg_query("insert into db_acount values($acount,1573,9198,'".AddSlashes(pg_result($resaco,$conresaco,'at81_hora'))."','$this->at81_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at81_prioridade"]))
           $resac = pg_query("insert into db_acount values($acount,1573,9214,'".AddSlashes(pg_result($resaco,$conresaco,'at81_prioridade'))."','$this->at81_prioridade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tarefas de atendimento de usuários das prefeituras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at81_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tarefas de atendimento de usuários das prefeituras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at81_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at81_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at81_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at81_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9194,'$at81_seq','E')");
         $resac = pg_query("insert into db_acount values($acount,1573,9194,'','".AddSlashes(pg_result($resaco,$iresaco,'at81_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1573,9195,'','".AddSlashes(pg_result($resaco,$iresaco,'at81_codatendcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1573,9196,'','".AddSlashes(pg_result($resaco,$iresaco,'at81_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1573,9211,'','".AddSlashes(pg_result($resaco,$iresaco,'at81_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1573,9197,'','".AddSlashes(pg_result($resaco,$iresaco,'at81_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1573,9198,'','".AddSlashes(pg_result($resaco,$iresaco,'at81_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1573,9214,'','".AddSlashes(pg_result($resaco,$iresaco,'at81_prioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendusucliitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at81_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at81_seq = $at81_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tarefas de atendimento de usuários das prefeituras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at81_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tarefas de atendimento de usuários das prefeituras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at81_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at81_seq;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:atendusucliitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $at81_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendusucliitem ";
     $sql .= "      inner join tipoatend  on  tipoatend.at04_codtipo = atendusucliitem.at81_codtipo";
     $sql .= "      inner join atendusucli  on  atendusucli.at80_codatendcli = atendusucliitem.at81_codatendcli";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendusucli.at80_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($at81_seq!=null ){
         $sql2 .= " where atendusucliitem.at81_seq = $at81_seq "; 
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
   function sql_query_file ( $at81_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendusucliitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($at81_seq!=null ){
         $sql2 .= " where atendusucliitem.at81_seq = $at81_seq "; 
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
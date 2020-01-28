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
//CLASSE DA ENTIDADE atendtecnicoocupado
class cl_atendtecnicoocupado { 
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
   var $at72_id = 0; 
   var $at72_id_usuario = 0; 
   var $at72_codtipo = 0; 
   var $at72_data_dia = null; 
   var $at72_data_mes = null; 
   var $at72_data_ano = null; 
   var $at72_data = null; 
   var $at72_hora = null; 
   var $at72_codatend = 0; 
   var $at72_tarefa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at72_id = int4 = Código 
                 at72_id_usuario = int4 = Cod. Usuário 
                 at72_codtipo = int4 = Código do tipo de Auseência 
                 at72_data = date = Data 
                 at72_hora = text = Hora 
                 at72_codatend = int4 = Atendimento 
                 at72_tarefa = int4 = Codigo da Tarefa 
                 ";
   //funcao construtor da classe 
   function cl_atendtecnicoocupado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendtecnicoocupado"); 
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
       $this->at72_id = ($this->at72_id == ""?@$GLOBALS["HTTP_POST_VARS"]["at72_id"]:$this->at72_id);
       $this->at72_id_usuario = ($this->at72_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["at72_id_usuario"]:$this->at72_id_usuario);
       $this->at72_codtipo = ($this->at72_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["at72_codtipo"]:$this->at72_codtipo);
       if($this->at72_data == ""){
         $this->at72_data_dia = ($this->at72_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at72_data_dia"]:$this->at72_data_dia);
         $this->at72_data_mes = ($this->at72_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at72_data_mes"]:$this->at72_data_mes);
         $this->at72_data_ano = ($this->at72_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at72_data_ano"]:$this->at72_data_ano);
         if($this->at72_data_dia != ""){
            $this->at72_data = $this->at72_data_ano."-".$this->at72_data_mes."-".$this->at72_data_dia;
         }
       }
       $this->at72_hora = ($this->at72_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["at72_hora"]:$this->at72_hora);
       $this->at72_codatend = ($this->at72_codatend == ""?@$GLOBALS["HTTP_POST_VARS"]["at72_codatend"]:$this->at72_codatend);
       $this->at72_tarefa = ($this->at72_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at72_tarefa"]:$this->at72_tarefa);
     }else{
       $this->at72_id = ($this->at72_id == ""?@$GLOBALS["HTTP_POST_VARS"]["at72_id"]:$this->at72_id);
     }
   }
   // funcao para inclusao
   function incluir ($at72_id){ 
      $this->atualizacampos();
     if($this->at72_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "at72_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at72_codtipo == null ){ 
       $this->erro_sql = " Campo Código do tipo de Auseência nao Informado.";
       $this->erro_campo = "at72_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at72_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "at72_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at72_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "at72_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at72_codatend == null ){ 
       $this->erro_sql = " Campo Atendimento nao Informado.";
       $this->erro_campo = "at72_codatend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at72_tarefa == null ){ 
       $this->erro_sql = " Campo Codigo da Tarefa nao Informado.";
       $this->erro_campo = "at72_tarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at72_id == "" || $at72_id == null ){
       $result = db_query("select nextval('atendtecnicoocupado_at72_id_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atendtecnicoocupado_at72_id_seq do campo: at72_id"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at72_id = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atendtecnicoocupado_at72_id_seq");
       if(($result != false) && (pg_result($result,0,0) < $at72_id)){
         $this->erro_sql = " Campo at72_id maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at72_id = $at72_id; 
       }
     }
     if(($this->at72_id == null) || ($this->at72_id == "") ){ 
       $this->erro_sql = " Campo at72_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendtecnicoocupado(
                                       at72_id 
                                      ,at72_id_usuario 
                                      ,at72_codtipo 
                                      ,at72_data 
                                      ,at72_hora 
                                      ,at72_codatend 
                                      ,at72_tarefa 
                       )
                values (
                                $this->at72_id 
                               ,$this->at72_id_usuario 
                               ,$this->at72_codtipo 
                               ,".($this->at72_data == "null" || $this->at72_data == ""?"null":"'".$this->at72_data."'")." 
                               ,'$this->at72_hora' 
                               ,$this->at72_codatend 
                               ,$this->at72_tarefa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Técnico ocupado ($this->at72_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Técnico ocupado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Técnico ocupado ($this->at72_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at72_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at72_id));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11030,'$this->at72_id','I')");
       $resac = db_query("insert into db_acount values($acount,1901,11030,'','".AddSlashes(pg_result($resaco,0,'at72_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1901,11031,'','".AddSlashes(pg_result($resaco,0,'at72_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1901,11032,'','".AddSlashes(pg_result($resaco,0,'at72_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1901,11033,'','".AddSlashes(pg_result($resaco,0,'at72_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1901,11034,'','".AddSlashes(pg_result($resaco,0,'at72_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1901,11035,'','".AddSlashes(pg_result($resaco,0,'at72_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1901,11036,'','".AddSlashes(pg_result($resaco,0,'at72_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at72_id=null) { 
      $this->atualizacampos();
     $sql = " update atendtecnicoocupado set ";
     $virgula = "";
     if(trim($this->at72_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at72_id"])){ 
       $sql  .= $virgula." at72_id = $this->at72_id ";
       $virgula = ",";
       if(trim($this->at72_id) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "at72_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at72_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at72_id_usuario"])){ 
       $sql  .= $virgula." at72_id_usuario = $this->at72_id_usuario ";
       $virgula = ",";
       if(trim($this->at72_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "at72_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at72_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at72_codtipo"])){ 
       $sql  .= $virgula." at72_codtipo = $this->at72_codtipo ";
       $virgula = ",";
       if(trim($this->at72_codtipo) == null ){ 
         $this->erro_sql = " Campo Código do tipo de Auseência nao Informado.";
         $this->erro_campo = "at72_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at72_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at72_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at72_data_dia"] !="") ){ 
       $sql  .= $virgula." at72_data = '$this->at72_data' ";
       $virgula = ",";
       if(trim($this->at72_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "at72_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at72_data_dia"])){ 
         $sql  .= $virgula." at72_data = null ";
         $virgula = ",";
         if(trim($this->at72_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "at72_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at72_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at72_hora"])){ 
       $sql  .= $virgula." at72_hora = '$this->at72_hora' ";
       $virgula = ",";
       if(trim($this->at72_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "at72_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at72_codatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at72_codatend"])){ 
       $sql  .= $virgula." at72_codatend = $this->at72_codatend ";
       $virgula = ",";
       if(trim($this->at72_codatend) == null ){ 
         $this->erro_sql = " Campo Atendimento nao Informado.";
         $this->erro_campo = "at72_codatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at72_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at72_tarefa"])){ 
       $sql  .= $virgula." at72_tarefa = $this->at72_tarefa ";
       $virgula = ",";
       if(trim($this->at72_tarefa) == null ){ 
         $this->erro_sql = " Campo Codigo da Tarefa nao Informado.";
         $this->erro_campo = "at72_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at72_id!=null){
       $sql .= " at72_id = $this->at72_id";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at72_id));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11030,'$this->at72_id','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at72_id"]))
           $resac = db_query("insert into db_acount values($acount,1901,11030,'".AddSlashes(pg_result($resaco,$conresaco,'at72_id'))."','$this->at72_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at72_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1901,11031,'".AddSlashes(pg_result($resaco,$conresaco,'at72_id_usuario'))."','$this->at72_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at72_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,1901,11032,'".AddSlashes(pg_result($resaco,$conresaco,'at72_codtipo'))."','$this->at72_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at72_data"]))
           $resac = db_query("insert into db_acount values($acount,1901,11033,'".AddSlashes(pg_result($resaco,$conresaco,'at72_data'))."','$this->at72_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at72_hora"]))
           $resac = db_query("insert into db_acount values($acount,1901,11034,'".AddSlashes(pg_result($resaco,$conresaco,'at72_hora'))."','$this->at72_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at72_codatend"]))
           $resac = db_query("insert into db_acount values($acount,1901,11035,'".AddSlashes(pg_result($resaco,$conresaco,'at72_codatend'))."','$this->at72_codatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at72_tarefa"]))
           $resac = db_query("insert into db_acount values($acount,1901,11036,'".AddSlashes(pg_result($resaco,$conresaco,'at72_tarefa'))."','$this->at72_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Técnico ocupado nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at72_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Técnico ocupado nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at72_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at72_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at72_id=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at72_id));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11030,'$at72_id','E')");
         $resac = db_query("insert into db_acount values($acount,1901,11030,'','".AddSlashes(pg_result($resaco,$iresaco,'at72_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1901,11031,'','".AddSlashes(pg_result($resaco,$iresaco,'at72_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1901,11032,'','".AddSlashes(pg_result($resaco,$iresaco,'at72_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1901,11033,'','".AddSlashes(pg_result($resaco,$iresaco,'at72_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1901,11034,'','".AddSlashes(pg_result($resaco,$iresaco,'at72_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1901,11035,'','".AddSlashes(pg_result($resaco,$iresaco,'at72_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1901,11036,'','".AddSlashes(pg_result($resaco,$iresaco,'at72_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendtecnicoocupado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at72_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at72_id = $at72_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Técnico ocupado nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at72_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Técnico ocupado nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at72_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at72_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:atendtecnicoocupado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atendimento_tecnico_registra ($at71_codigo,$clatendtecnicoocupado,$clatendtipoausencia,$atendimento=0,$tarefa=0) {
    global $codigo;
    $livre = true;
    $result_atend = $clatendtecnicoocupado->sql_record($clatendtecnicoocupado->sql_query(null,"at72_id as codigo",null," at72_id_usuario = ".db_getsession("DB_id_usuario")));          
    if($clatendtecnicoocupado->numrows>0){
      db_fieldsmemory($result_atend,0,0);
    
      $clatendtecnicoocupado->excluir($codigo); 
      if( $clatendtecnicoocupado->erro_status == 0){
        echo "erro ".$clatendtecnicoocupado->erro_msg;exit;
      }  

      $result_atend = $clatendtipoausencia->sql_record($clatendtipoausencia->sql_query(null,"at71_codigo",null," at71_codigo = $at71_codigo and  at71_tipo = 'f' "));          
      if( $clatendtipoausencia->numrows > 0 ){
        $livre = false;
      }

    }

    if( $livre==true ){
      $clatendtecnicoocupado->at72_id = 0;
      $clatendtecnicoocupado->at72_id_usuario = db_getsession("DB_id_usuario");
      $clatendtecnicoocupado->at72_codtipo = $at71_codigo;
      $clatendtecnicoocupado->at72_data = date("Y-m-d");
      $clatendtecnicoocupado->at72_hora = date("H:i");
      $clatendtecnicoocupado->at72_codatend = "$atendimento";
      $clatendtecnicoocupado->at72_tarefa = "$tarefa"; 

      $clatendtecnicoocupado->incluir(0); 
      if( $clatendtecnicoocupado->erro_status == 0){
        echo "erro aaa ".$clatendtecnicoocupado->erro_msg;exit;
      }  
    

    }

  }
   function sql_query ( $at72_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendtecnicoocupado ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendtecnicoocupado.at72_id_usuario";
     $sql .= "      inner join atendtipoausencia  on  atendtipoausencia.at71_codigo = atendtecnicoocupado.at72_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($at72_id!=null ){
         $sql2 .= " where atendtecnicoocupado.at72_id = $at72_id "; 
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
   function sql_query_file ( $at72_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendtecnicoocupado ";
     $sql2 = "";
     if($dbwhere==""){
       if($at72_id!=null ){
         $sql2 .= " where atendtecnicoocupado.at72_id = $at72_id "; 
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
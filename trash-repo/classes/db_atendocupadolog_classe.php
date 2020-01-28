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
//CLASSE DA ENTIDADE atendocupadolog
class cl_atendocupadolog { 
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
   var $at73_id = 0; 
   var $at73_id_usuario = 0; 
   var $at73_codtipo = 0; 
   var $at73_data_dia = null; 
   var $at73_data_mes = null; 
   var $at73_data_ano = null; 
   var $at73_data = null; 
   var $at73_hora = null; 
   var $at73_codatend = 0; 
   var $at73_tarefa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at73_id = int4 = Código Ausência 
                 at73_id_usuario = int4 = Usuário 
                 at73_codtipo = int4 = Tipo de Ausência 
                 at73_data = date = Data 
                 at73_hora = char(5) = Hora 
                 at73_codatend = int4 = Atendimento 
                 at73_tarefa = int4 = Tarefa 
                 ";
   //funcao construtor da classe 
   function cl_atendocupadolog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendocupadolog"); 
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
       $this->at73_id = ($this->at73_id == ""?@$GLOBALS["HTTP_POST_VARS"]["at73_id"]:$this->at73_id);
       $this->at73_id_usuario = ($this->at73_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["at73_id_usuario"]:$this->at73_id_usuario);
       $this->at73_codtipo = ($this->at73_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["at73_codtipo"]:$this->at73_codtipo);
       if($this->at73_data == ""){
         $this->at73_data_dia = ($this->at73_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at73_data_dia"]:$this->at73_data_dia);
         $this->at73_data_mes = ($this->at73_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at73_data_mes"]:$this->at73_data_mes);
         $this->at73_data_ano = ($this->at73_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at73_data_ano"]:$this->at73_data_ano);
         if($this->at73_data_dia != ""){
            $this->at73_data = $this->at73_data_ano."-".$this->at73_data_mes."-".$this->at73_data_dia;
         }
       }
       $this->at73_hora = ($this->at73_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["at73_hora"]:$this->at73_hora);
       $this->at73_codatend = ($this->at73_codatend == ""?@$GLOBALS["HTTP_POST_VARS"]["at73_codatend"]:$this->at73_codatend);
       $this->at73_tarefa = ($this->at73_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at73_tarefa"]:$this->at73_tarefa);
     }else{
       $this->at73_id = ($this->at73_id == ""?@$GLOBALS["HTTP_POST_VARS"]["at73_id"]:$this->at73_id);
     }
   }
   // funcao para inclusao
   function incluir ($at73_id){ 
      $this->atualizacampos();
     if($this->at73_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "at73_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at73_codtipo == null ){ 
       $this->erro_sql = " Campo Tipo de Ausência nao Informado.";
       $this->erro_campo = "at73_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at73_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "at73_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at73_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "at73_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at73_codatend == null ){ 
       $this->erro_sql = " Campo Atendimento nao Informado.";
       $this->erro_campo = "at73_codatend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at73_tarefa == null ){ 
       $this->erro_sql = " Campo Tarefa nao Informado.";
       $this->erro_campo = "at73_tarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->at73_id = $at73_id; 
     if(($this->at73_id == null) || ($this->at73_id == "") ){ 
       $this->erro_sql = " Campo at73_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendocupadolog(
                                       at73_id 
                                      ,at73_id_usuario 
                                      ,at73_codtipo 
                                      ,at73_data 
                                      ,at73_hora 
                                      ,at73_codatend 
                                      ,at73_tarefa 
                       )
                values (
                                $this->at73_id 
                               ,$this->at73_id_usuario 
                               ,$this->at73_codtipo 
                               ,".($this->at73_data == "null" || $this->at73_data == ""?"null":"'".$this->at73_data."'")." 
                               ,'$this->at73_hora' 
                               ,$this->at73_codatend 
                               ,$this->at73_tarefa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "atendocupadolog ($this->at73_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "atendocupadolog já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "atendocupadolog ($this->at73_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at73_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at73_id));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11041,'$this->at73_id','I')");
       $resac = db_query("insert into db_acount values($acount,1902,11041,'','".AddSlashes(pg_result($resaco,0,'at73_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1902,11042,'','".AddSlashes(pg_result($resaco,0,'at73_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1902,11038,'','".AddSlashes(pg_result($resaco,0,'at73_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1902,11039,'','".AddSlashes(pg_result($resaco,0,'at73_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1902,11040,'','".AddSlashes(pg_result($resaco,0,'at73_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1902,11037,'','".AddSlashes(pg_result($resaco,0,'at73_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1902,11043,'','".AddSlashes(pg_result($resaco,0,'at73_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at73_id=null) { 
      $this->atualizacampos();
     $sql = " update atendocupadolog set ";
     $virgula = "";
     if(trim($this->at73_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at73_id"])){ 
       $sql  .= $virgula." at73_id = $this->at73_id ";
       $virgula = ",";
       if(trim($this->at73_id) == null ){ 
         $this->erro_sql = " Campo Código Ausência nao Informado.";
         $this->erro_campo = "at73_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at73_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at73_id_usuario"])){ 
       $sql  .= $virgula." at73_id_usuario = $this->at73_id_usuario ";
       $virgula = ",";
       if(trim($this->at73_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "at73_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at73_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at73_codtipo"])){ 
       $sql  .= $virgula." at73_codtipo = $this->at73_codtipo ";
       $virgula = ",";
       if(trim($this->at73_codtipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Ausência nao Informado.";
         $this->erro_campo = "at73_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at73_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at73_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at73_data_dia"] !="") ){ 
       $sql  .= $virgula." at73_data = '$this->at73_data' ";
       $virgula = ",";
       if(trim($this->at73_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "at73_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at73_data_dia"])){ 
         $sql  .= $virgula." at73_data = null ";
         $virgula = ",";
         if(trim($this->at73_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "at73_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at73_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at73_hora"])){ 
       $sql  .= $virgula." at73_hora = '$this->at73_hora' ";
       $virgula = ",";
       if(trim($this->at73_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "at73_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at73_codatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at73_codatend"])){ 
       $sql  .= $virgula." at73_codatend = $this->at73_codatend ";
       $virgula = ",";
       if(trim($this->at73_codatend) == null ){ 
         $this->erro_sql = " Campo Atendimento nao Informado.";
         $this->erro_campo = "at73_codatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at73_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at73_tarefa"])){ 
       $sql  .= $virgula." at73_tarefa = $this->at73_tarefa ";
       $virgula = ",";
       if(trim($this->at73_tarefa) == null ){ 
         $this->erro_sql = " Campo Tarefa nao Informado.";
         $this->erro_campo = "at73_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at73_id!=null){
       $sql .= " at73_id = $this->at73_id";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at73_id));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11041,'$this->at73_id','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at73_id"]))
           $resac = db_query("insert into db_acount values($acount,1902,11041,'".AddSlashes(pg_result($resaco,$conresaco,'at73_id'))."','$this->at73_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at73_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1902,11042,'".AddSlashes(pg_result($resaco,$conresaco,'at73_id_usuario'))."','$this->at73_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at73_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,1902,11038,'".AddSlashes(pg_result($resaco,$conresaco,'at73_codtipo'))."','$this->at73_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at73_data"]))
           $resac = db_query("insert into db_acount values($acount,1902,11039,'".AddSlashes(pg_result($resaco,$conresaco,'at73_data'))."','$this->at73_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at73_hora"]))
           $resac = db_query("insert into db_acount values($acount,1902,11040,'".AddSlashes(pg_result($resaco,$conresaco,'at73_hora'))."','$this->at73_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at73_codatend"]))
           $resac = db_query("insert into db_acount values($acount,1902,11037,'".AddSlashes(pg_result($resaco,$conresaco,'at73_codatend'))."','$this->at73_codatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at73_tarefa"]))
           $resac = db_query("insert into db_acount values($acount,1902,11043,'".AddSlashes(pg_result($resaco,$conresaco,'at73_tarefa'))."','$this->at73_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atendocupadolog nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at73_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atendocupadolog nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at73_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at73_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at73_id=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at73_id));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11041,'$at73_id','E')");
         $resac = db_query("insert into db_acount values($acount,1902,11041,'','".AddSlashes(pg_result($resaco,$iresaco,'at73_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1902,11042,'','".AddSlashes(pg_result($resaco,$iresaco,'at73_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1902,11038,'','".AddSlashes(pg_result($resaco,$iresaco,'at73_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1902,11039,'','".AddSlashes(pg_result($resaco,$iresaco,'at73_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1902,11040,'','".AddSlashes(pg_result($resaco,$iresaco,'at73_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1902,11037,'','".AddSlashes(pg_result($resaco,$iresaco,'at73_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1902,11043,'','".AddSlashes(pg_result($resaco,$iresaco,'at73_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendocupadolog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at73_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at73_id = $at73_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atendocupadolog nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at73_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atendocupadolog nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at73_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at73_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:atendocupadolog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at73_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendocupadolog ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendocupadolog.at73_id_usuario";
     $sql .= "      inner join atendtipoausencia  on  atendtipoausencia.at71_codigo = atendocupadolog.at73_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($at73_id!=null ){
         $sql2 .= " where atendocupadolog.at73_id = $at73_id "; 
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
   function sql_query_file ( $at73_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendocupadolog ";
     $sql2 = "";
     if($dbwhere==""){
       if($at73_id!=null ){
         $sql2 .= " where atendocupadolog.at73_id = $at73_id "; 
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
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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcsuplemretif
class cl_orcsuplemretif { 
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
   var $o48_seq = 0; 
   var $o48_projeto = 0; 
   var $o48_retificado = 0; 
   var $o48_texto = null; 
   var $o48_id_usuario = 0; 
   var $o48_data_dia = null; 
   var $o48_data_mes = null; 
   var $o48_data_ano = null; 
   var $o48_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o48_seq = int8 = sequencial 
                 o48_projeto = int8 = codigo do projeto retificador 
                 o48_retificado = int8 = projeto retificado 
                 o48_texto = text = texto informativo 
                 o48_id_usuario = int8 = Usuario 
                 o48_data = date = Data do Processamento 
                 ";
   //funcao construtor da classe 
   function cl_orcsuplemretif() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcsuplemretif"); 
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
       $this->o48_seq = ($this->o48_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_seq"]:$this->o48_seq);
       $this->o48_projeto = ($this->o48_projeto == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_projeto"]:$this->o48_projeto);
       $this->o48_retificado = ($this->o48_retificado == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_retificado"]:$this->o48_retificado);
       $this->o48_texto = ($this->o48_texto == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_texto"]:$this->o48_texto);
       $this->o48_id_usuario = ($this->o48_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_id_usuario"]:$this->o48_id_usuario);
       if($this->o48_data == ""){
         $this->o48_data_dia = ($this->o48_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_data_dia"]:$this->o48_data_dia);
         $this->o48_data_mes = ($this->o48_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_data_mes"]:$this->o48_data_mes);
         $this->o48_data_ano = ($this->o48_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_data_ano"]:$this->o48_data_ano);
         if($this->o48_data_dia != ""){
            $this->o48_data = $this->o48_data_ano."-".$this->o48_data_mes."-".$this->o48_data_dia;
         }
       }
     }else{
       $this->o48_seq = ($this->o48_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_seq"]:$this->o48_seq);
     }
   }
   // funcao para inclusao
   function incluir ($o48_seq){ 
      $this->atualizacampos();
     if($this->o48_projeto == null ){ 
       $this->erro_sql = " Campo codigo do projeto retificador nao Informado.";
       $this->erro_campo = "o48_projeto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o48_retificado == null ){ 
       $this->erro_sql = " Campo projeto retificado nao Informado.";
       $this->erro_campo = "o48_retificado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o48_texto == null ){ 
       $this->erro_sql = " Campo texto informativo nao Informado.";
       $this->erro_campo = "o48_texto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o48_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "o48_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o48_data == null ){ 
       $this->erro_sql = " Campo Data do Processamento nao Informado.";
       $this->erro_campo = "o48_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o48_seq == "" || $o48_seq == null ){
       $result = db_query("select nextval('orcsuplemretif_o48_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcsuplemretif_o48_seq_seq do campo: o48_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o48_seq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcsuplemretif_o48_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $o48_seq)){
         $this->erro_sql = " Campo o48_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o48_seq = $o48_seq; 
       }
     }
     if(($this->o48_seq == null) || ($this->o48_seq == "") ){ 
       $this->erro_sql = " Campo o48_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcsuplemretif(
                                       o48_seq 
                                      ,o48_projeto 
                                      ,o48_retificado 
                                      ,o48_texto 
                                      ,o48_id_usuario 
                                      ,o48_data 
                       )
                values (
                                $this->o48_seq 
                               ,$this->o48_projeto 
                               ,$this->o48_retificado 
                               ,'$this->o48_texto' 
                               ,$this->o48_id_usuario 
                               ,".($this->o48_data == "null" || $this->o48_data == ""?"null":"'".$this->o48_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->o48_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->o48_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o48_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o48_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7875,'$this->o48_seq','I')");
       $resac = db_query("insert into db_acount values($acount,1319,7875,'','".AddSlashes(pg_result($resaco,0,'o48_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1319,7876,'','".AddSlashes(pg_result($resaco,0,'o48_projeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1319,7877,'','".AddSlashes(pg_result($resaco,0,'o48_retificado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1319,7880,'','".AddSlashes(pg_result($resaco,0,'o48_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1319,7878,'','".AddSlashes(pg_result($resaco,0,'o48_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1319,7879,'','".AddSlashes(pg_result($resaco,0,'o48_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o48_seq=null) { 
      $this->atualizacampos();
     $sql = " update orcsuplemretif set ";
     $virgula = "";
     if(trim($this->o48_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_seq"])){ 
       $sql  .= $virgula." o48_seq = $this->o48_seq ";
       $virgula = ",";
       if(trim($this->o48_seq) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "o48_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_projeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_projeto"])){ 
       $sql  .= $virgula." o48_projeto = $this->o48_projeto ";
       $virgula = ",";
       if(trim($this->o48_projeto) == null ){ 
         $this->erro_sql = " Campo codigo do projeto retificador nao Informado.";
         $this->erro_campo = "o48_projeto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_retificado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_retificado"])){ 
       $sql  .= $virgula." o48_retificado = $this->o48_retificado ";
       $virgula = ",";
       if(trim($this->o48_retificado) == null ){ 
         $this->erro_sql = " Campo projeto retificado nao Informado.";
         $this->erro_campo = "o48_retificado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_texto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_texto"])){ 
       $sql  .= $virgula." o48_texto = '$this->o48_texto' ";
       $virgula = ",";
       if(trim($this->o48_texto) == null ){ 
         $this->erro_sql = " Campo texto informativo nao Informado.";
         $this->erro_campo = "o48_texto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_id_usuario"])){ 
       $sql  .= $virgula." o48_id_usuario = $this->o48_id_usuario ";
       $virgula = ",";
       if(trim($this->o48_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "o48_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o48_data_dia"] !="") ){ 
       $sql  .= $virgula." o48_data = '$this->o48_data' ";
       $virgula = ",";
       if(trim($this->o48_data) == null ){ 
         $this->erro_sql = " Campo Data do Processamento nao Informado.";
         $this->erro_campo = "o48_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o48_data_dia"])){ 
         $sql  .= $virgula." o48_data = null ";
         $virgula = ",";
         if(trim($this->o48_data) == null ){ 
           $this->erro_sql = " Campo Data do Processamento nao Informado.";
           $this->erro_campo = "o48_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($o48_seq!=null){
       $sql .= " o48_seq = $this->o48_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o48_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7875,'$this->o48_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_seq"]))
           $resac = db_query("insert into db_acount values($acount,1319,7875,'".AddSlashes(pg_result($resaco,$conresaco,'o48_seq'))."','$this->o48_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_projeto"]))
           $resac = db_query("insert into db_acount values($acount,1319,7876,'".AddSlashes(pg_result($resaco,$conresaco,'o48_projeto'))."','$this->o48_projeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_retificado"]))
           $resac = db_query("insert into db_acount values($acount,1319,7877,'".AddSlashes(pg_result($resaco,$conresaco,'o48_retificado'))."','$this->o48_retificado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_texto"]))
           $resac = db_query("insert into db_acount values($acount,1319,7880,'".AddSlashes(pg_result($resaco,$conresaco,'o48_texto'))."','$this->o48_texto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1319,7878,'".AddSlashes(pg_result($resaco,$conresaco,'o48_id_usuario'))."','$this->o48_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_data"]))
           $resac = db_query("insert into db_acount values($acount,1319,7879,'".AddSlashes(pg_result($resaco,$conresaco,'o48_data'))."','$this->o48_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o48_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o48_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o48_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o48_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o48_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7875,'$o48_seq','E')");
         $resac = db_query("insert into db_acount values($acount,1319,7875,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1319,7876,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_projeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1319,7877,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_retificado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1319,7880,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1319,7878,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1319,7879,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcsuplemretif
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o48_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o48_seq = $o48_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o48_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o48_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o48_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcsuplemretif";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o48_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplemretif ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = orcsuplemretif.o48_id_usuario";
     $sql .= "      inner join orcprojeto  on  orcprojeto.o39_codproj = orcsuplemretif.o48_projeto and  orcprojeto.o39_codproj = orcsuplemretif.o48_retificado";
     $sql .= "      inner join orclei  on  orclei.o45_codlei = orcprojeto.o39_codlei";
     $sql .= "      inner join orclei  as a on   a.o45_codlei = orcprojeto.o39_codlei";
     $sql2 = "";
     if($dbwhere==""){
       if($o48_seq!=null ){
         $sql2 .= " where orcsuplemretif.o48_seq = $o48_seq "; 
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
   function sql_query_file ( $o48_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplemretif ";
     $sql2 = "";
     if($dbwhere==""){
       if($o48_seq!=null ){
         $sql2 .= " where orcsuplemretif.o48_seq = $o48_seq "; 
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
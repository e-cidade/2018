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

//MODULO: juridico
//CLASSE DA ENTIDADE inicialcodforo
class cl_inicialcodforo { 
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
   var $v55_inicial = 0; 
   var $v55_codforo = null; 
   var $v55_data_dia = null; 
   var $v55_data_mes = null; 
   var $v55_data_ano = null; 
   var $v55_data = null; 
   var $v55_id_login = 0; 
   var $v55_codvara = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v55_inicial = int4 = Inicial Numero 
                 v55_codforo = varchar(30) = Codigo do Processo 
                 v55_data = date = Data 
                 v55_id_login = int4 = Usuário 
                 v55_codvara = int4 = Vara 
                 ";
   //funcao construtor da classe 
   function cl_inicialcodforo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inicialcodforo"); 
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
       $this->v55_inicial = ($this->v55_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v55_inicial"]:$this->v55_inicial);
       $this->v55_codforo = ($this->v55_codforo == ""?@$GLOBALS["HTTP_POST_VARS"]["v55_codforo"]:$this->v55_codforo);
       if($this->v55_data == ""){
         $this->v55_data_dia = ($this->v55_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v55_data_dia"]:$this->v55_data_dia);
         $this->v55_data_mes = ($this->v55_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v55_data_mes"]:$this->v55_data_mes);
         $this->v55_data_ano = ($this->v55_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v55_data_ano"]:$this->v55_data_ano);
         if($this->v55_data_dia != ""){
            $this->v55_data = $this->v55_data_ano."-".$this->v55_data_mes."-".$this->v55_data_dia;
         }
       }
       $this->v55_id_login = ($this->v55_id_login == ""?@$GLOBALS["HTTP_POST_VARS"]["v55_id_login"]:$this->v55_id_login);
       $this->v55_codvara = ($this->v55_codvara == ""?@$GLOBALS["HTTP_POST_VARS"]["v55_codvara"]:$this->v55_codvara);
     }else{
       $this->v55_inicial = ($this->v55_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v55_inicial"]:$this->v55_inicial);
     }
   }
   // funcao para inclusao
   function incluir ($v55_inicial){ 
      $this->atualizacampos();
     if($this->v55_codforo == null ){ 
       $this->erro_sql = " Campo Codigo do Processo nao Informado.";
       $this->erro_campo = "v55_codforo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v55_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "v55_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v55_id_login == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "v55_id_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v55_codvara == null ){ 
       $this->erro_sql = " Campo Vara nao Informado.";
       $this->erro_campo = "v55_codvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->v55_inicial = $v55_inicial; 
     if(($this->v55_inicial == null) || ($this->v55_inicial == "") ){ 
       $this->erro_sql = " Campo v55_inicial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inicialcodforo(
                                       v55_inicial 
                                      ,v55_codforo 
                                      ,v55_data 
                                      ,v55_id_login 
                                      ,v55_codvara 
                       )
                values (
                                $this->v55_inicial 
                               ,'$this->v55_codforo' 
                               ,".($this->v55_data == "null" || $this->v55_data == ""?"null":"'".$this->v55_data."'")." 
                               ,$this->v55_id_login 
                               ,$this->v55_codvara 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Inicial Codigo Foro ($this->v55_inicial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Inicial Codigo Foro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Inicial Codigo Foro ($this->v55_inicial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v55_inicial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v55_inicial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2568,'$this->v55_inicial','I')");
       $resac = db_query("insert into db_acount values($acount,112,2568,'','".AddSlashes(pg_result($resaco,0,'v55_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,112,576,'','".AddSlashes(pg_result($resaco,0,'v55_codforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,112,2569,'','".AddSlashes(pg_result($resaco,0,'v55_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,112,2570,'','".AddSlashes(pg_result($resaco,0,'v55_id_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,112,2564,'','".AddSlashes(pg_result($resaco,0,'v55_codvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v55_inicial=null) { 
      $this->atualizacampos();
     $sql = " update inicialcodforo set ";
     $virgula = "";
     if(trim($this->v55_inicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v55_inicial"])){ 
       $sql  .= $virgula." v55_inicial = $this->v55_inicial ";
       $virgula = ",";
       if(trim($this->v55_inicial) == null ){ 
         $this->erro_sql = " Campo Inicial Numero nao Informado.";
         $this->erro_campo = "v55_inicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v55_codforo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v55_codforo"])){ 
       $sql  .= $virgula." v55_codforo = '$this->v55_codforo' ";
       $virgula = ",";
       if(trim($this->v55_codforo) == null ){ 
         $this->erro_sql = " Campo Codigo do Processo nao Informado.";
         $this->erro_campo = "v55_codforo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v55_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v55_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v55_data_dia"] !="") ){ 
       $sql  .= $virgula." v55_data = '$this->v55_data' ";
       $virgula = ",";
       if(trim($this->v55_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "v55_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v55_data_dia"])){ 
         $sql  .= $virgula." v55_data = null ";
         $virgula = ",";
         if(trim($this->v55_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "v55_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v55_id_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v55_id_login"])){ 
       $sql  .= $virgula." v55_id_login = $this->v55_id_login ";
       $virgula = ",";
       if(trim($this->v55_id_login) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "v55_id_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v55_codvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v55_codvara"])){ 
       $sql  .= $virgula." v55_codvara = $this->v55_codvara ";
       $virgula = ",";
       if(trim($this->v55_codvara) == null ){ 
         $this->erro_sql = " Campo Vara nao Informado.";
         $this->erro_campo = "v55_codvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v55_inicial!=null){
       $sql .= " v55_inicial = $this->v55_inicial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v55_inicial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2568,'$this->v55_inicial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v55_inicial"]))
           $resac = db_query("insert into db_acount values($acount,112,2568,'".AddSlashes(pg_result($resaco,$conresaco,'v55_inicial'))."','$this->v55_inicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v55_codforo"]))
           $resac = db_query("insert into db_acount values($acount,112,576,'".AddSlashes(pg_result($resaco,$conresaco,'v55_codforo'))."','$this->v55_codforo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v55_data"]))
           $resac = db_query("insert into db_acount values($acount,112,2569,'".AddSlashes(pg_result($resaco,$conresaco,'v55_data'))."','$this->v55_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v55_id_login"]))
           $resac = db_query("insert into db_acount values($acount,112,2570,'".AddSlashes(pg_result($resaco,$conresaco,'v55_id_login'))."','$this->v55_id_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v55_codvara"]))
           $resac = db_query("insert into db_acount values($acount,112,2564,'".AddSlashes(pg_result($resaco,$conresaco,'v55_codvara'))."','$this->v55_codvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inicial Codigo Foro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v55_inicial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inicial Codigo Foro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v55_inicial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v55_inicial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v55_inicial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v55_inicial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2568,'$v55_inicial','E')");
         $resac = db_query("insert into db_acount values($acount,112,2568,'','".AddSlashes(pg_result($resaco,$iresaco,'v55_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,112,576,'','".AddSlashes(pg_result($resaco,$iresaco,'v55_codforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,112,2569,'','".AddSlashes(pg_result($resaco,$iresaco,'v55_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,112,2570,'','".AddSlashes(pg_result($resaco,$iresaco,'v55_id_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,112,2564,'','".AddSlashes(pg_result($resaco,$iresaco,'v55_codvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inicialcodforo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v55_inicial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v55_inicial = $v55_inicial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inicial Codigo Foro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v55_inicial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inicial Codigo Foro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v55_inicial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v55_inicial;
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
        $this->erro_sql   = "Record Vazio na Tabela:inicialcodforo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $v55_inicial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicialcodforo ";
     $sql .= "      inner join inicial  on  inicial.v50_inicial = inicialcodforo.v55_inicial";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inicialcodforo.v55_id_login";
     $sql .= "      inner join vara  on  vara.v53_codvara = inicialcodforo.v55_codvara";
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql2 = "";
     if($dbwhere==""){
       if($v55_inicial!=null ){
         $sql2 .= " where inicialcodforo.v55_inicial = $v55_inicial "; 
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
   function sql_query_file ( $v55_inicial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inicialcodforo ";
     $sql2 = "";
     if($dbwhere==""){
       if($v55_inicial!=null ){
         $sql2 .= " where inicialcodforo.v55_inicial = $v55_inicial "; 
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
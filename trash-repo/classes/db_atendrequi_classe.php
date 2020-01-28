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

//MODULO: material
//CLASSE DA ENTIDADE atendrequi
class cl_atendrequi { 
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
   var $m42_codigo = 0; 
   var $m42_data_dia = null; 
   var $m42_data_mes = null; 
   var $m42_data_ano = null; 
   var $m42_data = null; 
   var $m42_hora = null; 
   var $m42_login = 0; 
   var $m42_depto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m42_codigo = int8 = Código Atendimento 
                 m42_data = date = Data 
                 m42_hora = varchar(5) = Hora 
                 m42_login = int4 = Cod. Usuário 
                 m42_depto = int4 = Depart. 
                 ";
   //funcao construtor da classe 
   function cl_atendrequi() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendrequi"); 
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
       $this->m42_codigo = ($this->m42_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m42_codigo"]:$this->m42_codigo);
       if($this->m42_data == ""){
         $this->m42_data_dia = ($this->m42_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m42_data_dia"]:$this->m42_data_dia);
         $this->m42_data_mes = ($this->m42_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m42_data_mes"]:$this->m42_data_mes);
         $this->m42_data_ano = ($this->m42_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m42_data_ano"]:$this->m42_data_ano);
         if($this->m42_data_dia != ""){
            $this->m42_data = $this->m42_data_ano."-".$this->m42_data_mes."-".$this->m42_data_dia;
         }
       }
       $this->m42_hora = ($this->m42_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["m42_hora"]:$this->m42_hora);
       $this->m42_login = ($this->m42_login == ""?@$GLOBALS["HTTP_POST_VARS"]["m42_login"]:$this->m42_login);
       $this->m42_depto = ($this->m42_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["m42_depto"]:$this->m42_depto);
     }else{
       $this->m42_codigo = ($this->m42_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m42_codigo"]:$this->m42_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m42_codigo){ 
      $this->atualizacampos();
     if($this->m42_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "m42_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m42_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "m42_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m42_login == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "m42_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m42_depto == null ){ 
       $this->erro_sql = " Campo Depart. nao Informado.";
       $this->erro_campo = "m42_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m42_codigo == "" || $m42_codigo == null ){
       $result = db_query("select nextval('atendrequi_m42_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atendrequi_m42_codigo_seq do campo: m42_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m42_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atendrequi_m42_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m42_codigo)){
         $this->erro_sql = " Campo m42_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m42_codigo = $m42_codigo; 
       }
     }
     if(($this->m42_codigo == null) || ($this->m42_codigo == "") ){ 
       $this->erro_sql = " Campo m42_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendrequi(
                                       m42_codigo 
                                      ,m42_data 
                                      ,m42_hora 
                                      ,m42_login 
                                      ,m42_depto 
                       )
                values (
                                $this->m42_codigo 
                               ,".($this->m42_data == "null" || $this->m42_data == ""?"null":"'".$this->m42_data."'")." 
                               ,'$this->m42_hora' 
                               ,$this->m42_login 
                               ,$this->m42_depto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "atendrequi ($this->m42_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "atendrequi já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "atendrequi ($this->m42_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m42_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m42_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6876,'$this->m42_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1130,6876,'','".AddSlashes(pg_result($resaco,0,'m42_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1130,6877,'','".AddSlashes(pg_result($resaco,0,'m42_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1130,6878,'','".AddSlashes(pg_result($resaco,0,'m42_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1130,6879,'','".AddSlashes(pg_result($resaco,0,'m42_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1130,6880,'','".AddSlashes(pg_result($resaco,0,'m42_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m42_codigo=null) { 
      $this->atualizacampos();
     $sql = " update atendrequi set ";
     $virgula = "";
     if(trim($this->m42_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m42_codigo"])){ 
       $sql  .= $virgula." m42_codigo = $this->m42_codigo ";
       $virgula = ",";
       if(trim($this->m42_codigo) == null ){ 
         $this->erro_sql = " Campo Código Atendimento nao Informado.";
         $this->erro_campo = "m42_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m42_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m42_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m42_data_dia"] !="") ){ 
       $sql  .= $virgula." m42_data = '$this->m42_data' ";
       $virgula = ",";
       if(trim($this->m42_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "m42_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m42_data_dia"])){ 
         $sql  .= $virgula." m42_data = null ";
         $virgula = ",";
         if(trim($this->m42_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "m42_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m42_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m42_hora"])){ 
       $sql  .= $virgula." m42_hora = '$this->m42_hora' ";
       $virgula = ",";
       if(trim($this->m42_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "m42_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m42_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m42_login"])){ 
       $sql  .= $virgula." m42_login = $this->m42_login ";
       $virgula = ",";
       if(trim($this->m42_login) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "m42_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m42_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m42_depto"])){ 
       $sql  .= $virgula." m42_depto = $this->m42_depto ";
       $virgula = ",";
       if(trim($this->m42_depto) == null ){ 
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "m42_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m42_codigo!=null){
       $sql .= " m42_codigo = $this->m42_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m42_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6876,'$this->m42_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m42_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1130,6876,'".AddSlashes(pg_result($resaco,$conresaco,'m42_codigo'))."','$this->m42_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m42_data"]))
           $resac = db_query("insert into db_acount values($acount,1130,6877,'".AddSlashes(pg_result($resaco,$conresaco,'m42_data'))."','$this->m42_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m42_hora"]))
           $resac = db_query("insert into db_acount values($acount,1130,6878,'".AddSlashes(pg_result($resaco,$conresaco,'m42_hora'))."','$this->m42_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m42_login"]))
           $resac = db_query("insert into db_acount values($acount,1130,6879,'".AddSlashes(pg_result($resaco,$conresaco,'m42_login'))."','$this->m42_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m42_depto"]))
           $resac = db_query("insert into db_acount values($acount,1130,6880,'".AddSlashes(pg_result($resaco,$conresaco,'m42_depto'))."','$this->m42_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atendrequi nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m42_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atendrequi nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m42_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m42_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m42_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m42_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6876,'$m42_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1130,6876,'','".AddSlashes(pg_result($resaco,$iresaco,'m42_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1130,6877,'','".AddSlashes(pg_result($resaco,$iresaco,'m42_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1130,6878,'','".AddSlashes(pg_result($resaco,$iresaco,'m42_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1130,6879,'','".AddSlashes(pg_result($resaco,$iresaco,'m42_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1130,6880,'','".AddSlashes(pg_result($resaco,$iresaco,'m42_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendrequi
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m42_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m42_codigo = $m42_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atendrequi nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m42_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atendrequi nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m42_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m42_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:atendrequi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m42_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendrequi ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendrequi.m42_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = atendrequi.m42_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m42_codigo!=null ){
         $sql2 .= " where atendrequi.m42_codigo = $m42_codigo "; 
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
   function sql_query_file ( $m42_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendrequi ";
     $sql2 = "";
     if($dbwhere==""){
       if($m42_codigo!=null ){
         $sql2 .= " where atendrequi.m42_codigo = $m42_codigo "; 
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
   function sql_query_requi ( $m42_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendrequi ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendrequi.m42_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = atendrequi.m42_depto";
     $sql .= "      inner join atendrequiitem on m43_codatendrequi = m42_codigo";
     $sql .= "      inner join matrequiitem on m43_codmatrequiitem = m41_codigo ";
     $sql .= "      inner join matrequi on m41_codmatrequi = m40_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($m42_codigo!=null ){
         $sql2 .= " where atendrequi.m42_codigo = $m42_codigo "; 
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
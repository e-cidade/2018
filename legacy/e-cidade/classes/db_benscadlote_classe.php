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

//MODULO: patrim
//CLASSE DA ENTIDADE benscadlote
class cl_benscadlote { 
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
   var $t42_codigo = 0; 
   var $t42_descr = null; 
   var $t42_data_dia = null; 
   var $t42_data_mes = null; 
   var $t42_data_ano = null; 
   var $t42_data = null; 
   var $t42_hora = null; 
   var $t42_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t42_codigo = int4 = Código do lote 
                 t42_descr = varchar(40) = Descrição do lote 
                 t42_data = date = Data 
                 t42_hora = char(5) = Hora 
                 t42_usuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_benscadlote() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benscadlote"); 
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
       $this->t42_codigo = ($this->t42_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t42_codigo"]:$this->t42_codigo);
       $this->t42_descr = ($this->t42_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["t42_descr"]:$this->t42_descr);
       if($this->t42_data == ""){
         $this->t42_data_dia = ($this->t42_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t42_data_dia"]:$this->t42_data_dia);
         $this->t42_data_mes = ($this->t42_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t42_data_mes"]:$this->t42_data_mes);
         $this->t42_data_ano = ($this->t42_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t42_data_ano"]:$this->t42_data_ano);
         if($this->t42_data_dia != ""){
            $this->t42_data = $this->t42_data_ano."-".$this->t42_data_mes."-".$this->t42_data_dia;
         }
       }
       $this->t42_hora = ($this->t42_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["t42_hora"]:$this->t42_hora);
       $this->t42_usuario = ($this->t42_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t42_usuario"]:$this->t42_usuario);
     }else{
       $this->t42_codigo = ($this->t42_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t42_codigo"]:$this->t42_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($t42_codigo){ 
      $this->atualizacampos();
     if($this->t42_descr == null ){ 
       $this->erro_sql = " Campo Descrição do lote nao Informado.";
       $this->erro_campo = "t42_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t42_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "t42_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t42_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "t42_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t42_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "t42_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t42_codigo == "" || $t42_codigo == null ){
       $result = db_query("select nextval('benscadlote_t42_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: benscadlote_t42_codigo_seq do campo: t42_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t42_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from benscadlote_t42_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $t42_codigo)){
         $this->erro_sql = " Campo t42_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t42_codigo = $t42_codigo; 
       }
     }
     if(($this->t42_codigo == null) || ($this->t42_codigo == "") ){ 
       $this->erro_sql = " Campo t42_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benscadlote(
                                       t42_codigo 
                                      ,t42_descr 
                                      ,t42_data 
                                      ,t42_hora 
                                      ,t42_usuario 
                       )
                values (
                                $this->t42_codigo 
                               ,'$this->t42_descr' 
                               ,".($this->t42_data == "null" || $this->t42_data == ""?"null":"'".$this->t42_data."'")." 
                               ,'$this->t42_hora' 
                               ,$this->t42_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de um lote para inclusão global do bem. ($this->t42_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de um lote para inclusão global do bem. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de um lote para inclusão global do bem. ($this->t42_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t42_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t42_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8912,'$this->t42_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1524,8912,'','".AddSlashes(pg_result($resaco,0,'t42_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1524,8913,'','".AddSlashes(pg_result($resaco,0,'t42_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1524,8914,'','".AddSlashes(pg_result($resaco,0,'t42_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1524,8915,'','".AddSlashes(pg_result($resaco,0,'t42_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1524,8916,'','".AddSlashes(pg_result($resaco,0,'t42_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t42_codigo=null) { 
      $this->atualizacampos();
     $sql = " update benscadlote set ";
     $virgula = "";
     if(trim($this->t42_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t42_codigo"])){ 
       $sql  .= $virgula." t42_codigo = $this->t42_codigo ";
       $virgula = ",";
       if(trim($this->t42_codigo) == null ){ 
         $this->erro_sql = " Campo Código do lote nao Informado.";
         $this->erro_campo = "t42_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t42_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t42_descr"])){ 
       $sql  .= $virgula." t42_descr = '$this->t42_descr' ";
       $virgula = ",";
       if(trim($this->t42_descr) == null ){ 
         $this->erro_sql = " Campo Descrição do lote nao Informado.";
         $this->erro_campo = "t42_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t42_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t42_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t42_data_dia"] !="") ){ 
       $sql  .= $virgula." t42_data = '$this->t42_data' ";
       $virgula = ",";
       if(trim($this->t42_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "t42_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t42_data_dia"])){ 
         $sql  .= $virgula." t42_data = null ";
         $virgula = ",";
         if(trim($this->t42_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "t42_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t42_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t42_hora"])){ 
       $sql  .= $virgula." t42_hora = '$this->t42_hora' ";
       $virgula = ",";
       if(trim($this->t42_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "t42_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t42_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t42_usuario"])){ 
       $sql  .= $virgula." t42_usuario = $this->t42_usuario ";
       $virgula = ",";
       if(trim($this->t42_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "t42_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t42_codigo!=null){
       $sql .= " t42_codigo = $this->t42_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t42_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8912,'$this->t42_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t42_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1524,8912,'".AddSlashes(pg_result($resaco,$conresaco,'t42_codigo'))."','$this->t42_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t42_descr"]))
           $resac = db_query("insert into db_acount values($acount,1524,8913,'".AddSlashes(pg_result($resaco,$conresaco,'t42_descr'))."','$this->t42_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t42_data"]))
           $resac = db_query("insert into db_acount values($acount,1524,8914,'".AddSlashes(pg_result($resaco,$conresaco,'t42_data'))."','$this->t42_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t42_hora"]))
           $resac = db_query("insert into db_acount values($acount,1524,8915,'".AddSlashes(pg_result($resaco,$conresaco,'t42_hora'))."','$this->t42_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t42_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1524,8916,'".AddSlashes(pg_result($resaco,$conresaco,'t42_usuario'))."','$this->t42_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de um lote para inclusão global do bem. nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t42_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de um lote para inclusão global do bem. nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t42_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t42_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t42_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t42_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8912,'$t42_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1524,8912,'','".AddSlashes(pg_result($resaco,$iresaco,'t42_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1524,8913,'','".AddSlashes(pg_result($resaco,$iresaco,'t42_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1524,8914,'','".AddSlashes(pg_result($resaco,$iresaco,'t42_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1524,8915,'','".AddSlashes(pg_result($resaco,$iresaco,'t42_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1524,8916,'','".AddSlashes(pg_result($resaco,$iresaco,'t42_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benscadlote
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t42_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t42_codigo = $t42_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de um lote para inclusão global do bem. nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t42_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de um lote para inclusão global do bem. nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t42_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t42_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:benscadlote";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t42_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benscadlote ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benscadlote.t42_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($t42_codigo!=null ){
         $sql2 .= " where benscadlote.t42_codigo = $t42_codigo "; 
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
   function sql_query_file ( $t42_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benscadlote ";
     $sql2 = "";
     if($dbwhere==""){
       if($t42_codigo!=null ){
         $sql2 .= " where benscadlote.t42_codigo = $t42_codigo "; 
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
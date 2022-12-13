<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_autoriza
class cl_lab_autoriza { 
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
   var $la48_i_codigo = 0; 
   var $la48_i_usuario = 0; 
   var $la48_i_requisicao = 0; 
   var $la48_c_hora = null; 
   var $la48_d_data_dia = null; 
   var $la48_d_data_mes = null; 
   var $la48_d_data_ano = null; 
   var $la48_d_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la48_i_codigo = int4 = Código 
                 la48_i_usuario = int4 = Usuário 
                 la48_i_requisicao = int4 = Requisição 
                 la48_c_hora = char(5) = Hora 
                 la48_d_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_lab_autoriza() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_autoriza"); 
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
       $this->la48_i_codigo = ($this->la48_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la48_i_codigo"]:$this->la48_i_codigo);
       $this->la48_i_usuario = ($this->la48_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["la48_i_usuario"]:$this->la48_i_usuario);
       $this->la48_i_requisicao = ($this->la48_i_requisicao == ""?@$GLOBALS["HTTP_POST_VARS"]["la48_i_requisicao"]:$this->la48_i_requisicao);
       $this->la48_c_hora = ($this->la48_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["la48_c_hora"]:$this->la48_c_hora);
       if($this->la48_d_data == ""){
         $this->la48_d_data_dia = ($this->la48_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la48_d_data_dia"]:$this->la48_d_data_dia);
         $this->la48_d_data_mes = ($this->la48_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la48_d_data_mes"]:$this->la48_d_data_mes);
         $this->la48_d_data_ano = ($this->la48_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la48_d_data_ano"]:$this->la48_d_data_ano);
         if($this->la48_d_data_dia != ""){
            $this->la48_d_data = $this->la48_d_data_ano."-".$this->la48_d_data_mes."-".$this->la48_d_data_dia;
         }
       }
     }else{
       $this->la48_i_codigo = ($this->la48_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la48_i_codigo"]:$this->la48_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la48_i_codigo){ 
      $this->atualizacampos();
     if($this->la48_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "la48_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la48_i_requisicao == null ){ 
       $this->erro_sql = " Campo Requisição nao Informado.";
       $this->erro_campo = "la48_i_requisicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la48_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "la48_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la48_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "la48_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la48_i_codigo == "" || $la48_i_codigo == null ){
       $result = db_query("select nextval('lab_autoriza_la48_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_autoriza_la48_i_codigo_seq do campo: la48_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la48_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_autoriza_la48_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la48_i_codigo)){
         $this->erro_sql = " Campo la48_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la48_i_codigo = $la48_i_codigo; 
       }
     }
     if(($this->la48_i_codigo == null) || ($this->la48_i_codigo == "") ){ 
       $this->erro_sql = " Campo la48_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_autoriza(
                                       la48_i_codigo 
                                      ,la48_i_usuario 
                                      ,la48_i_requisicao 
                                      ,la48_c_hora 
                                      ,la48_d_data 
                       )
                values (
                                $this->la48_i_codigo 
                               ,$this->la48_i_usuario 
                               ,$this->la48_i_requisicao 
                               ,'$this->la48_c_hora' 
                               ,".($this->la48_d_data == "null" || $this->la48_d_data == ""?"null":"'".$this->la48_d_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_autoriza ($this->la48_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_autoriza já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_autoriza ($this->la48_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la48_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la48_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16567,'$this->la48_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2908,16567,'','".AddSlashes(pg_result($resaco,0,'la48_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2908,16568,'','".AddSlashes(pg_result($resaco,0,'la48_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2908,16569,'','".AddSlashes(pg_result($resaco,0,'la48_i_requisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2908,16570,'','".AddSlashes(pg_result($resaco,0,'la48_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2908,16571,'','".AddSlashes(pg_result($resaco,0,'la48_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la48_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_autoriza set ";
     $virgula = "";
     if(trim($this->la48_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la48_i_codigo"])){ 
       $sql  .= $virgula." la48_i_codigo = $this->la48_i_codigo ";
       $virgula = ",";
       if(trim($this->la48_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "la48_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la48_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la48_i_usuario"])){ 
       $sql  .= $virgula." la48_i_usuario = $this->la48_i_usuario ";
       $virgula = ",";
       if(trim($this->la48_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "la48_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la48_i_requisicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la48_i_requisicao"])){ 
       $sql  .= $virgula." la48_i_requisicao = $this->la48_i_requisicao ";
       $virgula = ",";
       if(trim($this->la48_i_requisicao) == null ){ 
         $this->erro_sql = " Campo Requisição nao Informado.";
         $this->erro_campo = "la48_i_requisicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la48_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la48_c_hora"])){ 
       $sql  .= $virgula." la48_c_hora = '$this->la48_c_hora' ";
       $virgula = ",";
       if(trim($this->la48_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "la48_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la48_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la48_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la48_d_data_dia"] !="") ){ 
       $sql  .= $virgula." la48_d_data = '$this->la48_d_data' ";
       $virgula = ",";
       if(trim($this->la48_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "la48_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la48_d_data_dia"])){ 
         $sql  .= $virgula." la48_d_data = null ";
         $virgula = ",";
         if(trim($this->la48_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "la48_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($la48_i_codigo!=null){
       $sql .= " la48_i_codigo = $this->la48_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la48_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16567,'$this->la48_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la48_i_codigo"]) || $this->la48_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2908,16567,'".AddSlashes(pg_result($resaco,$conresaco,'la48_i_codigo'))."','$this->la48_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la48_i_usuario"]) || $this->la48_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2908,16568,'".AddSlashes(pg_result($resaco,$conresaco,'la48_i_usuario'))."','$this->la48_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la48_i_requisicao"]) || $this->la48_i_requisicao != "")
           $resac = db_query("insert into db_acount values($acount,2908,16569,'".AddSlashes(pg_result($resaco,$conresaco,'la48_i_requisicao'))."','$this->la48_i_requisicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la48_c_hora"]) || $this->la48_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2908,16570,'".AddSlashes(pg_result($resaco,$conresaco,'la48_c_hora'))."','$this->la48_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la48_d_data"]) || $this->la48_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2908,16571,'".AddSlashes(pg_result($resaco,$conresaco,'la48_d_data'))."','$this->la48_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_autoriza nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la48_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_autoriza nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la48_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la48_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la48_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la48_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16567,'$la48_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2908,16567,'','".AddSlashes(pg_result($resaco,$iresaco,'la48_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2908,16568,'','".AddSlashes(pg_result($resaco,$iresaco,'la48_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2908,16569,'','".AddSlashes(pg_result($resaco,$iresaco,'la48_i_requisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2908,16570,'','".AddSlashes(pg_result($resaco,$iresaco,'la48_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2908,16571,'','".AddSlashes(pg_result($resaco,$iresaco,'la48_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_autoriza
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la48_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la48_i_codigo = $la48_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_autoriza nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la48_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_autoriza nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la48_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la48_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_autoriza";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la48_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_autoriza ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_autoriza.la48_i_usuario";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_autoriza.la48_i_requisicao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_requisicao.la22_i_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = lab_requisicao.la22_i_departamento";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = lab_requisicao.la22_i_cgs";
     $sql2 = "";
     if($dbwhere==""){
       if($la48_i_codigo!=null ){
         $sql2 .= " where lab_autoriza.la48_i_codigo = $la48_i_codigo "; 
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
   function sql_query_file ( $la48_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_autoriza ";
     $sql2 = "";
     if($dbwhere==""){
       if($la48_i_codigo!=null ){
         $sql2 .= " where lab_autoriza.la48_i_codigo = $la48_i_codigo "; 
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

  function sql_query_controle( $la48_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $lProcedAtivo = true) {

    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from lab_autoriza ";
    $sql .= "      inner join lab_requisicao  on lab_requisicao.la22_i_codigo    = lab_autoriza.la48_i_requisicao";
    $sql .= "      inner join lab_requiitem   on lab_requiitem.la21_i_requisicao = lab_requisicao.la22_i_codigo";
    $sql .= "      inner join lab_setorexame  on lab_setorexame.la09_i_codigo    = lab_requiitem.la21_i_setorexame";
    $sql .= "      inner join lab_exame       on lab_exame.la08_i_codigo         = lab_setorexame.la09_i_exame";
    $sql .= "      left  join lab_exameproced on lab_exameproced.la53_i_exame    = lab_exame.la08_i_codigo";
    if ($lProcedAtivo) {
      $sql .= " and lab_exameproced.la53_i_ativo = 1";
    }
    $sql .= "      left  join sau_procedimento on sau_procedimento.sd63_i_codigo = lab_exameproced.la53_i_procedimento";
    $sql .= "      inner join lab_labsetor     on lab_labsetor.la24_i_codigo     = lab_setorexame.la09_i_labsetor";
    $sql .= "      inner join db_depart        on db_depart.coddepto             = lab_requisicao.la22_i_departamento";
    $sql2 = "";

    if($dbwhere == "") {

      if($la48_i_codigo != null ) {
        $sql2 .= " where lab_autoriza.la48_i_codigo = $la48_i_codigo ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if($ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";

      for($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }
}
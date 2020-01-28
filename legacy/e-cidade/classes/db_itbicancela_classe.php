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

//MODULO: itbi
//CLASSE DA ENTIDADE itbicancela
class cl_itbicancela { 
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
   var $it16_guia = 0; 
   var $it16_data_dia = null; 
   var $it16_data_mes = null; 
   var $it16_data_ano = null; 
   var $it16_data = null; 
   var $it16_obs = null; 
   var $it16_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it16_guia = int8 = Número da guia de ITBI 
                 it16_data = date = Data do cancelamento 
                 it16_obs = text = Observações 
                 it16_id_usuario = int4 = Identificador do Usuário 
                 ";
   //funcao construtor da classe 
   function cl_itbicancela() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbicancela"); 
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
       $this->it16_guia = ($this->it16_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it16_guia"]:$this->it16_guia);
       if($this->it16_data == ""){
         $this->it16_data_dia = ($this->it16_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["it16_data_dia"]:$this->it16_data_dia);
         $this->it16_data_mes = ($this->it16_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["it16_data_mes"]:$this->it16_data_mes);
         $this->it16_data_ano = ($this->it16_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["it16_data_ano"]:$this->it16_data_ano);
         if($this->it16_data_dia != ""){
            $this->it16_data = $this->it16_data_ano."-".$this->it16_data_mes."-".$this->it16_data_dia;
         }
       }
       $this->it16_obs = ($this->it16_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["it16_obs"]:$this->it16_obs);
       $this->it16_id_usuario = ($this->it16_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["it16_id_usuario"]:$this->it16_id_usuario);
     }else{
       $this->it16_guia = ($this->it16_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it16_guia"]:$this->it16_guia);
     }
   }
   // funcao para inclusao
   function incluir ($it16_guia){ 
      $this->atualizacampos();
     if($this->it16_data == null ){ 
       $this->erro_sql = " Campo Data do cancelamento não informado.";
       $this->erro_campo = "it16_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it16_id_usuario == null ){ 
       $this->erro_sql = " Campo Identificador do Usuário não informado.";
       $this->erro_campo = "it16_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->it16_guia = $it16_guia; 
     if(($this->it16_guia == null) || ($this->it16_guia == "") ){ 
       $this->erro_sql = " Campo it16_guia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbicancela(
                                       it16_guia 
                                      ,it16_data 
                                      ,it16_obs 
                                      ,it16_id_usuario 
                       )
                values (
                                $this->it16_guia 
                               ,".($this->it16_data == "null" || $this->it16_data == ""?"null":"'".$this->it16_data."'")." 
                               ,'$this->it16_obs' 
                               ,$this->it16_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tabela de ITBI's canceladas ($this->it16_guia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tabela de ITBI's canceladas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tabela de ITBI's canceladas ($this->it16_guia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it16_guia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->it16_guia  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5725,'$this->it16_guia','I')");
         $resac = db_query("insert into db_acount values($acount,906,5725,'','".AddSlashes(pg_result($resaco,0,'it16_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,906,5726,'','".AddSlashes(pg_result($resaco,0,'it16_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,906,5727,'','".AddSlashes(pg_result($resaco,0,'it16_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,906,20666,'','".AddSlashes(pg_result($resaco,0,'it16_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it16_guia=null) { 
      $this->atualizacampos();
     $sql = " update itbicancela set ";
     $virgula = "";
     if(trim($this->it16_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it16_guia"])){ 
       $sql  .= $virgula." it16_guia = $this->it16_guia ";
       $virgula = ",";
       if(trim($this->it16_guia) == null ){ 
         $this->erro_sql = " Campo Número da guia de ITBI não informado.";
         $this->erro_campo = "it16_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it16_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it16_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["it16_data_dia"] !="") ){ 
       $sql  .= $virgula." it16_data = '$this->it16_data' ";
       $virgula = ",";
       if(trim($this->it16_data) == null ){ 
         $this->erro_sql = " Campo Data do cancelamento não informado.";
         $this->erro_campo = "it16_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["it16_data_dia"])){ 
         $sql  .= $virgula." it16_data = null ";
         $virgula = ",";
         if(trim($this->it16_data) == null ){ 
           $this->erro_sql = " Campo Data do cancelamento não informado.";
           $this->erro_campo = "it16_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->it16_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it16_obs"])){ 
       $sql  .= $virgula." it16_obs = '$this->it16_obs' ";
       $virgula = ",";
     }
     if(trim($this->it16_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it16_id_usuario"])){ 
       $sql  .= $virgula." it16_id_usuario = $this->it16_id_usuario ";
       $virgula = ",";
       if(trim($this->it16_id_usuario) == null ){ 
         $this->erro_sql = " Campo Identificador do Usuário não informado.";
         $this->erro_campo = "it16_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($it16_guia!=null){
       $sql .= " it16_guia = $this->it16_guia";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->it16_guia));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5725,'$this->it16_guia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it16_guia"]))
             $resac = db_query("insert into db_acount values($acount,906,5725,'".AddSlashes(pg_result($resaco,$conresaco,'it16_guia'))."','$this->it16_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it16_data"]))
             $resac = db_query("insert into db_acount values($acount,906,5726,'".AddSlashes(pg_result($resaco,$conresaco,'it16_data'))."','$this->it16_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it16_obs"]))
             $resac = db_query("insert into db_acount values($acount,906,5727,'".AddSlashes(pg_result($resaco,$conresaco,'it16_obs'))."','$this->it16_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it16_id_usuario"]) || $this->it16_id_usuario != "")
             $resac = db_query("insert into db_acount values($acount,906,20666,'".AddSlashes(pg_result($resaco,$conresaco,'it16_id_usuario'))."','$this->it16_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabela de ITBI's canceladas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it16_guia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabela de ITBI's canceladas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it16_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it16_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it16_guia=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($it16_guia));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,5725,'$it16_guia','E')");
           $resac  = db_query("insert into db_acount values($acount,906,5725,'','".AddSlashes(pg_result($resaco,$iresaco,'it16_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,906,5726,'','".AddSlashes(pg_result($resaco,$iresaco,'it16_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,906,5727,'','".AddSlashes(pg_result($resaco,$iresaco,'it16_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,906,20666,'','".AddSlashes(pg_result($resaco,$iresaco,'it16_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from itbicancela
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it16_guia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it16_guia = $it16_guia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabela de ITBI's canceladas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it16_guia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabela de ITBI's canceladas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it16_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it16_guia;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbicancela";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $it16_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbicancela ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itbicancela.it16_id_usuario";
     $sql .= "      inner join itbi  on  itbi.it01_guia = itbicancela.it16_guia";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = itbi.it01_coddepto";
     $sql .= "      inner join itbitransacao  on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql2 = "";
     if($dbwhere==""){
       if($it16_guia!=null ){
         $sql2 .= " where itbicancela.it16_guia = $it16_guia "; 
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
   function sql_query_file ( $it16_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbicancela ";
     $sql2 = "";
     if($dbwhere==""){
       if($it16_guia!=null ){
         $sql2 .= " where itbicancela.it16_guia = $it16_guia "; 
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
<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: fiscal
//CLASSE DA ENTIDADE graficas
class cl_graficas { 
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
   var $y20_grafica = 0; 
   var $y20_id_usuario = 0; 
   var $y20_data_dia = null; 
   var $y20_data_mes = null; 
   var $y20_data_ano = null; 
   var $y20_data = null; 
   var $y20_datalimiteimpressao_dia = null; 
   var $y20_datalimiteimpressao_mes = null; 
   var $y20_datalimiteimpressao_ano = null; 
   var $y20_datalimiteimpressao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y20_grafica = int4 = Código Gráfica 
                 y20_id_usuario = int4 = Usuário 
                 y20_data = date = Data Inclusão 
                 y20_datalimiteimpressao = date = Data limite de impressão fiscal 
                 ";
   //funcao construtor da classe 
   function cl_graficas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("graficas"); 
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
       $this->y20_grafica = ($this->y20_grafica == ""?@$GLOBALS["HTTP_POST_VARS"]["y20_grafica"]:$this->y20_grafica);
       $this->y20_id_usuario = ($this->y20_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["y20_id_usuario"]:$this->y20_id_usuario);
       if($this->y20_data == ""){
         $this->y20_data_dia = ($this->y20_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y20_data_dia"]:$this->y20_data_dia);
         $this->y20_data_mes = ($this->y20_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y20_data_mes"]:$this->y20_data_mes);
         $this->y20_data_ano = ($this->y20_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y20_data_ano"]:$this->y20_data_ano);
         if($this->y20_data_dia != ""){
            $this->y20_data = $this->y20_data_ano."-".$this->y20_data_mes."-".$this->y20_data_dia;
         }
       }
       if($this->y20_datalimiteimpressao == ""){
         $this->y20_datalimiteimpressao_dia = ($this->y20_datalimiteimpressao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y20_datalimiteimpressao_dia"]:$this->y20_datalimiteimpressao_dia);
         $this->y20_datalimiteimpressao_mes = ($this->y20_datalimiteimpressao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y20_datalimiteimpressao_mes"]:$this->y20_datalimiteimpressao_mes);
         $this->y20_datalimiteimpressao_ano = ($this->y20_datalimiteimpressao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y20_datalimiteimpressao_ano"]:$this->y20_datalimiteimpressao_ano);
         if($this->y20_datalimiteimpressao_dia != ""){
            $this->y20_datalimiteimpressao = $this->y20_datalimiteimpressao_ano."-".$this->y20_datalimiteimpressao_mes."-".$this->y20_datalimiteimpressao_dia;
         }
       }
     }else{
       $this->y20_grafica = ($this->y20_grafica == ""?@$GLOBALS["HTTP_POST_VARS"]["y20_grafica"]:$this->y20_grafica);
     }
   }
   // funcao para inclusao
   function incluir ($y20_grafica){ 
      $this->atualizacampos();
     if($this->y20_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "y20_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y20_data == null ){ 
       $this->erro_sql = " Campo Data Inclusão nao Informado.";
       $this->erro_campo = "y20_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y20_datalimiteimpressao == null ){ 
       $this->y20_datalimiteimpressao = "null";
     }
       $this->y20_grafica = $y20_grafica; 
     if(($this->y20_grafica == null) || ($this->y20_grafica == "") ){ 
       $this->erro_sql = " Campo y20_grafica nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into graficas(
                                       y20_grafica 
                                      ,y20_id_usuario 
                                      ,y20_data 
                                      ,y20_datalimiteimpressao 
                       )
                values (
                                $this->y20_grafica 
                               ,$this->y20_id_usuario 
                               ,".($this->y20_data == "null" || $this->y20_data == ""?"null":"'".$this->y20_data."'")." 
                               ,".($this->y20_datalimiteimpressao == "null" || $this->y20_datalimiteimpressao == ""?"null":"'".$this->y20_datalimiteimpressao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Gráficas Cadastradas no Sistema ($this->y20_grafica) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Gráficas Cadastradas no Sistema já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Gráficas Cadastradas no Sistema ($this->y20_grafica) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y20_grafica;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->y20_grafica  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4668,'$this->y20_grafica','I')");
         $resac = db_query("insert into db_acount values($acount,613,4668,'','".AddSlashes(pg_result($resaco,0,'y20_grafica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,613,4670,'','".AddSlashes(pg_result($resaco,0,'y20_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,613,4669,'','".AddSlashes(pg_result($resaco,0,'y20_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,613,19929,'','".AddSlashes(pg_result($resaco,0,'y20_datalimiteimpressao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y20_grafica=null) { 
      $this->atualizacampos();
     $sql = " update graficas set ";
     $virgula = "";
     if(trim($this->y20_grafica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y20_grafica"])){ 
       $sql  .= $virgula." y20_grafica = $this->y20_grafica ";
       $virgula = ",";
       if(trim($this->y20_grafica) == null ){ 
         $this->erro_sql = " Campo Código Gráfica nao Informado.";
         $this->erro_campo = "y20_grafica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y20_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y20_id_usuario"])){ 
       $sql  .= $virgula." y20_id_usuario = $this->y20_id_usuario ";
       $virgula = ",";
       if(trim($this->y20_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "y20_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y20_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y20_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y20_data_dia"] !="") ){ 
       $sql  .= $virgula." y20_data = '$this->y20_data' ";
       $virgula = ",";
       if(trim($this->y20_data) == null ){ 
         $this->erro_sql = " Campo Data Inclusão nao Informado.";
         $this->erro_campo = "y20_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y20_data_dia"])){ 
         $sql  .= $virgula." y20_data = null ";
         $virgula = ",";
         if(trim($this->y20_data) == null ){ 
           $this->erro_sql = " Campo Data Inclusão nao Informado.";
           $this->erro_campo = "y20_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y20_datalimiteimpressao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y20_datalimiteimpressao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y20_datalimiteimpressao_dia"] !="") ){ 
       $sql  .= $virgula." y20_datalimiteimpressao = '$this->y20_datalimiteimpressao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y20_datalimiteimpressao_dia"])){ 
         $sql  .= $virgula." y20_datalimiteimpressao = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($y20_grafica!=null){
       $sql .= " y20_grafica = $this->y20_grafica";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->y20_grafica));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,4668,'$this->y20_grafica','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y20_grafica"]) || $this->y20_grafica != "")
             $resac = db_query("insert into db_acount values($acount,613,4668,'".AddSlashes(pg_result($resaco,$conresaco,'y20_grafica'))."','$this->y20_grafica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y20_id_usuario"]) || $this->y20_id_usuario != "")
             $resac = db_query("insert into db_acount values($acount,613,4670,'".AddSlashes(pg_result($resaco,$conresaco,'y20_id_usuario'))."','$this->y20_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y20_data"]) || $this->y20_data != "")
             $resac = db_query("insert into db_acount values($acount,613,4669,'".AddSlashes(pg_result($resaco,$conresaco,'y20_data'))."','$this->y20_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["y20_datalimiteimpressao"]) || $this->y20_datalimiteimpressao != "")
             $resac = db_query("insert into db_acount values($acount,613,19929,'".AddSlashes(pg_result($resaco,$conresaco,'y20_datalimiteimpressao'))."','$this->y20_datalimiteimpressao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Gráficas Cadastradas no Sistema nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y20_grafica;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Gráficas Cadastradas no Sistema nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y20_grafica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y20_grafica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y20_grafica=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($y20_grafica));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,4668,'$y20_grafica','E')");
           $resac  = db_query("insert into db_acount values($acount,613,4668,'','".AddSlashes(pg_result($resaco,$iresaco,'y20_grafica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,613,4670,'','".AddSlashes(pg_result($resaco,$iresaco,'y20_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,613,4669,'','".AddSlashes(pg_result($resaco,$iresaco,'y20_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,613,19929,'','".AddSlashes(pg_result($resaco,$iresaco,'y20_datalimiteimpressao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from graficas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y20_grafica != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y20_grafica = $y20_grafica ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Gráficas Cadastradas no Sistema nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y20_grafica;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Gráficas Cadastradas no Sistema nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y20_grafica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y20_grafica;
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
        $this->erro_sql   = "Record Vazio na Tabela:graficas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $y20_grafica=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from graficas ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = graficas.y20_grafica";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = graficas.y20_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($y20_grafica!=null ){
         $sql2 .= " where graficas.y20_grafica = $y20_grafica "; 
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
   function sql_query_file ( $y20_grafica=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from graficas ";
     $sql2 = "";
     if($dbwhere==""){
       if($y20_grafica!=null ){
         $sql2 .= " where graficas.y20_grafica = $y20_grafica "; 
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
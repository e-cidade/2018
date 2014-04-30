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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_versao
class cl_db_versao { 
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
   var $db30_codver = 0; 
   var $db30_codversao = 0; 
   var $db30_codrelease = 0; 
   var $db30_data_dia = null; 
   var $db30_data_mes = null; 
   var $db30_data_ano = null; 
   var $db30_data = null; 
   var $db30_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db30_codver = int4 = Código da Versão 
                 db30_codversao = int4 = Número da Release 
                 db30_codrelease = int4 = Número da Sub-Release 
                 db30_data = date = Data da Versão/Release 
                 db30_obs = text = Observação da Versão/Release 
                 ";
   //funcao construtor da classe 
   function cl_db_versao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_versao"); 
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
       $this->db30_codver = ($this->db30_codver == ""?@$GLOBALS["HTTP_POST_VARS"]["db30_codver"]:$this->db30_codver);
       $this->db30_codversao = ($this->db30_codversao == ""?@$GLOBALS["HTTP_POST_VARS"]["db30_codversao"]:$this->db30_codversao);
       $this->db30_codrelease = ($this->db30_codrelease == ""?@$GLOBALS["HTTP_POST_VARS"]["db30_codrelease"]:$this->db30_codrelease);
       if($this->db30_data == ""){
         $this->db30_data_dia = ($this->db30_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db30_data_dia"]:$this->db30_data_dia);
         $this->db30_data_mes = ($this->db30_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db30_data_mes"]:$this->db30_data_mes);
         $this->db30_data_ano = ($this->db30_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db30_data_ano"]:$this->db30_data_ano);
         if($this->db30_data_dia != ""){
            $this->db30_data = $this->db30_data_ano."-".$this->db30_data_mes."-".$this->db30_data_dia;
         }
       }
       $this->db30_obs = ($this->db30_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db30_obs"]:$this->db30_obs);
     }else{
       $this->db30_codver = ($this->db30_codver == ""?@$GLOBALS["HTTP_POST_VARS"]["db30_codver"]:$this->db30_codver);
     }
   }
   // funcao para inclusao
   function incluir ($db30_codver){ 
      $this->atualizacampos();
     if($this->db30_codversao == null ){ 
       $this->erro_sql = " Campo Número da Release nao Informado.";
       $this->erro_campo = "db30_codversao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db30_codrelease == null ){ 
       $this->erro_sql = " Campo Número da Sub-Release nao Informado.";
       $this->erro_campo = "db30_codrelease";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db30_data == null ){ 
       $this->erro_sql = " Campo Data da Versão/Release nao Informado.";
       $this->erro_campo = "db30_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db30_obs == null ){ 
       $this->erro_sql = " Campo Observação da Versão/Release nao Informado.";
       $this->erro_campo = "db30_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db30_codver == "" || $db30_codver == null ){
       $result = db_query("select nextval('db_versao_db30_codver_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_versao_db30_codver_seq do campo: db30_codver"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db30_codver = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_versao_db30_codver_seq");
       if(($result != false) && (pg_result($result,0,0) < $db30_codver)){
         $this->erro_sql = " Campo db30_codver maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db30_codver = $db30_codver; 
       }
     }
     if(($this->db30_codver == null) || ($this->db30_codver == "") ){ 
       $this->erro_sql = " Campo db30_codver nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_versao(
                                       db30_codver 
                                      ,db30_codversao 
                                      ,db30_codrelease 
                                      ,db30_data 
                                      ,db30_obs 
                       )
                values (
                                $this->db30_codver 
                               ,$this->db30_codversao 
                               ,$this->db30_codrelease 
                               ,".($this->db30_data == "null" || $this->db30_data == ""?"null":"'".$this->db30_data."'")." 
                               ,'$this->db30_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Versão Atual ($this->db30_codver) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Versão Atual já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Versão Atual ($this->db30_codver) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db30_codver;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db30_codver));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5867,'$this->db30_codver','I')");
       $resac = db_query("insert into db_acount values($acount,939,5867,'','".AddSlashes(pg_result($resaco,0,'db30_codver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,939,5868,'','".AddSlashes(pg_result($resaco,0,'db30_codversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,939,5869,'','".AddSlashes(pg_result($resaco,0,'db30_codrelease'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,939,5870,'','".AddSlashes(pg_result($resaco,0,'db30_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,939,5871,'','".AddSlashes(pg_result($resaco,0,'db30_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db30_codver=null) { 
      $this->atualizacampos();
     $sql = " update db_versao set ";
     $virgula = "";
     if(trim($this->db30_codver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db30_codver"])){ 
       $sql  .= $virgula." db30_codver = $this->db30_codver ";
       $virgula = ",";
       if(trim($this->db30_codver) == null ){ 
         $this->erro_sql = " Campo Código da Versão nao Informado.";
         $this->erro_campo = "db30_codver";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db30_codversao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db30_codversao"])){ 
       $sql  .= $virgula." db30_codversao = $this->db30_codversao ";
       $virgula = ",";
       if(trim($this->db30_codversao) == null ){ 
         $this->erro_sql = " Campo Número da Release nao Informado.";
         $this->erro_campo = "db30_codversao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db30_codrelease)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db30_codrelease"])){ 
       $sql  .= $virgula." db30_codrelease = $this->db30_codrelease ";
       $virgula = ",";
       if(trim($this->db30_codrelease) == null ){ 
         $this->erro_sql = " Campo Número da Sub-Release nao Informado.";
         $this->erro_campo = "db30_codrelease";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db30_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db30_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db30_data_dia"] !="") ){ 
       $sql  .= $virgula." db30_data = '$this->db30_data' ";
       $virgula = ",";
       if(trim($this->db30_data) == null ){ 
         $this->erro_sql = " Campo Data da Versão/Release nao Informado.";
         $this->erro_campo = "db30_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db30_data_dia"])){ 
         $sql  .= $virgula." db30_data = null ";
         $virgula = ",";
         if(trim($this->db30_data) == null ){ 
           $this->erro_sql = " Campo Data da Versão/Release nao Informado.";
           $this->erro_campo = "db30_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db30_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db30_obs"])){ 
       $sql  .= $virgula." db30_obs = '$this->db30_obs' ";
       $virgula = ",";
       if(trim($this->db30_obs) == null ){ 
         $this->erro_sql = " Campo Observação da Versão/Release nao Informado.";
         $this->erro_campo = "db30_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db30_codver!=null){
       $sql .= " db30_codver = $this->db30_codver";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db30_codver));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5867,'$this->db30_codver','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db30_codver"]))
           $resac = db_query("insert into db_acount values($acount,939,5867,'".AddSlashes(pg_result($resaco,$conresaco,'db30_codver'))."','$this->db30_codver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db30_codversao"]))
           $resac = db_query("insert into db_acount values($acount,939,5868,'".AddSlashes(pg_result($resaco,$conresaco,'db30_codversao'))."','$this->db30_codversao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db30_codrelease"]))
           $resac = db_query("insert into db_acount values($acount,939,5869,'".AddSlashes(pg_result($resaco,$conresaco,'db30_codrelease'))."','$this->db30_codrelease',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db30_data"]))
           $resac = db_query("insert into db_acount values($acount,939,5870,'".AddSlashes(pg_result($resaco,$conresaco,'db30_data'))."','$this->db30_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db30_obs"]))
           $resac = db_query("insert into db_acount values($acount,939,5871,'".AddSlashes(pg_result($resaco,$conresaco,'db30_obs'))."','$this->db30_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Versão Atual nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db30_codver;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Versão Atual nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db30_codver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db30_codver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db30_codver=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db30_codver));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5867,'$db30_codver','E')");
         $resac = db_query("insert into db_acount values($acount,939,5867,'','".AddSlashes(pg_result($resaco,$iresaco,'db30_codver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,939,5868,'','".AddSlashes(pg_result($resaco,$iresaco,'db30_codversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,939,5869,'','".AddSlashes(pg_result($resaco,$iresaco,'db30_codrelease'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,939,5870,'','".AddSlashes(pg_result($resaco,$iresaco,'db30_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,939,5871,'','".AddSlashes(pg_result($resaco,$iresaco,'db30_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_versao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db30_codver != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db30_codver = $db30_codver ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Versão Atual nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db30_codver;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Versão Atual nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db30_codver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db30_codver;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_versao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db30_codver=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_versao ";
     $sql .= "      left outer join db_versaocpd on db_versao.db30_codver = db_versaocpd.db33_codver";
     $sql .= "      left outer join db_versaousu on db_versao.db30_codver = db_versaousu.db32_codver";
     $sql2 = "";
     if($dbwhere==""){
       if($db30_codver!=null ){
         $sql2 .= " where db_versao.db30_codver = $db30_codver "; 
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
   function sql_query_file ( $db30_codver=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_versao ";
     $sql2 = "";
     if($dbwhere==""){
       if($db30_codver!=null ){
         $sql2 .= " where db_versao.db30_codver = $db30_codver "; 
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
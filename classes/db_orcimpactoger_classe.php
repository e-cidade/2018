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
//CLASSE DA ENTIDADE orcimpactoger
class cl_orcimpactoger { 
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
   var $o62_codimpger = 0; 
   var $o62_data_dia = null; 
   var $o62_data_mes = null; 
   var $o62_data_ano = null; 
   var $o62_data = null; 
   var $o62_obs = null; 
   var $o62_ativo = 0; 
   var $o62_passivo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o62_codimpger = int8 = Código 
                 o62_data = date = Data 
                 o62_obs = text = Obs 
                 o62_ativo = float8 = Ativo 
                 o62_passivo = float8 = Passivo 
                 ";
   //funcao construtor da classe 
   function cl_orcimpactoger() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpactoger"); 
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
       $this->o62_codimpger = ($this->o62_codimpger == ""?@$GLOBALS["HTTP_POST_VARS"]["o62_codimpger"]:$this->o62_codimpger);
       if($this->o62_data == ""){
         $this->o62_data_dia = ($this->o62_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o62_data_dia"]:$this->o62_data_dia);
         $this->o62_data_mes = ($this->o62_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o62_data_mes"]:$this->o62_data_mes);
         $this->o62_data_ano = ($this->o62_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o62_data_ano"]:$this->o62_data_ano);
         if($this->o62_data_dia != ""){
            $this->o62_data = $this->o62_data_ano."-".$this->o62_data_mes."-".$this->o62_data_dia;
         }
       }
       $this->o62_obs = ($this->o62_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["o62_obs"]:$this->o62_obs);
       $this->o62_ativo = ($this->o62_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["o62_ativo"]:$this->o62_ativo);
       $this->o62_passivo = ($this->o62_passivo == ""?@$GLOBALS["HTTP_POST_VARS"]["o62_passivo"]:$this->o62_passivo);
     }else{
       $this->o62_codimpger = ($this->o62_codimpger == ""?@$GLOBALS["HTTP_POST_VARS"]["o62_codimpger"]:$this->o62_codimpger);
     }
   }
   // funcao para inclusao
   function incluir ($o62_codimpger){ 
      $this->atualizacampos();
     if($this->o62_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "o62_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o62_obs == null ){ 
       $this->erro_sql = " Campo Obs nao Informado.";
       $this->erro_campo = "o62_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o62_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "o62_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o62_passivo == null ){ 
       $this->erro_sql = " Campo Passivo nao Informado.";
       $this->erro_campo = "o62_passivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o62_codimpger == "" || $o62_codimpger == null ){
       $result = db_query("select nextval('orcimpactoger_o62_codimpger_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcimpactoger_o62_codimpger_seq do campo: o62_codimpger"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o62_codimpger = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcimpactoger_o62_codimpger_seq");
       if(($result != false) && (pg_result($result,0,0) < $o62_codimpger)){
         $this->erro_sql = " Campo o62_codimpger maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o62_codimpger = $o62_codimpger; 
       }
     }
     if(($this->o62_codimpger == null) || ($this->o62_codimpger == "") ){ 
       $this->erro_sql = " Campo o62_codimpger nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpactoger(
                                       o62_codimpger 
                                      ,o62_data 
                                      ,o62_obs 
                                      ,o62_ativo 
                                      ,o62_passivo 
                       )
                values (
                                $this->o62_codimpger 
                               ,".($this->o62_data == "null" || $this->o62_data == ""?"null":"'".$this->o62_data."'")." 
                               ,'$this->o62_obs' 
                               ,$this->o62_ativo 
                               ,$this->o62_passivo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Impactos gerados ($this->o62_codimpger) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Impactos gerados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Impactos gerados ($this->o62_codimpger) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o62_codimpger;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o62_codimpger));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6662,'$this->o62_codimpger','I')");
       $resac = db_query("insert into db_acount values($acount,1094,6662,'','".AddSlashes(pg_result($resaco,0,'o62_codimpger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1094,6663,'','".AddSlashes(pg_result($resaco,0,'o62_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1094,6664,'','".AddSlashes(pg_result($resaco,0,'o62_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1094,6665,'','".AddSlashes(pg_result($resaco,0,'o62_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1094,6666,'','".AddSlashes(pg_result($resaco,0,'o62_passivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o62_codimpger=null) { 
      $this->atualizacampos();
     $sql = " update orcimpactoger set ";
     $virgula = "";
     if(trim($this->o62_codimpger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o62_codimpger"])){ 
       $sql  .= $virgula." o62_codimpger = $this->o62_codimpger ";
       $virgula = ",";
       if(trim($this->o62_codimpger) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o62_codimpger";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o62_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o62_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o62_data_dia"] !="") ){ 
       $sql  .= $virgula." o62_data = '$this->o62_data' ";
       $virgula = ",";
       if(trim($this->o62_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "o62_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o62_data_dia"])){ 
         $sql  .= $virgula." o62_data = null ";
         $virgula = ",";
         if(trim($this->o62_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "o62_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o62_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o62_obs"])){ 
       $sql  .= $virgula." o62_obs = '$this->o62_obs' ";
       $virgula = ",";
       if(trim($this->o62_obs) == null ){ 
         $this->erro_sql = " Campo Obs nao Informado.";
         $this->erro_campo = "o62_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o62_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o62_ativo"])){ 
       $sql  .= $virgula." o62_ativo = $this->o62_ativo ";
       $virgula = ",";
       if(trim($this->o62_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "o62_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o62_passivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o62_passivo"])){ 
       $sql  .= $virgula." o62_passivo = $this->o62_passivo ";
       $virgula = ",";
       if(trim($this->o62_passivo) == null ){ 
         $this->erro_sql = " Campo Passivo nao Informado.";
         $this->erro_campo = "o62_passivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o62_codimpger!=null){
       $sql .= " o62_codimpger = $this->o62_codimpger";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o62_codimpger));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6662,'$this->o62_codimpger','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o62_codimpger"]))
           $resac = db_query("insert into db_acount values($acount,1094,6662,'".AddSlashes(pg_result($resaco,$conresaco,'o62_codimpger'))."','$this->o62_codimpger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o62_data"]))
           $resac = db_query("insert into db_acount values($acount,1094,6663,'".AddSlashes(pg_result($resaco,$conresaco,'o62_data'))."','$this->o62_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o62_obs"]))
           $resac = db_query("insert into db_acount values($acount,1094,6664,'".AddSlashes(pg_result($resaco,$conresaco,'o62_obs'))."','$this->o62_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o62_ativo"]))
           $resac = db_query("insert into db_acount values($acount,1094,6665,'".AddSlashes(pg_result($resaco,$conresaco,'o62_ativo'))."','$this->o62_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o62_passivo"]))
           $resac = db_query("insert into db_acount values($acount,1094,6666,'".AddSlashes(pg_result($resaco,$conresaco,'o62_passivo'))."','$this->o62_passivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Impactos gerados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o62_codimpger;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Impactos gerados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o62_codimpger;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o62_codimpger;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o62_codimpger=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o62_codimpger));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6662,'$o62_codimpger','E')");
         $resac = db_query("insert into db_acount values($acount,1094,6662,'','".AddSlashes(pg_result($resaco,$iresaco,'o62_codimpger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1094,6663,'','".AddSlashes(pg_result($resaco,$iresaco,'o62_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1094,6664,'','".AddSlashes(pg_result($resaco,$iresaco,'o62_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1094,6665,'','".AddSlashes(pg_result($resaco,$iresaco,'o62_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1094,6666,'','".AddSlashes(pg_result($resaco,$iresaco,'o62_passivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpactoger
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o62_codimpger != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o62_codimpger = $o62_codimpger ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Impactos gerados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o62_codimpger;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Impactos gerados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o62_codimpger;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o62_codimpger;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpactoger";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o62_codimpger=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactoger ";
     $sql2 = "";
     if($dbwhere==""){
       if($o62_codimpger!=null ){
         $sql2 .= " where orcimpactoger.o62_codimpger = $o62_codimpger "; 
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
   function sql_query_file ( $o62_codimpger=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactoger ";
     $sql2 = "";
     if($dbwhere==""){
       if($o62_codimpger!=null ){
         $sql2 .= " where orcimpactoger.o62_codimpger = $o62_codimpger "; 
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
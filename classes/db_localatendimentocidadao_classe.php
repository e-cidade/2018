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

//MODULO: social
//CLASSE DA ENTIDADE localatendimentocidadao
class cl_localatendimentocidadao { 
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
   var $as17_sequencial = 0; 
   var $as17_localatendimentosocial = 0; 
   var $as17_cidadao = 0; 
   var $as17_cidadao_seq = 0; 
   var $as17_fimatendimento_dia = null; 
   var $as17_fimatendimento_mes = null; 
   var $as17_fimatendimento_ano = null; 
   var $as17_fimatendimento = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as17_sequencial = int4 = Sequencial 
                 as17_localatendimentosocial = int4 = Sequencial 
                 as17_cidadao = int4 = Cidadão 
                 as17_cidadao_seq = int4 = Sequencial 
                 as17_fimatendimento = date = Data Final de Atendimento 
                 ";
   //funcao construtor da classe 
   function cl_localatendimentocidadao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("localatendimentocidadao"); 
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
       $this->as17_sequencial = ($this->as17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as17_sequencial"]:$this->as17_sequencial);
       $this->as17_localatendimentosocial = ($this->as17_localatendimentosocial == ""?@$GLOBALS["HTTP_POST_VARS"]["as17_localatendimentosocial"]:$this->as17_localatendimentosocial);
       $this->as17_cidadao = ($this->as17_cidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["as17_cidadao"]:$this->as17_cidadao);
       $this->as17_cidadao_seq = ($this->as17_cidadao_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["as17_cidadao_seq"]:$this->as17_cidadao_seq);
       if($this->as17_fimatendimento == ""){
         $this->as17_fimatendimento_dia = ($this->as17_fimatendimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as17_fimatendimento_dia"]:$this->as17_fimatendimento_dia);
         $this->as17_fimatendimento_mes = ($this->as17_fimatendimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as17_fimatendimento_mes"]:$this->as17_fimatendimento_mes);
         $this->as17_fimatendimento_ano = ($this->as17_fimatendimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as17_fimatendimento_ano"]:$this->as17_fimatendimento_ano);
         if($this->as17_fimatendimento_dia != ""){
            $this->as17_fimatendimento = $this->as17_fimatendimento_ano."-".$this->as17_fimatendimento_mes."-".$this->as17_fimatendimento_dia;
         }
       }
     }else{
       $this->as17_sequencial = ($this->as17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as17_sequencial"]:$this->as17_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as17_sequencial){ 
      $this->atualizacampos();
     if($this->as17_localatendimentosocial == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "as17_localatendimentosocial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as17_cidadao == null ){ 
       $this->erro_sql = " Campo Cidadão nao Informado.";
       $this->erro_campo = "as17_cidadao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as17_cidadao_seq == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "as17_cidadao_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as17_fimatendimento == null ){ 
       $this->as17_fimatendimento = "null";
     }
     if($as17_sequencial == "" || $as17_sequencial == null ){
       $result = db_query("select nextval('localatendimentocidadao_as17_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: localatendimentocidadao_as17_sequencial_seq do campo: as17_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as17_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from localatendimentocidadao_as17_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as17_sequencial)){
         $this->erro_sql = " Campo as17_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as17_sequencial = $as17_sequencial; 
       }
     }
     if(($this->as17_sequencial == null) || ($this->as17_sequencial == "") ){ 
       $this->erro_sql = " Campo as17_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into localatendimentocidadao(
                                       as17_sequencial 
                                      ,as17_localatendimentosocial 
                                      ,as17_cidadao 
                                      ,as17_cidadao_seq 
                                      ,as17_fimatendimento 
                       )
                values (
                                $this->as17_sequencial 
                               ,$this->as17_localatendimentosocial 
                               ,$this->as17_cidadao 
                               ,$this->as17_cidadao_seq 
                               ,".($this->as17_fimatendimento == "null" || $this->as17_fimatendimento == ""?"null":"'".$this->as17_fimatendimento."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Local Atendimento Cidadão ($this->as17_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Local Atendimento Cidadão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Local Atendimento Cidadão ($this->as17_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as17_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as17_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19948,'$this->as17_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3575,19948,'','".AddSlashes(pg_result($resaco,0,'as17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3575,19949,'','".AddSlashes(pg_result($resaco,0,'as17_localatendimentosocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3575,19950,'','".AddSlashes(pg_result($resaco,0,'as17_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3575,19951,'','".AddSlashes(pg_result($resaco,0,'as17_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3575,19952,'','".AddSlashes(pg_result($resaco,0,'as17_fimatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as17_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update localatendimentocidadao set ";
     $virgula = "";
     if(trim($this->as17_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as17_sequencial"])){ 
       $sql  .= $virgula." as17_sequencial = $this->as17_sequencial ";
       $virgula = ",";
       if(trim($this->as17_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "as17_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as17_localatendimentosocial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as17_localatendimentosocial"])){ 
       $sql  .= $virgula." as17_localatendimentosocial = $this->as17_localatendimentosocial ";
       $virgula = ",";
       if(trim($this->as17_localatendimentosocial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "as17_localatendimentosocial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as17_cidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as17_cidadao"])){ 
       $sql  .= $virgula." as17_cidadao = $this->as17_cidadao ";
       $virgula = ",";
       if(trim($this->as17_cidadao) == null ){ 
         $this->erro_sql = " Campo Cidadão nao Informado.";
         $this->erro_campo = "as17_cidadao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as17_cidadao_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as17_cidadao_seq"])){ 
       $sql  .= $virgula." as17_cidadao_seq = $this->as17_cidadao_seq ";
       $virgula = ",";
       if(trim($this->as17_cidadao_seq) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "as17_cidadao_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as17_fimatendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as17_fimatendimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as17_fimatendimento_dia"] !="") ){ 
       $sql  .= $virgula." as17_fimatendimento = '$this->as17_fimatendimento' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as17_fimatendimento_dia"])){ 
         $sql  .= $virgula." as17_fimatendimento = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($as17_sequencial!=null){
       $sql .= " as17_sequencial = $this->as17_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as17_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19948,'$this->as17_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as17_sequencial"]) || $this->as17_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3575,19948,'".AddSlashes(pg_result($resaco,$conresaco,'as17_sequencial'))."','$this->as17_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as17_localatendimentosocial"]) || $this->as17_localatendimentosocial != "")
             $resac = db_query("insert into db_acount values($acount,3575,19949,'".AddSlashes(pg_result($resaco,$conresaco,'as17_localatendimentosocial'))."','$this->as17_localatendimentosocial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as17_cidadao"]) || $this->as17_cidadao != "")
             $resac = db_query("insert into db_acount values($acount,3575,19950,'".AddSlashes(pg_result($resaco,$conresaco,'as17_cidadao'))."','$this->as17_cidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as17_cidadao_seq"]) || $this->as17_cidadao_seq != "")
             $resac = db_query("insert into db_acount values($acount,3575,19951,'".AddSlashes(pg_result($resaco,$conresaco,'as17_cidadao_seq'))."','$this->as17_cidadao_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as17_fimatendimento"]) || $this->as17_fimatendimento != "")
             $resac = db_query("insert into db_acount values($acount,3575,19952,'".AddSlashes(pg_result($resaco,$conresaco,'as17_fimatendimento'))."','$this->as17_fimatendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Local Atendimento Cidadão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Local Atendimento Cidadão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as17_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($as17_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19948,'$as17_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3575,19948,'','".AddSlashes(pg_result($resaco,$iresaco,'as17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3575,19949,'','".AddSlashes(pg_result($resaco,$iresaco,'as17_localatendimentosocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3575,19950,'','".AddSlashes(pg_result($resaco,$iresaco,'as17_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3575,19951,'','".AddSlashes(pg_result($resaco,$iresaco,'as17_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3575,19952,'','".AddSlashes(pg_result($resaco,$iresaco,'as17_fimatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from localatendimentocidadao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as17_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as17_sequencial = $as17_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Local Atendimento Cidadão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Local Atendimento Cidadão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as17_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:localatendimentocidadao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from localatendimentocidadao ";
     $sql .= "      inner join cidadao  on  cidadao.ov02_sequencial = localatendimentocidadao.as17_cidadao and  cidadao.ov02_seq = localatendimentocidadao.as17_cidadao_seq";
     $sql .= "      inner join localatendimentosocial  on  localatendimentosocial.as16_sequencial = localatendimentocidadao.as17_localatendimentosocial";
     $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = localatendimentosocial.as16_db_depart";
     $sql2 = "";
     if($dbwhere==""){
       if($as17_sequencial!=null ){
         $sql2 .= " where localatendimentocidadao.as17_sequencial = $as17_sequencial "; 
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
   function sql_query_file ( $as17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from localatendimentocidadao ";
     $sql2 = "";
     if($dbwhere==""){
       if($as17_sequencial!=null ){
         $sql2 .= " where localatendimentocidadao.as17_sequencial = $as17_sequencial "; 
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
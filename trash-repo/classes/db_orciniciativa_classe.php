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

//MODULO: orcamento
//CLASSE DA ENTIDADE orciniciativa
class cl_orciniciativa { 
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
   var $o147_sequencial = 0; 
   var $o147_descricao = null; 
   var $o147_iniciativa = null; 
   var $o147_orcmeta = 0; 
   var $o147_ano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o147_sequencial = int4 = C�digo da Iniciativa 
                 o147_descricao = varchar(250) = Descri��o 
                 o147_iniciativa = text = Iniciativa 
                 o147_orcmeta = int4 = Meta 
                 o147_ano = int4 = Ano da iniciativa 
                 ";
   //funcao construtor da classe 
   function cl_orciniciativa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orciniciativa"); 
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
       $this->o147_sequencial = ($this->o147_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o147_sequencial"]:$this->o147_sequencial);
       $this->o147_descricao = ($this->o147_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["o147_descricao"]:$this->o147_descricao);
       $this->o147_iniciativa = ($this->o147_iniciativa == ""?@$GLOBALS["HTTP_POST_VARS"]["o147_iniciativa"]:$this->o147_iniciativa);
       $this->o147_orcmeta = ($this->o147_orcmeta == ""?@$GLOBALS["HTTP_POST_VARS"]["o147_orcmeta"]:$this->o147_orcmeta);
       $this->o147_ano = ($this->o147_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o147_ano"]:$this->o147_ano);
     }else{
       $this->o147_sequencial = ($this->o147_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o147_sequencial"]:$this->o147_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o147_sequencial){ 
      $this->atualizacampos();
     if($this->o147_descricao == null ){ 
       $this->erro_sql = " Campo Descri��o n�o informado.";
       $this->erro_campo = "o147_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o147_iniciativa == null ){ 
       $this->erro_sql = " Campo Iniciativa n�o informado.";
       $this->erro_campo = "o147_iniciativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o147_orcmeta == null ){ 
       $this->erro_sql = " Campo Meta n�o informado.";
       $this->erro_campo = "o147_orcmeta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o147_ano == null ){ 
       $this->erro_sql = " Campo Ano da iniciativa n�o informado.";
       $this->erro_campo = "o147_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o147_sequencial == "" || $o147_sequencial == null ){
       $result = db_query("select nextval('orciniciativa_o147_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orciniciativa_o147_sequencial_seq do campo: o147_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o147_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orciniciativa_o147_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o147_sequencial)){
         $this->erro_sql = " Campo o147_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o147_sequencial = $o147_sequencial; 
       }
     }
     if(($this->o147_sequencial == null) || ($this->o147_sequencial == "") ){ 
       $this->erro_sql = " Campo o147_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orciniciativa(
                                       o147_sequencial 
                                      ,o147_descricao 
                                      ,o147_iniciativa 
                                      ,o147_orcmeta 
                                      ,o147_ano 
                       )
                values (
                                $this->o147_sequencial 
                               ,'$this->o147_descricao' 
                               ,'$this->o147_iniciativa' 
                               ,$this->o147_orcmeta 
                               ,$this->o147_ano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Iniciativa ($this->o147_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Iniciativa j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Iniciativa ($this->o147_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o147_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o147_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19890,'$this->o147_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3562,19890,'','".AddSlashes(pg_result($resaco,0,'o147_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3562,19892,'','".AddSlashes(pg_result($resaco,0,'o147_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3562,19891,'','".AddSlashes(pg_result($resaco,0,'o147_iniciativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3562,19901,'','".AddSlashes(pg_result($resaco,0,'o147_orcmeta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3562,20324,'','".AddSlashes(pg_result($resaco,0,'o147_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o147_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orciniciativa set ";
     $virgula = "";
     if(trim($this->o147_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o147_sequencial"])){ 
       $sql  .= $virgula." o147_sequencial = $this->o147_sequencial ";
       $virgula = ",";
       if(trim($this->o147_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo da Iniciativa n�o informado.";
         $this->erro_campo = "o147_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o147_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o147_descricao"])){ 
       $sql  .= $virgula." o147_descricao = '$this->o147_descricao' ";
       $virgula = ",";
       if(trim($this->o147_descricao) == null ){ 
         $this->erro_sql = " Campo Descri��o n�o informado.";
         $this->erro_campo = "o147_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o147_iniciativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o147_iniciativa"])){ 
       $sql  .= $virgula." o147_iniciativa = '$this->o147_iniciativa' ";
       $virgula = ",";
       if(trim($this->o147_iniciativa) == null ){ 
         $this->erro_sql = " Campo Iniciativa n�o informado.";
         $this->erro_campo = "o147_iniciativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o147_orcmeta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o147_orcmeta"])){ 
       $sql  .= $virgula." o147_orcmeta = $this->o147_orcmeta ";
       $virgula = ",";
       if(trim($this->o147_orcmeta) == null ){ 
         $this->erro_sql = " Campo Meta n�o informado.";
         $this->erro_campo = "o147_orcmeta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o147_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o147_ano"])){ 
       $sql  .= $virgula." o147_ano = $this->o147_ano ";
       $virgula = ",";
       if(trim($this->o147_ano) == null ){ 
         $this->erro_sql = " Campo Ano da iniciativa n�o informado.";
         $this->erro_campo = "o147_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o147_sequencial!=null){
       $sql .= " o147_sequencial = $this->o147_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o147_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19890,'$this->o147_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o147_sequencial"]) || $this->o147_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3562,19890,'".AddSlashes(pg_result($resaco,$conresaco,'o147_sequencial'))."','$this->o147_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o147_descricao"]) || $this->o147_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3562,19892,'".AddSlashes(pg_result($resaco,$conresaco,'o147_descricao'))."','$this->o147_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o147_iniciativa"]) || $this->o147_iniciativa != "")
             $resac = db_query("insert into db_acount values($acount,3562,19891,'".AddSlashes(pg_result($resaco,$conresaco,'o147_iniciativa'))."','$this->o147_iniciativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o147_orcmeta"]) || $this->o147_orcmeta != "")
             $resac = db_query("insert into db_acount values($acount,3562,19901,'".AddSlashes(pg_result($resaco,$conresaco,'o147_orcmeta'))."','$this->o147_orcmeta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o147_ano"]) || $this->o147_ano != "")
             $resac = db_query("insert into db_acount values($acount,3562,20324,'".AddSlashes(pg_result($resaco,$conresaco,'o147_ano'))."','$this->o147_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Iniciativa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o147_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Iniciativa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o147_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o147_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o147_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($o147_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19890,'$o147_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3562,19890,'','".AddSlashes(pg_result($resaco,$iresaco,'o147_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3562,19892,'','".AddSlashes(pg_result($resaco,$iresaco,'o147_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3562,19891,'','".AddSlashes(pg_result($resaco,$iresaco,'o147_iniciativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3562,19901,'','".AddSlashes(pg_result($resaco,$iresaco,'o147_orcmeta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3562,20324,'','".AddSlashes(pg_result($resaco,$iresaco,'o147_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from orciniciativa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o147_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o147_sequencial = $o147_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Iniciativa nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o147_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Iniciativa nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o147_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o147_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:orciniciativa";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o147_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orciniciativa ";
     $sql .= "      inner join orcmeta  on  orcmeta.o145_sequencial = orciniciativa.o147_orcmeta";
     $sql .= "      inner join orcobjetivo  on  orcobjetivo.o143_sequencial = orcmeta.o145_orcobjetivo";
     $sql2 = "";
     if($dbwhere==""){
       if($o147_sequencial!=null ){
         $sql2 .= " where orciniciativa.o147_sequencial = $o147_sequencial "; 
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
   function sql_query_file ( $o147_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orciniciativa ";
     $sql2 = "";
     if($dbwhere==""){
       if($o147_sequencial!=null ){
         $sql2 .= " where orciniciativa.o147_sequencial = $o147_sequencial "; 
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
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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_medicosforarede
class cl_sau_medicosforarede { 
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
   var $s154_i_codigo = 0; 
   var $s154_i_medico = 0; 
   var $s154_c_nome = null; 
   var $s154_c_cns = null; 
   var $s154_rhcbo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s154_i_codigo = int4 = C�digo 
                 s154_i_medico = int4 = M�dico 
                 s154_c_nome = varchar(40) = Nome 
                 s154_c_cns = char(15) = CNS 
                 s154_rhcbo = int4 = C�digo cbo 
                 ";
   //funcao construtor da classe 
   function cl_sau_medicosforarede() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_medicosforarede"); 
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
       $this->s154_i_codigo = ($this->s154_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s154_i_codigo"]:$this->s154_i_codigo);
       $this->s154_i_medico = ($this->s154_i_medico == ""?@$GLOBALS["HTTP_POST_VARS"]["s154_i_medico"]:$this->s154_i_medico);
       $this->s154_c_nome = ($this->s154_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["s154_c_nome"]:$this->s154_c_nome);
       $this->s154_c_cns = ($this->s154_c_cns == ""?@$GLOBALS["HTTP_POST_VARS"]["s154_c_cns"]:$this->s154_c_cns);
       $this->s154_rhcbo = ($this->s154_rhcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["s154_rhcbo"]:$this->s154_rhcbo);
     }else{
       $this->s154_i_codigo = ($this->s154_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s154_i_codigo"]:$this->s154_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s154_i_codigo){ 
      $this->atualizacampos();
     if($this->s154_i_medico == null ){ 
       $this->erro_sql = " Campo M�dico n�o informado.";
       $this->erro_campo = "s154_i_medico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s154_c_nome == null ){ 
       $this->erro_sql = " Campo Nome n�o informado.";
       $this->erro_campo = "s154_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s154_rhcbo == null ){ 
       $this->s154_rhcbo = "null";
     }
     if($s154_i_codigo == "" || $s154_i_codigo == null ){
       $result = db_query("select nextval('sau_medicosforarede_s154_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_medicosforarede_s154_i_codigo_seq do campo: s154_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s154_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_medicosforarede_s154_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s154_i_codigo)){
         $this->erro_sql = " Campo s154_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s154_i_codigo = $s154_i_codigo; 
       }
     }
     if(($this->s154_i_codigo == null) || ($this->s154_i_codigo == "") ){ 
       $this->erro_sql = " Campo s154_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_medicosforarede(
                                       s154_i_codigo 
                                      ,s154_i_medico 
                                      ,s154_c_nome 
                                      ,s154_c_cns 
                                      ,s154_rhcbo 
                       )
                values (
                                $this->s154_i_codigo 
                               ,$this->s154_i_medico 
                               ,'$this->s154_c_nome' 
                               ,'$this->s154_c_cns' 
                               ,$this->s154_rhcbo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_medicosforarede ($this->s154_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_medicosforarede j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_medicosforarede ($this->s154_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s154_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s154_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17488,'$this->s154_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3091,17488,'','".AddSlashes(pg_result($resaco,0,'s154_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3091,17490,'','".AddSlashes(pg_result($resaco,0,'s154_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3091,17489,'','".AddSlashes(pg_result($resaco,0,'s154_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3091,17491,'','".AddSlashes(pg_result($resaco,0,'s154_c_cns'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3091,20311,'','".AddSlashes(pg_result($resaco,0,'s154_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s154_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_medicosforarede set ";
     $virgula = "";
     if(trim($this->s154_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s154_i_codigo"])){ 
       $sql  .= $virgula." s154_i_codigo = $this->s154_i_codigo ";
       $virgula = ",";
       if(trim($this->s154_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo n�o informado.";
         $this->erro_campo = "s154_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s154_i_medico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s154_i_medico"])){ 
       $sql  .= $virgula." s154_i_medico = $this->s154_i_medico ";
       $virgula = ",";
       if(trim($this->s154_i_medico) == null ){ 
         $this->erro_sql = " Campo M�dico n�o informado.";
         $this->erro_campo = "s154_i_medico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s154_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s154_c_nome"])){ 
       $sql  .= $virgula." s154_c_nome = '$this->s154_c_nome' ";
       $virgula = ",";
       if(trim($this->s154_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome n�o informado.";
         $this->erro_campo = "s154_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s154_c_cns)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s154_c_cns"])){ 
       $sql  .= $virgula." s154_c_cns = '$this->s154_c_cns' ";
       $virgula = ",";
     }
     if(trim($this->s154_rhcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s154_rhcbo"])){ 
        if(trim($this->s154_rhcbo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["s154_rhcbo"])){ 
           $this->s154_rhcbo = "null" ; 
        } 
       $sql  .= $virgula." s154_rhcbo = $this->s154_rhcbo ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($s154_i_codigo!=null){
       $sql .= " s154_i_codigo = $this->s154_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s154_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17488,'$this->s154_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s154_i_codigo"]) || $this->s154_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3091,17488,'".AddSlashes(pg_result($resaco,$conresaco,'s154_i_codigo'))."','$this->s154_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s154_i_medico"]) || $this->s154_i_medico != "")
             $resac = db_query("insert into db_acount values($acount,3091,17490,'".AddSlashes(pg_result($resaco,$conresaco,'s154_i_medico'))."','$this->s154_i_medico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s154_c_nome"]) || $this->s154_c_nome != "")
             $resac = db_query("insert into db_acount values($acount,3091,17489,'".AddSlashes(pg_result($resaco,$conresaco,'s154_c_nome'))."','$this->s154_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s154_c_cns"]) || $this->s154_c_cns != "")
             $resac = db_query("insert into db_acount values($acount,3091,17491,'".AddSlashes(pg_result($resaco,$conresaco,'s154_c_cns'))."','$this->s154_c_cns',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["s154_rhcbo"]) || $this->s154_rhcbo != "")
             $resac = db_query("insert into db_acount values($acount,3091,20311,'".AddSlashes(pg_result($resaco,$conresaco,'s154_rhcbo'))."','$this->s154_rhcbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_medicosforarede nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s154_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_medicosforarede nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s154_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s154_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s154_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($s154_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,17488,'$s154_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3091,17488,'','".AddSlashes(pg_result($resaco,$iresaco,'s154_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3091,17490,'','".AddSlashes(pg_result($resaco,$iresaco,'s154_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3091,17489,'','".AddSlashes(pg_result($resaco,$iresaco,'s154_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3091,17491,'','".AddSlashes(pg_result($resaco,$iresaco,'s154_c_cns'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3091,20311,'','".AddSlashes(pg_result($resaco,$iresaco,'s154_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sau_medicosforarede
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s154_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s154_i_codigo = $s154_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_medicosforarede nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s154_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_medicosforarede nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s154_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s154_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_medicosforarede";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s154_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_medicosforarede ";
     $sql .= "      left  join rhcbo  on  rhcbo.rh70_sequencial = sau_medicosforarede.s154_rhcbo";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = sau_medicosforarede.s154_i_medico";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($s154_i_codigo!=null ){
         $sql2 .= " where sau_medicosforarede.s154_i_codigo = $s154_i_codigo "; 
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
   function sql_query_file ( $s154_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_medicosforarede ";
     $sql2 = "";
     if($dbwhere==""){
       if($s154_i_codigo!=null ){
         $sql2 .= " where sau_medicosforarede.s154_i_codigo = $s154_i_codigo "; 
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
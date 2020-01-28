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
//CLASSE DA ENTIDADE cursosocialaula
class cl_cursosocialaula { 
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
   var $as21_sequencial = 0; 
   var $as21_cursosocial = 0; 
   var $as21_dataaula_dia = null; 
   var $as21_dataaula_mes = null; 
   var $as21_dataaula_ano = null; 
   var $as21_dataaula = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as21_sequencial = int4 = Código 
                 as21_cursosocial = int4 = Curso Social 
                 as21_dataaula = date = Dia de aula 
                 ";
   //funcao construtor da classe 
   function cl_cursosocialaula() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cursosocialaula"); 
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
       $this->as21_sequencial = ($this->as21_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as21_sequencial"]:$this->as21_sequencial);
       $this->as21_cursosocial = ($this->as21_cursosocial == ""?@$GLOBALS["HTTP_POST_VARS"]["as21_cursosocial"]:$this->as21_cursosocial);
       if($this->as21_dataaula == ""){
         $this->as21_dataaula_dia = ($this->as21_dataaula_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as21_dataaula_dia"]:$this->as21_dataaula_dia);
         $this->as21_dataaula_mes = ($this->as21_dataaula_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as21_dataaula_mes"]:$this->as21_dataaula_mes);
         $this->as21_dataaula_ano = ($this->as21_dataaula_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as21_dataaula_ano"]:$this->as21_dataaula_ano);
         if($this->as21_dataaula_dia != ""){
            $this->as21_dataaula = $this->as21_dataaula_ano."-".$this->as21_dataaula_mes."-".$this->as21_dataaula_dia;
         }
       }
     }else{
       $this->as21_sequencial = ($this->as21_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as21_sequencial"]:$this->as21_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as21_sequencial){ 
      $this->atualizacampos();
     if($this->as21_cursosocial == null ){ 
       $this->erro_sql = " Campo Curso Social nao Informado.";
       $this->erro_campo = "as21_cursosocial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as21_dataaula == null ){ 
       $this->erro_sql = " Campo Dia de aula nao Informado.";
       $this->erro_campo = "as21_dataaula_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as21_sequencial == "" || $as21_sequencial == null ){
       $result = db_query("select nextval('cursosocialaula_as21_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cursosocialaula_as21_sequencial_seq do campo: as21_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as21_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cursosocialaula_as21_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as21_sequencial)){
         $this->erro_sql = " Campo as21_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as21_sequencial = $as21_sequencial; 
       }
     }
     if(($this->as21_sequencial == null) || ($this->as21_sequencial == "") ){ 
       $this->erro_sql = " Campo as21_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cursosocialaula(
                                       as21_sequencial 
                                      ,as21_cursosocial 
                                      ,as21_dataaula 
                       )
                values (
                                $this->as21_sequencial 
                               ,$this->as21_cursosocial 
                               ,".($this->as21_dataaula == "null" || $this->as21_dataaula == ""?"null":"'".$this->as21_dataaula."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dia de aula ($this->as21_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dia de aula já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dia de aula ($this->as21_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as21_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as21_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19973,'$this->as21_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3580,19973,'','".AddSlashes(pg_result($resaco,0,'as21_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3580,19974,'','".AddSlashes(pg_result($resaco,0,'as21_cursosocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3580,19975,'','".AddSlashes(pg_result($resaco,0,'as21_dataaula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as21_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cursosocialaula set ";
     $virgula = "";
     if(trim($this->as21_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as21_sequencial"])){ 
       $sql  .= $virgula." as21_sequencial = $this->as21_sequencial ";
       $virgula = ",";
       if(trim($this->as21_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "as21_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as21_cursosocial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as21_cursosocial"])){ 
       $sql  .= $virgula." as21_cursosocial = $this->as21_cursosocial ";
       $virgula = ",";
       if(trim($this->as21_cursosocial) == null ){ 
         $this->erro_sql = " Campo Curso Social nao Informado.";
         $this->erro_campo = "as21_cursosocial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as21_dataaula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as21_dataaula_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as21_dataaula_dia"] !="") ){ 
       $sql  .= $virgula." as21_dataaula = '$this->as21_dataaula' ";
       $virgula = ",";
       if(trim($this->as21_dataaula) == null ){ 
         $this->erro_sql = " Campo Dia de aula nao Informado.";
         $this->erro_campo = "as21_dataaula_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as21_dataaula_dia"])){ 
         $sql  .= $virgula." as21_dataaula = null ";
         $virgula = ",";
         if(trim($this->as21_dataaula) == null ){ 
           $this->erro_sql = " Campo Dia de aula nao Informado.";
           $this->erro_campo = "as21_dataaula_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($as21_sequencial!=null){
       $sql .= " as21_sequencial = $this->as21_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->as21_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19973,'$this->as21_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as21_sequencial"]) || $this->as21_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3580,19973,'".AddSlashes(pg_result($resaco,$conresaco,'as21_sequencial'))."','$this->as21_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as21_cursosocial"]) || $this->as21_cursosocial != "")
             $resac = db_query("insert into db_acount values($acount,3580,19974,'".AddSlashes(pg_result($resaco,$conresaco,'as21_cursosocial'))."','$this->as21_cursosocial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as21_dataaula"]) || $this->as21_dataaula != "")
             $resac = db_query("insert into db_acount values($acount,3580,19975,'".AddSlashes(pg_result($resaco,$conresaco,'as21_dataaula'))."','$this->as21_dataaula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dia de aula nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as21_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dia de aula nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as21_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($as21_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19973,'$as21_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3580,19973,'','".AddSlashes(pg_result($resaco,$iresaco,'as21_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3580,19974,'','".AddSlashes(pg_result($resaco,$iresaco,'as21_cursosocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3580,19975,'','".AddSlashes(pg_result($resaco,$iresaco,'as21_dataaula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cursosocialaula
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as21_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as21_sequencial = $as21_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dia de aula nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as21_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dia de aula nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as21_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cursosocialaula";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as21_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursosocialaula ";
     $sql .= "      inner join cursosocial  on  cursosocial.as19_sequencial = cursosocialaula.as21_cursosocial";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cursosocial.as19_ministrante";
     $sql .= "      inner join tabcurritipo  on  tabcurritipo.h02_codigo = cursosocial.as19_tabcurritipo";
     $sql2 = "";
     if($dbwhere==""){
       if($as21_sequencial!=null ){
         $sql2 .= " where cursosocialaula.as21_sequencial = $as21_sequencial "; 
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
   function sql_query_file ( $as21_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursosocialaula ";
     $sql2 = "";
     if($dbwhere==""){
       if($as21_sequencial!=null ){
         $sql2 .= " where cursosocialaula.as21_sequencial = $as21_sequencial "; 
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
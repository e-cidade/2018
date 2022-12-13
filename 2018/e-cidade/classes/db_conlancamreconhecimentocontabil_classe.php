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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancamreconhecimentocontabil
class cl_conlancamreconhecimentocontabil { 
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
   var $c113_sequencial = 0; 
   var $c113_reconhecimentocontabil = 0; 
   var $c113_codlan = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c113_sequencial = int4 = sequencial 
                 c113_reconhecimentocontabil = int4 = Reconhecimento Contabil 
                 c113_codlan = int4 = C�digo Lan�amento 
                 ";
   //funcao construtor da classe 
   function cl_conlancamreconhecimentocontabil() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancamreconhecimentocontabil"); 
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
       $this->c113_sequencial = ($this->c113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c113_sequencial"]:$this->c113_sequencial);
       $this->c113_reconhecimentocontabil = ($this->c113_reconhecimentocontabil == ""?@$GLOBALS["HTTP_POST_VARS"]["c113_reconhecimentocontabil"]:$this->c113_reconhecimentocontabil);
       $this->c113_codlan = ($this->c113_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c113_codlan"]:$this->c113_codlan);
     }else{
       $this->c113_sequencial = ($this->c113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c113_sequencial"]:$this->c113_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c113_sequencial){ 
      $this->atualizacampos();
     if($this->c113_reconhecimentocontabil == null ){ 
       $this->erro_sql = " Campo Reconhecimento Contabil n�o informado.";
       $this->erro_campo = "c113_reconhecimentocontabil";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c113_codlan == null ){ 
       $this->erro_sql = " Campo C�digo Lan�amento n�o informado.";
       $this->erro_campo = "c113_codlan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c113_sequencial == "" || $c113_sequencial == null ){
       $result = db_query("select nextval('conlancamreconhecimentocontabil_c113_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conlancamreconhecimentocontabil_c113_sequencial_seq do campo: c113_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c113_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conlancamreconhecimentocontabil_c113_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c113_sequencial)){
         $this->erro_sql = " Campo c113_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c113_sequencial = $c113_sequencial; 
       }
     }
     if(($this->c113_sequencial == null) || ($this->c113_sequencial == "") ){ 
       $this->erro_sql = " Campo c113_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamreconhecimentocontabil(
                                       c113_sequencial 
                                      ,c113_reconhecimentocontabil 
                                      ,c113_codlan 
                       )
                values (
                                $this->c113_sequencial 
                               ,$this->c113_reconhecimentocontabil 
                               ,$this->c113_codlan 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligacao com reconheciemtento contabil ($this->c113_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligacao com reconheciemtento contabil j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligacao com reconheciemtento contabil ($this->c113_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c113_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c113_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20207,'$this->c113_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3628,20207,'','".AddSlashes(pg_result($resaco,0,'c113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3628,20209,'','".AddSlashes(pg_result($resaco,0,'c113_reconhecimentocontabil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3628,20208,'','".AddSlashes(pg_result($resaco,0,'c113_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c113_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update conlancamreconhecimentocontabil set ";
     $virgula = "";
     if(trim($this->c113_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c113_sequencial"])){ 
       $sql  .= $virgula." c113_sequencial = $this->c113_sequencial ";
       $virgula = ",";
       if(trim($this->c113_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial n�o informado.";
         $this->erro_campo = "c113_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c113_reconhecimentocontabil)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c113_reconhecimentocontabil"])){ 
       $sql  .= $virgula." c113_reconhecimentocontabil = $this->c113_reconhecimentocontabil ";
       $virgula = ",";
       if(trim($this->c113_reconhecimentocontabil) == null ){ 
         $this->erro_sql = " Campo Reconhecimento Contabil n�o informado.";
         $this->erro_campo = "c113_reconhecimentocontabil";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c113_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c113_codlan"])){ 
       $sql  .= $virgula." c113_codlan = $this->c113_codlan ";
       $virgula = ",";
       if(trim($this->c113_codlan) == null ){ 
         $this->erro_sql = " Campo C�digo Lan�amento n�o informado.";
         $this->erro_campo = "c113_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c113_sequencial!=null){
       $sql .= " c113_sequencial = $this->c113_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c113_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20207,'$this->c113_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c113_sequencial"]) || $this->c113_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3628,20207,'".AddSlashes(pg_result($resaco,$conresaco,'c113_sequencial'))."','$this->c113_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c113_reconhecimentocontabil"]) || $this->c113_reconhecimentocontabil != "")
             $resac = db_query("insert into db_acount values($acount,3628,20209,'".AddSlashes(pg_result($resaco,$conresaco,'c113_reconhecimentocontabil'))."','$this->c113_reconhecimentocontabil',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c113_codlan"]) || $this->c113_codlan != "")
             $resac = db_query("insert into db_acount values($acount,3628,20208,'".AddSlashes(pg_result($resaco,$conresaco,'c113_codlan'))."','$this->c113_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligacao com reconheciemtento contabil nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c113_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligacao com reconheciemtento contabil nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c113_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c113_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c113_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($c113_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20207,'$c113_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3628,20207,'','".AddSlashes(pg_result($resaco,$iresaco,'c113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3628,20209,'','".AddSlashes(pg_result($resaco,$iresaco,'c113_reconhecimentocontabil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3628,20208,'','".AddSlashes(pg_result($resaco,$iresaco,'c113_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from conlancamreconhecimentocontabil
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c113_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c113_sequencial = $c113_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligacao com reconheciemtento contabil nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c113_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligacao com reconheciemtento contabil nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c113_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c113_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancamreconhecimentocontabil";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancamreconhecimentocontabil ";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamreconhecimentocontabil.c113_codlan";
     $sql .= "      inner join reconhecimentocontabil  on  reconhecimentocontabil.c112_sequencial = conlancamreconhecimentocontabil.c113_reconhecimentocontabil";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = reconhecimentocontabil.c112_numcgm";
     $sql .= "      inner join reconhecimentocontabiltipo  on  reconhecimentocontabiltipo.c111_sequencial = reconhecimentocontabil.c112_reconhecimentocontabiltipo";
     $sql2 = "";
     if($dbwhere==""){
       if($c113_sequencial!=null ){
         $sql2 .= " where conlancamreconhecimentocontabil.c113_sequencial = $c113_sequencial "; 
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
   function sql_query_file ( $c113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancamreconhecimentocontabil ";
     $sql2 = "";
     if($dbwhere==""){
       if($c113_sequencial!=null ){
         $sql2 .= " where conlancamreconhecimentocontabil.c113_sequencial = $c113_sequencial "; 
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
  function sql_query_dadoslancamento ( $c113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from conlancamreconhecimentocontabil ";
  	$sql .= "      inner join conlancam  								  on  conlancam.c70_codlan                       = conlancamreconhecimentocontabil.c113_codlan ";
  	$sql .= "      inner join reconhecimentocontabil  		on  reconhecimentocontabil.c112_sequencial     = conlancamreconhecimentocontabil.c113_reconhecimentocontabil ";
  	$sql .= "      inner join reconhecimentocontabiltipo  on  reconhecimentocontabiltipo.c111_sequencial = reconhecimentocontabil.c112_reconhecimentocontabiltipo ";
  	$sql .= "      inner join conlancamcompl     					on  conlancam.c70_codlan                       = conlancamcompl.c72_codlan ";
  	$sql2 = "";
  	
  	if($dbwhere==""){
  		if($c113_sequencial!=null ){
  			$sql2 .= " where conlancamreconhecimentocontabil.c113_sequencial = $c113_sequencial ";
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
  // $sql .= "      inner join conlancamemp    on  conlancam.c70_codlan      = conlancamemp.c75_codlan ";
}
?>
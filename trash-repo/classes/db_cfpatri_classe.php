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

//MODULO: patrimonio
//CLASSE DA ENTIDADE cfpatri
class cl_cfpatri { 
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
   var $t06_codcla = 0; 
   var $t06_pesqorgao = 'f'; 
   var $t06_bensmodeloetiqueta = 0; 
   var $t06_controlaplacainstituicao = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t06_codcla = int4 = Código 
                 t06_pesqorgao = bool = Utiliza Pesquisa por Órgão 
                 t06_bensmodeloetiqueta = int4 = Modelo da Etiqueta 
                 t06_controlaplacainstituicao = bool = Contolar placa por instituição 
                 ";
   //funcao construtor da classe 
   function cl_cfpatri() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cfpatri"); 
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
       $this->t06_codcla = ($this->t06_codcla == ""?@$GLOBALS["HTTP_POST_VARS"]["t06_codcla"]:$this->t06_codcla);
       $this->t06_pesqorgao = ($this->t06_pesqorgao == "f"?@$GLOBALS["HTTP_POST_VARS"]["t06_pesqorgao"]:$this->t06_pesqorgao);
       $this->t06_bensmodeloetiqueta = ($this->t06_bensmodeloetiqueta == ""?@$GLOBALS["HTTP_POST_VARS"]["t06_bensmodeloetiqueta"]:$this->t06_bensmodeloetiqueta);
       $this->t06_controlaplacainstituicao = ($this->t06_controlaplacainstituicao == "f"?@$GLOBALS["HTTP_POST_VARS"]["t06_controlaplacainstituicao"]:$this->t06_controlaplacainstituicao);
     }else{
       $this->t06_codcla = ($this->t06_codcla == ""?@$GLOBALS["HTTP_POST_VARS"]["t06_codcla"]:$this->t06_codcla);
     }
   }
   // funcao para inclusao
   function incluir ($t06_codcla){ 
      $this->atualizacampos();
     if($this->t06_pesqorgao == null ){ 
       $this->erro_sql = " Campo Utiliza Pesquisa por Órgão não informado.";
       $this->erro_campo = "t06_pesqorgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t06_bensmodeloetiqueta == null ){ 
       $this->t06_bensmodeloetiqueta = "NULL";
     }
     if($this->t06_controlaplacainstituicao == null ){ 
       $this->erro_sql = " Campo Contolar placa por instituição não informado.";
       $this->erro_campo = "t06_controlaplacainstituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->t06_codcla = $t06_codcla; 
     if(($this->t06_codcla == null) || ($this->t06_codcla == "") ){ 
       $this->erro_sql = " Campo t06_codcla nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cfpatri(
                                       t06_codcla 
                                      ,t06_pesqorgao 
                                      ,t06_bensmodeloetiqueta 
                                      ,t06_controlaplacainstituicao 
                       )
                values (
                                $this->t06_codcla 
                               ,'$this->t06_pesqorgao' 
                               ,$this->t06_bensmodeloetiqueta 
                               ,'$this->t06_controlaplacainstituicao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configuracao de parametros ($this->t06_codcla) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configuracao de parametros já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configuracao de parametros ($this->t06_codcla) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t06_codcla;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->t06_codcla  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5822,'$this->t06_codcla','I')");
         $resac = db_query("insert into db_acount values($acount,433,5822,'','".AddSlashes(pg_result($resaco,0,'t06_codcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,433,14485,'','".AddSlashes(pg_result($resaco,0,'t06_pesqorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,433,15560,'','".AddSlashes(pg_result($resaco,0,'t06_bensmodeloetiqueta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,433,20193,'','".AddSlashes(pg_result($resaco,0,'t06_controlaplacainstituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t06_codcla=null) { 
      $this->atualizacampos();
     $sql = " update cfpatri set ";
     $virgula = "";
     if(trim($this->t06_codcla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t06_codcla"])){ 
       $sql  .= $virgula." t06_codcla = $this->t06_codcla ";
       $virgula = ",";
       if(trim($this->t06_codcla) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "t06_codcla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t06_pesqorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t06_pesqorgao"])){ 
       $sql  .= $virgula." t06_pesqorgao = '$this->t06_pesqorgao' ";
       $virgula = ",";
       if(trim($this->t06_pesqorgao) == null ){ 
         $this->erro_sql = " Campo Utiliza Pesquisa por Órgão não informado.";
         $this->erro_campo = "t06_pesqorgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t06_bensmodeloetiqueta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t06_bensmodeloetiqueta"])){ 
        if(trim($this->t06_bensmodeloetiqueta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t06_bensmodeloetiqueta"])){ 
           $this->t06_bensmodeloetiqueta = "0" ; 
        } 
       $sql  .= $virgula." t06_bensmodeloetiqueta = $this->t06_bensmodeloetiqueta ";
       $virgula = ",";
     }
     if(trim($this->t06_controlaplacainstituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t06_controlaplacainstituicao"])){ 
       $sql  .= $virgula." t06_controlaplacainstituicao = '$this->t06_controlaplacainstituicao' ";
       $virgula = ",";
       if(trim($this->t06_controlaplacainstituicao) == null ){ 
         $this->erro_sql = " Campo Contolar placa por instituição não informado.";
         $this->erro_campo = "t06_controlaplacainstituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t06_codcla!=null){
       $sql .= " t06_codcla = $this->t06_codcla";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->t06_codcla));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5822,'$this->t06_codcla','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["t06_codcla"]) || $this->t06_codcla != "")
             $resac = db_query("insert into db_acount values($acount,433,5822,'".AddSlashes(pg_result($resaco,$conresaco,'t06_codcla'))."','$this->t06_codcla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["t06_pesqorgao"]) || $this->t06_pesqorgao != "")
             $resac = db_query("insert into db_acount values($acount,433,14485,'".AddSlashes(pg_result($resaco,$conresaco,'t06_pesqorgao'))."','$this->t06_pesqorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["t06_bensmodeloetiqueta"]) || $this->t06_bensmodeloetiqueta != "")
             $resac = db_query("insert into db_acount values($acount,433,15560,'".AddSlashes(pg_result($resaco,$conresaco,'t06_bensmodeloetiqueta'))."','$this->t06_bensmodeloetiqueta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["t06_controlaplacainstituicao"]) || $this->t06_controlaplacainstituicao != "")
             $resac = db_query("insert into db_acount values($acount,433,20193,'".AddSlashes(pg_result($resaco,$conresaco,'t06_controlaplacainstituicao'))."','$this->t06_controlaplacainstituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuracao de parametros nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t06_codcla;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuracao de parametros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t06_codcla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t06_codcla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t06_codcla=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($t06_codcla));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,5822,'$t06_codcla','E')");
           $resac  = db_query("insert into db_acount values($acount,433,5822,'','".AddSlashes(pg_result($resaco,$iresaco,'t06_codcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,433,14485,'','".AddSlashes(pg_result($resaco,$iresaco,'t06_pesqorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,433,15560,'','".AddSlashes(pg_result($resaco,$iresaco,'t06_bensmodeloetiqueta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,433,20193,'','".AddSlashes(pg_result($resaco,$iresaco,'t06_controlaplacainstituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cfpatri
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t06_codcla != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t06_codcla = $t06_codcla ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuracao de parametros nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t06_codcla;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuracao de parametros nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t06_codcla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t06_codcla;
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
        $this->erro_sql   = "Record Vazio na Tabela:cfpatri";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t06_codcla=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfpatri ";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = cfpatri.t06_codcla";
     $sql .= "      left  join bensmodeloetiqueta  on  bensmodeloetiqueta.t71_sequencial = cfpatri.t06_bensmodeloetiqueta";
     $sql2 = "";
     if($dbwhere==""){
       if($t06_codcla!=null ){
         $sql2 .= " where cfpatri.t06_codcla = $t06_codcla "; 
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
   function sql_query_file ( $t06_codcla=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfpatri ";
     $sql2 = "";
     if($dbwhere==""){
       if($t06_codcla!=null ){
         $sql2 .= " where cfpatri.t06_codcla = $t06_codcla "; 
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
  // funcao para alteracao de parametro sem where
  function alterar_parametro ($t06_codcla=null) {
    $this->atualizacampos();
    $sql = " update cfpatri set ";
    $virgula = "";
    if(trim($this->t06_codcla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t06_codcla"])){
      $sql  .= $virgula." t06_codcla = $this->t06_codcla ";
      $virgula = ",";
      if(trim($this->t06_codcla) == null ){
        $this->erro_sql = " Campo Código nao Informado.";
        $this->erro_campo = "t06_codcla";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    //$sql .= " where ";
    if($t06_codcla!=null){
      $sql .= " where t06_codcla = $this->t06_codcla";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->t06_codcla));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,5822,'$this->t06_codcla','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t06_codcla"]))
          $resac = db_query("insert into db_acount values($acount,433,5822,'".AddSlashes(pg_result($resaco,$conresaco,'t06_codcla'))."','$this->t06_codcla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Configuracao de parametros nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->t06_codcla;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Configuracao de parametros nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->t06_codcla;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->t06_codcla;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  
   //funcao para alteracao
function alterarModeloEtiquetaNulo ($t06_codcla=null) { 
      $this->atualizacampos();
     $sql = " update cfpatri set ";
     $virgula = "";
     if(trim($this->t06_codcla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t06_codcla"])){ 
       $sql  .= $virgula." t06_codcla = $this->t06_codcla ";
       $virgula = ",";
       if(trim($this->t06_codcla) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "t06_codcla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t06_pesqorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t06_pesqorgao"])){ 
       $sql  .= $virgula." t06_pesqorgao = '$this->t06_pesqorgao' ";
       $virgula = ",";
       if(trim($this->t06_pesqorgao) == null ){ 
         $this->erro_sql = " Campo Utiliza Pesquisa por Órgão nao Informado.";
         $this->erro_campo = "t06_pesqorgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t06_bensmodeloetiqueta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t06_bensmodeloetiqueta"])){ 
        if(trim($this->t06_bensmodeloetiqueta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t06_bensmodeloetiqueta"])){ 
           $this->t06_bensmodeloetiqueta = "NULL" ; 
        } 
//     if($this->t06_bensmodeloetiqueta == null ){ 
//       $this->t06_bensmodeloetiqueta = "NULL";
//     }
       $sql  .= $virgula." t06_bensmodeloetiqueta = $this->t06_bensmodeloetiqueta ";
       $virgula = ",";
     }
     
     if(trim($this->t06_controlaplacainstituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t06_controlaplacainstituicao"])){
       $sql  .= $virgula." t06_controlaplacainstituicao = '$this->t06_controlaplacainstituicao' ";
       $virgula = ",";
       if(trim($this->t06_controlaplacainstituicao) == null ){
         $this->erro_sql = " Campo Contolar placa por instituição não informado.";
         $this->erro_campo = "t06_controlaplacainstituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     
     $sql .= " where ";
     if($t06_codcla!=null){
       $sql .= " t06_codcla = $this->t06_codcla";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t06_codcla));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5822,'$this->t06_codcla','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t06_codcla"]) || $this->t06_codcla != "")
           $resac = db_query("insert into db_acount values($acount,433,5822,'".AddSlashes(pg_result($resaco,$conresaco,'t06_codcla'))."','$this->t06_codcla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t06_pesqorgao"]) || $this->t06_pesqorgao != "")
           $resac = db_query("insert into db_acount values($acount,433,14485,'".AddSlashes(pg_result($resaco,$conresaco,'t06_pesqorgao'))."','$this->t06_pesqorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t06_bensmodeloetiqueta"]) || $this->t06_bensmodeloetiqueta != "")
           $resac = db_query("insert into db_acount values($acount,433,15560,'".AddSlashes(pg_result($resaco,$conresaco,'t06_bensmodeloetiqueta'))."','$this->t06_bensmodeloetiqueta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuracao de parametros nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t06_codcla;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuracao de parametros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t06_codcla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t06_codcla;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   }
   


}
?>
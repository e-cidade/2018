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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhempenhofolhaexcecaoregra
class cl_rhempenhofolhaexcecaoregra { 
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
   var $rh128_sequencial = 0; 
   var $rh128_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh128_sequencial = int4 = Sequencial 
                 rh128_descricao = varchar(100) = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_rhempenhofolhaexcecaoregra() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhempenhofolhaexcecaoregra"); 
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
       $this->rh128_sequencial = ($this->rh128_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh128_sequencial"]:$this->rh128_sequencial);
       $this->rh128_descricao = ($this->rh128_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh128_descricao"]:$this->rh128_descricao);
     }else{
       $this->rh128_sequencial = ($this->rh128_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh128_sequencial"]:$this->rh128_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh128_sequencial){ 
      $this->atualizacampos();
     if($this->rh128_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "rh128_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh128_sequencial == "" || $rh128_sequencial == null ){
       $result = db_query("select nextval('rhempenhofolhaexcecaoregra_rh128_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhempenhofolhaexcecaoregra_rh128_sequencial_seq do campo: rh128_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh128_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhempenhofolhaexcecaoregra_rh128_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh128_sequencial)){
         $this->erro_sql = " Campo rh128_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh128_sequencial = $rh128_sequencial; 
       }
     }
     if(($this->rh128_sequencial == null) || ($this->rh128_sequencial == "") ){ 
       $this->erro_sql = " Campo rh128_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhempenhofolhaexcecaoregra(
                                       rh128_sequencial 
                                      ,rh128_descricao 
                       )
                values (
                                $this->rh128_sequencial 
                               ,'$this->rh128_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Exceção Regra ($this->rh128_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Exceção Regra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Exceção Regra ($this->rh128_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh128_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh128_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20341,'$this->rh128_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3655,20341,'','".AddSlashes(pg_result($resaco,0,'rh128_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3655,20338,'','".AddSlashes(pg_result($resaco,0,'rh128_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh128_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhempenhofolhaexcecaoregra set ";
     $virgula = "";
     if(trim($this->rh128_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh128_sequencial"])){ 
       $sql  .= $virgula." rh128_sequencial = $this->rh128_sequencial ";
       $virgula = ",";
       if(trim($this->rh128_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh128_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh128_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh128_descricao"])){ 
       $sql  .= $virgula." rh128_descricao = '$this->rh128_descricao' ";
       $virgula = ",";
       if(trim($this->rh128_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "rh128_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh128_sequencial!=null){
       $sql .= " rh128_sequencial = $this->rh128_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh128_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20341,'$this->rh128_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh128_sequencial"]) || $this->rh128_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3655,20341,'".AddSlashes(pg_result($resaco,$conresaco,'rh128_sequencial'))."','$this->rh128_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh128_descricao"]) || $this->rh128_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3655,20338,'".AddSlashes(pg_result($resaco,$conresaco,'rh128_descricao'))."','$this->rh128_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exceção Regra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh128_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Exceção Regra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh128_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh128_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh128_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh128_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20341,'$rh128_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3655,20341,'','".AddSlashes(pg_result($resaco,$iresaco,'rh128_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3655,20338,'','".AddSlashes(pg_result($resaco,$iresaco,'rh128_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhempenhofolhaexcecaoregra
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh128_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh128_sequencial = $rh128_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exceção Regra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh128_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Exceção Regra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh128_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh128_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhempenhofolhaexcecaoregra";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh128_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolhaexcecaoregra ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh128_sequencial!=null ){
         $sql2 .= " where rhempenhofolhaexcecaoregra.rh128_sequencial = $rh128_sequencial "; 
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
   function sql_query_file ( $rh128_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolhaexcecaoregra ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh128_sequencial!=null ){
         $sql2 .= " where rhempenhofolhaexcecaoregra.rh128_sequencial = $rh128_sequencial "; 
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

  function sql_query_regras($sCampos, $iAnoUsu, $sWhere = null) {

    $sSql  = "select $sCampos                                                                                      ";
    $sSql .= "  from rhempenhofolhaexcecaoregra                                                                    ";
    $sSql .= "       inner join rhempenhofolhaexcecaorubrica on rh128_sequencial = rh74_rhempenhofolhaexcecaoregra ";
    $sSql .= " where rh74_anousu = $iAnoUsu                                                                        ";
    if ($sWhere){
      $sSql .= " and $sWhere                                                                                       ";  
    }

    return $sSql;
  }

  function sql_query_dados_regra($iExcecaoRegra) {

    $sCampos  = "rh74_orgao, o40_descr, rh74_unidade, o41_descr, rh74_projativ, o55_descr, rh74_recurso, o15_descr,           ";
    $sCampos .= "rh74_programa, o54_descr, rh74_funcao, o52_descr, rh74_subfuncao, o53_descr, rh74_concarpeculiar, c58_descr, ";
    $sCampos .= "rh74_codele, o56_descr, rh74_tipofolha";

    $sSql  = "select $sCampos";
    $sSql .= "  from rhempenhofolhaexcecaoregra";
    $sSql .= "       inner join rhempenhofolhaexcecaorubrica on rh128_sequencial = rh74_rhempenhofolhaexcecaoregra";
    $sSql .= "       left  join orctiporec  on  orctiporec.o15_codigo = rhempenhofolhaexcecaorubrica.rh74_recurso";
    $sSql .= "       left  join orcfuncao  on  orcfuncao.o52_funcao = rhempenhofolhaexcecaorubrica.rh74_funcao";
    $sSql .= "       left  join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = rhempenhofolhaexcecaorubrica.rh74_subfuncao";
    $sSql .= "       left  join orcprograma  on  orcprograma.o54_anousu = rhempenhofolhaexcecaorubrica.rh74_anousu and  orcprograma.o54_programa = rhempenhofolhaexcecaorubrica.rh74_programa";
    $sSql .= "       left  join orcelemento  on  orcelemento.o56_codele = rhempenhofolhaexcecaorubrica.rh74_codele and  orcelemento.o56_anousu = rhempenhofolhaexcecaorubrica.rh74_anousu";
    $sSql .= "       left  join orcprojativ  on  orcprojativ.o55_anousu = rhempenhofolhaexcecaorubrica.rh74_anousu and  orcprojativ.o55_projativ = rhempenhofolhaexcecaorubrica.rh74_projativ";
    $sSql .= "       left  join orcunidade  on  orcunidade.o41_anousu = rhempenhofolhaexcecaorubrica.rh74_anousu and  orcunidade.o41_orgao = rhempenhofolhaexcecaorubrica.rh74_orgao and  orcunidade.o41_unidade = rhempenhofolhaexcecaorubrica.rh74_unidade";
    $sSql .= "       inner join rhrubricas  on  rhrubricas.rh27_rubric = rhempenhofolhaexcecaorubrica.rh74_rubric and  rhrubricas.rh27_instit = rhempenhofolhaexcecaorubrica.rh74_instit";
    $sSql .= "       left  join concarpeculiar  on  concarpeculiar.c58_sequencial = rhempenhofolhaexcecaorubrica.rh74_concarpeculiar";
    $sSql .= "       left  join db_estruturavalor  on  db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
    $sSql .= "       left  join db_config  on  db_config.codigo = orcprojativ.o55_instit";
    $sSql .= "       left  join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
    $sSql .= "       left  join db_config  as a on   a.codigo = orcunidade.o41_instit";
    $sSql .= "       left  join orcorgao  on  orcorgao.o40_anousu = orcunidade.o41_anousu and  orcorgao.o40_orgao = orcunidade.o41_orgao";
    $sSql .= "       inner join db_config  as b on   b.codigo = rhrubricas.rh27_instit";
    $sSql .= "       inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
    $sSql .= "       left  join db_estruturavalor  as c on   c.db121_sequencial = concarpeculiar.c58_db_estruturavalor";
    $sSql .= "       left  join concarpeculiarclassificacao  on  concarpeculiarclassificacao.c09_sequencial = concarpeculiar.c58_tipo";
    $sSql .= " where rh128_sequencial = $iExcecaoRegra";

    return $sSql;
  }
}
?>
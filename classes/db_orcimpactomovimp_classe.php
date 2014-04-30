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
//CLASSE DA ENTIDADE orcimpactomovimp
class cl_orcimpactomovimp { 
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
   var $o68_codimpmov = 0; 
   var $o68_codimp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o68_codimpmov = int8 = Código 
                 o68_codimp = int4 = Código 
                 ";
   //funcao construtor da classe 
   function cl_orcimpactomovimp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpactomovimp"); 
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
       $this->o68_codimpmov = ($this->o68_codimpmov == ""?@$GLOBALS["HTTP_POST_VARS"]["o68_codimpmov"]:$this->o68_codimpmov);
       $this->o68_codimp = ($this->o68_codimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o68_codimp"]:$this->o68_codimp);
     }else{
       $this->o68_codimpmov = ($this->o68_codimpmov == ""?@$GLOBALS["HTTP_POST_VARS"]["o68_codimpmov"]:$this->o68_codimpmov);
       $this->o68_codimp = ($this->o68_codimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o68_codimp"]:$this->o68_codimp);
     }
   }
   // funcao para inclusao
   function incluir ($o68_codimpmov,$o68_codimp){ 
      $this->atualizacampos();
       $this->o68_codimpmov = $o68_codimpmov; 
       $this->o68_codimp = $o68_codimp; 
     if(($this->o68_codimpmov == null) || ($this->o68_codimpmov == "") ){ 
       $this->erro_sql = " Campo o68_codimpmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o68_codimp == null) || ($this->o68_codimp == "") ){ 
       $this->erro_sql = " Campo o68_codimp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpactomovimp(
                                       o68_codimpmov 
                                      ,o68_codimp 
                       )
                values (
                                $this->o68_codimpmov 
                               ,$this->o68_codimp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de ligação entre impactos ($this->o68_codimpmov."-".$this->o68_codimp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de ligação entre impactos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de ligação entre impactos ($this->o68_codimpmov."-".$this->o68_codimp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o68_codimpmov."-".$this->o68_codimp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o68_codimpmov,$this->o68_codimp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6693,'$this->o68_codimpmov','I')");
       $resac = db_query("insert into db_acountkey values($acount,6694,'$this->o68_codimp','I')");
       $resac = db_query("insert into db_acount values($acount,1100,6693,'','".AddSlashes(pg_result($resaco,0,'o68_codimpmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1100,6694,'','".AddSlashes(pg_result($resaco,0,'o68_codimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o68_codimpmov=null,$o68_codimp=null) { 
      $this->atualizacampos();
     $sql = " update orcimpactomovimp set ";
     $virgula = "";
     if(trim($this->o68_codimpmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o68_codimpmov"])){ 
       $sql  .= $virgula." o68_codimpmov = $this->o68_codimpmov ";
       $virgula = ",";
       if(trim($this->o68_codimpmov) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o68_codimpmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o68_codimp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o68_codimp"])){ 
       $sql  .= $virgula." o68_codimp = $this->o68_codimp ";
       $virgula = ",";
       if(trim($this->o68_codimp) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o68_codimp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o68_codimpmov!=null){
       $sql .= " o68_codimpmov = $this->o68_codimpmov";
     }
     if($o68_codimp!=null){
       $sql .= " and  o68_codimp = $this->o68_codimp";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o68_codimpmov,$this->o68_codimp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6693,'$this->o68_codimpmov','A')");
         $resac = db_query("insert into db_acountkey values($acount,6694,'$this->o68_codimp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o68_codimpmov"]))
           $resac = db_query("insert into db_acount values($acount,1100,6693,'".AddSlashes(pg_result($resaco,$conresaco,'o68_codimpmov'))."','$this->o68_codimpmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o68_codimp"]))
           $resac = db_query("insert into db_acount values($acount,1100,6694,'".AddSlashes(pg_result($resaco,$conresaco,'o68_codimp'))."','$this->o68_codimp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de ligação entre impactos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o68_codimpmov."-".$this->o68_codimp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de ligação entre impactos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o68_codimpmov."-".$this->o68_codimp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o68_codimpmov."-".$this->o68_codimp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o68_codimpmov=null,$o68_codimp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o68_codimpmov,$o68_codimp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6693,'$o68_codimpmov','E')");
         $resac = db_query("insert into db_acountkey values($acount,6694,'$o68_codimp','E')");
         $resac = db_query("insert into db_acount values($acount,1100,6693,'','".AddSlashes(pg_result($resaco,$iresaco,'o68_codimpmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1100,6694,'','".AddSlashes(pg_result($resaco,$iresaco,'o68_codimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpactomovimp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o68_codimpmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o68_codimpmov = $o68_codimpmov ";
        }
        if($o68_codimp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o68_codimp = $o68_codimp ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de ligação entre impactos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o68_codimpmov."-".$o68_codimp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de ligação entre impactos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o68_codimpmov."-".$o68_codimp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o68_codimpmov."-".$o68_codimp;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpactomovimp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o68_codimpmov=null,$o68_codimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactomovimp ";
     $sql .= "      inner join orcimpacto  on  orcimpacto.o90_codimp = orcimpactomovimp.o68_codimp";
     $sql .= "      inner join orcimpactomov  on  orcimpactomov.o63_codimpmov = orcimpactomovimp.o68_codimpmov";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcimpacto.o90_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcimpacto.o90_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcimpacto.o90_anoexe and  orcprograma.o54_programa = orcimpacto.o90_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcimpacto.o90_anoexe and  orcprojativ.o55_projativ = orcimpacto.o90_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcimpacto.o90_anoexe and  orcorgao.o40_orgao = orcimpacto.o90_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcimpacto.o90_anoexe and  orcunidade.o41_orgao = orcimpacto.o90_orgao and  orcunidade.o41_unidade = orcimpacto.o90_unidade";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcimpacto.o90_produto";
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpacto.o90_codperiodo";
     $sql .= "      inner join orcfuncao  as a on   a.o52_funcao = orcimpactomov.o63_funcao";
     $sql .= "      inner join orcsubfuncao  as b on   b.o53_subfuncao = orcimpactomov.o63_subfuncao";
     $sql .= "      inner join orcprograma  as c on   c.o54_anousu = orcimpactomov.o63_anoexe and   c.o54_programa = orcimpactomov.o63_programa";
     $sql .= "      inner join orcprojativ  as d on   d.o55_anousu = orcimpactomov.o63_anoexe and   d.o55_projativ = orcimpactomov.o63_acao";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcimpactomov.o63_anoexe and   d.o40_orgao = orcimpactomov.o63_orgao";
     $sql .= "      inner join orcunidade  as d on   d.o41_anousu = orcimpactomov.o63_anoexe and   d.o41_orgao = orcimpactomov.o63_orgao and   d.o41_unidade = orcimpactomov.o63_unidade";
     $sql .= "      inner join orcproduto  as d on   d.o22_codproduto = orcimpactomov.o63_produto";
     $sql .= "      inner join orcimpactoperiodo  as d on   d.o96_codperiodo = orcimpactomov.o63_codperiodo";
     $sql2 = "";
     if($dbwhere==""){
       if($o68_codimpmov!=null ){
         $sql2 .= " where orcimpactomovimp.o68_codimpmov = $o68_codimpmov "; 
       } 
       if($o68_codimp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactomovimp.o68_codimp = $o68_codimp "; 
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

   function sql_query_file ( $o68_codimpmov=null,$o68_codimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactomovimp ";
     $sql2 = "";
     if($dbwhere==""){
       if($o68_codimpmov!=null ){
         $sql2 .= " where orcimpactomovimp.o68_codimpmov = $o68_codimpmov "; 
       } 
       if($o68_codimp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactomovimp.o68_codimp = $o68_codimp "; 
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
   function sql_query_compl ( $o68_codimpmov=null,$o68_codimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactomovimp ";
     $sql .= "      inner join orcimpacto  on  orcimpacto.o90_codimp = orcimpactomovimp.o68_codimp";
     $sql .= "      left join orcimpactoval on o91_codimp = o90_codimp";
     $sql .= "      inner join orcimpactomov  on  orcimpactomov.o63_codimpmov = orcimpactomovimp.o68_codimpmov";
     $sql .= "      left join orcimpactovalmov on o64_codimpmov = o63_codimpmov";
     $sql2 = "";
     if($dbwhere==""){
       if($o68_codimpmov!=null ){
         $sql2 .= " where orcimpactomovimp.o68_codimpmov = $o68_codimpmov "; 
       } 
       if($o68_codimp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactomovimp.o68_codimp = $o68_codimp "; 
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
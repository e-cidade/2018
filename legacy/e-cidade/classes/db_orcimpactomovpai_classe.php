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
//CLASSE DA ENTIDADE orcimpactomovpai
class cl_orcimpactomovpai { 
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
   var $o86_codimpmovpai = 0; 
   var $o86_codimpmovfilho = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o86_codimpmovpai = int8 = Código 
                 o86_codimpmovfilho = int8 = Código 
                 ";
   //funcao construtor da classe 
   function cl_orcimpactomovpai() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpactomovpai"); 
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
       $this->o86_codimpmovpai = ($this->o86_codimpmovpai == ""?@$GLOBALS["HTTP_POST_VARS"]["o86_codimpmovpai"]:$this->o86_codimpmovpai);
       $this->o86_codimpmovfilho = ($this->o86_codimpmovfilho == ""?@$GLOBALS["HTTP_POST_VARS"]["o86_codimpmovfilho"]:$this->o86_codimpmovfilho);
     }else{
       $this->o86_codimpmovpai = ($this->o86_codimpmovpai == ""?@$GLOBALS["HTTP_POST_VARS"]["o86_codimpmovpai"]:$this->o86_codimpmovpai);
       $this->o86_codimpmovfilho = ($this->o86_codimpmovfilho == ""?@$GLOBALS["HTTP_POST_VARS"]["o86_codimpmovfilho"]:$this->o86_codimpmovfilho);
     }
   }
   // funcao para inclusao
   function incluir ($o86_codimpmovpai,$o86_codimpmovfilho){ 
      $this->atualizacampos();
       $this->o86_codimpmovpai = $o86_codimpmovpai; 
       $this->o86_codimpmovfilho = $o86_codimpmovfilho; 
     if(($this->o86_codimpmovpai == null) || ($this->o86_codimpmovpai == "") ){ 
       $this->erro_sql = " Campo o86_codimpmovpai nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o86_codimpmovfilho == null) || ($this->o86_codimpmovfilho == "") ){ 
       $this->erro_sql = " Campo o86_codimpmovfilho nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpactomovpai(
                                       o86_codimpmovpai 
                                      ,o86_codimpmovfilho 
                       )
                values (
                                $this->o86_codimpmovpai 
                               ,$this->o86_codimpmovfilho 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentos pais ($this->o86_codimpmovpai."-".$this->o86_codimpmovfilho) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentos pais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentos pais ($this->o86_codimpmovpai."-".$this->o86_codimpmovfilho) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o86_codimpmovpai."-".$this->o86_codimpmovfilho;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o86_codimpmovpai,$this->o86_codimpmovfilho));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6731,'$this->o86_codimpmovpai','I')");
       $resac = db_query("insert into db_acountkey values($acount,6732,'$this->o86_codimpmovfilho','I')");
       $resac = db_query("insert into db_acount values($acount,1101,6731,'','".AddSlashes(pg_result($resaco,0,'o86_codimpmovpai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1101,6732,'','".AddSlashes(pg_result($resaco,0,'o86_codimpmovfilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o86_codimpmovpai=null,$o86_codimpmovfilho=null) { 
      $this->atualizacampos();
     $sql = " update orcimpactomovpai set ";
     $virgula = "";
     if(trim($this->o86_codimpmovpai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o86_codimpmovpai"])){ 
       $sql  .= $virgula." o86_codimpmovpai = $this->o86_codimpmovpai ";
       $virgula = ",";
       if(trim($this->o86_codimpmovpai) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o86_codimpmovpai";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o86_codimpmovfilho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o86_codimpmovfilho"])){ 
       $sql  .= $virgula." o86_codimpmovfilho = $this->o86_codimpmovfilho ";
       $virgula = ",";
       if(trim($this->o86_codimpmovfilho) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o86_codimpmovfilho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o86_codimpmovpai!=null){
       $sql .= " o86_codimpmovpai = $this->o86_codimpmovpai";
     }
     if($o86_codimpmovfilho!=null){
       $sql .= " and  o86_codimpmovfilho = $this->o86_codimpmovfilho";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o86_codimpmovpai,$this->o86_codimpmovfilho));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6731,'$this->o86_codimpmovpai','A')");
         $resac = db_query("insert into db_acountkey values($acount,6732,'$this->o86_codimpmovfilho','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o86_codimpmovpai"]))
           $resac = db_query("insert into db_acount values($acount,1101,6731,'".AddSlashes(pg_result($resaco,$conresaco,'o86_codimpmovpai'))."','$this->o86_codimpmovpai',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o86_codimpmovfilho"]))
           $resac = db_query("insert into db_acount values($acount,1101,6732,'".AddSlashes(pg_result($resaco,$conresaco,'o86_codimpmovfilho'))."','$this->o86_codimpmovfilho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentos pais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o86_codimpmovpai."-".$this->o86_codimpmovfilho;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentos pais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o86_codimpmovpai."-".$this->o86_codimpmovfilho;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o86_codimpmovpai."-".$this->o86_codimpmovfilho;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o86_codimpmovpai=null,$o86_codimpmovfilho=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o86_codimpmovpai,$o86_codimpmovfilho));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6731,'$o86_codimpmovpai','E')");
         $resac = db_query("insert into db_acountkey values($acount,6732,'$o86_codimpmovfilho','E')");
         $resac = db_query("insert into db_acount values($acount,1101,6731,'','".AddSlashes(pg_result($resaco,$iresaco,'o86_codimpmovpai'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1101,6732,'','".AddSlashes(pg_result($resaco,$iresaco,'o86_codimpmovfilho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpactomovpai
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o86_codimpmovpai != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o86_codimpmovpai = $o86_codimpmovpai ";
        }
        if($o86_codimpmovfilho != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o86_codimpmovfilho = $o86_codimpmovfilho ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentos pais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o86_codimpmovpai."-".$o86_codimpmovfilho;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentos pais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o86_codimpmovpai."-".$o86_codimpmovfilho;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o86_codimpmovpai."-".$o86_codimpmovfilho;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpactomovpai";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o86_codimpmovpai=null,$o86_codimpmovfilho=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactomovpai ";
     $sql .= "      inner join orcimpactomov  on  orcimpactomov.o63_codimpmov = orcimpactomovpai.o86_codimpmovpai";
     $sql .= "      inner join orcimpactovalmov  on  orcimpactovalmov.o64_codimpmov = orcimpactomovpai.o86_codimpmovpai";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcimpactomov.o63_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcimpactomov.o63_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcimpactomov.o63_anoexe and  orcprograma.o54_programa = orcimpactomov.o63_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcimpactomov.o63_anoexe and  orcprojativ.o55_projativ = orcimpactomov.o63_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcimpactomov.o63_anoexe and  orcorgao.o40_orgao = orcimpactomov.o63_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcimpactomov.o63_anoexe and  orcunidade.o41_orgao = orcimpactomov.o63_orgao and  orcunidade.o41_unidade = orcimpactomov.o63_unidade";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcimpactomov.o63_produto";
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpactomov.o63_codperiodo";
     $sql2 = "";
     if($dbwhere==""){
       if($o86_codimpmovpai!=null ){
         $sql2 .= " where orcimpactomovpai.o86_codimpmovpai = $o86_codimpmovpai "; 
       } 
       if($o86_codimpmovfilho!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactomovpai.o86_codimpmovfilho = $o86_codimpmovfilho "; 
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
   function sql_query_file ( $o86_codimpmovpai=null,$o86_codimpmovfilho=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactomovpai ";
     $sql2 = "";
     if($dbwhere==""){
       if($o86_codimpmovpai!=null ){
         $sql2 .= " where orcimpactomovpai.o86_codimpmovpai = $o86_codimpmovpai "; 
       } 
       if($o86_codimpmovfilho!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactomovpai.o86_codimpmovfilho = $o86_codimpmovfilho "; 
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
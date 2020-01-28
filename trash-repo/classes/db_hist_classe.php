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

//MODULO: contabil
//CLASSE DA ENTIDADE hist
class cl_hist { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $c03_anousu = 0; 
   var $c03_codigo = 0; 
   var $c03_descr = null; 
   var $c03_compl = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c03_anousu = int4 = Ano do Exercicio 
                 c03_codigo = int4 = Histórico 
                 c03_descr = char(    30) = Descricao do Historico Padrao 
                 c03_compl = boolean = Complemento do Historico 
                 ";
   //funcao construtor da classe 
   function cl_hist() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("hist"); 
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
       $this->c03_anousu = ($this->c03_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c03_anousu"]:$this->c03_anousu);
       $this->c03_codigo = ($this->c03_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["c03_codigo"]:$this->c03_codigo);
       $this->c03_descr = ($this->c03_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["c03_descr"]:$this->c03_descr);
       $this->c03_compl = ($this->c03_compl == "f"?@$GLOBALS["HTTP_POST_VARS"]["c03_compl"]:$this->c03_compl);
     }else{
       $this->c03_anousu = ($this->c03_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c03_anousu"]:$this->c03_anousu);
       $this->c03_codigo = ($this->c03_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["c03_codigo"]:$this->c03_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($c03_anousu,$c03_codigo){ 
      $this->atualizacampos();
     if($this->c03_descr == null ){ 
       $this->erro_sql = " Campo Descricao do Historico Padrao nao Informado.";
       $this->erro_campo = "c03_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c03_compl == null ){ 
       $this->erro_sql = " Campo Complemento do Historico nao Informado.";
       $this->erro_campo = "c03_compl";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c03_anousu = $c03_anousu; 
       $this->c03_codigo = $c03_codigo; 
     if(($this->c03_anousu == null) || ($this->c03_anousu == "") ){ 
       $this->erro_sql = " Campo c03_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c03_codigo == null) || ($this->c03_codigo == "") ){ 
       $this->erro_sql = " Campo c03_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into hist(
                                       c03_anousu 
                                      ,c03_codigo 
                                      ,c03_descr 
                                      ,c03_compl 
                       )
                values (
                                $this->c03_anousu 
                               ,$this->c03_codigo 
                               ,'$this->c03_descr' 
                               ,'$this->c03_compl' 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Historico de Lancamentos                           ($this->c03_anousu."-".$this->c03_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Historico de Lancamentos                           já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Historico de Lancamentos                           ($this->c03_anousu."-".$this->c03_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c03_anousu."-".$this->c03_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->c03_anousu,$this->c03_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2935,'$this->c03_anousu','I')");
       $resac = pg_query("insert into db_acountkey values($acount,4671,'$this->c03_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,451,2935,'','".pg_result($resaco,0,'c03_anousu')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,451,4671,'','".pg_result($resaco,0,'c03_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,451,2938,'','".pg_result($resaco,0,'c03_descr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,451,2939,'','".pg_result($resaco,0,'c03_compl')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c03_anousu=null,$c03_codigo=null) { 
      $this->atualizacampos();
     $sql = " update hist set ";
     $virgula = "";
     if(trim($this->c03_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c03_anousu"])){ 
        if(trim($this->c03_anousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c03_anousu"])){ 
           $this->c03_anousu = "0" ; 
        } 
       $sql  .= $virgula." c03_anousu = $this->c03_anousu ";
       $virgula = ",";
       if(trim($this->c03_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "c03_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c03_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c03_codigo"])){ 
        if(trim($this->c03_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c03_codigo"])){ 
           $this->c03_codigo = "0" ; 
        } 
       $sql  .= $virgula." c03_codigo = $this->c03_codigo ";
       $virgula = ",";
       if(trim($this->c03_codigo) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "c03_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c03_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c03_descr"])){ 
       $sql  .= $virgula." c03_descr = '$this->c03_descr' ";
       $virgula = ",";
       if(trim($this->c03_descr) == null ){ 
         $this->erro_sql = " Campo Descricao do Historico Padrao nao Informado.";
         $this->erro_campo = "c03_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c03_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c03_compl"])){ 
       $sql  .= $virgula." c03_compl = '$this->c03_compl' ";
       $virgula = ",";
       if(trim($this->c03_compl) == null ){ 
         $this->erro_sql = " Campo Complemento do Historico nao Informado.";
         $this->erro_campo = "c03_compl";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  c03_anousu = $this->c03_anousu
 and  c03_codigo = $this->c03_codigo
";
     $resaco = $this->sql_record($this->sql_query_file($this->c03_anousu,$this->c03_codigo));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2935,'$this->c03_anousu','A')");
       $resac = pg_query("insert into db_acountkey values($acount,4671,'$this->c03_codigo','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c03_anousu"]))
         $resac = pg_query("insert into db_acount values($acount,451,2935,'".pg_result($resaco,0,'c03_anousu')."','$this->c03_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c03_codigo"]))
         $resac = pg_query("insert into db_acount values($acount,451,4671,'".pg_result($resaco,0,'c03_codigo')."','$this->c03_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c03_descr"]))
         $resac = pg_query("insert into db_acount values($acount,451,2938,'".pg_result($resaco,0,'c03_descr')."','$this->c03_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["c03_compl"]))
         $resac = pg_query("insert into db_acount values($acount,451,2939,'".pg_result($resaco,0,'c03_compl')."','$this->c03_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Historico de Lancamentos                           nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c03_anousu."-".$this->c03_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Historico de Lancamentos                           nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c03_anousu."-".$this->c03_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c03_anousu."-".$this->c03_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c03_anousu=null,$c03_codigo=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->c03_anousu,$this->c03_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2935,'".pg_result($resaco,$iresaco,'c03_anousu')."','E')");
       $resac = pg_query("insert into db_acountkey values($acount,4671,'".pg_result($resaco,$iresaco,'c03_codigo')."','E')");
       $resac = pg_query("insert into db_acount values($acount,451,2935,'','".pg_result($resaco,0,'c03_anousu')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,451,4671,'','".pg_result($resaco,0,'c03_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,451,2938,'','".pg_result($resaco,0,'c03_descr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,451,2939,'','".pg_result($resaco,0,'c03_compl')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from hist
                    where ";
     $sql2 = "";
      if($this->c03_anousu != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " c03_anousu = $this->c03_anousu ";
}
      if($this->c03_codigo != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " c03_codigo = $this->c03_codigo ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Historico de Lancamentos                           nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->c03_anousu."-".$this->c03_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Historico de Lancamentos                           nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->c03_anousu."-".$this->c03_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c03_anousu."-".$this->c03_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c03_anousu=null,$c03_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from hist ";
     $sql2 = "";
     if($dbwhere==""){
       if($c03_anousu!=null ){
         $sql2 .= " where hist.c03_anousu = $c03_anousu "; 
       } 
       if($c03_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " hist.c03_codigo = $c03_codigo "; 
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
   function sql_query_file ( $c03_anousu=null,$c03_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from hist ";
     $sql2 = "";
     if($dbwhere==""){
       if($c03_anousu!=null ){
         $sql2 .= " where hist.c03_anousu = $c03_anousu "; 
       } 
       if($c03_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " hist.c03_codigo = $c03_codigo "; 
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
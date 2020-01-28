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
//CLASSE DA ENTIDADE orcam
class cl_orcam { 
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
   var $o02_anousu = 0; 
   var $o02_codigo = null; 
   var $o02_descr = null; 
   var $o02_valor = 0; 
   var $o02_codtce = null; 
   var $o02_percen = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o02_anousu = int4 = Exercício 
                 o02_codigo = char(12) = Codigo da Receita/Despesa 
                 o02_descr = varchar(40) = Descricao da Receita/Despesa 
                 o02_valor = float8 = Valor da Receita/Despesa 
                 o02_codtce = varchar(13) = Codigo do tce para receita/despesa 
                 o02_percen = float8 = Percentual 
                 ";
   //funcao construtor da classe 
   function cl_orcam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcam"); 
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
       $this->o02_anousu = ($this->o02_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o02_anousu"]:$this->o02_anousu);
       $this->o02_codigo = ($this->o02_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o02_codigo"]:$this->o02_codigo);
       $this->o02_descr = ($this->o02_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o02_descr"]:$this->o02_descr);
       $this->o02_valor = ($this->o02_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o02_valor"]:$this->o02_valor);
       $this->o02_codtce = ($this->o02_codtce == ""?@$GLOBALS["HTTP_POST_VARS"]["o02_codtce"]:$this->o02_codtce);
       $this->o02_percen = ($this->o02_percen == ""?@$GLOBALS["HTTP_POST_VARS"]["o02_percen"]:$this->o02_percen);
     }else{
       $this->o02_anousu = ($this->o02_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o02_anousu"]:$this->o02_anousu);
       $this->o02_codigo = ($this->o02_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o02_codigo"]:$this->o02_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($o02_anousu,$o02_codigo){ 
      $this->atualizacampos();
     if($this->o02_descr == null ){ 
       $this->erro_sql = " Campo Descricao da Receita/Despesa nao Informado.";
       $this->erro_campo = "o02_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o02_valor == null ){ 
       $this->o02_valor = "0";
     }
     if($this->o02_percen == null ){ 
       $this->o02_percen = "0";
     }
       $this->o02_anousu = $o02_anousu; 
       $this->o02_codigo = $o02_codigo; 
     if(($this->o02_anousu == null) || ($this->o02_anousu == "") ){ 
       $this->erro_sql = " Campo o02_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o02_codigo == null) || ($this->o02_codigo == "") ){ 
       $this->erro_sql = " Campo o02_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into orcam(
                                       o02_anousu 
                                      ,o02_codigo 
                                      ,o02_descr 
                                      ,o02_valor 
                                      ,o02_codtce 
                                      ,o02_percen 
                       )
                values (
                                $this->o02_anousu 
                               ,'$this->o02_codigo' 
                               ,'$this->o02_descr' 
                               ,$this->o02_valor 
                               ,'$this->o02_codtce' 
                               ,$this->o02_percen 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contas da receita/despesa ($this->o02_anousu."-".$this->o02_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contas da receita/despesa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contas da receita/despesa ($this->o02_anousu."-".$this->o02_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o02_anousu."-".$this->o02_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->o02_anousu,$this->o02_codigo));
     $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
     $acount = pg_result($resac,0,0);
     $resac = pg_query("insert into db_acountkey values($acount,888,'$this->o02_anousu','I')");
     $resac = pg_query("insert into db_acountkey values($acount,889,'$this->o02_codigo','I')");
     $resac = pg_query("insert into db_acount values($acount,163,888,'','".pg_result($resaco,0,'o02_anousu')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,163,889,'','".pg_result($resaco,0,'o02_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,163,890,'','".pg_result($resaco,0,'o02_descr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,163,891,'','".pg_result($resaco,0,'o02_valor')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,163,892,'','".pg_result($resaco,0,'o02_codtce')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,163,893,'','".pg_result($resaco,0,'o02_percen')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     pg_free_result($resaco);
     return true;
   } 
   // funcao para alteracao
   function alterar ($o02_anousu=null,$o02_codigo=null) { 
      $this->atualizacampos();
     $sql = " update orcam set ";
     $virgula = "";
     if(isset($GLOBALS["HTTP_POST_VARS"]["o02_anousu"])){ 
       $sql  .= $virgula." o02_anousu = $this->o02_anousu ";
       $virgula = ",";
       if($this->o02_anousu == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o02_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["o02_codigo"])){ 
       $sql  .= $virgula." o02_codigo = '$this->o02_codigo' ";
       $virgula = ",";
       if($this->o02_codigo == null ){ 
         $this->erro_sql = " Campo Codigo da Receita/Despesa nao Informado.";
         $this->erro_campo = "o02_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["o02_descr"])){ 
       $sql  .= $virgula." o02_descr = '$this->o02_descr' ";
       $virgula = ",";
       if($this->o02_descr == null ){ 
         $this->erro_sql = " Campo Descricao da Receita/Despesa nao Informado.";
         $this->erro_campo = "o02_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["o02_valor"])){ 
       $sql  .= $virgula." o02_valor = $this->o02_valor ";
       $virgula = ",";
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["o02_codtce"])){ 
       $sql  .= $virgula." o02_codtce = '$this->o02_codtce' ";
       $virgula = ",";
     }
     if(isset($GLOBALS["HTTP_POST_VARS"]["o02_percen"])){ 
       $sql  .= $virgula." o02_percen = $this->o02_percen ";
       $virgula = ",";
     }
     $sql .= " where  o02_anousu = $this->o02_anousu
 and  o02_codigo = '$this->o02_codigo'
";
     $resaco = $this->sql_record($this->sql_query_file($this->o02_anousu,$this->o02_codigo));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,888,'$this->o02_anousu','A')");
       $resac = pg_query("insert into db_acountkey values($acount,889,'$this->o02_codigo','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o02_anousu"]))
         $resac = pg_query("insert into db_acount values($acount,163,888,'$this->o02_anousu','".pg_result($resaco,0,'o02_anousu')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o02_codigo"]))
         $resac = pg_query("insert into db_acount values($acount,163,889,'$this->o02_codigo','".pg_result($resaco,0,'o02_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o02_descr"]))
         $resac = pg_query("insert into db_acount values($acount,163,890,'$this->o02_descr','".pg_result($resaco,0,'o02_descr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o02_valor"]))
         $resac = pg_query("insert into db_acount values($acount,163,891,'$this->o02_valor','".pg_result($resaco,0,'o02_valor')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o02_codtce"]))
         $resac = pg_query("insert into db_acount values($acount,163,892,'$this->o02_codtce','".pg_result($resaco,0,'o02_codtce')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o02_percen"]))
         $resac = pg_query("insert into db_acount values($acount,163,893,'$this->o02_percen','".pg_result($resaco,0,'o02_percen')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       pg_free_result($resaco);
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas da receita/despesa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o02_anousu."-".$this->o02_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contas da receita/despesa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o02_anousu."-".$this->o02_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o02_anousu."-".$this->o02_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o02_anousu=null,$o02_codigo=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->o02_anousu,$this->o02_codigo));
     $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
     $acount = pg_result($resac,0,0);
     $resac = pg_query("insert into db_acountkey values($acount,888,'".pg_result($resaco,$iresaco,'o02_anousu')."','E')");
     $resac = pg_query("insert into db_acountkey values($acount,889,'".pg_result($resaco,$iresaco,'o02_codigo')."','E')");
     $resac = pg_query("insert into db_acount values($acount,163,888,'','".pg_result($resaco,0,'o02_anousu')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,163,889,'','".pg_result($resaco,0,'o02_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,163,890,'','".pg_result($resaco,0,'o02_descr')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,163,891,'','".pg_result($resaco,0,'o02_valor')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,163,892,'','".pg_result($resaco,0,'o02_codtce')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     $resac = pg_query("insert into db_acount values($acount,163,893,'','".pg_result($resaco,0,'o02_percen')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     pg_free_result($resaco);
     $sql = " delete from orcam
                    where ";
     $sql2 = "";
      if($this->o02_anousu != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " o02_anousu = $this->o02_anousu ";
}
      if($this->o02_codigo != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " o02_codigo = '$this->o02_codigo' ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas da receita/despesa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->o02_anousu."-".$this->o02_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contas da receita/despesa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->o02_anousu."-".$this->o02_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o02_anousu."-".$this->o02_codigo;
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
   function sql_query ( $o02_anousu=null,$o02_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcam ";
     $sql2 = "";
     if($dbwhere==""){
       if($o02_anousu!=null ){
         $sql2 .= " where orcam.o02_anousu = $o02_anousu "; 
       } 
       if($o02_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcam.o02_codigo = '$o02_codigo' "; 
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
   function sql_query_file ( $o02_anousu=null,$o02_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcam ";
     $sql2 = "";
     if($dbwhere==""){
       if($o02_anousu!=null ){
         $sql2 .= " where orcam.o02_anousu = $o02_anousu "; 
       } 
       if($o02_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcam.o02_codigo = '$o02_codigo' "; 
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
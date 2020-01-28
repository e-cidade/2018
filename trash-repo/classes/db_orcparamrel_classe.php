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
//CLASSE DA ENTIDADE orcparamrel
class cl_orcparamrel { 
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
   var $o42_codparrel = 0; 
   var $o42_orcparamrelgrupo = 0; 
   var $o42_descrrel = null; 
   var $o42_notapadrao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o42_codparrel = int4 = Código Relatório 
                 o42_orcparamrelgrupo = int4 = Código do grupo de relatório 
                 o42_descrrel = varchar(100) = Descrição 
                 o42_notapadrao = text = nota Explicativa Padrao 
                 ";
   //funcao construtor da classe 
   function cl_orcparamrel() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamrel"); 
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
       $this->o42_codparrel = ($this->o42_codparrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_codparrel"]:$this->o42_codparrel);
       $this->o42_orcparamrelgrupo = ($this->o42_orcparamrelgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_orcparamrelgrupo"]:$this->o42_orcparamrelgrupo);
       $this->o42_descrrel = ($this->o42_descrrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_descrrel"]:$this->o42_descrrel);
       $this->o42_notapadrao = ($this->o42_notapadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_notapadrao"]:$this->o42_notapadrao);
     }else{
       $this->o42_codparrel = ($this->o42_codparrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o42_codparrel"]:$this->o42_codparrel);
     }
   }
   // funcao para inclusao
   function incluir ($o42_codparrel){ 
      $this->atualizacampos();
     if($this->o42_orcparamrelgrupo == null ){ 
       $this->erro_sql = " Campo Código do grupo de relatório nao Informado.";
       $this->erro_campo = "o42_orcparamrelgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o42_descrrel == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o42_descrrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o42_codparrel == "" || $o42_codparrel == null ){
       $result = db_query("select nextval('orcparamrel_o42_codparrel_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamrel_o42_codparrel_seq do campo: o42_codparrel"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o42_codparrel = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamrel_o42_codparrel_seq");
       if(($result != false) && (pg_result($result,0,0) < $o42_codparrel)){
         $this->erro_sql = " Campo o42_codparrel maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o42_codparrel = $o42_codparrel; 
       }
     }
     if(($this->o42_codparrel == null) || ($this->o42_codparrel == "") ){ 
       $this->erro_sql = " Campo o42_codparrel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamrel(
                                       o42_codparrel 
                                      ,o42_orcparamrelgrupo 
                                      ,o42_descrrel 
                                      ,o42_notapadrao 
                       )
                values (
                                $this->o42_codparrel 
                               ,$this->o42_orcparamrelgrupo 
                               ,'$this->o42_descrrel' 
                               ,'$this->o42_notapadrao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parâmetros para Relatórios ($this->o42_codparrel) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parâmetros para Relatórios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parâmetros para Relatórios ($this->o42_codparrel) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o42_codparrel;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o42_codparrel));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5705,'$this->o42_codparrel','I')");
       $resac = db_query("insert into db_acount values($acount,901,5705,'','".AddSlashes(pg_result($resaco,0,'o42_codparrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,901,14101,'','".AddSlashes(pg_result($resaco,0,'o42_orcparamrelgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,901,5706,'','".AddSlashes(pg_result($resaco,0,'o42_descrrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,901,15561,'','".AddSlashes(pg_result($resaco,0,'o42_notapadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o42_codparrel=null) { 
      $this->atualizacampos();
     $sql = " update orcparamrel set ";
     $virgula = "";
     if(trim($this->o42_codparrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_codparrel"])){ 
       $sql  .= $virgula." o42_codparrel = $this->o42_codparrel ";
       $virgula = ",";
       if(trim($this->o42_codparrel) == null ){ 
         $this->erro_sql = " Campo Código Relatório nao Informado.";
         $this->erro_campo = "o42_codparrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o42_orcparamrelgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_orcparamrelgrupo"])){ 
       $sql  .= $virgula." o42_orcparamrelgrupo = $this->o42_orcparamrelgrupo ";
       $virgula = ",";
       if(trim($this->o42_orcparamrelgrupo) == null ){ 
         $this->erro_sql = " Campo Código do grupo de relatório nao Informado.";
         $this->erro_campo = "o42_orcparamrelgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o42_descrrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_descrrel"])){ 
       $sql  .= $virgula." o42_descrrel = '$this->o42_descrrel' ";
       $virgula = ",";
       if(trim($this->o42_descrrel) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o42_descrrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o42_notapadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o42_notapadrao"])){ 
       $sql  .= $virgula." o42_notapadrao = '$this->o42_notapadrao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o42_codparrel!=null){
       $sql .= " o42_codparrel = $this->o42_codparrel";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o42_codparrel));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5705,'$this->o42_codparrel','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_codparrel"]) || $this->o42_codparrel != "")
           $resac = db_query("insert into db_acount values($acount,901,5705,'".AddSlashes(pg_result($resaco,$conresaco,'o42_codparrel'))."','$this->o42_codparrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_orcparamrelgrupo"]) || $this->o42_orcparamrelgrupo != "")
           $resac = db_query("insert into db_acount values($acount,901,14101,'".AddSlashes(pg_result($resaco,$conresaco,'o42_orcparamrelgrupo'))."','$this->o42_orcparamrelgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_descrrel"]) || $this->o42_descrrel != "")
           $resac = db_query("insert into db_acount values($acount,901,5706,'".AddSlashes(pg_result($resaco,$conresaco,'o42_descrrel'))."','$this->o42_descrrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o42_notapadrao"]) || $this->o42_notapadrao != "")
           $resac = db_query("insert into db_acount values($acount,901,15561,'".AddSlashes(pg_result($resaco,$conresaco,'o42_notapadrao'))."','$this->o42_notapadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros para Relatórios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o42_codparrel;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros para Relatórios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o42_codparrel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o42_codparrel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o42_codparrel=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o42_codparrel));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5705,'$o42_codparrel','E')");
         $resac = db_query("insert into db_acount values($acount,901,5705,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_codparrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,901,14101,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_orcparamrelgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,901,5706,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_descrrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,901,15561,'','".AddSlashes(pg_result($resaco,$iresaco,'o42_notapadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamrel
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o42_codparrel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o42_codparrel = $o42_codparrel ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros para Relatórios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o42_codparrel;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros para Relatórios nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o42_codparrel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o42_codparrel;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamrel";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o42_codparrel=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamrel ";
     $sql .= "      inner join orcparamrelgrupo  on  orcparamrelgrupo.o112_sequencial = orcparamrel.o42_orcparamrelgrupo";
     $sql2 = "";
     if($dbwhere==""){
       if($o42_codparrel!=null ){
         $sql2 .= " where orcparamrel.o42_codparrel = $o42_codparrel "; 
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
   function sql_query_file ( $o42_codparrel=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamrel ";
     $sql2 = "";
     if($dbwhere==""){
       if($o42_codparrel!=null ){
         $sql2 .= " where orcparamrel.o42_codparrel = $o42_codparrel "; 
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
   function sql_funcao($relatorio,$parametro="",$instituicao="(1)"){
    $matriz = array();

    $sql    = "select distinct o45_func 
               from orcparamrel
                    inner join orcparamseq  on o69_codparamrel = o42_codparrel
                    inner join orcparamfunc on o45_anousu    = ".db_getsession("DB_anousu")." and
                                               o45_codparrel = orcparamseq.o69_codparamrel and
                                               o45_sequencia = orcparamseq.o69_codseq "; 
    $sql .= "inner join orcdotacao on orcdotacao.o58_anousu  = orcparamfunc.o45_anousu and
                                      orcdotacao.o58_funcao  = orcparamfunc.o45_func ";
    $sql .= "where orcparamrel.o42_codparrel = $relatorio";
    
    if ($parametro !="" ){
         $sql      .= " and orcparamseq.o69_codseq = $parametro";
    }	 
    
    $r = @db_query($sql);
    $rows = @pg_numrows($r);
    
    if ($rows > 0 ) {
      for($x=0;$x < pg_numrows($r);$x++){
        $matriz[$x] = pg_result($r,$x,"o45_func"); 
      }  
    }

    return $matriz;
  }
   function sql_nivel($relatorio,$parametro="",$anousu=null){ 
    //#10#//    

    if ($anousu == null){
      $anousu = db_getsession("DB_anousu");
    }

    $nivel = 0;
    $sql= "select o44_nivel 
    from orcparamnivel
    inner join orcparamseq on o69_codparamrel=o44_codparrel and o69_codseq=o44_sequencia                
    where o69_codparamrel = $relatorio and o69_libnivel ='t' and o44_anousu = $anousu     
    ";	 
    if ($parametro !="" ){
      $sql.= " and orcparamseq.o69_codseq = $parametro   ";
    }	  
    $r = @db_query($sql);
    $rows = @pg_numrows($r);
    if ($rows > 0 ) {
      for($x=0;$x < pg_numrows($r);$x++){
        $nivel = pg_result($r,$x,"o44_nivel"); 
      }  
    }	
    return $nivel;
  }
   function sql_nivelexclusao($relatorio,$parametro=""){ 
    //#10#//    
    $nivel = 0;
    $sql= "select o44_nivelexclusao
    from orcparamnivel
    inner join orcparamseq on o69_codparamrel=o44_codparrel and o69_codseq=o44_sequencia                
    where o69_codparamrel = $relatorio and o69_libnivel ='t'         
    ";	 
    if ($parametro !="" ){
      $sql.= " and orcparamseq.o69_codseq = $parametro   ";
    }	  
    $r = @db_query($sql);
    $rows = @pg_numrows($r);
    if ($rows > 0 ) {
      for($x=0;$x < pg_numrows($r);$x++){
        $nivel = pg_result($r,$x,"o44_nivelexclusao"); 
      }  
    }	
    return $nivel;
  }
   function sql_parametro($relatorio,$parametro="",$exclusao='f',$instituicao = "1",$anousu=null,$obrig=true){ 
    //#10#// exclusão: procura os parametros cadastrados como exclusao
    //#10#//
   
    $sql_instit      = "select codigo from db_config where prefeitura is true limit 1";
    $res_instit      = @db_query($sql_instit);
    $num_rows_instit = @pg_numrows($res_instit);

    if ($num_rows_instit > 0){
         $codigo = pg_result($res_instit,0,"codigo");
         if ($instituicao == "1"){
              if ($codigo != $instituicao){
                   $instituicao = $codigo;
              }
         }
    }

    if ($anousu == null) {
			$anousu = db_getsession("DB_anousu");
		}
		
    $matriz= array();
    $sql= "select distinct c60_estrut 
    from orcparamrel 
    inner join orcparamseq on o69_codparamrel=o42_codparrel
    inner join orcparamelemento on o44_anousu = $anousu
    and o44_codparrel = orcparamseq.o69_codparamrel
    and o44_sequencia = orcparamseq.o69_codseq
    ";
    if ($exclusao=='f' ){	   
      $sql .="         and (o44_exclusao = 'f' or o44_exclusao is null) ";
    }else {
      $sql .="         and (o44_exclusao = 't') ";
    }	
    $sql .="	   
    inner join conplano on c60_codcon = o44_codele and c60_anousu = o44_anousu
    where orcparamrel.o42_codparrel = $relatorio         
    ";	 
    if ($parametro !="" ){
      $sql.= " and orcparamseq.o69_codseq = $parametro   ";
    }

    $sql .= " and orcparamelemento.o44_instit in ($instituicao) ";

    $r = @db_query(analiseQueryPlanoOrcamento($sql)) or die($sql);
    $rows = @pg_numrows($r);

// Caso nao tenha conta definida na instituicao pega como defautl da Prefeitura (1)
    if ($rows == 0 && $obrig == false) {
         $sql= "select distinct c60_estrut
                from orcparamrel
                     inner join orcparamseq on o69_codparamrel=o42_codparrel
                     inner join orcparamelemento on o44_anousu = $anousu and 
                                                    o44_codparrel = orcparamseq.o69_codparamrel and 
                                                    o44_sequencia = orcparamseq.o69_codseq";
         if ($exclusao=='f' ) {
              $sql .="         and(o44_exclusao = 'f' or o44_exclusao is null) ";
         } else {
              $sql .="         and (o44_exclusao = 't') ";
         }

         $sql .="inner join conplano on c60_codcon = o44_codele and c60_anousu = o44_anousu
                 where orcparamrel.o42_codparrel = $relatorio";

         if ($parametro !="" ) {
              $sql.= " and orcparamseq.o69_codseq = $parametro   ";
         }
    }

    $r = @db_query(analiseQueryPlanoOrcamento($sql)) or die($sql);
    $rows = @pg_numrows($r);

    if ($rows > 0 ) {
      //echo $sql."<br>"; exit;

      for($x=0;$x < pg_numrows($r);$x++){
        if (in_array(pg_result($r,$x,"c60_estrut"),$matriz) == false){
             $matriz[$x] = pg_result($r,$x,"c60_estrut"); 
        }
      }  
    }

    return $matriz;
  }
   function sql_parametro_instit($relatorio,$parametro="",$exclusao='f',$instituicao = "1",$anousu=null,$obrig=true) {
    $sql_instit      = "select codigo from db_config where prefeitura is true limit 1";
    $res_instit      = @db_query($sql_instit);
    $num_rows_instit = @pg_numrows($res_instit);
    
    if ($num_rows_instit > 0) {
      $codigo = pg_result($res_instit,0,"codigo");
      if ($instituicao == "1") {
        if ($codigo != $instituicao) {
          $instituicao = $codigo;
        }
      }
    }
    
    if ($anousu == null) {
      $anousu = db_getsession("DB_anousu");
    }
    
    $matriz= array();

    $sql  = "select distinct c60_estrut, o44_instit ";
    $sql .= "  from orcparamrel ";
    $sql .= "       inner join orcparamseq       on o69_codparamrel=o42_codparrel ";
    $sql .= "       inner join orcparamelemento  on o44_anousu = $anousu ";
    $sql .= "                                   and o44_codparrel = orcparamseq.o69_codparamrel ";
    $sql .= "                                   and o44_sequencia = orcparamseq.o69_codseq ";

    if ($exclusao=='f' ) {
      $sql .="         and(o44_exclusao = 'f' or o44_exclusao is null) ";
    } else {
      $sql .="         and (o44_exclusao = 't') ";
    }

    $sql .= " inner join conplano on c60_codcon = o44_codele and c60_anousu = o44_anousu ";
    $sql .= " where orcparamrel.o42_codparrel = $relatorio ";

    if ($parametro !="" ) {
      $sql.= " and orcparamseq.o69_codseq = $parametro   ";
    }
    
    $sql .= " and orcparamelemento.o44_instit in ($instituicao) ";
    
    $r = @db_query($sql) or die($sql);
    $rows = @pg_numrows($r);
    
    // Caso nao tenha conta definida na instituicao pega como defautl da Prefeitura (1)
    if ($rows == 0 && $obrig == false) {
      $sql  = "select distinct c60_estrut, o44_instit ";
      $sql .= "  from orcparamrel ";
      $sql .= "       inner join orcparamseq       on o69_codparamrel=o42_codparrel ";
      $sql .= "       inner join orcparamelemento  on o44_anousu = $anousu  ";
      $sql .= "                                   and o44_codparrel = orcparamseq.o69_codparamrel ";
      $sql .= "                                   and o44_sequencia = orcparamseq.o69_codseq ";
      if ($exclusao=='f' ) {
        $sql .="         and(o44_exclusao = 'f' or o44_exclusao is null) ";
      } else {
        $sql .="         and (o44_exclusao = 't') ";
      }
      
      $sql .= " inner join conplano on c60_codcon = o44_codele and c60_anousu = o44_anousu ";
      $sql .= " where orcparamrel.o42_codparrel = $relatorio ";
      
      if ($parametro !="" ) {
        $sql.= " and orcparamseq.o69_codseq = $parametro   ";
      }
    }
    
    $r = @db_query($sql) or die($sql);
    $rows = @pg_numrows($r);
    
    if ($rows > 0 ) {
      //echo $sql."<br>"; exit;
      
      for ($x=0; $x < pg_numrows($r); $x++) {

        $aElemento = array(pg_result($r, $x, "c60_estrut"),
                           pg_result($r, $x, "o44_instit"));

        if (in_array($aElemento, $matriz) == false) {
          $matriz[$x] = $aElemento;
        }
      }
    }
    
    return $matriz;
  }
   function sql_recurso($relatorio,$parametro="",$instituicao = "(1)"){ 
    //#10#//    
    $matriz= array();
    $sql= "select o44_codrec
    from orcparamrel 
    inner join orcparamseq on o69_codparamrel=o42_codparrel
    inner join orcparamrecurso on  o44_anousu = ".db_getsession("DB_anousu")."
    and o44_codparrel = orcparamseq.o69_codparamrel
    and o44_sequencia = orcparamseq.o69_codseq
    ";
    $sql .="	   
    /* and o44_instit in ($instituicao) */
    where orcparamrel.o42_codparrel = $relatorio         
    ";	 
    if ($parametro !="" ){
      $sql.= " and orcparamseq.o69_codseq = $parametro   ";
    }	  
    $r = @db_query($sql);
    $rows = @pg_numrows($r);
    if ($rows > 0 ) {
      for($x=0;$x < pg_numrows($r);$x++){
        $matriz[$x] = pg_result($r,$x,"o44_codrec"); 
      }  
    }	
    return $matriz;
  }
   function sql_subfunc($relatorio,$parametro="",$instituicao = "(1)"){ 
    //#10#//    
    $matriz= array();
    $sql= "select distinct o58_subfuncao as o44_subfunc
    from orcparamrel 
    inner join orcparamseq on o69_codparamrel=o42_codparrel
    inner join orcparamsubfunc on  o44_anousu = ".db_getsession("DB_anousu")."
    and o44_codparrel = orcparamseq.o69_codparamrel
    and o44_sequencia = orcparamseq.o69_codseq ";

// Incluido para relacionar orcdotacao
    $sql .= " inner join orcdotacao on orcdotacao.o58_anousu    = orcparamsubfunc.o44_anousu and
                                       orcdotacao.o58_subfuncao = orcparamsubfunc.o44_subfunc ";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $sql .="	   
    /* and o44_instit in ($instituicao) */
    where orcparamrel.o42_codparrel = $relatorio         
    ";	 
    
    $sql_func = "select o45_func 
                 from orcparamfunc
                 where o45_anousu    = ".db_getsession("DB_anousu")." and
                       o45_codparrel = $relatorio";
                       
    if ($parametro !="" ){
         $sql      .= " and orcparamseq.o69_codseq = $parametro ";
         $sql_func .= " and o45_sequencia          = $parametro ";
    }	 

    $res_func = @db_query($sql_func);
    $numrows  = @pg_numrows($res_func);

    if ($numrows > 0){
         $tem_funcao = true;
    } else {
         $tem_funcao = false;
    }

    $r = @db_query($sql);
    $rows = @pg_numrows($r);

// Se não existir subfuncao, verifica se existe funcao definida.
    if ($rows == 0 && $tem_funcao == true){
         $sql = "select distinct o58_subfuncao as o44_subfunc
                 from orcparamrel
                      inner join orcparamseq  on orcparamseq.o69_codparamrel = orcparamrel.o42_codparrel
                      inner join orcparamfunc on orcparamfunc.o45_codparrel = orcparamseq.o69_codparamrel and
                                                 orcparamfunc.o45_anousu    = ".db_getsession("DB_anousu")." and
                                                 orcparamfunc.o45_sequencia = orcparamseq.o69_codseq
                      inner join orcdotacao   on orcdotacao.o58_anousu = orcparamfunc.o45_anousu and
                                 orcdotacao.o58_funcao = orcparamfunc.o45_func
                 where orcparamrel.o42_codparrel = $relatorio";

         if ($parametro != ""){
              $sql .= " and orcparamseq.o69_codseq = $parametro";
         }
    
         $r = @db_query($sql);
         $rows = @pg_numrows($r);
    }
/*
    if ($parametro == 19){
         echo $sql; exit;
    }
*/

    if ($rows > 0 ) {
      for($x=0;$x < pg_numrows($r);$x++){
        $matriz[$x] = pg_result($r,$x,"o44_subfunc"); 
      }  
    }

//    print_r($matriz); exit;
    return $matriz;
  }
}
?>
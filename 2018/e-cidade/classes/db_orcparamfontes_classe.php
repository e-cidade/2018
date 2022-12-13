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
//CLASSE DA ENTIDADE orcparamfontes
class cl_orcparamfontes { 
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
   var $o43_anousu = 0; 
   var $o43_codparrel = 0; 
   var $o43_codfon = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o43_anousu = int4 = Exercício 
                 o43_codparrel = int4 = Código Relatório 
                 o43_codfon = int4 = Código Fonte 
                 ";
   //funcao construtor da classe 
   function cl_orcparamfontes() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamfontes"); 
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
       $this->o43_anousu = ($this->o43_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o43_anousu"]:$this->o43_anousu);
       $this->o43_codparrel = ($this->o43_codparrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o43_codparrel"]:$this->o43_codparrel);
       $this->o43_codfon = ($this->o43_codfon == ""?@$GLOBALS["HTTP_POST_VARS"]["o43_codfon"]:$this->o43_codfon);
     }else{
       $this->o43_anousu = ($this->o43_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o43_anousu"]:$this->o43_anousu);
       $this->o43_codparrel = ($this->o43_codparrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o43_codparrel"]:$this->o43_codparrel);
       $this->o43_codfon = ($this->o43_codfon == ""?@$GLOBALS["HTTP_POST_VARS"]["o43_codfon"]:$this->o43_codfon);
     }
   }
   // funcao para inclusao
   function incluir ($o43_anousu,$o43_codparrel,$o43_codfon){ 
      $this->atualizacampos();
       $this->o43_anousu = $o43_anousu; 
       $this->o43_codparrel = $o43_codparrel; 
       $this->o43_codfon = $o43_codfon; 
     if(($this->o43_anousu == null) || ($this->o43_anousu == "") ){ 
       $this->erro_sql = " Campo o43_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o43_codparrel == null) || ($this->o43_codparrel == "") ){ 
       $this->erro_sql = " Campo o43_codparrel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o43_codfon == null) || ($this->o43_codfon == "") ){ 
       $this->erro_sql = " Campo o43_codfon nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamfontes(
                                       o43_anousu 
                                      ,o43_codparrel 
                                      ,o43_codfon 
                       )
                values (
                                $this->o43_anousu 
                               ,$this->o43_codparrel 
                               ,$this->o43_codfon 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros para Relatórios - Fontes Receita ($this->o43_anousu."-".$this->o43_codparrel."-".$this->o43_codfon) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros para Relatórios - Fontes Receita já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros para Relatórios - Fontes Receita ($this->o43_anousu."-".$this->o43_codparrel."-".$this->o43_codfon) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o43_anousu."-".$this->o43_codparrel."-".$this->o43_codfon;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o43_anousu,$this->o43_codparrel,$this->o43_codfon));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5712,'$this->o43_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,5707,'$this->o43_codparrel','I')");
       $resac = db_query("insert into db_acountkey values($acount,5708,'$this->o43_codfon','I')");
       $resac = db_query("insert into db_acount values($acount,902,5712,'','".AddSlashes(pg_result($resaco,0,'o43_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,902,5707,'','".AddSlashes(pg_result($resaco,0,'o43_codparrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,902,5708,'','".AddSlashes(pg_result($resaco,0,'o43_codfon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o43_anousu=null,$o43_codparrel=null,$o43_codfon=null) { 
      $this->atualizacampos();
     $sql = " update orcparamfontes set ";
     $virgula = "";
     if(trim($this->o43_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o43_anousu"])){ 
       $sql  .= $virgula." o43_anousu = $this->o43_anousu ";
       $virgula = ",";
       if(trim($this->o43_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o43_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o43_codparrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o43_codparrel"])){ 
       $sql  .= $virgula." o43_codparrel = $this->o43_codparrel ";
       $virgula = ",";
       if(trim($this->o43_codparrel) == null ){ 
         $this->erro_sql = " Campo Código Relatório nao Informado.";
         $this->erro_campo = "o43_codparrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o43_codfon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o43_codfon"])){ 
       $sql  .= $virgula." o43_codfon = $this->o43_codfon ";
       $virgula = ",";
       if(trim($this->o43_codfon) == null ){ 
         $this->erro_sql = " Campo Código Fonte nao Informado.";
         $this->erro_campo = "o43_codfon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o43_anousu!=null){
       $sql .= " o43_anousu = $this->o43_anousu";
     }
     if($o43_codparrel!=null){
       $sql .= " and  o43_codparrel = $this->o43_codparrel";
     }
     if($o43_codfon!=null){
       $sql .= " and  o43_codfon = $this->o43_codfon";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o43_anousu,$this->o43_codparrel,$this->o43_codfon));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5712,'$this->o43_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,5707,'$this->o43_codparrel','A')");
         $resac = db_query("insert into db_acountkey values($acount,5708,'$this->o43_codfon','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o43_anousu"]))
           $resac = db_query("insert into db_acount values($acount,902,5712,'".AddSlashes(pg_result($resaco,$conresaco,'o43_anousu'))."','$this->o43_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o43_codparrel"]))
           $resac = db_query("insert into db_acount values($acount,902,5707,'".AddSlashes(pg_result($resaco,$conresaco,'o43_codparrel'))."','$this->o43_codparrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o43_codfon"]))
           $resac = db_query("insert into db_acount values($acount,902,5708,'".AddSlashes(pg_result($resaco,$conresaco,'o43_codfon'))."','$this->o43_codfon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros para Relatórios - Fontes Receita nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o43_anousu."-".$this->o43_codparrel."-".$this->o43_codfon;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros para Relatórios - Fontes Receita nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o43_anousu."-".$this->o43_codparrel."-".$this->o43_codfon;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o43_anousu."-".$this->o43_codparrel."-".$this->o43_codfon;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o43_anousu=null,$o43_codparrel=null,$o43_codfon=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o43_anousu,$o43_codparrel,$o43_codfon));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5712,'$o43_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,5707,'$o43_codparrel','E')");
         $resac = db_query("insert into db_acountkey values($acount,5708,'$o43_codfon','E')");
         $resac = db_query("insert into db_acount values($acount,902,5712,'','".AddSlashes(pg_result($resaco,$iresaco,'o43_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,902,5707,'','".AddSlashes(pg_result($resaco,$iresaco,'o43_codparrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,902,5708,'','".AddSlashes(pg_result($resaco,$iresaco,'o43_codfon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamfontes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o43_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o43_anousu = $o43_anousu ";
        }
        if($o43_codparrel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o43_codparrel = $o43_codparrel ";
        }
        if($o43_codfon != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o43_codfon = $o43_codfon ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros para Relatórios - Fontes Receita nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o43_anousu."-".$o43_codparrel."-".$o43_codfon;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros para Relatórios - Fontes Receita nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o43_anousu."-".$o43_codparrel."-".$o43_codfon;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o43_anousu."-".$o43_codparrel."-".$o43_codfon;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamfontes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o43_anousu=null,$o43_codparrel=null,$o43_codfon=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamfontes ";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcparamfontes.o43_codfon and orcfontes.o57_codfon = ".db_getsession("DB_anousu");
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = orcparamfontes.o43_codparrel";
     $sql2 = "";
     if($dbwhere==""){
       if($o43_anousu!=null ){
         $sql2 .= " where orcparamfontes.o43_anousu = $o43_anousu "; 
       } 
       if($o43_codparrel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamfontes.o43_codparrel = $o43_codparrel "; 
       } 
       if($o43_codfon!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamfontes.o43_codfon = $o43_codfon "; 
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
   function sql_query_file ( $o43_anousu=null,$o43_codparrel=null,$o43_codfon=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamfontes ";
     $sql2 = "";
     if($dbwhere==""){
       if($o43_anousu!=null ){
         $sql2 .= " where orcparamfontes.o43_anousu = $o43_anousu "; 
       } 
       if($o43_codparrel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamfontes.o43_codparrel = $o43_codparrel "; 
       } 
       if($o43_codfon!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcparamfontes.o43_codfon = $o43_codfon "; 
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